<div class="col-sm-3 col-md-2 sidebar">
	<ul class="nav nav-sidebar">
	<?php $user = new User();
	if ($user->isLoggedIn()) { ?>
		<li><a href="./">Inicio</a></li>
		<?php if ($user->hasPermission('admin')) { ?>
			<li><a href="employees.php">Empleados</a></li>
			<li class="action-nav">
				<a href="#">Registros</a>
				<ul class="nav-hide nav">
					<li><a href="register.php">Registro de Empleado</a></li>
					<li><a href="positions.php">Registro de Cargos</a></li>
					<li><a href="timetable.php">Registro de Horarios</a></li>
				</ul>
			</li>
		<?php } ?>
		<li><a href="reports.php">Reportes</a></li>
	</ul>
	<?php } ?>
	<div id="time-count"></div>
</div>
