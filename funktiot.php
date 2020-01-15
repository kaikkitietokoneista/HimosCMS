<?php
  function poistavanhastatistiikka() {
    if (filesize("katsotutsivut.txt") > 20000) {
      unlink("katsotutsivut.txt");
      $katsotutusivut = fopen('katsotutsivut.txt', 'w');
      fwrite($katsotutusivut, '<tr><td><i>Tietokanta</i></td><td><i>resetoitu</i></td><td><i>'.date("Y/m/d").'</i></td></tr>'. PHP_EOL);
      fclose($katsotutusivut);
    }
  }

  function katsottusivu($ip, $sivu) {
    $katsotutusivut = fopen('katsotutsivut.txt', 'a');
    fwrite($katsotutusivut, '<tr><td>'.$ip.'</td><td>'.$sivu.'</td><td>'.date("Y/m/d").'</td></tr>'. PHP_EOL);
    fclose($katsotutusivut);
    poistavanhastatistiikka();
  }
