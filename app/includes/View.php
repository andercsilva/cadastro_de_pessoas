<?php
/**
* Classe View que responsável por buscar o template e renderizar
* @package Cadastro de Pessoas
* @category includes
* @name View
* @author Anderson Silva <anderson@haarieh.com>
* @copyright Haarieh Ltda ME
* @since 01-12-2015
*/
class View
{
    //Arquivo do template
    protected $_file;
    //Arquivo Javascript caso exista para cada template
    protected $_js;
    //Array que vai definir as variáveis dinâmicas
    protected $_attributes = array();
    //Atributo que define o layout
    protected $_layout = null;

     
    /**
     * Método Construtor
     * @access public
     */    
    public function __construct($file, $js)
    {
        $this->_file = $file;
        $this->_layout = __APP_PATH . '/views' . LAYOUT;
        $this->_js = $js;
    }
    
    /**
     * Método que vai sertar as variáveis dinâmicas no array
     * @access public
     * @return void
     */
    public function set($key, $value)
    {
        $this->_attributes[$key] = $value;
    }
    
    /**
     * Método que vai retornar a variável dinâmica setada no array
     * @access public
     * @return string
     */
    public function get($key) 
    {
        return $this->_attributes[$key];
    }

    public function renderParcial($attributes = array(), $print = true)
    {
        //Seta os atributos vindos pelo parametro
        if($attributes)
            foreach($attributes as $key=>$value)
                $this->set($key, $value);

        if (!file_exists($this->_file)) throw new Exception("Template " . $this->_file . " não existe.");
        //Seta o arquivo javascript caso exista
        $this->_attributes['js'] = $this->_js;
        //Importa variáveis para a tabela de símbolos a partir do array
        extract($this->_attributes);
        ob_start();
        include($this->_file);
        $content = ob_get_contents();
        ob_end_clean();
        if($print)
            echo $content;
        else
            return $content;      
    }    

    /**
     * Método que vai renderizar o template
     * @access public
     * @param array
     **/
    public function render($attributes = array(), $print = true)
    {
        $content = $this->renderParcial($attributes,false);
        $this->_attributes['content'] = $content;        
        extract($this->_attributes);
        ob_start();
        include($this->_layout);
        $layout = ob_get_contents();
        ob_end_clean();
        if($print)
            echo $layout;
        else
            return $layout;
    }



}