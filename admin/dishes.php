<?php
// admin/dishes.php
// Page qui liste tous les plats

require_once '../config/database.php';

// Récupérer tous les plats
try {
    $pdo = getConnection();
    $sql = "SELECT * FROM dishes ORDER BY created_at DESC";
    $stmt = $pdo->query($sql);
    $dishes = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "❌ Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des plats - Vite & Gourmand</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>📋 Liste des plats</h1>
            <a href="add-dish.php" class="btn btn-primary">➕ Ajouter un plat</a>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['success']) && $_GET['success'] === 'supprime'): ?>
    <div class="alert alert-success">✅ Plat supprimé avec succès !</div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger">❌ Erreur : <?= htmlspecialchars($_GET['error']) ?></div>
<?php endif; ?>
<?php if (isset($_GET['success']) && $_GET['success'] === 'modifie'): ?>
    <div class="alert alert-success">✅ Plat modifié avec succès !</div>
<?php endif; ?>
        
        <?php if (empty($dishes)): ?>
            <div class="alert alert-info">
                Aucun plat pour le moment. <a href="add-dish.php">Ajoutez-en un !</a>
            </div>
        <?php else: ?>
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Nom</th>
                                <th>Type</th>
                                <th>Date de création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dishes as $dish): ?>
                                <tr>
                                    <td><?= $dish['id'] ?></td>
                                    <td>
                                        <?php if ($dish['image']): ?>
                                            <img src="../assets/images/dishes/<?= htmlspecialchars($dish['image']) ?>" 
                                                 alt="<?= htmlspecialchars($dish['nom']) ?>" 
                                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                        <?php else: ?>
                                            <span class="text-muted">Pas d'image</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($dish['nom']) ?></td>
                                    <td>
                                        <?php
                                        $badges = [
                                            'entree' => '<span class="badge bg-success">Entrée</span>',
                                            'plat' => '<span class="badge bg-primary">Plat</span>',
                                            'dessert' => '<span class="badge bg-warning">Dessert</span>'
                                        ];
                                        echo $badges[$dish['type_plat']] ?? $dish['type_plat'];
                                        ?>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($dish['created_at'])) ?></td>
                                    <td>
                                        <a href="edit-dish.php?id=<?= $dish['id'] ?>" class="btn btn-sm btn-warning">✏️ Modifier</a>
                                        <a href="delete-dish.php?id=<?= $dish['id'] ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce plat ?')">🗑️ Supprimer</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>