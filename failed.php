<?php /* failed.php */
	if(isset($_GET['e']) != TRUE || $_GET['e'] == "") {
		$_GET['e'] = "0";
	} else {
		$_GET['e'] = (string)$_GET['e'];
	}
	$output = "";
	if(strlen($_GET['e']) == 2) {
		switch($_GET['e']) {
			case 01:
				$output = "Direct Access To This Page Is Not Allowed.";
				break;
			case 02:
				$output = "Full Name Input Did Not Validate.";
				break;
			case 03:
				$output = "Email Input Did Not Validate.";
				break;
			case 04:
				$output = "Email Input Did Not Meet Criteria For Email Formatting.";
				break;
			case 05:
				$output = "Message Input Did Not Validate.";
				break;
			default:
				$output = "Unknown Error Code. Contact Tech Support.";
				break;
		}
	} else {
		$output = "Unknown Error Code. Contact Tech Support.";
	}
	$title = "Failed :: ";
	include('header.php');
?>

	<div class="contact-form">
		<p class="contact-text-full">
			FAILED TO SUBMIT PROPERLY:<br><br>
			ERROR CODE: #<?php echo $_GET['e']; ?><br>
			<?php echo $output; ?><br><br>
			<a href="index.php">Click Here To Try Again.</a>
		</p>
	</div>
</body>
</html>