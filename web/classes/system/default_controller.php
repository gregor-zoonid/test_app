<?php
class Default_Controller
{
	public $template;
	
	private $_data = array();
	
	public function __construct()
	{
		echo "Default_Controller подключен<br>\r\n";
	}
	
	public function set($name, $value = NULL)
	{
		if (empty($name))
			return;
			
		if ($value === NULL)
			return;
		
		$this->_data[$name] = $value;
	}
	
	public function get_data()
	{
		return $this->_data;
	}
	
}
?>