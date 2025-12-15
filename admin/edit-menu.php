<?php
// admin/edit-menu.php
// Modifier un menu

require_once '../config/database.php';

if (!isset($_GET['id'])) {
    header('Location: menus.php');
    exit;
}

$id = (int)$_GET['id'];

try {
    $pdo = getConnection();
    
    // Récupérer le menu
    $sql = "SELECT * FROM menus WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $menu = $stmt->fetch();
    
    if (!$menu) {
        header('Location: menus.php?error=Menu introuvable');
        exit;
    }
    
    // Récupérer les thèmes et régimes
    $stmtThemes = $pdo->query("SELECT * FROM themes ORDER BY nom");
    $themes = $stmtThemes->fetchAll();
    
    $stmtRegimes = $pdo->query("SELECT * FROM regimes ORDER BY nom");
    $regimes = $stmtRegimes->fetchAll();
    
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = htmlspecialchars($_POST['titre']);
    $description = htmlspecialchars($_POST['description']);
    $theme_id = (int)$_POST['theme_id'];
    $regime_id = (int)$_POST['regime_id'];
    $nombre_personnes_min = (int)$_POST['nombre_personnes_min'];
    $prix_base = (float)$_POST['prix_base'];
    $conditions = htmlspecialchars($_POST['conditions']);
    $stock_disponible = (int)$_POST['stock_disponible'];
    
    try {
        $sql = "UPDATE menus SET 
                titre = :titre, 
                description = :description, 
                theme_id = :theme_id, 
                regime_id = :regime_id, 
                nombre_personnes_min = :nombre_personnes_min, 
                prix_base = :prix_base, 
                conditions = :conditions, 
                stock_disponible = :stock_disponible 
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'titre' => $titre,
            'description' => $description,
            'theme_id' => $theme_id,
            'regime_id' => $regime_id,
            'nombre_personnes_min' => $nombre_personnes_min,
            'prix_base' => $prix_base,
            'conditions' => $conditions,
            'stock_disponible' => $stock_disponible,
            'id' => $id
        ]);
        
        header('Location: menus.php?success=modifie');
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
    <title>Modifier un menu - Vite & Gourmand</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">✏️ Modifier un menu</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="titre" class="form-label">Titre du menu *</label>
                        <input type="text" class="form-control" id="titre" name="titre" 
                               value="<?= htmlspecialchars($menu['titre']) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description *</label>
                        <textarea class="form-control" id="description" name="description" rows="4" required><?= htmlspecialchars($menu['description']) ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="theme_id" class="form-label">Thème *</label>
                            <select class="form-select" id="theme_id" name="theme_id" required>
                                <?php foreach ($themes as $theme): ?>
                                    <option value="<?= $theme['id'] ?>" <?= $menu['theme_id'] == $theme['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($theme['nom']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="regime_id" class="form-label">Régime *</label>
                            <select class="form-select" id="regime_id" name="regime_id" required>
                                <?php foreach ($regimes as $regime): ?>
                                    <option value="<?= $regime['id'] ?>" <?= $menu['regime_id'] == $regime['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($regime['nom']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="nombre_personnes_min" class="form-label">Nb personnes min *</label>
                            <input type="number" class="form-control" id="nombre_personnes_min" name="nombre_personnes_min" 
                                   value="<?= $menu['nombre_personnes_min'] ?>" min="1" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="prix_base" class="form-label">Prix de base (€) *</label>
                            <input type="number" class="form-control" id="prix_base" name="prix_base" 
                                   value="<?= $menu['prix_base'] ?>" step="0.01" min="0" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="stock_disponible" class="form-label">Stock disponible *</label>
                            <input type="number" class="form-control" id="stock_disponible" name="stock_disponible" 
                                   value="<?= $menu['stock_disponible'] ?>" min="0" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="conditions" class="form-label">Conditions du menu</label>
                        <textarea class="form-control" id="conditions" name="conditions" rows="3"><?= htmlspecialchars($menu['conditions']) ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-success">✅ Enregistrer</button>
                    <a href="menus.php" class="btn btn-secondary">❌ Annuler</a>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>