<!DOCTYPE html>
<html>

<head>
    <title>Accueil</title>
</head>

<body>
    <div align="center">
        <h1>Ceci est un site test pour comprendre le principe de connexion / inscription utilisateur.</h1>
        <p>Ici est injecté en PHP le nom de l'utilisateur.</p>
    </div>
    <div align="center">
        <?php
        session_start();
        if (isset($_SESSION['pseudo'])) {
            echo '<p>Bonjour, ' . $_SESSION['pseudo'] . '! Vous êtes connecté, vous pouvez donc acceder à votre profil en cliquant sur le lien ci-dessous.</p>';
            echo '<a href="profil.php">Mon Profil</a></br>';
            echo '<a href="deconnexion.php">Se déconnecter</a>';
        } else {
            echo '<p>Pour le voir, vous devez vous connecter en cliquant sur le lien ci-dessous :</p>';
            echo '<a href="connexion.php">Connexion</a>';
        }
        ?>
    </div>
</body>

</html>