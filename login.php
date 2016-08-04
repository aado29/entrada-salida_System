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
			'id_num' => array('required' => true),
			'password' => array('required' => true)
		));
		if($validation->passed()){

			$user = new User();
			$remember = (Input::get('remember')) === 'on' ? true : false;
			
			$login = $user->login(Input::get('id_type'), Input::get('id_num'), Input::get('password'), $remember);
			
			if($login){
				Redirect::to('index.php');
			}else{
				$response = array('Usuario o Contraseña invalidos');
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
<form class="form-center" action="" method="post">
	<h2 class="form-center-heading">Ingrese sus Datos</h2>

	<label for="inputIdType">Identificación</label>
	<div class="form-group form-inline">
		<select name="id_type" id="inputIdType" class="form-control input-group">
			<option value="V">V</option>
			<option value="E">E</option>
		</select>
		<input value="21367773" name="id_num" type="text" id="inputIdNum" class="form-control input-group" placeholder="0000000" required autofocus>
	</div>

	<label for="inputPassword">Contraseña</label>
	<input value="21367773" name="password" type="password" id="inputPassword" class="form-control" placeholder="********" required>

	<input type="hidden" name="token" value="<?php echo Token::generate();?>">

	<!-- <div class="checkbox">
		<label><input type="checkbox" name="remember" id="remember">Recuerdame</label>
	</div> -->
	<button class="btn btn-md btn-primary btn-block" type="submit">Iniciar Sesión</button>
</form>
<center><span class=""><a href="forget.php">No recuerdo mi contraseña</a></span></center>
<?php get_template('footer'); ?>
