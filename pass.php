<?php 
require_once 'core/init.php';
$salt = Hash::salt(32);
$user = new User();
try {
	$user->update(array(
		'password' => Hash::make(21367773, $salt),
	    'salt' => $salt
	), 1);
} catch (Exception $e) {
    die($e->getMessage());
}
$report = new Report();

var_dump($report->getBetweenDates('2016-06-03', '2016-06-04'));
?>