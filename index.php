<?php
//Recevoir le shotcut

if(isset($_GET['q'])) {

    $shortcut = htmlspecialchars($_GET['q']);

    //Verifier si c'est un shortcut

    //connexion à la base
    include ('partials/connexion.php');

    $req = $bdd->prepare('SELECT COUNT(*) AS x FROM links WHERE shortcut = ?');

    $req->execute([$shortcut]);

    while($result = $req->fetch()) {

        if($result['x'] != 1) {
            header('location: ../projetRaccourcisseur/?error=true&message=Adresse url non connue');
            exit();
        }
    }

    //Redirection

    $req = $bdd->prepare('SELECT * FROM links WHERE shortcut = ?');

    $req->execute([$shortcut]);

    while($result = $req->fetch()) {

        header('location: '.$result['url']);
        exit();
    }

}


if (isset($_POST['url'])) {
    
    $url = $_POST['url'];

    //Vérification url

    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        header('location:../projetRaccourcisseur/?error=true&message=Adresse url non valide');
        exit();
    }

    //Shortcut

    $shortcut = crypt($url, rand());

    //Si l'url a déja été envoyée

    //connexion à la base
    include ('partials/connexion.php');

    $req = $bdd->prepare('SELECT COUNT(*) AS x FROM links WHERE url = ?');

    $req->execute([$url]);

    while($result = $req->fetch()){

        if($result['x'] != 0){
            header('location:../projetRaccourcisseur/?error=true&message=Adresse déjà raccourcie');
            exit();
        }
    }

    // Envoi

    $req = $bdd->prepare('INSERT INTO links(url, shortcut) VALUES (?, ?)');

    $req->execute([$url, $shortcut]);

    header('location: ../projetRaccourcisseur/?short='.$shortcut);
    exit();

}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Raccourcisseur URL</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="design/default.css">
    <link rel="shortcut icon" href="pictures/favico.png" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>

    <body>
        <section id="hello">
            <div class="container">
                <header>
                    <img id="logo" src="pictures/logo.png" alt="logo">
                </header>
                <h1>Une url longue ? Raccourcissez-là</h1>
                <h2>Largement meilleur et plus court que les autres.</h2>

                <form action="index.php" method="post">
                    <input type="url" name="url" placeholder="Collez un lien à raccourcir">
                    <input type="submit" value="Raccourcir">
                </form>

                <?php

                    if (isset($_GET['error']) && isset($_GET['message'])) {?>

                        <div class="container alert alert-danger text-center mt-5 py-3">
                            <b><?php echo htmlspecialchars($_GET['message'])?></b>
                        </div>

                    <?php } else if(isset($_GET['short'])) {?>

                        <div class="container alert alert-success text-center mt-5 py-3 shadow-lg p-3 mb-5 rounded">
                            <b>URL RACCOURCIE : http://localhost/projetRaccourcisseur/?q=<?php echo htmlspecialchars($_GET['short'])?></b>
                        </div>

                    <?php }
                    ?>
            </div>
        </section>
        <section id="brands">
            <div class="container">
                <h3>Ces marques nous font confiance</h3>
                <div class="row">
                    <div class="col-3">
                        <img src="pictures/1.png" alt="1">
                    </div>
                    <div class="col-3">
                        <img src="pictures/2.png" alt="2">
                    </div>
                    <div class="col-3">
                        <img src="pictures/3.png" alt="3">
                    </div>
                    <div class="col-3">
                        <img src="pictures/4.png" alt="4">
                    </div>
                </div>
            </div>
        </section>
        
        <footer>
            <img id="logo" src="pictures/logo2.png" alt="logo" id="logo2">

            <p>2018 &#169; Bitly</p>

            <a href="#">Contact</a> - <a href="#">A propos</a>
        </footer>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body>
</html>