<?php
	require_once 'core/init.php';
?>
<?php get_template('header'); ?>
<?php $user = new User();
$table = $user->getUsers(); ?>
<div class="row">
	<?php get_template('side-menu'); ?>
	<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
      	<h1 class="page-header">Overview</h1>
		<h2 class="sub-header">Section title</h2>
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th>Nombre</th>
						<th>Usuario</th>
						<th>email</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($table as $key => $value) { ?>
						<tr>
							<td><?php echo $value->id?></td>
							<td><?php echo $value->name?></td>
							<td><?php echo $value->username?></td>
							<td><?php echo $value->email?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
    </div>
</div>
<?php get_template('footer'); ?>