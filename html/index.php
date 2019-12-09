<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if they are, then redirect them to the loggedin page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
	header("location: loggedin/loggedin.php");
	exit;
}

// Include database connection
require_once ('../classes/DBConnection.php');
$db = new DBConnection('mySettings.ini');

// Define variables and initialize with empty values
$username = "";
$password = "";

$username_err = "";
$password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Check if the username is empty
	if (empty(trim($_POST["username"]))) {
		$username_err = "Please enter your username.";
	} else {
		$username = trim($_POST["username"]);
	}

	// Check if the password is empty
	if (empty(trim($_POST["password"]))) {
		$password_err = "Please enter your password.";
	} else {
		$password = trim($_POST["password"]);
	}

	// Validate credentials
	if (empty($username_err) && empty($password_err)) {
		// Prepare a statement
		$sql = "SELECT UserId, UserName, UserPassword FROM users WHERE Username = :username";
		$param_username = $username;

		$checkUser = $db->doQuery($sql, array(':username' => $param_username));

		// Check if the user exists, if so, then verify the password
		if (!empty($checkUser)) {
			$id = $checkUser[0][0];
			$hashed_password = $checkUser[0][2];
			if (password_verify($password, $hashed_password)) {
				// Password is correct, so start a new session
				session_start();

				// Store data in session variables
				$_SESSION["loggedin"] = true;
				$_SESSION["id"] = $id;
				$_SESSION["username"] = $username;

				// Redirect the user to the logged in page
				header("location: loggedin/loggedin.php");
			} else {
				// Display an error message if the password is not valid
				$password_err = "The password you entered was not valid.";
			}
		} else {
			// Display an error message if the user doesn't exist
			$username_err = "No account found with that username.";
		}
	}
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
							<?php echo (!empty($username_err)) ? '<div class="alert alert-danger" role="alert">' . $username_err . '</div>' : ''; ?>
							<div class="form-label-group">
								<input type="text" id="inputUsername" name="username" class="form-control" placeholder="Username" value="<?php echo $username; ?>" required autofocus>
								<label class="sr-only" for="inputUsername">Username</label>
							</div>

							<?php echo (!empty($password_err)) ? '<div class="alert alert-danger" role="alert">' . $password_err . '</div>' : ''; ?>
							<div class="form-label-group">
								<input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" value="<?php echo $password; ?>" required>
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