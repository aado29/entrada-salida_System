<script type="text/javascript" src="assets/js/jquery.min.js"></script>
<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="assets/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="assets/js/bootstrap-datepicker.es.min.js"></script>
<script type="text/javascript" src="assets/js/main.js"></script>
<script type="text/javascript">
	var options = {days: false, hours: true, minutes: true, seconds: true}
	new Counter('clock', options);
</script>
<?php 
$report = new Report();
$user = new User();
if ($user->isLoggedIn()) {
	$finished = $report->hasFinished($user);
	if (!$finished) {
		$finished = $report->getNonFinished($user); ?>
		<script type="text/javascript">
			var options = {days: false, hours: true, minutes: true, seconds: true}
			new Counter('time-count', options, <?php echo timeToJSON($finished); ?>);
		</script>
	<?php } 
} ?>
</body>
</html>