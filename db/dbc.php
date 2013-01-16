<?php /* dbc.php */
	class dbc {
		function connection() {
			$pdo_host = 'localhost'; // Create pdo_host variable
			$pdo_name = 'db_name'; // Create pdo_name variable
			$pdo_declare = 'mysql:host=' . $pdo_host . ';dbname=' . $pdo_name . ''; // Create pdo_declare variable
			$pdo_user = 'user_name'; // Create pdo_user variable
			$pdo_pass = 'user_pwd'; // Create pdo_pass variable
			$conn = new PDO($pdo_declare, $pdo_user, $pdo_pass); // Set up PDO connection
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set up PDO error handling

			return $conn;
		}
	}
?>