<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Livre</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f6fa;
            margin: 0;
            padding: 0;
        }
        .container {
            background: #fff;
            max-width: 400px;
            margin: 60px auto;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #434246;
        }
        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            color: #333;
        }
        input, select {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
            font-size: 16px;
        }
        button {
            width: 100%;
            background: #1a1f2c;
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background: #b4eaed;
        }
        .custum-file-upload {
            height: 200px;
            width: 300px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 2px dashed #cacaca;
            background-color: rgba(255, 255, 255, 1);
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0px 48px 35px -48px rgba(0,0,0,0.1);
            cursor: pointer;
        }
        .custum-file-upload .icon svg {
            height: 80px;
            fill: rgba(75, 85, 99, 1);
        }
        .custum-file-upload .text span {
            font-weight: 400;
            color: rgba(75, 85, 99, 1);
        }
        .custum-file-upload input {
            display: none;
        }
        button {
            font-family: inherit;
            font-size: 20px;
            background: #212121;
            color: white;
            fill: rgb(155, 153, 153);
            padding: 0.7em 1em;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 15px;
            font-weight: bold;
        }
        button:hover {
            background: #000;
        }
        button:hover .svg-wrapper {
            transform: scale(1.25);
            transition: 0.5s linear;
        }
        button:hover svg {
            transform: translateX(1.2em) scale(1.1);
            fill: #fff;
        }
        button:hover span {
            opacity: 0;
            transition: 0.5s linear;
        }
        button:active {
            transform: scale(0.95);
        }
        .titre-avec-retour {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 10px;
            margin-bottom: 20px;
        }
        .retour-icone {
            position: static;
            top: auto;
            right: auto;
            z-index: 1;
            margin-right: 8px;
        }
        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="titre-avec-retour">
            <span class="retour-icone">
                <a href="./affichageacceuil.php" title="Retour à la page d'accueil">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" aria-label="Retour">
                        <circle cx="12" cy="12" r="12" fill="#212121"/>
                        <path d="M14.5 7L10 12L14.5 17" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </span>
            <h2>Ajouter un Livre</h2>
        </div>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            include 'include/bd.php';
            if (!$conn) {
                echo '<div class="message error">Erreur de connexion à la base de données.</div>';
                exit;
            }

            // Validate inputs
            $titre = trim($_POST['titre'] ?? '');
            $auteur = trim($_POST['auteur'] ?? '');
            $annee = (int)($_POST['annee'] ?? 0);
            $genre = trim($_POST['genre'] ?? ''); // Fallback to empty string if undefined

            if (empty($titre) || empty($auteur) || $annee < 1000 || $annee > date('Y')) {
                echo '<div class="message error">Veuillez remplir tous les champs correctement.</div>';
            } else {
                $couverture = null;
                if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    $file_type = $_FILES['file']['type'];
                    $file_size = $_FILES['file']['size'];

                    if (!in_array($file_type, $allowed_types)) {
                        echo '<div class="message error">Type de fichier non autorisé. Utilisez JPG, PNG, GIF ou WebP.</div>';
                    } elseif ($file_size > 2 * 1024 * 1024) { // 2MB limit
                        echo '<div class="message error">Le fichier est trop volumineux (max 2 Mo).</div>';
                    } else {
                        $image_data = file_get_contents($_FILES['file']['tmp_name']);
                        $couverture = base64_encode($image_data);
                    }
                }

                if (!isset($couverture) || $couverture !== false) {
                    $sql = "INSERT INTO livre (Titre, Auteur, Annee, Genre, Couverture) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    if (!$stmt) {
                        echo '<div class="message error">Erreur SQL : ' . $conn->error . '</div>';
                    } else {
                        $stmt->bind_param("ssiss", $titre, $auteur, $annee, $genre, $couverture);
                        if ($stmt->execute()) {
                            echo '<div class="message success">Livre enregistré avec succès !</div>';
                        } else {
                            echo '<div class="message error">Erreur lors de l\'enregistrement : ' . $conn->error . '</div>';
                        }
                        $stmt->close();
                    }
                }
            }
            $conn->close();
        }
        ?>

        <form action="" method="post" enctype="multipart/form-data">
            <label for="titre">Titre du livre</label>
            <input type="text" id="titre" name="titre" maxlength="255" required>

            <label for="auteur">Auteur</label>
            <input type="text" id="auteur" name="auteur" maxlength="255" required>

            <label for="annee">Année de publication</label>
            <input type="number" id="annee" name="annee" min="1000" max="<?php echo date('Y'); ?>" required>

            <label for="genre">Genre</label>
            <select id="genre" name="genre">
                <option value="">Sélectionnez un genre</option>
                <option value="Roman">Roman</option>
                <option value="Science-fiction">Science-fiction</option>
                <option value="Fantastique">Fantastique</option>
                <option value="Biographie">Biographie</option>
                <option value="Poésie">Poésie</option>
            </select>

            <label class="custum-file-upload" for="file">
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M10 1C9.73478 1 9.48043 1.10536 9.29289 1.29289L3.29289 7.29289C3.10536 7.48043 3 7.73478 3 8V20C3 21.6569 4.34315 23 6 23H7C7.55228 23 8 22.5523 8 22C8 21.4477 7.55228 21 7 21H6C5.44772 21 5 20.5523 5 20V9H10C10.5523 9 11 8.55228 11 8V3H18C18.5523 3 19 3.44772 19 4V9C19 9.55228 19.4477 10 20 10C20.5523 10 21 9.55228 21 9V4C21 2.34315 19.6569 1 18 1H10ZM9 7H6.41421L9 4.41421V7ZM14 15.5C14 14.1193 15.1193 13 16.5 13C17.8807 13 19 14.1193 19 15.5V16V17H20C21.1046 17 22 17.8954 22 19C22 20.1046 21.1046 21 20 21H13C11.8954 21 11 20.1046 11 19C11 17.8954 11.8954 17 13 17H14V16V15.5ZM16.5 11C14.142 11 12.2076 12.8136 12.0156 15.122C10.2825 15.5606 9 17.1305 9 19C9 21.2091 10.7909 23 13 23H20C22.2091 23 24 21.2091 24 19C24 17.1305 22.7175 15.5606 20.9844 15.122C20.7924 12.8136 18.858 11 16.5 11Z" clip-rule="evenodd" fill-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="text">
                    <span>Télécharger la couverture du livre (JPG, PNG, GIF, WebP)</span>
                </div>
                <input type="file" id="file" name="file" accept="image/jpeg,image/png,image/gif,image/webp">
            </label>

            <button type="submit">
                <div class="svg-wrapper-1">
                    <div class="svg-wrapper">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="30" height="30" class="icon">
                            <path d="M22,15.04C22,17.23 20.24,19 18.07,19H5.93C3.76,19 2,17.23 2,15.04C2,13.07 3.43,11.44 5.31,11.14C5.28,11 5.27,10.86 5.27,10.71C5.27,9.33 6.38,8.20 7.76,8.2C8.37,8.2 8.94,8.43 9.37,8.8C10.14,7.05 11.13,5.44 13.91,5.44C17.28,5.44 18.87,8.06 18.87,10.83C18.87,10.94 18.87,11.06 18.86,11.17C20.65,11.54 22,13.13 22,15.04Z"></path>
                        </svg>
                    </div>
                </div>
                <span>Enregistrer</span>
            </button>
        </form>
    </div>
</body>
</html>