<?php
/**
* Arquivo Index onde inicia a aplicação
* @package Cadastro de Pessoas
* @category index
* @name index
* @author Anderson Silva <anderson@haarieh.com>
* @copyright Haarieh Ltda ME
* @since 01-12-2015
*/
//Define se quer debugar ou não
$debug = true;

//Define se aparece os erros ou não
if ($debug) {
	ini_set('display_errors',$debug);
	ini_set('display_startup_erros',$debug);
	error_reporting(E_ALL);
}

//Define o caminho do aplicativo
$app_path = realpath(dirname(__FILE__) . '/../app');
define ('__APP_PATH', $app_path);

//Inclui o arquivo init
include __APP_PATH . '/includes/init.php';

Controller::run();

?>