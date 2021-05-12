<?php
        require_once 'vendor/autoload.php';

        $Parsedown = new Parsedown();

        $sivut = scandir('sivut');
?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <!-- https://coolors.co/000000-171717-1c1c1c-2e2e2e-454545-5c5c5c-->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/static/css/main.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital@1&display=swap" rel="stylesheet"> 
    <title></title>
</head>
<body>
    <nav class="sivumenu">
        <center><h2>Artikkelit</h2></center>
        <?php
            foreach ($sivut as $sivu) {
                if ($sivu[0] != '.') { //Ei piilotettuja tiedostoja
                    $sivun_nimi = str_replace(".md", "",$sivu);
                    ?>
                        <a href="/?sivu=<?php echo $sivun_nimi ?>">
                            <?php echo $sivun_nimi ?>
                        </a>
                    <?php
                }
            }
        ?>
    </nav>
    <div class="artikkelialue">
        <header>
            <a href="/"><h1>HimosCMS</h1></a>
            <p>Tietokonekeskittynyt blogi</p>
        </header>

        <article class="artikkeli">
            <?php
            if (isset($_GET["sivu"])) {
                $sivu = str_replace('/', '_', $_GET["sivu"]);

                $polku = 'sivut/'.$sivu.'.md';

                //Lisää 404 koodi myös headeriin
                if (file_exists($polku)) {
                    $sisältö = file_get_contents($polku);
                    
                    echo $Parsedown->text($sisältö);
                } else {
                    ?>
                    <h1>404</h1>
                    <p>
                        Sivua ei löytynyt.
                    </p>
                    <?php
                }
            } else {
                ?>
                <!-- TODO: jotain hienoa tähän -->
                <-- Valitse vasemmalta artikkeli
                <?php
            }
            ?>
        </article>
    </div>
</body>
</html>
