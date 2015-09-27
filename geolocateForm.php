<?php 
	require './includes/geocode.php';
	$address = $_POST['address'];
?>

<html>
<body>
	<form method="post" action="<?php echo $PHP_SELF;?>">
		Address: <input type="text" name="address"><br>
		<input type="submit" value="submit">
	</form><br>
	<?php echo $address;?><br>
	<?php print_r(getGeoLocation($address));?>
</body>
</html>