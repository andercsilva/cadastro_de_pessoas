<?php
/**
* Arquivo de configurações
* @package Cadastro de Pessoas
* @category includes
* @name Config
* @author Anderson Silva <anderson@haarieh.com>
* @copyright Haarieh Ltda ME
* @since 01-12-2015
**/
//Configuração da Base
define ('DB_HOST',  'localhost');
define ('DB_NAME',  'pessoas');
define ('DB_USER',  'root');
define ('DB_PASS',  '123');
//Configuração do Controller Padrão
define ('CONTROLLER_DEFAULT',  'Pessoa');
//Configuração da View Layout
define ('LAYOUT',  '/layout/index.php');
//Registro por página
define('PER_PAGE', 4);