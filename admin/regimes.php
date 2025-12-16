<?php
// admin/regimes.php
// Liste des régimes

require_once '../config/database.php';

try {
    $pdo = getConnection();
    $sql = "SELECT * FROM regimes ORDER BY nom ASC";
    $stmt = $pdo->query($sql);
    $regimes = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "❌ Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des régimes - Vite & Gourmand</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/admin-nav.php'; ?>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>🥗 Liste des régimes</h1>
            <a href="add-regime.php" class="btn btn-primary">➕ Ajouter un régime</a>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <?php if (isset($_GET['success']) && $_GET['success'] === 'ajoute'): ?>
            <div class="alert alert-success">✅ Régime ajouté avec succès !</div>
        <?php endif; ?>
        
        <?php if (isset($_GET['success']) && $_GET['success'] === 'supprime'): ?>
            <div class="alert alert-success">✅ Régime supprimé avec succès !</div>
        <?php endif; ?>
        
        <?php if (isset($_GET['success']) && $_GET['success'] === 'modifie'): ?>
            <div class="alert alert-success">✅ Régime modifié avec succès !</div>
        <?php endif; ?>
        
        <?php if (empty($regimes)): ?>
            <div class="alert alert-info">
                Aucun régime pour le moment. <a href="add-regime.php">Ajoutez-en un !</a>
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
                            <?php foreach ($regimes as $regime): ?>
                                <tr>
                                    <td><?= $regime['id'] ?></td>
                                    <td><?= htmlspecialchars($regime['nom']) ?></td>
                                    <td>
                                        <a href="edit-regime.php?id=<?= $regime['id'] ?>" class="btn btn-sm btn-warning">✏️ Modifier</a>
                                        <a href="delete-regime.php?id=<?= $regime['id'] ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce régime ?')">🗑️ Supprimer</a>
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