<?php $user = new User(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>
		<?php echo get_template_name().' - '.get_page_name(); ?>
	</title>
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap-datepicker3.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<style media="screen">
		.noPrint{ display: block; }
	</style>

	<style media="print">
		.noPrint{ display: none; }
	</style>
</head>

<body>
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="./"><?php echo get_page_name(); ?></a>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
				<ul class="nav navbar-nav navbar-right">
					<?php  $user = new User();
					if($user->isLoggedIn()) { ?>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<?php $user->getFullName(null); ?>
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<li><a href="profile.php">Perfil</a></li>
							<li><a href="update.php">Modificar Usuario</a></li>
							<li><a href="changepassword.php">Cambiar Contarseña</a></li>
							<li><a href="logout.php">Cerrar Sesíon</a></li>
						</ul>
					</li>
					<?php } else { ?>
						<li><a href="login.php">Iniciar Sesíon</a></li>
					<?php } ?>
				</ul>
				<?php if ($user->isLoggedIn() && $user->hasPermission('admin')) { ?>
					<?php get_template('form-search'); ?>
				<?php } ?>
			</div>
		</div>
	</nav>
	<div class="container-fluid">
