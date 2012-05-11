<?php
defined('ACCESS') OR die('Access denied');

return array(
	'blog'		=> array(
			'pattern'	=>	'/(<controller>/(<id>))',
			'regex'		=>	array(
			),

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