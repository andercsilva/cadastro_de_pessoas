<?php
/**
* Arquivo responsável por iniciar a aplicação incluindo os arquivos necessários
* @package Cadastro de Pessoas
* @category includes
* @name init
* @author Anderson Silva <anderson@haarieh.com>
* @copyright Haarieh Ltda ME
* @since 01-12-2015
**/

//Inclui o arquivo de configurações
include __APP_PATH . '/includes/Config.php';

//Inclui o arquivo Crud responsável pelas conexões com a base
include __APP_PATH . '/includes/Crud.php';

//Inclui o arquivo Model Base onde seta os gets e sets dinâmicos
include __APP_PATH . '/includes/Model.php';

//Inclui o arquivo Active
include __APP_PATH . '/includes/Active.php';

//Inclui o arquivo controller base
include __APP_PATH . '/includes/Controller.php';

//Inclui o arquivo View onde chama os templates
include __APP_PATH . '/includes/View.php';

//Função que vai carregar automaticamente os Models e Controllers
function __autoload($class_name) {
	//Converte o nome da classe para minúscula e completa com o nome do arquivo
	$files[] = __APP_PATH . '/model/' . $class_name . '.php';
	$files[] = __APP_PATH . '/controller/' . $class_name . '.php';

	//Se o arquivo não existir retorna falso
	foreach($files as $file)
		//Inclui o Model caso tenha encontrado o arquivo
		if (file_exists($file)) include ($file);
}

?>