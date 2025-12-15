<?php
// admin/menus.php
// Liste des menus avec leurs plats

require_once '../config/database.php';

try {
    $pdo = getConnection();
    
    // Récupérer tous les menus avec leurs infos
    $sql = "SELECT m.*, t.nom as theme_nom, r.nom as regime_nom 
            FROM menus m
            JOIN themes t ON m.theme_id = t.id
            JOIN regimes r ON m.regime_id = r.id
            ORDER BY m.created_at DESC";
    $stmt = $pdo->query($sql);
    $menus = $stmt->fetchAll();
    
    // Pour chaque menu, récupérer ses plats
    foreach ($menus as &$menu) {
        $sqlDishes = "SELECT d.nom, d.type_plat 
                      FROM dishes d
                      JOIN menus_dishes md ON d.id = md.dish_id
                      WHERE md.menu_id = :menu_id
                      ORDER BY d.type_plat";
        $stmtDishes = $pdo->prepare($sqlDishes);
        $stmtDishes->execute(['menu_id' => $menu['id']]);
        $menu['plats'] = $stmtDishes->fetchAll();
    }
    
} catch (PDOException $e) {
    $error = "❌ Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des menus - Vite & Gourmand</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>🍽️ Liste des menus</h1>
            <a href="add-menu.php" class="btn btn-primary">➕ Ajouter un menu</a>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <?php if (isset($_GET['success']) && $_GET['success'] === 'ajoute'): ?>
            <div class="alert alert-success">✅ Menu ajouté avec succès !</div>
        <?php endif; ?>
        
        <?php if (isset($_GET['success']) && $_GET['success'] === 'supprime'): ?>
            <div class="alert alert-success">✅ Menu supprimé avec succès !</div>
        <?php endif; ?>
        
        <?php if (isset($_GET['success']) && $_GET['success'] === 'modifie'): ?>
            <div class="alert alert-success">✅ Menu modifié avec succès !</div>
        <?php endif; ?>
        
        <?php if (empty($menus)): ?>
            <div class="alert alert-info">
                Aucun menu pour le moment. <a href="add-menu.php">Ajoutez-en un !</a>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($menus as $menu): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($menu['titre']) ?></h5>
                                <p class="card-text text-muted small"><?= htmlspecialchars(substr($menu['description'], 0, 80)) ?>...</p>
                                
                                <div class="mb-2">
                                    <span class="badge bg-info"><?= htmlspecialchars($menu['theme_nom']) ?></span>
                                    <span class="badge bg-success"><?= htmlspecialchars($menu['regime_nom']) ?></span>
                                </div>
                                
                                <ul class="list-unstyled small mb-3">
                                    <li><strong>👥 Min :</strong> <?= $menu['nombre_personnes_min'] ?> personnes</li>
                                    <li><strong>💰 Prix :</strong> <?= number_format($menu['prix_base'], 2) ?> €</li>
                                    <li><strong>📦 Stock :</strong> <?= $menu['stock_disponible'] ?></li>
                                </ul>
                                
                                <!-- AFFICHER LES PLATS -->
                                <?php if (!empty($menu['plats'])): ?>
                                    <div class="border-top pt-2">
                                        <strong class="small">🍴 Composition :</strong>
                                        <ul class="small mb-0 mt-1">
                                            <?php foreach ($menu['plats'] as $plat): ?>
                                                <li>
                                                    <?php
                                                    $icons = [
                                                        'entree' => '🥗',
                                                        'plat' => '🍖',
                                                        'dessert' => '🍰'
                                                    ];
                                                    echo $icons[$plat['type_plat']] . ' ';
                                                    ?>
                                                    <?= htmlspecialchars($plat['nom']) ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-warning small mb-0">
                                        ⚠️ Aucun plat associé
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-footer bg-white">
                                <a href="edit-menu.php?id=<?= $menu['id'] ?>" class="btn btn-sm btn-warning">✏️ Modifier</a>
                                <a href="delete-menu.php?id=<?= $menu['id'] ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce menu ?')">🗑️ Supprimer</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>