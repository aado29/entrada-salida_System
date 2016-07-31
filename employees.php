<?php
	require_once 'core/init.php';
	$user = new User();

	if (!$user->isLoggedIn()) {
	    Redirect::to('index.php');
	}

	if (!$user->hasPermission('admin')) {
	    Redirect::to('index.php');
	}
?>
<?php get_template('header'); ?>
<div class="row">
	<?php get_template('side-menu'); ?>
	<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
		<h1 class="page-header">Empleados</h1>
		<div class="row placeholders">
			<?php foreach ($user->getUsers() as $value) { ?>
				<div class="col-xs-6 col-sm-3 placeholder">
					<img src="" width="200" height="200" class="img-responsive" alt="">
					<h4><?php echo $value->firstName . ' ' . $value->lastName; ?></h4>
					<span class="text-muted"><b>Id #: </b><?php echo $value->id_type; ?>-<?php echo $value->id_num; ?></span></br>
					<span class="text-muted">
						<a href="update.php<?php get_permalink(array('user' => $value->id_num)); ?>">
							Editar Perfil
						</a>
					</span>
				</div>
			<?php } ?>
        </div>
	</div>
</div>
<?php get_template('footer'); ?>