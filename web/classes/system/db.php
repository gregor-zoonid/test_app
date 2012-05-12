<?php
defined('ACCESS') OR die('Access denied');

class DB
{
	private $host;

	private $name;

	private $user;

	private $pass;

	private $type;

	private $persistent;

	private $conline;

	private static $_db = NULL;

	public function __construct()
	{
		$conf = Application::get_config('database');

		$this->host = $conf['db_host'];

		$this->name = $conf['db_name'];

		$this->user = $conf['db_user'];

		$this->pass = $conf['db_pass'];

		$this->type = $conf['db_type'];

		$this->persistent = (bool) $conf['db_persistent'];

		$this->conline = $this->type.':host='.$this->host.';dbname='.$this->name;

		$options = array(
			PDO::ATTR_PERSISTENT	=>	$this->persistent,
			PDO :: MYSQL_ATTR_INIT_COMMAND => 'SET NAMES `utf8`'
		);

		try
		{
			DB::$_db = new PDO($this->conline, $this->user, $this->pass, $options);
		}
		catch (PDOException $e)
		{
			throw $e;
		}

	}

	public function __call($name, $args)
	{
		try
		{
			switch (count($args))
			{
				case 0	:
						$return = DB::$_db->$name();
					break;
				case 1	:
						$return = DB::$_db->$name($args[0]);
					break;
				case 2	:
						$return = DB::$_db->$name($args[0], $args[1]);
					break;
				default:
					return NULL;
			}
		}
		catch (PDOException $e)
		{
		    throw $e;
		}

		return $return;
	}

}

?>