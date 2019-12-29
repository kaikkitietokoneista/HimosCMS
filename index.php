<?php
include 'asetukset.php';

 ?>
<style type="text/css">
  <?php
    //Custom CSS
   ?>
</style>
  <link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
  <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
  <script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>

<div class="container">
<?php

    if ($automylaosa == "true") {
      include 'automylaosa.php';
    } else {
      include 'osat/ylaosa';
    }

  	echo "<br>";

    if ($_GET["s"]) {
      $haluttusivu = $_GET["s"];
      if (file_exists("sivut/" . $haluttusivu) == TRUE) {
        $tiedosto = fopen("sivut/" . $haluttusivu, "r");
        $sisältö = fread($tiedosto, filesize("sivut/" . $haluttusivu));
        echo $sisältö;

      } else {
        ?>
        <div class="alert alert-warning alert-dismissible" role="alert">
          <span type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></span>
          <strong>Sivua ei ole olemassa.</strong>
        </div>
        <?php
      }
    } else {
      include 'osat/keskiosa';
    }

  	echo "<br>";

  	include 'osat/alaosa';

?>
</div>