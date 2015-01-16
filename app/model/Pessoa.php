<?php
/**
* Classe Pessoa extendida do Model
* @package cadastro_de_pessoas
* @category model
* @name Pessoa
* @author Anderson Silva <anderson@haarieh.com>
* @copyright Haarieh Ltda ME
* @since 01-12-2015
*/ 
class Pessoa extends Active {

	/**
	 * Construtor do Model
	 * @access public
	 * @return void
	 **/
	public function __construct() {
		//Seta a chave primária
		$this->setPrimary('id');
		parent::__construct();
	}

	/**
	 * Método que retorna o nome da tabela
	 * @return string
	 */
	public function tableName()
	{
		return 'pessoas';
	}

	/**
	 * Método que define os nomes dos campos
	 * @access public
	 * @return array
	 **/
	public function labels() {
		return array(
			'id' => 'Id',
			'nome' => 'Nome',
			'sobrenome' => 'Sobrenome',
			'endereco' => 'Endereço'
		);
	}
	
	/**
	 * Método que faz a busca pelos campos setados no Model
	 * @access public
	 * @return array
	 * @param integer pagina
	 **/
	public function search($page = 1) {
		$condicao = array();
		$atributos = $this->getAttributes();
		//Verifica se o atributo está setado e adiciona na busca
		if(!empty($atributos['id'])) $condicao['id'] = array($this->id,'=');
		if(!empty($atributos['nome'])) $condicao['nome'] = array("%{$this->nome}%",'like');
		if(!empty($atributos['sobrenome'])) $condicao['sobrenome'] = array("%{$this->sobrenome}%",'like');
		if(!empty($atributos['endereco'])) $condicao['endereco'] = array("%{$this->endereco}%",'like');		
		return $this->findAll($condicao, $page, 'ORDER BY id DESC');
	}


	
}