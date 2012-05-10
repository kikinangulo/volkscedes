<?php
	$service = $_POST['service'];
	$fname = $_POST['fname'];
	$sname = $_POST['sname'];
	$add1 = $_POST['add1'];
	$add2 = $_POST['add2'];
	$add3 = $_POST['add3'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	$make = $_POST['make'];
	$model = $_POST['model'];
	$milage = $_POST['milage'];
	$km_m = $_POST['km_m'];
	$reg = $_POST['reg'];
	$comments = $_POST['comments'];
	$previous = $_POST['previous'];
	
	mysql_connect("mysql4.mylogin.ie", "volkscedes", "volkscedes1") or die ('Error: ' .mysql_error());
	mysql_select_db("volkscedes");
	
	$query="INSERT INTO booking (service, fname, sname, add1, add2, add3, email, phone, make, model, milage, km_m, reg, comments, previous)VALUES ('".$service."','".$fname."','".$sname."','".$add1."','".$add2."','".$add3."','".$email."','".$phone."','".$make."','".$model."','".$milage."','".$km_m."','".$reg."','".$comments."','".$previous."')";
	
	mysql_query($query) or die ('Error updating database');
?>
<body>
	<section id="intro">
		<header>
			Booking Successfully Registered
		</header>		
	</section>
</body>