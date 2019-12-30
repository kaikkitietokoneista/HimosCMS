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
        //Tallentaa jos sisalto POST data löytyy
        if ($_POST["sisalto"] != "") {
          $sisältö = $_POST["sisalto"];
          $kohde = $_POST["kohde"];

          if (file_exists("osat/" . $kohde) == TRUE) {
            $tiedosto = fopen("osat/" . $kohde, "w") or die("Kriittinen virhe!");
            fwrite($tiedosto, $sisältö);
            fclose($tiedosto);
            ?>
            <div class="alert alert-success alert-dismissible" role="alert">
              <span type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></span>
              <strong>Tallennettu</strong> kohteeseen <?php echo $kohde; ?>.
            </div>
            <?php
          } else {
            $tiedosto = fopen("sivut/" . $kohde, "w") or die("Kriittinen virhe!");
            fwrite($tiedosto, $sisältö);
            fclose($tiedosto);
            ?>
            <div class="alert alert-success alert-dismissible" role="alert">
              <span type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></span>
              <strong>Tallennettu</strong> kohteeseen <?php echo $kohde; ?>.
            </div>
            <?php
          }
        }
    ?>
    <div class="form-group">
      <select class="form-control" id="valittavasivu">
          <?php
          //Etusivun osat
          $arrayetusivuista = scandir("osat");
          foreach ($arrayetusivuista as $key => $value) {
            if ($value != ".") {
              if ($value != "..") {
                echo "<option>" . $value . "</option>";
              }
            }
          }
          echo "<option disabled>──────────</option>";
          //Sivut
          $arraysivuista = scandir("sivut");
          foreach ($arraysivuista as $key => $value) {
            if ($value != ".") {
              if ($value != "..") {
                echo "<option>" . $value . "</option>";
              }
            }
          }
        ?>
      </select>
    </div>
    <div id="summernote"><h2>Otsikko</h2><p>Voit aloittaa muokkaamisen</p></div>
    <form method="POST" action="" id="lahetyslomake">
      <div class="hiddenit"></div>
      <div class="hiddenit2"></div>
      <input type='submit' class="btn btn-primary" value='Tallenna'>
    </form>
  </div>
  <script>
      var osoitteenalku = location.protocol + "//" + location.hostname + ":" +  location.port +  location.pathname + "?sivu=";
      var kohde = $("#valittavasivu").val();
      $.get(osoitteenalku + kohde, function(data) {
        $('#summernote').summernote('destroy');
        $("#summernote").html("<div id='summernote'>" + data + "</div>");
        $('#summernote').summernote();
      });
      $(".hiddenit2").html("<input type='hidden' name='kohde' value='" + kohde + "''>");


    //Ottaa uuden sisällön editoitavaksi #vallitavasivu:n vaihduttua
    $("#valittavasivu").change(function(){
      var kohde = $("#valittavasivu").val();
      $.get(osoitteenalku + kohde, function(data) {
        $('#summernote').summernote('destroy');
        $("#summernote").html("<div id='summernote'>" + data + "</div>");
        $('#summernote').summernote();
        $(".hiddenit2").html("<input type='hidden' name='kohde' value='" + kohde + "''>");
      });
    });

    function tallennahiddeninputtiin() {
         var hvalue = $('#summernote').summernote('code');
        $(".hiddenit").html("<input type='hidden' name='sisalto' value=' " + hvalue + " '/>");
    }

    setInterval(tallennahiddeninputtiin, 300);
  </script>
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
