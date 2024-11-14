<?php
// Start session to display error messages
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>
	<!-- Link to external CSS -->
	<link rel="stylesheet" href="assets/style.css">
</head>

<body>
	<!-- Centered Login Form -->
	<div class="login-container">
		<h2>Login</h2>

		<!-- Display error message if login fails -->
		<?php
        if (isset($_SESSION['login_error'])) {
            echo '<div class="error-message">' . $_SESSION['login_error'] . '</div>';
            unset($_SESSION['login_error']); // Clear the error message after displaying it
        }
        ?>

		<!-- Simple login form -->
		<form method="post" action="php/login/login.php">
			<div class="input-group">
				<input type="text" name="username" placeholder="Username" required><br>
			</div>
			<div class="input-group">
				<input type="password" name="password" placeholder="Password" required><br>
			</div>
			<div class="input-group">
				<input type="submit" value="Login">
			</div>
		</form>
	</div>
</body>

</html>