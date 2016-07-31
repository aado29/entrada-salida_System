<?php
	require_once 'core/init.php';

	$user = new User();

	if(!$user->isLoggedIn()) {
		Redirect::to('index.php');
	}

	if (Input::exists()) {

	    if (Token::check(Input::get('token'))) {
	    	$validate = new Validate();
        	$validation = $validate->check($_POST, array(
        		'date' => array(
        			'date_limit' => date('Y-m-d'),
        			'display' => 'Día'
        		),
        		'from' => array(
        			'date_limit' => Input::get('to'),
        			'display' => 'Día de inicio'
        		),
        		'to' => array(
        			'date_limit' => date('Y-m-d'),
        			'display' => 'Día de fin'
        		)
        	));
        	if ($validation->passed()) {

		    	if ($user->hasPermission('admin')) {
		    		$choice = Input::get('choice');

		    		if ($choice == 'user') {
		    			$data = getReportByUserId(Input::get('user'), Input::get('date'));
		    		}
		    		if ($choice == 'date') {
		    			$data = getReportByDate(Input::get('date'));
		    		}
		    		if ($choice == 'range') {
		    			$data = getReportByRangeDate(Input::get('from'), Input::get('to'));
		    		}
		    		if ($choice == 'rangeById') {
		    			$data = getReportRangeByUserId(Input::get('user'), Input::get('from'), Input::get('to'));
		    		}

		    	} else {
		    		$data = getReportByUserId($user->data()->id, Input::get('date'));
		    	}
		    } else {
		    	$errors = $validation->errors();
		    }

	    }
	}

	get_template('header');

?>
<?php get_template('side-menu'); ?>
	<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
		<h1 class="page-header">Reportes</h1>
		<?php if (!empty($errors)) {
            handle_messages($errors, 'danger');
        }?>
		<?php if ($user->hasPermission('admin')) { ?>
			<form class="form-center noPrint" id="report-form" action="./reports.php" method="post">

				<label for="inputBy">Realiazar por:</label>
				<select name="choice" class="form-control form-control-lg" id="report-type">
					<option value="user">Usuario (Día)</option>
					<option value="date">Fecha (Día)</option>
					<option value="range">Fecha (Rango)</option>
					<option value="rangeById">Usuario (Rango)</option>
				</select>

				<div id="report-user-input" >
					<label for="inputField">Usuario:</label>
					<select name="user" class="form-control">
		                <?php foreach ($user->getUsers() as $key => $value) { ?>
		                	<?php if(Input::get('user') == $value->id) { ?>
		                		<option value="<?php echo $value->id ?>" selected>
		                			<?php echo $value->firstName . ' (' . $value->id_type . '-' . $value->id_num. ')'; ?>
		                		</option>
		                	<?php } else { ?>
		                    	<option value="<?php echo $value->id ?>">
		                    		<?php echo $value->firstName . ' (' . $value->id_type . '-' . $value->id_num . ')'; ?>
		                    	</option> 
		                    <?php } ?>
		                <?php } ?>
		            </select>
				</div>

				<div id="report-day-input" >
					<label for="inputDate">Día</label>
					<input name="date" type="text" id="inputDate" class="date-picker form-control" placeholder="Ej: <?php echo date('Y-m-d') ?>" <?php if(!empty(Input::get('date'))) echo 'value="'.Input::get('date').'"' ?>>
				</div>

				<div id="report-range-input" >
					<label for="inputFrom">Rango: De </label>
					<input name="from" type="text" id="inputFrom" class="date-picker form-control" placeholder="Ej: <?php echo date('Y-m-d') ?>" <?php if(!empty(Input::get('from'))) echo 'value="'.Input::get('from').'"' ?>>
					<label for="inputTo">Hasta </label>
					<input name="to" type="text" id="inputTo" class="date-picker form-control" placeholder="Ej: <?php echo date('Y-m-d') ?>" <?php if(!empty(Input::get('to'))) echo 'value="'.Input::get('to').'"' ?>>
				</div>

				</br>
				<input type="hidden" name="token" value="<?php echo Token::generate();?>">
				<button name="submit" class="btn btn-lg btn-primary btn-block" type="submit">Buscar</button>
			</form>
		<?php } else { ?>
			<form class="form-center" id="report-form" action="./reports.php" method="post">
				<label for="inputBy">Realiazar por:</label>
				<select name="choice" class="form-control form-control-lg" id="report-type">
					<option value="date">Fecha (Día)</option>
					<option value="range">Fecha (Rango)</option>
				</select>

				<div id="report-day-input" >
					<label for="inputDate">Día</label>
					<input name="date" type="text" id="inputDate" class="date-picker form-control" placeholder="Ej: <?php echo date('Y-m-d') ?>" <?php if(!empty(Input::get('date'))) echo 'value="'.Input::get('date').'"' ?>>
				</div>

				<div id="report-range-input" >
					<label for="inputFrom">Rango: De </label>
					<input name="from" type="text" id="inputFrom" class="date-picker form-control" placeholder="Ej: <?php echo date('Y-m-d') ?>" <?php if(!empty(Input::get('from'))) echo 'value="'.Input::get('from').'"' ?>>
					<label for="inputTo">Hasta </label>
					<input name="to" type="text" id="inputTo" class="date-picker form-control" placeholder="Ej: <?php echo date('Y-m-d') ?>" <?php if(!empty(Input::get('to'))) echo 'value="'.Input::get('to').'"' ?>>
				</div>
				</br>
				<input type="hidden" name="token" value="<?php echo Token::generate();?>">
				<input type="hidden" name="user" value="<?php echo $user->data()->id;?>">
				<button name="submit" class="btn btn-lg btn-primary btn-block" type="submit">Buscar</button>
			</form>
		<?php }
		if (Input::exists() && empty($errors)) { ?>
			<?php if (!empty($data)) { ?>
				<div class="table-responsive">
					<input class="btn btn-lg btn-success btn-block noPrint" type="button" onClick="window.print()" value="Imprimir">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Registro #</th>
								<th>Usuario</th>
								<th>Fecha</th>
								<th>Entrada</th>
								<th>Salida</th>
								<th>Retraso</th>
								<th>Adelanto</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($data as $value) { ?>
							<tr>
								<td><?php echo $value->id;?></td>
								<td><?php echo getUserById($value->id_user)->firstName;?></td>
								<td><?php echo $value->date;?></td>
								<td><?php echo $value->hourIn;?></td>
								<td><?php if ($value->hourOut) echo $value->hourOut; else echo 'Pendiente';?></td>
								<?php $timetable = new Timetable(); ?>
								<td><?php 
									if ($value->id_timetable > 0) {
										$c_tt = $timetable->getDataById($value->id_timetable);
										if ( $timetable->maxVal($c_tt->hourIn, $value->hourIn) == $c_tt->hourIn) {
											echo 'A Tiempo';
										}  else {
											$diff = date('G:i:s', strtotime("00:00:00") + strtotime($value->hourIn) - strtotime($c_tt->hourIn));
											echo $diff;
										}
									} else 
										echo 'No Posee';
									?>
								</td>
								<td><?php 
									if ($value->id_timetable > 0) {
										$c_tt = $timetable->getDataById($value->id_timetable);
										if ( $timetable->maxVal($c_tt->hourOut, $value->hourOut) == $value->hourOut) {
											echo 'A Tiempo';
										}  else {
											$diff = date('G:i:s', strtotime("00:00:00") + strtotime($c_tt->hourOut) - strtotime($value->hourOut));
											echo $diff;
										}
									} else 
										echo 'No Posee';
									?>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
					<input class="btn btn-lg btn-success btn-block noPrint" type="button" onClick="window.print()" value="Imprimir">
				</div>

		<?php } else {
				echo '<center><p>no se encontraron resultados</p></center>';
			} 
		} ?>
	</div>
<?php get_template('footer'); ?>