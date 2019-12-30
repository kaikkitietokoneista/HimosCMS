<?php
	include 'asetukset.php';

	if ($_POST["kayttajanimi"] != "") {
		 if ($_POST["kayttajanimi"] === $käyttäjänimi) {
			 if (password_verify($_POST["salasana"], $salasana)) {
				 $_SESSION["kirjautunut"] = $käyttäjänimi;
			 }
		 }
	}

	if (!isset($_SESSION["kirjautunut"])) {

?>

<!DOCTYPE html>
<html>
<head>
	<title>Kirjaudu</title>
	<link href="//netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
  <script src="//netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>
</head>
<body>
	<div class="container">
		<h2>Kirjaudu</h2>
		<form method="post">
			<div class="form-row">
				<div class="form-group col-md-12">
					<label for="kayttajanimi">Käyttäjänimi:</label>
				 	<input type="text" class="form-control" id="kayttajanimi" name="kayttajanimi">
				</div>
				<div class="form-group col-md-12">
					<label for="salasana">Salasana:</label>
				 	<input type="password" class="form-control" id="salasana" name="salasana">
				</div>
				<div class="form-group col-md-12">
					<input type='submit' class="btn btn-primary" value='Kirjaudu'>
				</div>
			</div>
		</form>
	</div>
</body>
</html>
<?php
	} else {
		if ($_GET["kirjaudu"] === "ulos") {
			session_unset();

			session_destroy();
			?>
			<script>
			location.search = "";
			</script>
			<?php
		} else {
			?>
			<!DOCTYPE html>
			<html>
			<head>
				<title>Kirjaudu</title>
				<link href="//netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
			  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
			  <script src="//netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>
			</head>
			<body>
				<div class="container">
					<h2>Olet kirjautunut</h2>
					<p>Mene sivun <a href="asetuksetgui.php">asetuksiin</a>.</p>
				</div>
			</body>
			</html>
			<?php
		}
	}
