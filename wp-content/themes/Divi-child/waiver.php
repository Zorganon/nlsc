<?php
//define variables and set to empty values
$nameErr = $signatureErr = "";
$name = $signature = $date = "";
$user_id = get_current_user();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	//checks if the name field is empty
	if (empty($_POST["name"])) {
		$nameErr = "Your name is required near the top of this document.";
	} else {
		$name = test_input($_POST["name"]);
		//checks if the name includes special charactes
		if (!preg_match("/^[a-zA-Z' ]*$/",$name)) {
			$nameErr = "Only letters and white space allowed";
		}
}
	}
	//checks if the signature field is empty
	if (empty($POST["signature"])) {
		$signatureErr = "Your signature is required at the bottom of this document.";
	} else {
		$signature = test_input($_POST["signature"]);
		//checks if the signature includes special charactes	
		if (!preg_match("/^[a-zA-Z' ]*$/",$name)) {
			$nameErr = "Only letters and white space allowed";
		}
	}
	$date = date("Y-m-d")
}

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

//Database Connection
$conn = mysqli_connect(getenv('IP'), getenv('C9_USER'), '', 'c9');
if(!$conn){
	die('Problem in database connection: ' . mysql_error());
}

//Data insertion into database
if is_user_logged_in() {
	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;
	$date_signed = date_timestamp_get()
	$query = "INSERT INTO c9.wp_bp_xprofile_data ( 'field_id', 'user_id', 'value', 'last_updated' ) VALUES ('69', $user_id, 1, $date_signed)";
	mysqli_query($conn, $query)
}
?>