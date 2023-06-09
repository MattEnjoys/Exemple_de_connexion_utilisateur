<?php
session_start();

$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root', '');

// Vérification si le formulaire de connexion a été soumis. 
// Cela permet de s'assurer que les données du formulaire ont été envoyées.
if (isset($_POST['formconnexion'])) {
    // Récupération et sécurisation des données saisies :
    // Le champ "mail" est récupéré et stocké dans la variable "$mailconnect" après avoir été sécurisé avec la fonction "htmlspecialchars()".
    $mailconnect = htmlspecialchars($_POST['mailconnect']);
    // Le champ "mot de passe" est récupéré et stocké dans la variable $mdpconnect tel quel, sans sécurité supplémentaire.
    $mdpconnect = $_POST['mdpconnect'];

    // Vérification si les champs du formulaire ne sont pas vides.
    // Cela permet de s'assurer que les champs obligatoires sont remplis.
    if (!empty($mailconnect) && !empty($mdpconnect)) {
        // Requête pour récupérer les informations de l'utilisateur correspondant à l'adresse e-mail saisie.
        // Une requête préparée est préparée avec la requête SQL.
        $requser = $bdd->prepare("SELECT * FROM membres WHERE mail = ?");
        // La valeur de l'e-mail est passée en tant que paramètre de la requête à l'aide de la méthode.
        $requser->execute([$mailconnect]);
        // La méthode rowCount() est utilisée pour compter le nombre de résultats retournés par la requête.
        $userexist = $requser->rowCount();
        // Vérification si l'utilisateur existe.
        // Si rowCount() renvoie 1, cela signifie qu'un utilisateur correspondant à l'adresse e-mail saisie a été trouvé dans la base de données.
        if ($userexist == 1) {
            // Les informations de l'utilisateur sont récupérées à l'aide de la méthode fetch() et stockées dans la variable $userinfo.
            $userinfo = $requser->fetch();
            // Vérification du mot de passe.
            // La fonction password_verify() est utilisée pour comparer le mot de passe saisi ($mdpconnect) avec le mot de passe haché stocké en base de données ($userinfo['motdepasse']).
            // Si les mots de passe correspondent, la connexion est considérée comme réussie.
            if (password_verify($mdpconnect, $userinfo['motdepasse'])) {
                // Les informations de l'utilisateur sont stockées dans des variables de session ($_SESSION['id'], $_SESSION['pseudo'], $_SESSION['mail']).
                $_SESSION['id'] = $userinfo['id'];
                $_SESSION['pseudo'] = $userinfo['pseudo'];
                $_SESSION['mail'] = $userinfo['mail'];
                // Redirection vers la page de profil.
                // Si la connexion est réussie, l'utilisateur est redirigé vers la page profil.php en passant l'identifiant de session dans l'URL (profil.php?id=...).
                // La fonction header("Location: ...") est utilisée pour effectuer la redirection.
                header("Location: index.php?id=" . $_SESSION['id']);
                exit();
            } else {
                // Affichage des erreurs.
                // Si une erreur survient à l'une des étapes précédentes, elle est stockée dans la variable $erreur.
                // Cette variable peut être utilisée pour afficher un message d'erreur approprié à l'utilisateur.
                $erreur = "Mot de passe incorrect !";
            }
        } else {
            $erreur = "L'utilisateur n'existe pas !";
        }
    } else {
        $erreur = "Tous les champs doivent être complétés !";
    }
}
?>

<html>

<head>
    <title>Connexion</title>
    <meta charset="utf-8">
</head>

<body>
    <div align="center">
        <h2>Connexion</h2>
        <br><br>
        <form method="POST"
              action="">
            <table>
                <tr>
                    <td align="right">
                        <label for="mailconnect">Mail :</label>
                    </td>
                    <td>
                        <input type="email"
                               placeholder="Votre mail"
                               id="mailconnect"
                               name="mailconnect" />
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <label for="mdpconnect">Mot de passe :</label>
                    </td>
                    <td>
                        <input type="password"
                               placeholder="Votre mot de passe"
                               id="mdpconnect"
                               name="mdpconnect" />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td align="center">
                        <br>
                        <input type="submit"
                               name="formconnexion"
                               value="Se connecter" />
                    </td>
                </tr>
            </table>
            <br>
        </form>
        <a href="inscription.php">Se créer un compte</a>
        <br>
        <a href="index.php">Retourner à l'accueil</a>

        <?php
        if (isset($erreur)) {
            echo '<font color="red">' . $erreur . "</font>";
        }
        ?>

    </div>
</body>

</html>