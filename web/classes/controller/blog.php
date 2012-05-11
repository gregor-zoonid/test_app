<?php
class Blog_Controller extends Default_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->template = 'default/blog';
		
//		echo "Blog_Controller подключен<br>\r\n";
	}
	
	public function index()
	{
		if ( isset($_GET['auth']))
		{
			Application::$auth = TRUE;
		}
		$this->set('items', Application::$db->query('SELECT * FROM `blog` ORDER BY `create` DESC')
						->fetchAll());
	}
	
	public function edit()
	{
		Application::$auth = TRUE;
		
		$this->template = 'default/edit';
		
		$id = Application::$route_params['id'];
	}
	
	public function delete()
	{
		Application::$auth = TRUE;
		
		$id = Application::$route_params['id'];
		
		echo "Delete $id";
		die;
	}
}
?>