// logout.php
<?php
/**
 * Script de déconnexion sécurisé.
 *
 * Ce script effectue les actions suivantes dans l'ordre :
 * 1. Démarre la session PHP existante.
 * 2. Vide le tableau de session ($_SESSION) pour effacer toutes les données.
 * 3. Invalide le cookie de session dans le navigateur du client en lui donnant une date d'expiration passée.
 * 4. Détruit complètement la session sur le serveur.
 * 5. Redirige l'utilisateur vers la page de connexion.
 */

// 1. Il est impératif de démarrer la session pour pouvoir la manipuler.
session_start();

// 2. On vide le tableau de la session.
// C'est une première étape pour "nettoyer" la session en cours.
$_SESSION = [];

// 3. On détruit le cookie de session côté client (navigateur).
// C'est l'étape la plus importante pour une déconnexion complète et sécurisée.
// On récupère les paramètres du cookie de session pour s'assurer de le supprimer correctement.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),      // Le nom du cookie de session (ex: 'PHPSESSID')
        '',                  // Une valeur vide
        time() - 42000,      // Une date d'expiration dans le passé, ce qui le supprime
        $params["path"],     // Le chemin du cookie
        $params["domain"],   // Le domaine du cookie
        $params["secure"],   // Le flag 'secure' (https)
        $params["httponly"]  // Le flag 'httponly'
    );
}

// 4. Finalement, on détruit la session côté serveur.
session_destroy();

// 5. Redirection vers la page de connexion.
// C'est la destination la plus logique après une déconnexion.
// Si on redirigeait vers 1.php, l'utilisateur serait probablement renvoyé vers login.php de toute façon.
header("Location: 1.php");

// On s'assure que le script s'arrête immédiatement après la redirection.
exit();
?>