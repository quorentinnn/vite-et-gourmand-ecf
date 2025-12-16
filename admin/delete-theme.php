<?php
// admin/delete-theme.php
// Supprimer un thème

require_once '../config/database.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    try {
        $pdo = getConnection();
        $sql = "DELETE FROM themes WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        header('Location: themes.php?success=supprime');
        exit;
    } catch (PDOException $e) {
        header('Location: themes.php?error=' . urlencode($e->getMessage()));
        exit;
    }
} else {
    header('Location: themes.php');
    exit;
}
?>