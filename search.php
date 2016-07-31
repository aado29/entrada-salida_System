<?php
	require_once 'core/init.php';

	$user = new User();

	if(!$user->isLoggedIn()) {
		Redirect::to('index.php');
	}
	if(!$user->hasPermission('admin')) {
		Redirect::to('index.php');
	}

	$dbName = 'users';
	$type = Input::get('type');
	$field = '%'.Input::get('field').'%';
	$db = DB::getInstance();

	$sql = "SELECT * FROM {$dbName} WHERE {$type} LIKE '{$field}'";

	$check = $db->query($sql);

	if($check->count()){
		$results = $check->results();
	}

	get_template('header');
?>
	<div class="row">
	<?php get_template('side-menu'); ?>
	<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
		<h1 class="page-header">Busqueda</h1>
		<div class="row placeholders">
			<?php  
				if (!empty($results)) {
					$user = new User();
					foreach ($results as $value) { ?>
						<div class="col-xs-6 col-sm-3 placeholder">
							<a href="profile.php<?php get_permalink(array('user' => $value->id_num)); ?>">			
								<img src="" width="200" height="200" class="img-responsive" alt="">
								<h4><?php echo $value->firstName . ' ' . $value->lastName; ?></h4>
								<span class="text-muted">
									<?php echo getPositionById($value->id_position)->name; ?>
								</span>
							</a></br>
							<span class="text-muted">
								<a href="update.php<?php get_permalink(array('user' => $value->id_num)); ?>">
									Editar Perfil
								</a>
							</span>
						</div>
				<?php }
				} else {
					echo 'Nada :(';
				}
			?>
        </div>
	</div>
</div>

<?php 
	get_template('footer');
?>