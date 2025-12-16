<?php
// pages/menus.php
// Liste publique des menus

require_once '../config/database.php';

$pageTitle = "Nos Menus - Vite & Gourmand";

try {
    $pdo = getConnection();
    
    // Récupérer tous les menus avec leurs infos
    $sql = "SELECT m.*, t.nom as theme_nom, r.nom as regime_nom 
            FROM menus m
            JOIN themes t ON m.theme_id = t.id
            JOIN regimes r ON m.regime_id = r.id
            WHERE m.stock_disponible > 0
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

include '../includes/header.php';
?>

<!-- Section Header -->
<section class="bg-primary text-white py-5">
    <div class="container text-center">
        <h1 class="display-4 fw-bold">📋 Nos Menus</h1>
        <p class="lead">Découvrez notre sélection de menus pour tous vos événements</p>
    </div>
</section>

<!-- Section Filtres (on les fera fonctionner avec JavaScript plus tard) -->
<section class="bg-light py-4">
    <div class="container">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-bold">Prix maximum</label>
                <input type="range" class="form-range" id="prixMax" min="0" max="100" value="100">
                <small class="text-muted"><span id="prixMaxValue">100</span> €</small>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Thème</label>
                <select class="form-select" id="filtreTheme">
                    <option value="">Tous les thèmes</option>
                    <option value="noel">Noël</option>
                    <option value="paques">Pâques</option>
                    <option value="ete">Été</option>
                    <option value="classique">Classique</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Régime</label>
                <select class="form-select" id="filtreRegime">
                    <option value="">Tous les régimes</option>
                    <option value="vegetarien">Végétarien</option>
                    <option value="vegan">Vegan</option>
                    <option value="sans-gluten">Sans gluten</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Personnes min</label>
                <input type="number" class="form-control" id="filtrePersonnes" placeholder="Ex: 6" min="1">
            </div>
        </div>
    </div>
</section>

<!-- Section Liste des menus -->
<section class="py-5">
    <div class="container">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <?php if (empty($menus)): ?>
            <div class="alert alert-info text-center">
                <h4>Aucun menu disponible pour le moment</h4>
                <p>Revenez bientôt pour découvrir nos nouveaux menus !</p>
            </div>
        <?php else: ?>
            <div class="row" id="menusList">
                <?php foreach ($menus as $menu): ?>
                    <div class="col-md-6 col-lg-4 mb-4 menu-card" 
                         data-prix="<?= $menu['prix_base'] ?>"
                         data-theme="<?= strtolower($menu['theme_nom']) ?>"
                         data-regime="<?= strtolower($menu['regime_nom']) ?>"
                         data-personnes="<?= $menu['nombre_personnes_min'] ?>">
                        <div class="card h-100 shadow-sm hover-shadow">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($menu['titre']) ?></h5>
                                <p class="card-text text-muted small">
                                    <?= htmlspecialchars(substr($menu['description'], 0, 100)) ?>...
                                </p>
                                
                                <div class="mb-3">
                                    <span class="badge bg-info"><?= htmlspecialchars($menu['theme_nom']) ?></span>
                                    <span class="badge bg-success"><?= htmlspecialchars($menu['regime_nom']) ?></span>
                                </div>
                                
                                <ul class="list-unstyled small mb-3">
                                    <li><strong>👥</strong> À partir de <?= $menu['nombre_personnes_min'] ?> personnes</li>
                                    <li><strong>💰</strong> <?= number_format($menu['prix_base'], 2) ?> € / personne</li>
                                    <li><strong>📦</strong> <?= $menu['stock_disponible'] ?> menus disponibles</li>
                                </ul>
                                
                                <?php if (!empty($menu['plats'])): ?>
                                    <div class="border-top pt-3 mb-3">
                                        <strong class="small">🍴 Composition :</strong>
                                        <ul class="small mb-0 mt-2">
                                            <?php 
                                            $count = 0;
                                            foreach ($menu['plats'] as $plat): 
                                                if ($count < 3):
                                            ?>
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
                                            <?php 
                                                $count++;
                                                endif;
                                            endforeach; 
                                            if (count($menu['plats']) > 3):
                                            ?>
                                                <li class="text-muted">... et <?= count($menu['plats']) - 3 ?> autre(s) plat(s)</li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-footer bg-white">
                                <a href="menu-detail.php?id=<?= $menu['id'] ?>" class="btn btn-primary w-100">
                                    👁️ Voir le détail
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.hover-shadow {
    transition: transform 0.3s, box-shadow 0.3s;
}
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.2) !important;
}
</style>
<script>
// ========================================
// FILTRES DYNAMIQUES - JavaScript
// ========================================

// 1. RÉCUPÉRER TOUS LES ÉLÉMENTS HTML dont on a besoin
const prixMax = document.getElementById('prixMax');           // Le slider de prix
const prixMaxValue = document.getElementById('prixMaxValue'); // L'affichage de la valeur
const filtreTheme = document.getElementById('filtreTheme');   // Le select des thèmes
const filtreRegime = document.getElementById('filtreRegime'); // Le select des régimes
const filtrePersonnes = document.getElementById('filtrePersonnes'); // L'input du nb de personnes
const menuCards = document.querySelectorAll('.menu-card');    // TOUTES les cartes de menu

// 2. FONCTION PRINCIPALE qui filtre les menus
function filtrerMenus() {
    // Récupérer les valeurs actuelles des filtres
    const prixMaximum = parseFloat(prixMax.value);
    const themeChoisi = filtreTheme.value.toLowerCase();
    const regimeChoisi = filtreRegime.value.toLowerCase();
    const personnesMin = filtrePersonnes.value ? parseInt(filtrePersonnes.value) : 0;
    
    // Pour CHAQUE carte de menu
    menuCards.forEach(card => {
        // Récupérer les données de la carte (qu'on a mises dans les attributs data-*)
        const prixMenu = parseFloat(card.getAttribute('data-prix'));
        const themeMenu = card.getAttribute('data-theme').toLowerCase();
        const regimeMenu = card.getAttribute('data-regime').toLowerCase();
        const personnesMenu = parseInt(card.getAttribute('data-personnes'));
        
        // VÉRIFIER si le menu correspond aux filtres
        let afficher = true; // Par défaut on affiche
        
        // Filtre 1 : Prix
        if (prixMenu > prixMaximum) {
            afficher = false; // Si le prix est trop élevé, on cache
        }
        
        // Filtre 2 : Thème
        if (themeChoisi !== '' && themeMenu !== themeChoisi) {
            afficher = false; // Si le thème ne correspond pas, on cache
        }
        
        // Filtre 3 : Régime
        if (regimeChoisi !== '' && regimeMenu !== regimeChoisi) {
            afficher = false; // Si le régime ne correspond pas, on cache
        }
        
        // Filtre 4 : Nombre de personnes
        if (personnesMin > 0 && personnesMenu > personnesMin) {
            afficher = false; // Si le menu demande trop de personnes, on cache
        }
        
        // AFFICHER ou CACHER la carte
        if (afficher) {
            card.style.display = 'block'; // On montre
        } else {
            card.style.display = 'none';  // On cache
        }
    });
}

// 3. METTRE À JOUR l'affichage du prix quand on bouge le slider
prixMax.addEventListener('input', function() {
    prixMaxValue.textContent = this.value; // Afficher la valeur
    filtrerMenus(); // Relancer le filtrage
});

// 4. ÉCOUTER les changements sur les autres filtres
filtreTheme.addEventListener('change', filtrerMenus);
filtreRegime.addEventListener('change', filtrerMenus);
filtrePersonnes.addEventListener('input', filtrerMenus);

// 5. Au chargement de la page, tout afficher
window.addEventListener('load', function() {
    console.log('✅ Filtres JavaScript chargés !');
});
</script>



<?php include '../includes/footer.php'; ?>