<link rel="stylesheet" href="style1.css">
<style>
        body.body {
            background: url('madagascar.png') no-repeat center center fixed;
            background-size: cover;
        }
        .form {
            background: rgba(255,255,255,0.85);
            padding: 30px;
            border-radius: 10px;
            max-width: 600px;
            margin: 40px auto;
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        }
        .title {
            font-size: 1.5em;
            font-weight: bold;
            text-align: center;
        }
        .message {
            text-align: center;
            margin-bottom: 20px;
        }
        .flex {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        label {
            display: block;
            margin-bottom: 15px;
            width: 100%;
        }
        .input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .submit {
            display: block;
            width: 100%;
            padding: 10px;
            background: #314ce6;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            margin-top: 20px;
        }
        .submit:hover {
            background: #41b4ee;
        }
    </style>
</head>
<body>
<?php
include_once'include/bd.php';
if (isset($_POST['ajouter'])) {
    $nom = $_POST['nom'];
    $Fanampiny = $_POST['Fanampiny'];
    $daty = $_POST['daty'];
    $Toerana = $_POST['Toerana'];
    $signe = $_POST['signe'];
    $numero = $_POST['numero'];
    $fonenana = $_POST['fonenana'];
    $boriborintany = $_POST['boriborintany'];
    $asa = $_POST['asa'];
    $ray = $_POST['ray'];
    $reny = $_POST['reny'];
    $natao = $_POST['natao'];
    $le = $_POST['le'];
    $numero1 = $_POST['numero1'];
    $sary = $_POST['Sary'];
    $req = mysqli_query($conn, "INSERT INTO cin(`Anarana`, `Fanampiny`, `Daty`, `Toerana`, `Famantarana`, `Laharana`, `Fonenana`, `Boriborintany`, `Asa`, `Ray niteraka`, `Reny niteraka`, `Natao tao`, `Daty nanaovana`, `N°`,`Sary`) 
    VALUES ('$nom', '$Fanampiny', '$daty', '$Toerana', '$signe', '$numero', '$fonenana', '$boriborintany', '$asa', '$ray', '$reny','$natao', '$le', '$numero1', '$sary')");
    if ($req) {
        echo "<script>alert('CIN a été ajouté avec succès');</script>";
    } else {
        echo "<script>alert('Erreur lors de l\'ajout du CIN');</script>";
    }
}
?>
    <div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-6"> 
                <form class="form" action="" method="POST" enctype="multipart/form-data">
                    <img src="logo mada.jpg" alt="" style="align-self: center;width: 100%;">
                   
        <p class="title">KARA-PANONDROM-PIRENENA</p>
        <p class="message">(Carte d'Identité National)</p>
        <div class="flex">
            <label>
                <span>ANARANA :</span>
                <input required placeholder="Nom" type="text" class="input" name="nom">
            </label>
            <label>
                <span>FANAMPINY ANARANA :</span>
                <input required placeholder="Prenom" type="text" class="input" name="Fanampiny">
            </label>
            <input type="image" src="signature.png" alt="">
        </div>
        <label>
            <span>TERAKA TAMIN'NY :</span>
            <input required placeholder="Né(e) le" type="date" class="input" name="daty">
        </label>
        <label>
            <span>TAO :</span>
            <input required placeholder="à" type="text" class="input" name="Toerana"">
        </label>
        <label>
            <span>FAMANTARANA MANOKANA :</span>
            <input required placeholder="Signe particulier" type="text" class="input" name="signe">
        </label>
        <label for="">
            <span>
                Laharana : 
            </span>
            <input type="text" class="input" placeholder="N° " name="numero" required>
        </label>
                     <label>
                <span>FONENANA :</span>
                <input required placeholder="Domaine " type="text" class="input" name="fonenana">
            </label>
            <label for="">
                <span>BORIBORITANY : </span>
                <input type="text" class="input" placeholder="Arrondissement " name="boriborintany">
            </label>
            <label for="">
                <span>
                    ASA ATAO :
                </span>
                <input type="text" class="input" placeholder="Profession " name="asa">
            </label>
            <label for="">
                <span>RAY NITERAKA :</span>
                <input type="text" class="input" placeholder="Père " name="ray">
            </label>
            <label for="">
                <span>RENY NITERAKA :</span>
                <input type="text" class="input" placeholder="Mere " name="reny">
            </label>
            <label for="">
                <span>NATAO TAO :</span>
                <select name="natao" type="text" class="input" required>
                    <option value="" id="" disabled="disabled">SAFIDY</option>
                    <option value="Antananarivo">Antananarivo</option>
                    <option value="Antsiranana">Antsiranana</option>
                    <option value="Mahajanga">Mahajanga</option>
                    <option value="Fianarantsoa">Fianarantsoa</option>
                    <option value="Toamasina">Toamasina</option>
                    <option value="Toliara">Toliara</option>
                    <option value="Antalaha">Antalaha</option>
                    <option value="Ambatondrazaka">Ambatondrazaka</option>
                    <option value="Ambanja">Ambanja</option>
                    <option value="Ambositra">Ambositra</option>
                    <option value="Androy">Androy</option>
                    <option value="Analamanga">Analamanga</option>
                    <option value="Anosy">Anosy</option>
                </select>
            </label>
              <label for="">
                <span>TAMIN'NY :</span>
                <input type="date" class="input" placeholder=" Le " name="le">
            </label>
            <label for="">
                <span class="numero "> N° </span>
                <input type="text" class="input" placeholder="" name="numero1" required>
            </label>
            <label>
                <span>PHOTO :</span>
                    <span class="text" name="Sary">File Explorer</span>
                </button>

                <input type="file" class="input" name="photo" accept="image/*" required>
            </label>
                     <button class="submit" name="ajouter">Enregistrer</button>
                </form>

            </div>
        </div>
    </div>
      <script src="script.js"></script>
</body>
</html>