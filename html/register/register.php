<?php
require_once ('../../classes/DBConnection.php');
$db = new DBConnection('../mySettings.ini');

// Define variables and initialize them with empty values
$username = "";
$full_name = "";
$password = "";
$confirm_password = "";
$confirmation = "";

$username_err = "";
$full_name_err = "";
$password_err = "";
$confirm_password_err = "";

// Processing the form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the Username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        // Prepare a select statement
        $sqlUsername = "SELECT UserName FROM users WHERE UserName = :username";
        $param_username = trim($_POST["username"]);

        $checkUsername = $db->doQuery($sqlUsername, array(':username' => $param_username));
		if (!empty($checkUsername)) {
			if (in_array($param_username, $checkUsername[0])) {
				$username_err = "This username is already taken.";
				// Setting the username, so that the user can see what username they tried, that is already taken.
				$username = trim($_POST["username"]);
			}
		} else {
			$username = trim($_POST["username"]);
		}
    }

	// Validate the Full Name
	if (empty(trim($_POST["full_name"]))) {
		$full_name_err = "Please enter your full name.";
	} else {
		$full_name = trim($_POST["full_name"]);
	}

    // Validate the Password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif(strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have atleast 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate the Confirm Password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm the password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check input errors before inserting into database
	if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
		// Prepare an insert statement
		$sqlRegister = "INSERT INTO users (UserName, UserPassword, UserFullName) VALUES (:username, :password, :full_name)";
		
		// Set parameters
		$param_username = $username;
		// Create a hashed password
		$param_password = password_hash($password, PASSWORD_DEFAULT);
		$param_full_name = $full_name;

		// Execute insert
		$registerUser = $db->doQuery($sqlRegister, array(':username' => $param_username, ':password' => $param_password, ':full_name' => $param_full_name));
		$confirmation = 'User registered. <a href="../index.php" class="alert-link">Login here</a>.';
	}
}
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>Register</title>
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
						<h5 class="card-title text-center">Register</h5>
						<h6 class="text-center">Fill out the form to create an account</h6>
						<form class="form-signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
							<?php echo (!empty($confirmation)) ? '<div class="alert alert-success" role="alert">' . $confirmation . '</div>' : ''; ?>
							<?php echo (!empty($username_err)) ? '<div class="alert alert-danger" role="alert">' . $username_err . '</div>' : ''; ?>
							<div class="form-label-group">
								<input type="text" id="inputUsername" name="username" class="form-control" placeholder="Username" value="<?php echo $username; ?>" required autofocus>
								<label class="sr-only" for="inputUsername">Username</label>
							</div>

							<?php echo (!empty($full_name_err)) ? '<div class="alert alert-danger" role="alert">' . $full_name_err . '</div>' : ''; ?>
							<div class="form-label-group">
								<input type="text" id="inputFullName" name="full_name" class="form-control" placeholder="Full Name" value="<?php echo $full_name; ?>" required />
								<label class="sr-only" for="inputFullName">Full Name</label>
							</div>

							<?php echo (!empty($password_err)) ? '<div class="alert alert-danger" role="alert">' . $password_err . '</div>' : ''; ?>
							<div class="form-label-group">
								<input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" value="<?php echo $password; ?>" required>
								<label class="sr-only" for="inputPassword">Password</label>
							</div>

							<?php echo (!empty($confirm_password_err)) ? '<div class="alert alert-danger" role="alert">' . $confirm_password_err . '</div>' : ''; ?>
							<div class="form-label-group">
								<input type="password" id="inputConfirmPassword" name="confirm_password" class="form-control" placeholder="Confirm Password" value="<?php echo $confirm_password; ?>" required />
								<label class="sr-only" for="inputConfirmPassword">Confirm Password</label>
							</div>

							<div class="form-label-group">
								<button class="btn btn-lg btn-block btn-primary text-uppercase" type="submit">Register</button>
							</div>
						</form>
						<p>Already have an account? <a href="../index.php">Login here</a>.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>