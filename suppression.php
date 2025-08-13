<?php
include 'include/bd.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Suppression du livre
    $stmt = $conn->prepare("DELETE FROM livre WHERE ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // Réorganiser les IDs
    $conn->query("SET @num := 0");
    $conn->query("UPDATE livre SET ID = (@num := @num + 1) ORDER BY ID");
    $conn->query("ALTER TABLE livre AUTO_INCREMENT = 1");
}

// Redirection vers la liste des livres
header("Location: listedeslivre.php");
exit;