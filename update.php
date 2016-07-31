<?php

require_once 'core/init.php';
$user = new User();

if(!$user->isLoggedIn()){
	Redirect::to('index.php');
}

$currentId = $user->data()->id;

if ($username = Input::get('user')) { 
	if ( (!$user->hasPermission('admin')) || ($username == $user->data()->id_num) ) {
		Redirect::to('update.php');
	}
	$user = new User($username); 
	if(!$user->exists()){
		Redirect::to('index.php');
	}
}

if(Input::exists('update')){
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'firstName' => array(
			'required' => true,
			'min' => 3,
			'max' => 45,
			'display' => 'Nombre'
		),
		'lastName' => array(
			'required' => true,
			'min' => 3,
			'max' => 45,
			'display' => 'Apellido'
		),
		'id_num' => array(
			'required' => true,
			'numeric' => true,
			'display' => 'Numero de Cedula'
		),
		'email' => array(
			'required' => true,
			'email' => true,
			'display' => 'Email'
		),
		'position' => array(
			// no es necesario
			'display' => 'Cargo'
		)
	));
	
	if ($validation->passed()) {
		try {
			$user->update(array(
				'id_num' => escape(Input::get('id_num')),
				'email' => escape(Input::get('email')),
				'firstName' => escape(Input::get('firstName')),
				'lastName' => escape(Input::get('lastName')),
				'id_position' => escape(Input::get('position'))
			), escape(Input::get('id')));
			Session::flash('update', array('Tu perfil ha sido modificado exitosamente!'));
			Redirect::to('update.php');
		} catch (Exception $e) {
			die($e->getMessage());
		}
	} else {
		$response = $validation->errors();
	}
	
}

if(Input::exists('del')){
	if(Token::check(Input::get('token_'))) {
		if (escape(Input::get('id')) == $currentId) {
			$response = array('No puede eliminarse usted mismo.');
		} else {
			$user = new User();
			$user->delete(array(
				'id', '=', escape(Input::get('id'))
			));
			Session::flash('update', array('El perfil ha sido eliminado exitosamente!'));
			Redirect::to('update.php');
		}
	}
}

?>
<?php get_template('header') ?>
<?php get_template('side-menu'); ?>
	<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
		<h1 class="page-header">Editar Perfil</h1>
		<?php if (Session::exists('update')) {
			handle_messages(Session::flash('update'));
		}
		if (!empty($response)) {
			handle_messages($response, 'danger');
		}?>
		<form class="form-center" action="#" method="POST">
			<div class="field">
				<label for="inputFirstName">Nombre:</label>
			<input name="firstName" type="text" id="inputFirstName" class="form-control" value="<?php echo escape($user->data()->firstName); ?>">

			<label for="inputLastName">Apellido:</label>
			<input name="lastName" type="text" id="inputLastName" class="form-control" value="<?php echo escape($user->data()->lastName); ?>">

			<label for="inputCI">Cedula:</label>
			<input name="id_num" type="text" id="inputCI" class="form-control" value="<?php echo escape($user->data()->id_num); ?>">
			
			<label for="inputEmail">Email:</label>
			<input name="email" type="text" id="inputEmail" class="form-control" value="<?php echo escape($user->data()->email); ?>">

			<label for="inputEmail">Cargo:</label>
			<select name="position" class="form-control">
				<?php foreach (getPositions() as $key => $value) { ?>
					<?php if($user->data()->id_position == $value->id) { ?>
						<option value="<?php echo $value->id; ?>" selected ><?php echo $value->name; ?></option> 
					<?php } else { ?>
						<option value="<?php echo $value->id ?>"><?php echo $value->name; ?></option> 
				<?php }
				} ?>
			</select>
			</br>
				
			<input type="hidden" name="token" value="<?php echo Token::generate();?>">
			<input type="hidden" name="id" value="<?php echo $user->data()->id;?>">
			<input class="btn btn-lg btn-primary btn-block" name="update" type="submit" value="Modificar" >
		</div>
	</form>

	</br>
	<?php if ($user->data()->id != $currentId) { ?>
	<form class="form-center" action="#" method="POST">
		<input type="hidden" name="token_" value="<?php echo Token::generate();?>">
		<input type="hidden" name="id" value="<?php echo $user->data()->id;?>">
		<input class="btn btn-lg btn-danger btn-block" name="del" type="submit" value="Eliminar esta cuenta" >
	</form>
	<?php } ?>
<?php get_template('footer') ?>