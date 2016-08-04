<?php
require_once 'core/init.php';

$user = new User();
if (!$user->isLoggedIn()) {
	Redirect::to('index.php');
}
if (!$user->hasPermission('admin')) {
	Redirect::to('index.php');
}

if (Input::exists('new')) {

	if (Token::check(Input::get('token'))) {
		
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'name' => array(
				'required' => true,
				'min' => 3,
				'max' => 20,
				'display' => 'Nombre'
			),
			'description' => array(
				'required' => true,
				'min' => 3,
				'max' => 50,
				'display' => 'Descripción'
			),
			'hourIn' => array(
				'required' => true,
				'time' => true,
				'display' => 'Hora de Entrada'
			),
			'hourOut' => array(
				'required' => true,
				'time' => true,
				'display' => 'Hora de Salida'
			)
		));

		if ($validation->passed()) {
			$timetable = new Timetable();
			
			$salt = Hash::salt(32);
			try{
				
				$timetable->create(array(
					'name' => escape(Input::get('name')),
					'description' => escape(Input::get('description')),
					'hourIn' => escape(Input::get('hourIn')),
					'hourOut' => escape(Input::get('hourOut')),
					'type' => escape(Input::get('type'))
				));
				
				Session::flash('timetable', array('Haz resgistrado un nuevo horario'));
				Redirect::to('timetable.php');
				
			} catch (Exception $e) {
				die($e->getMessage() );
			}
			
		} else {
			$response = $validation->errors();
		}
	}
}

if (Input::exists('edit', 'post')) {

	if (Token::check(Input::get('token'))) {
		
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'name' => array(
				'required' => true,
				'min' => 3,
				'max' => 20,
				'display' => 'Nombre'
			),
			'description' => array(
				'required' => true,
				'min' => 3,
				'max' => 50,
				'display' => 'Descripción'
			),
			'hourIn' => array(
				'required' => true,
				'time' => true,
				'display' => 'Hora de Entrada'
			),
			'hourOut' => array(
				'required' => true,
				'time' => true,
				'display' => 'Hora de Salida'
			)
		));

		if ($validation->passed()) {
			$timetable = new Timetable();
			
			$salt = Hash::salt(32);
			try{

				$timetable->update(array(
					'name' => Input::get('name'),
					'description' => escape(Input::get('description')),
					'hourIn' => escape(Input::get('hourIn')),
					'hourOut' => escape(Input::get('hourOut')),
					'type' => escape(Input::get('type'))
				), escape(Input::get('id')));
				
				Session::flash('timetable', array('Haz editado el horario exitosamente'));
				Redirect::to('timetable.php');
				
			} catch (Exception $e) {
				die($e->getMessage() );
			}
			
		} else {
			$response = $validation->errors();
		}
	}
}

if (Input::exists('delete', 'post')) {

	$timetable = new Timetable();

	try{

		$timetable->delete( array( 'id', '=', Input::get('id_del') ) );
		
		Session::flash('timetable', array('Haz borrado el horario exitosamente'));
		Redirect::to('timetable.php');
		
	} catch (Exception $e) {
		die($e->getMessage() );
	}

}
?>

<?php get_template('header') ?>
<?php get_template('side-menu'); ?>
	<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
		<h1 class="page-header">Registro de Horarios</h1>
		<?php if (Session::exists('timetable')) {
			handle_messages(Session::flash('timetable'));
		}
		if (!empty($response)) {
			handle_messages($response, 'danger');
		}?>
		<?php if ( !Input::exists('edit', 'get') ) { ?>
			
			<?php $timetable = new Timetable(); ?>
			<?php $timetables = $timetable->get(array('id', '>', 0)); ?>
			<table class="table table-striped">
				<thead style="width:100%">
					<tr>
						<th>Horario</th>
						<th>Descripción</th>
						<th>Horas de Entrada</th>
						<th>Horas de Salida</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody style="width:100%">
					<?php foreach($timetables as $value) { ?>
					<tr>
						<td><?php echo $value->name;?></td>
						<td><?php echo $value->description;?></td>
						<td><?php echo $value->hourIn;?></td>
						<td><?php echo $value->hourOut;?></td>
						<td>
							<a class="btn btn-link" href="timetable.php?edit=<?php echo $value->id;?>">Editar</a>
							<form style="display:inline-block" action="#" method="POST">
								<input type="hidden" name="id_del" value="<?php echo $value->id;?>" />
								<input type="submit" name="delete" class="btn btn-link" value="Eliminar" />
							</form>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<h2>Registrar Nuevo</h2>
			<form class="form-center" action="#" method="POST">

				<label for="inputName">Nombre:</label>
				<input name="name" type="text" id="inputName" class="form-control" value="<?php echo escape(Input::get('name')); ?>">

				<label for="inputDescription">Descripción:</label>
				<input name="description" type="text" id="inputDescription" class="form-control" value="<?php echo escape(Input::get('description')); ?>">
				
				<label for="inputHourIn">Hora de Entrada:</label>
				<input name="hourIn" type="text" id="inputHourIn" class="form-control" placeholder="Ej: HH:MM:SS" value="<?php echo escape(Input::get('hourIn')); ?>">

				<label for="inputHourOut">Hora de Salida:</label>
				<input name="hourOut" type="text" id="inputHourOut" class="form-control" placeholder="Ej: HH:MM:SS" value="<?php echo escape(Input::get('hourOut')); ?>">

				<label for="inputType">Tipo de Horario</label>
				<select name="type" class="form-control" id="inputType">
					<option value="0">Continuo</option>
					<option value="1">Disontinuo</option>
				<select>
				</br>


				<input type="hidden" name="token" value="<?php echo Token::generate();?>">
				<input type="submit" name="new" class="btn btn-md btn-primary col-xs-12" value="Registrar" />
			</form>
		<?php } else { ?>
			<?php $timetable = new Timetable(); ?>
			<?php $timetables = $timetable->getById(Input::get('edit', 'GET')); ?>
			<form class="form-center" action="" method="POST">

				<label for="inputName">Nombre:</label>
				<input name="name" type="text" id="inputName" class="form-control" value="<?php echo $timetables->name; ?>">

				<label for="inputDescription">Descripción:</label>
				<input name="description" type="text" id="inputDescription" class="form-control" value="<?php echo escape($timetables->description); ?>">
				
				<label for="inputHourIn">Hora de Entrada:</label>
				<input name="hourIn" type="text" id="inputHourIn" class="form-control" placeholder="Ej: HH:MM:SS" value="<?php echo escape($timetables->hourIn); ?>">

				<label for="inputHourOut">Hora de Salida:</label>
				<input name="hourOut" type="text" id="inputHourOut" class="form-control" placeholder="Ej: HH:MM:SS" value="<?php echo escape($timetables->hourOut); ?>">

				<label for="inputType">Tipo de Horario</label>
				<select name="type" class="form-control" id="inputType">
					<?php if ($timetables->type == 0) { ?>
						<option value="0" selected>Continuo</option>
					<?php } else { ?>
						<option value="0">Continuo</option>
					<?php } ?>
					<?php if ($timetables->type == 1) { ?>
						<option value="1" selected>Disontinuo</option>
					<?php } else { ?>
						<option value="1">Disontinuo</option>
					<?php } ?>
				<select>
				</br>

				<input type="hidden" name="id" value="<?php echo Input::get('edit', 'GET'); ?>">
				<input type="hidden" name="token" value="<?php echo Token::generate();?>">
				<input type="submit" name="edit" class="btn btn-md btn-primary col-xs-12" value="Modificar">
			</form>
		<?php } ?>
	</div>
<?php get_template('footer'); ?>
