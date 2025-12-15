<?php
// admin/delete-menu.php
// Supprimer un menu

require_once '../config/database.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    try {
        $pdo = getConnection();
        
        // Supprimer le menu (les associations menus_dishes seront supprimées automatiquement grâce à ON DELETE CASCADE)
        $sql = "DELETE FROM menus WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        header('Location: menus.php?success=supprime');
        exit;
    } catch (PDOException $e) {
        header('Location: menus.php?error=' . urlencode($e->getMessage()));
        exit;
    }
} else {
    header('Location: menus.php');
    exit;
}
?>