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

// Traitement du formulaire de modification du pseudo, du mail et du mot de passe
if (isset($_POST['formmodification'])) {
    $newpseudo = !empty($_POST['newpseudo']) ? htmlspecialchars($_POST['newpseudo']) : $userinfo['pseudo'];
    $newmail = !empty($_POST['newmail']) ? htmlspecialchars($_POST['newmail']) : $userinfo['mail'];
    $mdp = $_POST['mdp'];

    // Vérification du mot de passe actuel
    if (password_verify($mdp, $userinfo['motdepasse'])) {
        // Mise à jour du pseudo et du mail dans la base de données
        $updateuser = $bdd->prepare("UPDATE membres SET pseudo = ?, mail = ? WHERE id = ?");
        $updateuser->execute([$newpseudo, $newmail, $_SESSION['id']]);

        // Mise à jour des informations dans la variable de session
        $_SESSION['pseudo'] = $newpseudo;
        $_SESSION['mail'] = $newmail;

        $success_msg = "Les modifications ont été effectuées avec succès !";
    } else {
        $erreur = "Mot de passe incorrect";
    }
}
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
        <?php echo $userinfo['pseudo']; ?><br>
        Mail :
        <?php echo $userinfo['mail']; ?>
        <br><br>
        <form method="POST"
              action="">
            <label for="newpseudo">Nouveau pseudo :</label>
            <input type="text"
                   id="newpseudo"
                   name="newpseudo"
                   placeholder="Nouveau pseudo"><br><br>
            <label for="newmail">Nouveau mail :</label>
            <input type="email"
                   id="newmail"
                   name="newmail"
                   placeholder="Nouveau mail"><br><br>
            <label for="mdp">Mot de passe :</label>
            <input type="password"
                   id="mdp"
                   name="mdp"
                   placeholder="Mot de passe"><br><br>
            <input type="submit"
                   name="formmodification"
                   value="Modifier">
        </form>
        <?php
        if (isset($erreur)) {
            echo '<font color="red">' . $erreur . "</font>";
        } elseif (isset($success_msg)) {
            echo '<font color="green">' . $success_msg . "</font>";
        }
        ?>
        <br>
        <a href="deconnexion.php">Se déconnecter</a><br>
        <a href="index.php">Retourner à l'accueil</a>
    </div>
</body>

</html>