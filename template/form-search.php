<form class="navbar-form navbar-right" action="search.php" method="GET">
	<?php $type = Input::get('type'); ?>
	<select name="type" class="form-control">
		<option value="firstName" <?php if(($type) && $type == 'firstName') echo 'Selected'?>>Nombre</option>
		<option value="lastName" <?php if(($type) && $type == 'lastName') echo 'Selected'?>>Apellido</option>
		<option value="id_num" <?php if(($type) && $type == 'ci') echo 'Selected'?>>Cedula</option>
	</select>
	<input name="field" type="text" class="form-control" value="<?php if (Input::get('field')) echo Input::get('field') ?>" placeholder="Buscar">
	<button class="btn btn-primary" type="submit">Buscar</button>
</form>