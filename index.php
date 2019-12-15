<?php

// Initialize the session
session_start();

// Check if the user is already logged in, if they are, then redirect them to the loggedin page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
	header("location: loggedin/loggedin.php"); 
	exit;
}

require_once('../classes/Users.php');
$login = new Users();

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$login->validateCredentials(trim($_POST['username']), trim($_POST['password']), "SELECT UserId, UserName, UserPassword FROM users WHERE Username = ?");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>Login</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<script src="scripts/jquery-3.4.1.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body class="text-center">
	<div class="container">
		<div class="row">
			<div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
				<div class="card card-signin my-5">
					<div class="card-body">
						<h5 class="card-title text-center">Sign In</h5>
						<form class="form-signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
							
							<?php echo (!empty($login->username_err)) ? '<div class="alert alert-danger" role="alert">' . $login->username_err . '</div>' : ''; ?>
							<div class="form-label-group">
								<input type="text" id="inputUsername" name="username" class="form-control" placeholder="Username" value="<?php echo $login->username ?>" required autofocus>
								<label class="sr-only" for="inputUsername">Username</label>
							</div>

							<?php echo (!empty($login->password_err)) ? '<div class="alert alert-danger" role="alert">' . $login->password_err . '</div>' : ''; ?>
							<div class="form-label-group">
								<input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" value="<?php echo $login->password; ?>" required>
								<label class="sr-only" for="inputPassword">Password</label>
							</div>

							<div class="form-label-group">
								<button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit">Sign in</button>
							</div>
						</form>
						<p>Don't have an account? <a href="register/register.php">Sign up now</a>.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>