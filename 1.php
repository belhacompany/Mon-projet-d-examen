
<?php
/* ---------- CONFIG DB ---------- */
$dbHost = 'localhost';
$dbName = 'ravelojaona';
$dbUser = 'root';
$dbPass = '';

$dsn = "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4";

/* ---------- AUTO-CREATE TABLE ---------- */
try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id         INT AUTO_INCREMENT PRIMARY KEY,
            full_name  VARCHAR(255) NOT NULL,
            email      VARCHAR(255) NOT NULL UNIQUE,
            password   VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
} catch (PDOException $e) {
    error_log("DB error: " . $e->getMessage());
    die("An error occurred. Please try again.");
}

/* ---------- HANDLE POST ---------- */
session_start();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // SIGN-UP
    if (isset($_POST['register'])) {
        $full = trim($_POST['name']);
        $email = trim($_POST['email']);
        $pass = trim($_POST['password']);
        
        $pass_confirm = isset($_POST['password_confirm']) ? trim($_POST['password_confirm']) : '';
        $special_char_regex = '/[\W_]/'; // \W est n'importe quel caractère non alphanumérique, _ est le soulignement

        if ($pass !== $pass_confirm) {
            $msg = 'Les mots de passe ne correspondent pas.';
        } elseif (strlen($pass) < 8 || !preg_match('/[A-Z]/', $pass) || !preg_match('/[a-z]/', $pass) || !preg_match('/[0-9]/', $pass) || !preg_match($special_char_regex, $pass)) {
            // MESSAGE TRADUIT ICI
            $msg = 'Le mot de passe doit contenir au moins 8 caractères, incluant une majuscule, une minuscule, un chiffre et un caractère spécial.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $msg = 'Format de l\'email invalide.';
        } else {
            try {
                $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password) VALUES (?,?,?)");
                $stmt->execute([$full, $email, $hashed_pass]);
                $userId = $pdo->lastInsertId();
                session_regenerate_id(true);
                $_SESSION['user_id'] = $userId;
                header('Location: Couverture final.php');
                exit;
            } catch (PDOException $e) {
                error_log("Registration error: " . $e->getMessage());
                if ($e->errorInfo[1] == 1062) {
                    $msg = 'Cet email est déjà utilisé. Veuillez en choisir un autre.';
                } else {
                    $msg = 'Erreur lors de l\'inscription. Veuillez réessayer.';
                }
            }
        }
    }

    // LOGIN
    if (isset($_POST['login'])) {
        $email = trim($_POST['email']);
        $pass = trim($_POST['password']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $msg = 'Format de l\'email invalide.';
        } else {
            try {
                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($pass, $user['password'])) {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user['id'];
                    header('Location: Couverture final.php');
                    exit;
                } else {
                    $msg = 'Identifiants invalides.';
                }
            } catch (PDOException $e) {
                error_log("Login error: " . $e->getMessage());
                $msg = 'Erreur lors de l\'authentification. Veuillez réessayer.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cryptid Collective | Authentification de l'enquêteur</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=Space+Mono&family=Work+Sans:wght@400;500&display=swap" rel="stylesheet">
  <style>
    :root {
      /* Primary Colors */
      --expedition-green: #1E3B2C;
      --midnight-charcoal: #2D3033;
      --misty-gray: #607D8B;
      /* Accent Colors */
      --bioluminescent-teal: #39F0D9;
      --evidence-amber: #FF9800;
      --scanner-green: #76FF03;
      /* Fonts */
      --font-heading: 'Rajdhani', sans-serif;
      --font-body: 'Work Sans', sans-serif;
      --font-mono: 'Space Mono', monospace;
      /* Effects */
      --glass-opacity: 0.15;
      --blur-amount: 12px;
      --glow-strength: 3px;
      /* Mouse Tracking */
      --mouse-x: 50%;
      --mouse-y: 50%;
      --ping-color: var(--bioluminescent-teal);
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: var(--font-body);
      color: #fff;
      line-height: 1.6;
      min-height: 100vh;
      position: relative;
      overflow-x: hidden;
      transition: background 0.5s ease, color 0.5s ease, all 0.8s ease;
      background: var(--background-primary, linear-gradient(135deg, var(--midnight-charcoal), var(--expedition-green)));
    }

    .background {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -2;
      background: var(--background-primary, linear-gradient(135deg, var(--midnight-charcoal), var(--expedition-green)));
      animation: backgroundPulse 15s ease-in-out infinite;
    }

    .noise {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: url('data:image/svg+xml,%3Csvg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg"%3E%3Cfilter id="noiseFilter"%3E%3CfeTurbulence type="fractalNoise" baseFrequency="0.65" numOctaves="3" stitchTiles="stitch"/%3E%3C/filter%3E%3Crect width="100%" height="100%" filter="url(%23noiseFilter)" opacity="0.15"/%3E%3C/svg%3E');
      opacity: 0.3;
      z-index: -1;
      mix-blend-mode: overlay;
      animation: noiseShift 0.5s steps(2) infinite;
      pointer-events: none;
    }

    .gradient-overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: 
        radial-gradient(circle at var(--mouse-x, 50%) var(--mouse-y, 50%), rgba(57, 240, 217, 0.15), transparent 25%),
        radial-gradient(circle at calc(100% - var(--mouse-x, 50%)) calc(100% - var(--mouse-y, 50%)), rgba(255, 152, 0, 0.1), transparent 25%);
      z-index: -1;
      pointer-events: none;
    }

    .topographic-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: url('data:image/svg+xml,%3Csvg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg"%3E%3Cdefs%3E%3Cpattern id="topo" patternUnits="userSpaceOnUse" width="200" height="200"%3E%3Cpath d="M0,100 Q50,50 100,100 Q150,150 200,100 M0,50 Q50,0 100,50 Q150,100 200,50 M0,150 Q50,100 100,150 Q150,200 200,150" fill="none" stroke="rgba(57, 240, 217, 0.07)" stroke-width="0.5"/%3E%3C/pattern%3E%3C/defs%3E%3Crect width="100%" height="100%" fill="url(%23topo)"/%3E%3C/svg%3E');
      z-index: -1;
      opacity: 0.4;
      pointer-events: none;
    }

    .container {
      width: 100%;
      max-width: 550px;
      margin: 0 auto;
      padding: 2rem;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      justify-content: center;
    }

    header {
      display: flex;
      justify-content: center;
      margin-bottom: 1.5rem;
    }

    .flavor-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-family: var(--font-mono);
      font-size: 0.7rem;
      color: var(--misty-gray);
      opacity: 0.6;
      margin-bottom: 1rem;
      padding: 0.5rem;
      background: rgba(45, 48, 51, 0.2);
      border-radius: 4px;
    }

    .header-batch,
    .header-coordinates,
    .header-phrase {
      color: var(--bioluminescent-teal);
    }

    .header-batch.warning-alert {
      color: var(--evidence-amber);
    }

    .header-batch.danger-alert {
      color: var(--bioluminescent-teal);
    }

    .header-phrase.danger-text {
      color: var(--bioluminescent-teal);
    }

    .header-coordinates.warning-flicker {
      animation: flicker 0.2s infinite;
    }

    .header-coordinates.danger-corrupt {
      color: var(--bioluminescent-teal);
    }

    .logo {
      height: 40px;
      filter: drop-shadow(0 0 5px rgba(57, 240, 217, 0.3));
    }

    .coordinates {
      position: fixed;
      bottom: 1rem;
      right: 1rem;
      font-family: var(--font-mono);
      font-size: 0.7rem;
      color: var(--misty-gray);
      opacity: 0.6;
      text-align: right;
    }

    .coordinates span {
      display: block;
      margin: 5px 0;
    }

    .time-label {
      color: var(--bioluminescent-teal);
    }

    .glass-panel {
      background: var(--container-bg, rgba(45, 48, 51, 0.1));
      backdrop-filter: blur(20px) contrast(120%);
      border: 1px solid var(--container-border, rgba(255, 255, 255, 0.05));
      box-shadow: var(--container-shadow, 0 4px 30px rgba(0, 0, 0, 0.2), 0 0 50px rgba(57, 240, 217, 0.1), inset 0 0 15px rgba(57, 240, 217, 0.05));
      border-radius: 10px;
      padding: 2.5rem;
      position: relative;
      overflow: hidden;
      width: 100%;
      transform-style: preserve-3d;
      perspective: 1000px;
      transition: all 0.8s ease;
    }

    .glass-panel.warning-pulse {
      animation: warningPulse 1s infinite;
    }

    .glass-panel.danger-pulse {
      animation: dangerPulse 0.5s infinite;
    }

    .glass-panel.lockout {
      pointer-events: none;
      opacity: 0.5;
    }

    .glass-panel::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 1px;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    }

    .glass-panel::after {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(
        135deg,
        transparent 30%,
        rgba(57, 240, 217, 0.03) 40%,
        rgba(57, 240, 217, 0.03) 60%,
        transparent 70%
      );
      z-index: 1;
      pointer-events: none;
    }

    .classified-stamp {
      position: absolute;
      top: 1.5rem;
      right: 1.5rem;
      font-family: var(--font-mono);
      color: rgba(255, 152, 0, 0.2);
      border: 1px solid rgba(255, 152, 0, 0.2);
      padding: 0.2rem 0.4rem;
      font-size: 0.6rem;
      transform: rotate(12deg);
      text-transform: uppercase;
      letter-spacing: 1px;
      pointer-events: none;
      z-index: 10;
      opacity: 0.15;
    }

    .auth-notification {
      position: absolute;
      top: 10px;
      left: 50%;
      transform: translateX(-50%);
      padding: 10px 20px;
      border-radius: 4px;
      font-family: var(--font-mono);
      font-weight: bold;
      text-align: center;
      z-index: 100;
      animation: slideDown 0.3s forwards;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
    }

    .auth-notification.error,
    .auth-notification.critical {
      background-color: rgba(244, 67, 54, 0.9);
      color: white;
    }

    .auth-notification.success {
      background: rgba(57, 240, 217, 0.15);
      border: 1px solid var(--bioluminescent-teal);
      color: var(--bioluminescent-teal);
      animation: successPulse 2s infinite;
    }

    .auth-notification.notice {
      background: rgba(45, 48, 51, 0.8);
      color: var(--bioluminescent-teal);
    }

    .card-switch {
      position: relative;
    }

    .switch {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 20px;
    }

    .toggle {
      opacity: 0;
      width: 0;
      height: 0;
    }

    .slider {
      width: 60px;
      height: 24px;
      border: 2px solid var(--bioluminescent-teal);
      border-radius: 5px;
      background: rgba(45, 48, 51, 0.8);
      position: relative;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .slider:before {
      content: "";
      position: absolute;
      width: 20px;
      height: 20px;
      background: var(--bioluminescent-teal);
      border-radius: 3px;
      top: 0;
      left: 0;
      transition: transform 0.3s;
      box-shadow: 0 0 10px var(--bioluminescent-teal);
    }

    .toggle:checked + .slider:before {
      transform: translateX(36px);
    }

    .toggle:checked ~ .card-side:before {
      color: var(--misty-gray);
    }

    .toggle:checked ~ .card-side:after {
      color: var(--bioluminescent-teal);
    }

    .card-side::before,
    .card-side::after {
      position: absolute;
      top: -30px;
      font-family: var(--font-mono);
      font-size: 16px;
      color: var(--bioluminescent-teal);
      cursor: pointer;
      transition: color 0.3s;
    }

    .card-side::before {
      content: 'CONNEXION';
      left: 10px;
    }

    .card-side::after {
      content: 'INSCRIPTION';
      right: 10px;
    }

    .flip-card__inner {
      width: 100%;
      height: 450px;
      position: relative;
      perspective: 1000px;
      transition: transform 0.8s;
      transform-style: preserve-3d;
    }

    .toggle:checked ~ .flip-card__inner {
      transform: rotateY(180deg);
    }

    .flip-card__front,
    .flip-card__back {
      position: absolute;
      width: 100%;
      height: 100%;
      padding: 20px;
      border-radius: 10px;
      background: rgba(45, 48, 51, 0.1);
      backdrop-filter: blur(5px);
      border: 1px solid rgba(255, 255, 255, 0.05);
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
      backface-visibility: hidden;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 20px;
      z-index: 2;
    }

    .flip-card__back {
      transform: rotateY(180deg);
    }

    .title {
      font-family: var(--font-heading);
      font-size: clamp(1.8rem, 5vw, 2.5rem);
      color: #fff;
      line-height: 1;
      font-weight: 600;
      letter-spacing: -0.02em;
      position: relative;
    }

    .title.glitch-text::before,
    .title.glitch-text::after {
      content: attr(data-text);
      position: absolute;
      left: 0;
      color: var(--bioluminescent-teal);
      opacity: 0.5;
    }

    .title.glitch-text::before {
      animation: glitch-top 1s linear infinite;
      clip-path: polygon(0 0, 100% 0, 100% 33%, 0 33%);
      transform: translate(-2px, -2px);
    }

    .title.glitch-text::after {
      animation: glitch-bottom 1.5s linear infinite;
      clip-path: polygon(0 67%, 100% 67%, 100% 100%, 0 100%);
      transform: translate(2px, 2px);
    }

    .classified-stripe {
      background-color: var(--evidence-amber);
      color: var(--midnight-charcoal);
      font-family: var(--font-mono);
      font-size: 0.7rem;
      font-weight: bold;
      padding: 0.25rem 0.75rem;
      display: inline-block;
      margin-bottom: 1.5rem;
      letter-spacing: 1px;
      transition: all 0.8s ease;
    }

    .form-group {
      position: relative;
      margin-bottom: 1.5rem;
      opacity: 0;
      transform: translateY(10px);
      animation: fadeInUp 0.5s forwards;
      width: 100%;
    }

    .form-group.scanned {
      animation: scanHighlight 2s forwards;
    }

    label {
      display: block;
      font-size: 0.9rem;
      margin-bottom: 0.5rem;
      color: var(--misty-gray);
      text-align: left;
    }

    .input-container {
      display: flex;
      align-items: center;
      position: relative;
      margin-bottom: 0.5rem;
      z-index: 5;
    }

    .input-prefix {
      color: var(--bioluminescent-teal);
      font-family: var(--font-mono);
      font-size: 1.2rem;
      margin-right: 0.5rem;
      opacity: 0.7;
      position: absolute;
      left: 0.7rem;
      top: 50%;
      transform: translateY(-50%);
      z-index: 5;
      user-select: none;
      pointer-events: none;
    }

    .input-prefix.secure {
      color: var(--evidence-amber);
    }

    .flip-card__input {
      width: 100%;
      padding: 0.75rem 1rem;
      padding-left: 2rem;
      border: 1px solid rgba(96, 125, 139, 0.3);
      border-radius: 4px;
      background: rgba(45, 48, 51, 0.3);
      color: #fff;
      font-family: var(--font-body);
      transition: border-color 0.3s ease, background-color 0.3s ease, all 0.8s ease;
      z-index: 2;
    }

    .flip-card__input.error {
      border-color: #f44336;
      box-shadow: 0 0 5px #f44336;
    }

    .flip-card__input:focus {
      outline: none;
      border-color: var(--bioluminescent-teal);
      background: rgba(45, 48, 51, 0.5);
    }

    .input-glow {
      position: absolute;
      inset: 0;
      pointer-events: none;
      transition: box-shadow 0.3s ease, opacity 0.3s ease;
      z-index: 1;
    }

    .flip-card__btn {
      font-family: var(--font-heading);
      border: none;
      border-radius: 4px;
      padding: 0.75rem 1.5rem;
      font-size: 1rem;
      cursor: pointer;
      position: relative;
      letter-spacing: 0.5px;
      transition: all 0.3s ease;
      width: 100%;
      margin-bottom: 1rem;
      background: rgba(73, 104, 100, 0.2);
      color: var(--bioluminescent-teal);
      border: 1px solid rgba(92, 96, 96, 0.3);
    }

    .btn-glow {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(45deg, var(--bioluminescent-teal), transparent);
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .flip-card__btn:hover .btn-glow {
      opacity: 0.2;
    }

    .flip-card__btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 0 15px rgba(57, 240, 217, 0.3);
    }

    .security-indicators {
      display: flex;
      gap: 6px;
      position: absolute;
      bottom: 12px;
      right: 12px;
      z-index: 10;
    }

    .indicator {
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.3);
      transition: all 0.5s cubic-bezier(0.2, 0, 0.4, 1);
    }

    .indicator.active {
      background: var(--bioluminescent-teal);
      box-shadow: 0 0 8px var(--bioluminescent-teal);
      animation: pulse 1s infinite;
    }

    .indicator.warning {
      background: var(--evidence-amber);
      box-shadow: 0 0 8px var(--evidence-amber);
    }

    .indicator.danger {
      background: #f44336;
      box-shadow: 0 0 8px #f44336;
    }

    .protocol-acceptance {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 1rem;
      font-family: var(--font-mono);
      font-size: 0.8rem;
      color: var(--misty-gray);
    }

    .protocol-acceptance.error-flash {
      animation: errorPulse 0.6s;
    }

    .custom-toggle-container {
      position: relative;
      width: 40px;
      height: 20px;
      cursor: pointer;
    }

    .custom-toggle {
      width: 100%;
      height: 100%;
      background: rgba(45, 48, 51, 0.8);
      border: 2px solid var(--bioluminescent-teal);
      border-radius: 5px;
      position: relative;
      transition: background 0.3s ease;
    }

    .custom-toggle::before {
      content: '';
      position: absolute;
      width: 16px;
      height: 16px;
      background: var(--bioluminescent-teal);
      border-radius: 3px;
      top: 0;
      left: 0;
      transition: transform 0.3s;
      box-shadow: 0 0 8px var(--bioluminescent-teal);
    }

    .custom-toggle.checked::before {
      transform: translateX(20px);
    }

    .particle-field {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: -1;
    }

    .particle {
      position: absolute;
      width: 4px;
      height: 4px;
      background: var(--particle-color, rgba(57, 240, 217, 0.6));
      border-radius: 50%;
      animation: float 20s infinite linear;
      box-shadow: 0 0 3px var(--particle-color, rgba(57, 240, 217, 0.6));
    }

    .scanner-line {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 2px;
      background: linear-gradient(90deg, transparent, var(--bioluminescent-teal), transparent);
      animation: scan 3s infinite;
      opacity: 0.5;
      z-index: 2;
    }

    .radar-ping {
      position: absolute;
      width: 20px;
      height: 20px;
      background: transparent;
      border: 2px solid var(--ping-color);
      border-radius: 50%;
      animation: ping 2s ease-out;
      pointer-events: none;
      transform: translate(-50%, -50%);
    }

    .glitch-line {
      position: absolute;
      height: 1px;
      background: var(--glitch-line-color, rgba(57, 240, 217, 0.5));
      opacity: 0.5;
      z-index: 10;
    }

    .shine-effect {
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: linear-gradient(
        45deg,
        transparent,
        rgba(255, 255, 255, 0.1) 45%,
        rgba(255, 255, 255, 0.2) 50%,
        rgba(255, 255, 255, 0.1) 55%,
        transparent
      );
      transform: rotate(45deg);
      transform-origin: center;
      opacity: 0;
      transition: opacity 0.5s ease;
      pointer-events: none;
    }

    .glass-panel:hover .shine-effect {
      opacity: 0.3;
    }

    .environment-status {
      display: flex;
      gap: 10px;
      font-family: var(--font-mono);
      font-size: 0.7rem;
      color: var(--misty-gray);
      margin-top: 10px;
    }

    .status-item {
      display: flex;
      gap: 5px;
    }

    .status-value.status-ok {
      color: var(--bioluminescent-teal);
    }

    .status-value.status-warning {
      color: var(--evidence-amber);
    }

    .mission-note {
      font-family: var(--font-mono);
      font-size: 0.7rem;
      color: var(--bioluminescent-teal);
      margin-top: 10px;
    }

    .mission-label {
      color: var(--evidence-amber);
    }

    .biometric-scanner {
      margin-top: 1rem;
      width: 100%;
      text-align: center;
    }

    .auth-divider {
      position: relative;
      margin: 1rem 0;
      text-align: center;
      color: var(--misty-gray);
      font-family: var(--font-mono);
      font-size: 0.8rem;
    }

    .auth-divider::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 0;
      width: 100%;
      height: 1px;
      background: rgba(96, 125, 139, 0.3);
    }

    .auth-divider span {
      background: rgba(45, 48, 51, 0.5);
      padding: 0 10px;
      position: relative;
    }

    .fingerprint-scanner {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 10px;
    }

    .scanner-pad {
      width: 80px;
      height: 80px;
      border: 2px solid var(--bioluminescent-teal);
      border-radius: 8px;
      position: relative;
      background: rgba(45, 48, 51, 0.3);
      box-shadow: 0 0 15px var(--bioluminescent-teal);
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
    }

    .scanner-pad.scanning {
      animation: scanPulse 2s infinite;
    }

    .scanner-pad.processing {
      animation: processPulse 1s infinite;
    }

    .scanner-pad.scan-error {
      border-color: #f44336;
      box-shadow: 0 0 15px #f44336;
    }

    .scan-lines {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: repeating-linear-gradient(
        transparent,
        transparent 2px,
        var(--bioluminescent-teal) 2px,
        var(--bioluminescent-teal) 4px
      );
      animation: scanLines 0.5s infinite;
    }

    .fingerprint-icon {
      width: 40px;
      height: 40px;
      background: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="%2339F0D9" stroke-width="1"%3E%3Cpath d="M12 2a10 10 0 0 0-7 3 8 8 0 0 0 0 14 10 10 0 0 0 14 0 8 8 0 0 0 0-14 10 10 0 0 0-7-3zm0 2c4.4 0 8 3.6 8 8s-3.6 8-8 8-8-3.6-8-8 3.6-8 8-8zm0 2c-3.3 0-6 2.7-6 6 0 2 .8 3.8 2 5a1 1 0 0 0 1.4-.2 1 1 0 0 0-.2-1.4c-.9-1-1.2-2.3-1.2-3.4 0-2.2 1.8-4 4-4s4 1.8 4 4c0 1.1-.3 2.4-1.2 3.4a1 1 0 0 0-.2 1.4 1 1 0 0 0 1.4.2c1.2-1.2 2-3 2-5 0-3.3-2.7-6-6-6z"/%3E%3C/svg%3E') no-repeat center;
      opacity: 0.7;
    }

    .scanner-label {
      font-family: var(--font-mono);
      font-size: 0.8rem;
      color: var(--bioluminescent-teal);
    }

    .scanner-label.scan-error {
      color: #f44336;
    }

    .thermal-hints {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: 5;
    }

    .thermal-hint {
      position: absolute;
      font-family: var(--font-mono);
      font-size: 0.7rem;
      color: rgba(122, 125, 124, 0.3);
      opacity: 0.5;
      transform: translate(-50%, -50%);
    }

    .aptitude-test-container {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      display: none;
      align-items: center;
      justify-content: center;
      background: rgba(30, 59, 44, 0.9);
      z-index: 1000;
    }

    .aptitude-test-container.active {
      display: flex;
    }

    .aptitude-panel {
      max-width: 600px;
      width: 90%;
    }

    .test-header {
      margin-bottom: 1rem;
    }

    .test-header h2 {
      font-family: var(--font-heading);
      font-size: 1.8rem;
      color: var(--bioluminescent-teal);
    }

    .test-progress {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-top: 0.5rem;
    }

    .progress-bar {
      width: 100px;
      height: 4px;
      background: rgba(45, 48, 51, 0.5);
      border-radius: 2px;
      overflow: hidden;
    }

    .progress-fill {
      height: 100%;
      background: var(--bioluminescent-teal);
      transition: width 0.3s ease;
    }

    .progress-label {
      font-family: var(--font-mono);
      font-size: 0.8rem;
      color: var(--bioluminescent-teal);
    }

    .test-section {
      display: none;
    }

    .test-section.active {
      display: block;
    }

    .test-instruction {
      font-family: var(--font-mono);
      font-size: 0.9rem;
      color: var(--misty-gray);
      margin-bottom: 1rem;
    }

    .pattern-sequence {
      display: flex;
      gap: 10px;
      margin-bottom: 1rem;
    }

    .pattern-item {
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(45, 48, 51, 0.5);
      border: 1px solid var(--bioluminescent-teal);
      border-radius: 4px;
      font-family: var(--font-mono);
      font-size: 1.2rem;
      color: var(--bioluminescent-teal);
    }

    .pattern-options {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }

    .pattern-option {
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(45, 48, 51, 0.5);
      border: 1px solid var(--bioluminescent-teal);
      border-radius: 4px;
      font-family: var(--font-mono);
      font-size: 1.2rem;
      color: var(--bioluminescent-teal);
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .pattern-option:hover {
      background: rgba(57, 240, 217, 0.2);
    }

    .pattern-option.selected {
      background: var(--bioluminescent-teal);
      color: var(--midnight-charcoal);
    }

    .pattern-option.correct {
      border-color: var(--scanner-green);
    }

    .test-continue {
      margin-top: 1rem;
    }

    .test-continue.pulse-once {
      animation: pulse 0.5s;
    }

    .test-continue.shake {
      animation: shake 0.5s;
    }

    .memory-map {
      position: relative;
      width: 100%;
      height: 200px;
      background: linear-gradient(135deg, var(--midnight-charcoal), var(--expedition-green));
      border: 1px solid var(--bioluminescent-teal);
      border-radius: 4px;
      margin-bottom: 20px;
    }

    .memory-grid {
      position: relative;
      width: 100%;
      height: 100%;
    }

    .memory-marker {
      position: absolute;
      width: 12px;
      height: 12px;
      background-color: var(--bioluminescent-teal);
      border-radius: 50%;
      transform: translate(-50%, -50%);
      box-shadow: 0 0 8px var(--bioluminescent-teal);
      transition: opacity 0.3s ease;
    }

    .map-overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(45, 48, 51, 0.8);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--bioluminescent-teal);
      font-size: 1.5rem;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.5s ease;
    }

    .map-overlay.visible {
      opacity: 1;
    }

    .memory-recall {
      width: 100%;
      transition: opacity 0.5s ease;
    }

    .memory-recall.hidden {
      opacity: 0;
      pointer-events: none;
    }

    .recall-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      grid-template-rows: repeat(4, 1fr);
      gap: 4px;
      width: 100%;
      height: 200px;
    }

    .recall-cell {
      background-color: rgba(57, 240, 217, 0.1);
      border: 1px solid rgba(57, 240, 217, 0.3);
      border-radius: 2px;
      cursor: pointer;
      transition: background-color 0.2s ease;
    }

    .recall-cell:hover {
      background-color: rgba(57, 240, 217, 0.2);
    }

    .recall-cell.selected {
      background-color: rgba(57, 240, 217, 0.4);
      box-shadow: 0 0 5px var(--bioluminescent-teal);
    }

    .reaction-arena {
      position: relative;
      width: 100%;
      height: 200px;
      background: linear-gradient(135deg, var(--midnight-charcoal), var(--expedition-green));
      border: 1px solid var(--bioluminescent-teal);
      border-radius: 4px;
      margin-bottom: 20px;
    }

    .capture-counter {
      position: absolute;
      top: 10px;
      right: 10px;
      font-family: var(--font-mono);
      font-size: 0.8rem;
      color: var(--bioluminescent-teal);
    }

    .cryptid-target {
      position: absolute;
      display: flex;
      justify-content: center;
      align-items: center;
      opacity: 0;
      transform: scale(0.5);
      transition: opacity 0.3s, transform 0.3s;
      cursor: pointer;
      filter: drop-shadow(0 0 5px rgba(0, 0, 0, 0.8));
    }

    .cryptid-target.visible {
      opacity: 1;
      transform: scale(1);
    }

    .cryptid-target.captured {
      transform: scale(1.5);
      opacity: 0;
      transition: transform 0.5s, opacity 0.5s;
    }

    .target-logo {
      width: 50px;
      height: 50px;
      object-fit: contain;
      filter: brightness(1.5) drop-shadow(0 0 3px #000);
    }

    .results-panel {
      text-align: center;
    }

    .results-panel h3 {
      font-family: var(--font-heading);
      font-size: 1.5rem;
      color: var(--bioluminescent-teal);
    }

    .lockout-message {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: rgba(0, 0, 0, 0.85);
      color: #ff3333;
      padding: 20px;
      border: 1px solid #990000;
      font-family: var(--font-mono);
      text-align: center;
      z-index: 1000;
    }

    .alert-prefix {
      color: var(--bioluminescent-teal);
    }

    .small-text {
      font-size: 0.7rem;
      color: var(--misty-gray);
      display: block;
      margin-top: 10px;
    }

    .breach-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: repeating-linear-gradient(
        45deg,
        rgba(244, 67, 54, 0.2),
        rgba(244, 67, 54, 0.2) 10px,
        transparent 10px,
        transparent 20px
      );
      z-index: 999;
      pointer-events: none;
    }

    .danger-cryptid {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      opacity: 0;
      transition: opacity 1s;
      z-index: 1000;
    }

    .danger-cryptid svg {
      width: 100px;
      height: 100px;
    }

    .danger-cryptid.fade-out {
      opacity: 1;
      transition: opacity 1s;
    }

    .temp-feedback {
      position: fixed;
      top: 20px;
      left: 50%;
      transform: translateX(-50%);
      background: rgba(45, 48, 51, 0.8);
      color: var(--bioluminescent-teal);
      padding: 10px 20px;
      border-radius: 4px;
      font-family: var(--font-mono);
      z-index: 100;
    }

    footer {
      margin-top: 0;
      padding: 1rem 0;
      text-align: center;
    }

    .disclaimer {
      font-family: var(--font-mono);
      font-size: 0.7rem;
      color: var(--misty-gray);
      opacity: 0.6;
      letter-spacing: 1px;
    }

    @keyframes backgroundPulse {
      0%, 100% { filter: hue-rotate(0deg); }
      50% { filter: hue-rotate(15deg); }
    }

    @keyframes noiseShift {
      0%, 100% { transform: translate(0, 0); }
      25% { transform: translate(4px, -4px); }
      50% { transform: translate(-4px, 4px); }
      75% { transform: translate(-4px, -4px); }
    }

    @keyframes fadeInUp {
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes pulse {
      0% { opacity: 1; }
      50% { opacity: 0.5; }
      100% { opacity: 1; }
    }

    @keyframes slideDown {
      from { top: -50px; opacity: 0; }
      to { top: 10px; opacity: 1; }
    }

    @keyframes successPulse {
      0%, 100% { opacity: 0.8; }
      50% { opacity: 1; }
    }

    @keyframes scan {
      0% { transform: translateY(-100%); }
      100% { transform: translateY(100%); }
    }

    @keyframes ping {
      0% { transform: translate(-50%, -50%) scale(0); opacity: 1; }
      100% { transform: translate(-50%, -50%) scale(2); opacity: 0; }
    }

    @keyframes scanHighlight {
      0% { box-shadow: 0 0 0 transparent; }
      50% { box-shadow: 0 0 10px var(--bioluminescent-teal); }
      100% { box-shadow: 0 0 0 transparent; }
    }

    @keyframes scanPulse {
      0%, 100% { opacity: 0.7; }
      50% { opacity: 1; }
    }

    @keyframes processPulse {
      0%, 100% { opacity: 0.7; }
      50% { opacity: 1; }
    }

    @keyframes scanLines {
      0% { background-position: 0 0; }
      100% { background-position: 0 8px; }
    }

    @keyframes errorPulse {
      0%, 100% { box-shadow: none; }
      50% { box-shadow: 0 0 8px #f44336; }
    }

    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      25% { transform: translateX(-5px); }
      75% { transform: translateX(5px); }
    }

    @keyframes glitch-top {
      0% { transform: translate(-2px, -2px); }
      25% { transform: translate(-2px, 2px); }
      50% { transform: translate(2px, -2px); }
      75% { transform: translate(2px, 2px); }
      100% { transform: translate(-2px, -2px); }
    }

    @keyframes glitch-bottom {
      0% { transform: translate(2px, 2px); }
      25% { transform: translate(-2px, 2px); }
      50% { transform: translate(2px, -2px); }
      75% { transform: translate(-2px, -2px); }
      100% { transform: translate(2px, 2px); }
    }

    @keyframes flicker {
      0% { opacity: 1; }
      50% { opacity: 0.5; }
      100% { opacity: 1; }
    }

    @keyframes warningPulse {
      0%, 100% { box-shadow: 0 0 15px var(--evidence-amber); }
      50% { box-shadow: 0 0 25px var(--evidence-amber); }
    }

    @keyframes dangerPulse {
      0%, 100% { box-shadow: 0 0 15px #f44336; }
      50% { box-shadow: 0 0 25px #f44336; }
    }

    @keyframes red-pulse {
      0%, 100% { filter: brightness(1); }
      50% { filter: brightness(1.2); }
    }

    @media (max-width: 768px) {
      .container {
        padding: 1rem;
      }
      .glass-panel {
        padding: 1.5rem;
      }
      .flip-card__inner {
        height: 500px;
      }
      .card-side::before,
      .card-side::after {
        font-size: 14px;
        top: -20px;
      }
      .auth-notification {
        width: 90%;
        font-size: 0.7rem;
        padding: 0.5rem;
      }
      .biometric-scanner {
        display: block;
      }
    }

    @media (min-width: 769px) {
      .biometric-scanner.mobile-only {
        display: none;
      }
    }
  </style>
</head>
<body>
  <div class="background">
    <div class="noise"></div>
    <div class="gradient-overlay"></div>
  </div>
  <div class="topographic-overlay"></div>
  <div class="container">
    <header>
      <img src="./logo info transparent.png" alt="Cryptid Collective" class="logo">
    </header>
    <div class="coordinates">
      <span id="coordinates">PROJET EXAMEN 2025</span>
      <span><span class="time-label">BY BELHADESIGN </span><span id="timestamp"><?php echo date('Y-m-d H:i:s'); ?> UTC</span></span>
    </div>
    <main class="glass-panel">
      <div class="classified-stamp">Mon Authentification</div>
      <?php if (!empty($msg)): ?>
        <div class="auth-notification <?php echo strpos($msg, 'Erreur') !== false || strpos($msg, 'invalide') !== false || strpos($msg, 'doit') !== false ? 'error' : 'success'; ?>">
          <?php echo htmlspecialchars($msg); ?>
        </div>
      <?php endif; ?>
      <div class="card-switch">
        <label class="switch">
          <input type="checkbox" class="toggle" id="form-toggle">
          <span class="slider"></span>
          <span class="card-side"></span>
          <div class="flip-card__inner">
            <!-- LOGIN -->
            <div class="flip-card__front" id="sign-in-container">
              <div class="title" data-text="Field Researcher Authentication">Authentification</div>
              <div class="classified-stripe">Veuillez vous connecter</div>
              <form class="flip-card__form" id="sign-in-form" method="POST">
                <div class="form-group">
                  <label for="login_email">Email :</label>
                  <div class="input-container">
                    <span class="input-prefix">#</span>
                    <input class="flip-card__input" id="login_email" name="email" placeholder="format: CC-XXXXX" type="email" required>
                    <div class="input-glow"></div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="login_password">Mot de passe :</label>
                  <div class="input-container">
                    <span class="input-prefix secure">
                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24" transform="" id="injected-svg">
<path d="M8 21c2.76 0 5-2.24 5-5 0-1.02-.31-1.96-.83-2.75l3.33-3.33 1.79 1.79 1.41-1.41-1.79-1.79L18 7.42l2.29 2.29L21.7 8.3l-2.29-2.29 1.29-1.29-1.41-1.41-8.54 8.54c-.79-.52-1.74-.83-2.75-.83-2.76 0-5 2.24-5 5s2.24 5 5 5Zm0-8c1.65 0 3 1.35 3 3s-1.35 3-3 3-3-1.35-3-3 1.35-3 3-3"/>
</svg>
                    </span>
                    <input class="flip-card__input" id="login_password" name="password" placeholder="********" type="password" required>
                    <div class="input-glow"></div>
                  </div>
                </div>
                <div class="biometric-scanner mobile-only">
                  <div class="auth-divider"><span>OU</span></div>
                  <div class="fingerprint-scanner">
                    <div class="scanner-pad">
                      <div class="scan-lines"></div>
                      <div class="fingerprint-icon"></div>
                      <div class="scan-result"></div>
                    </div>
                    <div class="scanner-label"></div>
                  </div>
                </div>
                <button class="flip-card__btn" name="login">
                  CONNEXION
                  <span class="btn-glow"></span>
                  <div class="security-indicators">
                    <div class="indicator active"></div>
                    <div class="indicator active"></div>
                    <div class="indicator active"></div>
                  </div>
                </button>
                <div class="environment-status">
                  <div class="status-item"><span class="status-label">STATUT :</span> <span class="status-value status-ok">EN LIGNE</span></div>
                  <div class="status-item"><span class="status-label">COMPTE :</span> <span class="status-value status-ok">ACTIF</span></div>
                  <div class="status-item"><span class="status-label">PERMISSION :</span> <span class="status-value status-ok">SÉCURISÉ</span></div>
                </div>
                <div class="mission-note">
                  <span class="mission-label">MISSION ACTIVE :</span> RECON · MADAGASCAR
                </div>
              </form>
            </div>
            <!-- SIGN-UP -->
            <div class="flip-card__back" id="register-container">
              <div class="title" data-text="Field Researcher Onboarding">S'INSCRIRE</div>
              <div class="classified-stripe">NOUVEAU COMPTE</div>
              <form class="flip-card__form" id="register-form" method="POST">
                <div class="form-group">
                  <label for="signup_name">NOM COMPLET</label>
                  <div class="input-container">
                    <span class="input-prefix">&gt;</span>
                    <input class="flip-card__input" id="signup_name" name="name" placeholder="Nom, Prénom" type="text" required>
                    <div class="input-glow"></div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="signup_email">Email</label>
                  <div class="input-container">
                    <span class="input-prefix">#</span>
                    <input class="flip-card__input" id="signup_email" name="email" placeholder="format: CC-XXXXX" type="email" required>
                    <div class="input-glow"></div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="signup_password">Mot de passe</label>
                  <div class="input-container">
                    <span class="input-prefix secure">#</span>
                    <input class="flip-card__input" id="signup_password" name="password" placeholder="min 8 cars, maj, nbr..." type="password" required>
                    <div class="input-glow"></div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="signup_password_confirm">Confirmer le mot de passe</label>
                  <div class="input-container">
                    <span class="input-prefix secure">#</span>
                    <input class="flip-card__input" id="signup_password_confirm" name="password_confirm" placeholder="Confirmez votre mot de passe" type="password" required>
                    <div class="input-glow"></div>
                  </div>
                </div>
                <button class="flip-card__btn btn-init" name="register">
                       ENREGISTRER
                <span class="btn-glow"></span>
                  <div class="security-indicators">
                    <div class="indicator active"></div>
                    <div class="indicator active"></div>
                    <div class="indicator active"></div>
                  </div>
                </button>
              </form>
            </div>
          </div>
        </label>
      </div>
    </main>
    <div class="aptitude-test-container">
      <div class="glass-panel aptitude-panel">
        <div class="test-header">
          <h2>SÉCURITÉ ASSURÉE</h2>
          <div class="test-progress">
            <div class="progress-bar">
              <div class="progress-fill"></div>
            </div>
            <div class="progress-label">TEST 1/3</div>
          </div>
        </div>
        <div class="test-section active" data-test="1">
          <h3>Test de Sécurité à 100%</h3>
          <div class="test-instruction">
          Identification à chaque session
        </div>
          <div class="pattern-sequence">
            <div class="pattern-item">△</div>
            <div class="pattern-item">□</div>
            <div class="pattern-item">○</div>
            <div class="pattern-item">?</div>
          </div>
          <div class="pattern-options">
            <div class="pattern-option" data-correct="true">△</div>
            <div class="pattern-option">□</div>
            <div class="pattern-option">○</div>
            <div class="pattern-option">⬡</div>
          </div>
        </div>
        <div class="test-section" data-test="2">
          <h3>Test de Mémoire</h3>
          <div class="test-instruction">
            Mémorisez l'emplacement des marqueurs. Vous avez <span class="countdown">5</span> secondes.
          </div>
          <div class="memory-map">
            <div class="memory-grid">
              <div class="memory-marker" style="top: 35%; left: 20%"></div>
              <div class="memory-marker" style="top: 15%; left: 60%"></div>
              <div class="memory-marker" style="top: 75%; left: 80%"></div>
            </div>
            <div class="map-overlay"></div>
          </div>
          <div class="memory-recall hidden">
            <div class="recall-grid">
              <div class="recall-cell" data-location="A1"></div>
              <div class="recall-cell" data-location="A2"></div>
              <div class="recall-cell" data-location="A3"></div>
              <div class="recall-cell" data-location="A4"></div>
              <div class="recall-cell" data-location="B1"></div>
              <div class="recall-cell" data-location="B2"></div>
              <div class="recall-cell" data-location="B3"></div>
              <div class="recall-cell" data-location="B4"></div>
              <div class="recall-cell" data-location="C1"></div>
              <div class="recall-cell" data-location="C2"></div>
              <div class="recall-cell" data-location="C3"></div>
              <div class="recall-cell" data-location="C4"></div>
              <div class="recall-cell" data-location="D1"></div>
              <div class="recall-cell" data-location="D2"></div>
              <div class="recall-cell" data-location="D3"></div>
              <div class="recall-cell" data-location="D4"></div>
            </div>
          </div>
        </div>
        <div class="test-section" data-test="3">
          <h3>Test de Réaction</h3>
          <div class="test-instruction">
            Capturez les cibles dès leur apparition.
          </div>
          <div class="reaction-arena">
            <div class="capture-counter"><span>0</span></div>
          </div>
        </div>
        <div class="test-section" data-test="results">
          <div class="results-panel">
            <h3>Évaluation Terminée</h3>
            <p>Merci d'avoir complété l'évaluation d'aptitude.</p>
          </div>
        </div>
        <div class="test-navigation">
          <button type="button" class="flip-card__btn test-continue">
            <span class="btn-text">COMMENCER L'ÉVALUATION</span>
            <span class="btn-glow"></span>
          </button>
        </div>
      </div>
    </div>
    <footer>
      <p class="disclaimer">SÉCURITÉ · CONFIDENTIALITÉ · ASSURANCE</p>
    </footer>
    <div class="thermal-hints">
      <div class="thermal-hint" style="left: 15%; top: 20%;">ID FORMAT: CC-XXXXX</div>
      <div class="thermal-hint" style="left: 85%; top: 40%;">SPECIMEN CODE: CRYPTID</div>
      <div class="thermal-hint" style="left: 25%; top: 75%;">NUMERICAL KEY: 31415</div>
      <div class="thermal-hint" style="left: 75%; top: 85%;">DESIGNATION: X</div>
    </div>
  </div>
  <script>
    // Le code JavaScript reste identique et n'a pas besoin de modification pour ce changement.
    // Vous pouvez garder celui que vous aviez déjà.
    // DOM elements
    const signInContainer = document.getElementById('sign-in-container');
    const registerContainer = document.getElementById('register-container');
    const showRegisterLink = document.querySelector('.card-side::after');
    const showLoginLink = document.querySelector('.card-side::before');
    const toggleCheckbox = document.getElementById('form-toggle');
    const timestampElement = document.getElementById('timestamp');
    const aptitudeTestContainer = document.querySelector('.aptitude-test-container');
    const testSections = document.querySelectorAll('.test-section');
    const progressFill = document.querySelector('.progress-fill');
    const progressLabel = document.querySelector('.progress-label');
    const testContinueButton = document.querySelector('.test-continue');
    const scannerPad = document.querySelector('.scanner-pad');
    const scannerLabel = document.querySelector('.scanner-label');

    // Security state handling
    const loginAttemptsHandler = {
      attempts: 0,
      maxAttempts: 3,
      state: 'normal',
      attempt() {
        this.attempts++;
        if (this.attempts === this.maxAttempts - 1) {
          this.setState('warning');
        } else if (this.attempts >= this.maxAttempts) {
          this.setState('danger');
        }
      },
      reset() {
        this.attempts = 0;
        this.setState('normal');
      },
      setState(state) {
        this.state = state;
        const glassPanel = document.querySelector('.glass-panel');
        const headerBatch = document.querySelector('.header-batch');
        const headerCoordinates = document.querySelector('.header-coordinates');
        const indicators = document.querySelectorAll('.indicator');

        // Null checks
        if (glassPanel) glassPanel.classList.remove('warning-pulse', 'danger-pulse', 'lockout');
        if (headerBatch) headerBatch.classList.remove('warning-alert', 'danger-alert');
        if (headerCoordinates) headerCoordinates.classList.remove('warning-flicker', 'danger-corrupt');
        if (indicators) indicators.forEach(ind => ind.classList.remove('warning', 'danger'));

        if (state === 'warning') {
          if (glassPanel) glassPanel.classList.add('warning-pulse');
          if (headerBatch) headerBatch.classList.add('warning-alert');
          if (headerCoordinates) headerCoordinates.classList.add('warning-flicker');
          if (indicators) indicators.forEach(ind => ind.classList.add('warning'));
          showNotification('Warning: Unauthorized access attempt detected.', 'error');
        } else if (state === 'danger') {
          if (glassPanel) glassPanel.classList.add('danger-pulse', 'lockout');
          if (headerBatch) headerBatch.classList.add('danger-alert');
          if (headerCoordinates) headerCoordinates.classList.add('danger-corrupt');
          if (indicators) indicators.forEach(ind => ind.classList.add('danger'));
          showNotification('CRITICAL: System lockdown initiated.', 'critical');
          showDangerOverlay();
        }
      }
    };

    // Mouse tracking
    document.addEventListener('mousemove', (e) => {
      const x = (e.clientX / window.innerWidth) * 100;
      const y = (e.clientY / window.innerHeight) * 100;
      document.documentElement.style.setProperty('--mouse-x', `${x}%`);
      document.documentElement.style.setProperty('--mouse-y', `${y}%`);
      addRadarPing(e.clientX, e.clientY);
    });

    // Toggle between sign-in and registration forms
    showRegisterLink.addEventListener('click', (e) => {
      e.preventDefault();
      if (loginAttemptsHandler.state !== 'danger') {
        toggleCheckbox.checked = true;
        toggleCheckbox.dispatchEvent(new Event('change'));
      }
    });

    showLoginLink.addEventListener('click', (e) => {
      e.preventDefault();
      if (loginAttemptsHandler.state !== 'danger') {
        toggleCheckbox.checked = false;
        toggleCheckbox.dispatchEvent(new Event('change'));
      }
    });

    // Form submissions
    const loginForm = document.getElementById('sign-in-form');
    const registerForm = document.getElementById('register-form');

    function validateRegistrationForm(form) {
      const password = form.querySelector('#signup_password').value;
      const email = form.querySelector('#signup_email').value;
      if (password.length < 8 || !/[A-Z]/.test(password) || !/\d/.test(password)) {
        flashInputError(form.querySelector('#signup_password'));
        showNotification('Clearance code must be at least 8 characters, with uppercase letters and numbers.', 'error');
        return false;
      }
      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        flashInputError(form.querySelector('#signup_email'));
        showNotification('Invalid field contact format.', 'error');
        return false;
      }
      return true;
    }

    function flashInputError(input) {
      if (input) {
        input.classList.add('error');
        setTimeout(() => input.classList.remove('error'), 1000);
      }
    }

    function resetFormErrors() {
      document.querySelectorAll('.flip-card__input.error').forEach(input => input.classList.remove('error'));
    }

    loginForm.addEventListener('submit', function(e) {
      e.preventDefault();
      if (loginAttemptsHandler.state === 'danger') {
        showNotification('System locked. Access denied.', 'critical');
        return;
      }

      const emailInput = document.getElementById('login_email');
      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value)) {
        flashInputError(emailInput);
        showNotification('Invalid field contact format.', 'error');
        loginAttemptsHandler.attempt();
        return;
      }

      const formData = new FormData(this);
      fetch('', {
        method: 'POST',
        body: formData
      }).then(response => {
        if (!response.ok) throw new Error('Network error');
        return response.text();
      }).then(data => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(data, 'text/html');
        const msg = doc.querySelector('.auth-notification')?.textContent || 'Error processing request.';
        showNotification(msg, msg.includes('Invalid') || msg.includes('Error') ? 'error' : 'success');
        if (msg.includes('Invalid') || msg.includes('Error')) {
          loginAttemptsHandler.attempt();
          flashInputError(document.getElementById('login_email'));
          flashInputError(document.getElementById('login_password'));
        } else {
          loginAttemptsHandler.reset();
          setTimeout(() => window.location.href = 'Couverture final.php', 2000);
        }
      }).catch(error => {
        showNotification('Network error. Please try again.', 'error');
        loginAttemptsHandler.attempt();
      });
    });

    registerForm.addEventListener('submit', function(e) {
      e.preventDefault();
      if (loginAttemptsHandler.state === 'danger') {
        showNotification('System locked. Access denied.', 'critical');
        return;
      }

      if (!validateRegistrationForm(this)) {
        return;
      }

      const formData = new FormData(this);
      fetch('', {
        method: 'POST',
        body: formData
      }).then(response => {
        if (!response.ok) throw new Error('Network error');
        if (response.redirected) {
          window.location.href = response.url;
          return;
        }
        return response.text();
      }).then(data => {
        if (!data) return;
        const parser = new DOMParser();
        const doc = parser.parseFromString(data, 'text/html');
        const msg = doc.querySelector('.auth-notification')?.textContent || 'Error processing request.';
        showNotification(msg, msg.includes('Error') || msg.includes('Invalid') || msg.includes('must') ? 'error' : 'success');
      }).catch(error => {
        showNotification('Network error. Please try again.', 'error');
      });
    });

    // Biometric scanner
    let isScanning = false;
    scannerPad.addEventListener('click', () => {
      if (loginAttemptsHandler.state === 'danger' || isScanning) return;
      isScanning = true;
      scannerPad.classList.add('scanning');
      scannerLabel.textContent = 'SCANNING...';
      setTimeout(() => {
        scannerPad.classList.remove('scanning');
        scannerPad.classList.add('processing');
        scannerLabel.textContent = 'PROCESSING...';
        setTimeout(() => {
          scannerPad.classList.remove('processing');
          const success = Math.random() > 0.3; // Mock 70% success rate
          if (success) {
            scannerLabel.textContent = 'ACCESS GRANTED';
            loginAttemptsHandler.reset();
            setTimeout(() => window.location.href = 'Couverture final.php', 1000);
          } else {
            scannerPad.classList.add('scan-error');
            scannerLabel.classList.add('scan-error');
            scannerLabel.textContent = 'SCAN FAILED';
            loginAttemptsHandler.attempt();
            setTimeout(() => {
              scannerPad.classList.remove('scan-error');
              scannerLabel.classList.remove('scan-error');
              scannerLabel.textContent = 'PLACE FINGER ON SENSOR';
              isScanning = false;
            }, 2000);
          }
        }, 2000);
      }, 2000);
    });

    // Visual effects
    function addRadarPing(x, y) {
      const existingPings = document.querySelectorAll('.radar-ping');
      if (existingPings.length > 10) return; // Prevent accumulation
      const ping = document.createElement('div');
      ping.classList.add('radar-ping');
      ping.style.left = `${x}px`;
      ping.style.top = `${y}px`;
      document.body.appendChild(ping);
      setTimeout(() => ping.remove(), 2000);
    }

    function addGlitchLine() {
      const existingLines = document.querySelectorAll('.glitch-line');
      if (existingLines.length > 5) return; // Prevent accumulation
      const line = document.createElement('div');
      line.classList.add('glitch-line');
      line.style.width = `${Math.random() * 200 + 50}px`;
      line.style.left = `${Math.random() * (window.innerWidth - 200)}px`;
      line.style.top = `${Math.random() * window.innerHeight}px`;
      document.body.appendChild(line);
      setTimeout(() => line.remove(), 500);
    }

    function addTopographicOverlay() {
      const existingOverlay = document.querySelector('.topographic-overlay');
      if (!existingOverlay) {
        const overlay = document.createElement('div');
        overlay.classList.add('topographic-overlay');
        document.body.appendChild(overlay);
        setTimeout(() => overlay.remove(), 5000);
      }
    }

    function addClassifiedStamp() {
      const existingStamp = document.querySelector('.glass-panel .classified-stamp');
      if (!existingStamp) {
        const stamp = document.createElement('div');
        stamp.classList.add('classified-stamp');
        stamp.textContent = 'Level 3 Clearance';
        const glassPanel = document.querySelector('.glass-panel');
        if (glassPanel) glassPanel.appendChild(stamp);
        setTimeout(() => stamp.remove(), 5000);
      }
    }

    function showNotification(message, type) {
      const existingNotifications = document.querySelectorAll('.auth-notification');
      existingNotifications.forEach(note => note.remove());
      const notification = document.createElement('div');
      notification.classList.add('auth-notification', type);
      notification.textContent = message;
      document.body.appendChild(notification);
      setTimeout(() => notification.remove(), 3000);
    }

    function showDangerOverlay() {
      const existingOverlay = document.querySelector('.breach-overlay');
      if (existingOverlay) return; // Prevent accumulation
      const overlay = document.createElement('div');
      overlay.classList.add('breach-overlay');
      const cryptid = document.createElement('div');
      cryptid.classList.add('danger-cryptid', 'fade-out');
      cryptid.innerHTML = '<svg viewBox="0 0 100 100"><path d="M50 10C30 10 15 25 15 45s15 35 35 35 35-15 35-35S70 10 50 10z" fill="rgba(244,67,54,0.5)"/></svg>';
      document.body.appendChild(overlay);
      document.body.appendChild(cryptid);
      setTimeout(() => {
        overlay.remove();
        cryptid.remove();
      }, 3000);
    }

    // Update timestamp
    function updateTimestamp() {
      const now = new Date();
      timestampElement.textContent = now.toISOString();
    }
    setInterval(updateTimestamp, 1000);

    // Aptitude test logic
    let currentTest = 0;
    let testEventListeners = [];

    function clearTestListeners() {
      testEventListeners.forEach(({ element, type, handler }) => {
        element.removeEventListener(type, handler);
      });
      testEventListeners = [];
    }

    function updateProgress() {
      const progress = (currentTest / (testSections.length - 1)) * 100;
      progressFill.style.width = `${progress}%`;
      progressLabel.textContent = `TEST ${currentTest + 1}/${testSections.length - 1}`;
    }

    function launchAptitudeTests() {
      if (aptitudeTestContainer) {
        aptitudeTestContainer.classList.add('active');
        testContinueButton.querySelector('.btn-text').textContent = 'BEGIN ASSESSMENT';
        testContinueButton.disabled = false;
        currentTest = 0;
        updateProgress();
        testSections.forEach(section => section.classList.remove('active'));
        testSections[0].classList.add('active');
        initPatternTest();
      }
    }

    testContinueButton.addEventListener('click', () => {
      if (currentTest < testSections.length - 1) {
        clearTestListeners();
        currentTest++;
        testSections.forEach(section => section.classList.remove('active'));
        testSections[currentTest].classList.add('active');
        updateProgress();
        testContinueButton.disabled = true;
        testContinueButton.querySelector('.btn-text').textContent = 'CONTINUE';
        if (currentTest === 1) initMemoryTest();
        else if (currentTest === 2) initReactionTest();
        else if (currentTest === 3) showResults();
      }
    });

    function initPatternTest() {
      const options = document.querySelectorAll('.pattern-option');
      testContinueButton.disabled = true;
      clearTestListeners();

      options.forEach(option => {
        const handler = () => {
          options.forEach(opt => opt.classList.remove('selected'));
          option.classList.add('selected');
          if (option.dataset.correct === 'true') {
            testContinueButton.disabled = false;
            testContinueButton.classList.add('pulse-once');
            option.classList.add('correct');
            showNotification('Correct pattern identified.', 'success');
          } else {
            testContinueButton.classList.add('shake');
            showNotification('Incorrect pattern. Try again.', 'error');
            setTimeout(() => testContinueButton.classList.remove('shake'), 500);
          }
        };
        option.addEventListener('click', handler);
        testEventListeners.push({ element: option, type: 'click', handler });
      });
      addGlitchLine();
      addTopographicOverlay();
      addClassifiedStamp();
    }

    function initMemoryTest() {
      const memoryGrid = document.querySelector('.memory-grid');
      const mapOverlay = document.querySelector('.map-overlay');
      const recallGrid = document.querySelector('.recall-grid');
      const countdownElement = document.querySelector('.countdown');
      let timeLeft = 5;
      testContinueButton.disabled = true;
      clearTestListeners();

      if (memoryGrid && mapOverlay && recallGrid && countdownElement) {
        countdownElement.textContent = timeLeft;
        mapOverlay.textContent = `Memorize in ${timeLeft} seconds`;
        mapOverlay.classList.add('visible');

        const countdown = setInterval(() => {
          timeLeft--;
          countdownElement.textContent = timeLeft;
          mapOverlay.textContent = `Memorize in ${timeLeft} seconds`;
          if (timeLeft <= 0) {
            clearInterval(countdown);
            memoryGrid.style.opacity = '0';
            mapOverlay.classList.remove('visible');
            recallGrid.parentElement.classList.remove('hidden');

            const correctLocations = ['B1', 'A3', 'D4'];
            let selectedLocations = [];

            const cells = document.querySelectorAll('.recall-cell');
            cells.forEach(cell => {
              const handler = () => {
                if (selectedLocations.length < 3) {
                  cell.classList.add('selected');
                  selectedLocations.push(cell.dataset.location);
                  if (selectedLocations.length === 3) {
                    const isCorrect = correctLocations.every(loc => selectedLocations.includes(loc));
                    if (isCorrect) {
                      testContinueButton.disabled = false;
                      testContinueButton.classList.add('pulse-once');
                      showNotification('Memory test passed.', 'success');
                    } else {
                      testContinueButton.classList.add('shake');
                      showNotification('Incorrect recall. Try again.', 'error');
                      setTimeout(() => {
                        testContinueButton.classList.remove('shake');
                        selectedLocations = [];
                        cells.forEach(c => c.classList.remove('selected'));
                      }, 500);
                    }
                  }
                }
              };
              cell.addEventListener('click', handler);
              testEventListeners.push({ element: cell, type: 'click', handler });
            });
          }
        }, 1000);
        addGlitchLine();
        addTopographicOverlay();
        addClassifiedStamp();
      }
    }

    function initReactionTest() {
      const arena = document.querySelector('.reaction-arena');
      const counter = document.querySelector('.capture-counter span');
      let captureCount = 0;
      const requiredCaptures = 5;
      testContinueButton.disabled = true;
      clearTestListeners();

      if (arena && counter) {
        function spawnTarget() {
          if (captureCount >= requiredCaptures) return;
          const target = document.createElement('div');
          target.classList.add('cryptid-target');
          target.innerHTML = '<img src="logo.svg" class="target-logo">';
          target.style.left = `${Math.random() * (arena.offsetWidth - 50)}px`;
          target.style.top = `${Math.random() * (arena.offsetHeight - 50)}px`;
          arena.appendChild(target);
          setTimeout(() => target.classList.add('visible'), 100);

          const handler = () => {
            target.classList.add('captured');
            captureCount++;
            counter.textContent = captureCount;
            setTimeout(() => target.remove(), 500);
            if (captureCount >= requiredCaptures) {
              testContinueButton.disabled = false;
              testContinueButton.classList.add('pulse-once');
              showNotification('Reaction test completed.', 'success');
            } else {
              setTimeout(spawnTarget, 1000);
            }
          };
          target.addEventListener('click', handler);
          testEventListeners.push({ element: target, type: 'click', handler });

          setTimeout(() => {
            if (!target.classList.contains('captured')) {
              target.remove();
              setTimeout(spawnTarget, 1000);
            }
          }, 2000);
        }

        spawnTarget();
        addGlitchLine();
        addTopographicOverlay();
        addClassifiedStamp();
      }
    }

    function showResults() {
      testContinueButton.querySelector('.btn-text').textContent = 'FINALIZE';
      testContinueButton.disabled = false;
      clearTestListeners();
      testContinueButton.addEventListener('click', () => {
        showNotification('Assessment complete. Redirecting...', 'success');
        setTimeout(() => window.location.href = 'Couverture final.php', 2000);
      }, { once: true });
      addGlitchLine();
      addTopographicOverlay();
      addClassifiedStamp();
    }
</script>