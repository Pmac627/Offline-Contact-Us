<?php /* index.php */
	require_once('db/dbc.php');
	// Create find_ip function
	function find_ip() {
		$ip_var = ''; // Create ip_var variable
		if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip_var = $_SERVER['HTTP_CLIENT_IP']; // Adjust ip_var variable
		} elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip_var = $_SERVER['HTTP_X_FORWARDED_FOR']; // Adjust ip_var variable
		} elseif(!empty($_SERVER['REMOTE_ADDR'])) {
			$ip_var = $_SERVER['REMOTE_ADDR']; // Adjust ip_var variable
		} else {
			$ip_var = 'HIDDEN'; // Adjust ip_var variable
		}

		return $ip_var;
	}

	// Create visitor_record function
	function visitor_record() {
		if(isset($_COOKIE['offline-contact-us'])) {
			// Do nothing
		} else {
			$ipv4 = $ipv6 = ''; // Create ip_var property
			$ipv4 = find_ip();
			$ip_dump = NULL;
			$visitor_id = 0;

			$conn = dbc::connection();

			$sql = $conn->prepare('SELECT visitor_id, visitor_total FROM visitors WHERE visitor_ip4 = ? LIMIT 1');
			$sql->bindParam(1, $ipv4, PDO::PARAM_STR); // Bind parameter
			$sql->execute(); // Execute prepared statement
			$ip_dump = $sql->fetch(PDO::FETCH_ASSOC);

			if($ip_dump != NULL) {
				$visit_total = $ip_dump['visitor_total'];
				$visitor_id = $ip_dump['visitor_id'];
				$visit_total++;

				$sql = $conn->prepare('UPDATE visitors SET visitor_total = ? WHERE visitor_ip4 = ?');
				$sql->bindParam(1, $visit_total, PDO::PARAM_INT); // Bind parameter
				$sql->bindParam(2, $ipv4, PDO::PARAM_STR); // Bind parameter
				$sql->execute(); // Execute prepared statement
			} else {
				$visit_total = 1;
				$visitor_date = date("Y-m-d H:i:s", time());

				$sql = $conn->prepare('INSERT INTO visitors (visitor_ip4, visitor_date, visitor_total) VALUES (?, ?, ?)');
				$sql->bindParam(1, $ipv4, PDO::PARAM_STR); // Bind parameter
				$sql->bindParam(2, $visitor_date, PDO::PARAM_STR); // Bind parameter
				$sql->bindParam(3, $visit_total, PDO::PARAM_INT); // Bind parameter
				$sql->execute(); // Execute prepared statement
				$visitor_id = $conn->lastInsertId();
			}

			setcookie('offline-contact-us', $visitor_id, time() + 3600); // 1-hour cookie
		}
	}

	visitor_record();
	$title = "";
	include('header.php');
?>

	<form class="contact-form" action="process.php" method="post">
		<p class="contact-text">
			A proof of concept for a contact form that accepts input offline. Will utilize jQuery, HTML5 Cache Manifest and PHP to compete the task.
		</p>
		<div class="contact-element">
			<label class="contact-label" for="contact_name">Full Name</label>
			<input type="hidden" name="contact_ipv4" id="contact_ipv4" value="<?php echo find_ip(); ?>" />
			<input class="contact-input" type="text" name="contact_name" id="contact_name" placeholder="full name..." maxlength="40" title="**Required** Please enter your Full Name so I can address you properly!" required="required" />
		</div>
		<div class="contact-element">
			<label class="contact-label" for="contact_email">Email</label>
			<input class="contact-input" type="email" name="contact_email" id="contact_email" placeholder="email..." maxlength="60" title="**Required** Please enter your Email so I can promptly reply!" required="required" />
		</div>
		<div class="contact-element">
			<label class="contact-label" for="contact_message">Message</label>
			<textarea class="contact-textarea" name="contact_message" id="contact_message" placeholder="message..." maxlength="300" title="*Required* Please enter your Questions and/or Comments so I can cry or dance around the room!" required="required"></textarea>
		</div>
		<div class="contact-element">
			<input class="contact-button" type="submit" name="submit" value="Send Message!" />
		</div>
	</form>
	<script type="text/javascript">
		// Check to see if there are any saved items to send and send them!
		function checkLocalStorage() {
			if(window.navigator.onLine == true) {
				if(typeof(Storage) !== "undefined") {
					if(localStorage["contact_name"]) {
						$.ajax({
							type: "POST",
							url: "process_offline.php",
							data: {
								contact_name: localStorage.getItem('contact_name'),
								contact_ipv4: localStorage.getItem('contact_ipv4'),
								contact_email: localStorage.getItem('contact_email'),
								contact_message: localStorage.getItem('contact_message')
							},
							success: function(data) {
								if(data == 'FAIL') {
									window.alert("Your stored messages could not be sent!");
								} else {
									window.alert("All stored messages have been sent!");
									localStorage.clear();
								}
								
							}
						});
					} else {
						// Do nothing
					}
				}
			} else {
				$("form").submit(function() {
					return false;
				});
			}
		}

		$(document).ready(function () {
			$('.contact-button').click(function() {
				if(window.navigator.onLine == false) {
					if(typeof(Storage) !== "undefined") {
						window.alert("Because the application is offline, your message will be saved to localStorage. Next time you are online and visit this site, the message will be automatically sent!");
						// Save to localStorage
						localStorage[$('#contact_ipv4').attr('name')] = $('#contact_ipv4').val();
						localStorage[$('#contact_name').attr('name')] = $('#contact_name').val();
						localStorage[$('#contact_email').attr('name')] = $('#contact_email').val();
						localStorage[$('#contact_message').attr('name')] = $('#contact_message').val();
					} else {
						window.alert("Your message could not be sent because you are offline. Your message could not be saved because your browser does not currently support localStorage.");
					}
				}
			});
		});
	</script>
</body>
</html>