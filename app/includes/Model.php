<?php
/**
* Classe abstrata Model é a classe base fornecendo as características comuns necessárias por objetos de modelo de dados.
* @package Cadastro de Pessoas
* @category includes
* @name Model
* @author Anderson Silva <anderson@haarieh.com>
* @copyright Haarieh Ltda ME
* @since 01-12-2015
**/
abstract class Model extends Crud {
	//Define o senário crud(create, read, update e delete)
	private $_scenario = '';
	//Define a chave primária da tabela
	private $_primary = null;

	/**
	 * Método que retorna o nome da tabela do banco de dados
	 * @access public
	 * @return string
	 **/
	public function tableName()
	{
		return get_class($this);
	}

	/**
	 * Método para definir as labels, padrão vazio
	 * @access public
	 * @return array
	 **/
	public function labels() {
		return array();
	}

	/**
	 * Método que seta a chave primária da tabela
	 * @return void
	 * @access public
	 **/
	public function setPrimary($primary) {
		$this->_primary = $primary;
	}
	/**
	 * Método para retornar o nome da Coluna
	 * @access public
	 * @return string
	 **/
	public function getLabel($column) {
		$labels = $this->labels();
		if(isset($labels[$column])) return $labels[$column];
	}

	/**
	 * Método que retorna a chave primária
	 * @return mixed
	 * @access public
	 **/
	public function getPrimary() {
		return $this->_primary;
	} 

	/**
	 * Seta o cenário atual no model
	 * @param string $value
	 */
	public function setScenario($scenario) {
		$this->_scenario = $scenario;
	}
	/**
	 * Retorna o cenário atual do model
	 * @access public
	 * @return mixed
	 **/
	public function getScenario() {
		return $this->_scenario;
	}

	/**
	 * Método responsável por salvar dependendo do cenário
	 * @access public
	 * @return Model
	 **/
	public function save() {
		$primary = $this->getPrimary();
		$attributes = $this->getAttributes();
		if($this->_scenario == 'create') {
			$id = $this->insert($attributes);
			if($id) {
				$this->setAttribute($primary,$id);
				$this->setScenario('update');
				return $this;
			}
		}
		if($this->_scenario == 'update') {
			$this->update($attributes, array($primary => $this->$primary) );
			return $this;
		}
	}

	/**
	 * Busca pela chave primária
	 * @access public
	 * @return Model
	 **/
	public function findByPk($id) {
		$attributes = $this->getAttributes();
		$primary = $this->getPrimary();
		if($attributes) {
			$campos = implode(',',array_keys($attributes));
			$sql = "SELECT {$campos} FROM {$this->tableName()} WHERE {$primary} = ?";  
			$arrayParam = array($primary => $id);  
			$dados = $this->getSQLGeneric($sql, $arrayParam, false); 
			$this->setScenario('update');
			$this->setAttributes($dados);
			return $this;
		}
	}

	public function findAll($condicao = array(), $page=1, $order = '') {
		//Declaração das variáveis
		$valCampos = '';		
		$countP = '';
		$paginate = '';
		$sql = '';		
		$retorno = array();
		$class = get_class($this);
		//Seta os atributos
		$attributes = $this->getAttributes();
		//Verifica se existe os atributos
		if($attributes) {
			//Seta a quantidade de registros por páginas vinda da configuração
			$per_page = PER_PAGE;
			//Define os campos vindos dos atributos
			$campos = implode(',',array_keys($attributes));
			
			if($condicao) {
				// Loop para montar a instrução com os campos e valores
				foreach($condicao as $chave => $valor) {
					$valCampos .= $chave . ' ' . $valor[1] .  ' ' . '? AND ';
				}
				$valCampos = ' WHERE ' . $valCampos;
			}	
	    	// Retira a palavra AND do final da string
      		$valCampos = (substr($valCampos, -4) == 'AND ') ? trim(substr($valCampos, 0, (strlen($valCampos) - 4))) : $valCampos ;
				
			$sql = "SELECT {$campos} FROM {$this->tableName()} {$valCampos} {$order} LIMIT ".(($page - 1) *  $per_page).", ".$per_page;

	        $stm = $this->getPdo()->prepare($sql);
	        if($condicao) {
        		$cont = 1;
        		foreach ($condicao as $valor) {
          			$stm->bindValue($cont, $valor[0]);
          			$cont++;
        		}
        	}
			$stm->execute();
			$rows = $stm ->fetchAll();			
			if($rows) {
				foreach($rows as $row) {
					//Inicia uma nova classe 
					$model = new $class();	
					//Seta os atributos						
					$model->setAttributes($row);
					//Seta o cenário
					$model->setScenario('update');
					//Adiciona na array de retorno o objeto
					$retorno['data'][] = $model;
				}
				
			}
			//Contar os registros
			$sql = "SELECT count(*) FROM {$this->tableName()} {$valCampos}";
			$stm = $this->getPdo()->prepare($sql);
	        if($condicao) {
        		$cont = 1;
        		foreach ($condicao as $valor) {
          			$stm->bindValue($cont, $valor[0]);
          			$cont++;
        		}
        	}			
			$stm->execute();
			$count = $stm->fetchColumn();
			$retorno['total'] = $count;
			$retorno['page'] = $page;
			$countP=(ceil($count/$per_page)) + 1;
 			$paginate .= "<div>";
 			$url = $class . '/search';
 			for($i=1;$i<$countP;$i++){
  				$active = ($i==$page) ? "green" : "";
  				$paginate .= "<a class='page $active' href='#' data-url='/$url/$i'>$i</a>";
 			}
 			$paginate .= "</div>";
 			$retorno['paginate'] = $paginate;
			return $retorno;	
		}		
	}
}
?>