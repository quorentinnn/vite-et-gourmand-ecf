<?php
// admin/themes.php
// Liste des thèmes

require_once '../config/database.php';

try {
    $pdo = getConnection();
    $sql = "SELECT * FROM themes ORDER BY nom ASC";
    $stmt = $pdo->query($sql);
    $themes = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "❌ Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des thèmes - Vite & Gourmand</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/admin-nav.php'; ?>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>🎨 Liste des thèmes</h1>
            <a href="add-theme.php" class="btn btn-primary">➕ Ajouter un thème</a>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <?php if (isset($_GET['success']) && $_GET['success'] === 'ajoute'): ?>
            <div class="alert alert-success">✅ Thème ajouté avec succès !</div>
        <?php endif; ?>
        
        <?php if (isset($_GET['success']) && $_GET['success'] === 'supprime'): ?>
            <div class="alert alert-success">✅ Thème supprimé avec succès !</div>
        <?php endif; ?>
        
        <?php if (isset($_GET['success']) && $_GET['success'] === 'modifie'): ?>
            <div class="alert alert-success">✅ Thème modifié avec succès !</div>
        <?php endif; ?>
        
        <?php if (empty($themes)): ?>
            <div class="alert alert-info">
                Aucun thème pour le moment. <a href="add-theme.php">Ajoutez-en un !</a>
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
                            <?php foreach ($themes as $theme): ?>
                                <tr>
                                    <td><?= $theme['id'] ?></td>
                                    <td><?= htmlspecialchars($theme['nom']) ?></td>
                                    <td>
                                        <a href="edit-theme.php?id=<?= $theme['id'] ?>" class="btn btn-sm btn-warning">✏️ Modifier</a>
                                        <a href="delete-theme.php?id=<?= $theme['id'] ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce thème ?')">🗑️ Supprimer</a>
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