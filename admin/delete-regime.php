<?php
// admin/delete-regime.php
// Supprimer un régime

require_once '../config/database.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    try {
        $pdo = getConnection();
        $sql = "DELETE FROM regimes WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        header('Location: regimes.php?success=supprime');
        exit;
    } catch (PDOException $e) {
        header('Location: regimes.php?error=' . urlencode($e->getMessage()));
        exit;
    }
} else {
    header('Location: regimes.php');
    exit;
}
?>