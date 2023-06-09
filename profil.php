<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: connexion.php");
    exit();
}

$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root', '');

$requser = $bdd->prepare("SELECT * FROM membres WHERE id = ?");
$requser->execute([$_SESSION['id']]);
$userinfo = $requser->fetch();



?>


<html>

<head>
    <title>Profil</title>
    <meta charset="utf-8">
</head>

<body>
    <div align="center">
        <h2>Bienvenue
            <?php echo $userinfo['pseudo']; ?> sur votre profil !
        </h2>
        <br><br>
        Pseudo :
        <?php echo $userinfo['pseudo']; ?>
        <br>
        Mail :
        <?php echo $userinfo['mail']; ?>
        <br><br>
        <a href="deconnexion.php">Se déconnecter</a>
    </div>
</body>

</html>