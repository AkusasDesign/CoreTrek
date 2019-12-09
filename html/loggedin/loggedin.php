<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect them to the login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
	header("location: ../index.php");
	exit;
}

// Include database connection
require_once('../../classes/DBConnection.php');
$db = new DBConnection('../mySettings.ini');

// Retrieve array of articles from database
// Prepare a select statement
$sql = "SELECT * FROM articles";
// Execute select
$retrieveArticles = $db->doQuery($sql, array());

// Function to retrieve a selected article
function selectArticle($articleID) {
	// Prepare a select statement
	$sqlSelectArticle = "DELETE FROM articles WHERE ArticleID = :id";

	// Set parameters
	$param_id = $articleID;

	// Execute select
	$retrieveArticle = $db->doQuery($sqlSelectArticle, array(':id' => $param_id));

	// Set variables
	$articleTitle = $retrieveArticle[0]['ArticleTitle'];
	header("location: /");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST["deleteButton"])) {
		// Prepare a select statement
		$sqlSelectArticle = "DELETE FROM articles WHERE ArticleID = :id";

		// Set parameters
		$param_id = $_POST["deleteButton"];

		// Execute select
		$retrieveArticle = $db->doQuery($sqlSelectArticle, array(':id' => $param_id));
		header("location: ../index.php");
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>Dashboard</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<script src="../scripts/jquery-3.4.1.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	<script src="https://kit.fontawesome.com/34d30aa342.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="../styles.css" />
</head>
<body id="dashboard" class="text-center">
	<nav class="navbar sticky-top navbar-light bg-light">
		<div class="navbar-brand" href="#">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b></div>
		<form class="form-inline">
			<a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
			<a href="logout.php" class="btn btn-danger">Sign Out</a>
		</form>
	</nav>
	<div class="page-header">
		<h1>Welcome to SimplePress</h1>
	</div>
	<div>
		<p>
			<a href="articles/create.php" class="btn btn-primary">Create Article</a>
		</p>
		<table class="table table-hover">
			<thead class="thead-light">
				<th scope="col">Title</th>
				<th scope="col">Content</th>
				<th scope="col">Author</th>
				<th scope="col">Edit</th>
				<th scope="col">Delete</th>
			</thead>
			<tbody>
				<!-- Loops through the array retrieved from the database and display a table row for each element -->
				<?php foreach ($retrieveArticles as $article) { ?>
					<tr>
						<td>
							<a href="articles/edit.php?articleID=<?php echo $article['ArticleID']; ?>">
								<?php
									// Display article name
									echo $article['ArticleTitle'];
								?>
							</a>		
						</td>
						<td>
							<?php echo $article['ArticleContent']; ?>
						</td>
						<td>
							<?php
								// Check against the users database for who the author is
								// Prepare a select statement
								$sqlUser = "SELECT UserFullName FROM users WHERE UserID = :id";
								// Execute select
								$userID = $article['UserID'];
								$retrieveUser = $db->doQuery($sqlUser, array(':id' => $userID));
								echo $retrieveUser[0]['UserFullName'];
							?>
						</td>
						<td>
							<a href="articles/edit.php?articleID=<?php echo $article['ArticleID']; ?>">
								<i class="fas fa-pen"></i>
							</a>
						</td>
						<td>
							<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
								<button class="delete" type="submit" name="deleteButton" value="<?php echo $article['ArticleID']; ?>" data-toggle="modal" data-target="#deleteModal">
									<i class="fas fa-trash"></i>
								</button>
							</form>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</body>
</html>