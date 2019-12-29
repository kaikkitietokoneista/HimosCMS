<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="/"><?php echo $sivunnimi; ?></a>
    </div>
    <ul class="nav navbar-nav">
      <?php
        $arraysivuista = scandir("sivut");
        foreach ($arraysivuista as $key => $value) {
          if ($value != ".") {
            if ($value != "..") {
              echo "<li><a href='index.php?s=" . $value . "'>" . $value . "</a></li>";
            }
          }
        }
      ?>
    </ul>
  </div>
</nav>
