<?php
// admin/add-theme.php
// Ajouter un thème

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars($_POST['nom']);
    
    try {
        $pdo = getConnection();
        $sql = "INSERT INTO themes (nom) VALUES (:nom)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['nom' => $nom]);
        
        header('Location: themes.php?success=ajoute');
        exit;
    } catch (PDOException $e) {
        $error = "❌ Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un thème - Vite & Gourmand</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/admin-nav.php'; ?>
    <div class="container mt-5">
        <h1 class="mb-4">🎨 Ajouter un thème</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom du thème *</label>
                        <input type="text" class="form-control" id="nom" name="nom" required 
                               placeholder="Ex: Noël, Pâques, Été...">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">✅ Ajouter</button>
                    <a href="themes.php" class="btn btn-secondary">📋 Voir tous les thèmes</a>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>