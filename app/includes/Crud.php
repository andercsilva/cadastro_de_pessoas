<?php
/**
* Classe Abstrata Crud responsável pelo insert, update, delete e select
* @package cadastro_de_pessoas
* @category includes
* @name Crud
* @author Anderson Silva <anderson@haarieh.com>
* @copyright Haarieh Ltda ME
* @since 01-15-2015
*/
abstract class Crud
{
    //Atributo que mantem a conexão com a base
    private $pdo;
    //Atributo onde será guardado o nome da tabela
    private $tabela = null;

    /**
     * Método construtor responsável pela conexão com a base
     * @access public
     * @return void
     **/
    public function __construct()
    {
      if (!$this->pdo)
      {
        try
        {
          $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';';
          $this->pdo = new PDO($dsn, DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
          $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
          $this->tabela = $this->tableName();
        } catch (PDOException $e) {
          die('Connection error: ' . $e->getMessage());
        }
      }
    }

    /**
     * Método privado para construção da instrução SQL de INSERT
     * @param $arrayDados = Array de dados contendo colunas e valores
     * @return String contendo instrução SQL
     **/
    private function buildInsert($arrayDados){
      // Inicializa variáveis
      $sql = "";
      $campos = "";
      $valores = "";

      // Loop para montar a instrução com os campos e valores
      foreach($arrayDados as $chave => $valor){
        $campos .= $chave . ', ';
        $valores .= '?, ';
      }
      // Retira vírgula do final da string
      $campos = (substr($campos, -2) == ', ') ? trim(substr($campos, 0, (strlen($campos) - 2))) : $campos ;
      // Retira vírgula do final da string
      $valores = (substr($valores, -2) == ', ') ? trim(substr($valores, 0, (strlen($valores) - 2))) : $valores ;
      // Concatena todas as variáveis e finaliza a instrução
      $sql .= "INSERT INTO {$this->tabela} (" . $campos . ")VALUES(" . $valores . ")";
      // Retorna string com instrução SQL
      return trim($sql);
    }

    /**
     * Método privado para construção da instrução SQL de UPDATE
     * @param $arrayDados = Array de dados contendo colunas, operadores e valores
     * @param $arrayCondicao = Array de dados contendo colunas e valores para condição WHERE
     * @return String contendo instrução SQL
     **/
    private function buildUpdate($arrayDados, $arrayCondicao){

      // Inicializa variáveis
      $sql = "";
      $valCampos = "";
      $valCondicao = "";

      // Loop para montar a instrução com os campos e valores
      foreach($arrayDados as $chave => $valor) {
        $valCampos .= $chave . '=?, ';
      }
      // Loop para montar a condição WHERE
      foreach($arrayCondicao as $chave => $valor) {
        $valCondicao .= $chave . '=? AND ';
      }
      // Retira vírgula do final da string
      $valCampos = (substr($valCampos, -2) == ', ') ? trim(substr($valCampos, 0, (strlen($valCampos) - 2))) : $valCampos ;
      // Retira vírgula do final da string
      $valCondicao = (substr($valCondicao, -4) == 'AND ') ? trim(substr($valCondicao, 0, (strlen($valCondicao) - 4))) : $valCondicao ;
      // Concatena todas as variáveis e finaliza a instrução
      $sql .= "UPDATE {$this->tabela} SET " . $valCampos . " WHERE " . $valCondicao;
      // Retorna string com instrução SQL
      return trim($sql);
    }

    /**
     * Método privado para construção da instrução SQL de DELETE
     * @param $arrayCondicao = Array de dados contendo colunas, operadores e valores para condição WHERE
     * @return String contendo instrução SQL
     **/
    private function buildDelete($arrayCondicao){
      // Inicializa variáveis
      $sql = "";
      $valCampos= "";
      // Loop para montar a instrução com os campos e valores
      foreach($arrayCondicao as $chave => $valor) {
        $valCampos .= $chave . '=? AND ';
      }
      // Retira a palavra AND do final da string
      $valCampos = (substr($valCampos, -4) == 'AND ') ? trim(substr($valCampos, 0, (strlen($valCampos) - 4))) : $valCampos ;
      // Concatena todas as variáveis e finaliza a instrução
      $sql .= "DELETE FROM {$this->tabela} WHERE " . $valCampos;
      // Retorna string com instrução SQL
      return trim($sql);
    }

    /**
     * Método público para inserir os dados na tabela
     * @param $arrayDados = Array de dados contendo colunas e valores
     * @return Retorna resultado booleano da instrução SQL
     **/
    public function insert($arrayDados){      
      try {
        // Atribui a instrução SQL construida no método
        $sql = $this->buildInsert($arrayDados);
        // Passa a instrução para o PDO
        $stm = $this->pdo->prepare($sql);
        // Loop para passar os dados como parâmetro
        $cont = 1;
        foreach ($arrayDados as $valor) {
          $stm->bindValue($cont, $valor);
          $cont++;
        }
        // Executa a instrução SQL e captura o retorno
        if($stm->execute()) {
          return $this->pdo->lastInsertId();
        }
      } catch (PDOException $e) {
        throw new Exception("Erro: " . $e->getMessage());
      }
    }

    /**
     * Método público para atualizar os dados na tabela
     * @param $arrayDados = Array de dados contendo colunas e valores
     * @param $arrayCondicao = Array de dados contendo colunas e valores para condição WHERE - Exemplo array('$id='=>1)
     * @return Retorna resultado booleano da instrução SQL
     **/
    public function update($arrayDados, $arrayCondicao){
      try {
        // Atribui a instrução SQL construida no método
        $sql = $this->buildUpdate($arrayDados, $arrayCondicao);
        // Passa a instrução para o PDO
        $stm = $this->pdo->prepare($sql);
        // Loop para passar os dados como parâmetro
        $cont = 1;
        foreach ($arrayDados as $valor){
          $stm->bindValue($cont, $valor);
          $cont++;
        }
        // Loop para passar os dados como parâmetro cláusula WHERE
        foreach ($arrayCondicao as $valor){
          $stm->bindValue($cont, $valor);
          $cont++;
        }
        // Executa a instrução SQL e captura o retorno
        $retorno = $stm->execute();
        return $retorno;
      } catch (PDOException $e) {
        throw new Exception("Erro: " . $e->getMessage());
      }
    }

    /*
    * Método público para excluir os dados na tabela
    * @param $arrayCondicao = Array de dados contendo colunas e valores para condição WHERE - Exemplo array('$id='=>1)
    * @return Retorna resultado booleano da instrução SQL
    */
    public function delete($arrayCondicao){
      try {
        // Atribui a instrução SQL construida no método
        $sql = $this->buildDelete($arrayCondicao);
        // Passa a instrução para o PDO
        $stm = $this->pdo->prepare($sql);
        // Loop para passar os dados como parâmetro cláusula WHERE
        $cont = 1;
        foreach ($arrayCondicao as $valor){
          $stm->bindValue($cont, $valor);
          $cont++;
        }
        // Executa a instrução SQL e captura o retorno
        $retorno = $stm->execute();
        return $retorno;
      } catch (PDOException $e) {
        throw new Exception("Erro: " . $e->getMessage());
      }
    }

    /**
     * Método genérico para executar instruções de consulta independente do nome da tabela passada no _construct
     * @param $sql = Instrução SQL inteira contendo, nome das tabelas envolvidas, JOINS, WHERE, ORDER BY, GROUP BY e LIMIT
     * @param $arrayParam = Array contendo somente os parâmetros necessários para clásusla WHERE
     * @param $fetchAll  = Valor booleano com valor default TRUE indicando que serão retornadas várias linhas, FALSE retorna apenas a primeira linha
     * @return Retorna array de dados da consulta em forma de objetos
     **/
    public function getSQLGeneric($sql, $arrayParams=null, $fetchAll=true){
      try {
        // Passa a instrução para o PDO
        $stm = $this->pdo->prepare($sql);
        // Verifica se existem condições para carregar os parâmetros
        if (!empty($arrayParams)) {
          // Loop para passar os dados como parâmetro cláusula WHERE
          $cont = 1;
          foreach ($arrayParams as $valor) {
            $stm->bindValue($cont, $valor);
            $cont++;
          }
        }
        // Executa a instrução SQL
        $stm->execute();
        // Verifica se é necessário retornar várias linhas
        if($fetchAll)
          $dados = $stm->fetchAll(PDO::FETCH_OBJ);
        else
          $dados = $stm->fetch();
        return $dados;

      } catch (PDOException $e) {
        throw new Exception("Erro: " . $e->getMessage());
      }
    }

    /**
     * Método que retorna a conexão PDO
     * @access public
     * @return PDO
     **/
    public function getPdo() {
      return $this->pdo;
    }
}
?>
