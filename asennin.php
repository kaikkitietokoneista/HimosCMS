<?php

  if ($_POST["kayttajanimi"] != "") {
    if (!is_dir("osat") || !is_dir("sivut")) {
      $asennushakemisto = $_POST["asennushakemisto"];
      if (!is_dir($asennushakemisto)) {
        mkdir($asennushakemisto);
        $loki = $loki . "<br>Tehdään hakemistoa.";
      } else {
        $loki = $loki . "<br>Hakemisto olemassa. Asennetaan siihen.";
      }

      chdir($asennushakemisto);

      file_put_contents("asennus.zip", fopen("https://himoscms.r.takanen.eu/HimosCMS_viimeisin.zip", 'r'));

      $zip = new ZipArchive;
      $res = $zip->open('asennus.zip');
      if ($res === TRUE) {
        $zip->extractTo($asennushakemisto);
        $zip->close();
      }
      unlink("asennus.zip");

      //Vasta vikana, jotta ylikirjoittaa zip-paketista tulleen asetukset.json:in
      $asetukset = new stdClass();
      $asetukset->nimi = $_POST["sivunnimi"];
      $suolattupassu = password_hash($_POST["passu"], PASSWORD_DEFAULT);
      $asetukset->hallintasalasana = $suolattupassu;
      $asetukset->hallintanimi = $_POST["kayttajanimi"];

      $json = json_encode($asetukset);

      $jsontiedosto = fopen("asetukset.json", "w") or die("Kriittinen virhe! Korjaus: käytä uudempaa asennusohjelmaa tai ota yhteyttä HimosCMS:n ylläpitoon.");
      fwrite($jsontiedosto, $json);
    } else {
      //Eli osat ja sivut hakemistot ovat olemassa niin resetoi vain passu
      $asetukset = new stdClass();
      $asetukset->nimi = $_POST["sivunnimi"];
      $suolattupassu = password_hash($_POST["passu"], PASSWORD_DEFAULT);
      $asetukset->hallintasalasana = $suolattupassu;
      $asetukset->hallintanimi = $_POST["kayttajanimi"];

      $json = json_encode($asetukset);

      $jsontiedosto = fopen("asetukset.json", "w") or die("Kriittinen virhe! Korjaus: käytä uudempaa asennusohjelmaa tai ota yhteyttä HimosCMS:n ylläpitoon.");
      fwrite($jsontiedosto, $json);
    }
  }

 ?>
<!DOCTYPE html>
<html lang="fi">
  <head>
    <meta charset="UTF-8">
    <title>Asenna HimosCMS</title>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>
  </head>
  <body>
    <div class="container">
      <h2>Asenna HimosCMS</h2>
      <hr>
      <form method="post" action="asennin.php">
        <div class="form-row">
         <div class="form-group col-md-6">
            <label for="inputtikayttajanimi">Uusi käyttäjänimi:</label>
            <input type="text" class="form-control" name="kayttajanimi" placeholder="Käyttäjänimi..." name="passu" id="inputtikayttajanimi" required>
          </div>
          <div class="form-group col-md-6">
            <label for="inputtipassu">Uusi salasana (unohtuneen <a href="#" data-toggle="popover" title="Salasanan palauttaminen" data-content="Voit käyttää tätä asenninta olemassaolevan HimosCMS-asennuksen salasanan palauttamiseen. Säilytät varmuuskopiosi sivusi ja etusivusi osat silloin.">palauttaminen</a>):</label>
            <input type="password" class="form-control" name="passu" placeholder="Uusi salasana..." name="passu" id="inputtipassu" required>
          </div>
          <div class="form-group col-md-12">
            <label for="sivunnimi">Sivun nimi</label>
            <input type="text" class="form-control" id="sivunnimi" name="sivunnimi" placeholder="Sivun nimi..." required>
          </div>
          <div class="form-group col-md-12">
            <label for="asennushakemisto">Asennuksen hakemisto (kirjoita  . asentaaksesi nykyiseen)</label>
            <input type="text" class="form-control" id="asennushakemisto" name="asennushakemisto" placeholder="Sivun nimi..." required>
          </div>
          <div class="form-group col-md-12">
            <input type='submit' class="btn btn-primary" value='Tallenna'>
          </div>
        </div>
      </form>
    </div>
    <script>
    $(document).ready(function(){
      $('[data-toggle="popover"]').popover();
    });
    </script>
  </body>
</html>
