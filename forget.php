<?php 
include_once 'core/init.php';

$user = new User();
if ($user->isLoggedIn()) {
	Session::flash('home', 'You already log in');
	Redirect::to('index.php');
}

if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'email' => array('required' => true, 'email' => true, 'display' => 'Correo Electronico')
		));
		if($validation->passed()){

			$user = new User();
			$data = $user->Remember(Input::get('email', 'POST'));
			$err = $data[0];
			$pass = $data[1];
			$id = $data[2];

			if (!is_null($pass)) {
				$Link = 'savepassword.php?secret_token='.$pass.'&user_id='.$id;
			} else {
				$response = array($err);
			}


		}  else {
			$response = $validation->errors();
		}
	}
	
}
get_template('header'); ?>

<?php if (Session::exists('change')) {
    handle_messages(Session::flash('change'));
}
if (!empty($response)) {
    handle_messages($response, 'danger');
}?>
<?php if(empty($Link)) { ?>
	<h2 class="form-center">Ingrese sus datos para recuperar contraseña</h2>
	<form class="form-center" action="" method="post">

		<label for="inputEmail">Correo Electronico</label>
		<input value="aado29@gmail.com" name="email" type="email" id="inputEmail" class="form-control" placeholder="email@dominio.ext" required autofocus>
		</br>
		<input type="hidden" name="token" value="<?php echo Token::generate();?>">
		<button class="btn btn-md btn-primary btn-block" type="submit">Recuperar</button>
	</form>
<?php } else { ?>
	<center>
		<p><a href="http://localhost:8888/entrada-salida_System/<?php echo $Link; ?>"> Recuperar</a> Contraseña</p>
	</center>
<?php } ?>
<?php get_template('footer'); ?>
