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
	<link rel="stylesheet" href="assets/styleLogin.css">
</head>

<body>
	<!-- Centered Login Section -->
	<div class="login-wrapper">
		<div class="login-container">
			<!-- Logo Section -->
			<div class="login-image">
				<img src="assets/images/logo.png" alt="Login Logo">
			</div>

			<!-- Error Message -->
			<?php
			if (isset($_SESSION['login_error'])) {
				echo '<div class="error-message">' . $_SESSION['login_error'] . '</div>';
				unset($_SESSION['login_error']);
			}
			?>

			<!-- Login Form -->
			<form method="post" action="php/login/login.php" class="login-form">
				<div class="input-group">
					<input type="text" name="username" placeholder="User ID" required>
				</div>
				<div class="input-group">
					<input type="password" name="password" placeholder="Password" required>
				</div>
				<div class="input-group">
					<input type="submit" value="Login" class="login-btn">
				</div>
			</form>
		</div>
	</div>
</body>

</html>
