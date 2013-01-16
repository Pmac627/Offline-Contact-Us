<?php /* process.php */
	if(isset($_POST) == TRUE) {
		require_once('db/dbc.php');

		// Create validate_email function
		function validate_email($email) {
			return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? TRUE : FALSE;
		}

		// Create validate_input function
		function validate_input($string, $type, $length) {
			$type = 'is_'.$type; // Adjust type variable

			if(!$type($string)) {
				return FALSE; // Kill process
			} elseif(empty($string)) {
				return FALSE; // Kill process
			} elseif(strlen($string) > $length) {
				return FALSE; // Kill process
			} else {
				return TRUE; // Continue process
			}
		}

		// Create generate_error function
		function generate_error($error_code) {
			header("Location: failed.php?e=$error_code");
		}

		// Remove any tags and cast string type
		$_POST['contact_name'] = (string)strip_tags($_POST['contact_name']);
		$_POST['contact_ipv4'] = (string)strip_tags($_POST['contact_ipv4']);
		$_POST['contact_email'] = (string)strip_tags($_POST['contact_email']);
		$_POST['contact_message'] = (string)strip_tags($_POST['contact_message']);

		if(validate_input($_POST['contact_name'], "string", 40) == FALSE) {
			generate_error("02"); // Full Name input invalid
		} elseif(validate_input($_POST['contact_email'], "string", 60) == FALSE) {
			generate_error("03"); // Email input invalid
		} elseif(validate_email($_POST['contact_email']) == FALSE) {
			generate_error("04"); // Email input failed to validate as legit email format
		} elseif(validate_input($_POST['contact_message'], "string", 300) == FALSE) {
			generate_error("05"); // Message input invalid
		} else {
			$ip_dump = NULL;
			$conn = dbc::connection();

			$sql = $conn->prepare('SELECT visitor_id FROM visitors WHERE visitor_ip4 = ? LIMIT 1');
			$sql->bindParam(1, $_POST['contact_ipv4'], PDO::PARAM_STR); // Bind parameter
			$sql->execute(); // Execute prepared statement
			$ip_dump = $sql->fetch(PDO::FETCH_ASSOC);

			if($ip_dump != NULL) {
				$visitor_id = $ip_dump['visitor_id'];
			} else {
				$visitor_id = 0;
			}

			// Insert the message into the database
			$sql = $conn->prepare('INSERT INTO contact (visitor_id, visitor_name, visitor_email, visitor_message) VALUES (?, ?, ?, ?)');
			$sql->bindParam(1, $visitor_id, PDO::PARAM_INT); // Bind parameter
			$sql->bindParam(2, $_POST['contact_name'], PDO::PARAM_STR); // Bind parameter
			$sql->bindParam(3, $_POST['contact_email'], PDO::PARAM_STR); // Bind parameter
			$sql->bindParam(4, $_POST['contact_message'], PDO::PARAM_STR); // Bind parameter
			$sql->execute(); // Execute prepared statement

			header("Location: success.php"); // Redirect to success page
		}
	} else {
			generate_error("01"); // Form wasn't sent. You shouldn't be here!
	}
?>