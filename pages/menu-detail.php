<?php
//DETAIL D'un menu 
// pages/menu-detail.php

require_once '../config/database.php';

try {
    $pdo = getConnection();

    if (isset($_GET['id'])) {
        $menuId = (int)$_GET['id'];

        // Récupérer les détails du menu
       $sql = "SELECT m.*, t.nom as theme_nom, r.nom as regime_nom 
        FROM menus m
        JOIN themes t ON m.theme_id = t.id
        JOIN regimes r ON m.regime_id = r.id
        WHERE m.id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $menuId]);
        $menu = $stmt->fetch();

        if (!$menu) {
            throw new Exception("Menu non trouvé.");
        }

        // Récupérer les plats associés au menu
        $sql = "SELECT d.* FROM dishes d
                JOIN menus_dishes md ON d.id = md.dish_id
                WHERE md.menu_id = :menu_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['menu_id' => $menuId]);
        $dishes = $stmt->fetchAll();

    } else {
        throw new Exception("ID de menu manquant.");
    }
} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}
$pageTitle = htmlspecialchars($menu['titre']) . " - Vite & Gourmand";
include '../includes/header.php';
?>

<!-- Section Header du menu -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <h1 class="display-4 fw-bold"><?= htmlspecialchars($menu['titre']) ?></h1>
        <p class="lead"><?= htmlspecialchars($menu['description']) ?></p>
        
        <div class="mt-3">
            <span class="badge bg-light text-dark fs-6 me-2">
                🎨 <?= htmlspecialchars($menu['theme_nom']) ?>
            </span>
            <span class="badge bg-light text-dark fs-6">
                🥗 <?= htmlspecialchars($menu['regime_nom']) ?>
            </span>
        </div>
    </div>
</section>

<!-- Section Détails du menu -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Colonne gauche : Informations -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title mb-4">📋 Informations</h3>
                        
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>👥 Nombre de personnes minimum :</strong>
                                <span class="badge bg-primary"><?= $menu['nombre_personnes_min'] ?> personnes</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>💰 Prix par personne :</strong>
                                <span class="badge bg-success"><?= number_format($menu['prix_base'], 2) ?> €</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>📦 Stock disponible :</strong>
                                <span class="badge bg-info"><?= $menu['stock_disponible'] ?> menus</span>
                            </li>
                        </ul>
                        
                        <?php if ($menu['conditions']): ?>
                            <div class="alert alert-warning mt-4">
                                <strong>⚠️ Conditions importantes :</strong>
                                <p class="mb-0 mt-2"><?= nl2br(htmlspecialchars($menu['conditions'])) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Colonne droite : Composition du menu -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title mb-4">🍴 Composition du menu</h3>
                        
                        <?php if (empty($dishes)): ?>
                            <div class="alert alert-info">
                                Aucun plat associé à ce menu pour le moment.
                            </div>
                        <?php else: ?>
                            <!-- Grouper les plats par type -->
                            <?php
                            $dishesByType = [
                                'entree' => [],
                                'plat' => [],
                                'dessert' => []
                            ];
                            
                            foreach ($dishes as $dish) {
                                $dishesByType[$dish['type_plat']][] = $dish;
                            }
                            ?>
                            
                            <!-- ENTRÉES -->
                            <?php if (!empty($dishesByType['entree'])): ?>
                                <div class="mb-4">
                                    <h5 class="text-success mb-3">🥗 Entrées</h5>
                                    <ul class="list-unstyled">
                                        <?php foreach ($dishesByType['entree'] as $dish): ?>
                                            <li class="mb-2">
                                                <i class="bi bi-check-circle-fill text-success"></i>
                                                <?= htmlspecialchars($dish['nom']) ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            
                            <!-- PLATS -->
                            <?php if (!empty($dishesByType['plat'])): ?>
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3">🍖 Plats</h5>
                                    <ul class="list-unstyled">
                                        <?php foreach ($dishesByType['plat'] as $dish): ?>
                                            <li class="mb-2">
                                                <i class="bi bi-check-circle-fill text-primary"></i>
                                                <?= htmlspecialchars($dish['nom']) ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            
                            <!-- DESSERTS -->
                            <?php if (!empty($dishesByType['dessert'])): ?>
                                <div class="mb-4">
                                    <h5 class="text-warning mb-3">🍰 Desserts</h5>
                                    <ul class="list-unstyled">
                                        <?php foreach ($dishesByType['dessert'] as $dish): ?>
                                            <li class="mb-2">
                                                <i class="bi bi-check-circle-fill text-warning"></i>
                                                <?= htmlspecialchars($dish['nom']) ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Boutons d'action -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="menus.php" class="btn btn-secondary btn-lg me-3">
                    ← Retour aux menus
                </a>
                <a href="#" class="btn btn-primary btn-lg">
                    🛒 Commander ce menu
                </a>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>