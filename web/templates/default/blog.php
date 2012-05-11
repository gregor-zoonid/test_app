<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>test_app</title>
	<link rel="stylesheet" type="text/css" href="/css/styles.css">
</head>
<body>
	<div>
	<?php if (Application::$is_auth):?>
		<a href="<?php echo Application::get_uri('blog', array( 'method' => 'edit', 'id' => 'new' )); ?>">Добавить пост</a>
	<?php else:?>
		<a href="<?php echo Application::get_uri('blog', array( 'params' => 'auth' )); ?>">Авторизоваться</a>
	<?php endif;?>
	</div>
	<hr>
	<h1>My blog</h1>
	<hr>
	<ul>
	<?php foreach ($items as $item): ?>
		<li>	
			<h2><?php echo $item['title'] ?>&nbsp;<span>(<?php echo $item['create'] ?>)</span></h2>
			<p><?php echo $item['text'] ?></p>
			<?php if (Application::$is_auth):?>
			<ul>
				<li><a href="<?php echo Application::get_uri('blog', array( 'method' => 'edit', 'id' => $item['id'] )); ?>">Редактировать</a></li>
				<li><a href="<?php echo Application::get_uri('blog', array( 'method' => 'delete', 'id' => $item['id'] )); ?>">Удалить</a></li>
			</ul>
			<?php endif;?>
		</li>	
	<?php endforeach; ?>
	</ul>
</body>
</html>