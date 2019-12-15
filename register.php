<?php
require_once ('../../classes/Users.php');
$register = new Users();

// Processing the form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$register->registerUser(trim($_POST['username']), trim($_POST['full_name']), trim($_POST['password']), trim($_POST['confirm_password']), "INSERT INTO users (UserName, UserPassword, UserFullName) VALUES (?, ?, ?)");
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
							<?php echo (!empty($register->confirmation)) ? '<div class="alert alert-success" role="alert">' . $register->confirmation . '</div>' : ''; ?>
							<?php echo (!empty($register->registerUsername_err)) ? '<div class="alert alert-danger" role="alert">' . $register->registerUsername_err . '</div>' : ''; ?>
							<div class="form-label-group">
								<input type="text" id="inputUsername" name="username" class="form-control" placeholder="Username" value="<?php echo $register->registerUsername; ?>" required autofocus>
								<label class="sr-only" for="inputUsername">Username</label>
							</div>

							<?php echo (!empty($register->registerFullName_err)) ? '<div class="alert alert-danger" role="alert">' . $register->registerFullName_err . '</div>' : ''; ?>
							<div class="form-label-group">
								<input type="text" id="inputFullName" name="full_name" class="form-control" placeholder="Full Name" value="<?php echo $register->registerFullName; ?>" required />
								<label class="sr-only" for="inputFullName">Full Name</label>
							</div>

							<?php echo (!empty($register->registerPassword_err)) ? '<div class="alert alert-danger" role="alert">' . $register->registerPassword_err . '</div>' : ''; ?>
							<div class="form-label-group">
								<input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" value="<?php echo $register->registerPassword; ?>" required>
								<label class="sr-only" for="inputPassword">Password</label>
							</div>

							<?php echo (!empty($register->registerConfirmPassword_err)) ? '<div class="alert alert-danger" role="alert">' . $register->registerConfirmPassword_err . '</div>' : ''; ?>
							<div class="form-label-group">
								<input type="password" id="inputConfirmPassword" name="confirm_password" class="form-control" placeholder="Confirm Password" value="<?php echo $register->registerConfirmPassword; ?>" required />
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