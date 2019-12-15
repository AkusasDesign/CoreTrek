<?php

include('../../includes/session.php');
require_once ('../../classes/Users.php');
$update = new Users();

// Processing the form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$update->updatePassword(trim($_POST['new_password']), trim($_POST['confirm_password']), $_SESSION['id'], "UPDATE users SET UserPassword = ? WHERE UserID = ?");
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
							<?php echo (!empty($update->registerPassword_err)) ? '<div class="alert alert-danger" role="alert">' . $update->registerPassword_err . '</div>' : ''; ?>
							<div class="form-label-group">
								<input type="password" id="inputNewPassword" name="new_password" class="form-control" placeholder="New Password" value="<?php echo $update->registerPassword; ?>" required autofocus>
								<label class="sr-only" for="inputNewPassword">New Password</label>
							</div>

							<?php echo (!empty($update->registerConfirmPassword_err)) ? '<div class="alert alert-danger" role="alert">' . $update->registerConfirmPassword_err . '</div>' : ''; ?>
							<div class="form-label-group">
								<input type="password" id="inputConfirmPassword" name="confirm_password" class="form-control" placeholder="Confirm Password" value="<?php echo $update->registerConfirmPassword; ?>" required />
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