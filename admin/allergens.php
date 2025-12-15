<?php
// admin/allergens.php
// Liste des allergènes

require_once '../config/database.php';

try {
    $pdo = getConnection();
    $sql = "SELECT * FROM allergens ORDER BY nom ASC";
    $stmt = $pdo->query($sql);
    $allergens = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "❌ Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des allergènes - Vite & Gourmand</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>⚠️ Liste des allergènes</h1>
            <a href="add-allergen.php" class="btn btn-primary">➕ Ajouter un allergène</a>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <?php if (isset($_GET['success']) && $_GET['success'] === 'ajoute'): ?>
            <div class="alert alert-success">✅ Allergène ajouté avec succès !</div>
        <?php endif; ?>
        
        <?php if (isset($_GET['success']) && $_GET['success'] === 'supprime'): ?>
            <div class="alert alert-success">✅ Allergène supprimé avec succès !</div>
        <?php endif; ?>
        
        <?php if (isset($_GET['success']) && $_GET['success'] === 'modifie'): ?>
            <div class="alert alert-success">✅ Allergène modifié avec succès !</div>
        <?php endif; ?>
        
        <?php if (empty($allergens)): ?>
            <div class="alert alert-info">
                Aucun allergène pour le moment. <a href="add-allergen.php">Ajoutez-en un !</a>
            </div>
        <?php else: ?>
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($allergens as $allergen): ?>
                                <tr>
                                    <td><?= $allergen['id'] ?></td>
                                    <td><?= htmlspecialchars($allergen['nom']) ?></td>
                                    <td>
                                        <a href="edit-allergen.php?id=<?= $allergen['id'] ?>" class="btn btn-sm btn-warning">✏️ Modifier</a>
                                        <a href="delete-allergen.php?id=<?= $allergen['id'] ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet allergène ?')">🗑️ Supprimer</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>