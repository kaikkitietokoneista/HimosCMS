<?php
  include 'asetukset.php';

  if ($kirjautunut == TRUE) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Hallinnoi sivuja</title>
  <link href="//netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
  <script src="//netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>
</head>
<body>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="/"><?php echo $sivunnimi; ?></a>
      </div>
      <ul class="nav navbar-nav">
        <li><a href="#">Työpöytä (Dashboard)</a></li>
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
    <?php
    //Sivun poistaminen
    if ($_GET["poista"] != "") {
      $poistettavasivu = __DIR__ . "/sivut/" . $_GET["poista"];
      unlink($poistettavasivu);
        ?>
        <div class="alert alert-success alert-dismissible" role="alert">
          <span type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></span>
          <strong>Sivu on poistettu</strong> nimeltä <?php echo $_GET["poista"]; ?>
          <!-- Tyhjentää GET parametrit JS:n avulla -->
          <script>
            location.search = "";
          </script>
        </div>
        <?php
    }

    //Uuden sivun luonti jos sivunnimi ei ole tyhjä
    if ($_POST["sivunnimi"] != "") {
      $tiedosto = $_POST["sivunnimi"];
      //Tarkastaa ono sivu jo olemassa ja estää näin ylikirjoituksen
      if (file_exists(__DIR__ . "/sivut/" . $tiedosto) == TRUE) {
        ?>
        <div class="alert alert-warning alert-dismissible" role="alert">
          <span type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></span>
          <strong>Sivu on jo olemassa</strong> nimeltä <?php echo  $_POST["sivunnimi"]; ?>
        </div>
        <?php
      } else {
        $tiedosto = fopen(__DIR__ . "/sivut/" . $tiedosto, "w") or die("Kriittinen virhe!");
        fwrite($tiedosto, '<h2>Otsikko</h2><p>Voit aloittaa muokkaamisen</p><link rel="stylesheet" href="teemat/'.$teema.'.css">');
        fclose($tiedosto);
        ?>
        <div class="alert alert-success alert-dismissible" role="alert">
          <span type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></span>
          <strong>Uusi sivu luotu</strong> nimellä <?php echo  $_POST["sivunnimi"]; ?>.
        </div>
        <?php
      }
    }
    ?>
    <form method="post">
      <div class="form-row">
        <div class="form-group col-md-12">
          <h2>Luo uusi sivu</h2>
        </div>
       <div class="form-group col-md-10">
          <input type="text" class="form-control" name="sivunnimi" placeholder="Uuden sivun nimi..." name="passu" id="inputtikayttajanimi">
        </div>
        <div class="form-group col-md-2">
          <input type='submit' class="btn btn-primary" value='Luo'>
        </div>
      </div>
    </form>
    </div>
    <div class="container">
      <ul class="list-group">
        <li class="list-group-item active">Etusivun osat</li>
        <?php
        //Etusivun osat
        $arrayetusivuista = scandir("osat");
        foreach ($arrayetusivuista as $key => $value) {
          if ($value != ".") {
            if ($value != "..") {
              echo '<li class="list-group-item">' . $value . '</li>';
            }
          }
        }
        echo '<li class="list-group-item active">Muut sivut</li>';
        //Sivut
        $arraysivuista = scandir("sivut");
        foreach ($arraysivuista as $key => $value) {
          if ($value != ".") {
            if ($value != "..") {
              echo '<li class="list-group-item">' . $value . '<span class="badge"><a style="color:white;" href="index.php?s=' . $value . '">näytä</a></span><span class="badge"><a style="color:white;" href="?poista=' . $value . '">poista</a></span></li>';
            }
          }
        }
      ?>
      </ul>
    </div>
  </body>
</html>
<?php
} else {
?>
<a href="kirjaudu.php">Kirjaudu</a>
<?php
}

 ?>
