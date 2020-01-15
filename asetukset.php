<?php

session_start();
/*

$asetukset = new stdClass();

$asetukset->nimi = $_POST["sivunnimi"];
$asetukset->hallintasalasana = $_POST["passu"];
$asetukset->hallintanimi = $_POST["kayttajanimi"];
$asetukset->automylaosa = $_POST["automylaosa"];

$json = json_encode($asetukset);

echo $json; */
//TÄNNE KAIKKI SESSION HALLINTA JA ONKO KIRJAUTUNUT TARKISTAMINEN

$asetustiedosto = fopen("asetukset.json", "r") or die("Kriittinen virhe!");
$asetusjson = fread($asetustiedosto,filesize("asetukset.json"));
fclose($asetustiedosto);

$sivunnimi = json_decode($asetusjson)->{'nimi'};
$käyttäjänimi = json_decode($asetusjson)->{'hallintanimi'};
$salasana = json_decode($asetusjson)->{'hallintasalasana'};
$automylaosa = json_decode($asetusjson)->{'automylaosa'};
$teema = json_decode($asetusjson)->{'teema'};

if (!isset($_SESSION["kirjautunut"])) {
  $kirjautunut = FALSE;
} else {
  $kirjautunut = TRUE;
}
