<?php
defined('ACCESS') OR die('Access denied');

class Application 
{
	const REGEX_KEY     = '<([a-zA-Z0-9_]++)>';

	const REGEX_SEGMENT = '[^/.,;?\n]++';

	const REGEX_ESCAPE  = '[.\\+*?[^\\]${}=!|]';
	
	public static $template = 'default/index';
	
	public static $url;
	
	public static $route_params = array();
	
	public static $auth = FALSE;
	
	public static $is_auth = FALSE;
	
	public static $db;
	
	private static $_conf;
	
	public function __construct()
	{
		try
		{
			Application::$db = new DB();
		}
		catch (Exception $e)
		{
			throw $e;
		}
		
		if (isset($_SERVER['REDIRECT_URL']))
		{
			Application::$url = $_SERVER['REDIRECT_URL'];
		}
		else 
		{
			Application::$url = $_SERVER['REQUEST_URI'];
		}
		
		if ( ! $this->parse_url())
		{
			throw new Exception('Not found', 404);
		}
		
		if ($_SESSION['auth'])
		{
			Application::$is_auth = TRUE;
		}
		
	}
	
	public function run()
	{
		// подключаем нужный контроллер
		$controller_class = ucfirst(Application::$route_params['controller']).'_Controller';
		$method_name = Application::$route_params['method'];

		if ( ! class_exists($controller_class))
		{
			throw new Exception("Unable to load class: $controller_class");
		}
		
		$controller = new $controller_class();
		
		if ( ! method_exists($controller_class, $method_name))
		{
			throw new Exception("Method {$method_name} doesn't exist in {$controller_class} class");
		}

		$controller->$method_name();
		
		if ( ! Application::$is_auth AND Application::$auth AND $this->auth() )
		{
			Application::$is_auth = TRUE;
		}
		
		// подключаем вьюшку
		if ( ! empty($controller->template))
		{
			Application::$template = $controller->template;
		}
		Application::$template = TEMPLATE_PATH.DIRECTORY_SEPARATOR.Application::$template.EXT;
		
		if ( ! is_file(Application::$template))
		{
			// кидаем эксепшн, что нет такого шаблона
			throw new Exception("File not exist. Unable to load template: Application::$template");
		}
	
		$data = $controller->get_data();
		
		echo $this->render_output($data);
	}
	
	public static function get_config($name)
	{
		if (isset(Application::$_conf[$name]))
			return Application::$_conf[$name];

		return Application::$_conf[$name] = include CONFIG_PATH.DIRECTORY_SEPARATOR.$name.EXT;
	}
	
	public function render_output($_controller_data_array = array())
	{
		extract($_controller_data_array, EXTR_SKIP);
		ob_start();
		
		try
		{
			include Application::$template;
		}
		catch (Exception $e)
		{
			ob_end_clean();

			throw $e;
		}

		return ob_get_clean();
	}
	
	public static function get_uri($route, $params = NULL)
	{
		$routes = Application::get_config('routes');
		$route = $routes[$route];
		
		if ( ! isset($route['default']) OR ! is_array($route['default']))
		{
			$route['default'] = array();
		}
		
		if ($params === NULL)
		{
			$params = $route['default'];
		}
		else
		{
			$params = array_merge($route['default'], $params);
		}
		
		$uri_pattern = $route['pattern'];
		if (strpos($uri_pattern, '<') === FALSE AND strpos($uri_pattern, '(') === FALSE)
		{
			return $uri_pattern;
		}
		
		// optional route params, inside parentheses
		while (preg_match('#\([^()]++\)#', $uri_pattern, $match))
		{
			$search = $match[0];
			$replace = substr($match[0], 1, -1);
			while (preg_match('#'.Application::REGEX_KEY.'#', $replace, $match))
			{
				list($key, $param) = $match;
				if (isset($params[$param]))
				{
					$replace = str_replace($key, $params[$param], $replace);
				}
				else
				{
					$replace = '';
					break;
				}
			}
			
			$uri_pattern = str_replace($search, $replace, $uri_pattern);
		}
	
		// required route params
		while (preg_match('#'.Application::REGEX_KEY.'#', $uri_pattern, $match))
		{
			list($key, $param) = $match;

			if ( ! isset($params[$param]))
			{
				// кидаем эксепшн с инфой типа такого array('<param>' => $param))
			}

			$uri_pattern = str_replace($key, $params[$param], $uri_pattern);
		}
		
		return $uri_pattern;
	}
	
	protected function parse_url()
	{
		$routes = Application::get_config('routes');
		
		foreach ($routes as $name => $params)
		{
			$pattern = Application::_compile_route($params['pattern']);
			
			if ( ! preg_match($pattern, Application::$url, $matches))
				continue;
			
			if (isset($params['default']) AND is_array($params['default']))
			{
				Application::$route_params = $params['default'];
			}
			
			foreach ($matches as $key => $value)
			{
				if (is_int($key))
					continue;
	
				Application::$route_params[$key] = $value;
			}
			
			return TRUE;
		}
		
		return FALSE;
	}

	protected static function _compile_route($_pattern)
	{
		$regex = preg_replace('#'.Application::REGEX_ESCAPE.'#', '\\\\$0', $_pattern);
		
		if (strpos($regex, '(') !== FALSE)
		{
			$regex = str_replace(array('(', ')'), array('(?:', ')?'), $regex);
		}

		$regex = str_replace(array('<', '>'), array('(?P<', '>'.Application::REGEX_SEGMENT.')'), $regex);

		return '#^'.$regex.'$#uD';
	}
	
	private function auth()
	{
		if ( ! isset($_SERVER['PHP_AUTH_USER']) AND ! $_SESSION['auth']) {
			$relam = md5(session_id()."_edit");
			header('WWW-Authenticate: Basic realm="'.$relam.'"');
			header('HTTP/1.1 401 Unauthorized');
			die('Вам необходимо авторизоваться перед действием');
		} elseif ( ! $_SESSION['auth']) {
			$login = $_SERVER['PHP_AUTH_USER'];
			$pass = md5($_SERVER['PHP_AUTH_PW']);
			if ( ! $this->login($login, $pass))
			{
				die('Неверная пара логин/пароль');
			}
			$_SESSION['auth'] = TRUE;
		}
		return TRUE;
	}
	
	private function login ($login, $pass)
	{
		if (($login == 'admin') AND ($pass == md5('admin')))
			return TRUE;
		else
			return FALSE;
	}
}
?>