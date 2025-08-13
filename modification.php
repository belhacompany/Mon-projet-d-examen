<?php
include 'include/bd.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$livre = null;

// 1. Récupérer les infos du livre AVANT d'afficher le formulaire
if ($id > 0) {
    $sql = "SELECT * FROM livre WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $livre = $result->fetch_assoc();
    $stmt->close();
}
if (!$livre) {
    echo "<div style='color:red;text-align:center;'>Livre introuvable.</div>";
    exit;
}

// 2. Si le formulaire est soumis, mettre à jour le livre
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier'])) {
    $titre = mysqli_real_escape_string($conn, $_POST['titre']);
    $auteur = mysqli_real_escape_string($conn, $_POST['auteur']);
    $annee = intval($_POST['annee']);
    $genre = mysqli_real_escape_string($conn, $_POST['genre']);

    // Gestion de la couverture
    if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $file_type = $_FILES['file']['type'];
        if (in_array($file_type, $allowed_types)) {
            $image_data = file_get_contents($_FILES['file']['tmp_name']);
            $couverture = base64_encode($image_data);
        } else {
            $couverture = $livre['Couverture'];
        }
    } else {
        $couverture = $livre['Couverture'];
    }

    $sql = "UPDATE livre SET Titre=?, Auteur=?, Annee=?, Genre=?, Couverture=? WHERE ID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssissi", $titre, $auteur, $annee, $genre, $couverture, $id);

    $modif_succes = false;
    if ($stmt->execute()) {
        $modif_succes = true;
        // header("Location: listedeslivre.php");
        // exit;
    } else {
        echo "<div style='color:red;text-align:center;'>Erreur lors de la modification.</div>";
    }
    $stmt->close();

    // Recharger les infos du livre après modification pour affichage
    $sql = "SELECT * FROM livre WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $livre = $result->fetch_assoc();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modification</title>
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
  align-items: space-between;
  gap: 20px;
  cursor: pointer;
  align-items: center;
  justify-content: center;
  border: 2px dashed #cacaca;
  background-color: rgba(255, 255, 255, 1);
  padding: 1.5rem;
  border-radius: 10px;
  box-shadow: 0px 48px 35px -48px rgba(0,0,0,0.1);
}

.custum-file-upload .icon {
  display: flex;
  align-items: center;
  justify-content: center;
}

.custum-file-upload .icon svg {
  height: 80px;
  fill: rgba(75, 85, 99, 1);
}

.custum-file-upload .text {
  display: flex;
  align-items: center;
  justify-content: center;
}

.custum-file-upload .text span {
  font-weight: 400;
  color: rgba(75, 85, 99, 1);
}

.custum-file-upload input {
  display: none;
}
/* From Uiverse.io by Peary74 */ 
button {
  font-family: inherit;
  font-size: 20px;
  background: #212121;
  color: white;
  fill: rgb(155, 153, 153);
  padding: 0.7em 1em;
  padding-left: 0.9em;
  display: flex;
  align-items: center;
  cursor: pointer;
  border: none;
  border-radius: 15px;
  font-weight: 1000;
}

button span {
  display: block;
  margin-left: 0.3em;
  transition: all 0.3s ease-in-out;
}

button svg {
  display: block;
  transform-origin: center center;
  transition: transform 0.3s ease-in-out;
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
    </style>
</head>
<body>
<?php if (isset($modif_succes) && $modif_succes): ?>
    <div style="background:#d4edda;color:#155724;padding:15px;text-align:center;border-radius:6px;margin-bottom:20px;border:1px solid #c3e6cb;">
        Modification effectuée avec succès !
    </div>
<?php endif; ?>
    <div class="container">
  <div class="titre-avec-retour">
    <span class="retour-icone">
      <a href="./listedeslivre.php" title="Retour">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none">
          <circle cx="12" cy="12" r="12" fill="#212121"/>
          <path d="M14.5 7L10 12L14.5 17" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </a>
    </span>
    <h2>Modifier un Livre</h2>
  </div>
  <form action="" method="post" enctype="multipart/form-data">
    <label for="titre">Titre du livre</label>
    <input type="text" id="titre" name="titre" value="<?php echo htmlspecialchars($livre['Titre']); ?>" required>

    <label for="auteur">Auteur</label>
    <input type="text" id="auteur" name="auteur" value="<?php echo htmlspecialchars($livre['Auteur']); ?>" required>

    <label for="annee">Année de publication</label>
    <input type="number" id="annee" name="annee" min="1000" max="9999" value="<?php echo htmlspecialchars($livre['Annee']); ?>" required>

    <label for="genre">Genre</label>
    <select id="genre" name="genre" required>
        <option value="">--Choisir un genre--</option>
        <option value="Roman" <?php if($livre['Genre']=='Roman') echo 'selected'; ?>>Roman</option>
        <option value="Science-fiction" <?php if($livre['Genre']=='Science-fiction') echo 'selected'; ?>>Science-fiction</option>
        <option value="Biographie" <?php if($livre['Genre']=='Biographie') echo 'selected'; ?>>Biographie</option>
        <option value="Poésie" <?php if($livre['Genre']=='Poésie') echo 'selected'; ?>>Poésie</option>
        <option value="Autre" <?php if($livre['Genre']=='Autre') echo 'selected'; ?>>Autre</option>
    </select>
    <label class="custum-file-upload" for="file">
        <div class="icon">
<svg xmlns="http://www.w3.org/2000/svg" fill="" viewBox="0 0 24 24"><g stroke-width="0" id="SVGRepo_bgCarrier"></g><g stroke-linejoin="round" stroke-linecap="round" id="SVGRepo_tracerCarrier"></g><g id="SVGRepo_iconCarrier"> <path fill="" d="M10 1C9.73478 1 9.48043 1.10536 9.29289 1.29289L3.29289 7.29289C3.10536 7.48043 3 7.73478 3 8V20C3 21.6569 4.34315 23 6 23H7C7.55228 23 8 22.5523 8 22C8 21.4477 7.55228 21 7 21H6C5.44772 21 5 20.5523 5 20V9H10C10.5523 9 11 8.55228 11 8V3H18C18.5523 3 19 3.44772 19 4V9C19 9.55228 19.4477 10 20 10C20.5523 10 21 9.55228 21 9V4C21 2.34315 19.6569 1 18 1H10ZM9 7H6.41421L9 4.41421V7ZM14 15.5C14 14.1193 15.1193 13 16.5 13C17.8807 13 19 14.1193 19 15.5V16V17H20C21.1046 17 22 17.8954 22 19C22 20.1046 21.1046 21 20 21H13C11.8954 21 11 20.1046 11 19C11 17.8954 11.8954 17 13 17H14V16V15.5ZM16.5 11C14.142 11 12.2076 12.8136 12.0156 15.122C10.2825 15.5606 9 17.1305 9 19C9 21.2091 10.7909 23 13 23H20C22.2091 23 24 21.2091 24 19C24 17.1305 22.7175 15.5606 20.9844 15.122C20.7924 12.8136 18.858 11 16.5 11Z" clip-rule="evenodd" fill-rule="evenodd"></path> </g></svg>
        </div>
        <div class="text"><span>Click ici pour le photo du livre</span></div>
        <input type="file" id="file" name="file">
    </label>
    <?php if (!empty($livre['Couverture'])): ?>
        <img id="img-preview" src="data:image/jpeg;base64,<?php echo $livre['Couverture']; ?>" style="max-width:100px;max-height:120px;margin:10px auto;display:block;border-radius:6px;">
    <?php else: ?>
        <img id="img-preview" style="max-width:100px;max-height:120px;margin:10px auto;display:none;border-radius:6px;">
    <?php endif; ?>
    <div>
        <button type="submit" name="modifier">
            <span>Modifier</span>
        </button>
    </div>
</form>
    </div>
</body>
</html>