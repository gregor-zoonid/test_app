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

		try
		{
			$this->set('items', Application::$db->query('SELECT * FROM `blog` ORDER BY `create` DESC')
						->fetchAll());
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	public function edit()
	{
		if ( ! Application::$is_auth)
			die ('Авторизуйтесь');

		if (isset($_POST['save']))
		{
			if ($_POST['id'] == 'new')
			{
				$sql = 'INSERT INTO `blog` (`title`, `text`) VALUES (:title, :text)';
				$values = array(
					':title' => $_POST['title'],
					':text' => $_POST['text'],
				);
			}
			else
			{
				$sql = 'UPDATE `blog` SET `title` = :title, `text` = :text, `create` = :craete WHERE `id` = :id';
				$values = array(
					':title' => $_POST['title'],
					':text' => $_POST['text'],
					':craete' => date('Y-m-d H:i:s'),
					':id' => $_POST['id'],
				);
			}

			try
			{
				$statement = Application::$db->prepare($sql);
				$statement->execute($values);
			}
			catch (Exception $e)
			{
				throw $e;
			}

			header('Location: /');
			die;
		}

		$this->template = 'default/edit';
		$id = Application::$route_params['id'];

		if ($id == 'new')
		{
			$item = array(
				'id'	=>	$id,
				'title'	=>	'',
				'text'	=>	'',
			);
			$title = "Добавление новой записи";
		}
		else
		{
			try
			{
				$statement = Application::$db->prepare('SELECT * FROM `blog` WHERE `id`=:id');
				$statement->execute(array( ':id' => $id ));
				$item = $statement->fetch();
			}
			catch (Exception $e)
			{
				throw $e;
			}

			$title = "Редактирование записи c id = {$id}";
		}

		$this->set('title', $title)
			 ->set('item', $item);
	}

	public function delete()
	{
		if ( ! Application::$is_auth)
			die ('Авторизуйтесь');

		$id = Application::$route_params['id'];

		if ( ! empty($id) )
		{
			try
			{
				$statement = Application::$db->prepare('DELETE FROM `blog` WHERE `id`=:id');
				$statement->execute(array( ':id' => $id ));
			}
			catch (Exception $e)
			{
				throw $e;
			}
		}

		header('Location: /');
		die;
	}
}
?>