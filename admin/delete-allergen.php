<?php
// admin/delete-allergen.php
// Supprimer un allergène

require_once '../config/database.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    try {
        $pdo = getConnection();
        $sql = "DELETE FROM allergens WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        header('Location: allergens.php?success=supprime');
        exit;
    } catch (PDOException $e) {
        header('Location: allergens.php?error=' . urlencode($e->getMessage()));
        exit;
    }
} else {
    header('Location: allergens.php');
    exit;
}
?>