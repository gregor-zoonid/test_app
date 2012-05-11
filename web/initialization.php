<?php
defined('ACCESS') OR die('Access denied');

// Include system classes
$path = CLASSES_PATH.DIRECTORY_SEPARATOR.'system';
if (is_dir($path)) 
{
	$dir = dir($path);
	while ($line = $dir->read())
	{
		if (($line == '.') OR ($line == '..'))
			continue;
		include $path.DIRECTORY_SEPARATOR.$line;
	}
}


// Include users classes
$path = CLASSES_PATH.DIRECTORY_SEPARATOR.'controller';
if (is_dir($path)) 
{
	$dir = dir($path);
	while ($line = $dir->read())
	{
		if (($line == '.') OR ($line == '..'))
			continue;
		include $path.DIRECTORY_SEPARATOR.$line;
	}
}

function uncatched_exception($e)
{
	if (defined('DEVELOPER') AND DEVELOPER)
	{
		echo "Uncatched exception <br>\r\n";
		echo $e->getMessage();
		echo '<pre>'.$e->getTraceAsString().'</pre>';
	}
	else
	{
		echo 'Ошибка!';
	}
}

set_exception_handler('uncatched_exception');

session_start();
?>