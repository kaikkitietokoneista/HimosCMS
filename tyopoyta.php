<?php
  include 'asetukset.php';

  if ($kirjautunut == TRUE) {

  if ($_GET["sivu"] != "") {
    $haluttusivu = $_GET["sivu"];

    //TODO echo file_exists("test.txt"); tarvitsee clearcachen avuksi
    if (file_exists("osat/" . $haluttusivu) == TRUE) {
      $tiedosto = fopen("osat/" . $haluttusivu, "r");
      $sisältö = fread($tiedosto, filesize("osat/" . $haluttusivu));
      echo $sisältö;
    } else {
      $tiedosto = fopen("sivut/" . $haluttusivu, "r");
      $sisältö = fread($tiedosto, filesize("sivut/" . $haluttusivu));
      echo $sisältö;
    }
  } if ($_GET["sivu"] == "") {

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Muokkain</title>
  <link href="//netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
  <script src="//netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>
  <link href="//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.css" rel="stylesheet">
  <script src="//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.js"></script>
</head>
<body>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="/"><?php echo $sivunnimi; ?></a>
      </div>
      <ul class="nav navbar-nav">
        <li><a href="tyopoyta.php">Työpöytä (Dashboard)</a></li>
        <li><a href="muokkain.php">Muokkaa sivua</a></li>
        <li><a href="hallinnoi.php">Hallinnoi sivuja</a></li>
        <li><a href="asetuksetgui.php">Asetukset</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a class="navbar-right" href="kirjaudu.php?kirjaudu=ulos"><span class="glyphicon glyphicon-log-out"></span>Kirjaudu ulos</a></li>
      </ul>
    </div>
  </nav>
  <div class="container">
    <h2>Statistiikat</h2>
    <pre class="pre-scrollable">
      <table style="width:100%">
        <tr>
          <th>IP-osoite</th>
          <th>Sivu</th>
          <th>Päivä</th>
        </tr>
        <?php
        $katsotutusivut = fopen("katsotutsivut.txt", "r") or die("Unable to open file!");
        echo fread($katsotutusivut,filesize("katsotutsivut.txt"));
        fclose($katsotutusivut);
         ?>
       </table>
    </pre>
  </div>
</body>
</html>
<?php
  }
} else {
  ?>
  <a href="kirjaudu.php">Kirjaudu</a>
  <?php
}

   ?>
