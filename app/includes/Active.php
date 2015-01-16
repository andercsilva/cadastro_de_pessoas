<?php
/**
* Classe Active responsável pela conexão com a base e definir
* os attributos do Model Active
* @package cadastro_de_pessoas
* @category includes
* @name Active
* @author Anderson Silva <anderson@haarieh.com>
* @copyright Haarieh Ltda ME
* @since 01-12-2015
*/
abstract class Active extends Model {
	//Define os atributos do modelo
	private $_attributes = array();
	//É novo registro ou não;
	private $_new;

	/**
	 * Método construtor onde vai definir o cenário CRUD
	 * @access public
	 **/
	public function __construct($scenario = 'create') {
		//Caso o cenário for nulo retorna
		if($scenario===null) return;		
		//Inicia o Crud chamando o construtor
		parent::__construct();
		//Seta os atributos buscando os campos na base
		$this->setColumnNamesInAttributes();
		//Define o senário padrão
		$this->setScenario($scenario);
		//Define que é um novo registro e não uma alteração
		$this->setIsNewRecord(true);

	}


	/**
	 * Método mágico que retorna os gets pelo nome do atributo
	 * @param string $name
	 * @return mixed propriedade
	 * @see getAttribute
	 */
	public function __get($name)
	{
		//Verifica se a chave existe no array $this->_attributes
		if(array_key_exists($name,$this->_attributes))
			return $this->_attributes[$name];
		else
			return null;
	}


	/**
	 * Método mágico que seta os atributos
	 * This method is overridden so that AR attributes can be accessed like properties.
	 * @param string $name property name
	 * @param mixed $value property value
	 */
	public function __set($name,$value)
	{
		//Caso o set attribute falhe, seta a propriedade da classe
		$this->setAttribute($name,$value);
	}

	/**
	 * Método que vai buscar na base os campos e adiciona como atributos da classe
	 * @return boolean
	 */
	public function setColumnNamesInAttributes(){
		$pdo = $this->getPdo();
		$q = $pdo->prepare("DESCRIBE ".$this->tableName());
		$q->execute();
		$columns = $q->fetchAll(PDO::FETCH_COLUMN);
		foreach($columns as $column) {
			$this->_attributes[$column] = null;
		}
		return true;
	}

	/**
	 * Returna o valor do atributo pelo nome
	 * @param string $name
	 * @return mixed
	 */
	public function getAttribute($name)
	{
		if(property_exists($this,$name))
			return $this->$name;
		elseif(isset($this->_attributes[$name])) {
			return $this->_attributes[$name];
		}
	}

	/**
	 * Seta o os atributos pelo nome e valor
	 * @param string
	 * @param mixed
	 * @return boolean
	 */
	public function setAttribute($name,$value)
	{
		if(property_exists($this,$name))
			$this->$name=$value;
		elseif(array_key_exists($name,$this->_attributes)) {
				$this->_attributes[$name]=$value;
		}
		else
			return false;
		return true;
	}

	/**
	 * Retorna todos os atributos ativos
	 * @access public
	 * @return array
	 **/
	public function getAttributes() {
		return $this->_attributes;
	}
	
	/**
	 * Método para setar os attributos
	 * @access public
	 * @return void
	 **/
	public function setAttributes($attributes) {
		$this->_attributes = $attributes;
	}

	/**
	 * Seta o atributo _new para true caso seja um novo registro
	 * @param boolean
	 */
	public function setIsNewRecord($value)
	{
		$this->_new=$value;
	}

	/**
	 * Retorna se o registro é novo ou não
	 * @return boolean
	 */
	public function getIsNewRecord()
	{
		return $this->_new;
	}

}
?>
