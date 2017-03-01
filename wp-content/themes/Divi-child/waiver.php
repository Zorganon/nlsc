<?php
/*
Template Name: Liability Waiver Page
*/
?>

<?php
//this code loads the wp functions for us to use
if ( !isset($wp_did_header) ) {
    $wp_did_header = true;
    require_once( dirname(__FILE__) . '/wp-load.php' );
    wp();
    require_once( ABSPATH . WPINC . '/template-loader.php' );
}

//define variables and set to empty values
global $wpdb;

$saveErr = $nameErr = $signatureErr = "";
$name = $signature = "";
$user_id = get_current_user();
$date_signed = date_create();
$version = "1.5";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	//checks if the name field is empty
	if (empty($_POST["name"])) {
		$nameErr = "Your name is required near the top of this document.";
	} else {
		$name = test_input($_POST["name"]);
		//checks if the name includes special charactes
		if (!preg_match("/^[a-zA-Z' ]*$/", $name)) {
			$nameErr = "Only letters and white space allowed";
		}
	}
	//checks if the signature field is empty
	if (empty($_POST["signature"])) {
		$signatureErr = "Your signature is required at the bottom of this document.";
	} else {
		$signature = test_input($_POST["signature"]);
		//checks if the signature includes special charactes	
		if (!preg_match("/^[a-zA-Z' ]*$/", $signature)) {
			$nameErr = "Only letters and white space allowed";
		}
	}

	//Data insertion into database
	if (is_user_logged_in()) {
		$current_user = wp_get_current_user();
		$user_id = $current_user->ID;
		$data = array( 'user_id' => $user_id, 'waiver_version' => $version, 'member_name' => $name, 'member_signature' => $signature);
		if ($wpdb->insert( 'liability_waiver', $data )) {
			wp_redirect('https://nlsc.org');
			exit;
		} else {
			$saveErr = "The form was not saved properly, please log out, log back in, and try again.";
		}
	}
}

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

?>

<!DOCTYPE html>
<head>

</head>
<body>
	<h2 style="text-color:red"><?php echo $saveError ?></h2>
	<div class="main-doc" style="margin: 30px">
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<h1>Northern Lights Sailing Club</h1>
			<h2>Mutual Release of Liability and Indemnification</h2>
			<h3>As a member or guest of Northern Lights Sailing Club (NLSC) and a participant in events planned and executed by NLSC; I understand that boating of any type, duration or location can be hazardous. I recognize the inherent risks of engaging in these events can include: death, injury to my person and loss of or damage to my property.</h3>
			<h2>I KNOWINGLY ACCEPT THESE RISKS AND FURTHER AGREE AS FOLLOWS</h2>
			<p>I, <input type="text" name="name" value="<?php echo $name;?>">, hereby enter into this Mutual Release of Liability and Indemnification (hereinafter “Mutual Release”) in consideration for (1) my membership in or being a guest of the Northern Lights Sailing Club, (2) my participation in events sponsored by the Northern Lights Sailing Club and Sailing Opportunities communicated by Northern Lights Sailing Club (hereafter referred to as “NLSC Events”, and (3) my desire not to subject my own personal assets to the risk of litigation, claims of any type and actual or potential adverse judgment.</p>
			<p>On behalf of myself, next-of-kin, trustees, administrators, executors, heirs and assigns, I HEREBY RELEASE the NORTHERN LIGHTS SAILING CLUB and all of its officers, directors, members, crew members (specifically including the Trip Chair, Captain(s) and First Mate(s)), and each of their heirs, administrators, successors, affiliates, assigns and agents individually and jointly (hereinafter collectively referred to as “Released Parties”) from any liability for my death, personal injury, property damage or other financial loss that may arise from or occur during my participation in NLSC Events.</p>
			<p>On behalf of myself, next-of-kin, trustees, administrators, executors, heirs and assigns I further AGREE NOT TO SUE THE RELEASED PARTIES — for any reason whatsoever, including their strict liability, carelessness, negligence or gross negligence — for any damages, loss cost, expense or other relief that may be available to me or my next-of-kin, trustees, administrators, executors, heirs and assigns under international laws and treaties and/or federal, state and local statutes, regulations and common law — that may arise from or occur during my participation in NLSC Events.</p>
			<p>On behalf of myself, next-of-kin, trustees, administrators, executors, heirs and assigns I further AGREE TO DEFEND, INDEMNIFY AND HOLD HARMLESS THE RELEASED PARTIES from any and all liability, actions, causes of action, debts, claims and demands of any nature whatsoever, which I have now or which I may have arising out of or occurring during my participation in NLSC Events.</p>
			<p>I fully understand that any boat and equipment made available during my participation in NLSC Events COME WITHOUT ANY WARRANTIES, GUARANTEES OR OTHER REPRESENTATIONS BY THE RELEASED PARTIES, including any warranty of merchantability or fitness for a particular purpose.</p>
			<p>I agree that I shall not rely on the judgment of any of the RELEASED PARTIES during my participation in NLSC Events. I assume the responsibility to judge my fitness to participate in NLSC Events. I further recognize that I ALONE AM RESPONSIBLE FOR MY SAFETY while participating in NLSC Events.</p>
			<p>I acknowledge that IF I (MY NEXT-OF KIN, TRUSTEES, ADMINISTRATORS, EXECUTORS, HEIRS, OR ASSIGNS) IGNORE THIS AGREEMENT AND COMMENCE ANY ACTION, I (MY NEXT-OF KIN, TRUSTEES, ADMINISTRATORS, EXECUTORS, HEIRS, OR ASSIGNS) WILL BE HELD RESPONSIBLE FOR ALL ATTORNEYS FEES, WITNESS FEES, EXPENSES AND COURT COSTS INCURRED BY THE RELEASED PARTIES.</p>
			<p>I hereby agree that Minnesota law shall govern my participation in NLSC Events and the construction, interpretation and enforcement of this Mutual Release, regardless of the location(s) in which an NLSC Event is held or in which my death, injury, property damage or loss may arise. In the event that a court determines that any provision of this Mutual Release is unenforceable or invalid, I agree that it will not affect the enforceability or validity of the remaining portions of this Mutual Release.</p>
			<p><b>I UNDERSTAND BY MAKING AND SIGNING THIS MUTUAL RELEASE I SURRENDER VALUABLE RIGHTS, INCLUDING BUT NOT LIMITED TO ANY RIGHT TO SUE THE RELEASED PARTIES.</b></p>
			<p><b>I CERTIFY THAT I HAVE CAREFULLY READ AND UNDERSTOOD ALL PARTS OF THIS MUTUAL RELEASE AND SIGN IT FREELY AND INTENDING TO BE LEGALLY BOUND BY IT.</b></p>
			<h6><i>Document Version: 1.5</i></h6>
			<p>Signature: <input type="text" name="signature" value="<?php echo $signature;?>"></p>
			<p><?php echo date("jS \of F Y") ?></p>
			<input type="submit">
			<span class="error"><?php echo $nameErr;?></span>
			<span class="error"><?php echo $signatureErr;?></span>
		</form>
	</div>
</body>