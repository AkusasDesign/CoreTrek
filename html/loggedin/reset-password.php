<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect them to the login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
	header("location: ../index.php");
	exit;
}

// Include database connection
require_once ('../../classes/DBConnection.php');
$db = new DBConnection('../mySettings.ini');

// Define variables and initialize them with empty values
$new_password = "";
$confirm_password = "";

$new_password_err = "";
$confirm_password_err = "";

// Processing the form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Validate the new password
	if (empty(trim($_POST["new_password"]))) {
		$new_password_err = "Please enter a new password.";
	} elseif (strlen(trim($_POST["new_password"])) < 6) {
		$new_password_err = "Password must have atleast 6 characters.";
	} else {
		$new_password = trim($_POST["new_password"]);
	}

	// Validate the confirm password
	if (empty(trim($_POST["confirm_password"]))) {
		$confirm_password_err = "Please confirm the password.";
	} else {
		$confirm_password = trim($_POST["confirm_password"]);
		if (empty($new_password_err) && ($new_password != $confirm_password)) {
			$confirm_password_err = "Password did not match.";
		}
	}

	// Check input errors before updating the database
	if (empty($new_password_err) && empty($confirm_password_err)) {
		// Prepare an update statement
		$sql = "UPDATE users SET UserPassword = :password WHERE UserID = :id";
		// Set parameters
		$param_id = $_SESSION["id"];
		// Hash the password
		$param_password = password_hash($new_password, PASSWORD_DEFAULT);

		// Update the password. Upon completion destroy the session, and redirect the user to the login page
		$UpdatePassword = $db->doQuery($sql, array(':password' => $param_password, ':id' => $param_id));
		session_destroy();
		header("location: ../index.php");
		exit();
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>Reset Password</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<script src="../scripts/jquery-3.4.1.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="../styles.css" />
</head>
<body class="text-center">
	<div class="container">
		<div class="row">
			<div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
				<div class="card card-signin my-5">
					<div class="card-body">
						<h5 class="card-title text-center">Reset Password</h5>
						<h6 class="text-center">Fill out this form to change your password</h6>
						<form class="form-signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
							<?php echo (!empty($new_password_err)) ? '<div class="alert alert-danger" role="alert">' . $new_password_err . '</div>' : ''; ?>
							<div class="form-label-group">
								<input type="password" id="inputNewPassword" name="new_password" class="form-control" placeholder="New Password" value="<?php echo $new_password; ?>" required autofocus>
								<label class="sr-only" for="inputNewPassword">New Password</label>
							</div>

							<?php echo (!empty($confirm_password_err)) ? '<div class="alert alert-danger" role="alert">' . $confirm_password_err . '</div>' : ''; ?>
							<div class="form-label-group">
								<input type="password" id="inputConfirmPassword" name="confirm_password" class="form-control" placeholder="Confirm Password" value="<?php echo $confirm_password; ?>" required />
								<label class="sr-only" for="inputConfirmPassword">Confirm Password</label>
							</div>

							<div class="form-label-group">
								<button class="btn btn-lg btn-primary text-uppercase double-btn" type="submit">Submit</button>
								<a class="btn bn-lg btn-default text-uppercase double-btn" href="loggedin.php">Cancel</a>
							</div>
						</form>

					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>