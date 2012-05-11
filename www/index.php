<?php

define('ACCESS', TRUE);

define('DEVELOPER', TRUE);

define('EXT', '.php');

define('LIB_PATH', realpath('..'.DIRECTORY_SEPARATOR.'web'));

define('CLASSES_PATH', LIB_PATH.DIRECTORY_SEPARATOR.'classes');

define('CONFIG_PATH', LIB_PATH.DIRECTORY_SEPARATOR.'config');

define('TEMPLATE_PATH', LIB_PATH.DIRECTORY_SEPARATOR.'templates');

require_once LIB_PATH.DIRECTORY_SEPARATOR.'initialization.php';

try 
{
	$app = new Application();
	$app->run();
}
catch (Exception $e)
{
	if (defined('DEVELOPER') AND DEVELOPER)
	{
		echo $e->getMessage();
		echo '<pre>'.$e->getTraceAsString().'</pre>';
	}
	else
	{
		echo 'Ошибка!';
	}
}

?>