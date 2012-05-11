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
	<h1><?php $title?></h1>
	<hr>
	<form method="post">
		<input type="hidden" name="title" value="" />
		<input type="text" name="title" value="" />
		<textarea name="text"></textarea>
		<input type="submit" name="save">
	</form>
</body>
</html>