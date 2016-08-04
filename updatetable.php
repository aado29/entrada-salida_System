<?php

require_once 'core/init.php';
$user = new User();

if(!$user->isLoggedIn()){
	Redirect::to('index.php');
}

$currentId = $user->data()->id;

if (Input::exists('id_type', 'get') && Input::exists('id_num', 'get')) { 
	$id_type = Input::get('id_type', 'GET');
	$id_num = Input::get('id_num', 'GET');
	if ( !$user->hasPermission('admin') || ( $id_num == $user->data()->id_num && $id_type == $user->data()->id_type ) ) {
		Redirect::to('updatetable.php');
	}
	$user = new User(); 
	$user->getUserIdByData($id_type, $id_num);
	if(!$user->exists()){
		Redirect::to('index.php');
	}
}

if(Input::exists('update')){
	$timetable = new Timetable();
	$tables = Input::get('tables');
	try {
		$db = DB::getInstance();
		$db->delete('meta_data', array('id_user', '=', $user->data()->id));
		if ($tables) {
			foreach ($tables as $value) {
				if (!$db->insert('meta_data', array(
						'id_user' => $user->data()->id,
						'id_data' => $value
					))) {
					die('Error Vuelva a intentar');
				}
			}
		}
		Session::flash('updatetable', array('Los horarios han sido modificado exitosamente!'));

		$alpha = get_permalink( array(
								'id_type' => $user->data()->id_type,
								'id_num' => $user->data()->id_num
								)
							);
		$link = 'updatetable.php'.$alpha;
		Redirect::to($link);
	} catch (Exception $e) {
		die($e->getMessage());
	}
}

?>
<?php get_template('header') ?>
<?php get_template('side-menu'); ?>
	<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
		<h1 class="page-header">Editar Horarios</h1>
		<?php 
		if (Session::exists('updatetable'))
			handle_messages(Session::flash('updatetable'));
		if (!empty($response))
			handle_messages($response, 'danger');
		?>
		<?php 
		$timetable = new Timetable();
		$timetables = $timetable->getByIdUser($user->data()->id);
		$continuous = $timetable->get(array('type', '=', 0));
		$discontinuous = $timetable->get(array('type', '=', 1));
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
		<form class="form-center" action="#" method="POST">
			<div class="col-sm-6">
				<h3>Continuos</h3>
				<?php if (count($continuous)) {
					foreach ($continuous as $key => $value) { ?>
						<div class="checkbox">
							<?php 
								$bool = false;
								if (count($timetables))
									foreach ($timetables as $val) {
										if ($val->id == $value->id) {
											$bool = true;
										}
									}
							?>
							<?php if ($bool) { ?>
								<label>
									<input name="tables[]" type="checkbox" checked value="<?php echo $value->id; ?>"><?php echo $value->name; ?>
								</label>
							<?php } else { ?>
								<label>
									<input name="tables[]" type="checkbox" value="<?php echo $value->id; ?>"><?php echo $value->name; ?>
								</label>
							<?php } ?>
						</div>
					<?php } 
				} ?>
			</div>
			<div class="col-sm-6">
				<h3>Discontinuos</h3>
				<?php if (count($discontinuous)) {
					foreach ($discontinuous as $value) { ?>
						<div class="checkbox">
							<?php 
								$bool = false;
								if (count($timetables))
									foreach ($timetables as $val) {
										if ($val->id == $value->id) {
											$bool = true;
										}
									}
							?>
							<?php if ($bool) { ?>
								<label>
									<input name="tables[]" type="checkbox" checked value="<?php echo $value->id; ?>"><?php echo $value->name; ?>
								</label>
							<?php } else { ?>
								<label>
									<input name="tables[]" type="checkbox" value="<?php echo $value->id; ?>"><?php echo $value->name; ?>
								</label>
							<?php } ?>
						</div>
					<?php } 
				}?>
			</div>
			<input type="hidden" name="token" value="<?php echo Token::generate();?>">
			<input type="hidden" name="id" value="<?php echo $user->data()->id;?>">
			<input class="btn btn-md btn-primary btn-block" name="update" type="submit" value="Modificar" >
		</form>

	</div>
<?php get_template('footer') ?>