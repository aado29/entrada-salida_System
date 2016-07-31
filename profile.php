<?php
require_once 'core/init.php';

$user = new User();

if(!$user->isLoggedIn()) {
	Redirect::to('index.php');
}
if ($username = Input::get('user')) { 
	if (!$user->hasPermission('admin')) {
		Redirect::to('profile.php');
	}
	$user = new User($username); 
	if(!$user->exists()){
    	Redirect::to('index.php');
    }
}
get_template('header');
?>

<?php get_template('side-menu'); ?>
	<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
		<h1 class="page-header">Perfil</h1>
		<?php if ($username = Input::get('user')) { 
			$user = new User($username); ?>
		<?php } ?>
		<p><strong>Nombre Completo: </strong><?php $user->getFullName(false); ?></p>
		<p><strong>Email: </strong><?php $user->getEmail(false); ?></p>
		<p><strong>Cargo: </strong><?php $user->getPosition(false); ?></p>
	</div>
<?php
get_template('footer');