<?php
include('../../../includes/session.php');

// Include database connection
require_once('../../../classes/DBConnection.php');
$db = new DBConnection('../../mySettings.ini');

// Define variables and initialize them with empty values
$title = "";
$content = "";
$userID = "";
$confirmation = "";

$title_err = "";
$content_err = "";

// Processing the form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Validate the title
	if (empty(trim($_POST["title"]))) {
		$title_err = "Please enter a title.";
	} else {
		$title = trim($_POST["title"]);
	}

	// Validate the content
	if (empty(trim($_POST["content"]))) {
		$content_err = "Please enter some content.";
	} else {
		$content = trim($_POST["content"]);
	}

	// Check input errors before inserting into database
	if (empty($title_err) && empty($content_err)) {
		// Prepare an insert statement
		$sql = "INSERT INTO articles (ArticleTitle, ArticleContent, UserID) VALUES (:title, :content, :id)";

		// Set parameters
		$param_title = $title;
		$param_content = $content;
		$param_id = $_SESSION["id"];

		// Execute insert
		$createArticle = $db->doQuery($sql, array(':title' => $param_title, ':content' => $param_content, ':id' => $param_id));
		$confirmation = "Article created. <a class='alert-link' href='../loggedin.php'>Return to dashboard</a>.";
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>Create Article</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<script src="../../scripts/jquery-3.4.1.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="../../styles.css" />
</head>
<body class="text-center">
	<div class="container">
		<div class="row">
			<div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
				<div class="card card-signin my-5">
					<div class="card-body">
						<h5 class="card-title text-center">Create Article</h5>
						<h6 class="text-center">Fill out this form to create an article</h6>
						<form class="form-signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
							<?php echo (!empty($confirmation)) ? '<div class="alert alert-success" role="alert">' . $confirmation . '</div>' : ''; ?>
							<?php echo (!empty($title_err)) ? '<div class="alert alert-danger" role="alert">' . $title_err . '</div>' : ''; ?>
							<div class="form-label-group">
								<input type="text" id="inputTitle" name="title" class="form-control" placeholder="Title" value="<?php echo $title; ?>" required autofocus>
								<label class="sr-only" for="inputTitle">Title</label>
							</div>

							<?php echo (!empty($content_err)) ? '<div class="alert alert-danger" role="alert">' . $content_err . '</div>' : ''; ?>
							<div class="form-label-group">
								<input type="text" id="inputContent" name="content" class="form-control" placeholder="Content" value="<?php echo $content; ?>" required />
								<label class="sr-only" for="inputContent">Content</label>
							</div>

							<div class="form-label-group">
								<button class="btn btn-lg btn-primary text-uppercase double-btn" type="submit">Create</button>
								<a class="btn bn-lg btn-default text-uppercase double-btn" href="../loggedin.php">Cancel</a>
							</div>
						</form>

					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>