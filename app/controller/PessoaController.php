<?php
/**
* Classe onde define as ações do cadastro de pessoas
* @package cadastro_de_pessoas
* @category controller
* @name PessoaController
* @author Anderson Silva <anderson@haarieh.com>
* @copyright Haarieh Ltda ME
* @since 01-16-2015
*/
class PessoaController extends Controller {

    /**
     * Método ação da home, primeira página default
     * @access public
     * @return string
     **/
    public function index() {        
        $model = new Pessoa();
        $titulo = 'Cadastro de Pessoas';
        return $this->_view->render(
            array(
                'title' => $titulo,
                'model' => $model,
                'dataprovider' => $model->search()
            )
        );
    }

    /**
     * Método para buscar na base
     * @access public
     * @return string json
     **/
    public function search($page) 
    {
       
        $model = new Pessoa();
        if($_POST) $model->setAttributes($_POST['pessoa']);
        $dataprovider = $model->search($page);
        if(isset($dataprovider['data'])) {
            //Prepara o retorno para Json
            foreach($dataprovider['data'] as $valor) {
                $retorno['data'][] = $valor->getAttributes();
                $retorno['pagina'] = $page;
                $retorno['paginate'] = $dataprovider['paginate'];
            }
            echo json_encode($retorno);
        }
    }

    /**
     * Método ação para editar um registro
     * @access public
     * @return string json com os atributos
     **/
    public function edit($id) 
    {
        $model = new Pessoa();
        if($id) $model->findByPk($id);

        if($_POST) {
            $model->setAttributes($_POST['pessoa']);
            $model->save();
            if($id) 
                $retorno['mensagem'] = 'Edição salva com sucesso!';
            else 
                $retorno['mensagem'] = 'Cadastro salvo com sucesso!';
        }
        $retorno['data'] = $model->getAttributes();
        echo json_encode($retorno);
        
    }

    /**
     * Método para visualizar um registro
     * @access public
     * @return strin json com os atributos
     **/
    public function view($id) {
        $model = new Pessoa();
        if($id) $model->findByPk($id);
        echo json_encode($model->getAttributes());        
    }

    /**
     * Método ação que deleta a informação
     * @access public
     * @return strin json com os atributos
     **/
    public function delete($id) {        
        $model = $this->loadModel($id);    
        $model->delete(array('id' => $id));
        echo 'Pessoa excluída com sucesso!';
    }

    /**
     * Método que retorna o model pela chave primaria
     * @access public
     * @param integer
     * @return Model
     **/
    public function loadModel($id)
    {
      if($this->_model===null)
      {
        $model = new Pessoa();        
        $this->_model = $model->findbyPk($id);
        if($this->_model===null)
          throw new Exception("Página não encontrada");
      }
      return $this->_model;
    }

}

?>
