<?php
// admin/add-menu.php
// Ajouter un menu avec sélection des plats

require_once '../config/database.php';

try {
    $pdo = getConnection();
    
    // Récupérer les thèmes et régimes
    $stmtThemes = $pdo->query("SELECT * FROM themes ORDER BY nom");
    $themes = $stmtThemes->fetchAll();
    
    $stmtRegimes = $pdo->query("SELECT * FROM regimes ORDER BY nom");
    $regimes = $stmtRegimes->fetchAll();
    
    // Récupérer tous les plats groupés par type
    $stmtDishes = $pdo->query("SELECT * FROM dishes ORDER BY type_plat, nom");
    $allDishes = $stmtDishes->fetchAll();
    
    // Grouper les plats par type
    $dishesByType = [
        'entree' => [],
        'plat' => [],
        'dessert' => []
    ];
    
    foreach ($allDishes as $dish) {
        $dishesByType[$dish['type_plat']][] = $dish;
    }
    
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
    $selected_dishes = isset($_POST['dishes']) ? $_POST['dishes'] : [];
    
    try {
        // Insérer le menu
        $sql = "INSERT INTO menus (titre, description, theme_id, regime_id, nombre_personnes_min, prix_base, conditions, stock_disponible) 
                VALUES (:titre, :description, :theme_id, :regime_id, :nombre_personnes_min, :prix_base, :conditions, :stock_disponible)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'titre' => $titre,
            'description' => $description,
            'theme_id' => $theme_id,
            'regime_id' => $regime_id,
            'nombre_personnes_min' => $nombre_personnes_min,
            'prix_base' => $prix_base,
            'conditions' => $conditions,
            'stock_disponible' => $stock_disponible
        ]);
        
        // Récupérer l'ID du menu créé
        $menu_id = $pdo->lastInsertId();
        
        // Insérer les associations menu-plats
        if (!empty($selected_dishes)) {
            $sqlAssoc = "INSERT INTO menus_dishes (menu_id, dish_id) VALUES (:menu_id, :dish_id)";
            $stmtAssoc = $pdo->prepare($sqlAssoc);
            
            foreach ($selected_dishes as $dish_id) {
                $stmtAssoc->execute([
                    'menu_id' => $menu_id,
                    'dish_id' => (int)$dish_id
                ]);
            }
        }
        
        header('Location: menus.php?success=ajoute');
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
    <title>Ajouter un menu - Vite & Gourmand</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">🍽️ Ajouter un menu</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="titre" class="form-label">Titre du menu *</label>
                        <input type="text" class="form-control" id="titre" name="titre" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description *</label>
                        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="theme_id" class="form-label">Thème *</label>
                            <select class="form-select" id="theme_id" name="theme_id" required>
                                <option value="">-- Choisir un thème --</option>
                                <?php foreach ($themes as $theme): ?>
                                    <option value="<?= $theme['id'] ?>"><?= htmlspecialchars($theme['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="regime_id" class="form-label">Régime *</label>
                            <select class="form-select" id="regime_id" name="regime_id" required>
                                <option value="">-- Choisir un régime --</option>
                                <?php foreach ($regimes as $regime): ?>
                                    <option value="<?= $regime['id'] ?>"><?= htmlspecialchars($regime['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="nombre_personnes_min" class="form-label">Nb personnes min *</label>
                            <input type="number" class="form-control" id="nombre_personnes_min" name="nombre_personnes_min" min="1" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="prix_base" class="form-label">Prix de base (€) *</label>
                            <input type="number" class="form-control" id="prix_base" name="prix_base" step="0.01" min="0" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="stock_disponible" class="form-label">Stock disponible *</label>
                            <input type="number" class="form-control" id="stock_disponible" name="stock_disponible" min="0" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="conditions" class="form-label">Conditions du menu</label>
                        <textarea class="form-control" id="conditions" name="conditions" rows="3" 
                                  placeholder="Ex: Commander au minimum 7 jours avant la prestation. Conservation au frais."></textarea>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- SÉLECTION DES PLATS -->
                    <h4 class="mb-3">🍴 Composition du menu</h4>
                    <p class="text-muted">Sélectionnez les plats qui composent ce menu</p>
                    
                    <div class="row">
                        <!-- ENTRÉES -->
                        <div class="col-md-4 mb-3">
                            <h5 class="text-success">🥗 Entrées</h5>
                            <?php if (empty($dishesByType['entree'])): ?>
                                <p class="text-muted small">Aucune entrée disponible</p>
                            <?php else: ?>
                                <?php foreach ($dishesByType['entree'] as $dish): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="dishes[]" 
                                               value="<?= $dish['id'] ?>" id="dish_<?= $dish['id'] ?>">
                                        <label class="form-check-label" for="dish_<?= $dish['id'] ?>">
                                            <?= htmlspecialchars($dish['nom']) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        
                        <!-- PLATS -->
                        <div class="col-md-4 mb-3">
                            <h5 class="text-primary">🍖 Plats</h5>
                            <?php if (empty($dishesByType['plat'])): ?>
                                <p class="text-muted small">Aucun plat disponible</p>
                            <?php else: ?>
                                <?php foreach ($dishesByType['plat'] as $dish): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="dishes[]" 
                                               value="<?= $dish['id'] ?>" id="dish_<?= $dish['id'] ?>">
                                        <label class="form-check-label" for="dish_<?= $dish['id'] ?>">
                                            <?= htmlspecialchars($dish['nom']) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        
                        <!-- DESSERTS -->
                        <div class="col-md-4 mb-3">
                            <h5 class="text-warning">🍰 Desserts</h5>
                            <?php if (empty($dishesByType['dessert'])): ?>
                                <p class="text-muted small">Aucun dessert disponible</p>
                            <?php else: ?>
                                <?php foreach ($dishesByType['dessert'] as $dish): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="dishes[]" 
                                               value="<?= $dish['id'] ?>" id="dish_<?= $dish['id'] ?>">
                                        <label class="form-check-label" for="dish_<?= $dish['id'] ?>">
                                            <?= htmlspecialchars($dish['nom']) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <button type="submit" class="btn btn-primary btn-lg">✅ Ajouter le menu</button>
                    <a href="menus.php" class="btn btn-secondary btn-lg">📋 Voir tous les menus</a>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>