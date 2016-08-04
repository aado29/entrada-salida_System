<?php
	require_once 'core/init.php';

	$user = new User();

	if(!$user->isLoggedIn()){

		Redirect::to('login.php');

	}

	if(Input::exists('submit_in')){
		if(Token::check(Input::get('token'))){
			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'submit_in' => array(
					'required' => true,
					'register_in' => $user->data()->id
				)
			));
			if($validation->passed()){
				$report = new Report();
				$idT = escape(Input::get('id_timetable'));
				if (!Input::exists('id_timetable')) {
					$idT = 0;
				}
					
				try {
					$report->create( array(
						'id_user' => escape($user->data()->id),
						'date' => date('Y-m-d'),
						'hourIn' => date('G:i:s'),
						'id_timetable' => escape(Input::get('id_timetable'))
					));
					Session::flash('home', array('Haz hecho el chequeo de entrada.'));
					Redirect::to('index.php');

				} catch (Exception $e) {
					die($e->getMessage());
				}
			} else {
	            $response = $validation->errors();
	        }
	    }
	}

	if(Input::exists('submit_out')){
		if(Token::check(Input::get('token'))) {
	        $validate = new Validate();
	        $validation = $validate->check($_POST, array(
	        	'submit_out' => array(
		        	'required' => true,
		        	'register_out' => $user->data()->id
				)
	        ));
	        
	        if ($validation->passed()) {
	        	$report = new Report();
	            try {
	                $report->update(array(
	                    'hourOut' => date('G:i:s')
	                ), Input::get('id'));
	                Session::flash('home', array('Haz hecho el chequeo de salida.'));
	                Redirect::to('index.php');
	            } catch (Exception $e) {
	                die($e->getMessage());
	            }
	        } else {
	            $response = $validation->errors();
	        }
	    }
	}

	get_template('header');
?>	
	<?php get_template('side-menu'); ?>
	<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
	<?php 
	if (Session::exists('home')) {
		handle_messages(Session::flash('home'));
	} 
	if (!empty($response)) {
		handle_messages($response, 'danger');
	}?>
		<div class="jumbotron">
			<h1>Bienvenido, </h1>
			<p class="lead">
			<?php echo escape($user->data()->firstName)." ".escape($user->data()->lastName);?></p>
			<div id="time"><?php echo date('d-m-Y'); ?></div>
			<div id="clock"></div>
			<?php 
				$report = new Report();
				$timetable = new Timetable();
				$result = $report->getNonFinished($user); 
				$current_timetable = $timetable->current($user->data()->id);
				$timetables = $timetable->getByIdUser($user->data()->id); 
			?>
			<h2>Horarios:</h2>
			<?php if($timetables) { ?>
			<table class="table table-striped">
				<thead style="width:100%">
					<tr>
						<th>Horario</th>
						<th>Horas de Entrada</th>
						<th>Horas de Salida</th>
					</tr>
				</thead>
				<tbody style="width:100%">
					<?php foreach($timetables as $value) { ?>
					<tr>
						<td><?php echo $value->name;?></td>
						<td><?php echo $value->hourIn;?></td>
						<td><?php echo $value->hourOut;?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php } else { ?>
				<p>No hay horarios relacionados</p>
			<?php } ?>
			<h2>Horario Actual:</h2>
			<?php if ($current_timetable) { ?>
				<p><?php echo $current_timetable->name; ?></p>
			<?php } else { ?>
				<p>Usted ha ingresado fuera de su horario laboral</p>
			<?php } ?>

			<?php if ($result) { ?>
				<form action="./" method="POST">
					<input type="submit" name="submit_out" class="btn btn-md btn-danger col-xs-12" value="Salir" />
					<input type="hidden" name="id" value="<?php echo $result->id; ?>" />
					<input type="hidden" name="token" value="<?php echo Token::generate();?>">
				</form>
			<?php } else { ?>
				<form action="./" method="POST">
					<input type="submit" name="submit_in" class="btn btn-md btn-success col-xs-12" value="Entrar" />
					<?php if ($current_timetable) { ?>
						<input type="hidden" name="id_timetable" value="<?php echo $current_timetable->id; ?>"/>
					<?php } ?>
					<input type="hidden" name="token" value="<?php echo Token::generate();?>">
				</form>
			<?php } ?>
		</div>
	</div>
<?php
get_template('footer');