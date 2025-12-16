<?php
// admin/edit-regime.php
// Modifier un régime

require_once '../config/database.php';

if (!isset($_GET['id'])) {
    header('Location: regimes.php');
    exit;
}

$id = (int)$_GET['id'];

try {
    $pdo = getConnection();
    $sql = "SELECT * FROM regimes WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $regime = $stmt->fetch();
    
    if (!$regime) {
        header('Location: regimes.php?error=Régime introuvable');
        exit;
    }
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars($_POST['nom']);
    
    try {
        $sql = "UPDATE regimes SET nom = :nom WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['nom' => $nom, 'id' => $id]);
        
        header('Location: regimes.php?success=modifie');
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
    <title>Modifier un régime - Vite & Gourmand</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/admin-nav.php'; ?>
    <div class="container mt-5">
        <h1 class="mb-4">✏️ Modifier un régime</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom du régime *</label>
                        <input type="text" class="form-control" id="nom" name="nom" 
                               value="<?= htmlspecialchars($regime['nom']) ?>" required>
                    </div>
                    
                    <button type="submit" class="btn btn-success">✅ Enregistrer</button>
                    <a href="regimes.php" class="btn btn-secondary">❌ Annuler</a>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>