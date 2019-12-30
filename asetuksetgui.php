<?php
  include 'asetukset.php';

  if ($kirjautunut == TRUE) {

    $asetukset = new stdClass();
    if ($_POST["sivunnimi"] != "" || $_POST["passu"] != "" || $_POST["kayttajanimi"] != "") {
      $asetukset->nimi = $_POST["sivunnimi"];
      if ($_POST["passu"] != "") {
        $suolattupassu = password_hash($_POST["passu"], PASSWORD_DEFAULT);
        $asetukset->hallintasalasana = $suolattupassu;
      }
      $asetukset->hallintanimi = $_POST["kayttajanimi"];
      if (isset($_POST['automylaosa'])) {
        $asetukset->automylaosa = "true";
      } else {
        $asetukset->automylaosa = "false";
      }
      $asetukset->suolo = $_POST["suolo"];

      $json = json_encode($asetukset);

      $jsontiedosto = fopen("asetukset.json", "w") or die("Kriittinen virhe!");
      fwrite($jsontiedosto, $json);
    }
  ?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Asetukset</title>
    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>
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
      <form method="post" action="asetuksetgui.php">
        <div class="form-row">
         <div class="form-group col-md-6">
            <label for="inputtikayttajanimi">Käyttäjänimi:</label>
            <input type="text" class="form-control" name="kayttajanimi" value="<?php echo $käyttäjänimi ?>" name="passu" id="inputtikayttajanimi">
          </div>
          <div class="form-group col-md-6">
            <label for="inputtipassu">Salasana:</label>
            <input type="password" class="form-control" name="passu" placeholder="Uusi salasana..." name="passu" id="inputtipassu">
          </div>
          <div class="form-group col-md-12">
            <label for="sivunnimi">Sivun nimi</label>
            <input type="text" class="form-control" id="sivunnimi" name="sivunnimi" value="<?php echo $sivunnimi;?>">
          </div>
          <div class="form-group col-md-11">
            <strong>Automaattinen yläosa</strong>
          </div>
          <div class="form-group col-md-1">
            <input type="checkbox" class="form-control" id="automylaosa" name="automylaosa" <?php if ($automylaosa == "true") {echo "checked";}?>>
          </div>
          <div class="form-group col-md-12">
            <input type='submit' class="btn btn-primary" value='Tallenna'>
          </div>
        </div>
      </form>
    </div>
    <div class="container">
        <hr>
        <?php
        if ($_POST["luovarmuuskopio"] === "kyllä") {
          $zip = new ZipArchive;
          if ($zip->open(date("Y-m-d") . '.zip') === TRUE) {
              //Etusivun osat
              $arrayetusivuista = scandir(__DIR__ . "/osat/");
              foreach ($arrayetusivuista as $key => $value) {
                if ($value != ".") {
                  if ($value != "..") {
                    $zip->addFile(__DIR__ . "/osat/" . $value, $value);
                  }
                }
              }
              //Sivut
              $arraysivuista = scandir(__DIR__ . "/sivut/");
              foreach ($arraysivuista as $key => $value) {
                if ($value != ".") {
                  if ($value != "..") {
                    $zip->addFile(__DIR__ . "/sivut/" . $value, $value);
                  }
                }
              }

              $zip->close();
              ?>
              <div class="alert alert-success alert-dismissible" role="alert">
                <span type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></span>
                <strong>Varmuuskopion tekeminen onnistui.</strong>
              </div>
              <?php
            } else {
              ?>
              <div class="alert alert-warning alert-dismissible" role="alert">
                <span type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></span>
                <strong>Varmuuskopion tekeminen epäonnistui.</strong>
              </div>
              <?php
            }
          }
         ?>
        <form method="post" action="">
          <input type="hidden" name="luovarmuuskopio" value="kyllä">
          <div class="form-row">
           <div class="form-group col-md-10">
             <h4>Varmuuskopio (Tulossa pian)</h4>
           </div>
            <div class="form-group col-md-2">
              <input type='submit' class="btn btn-primary" value='Luo'>
            </div>
          </div>
        </form>
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
