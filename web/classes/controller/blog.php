<?php
class Blog_Controller extends Default_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->template = 'default/blog';
		
		echo "Blog_Controller подключен<br>\r\n";
	}
	
	public function run()
	{
		echo "Blog_Controller выполнен<br>\r\n";
	}
}
?>