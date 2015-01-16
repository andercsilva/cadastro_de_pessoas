<?php
/**
* Arquivo responsável por controlar as ações do sistema
* @package Cadastro de Pessoas
* @category controller
* @name Controller
* @author Anderson Silva <anderson@haarieh.com>
* @copyright Haarieh Ltda ME
* @since 01-12-2015
**/
class Controller
{
    //Atributos da classe
    protected $_model;
    protected $_controller;
    protected $_action;
    protected $_view;
    protected $_js;
    protected $_url;
    protected $_modelBaseName;

    /**
     * Método contrutor do controller onde inicia os atributos da classe
     * @param Model
     * @param string
     * @return void
     **/
    public function __construct($model, $action)
    {
        $this->_controller = ucwords(__CLASS__);
        $this->_action = $action;
        $this->_modelBaseName = $model;
       // $this->_setModel($model);
        $this->setJs($action);
        $this->setView($action);
        
    }

    /**
     * Método estático para iniciar o controller
     * @access public
     * @return void
     **/
    public static function run(){
        //Define o controller default
        $controller = CONTROLLER_DEFAULT;
        //Index é a ação padrão
        $action = "index";
        //Por padrão o ID é vazio
        $id = null;
        if (isset($_GET['url']))
        {
            $url = $_GET['url'];
            $params = array();
            $params = explode("/", $url);
            //O primeiro parametro é o controller
            $controller = ucwords($params[0]);
            //Segundo a ação
            if (isset($params[1]) && !empty($params[1]))
                $action = $params[1];
            //Terceiro o id caso possua
            if (isset($params[2]) && !empty($params[2])) {
                $id = (int) $params[2];
            }
        }
        //Seta o Model;
        $modelName = $controller;
        //Define o nome do Controller
        $controller .= 'Controller';
        //Instancia o Controller do Model
        $load = new $controller($modelName, $action);
        //Caso exista a ação ele executa
        if (method_exists($load, $action))
            $load->$action($id);
        else
            die('Método inválido. Verifique a URL digitada.');
    }
    /**
     * Método para setar o model
     * @access public
     * @return void
     **/
    protected function setModel($modelName)
    {
        $this->_model = new $modelName();
    }

    /**
     * Método para preparar a view
     * @access public
     * @return void
     **/
    protected function setView($viewName)
    {
        $this->_view = new View(__APP_PATH . '/views/' . strtolower($this->_modelBaseName) . '/' . $viewName . '.php', $this->_js);
    }

    /**
     * Método para setar o JavaScript do template caso exista o arquivo na pasta
     * @access public
     * @return void
     **/
    protected function setJs($jsName)
    {
        $jsFile = __APP_PATH . '/../public/js/viewJs/' . strtolower($this->_modelBaseName) . '/' . $jsName . '.js';
        $js = '/js/viewJs/' . strtolower($this->_modelBaseName) . '/' . $jsName . '.js';
        if(file_exists($jsFile))
            $this->_js = $js;
    }

}

?>
