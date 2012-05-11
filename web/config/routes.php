<?php
defined('ACCESS') OR die('Access denied');

return array(
	'blog'		=> array(
			'pattern'	=>	'/(<method>/(<id>))(?<params>)',

			'default'	=>	array(
				'controller'	=>	'blog',
				'method'		=>	'index',
			),
			
	),
	
	'default'		=> array(
			'pattern'	=>	'<controller>/<method>',
	),
);
?>