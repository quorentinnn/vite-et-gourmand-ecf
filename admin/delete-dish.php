<?php
// admin/delete-dish.php
// Supprimer un plat

require_once '../config/database.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    try {
        $pdo = getConnection();
        
        // Récupérer l'image avant suppression pour la supprimer du serveur
        $sql = "SELECT image FROM dishes WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $dish = $stmt->fetch();
        
        // Supprimer le plat
        $sql = "DELETE FROM dishes WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        // Supprimer l'image du serveur si elle existe
        if ($dish && $dish['image']) {
            $imagePath = '../assets/images/dishes/' . $dish['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        // Redirection avec message de succès
        header('Location: dishes.php?success=supprime');
        exit;
        
    } catch (PDOException $e) {
        header('Location: dishes.php?error=' . urlencode($e->getMessage()));
        exit;
    }
} else {
    header('Location: dishes.php');
    exit;
}
?>