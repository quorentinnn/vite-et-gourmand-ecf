<!-- includes/header.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Vite & Gourmand - Traiteur à Bordeaux' ?></title>
    <meta name="description" content="Vite & Gourmand - Traiteur professionnel à Bordeaux. Découvrez nos menus pour tous vos événements.">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS (optionnel pour plus tard) -->
    <style>
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 0;
        }
    </style>
</head>
<body>
    <!-- Navbar publique -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="/vite-et-gourmand/index.php">
                🍽️ Vite & Gourmand
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPublic">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarPublic">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="/vite-et-gourmand/index.php">🏠 Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/vite-et-gourmand/pages/menus.php">📋 Nos Menus</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/vite-et-gourmand/pages/contact.php">📧 Contact</a>
                    </li>
                    <?php
// Démarrer la session si pas déjà fait
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<?php if (isset($_SESSION['user_id'])): ?>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle btn btn-primary text-white ms-2" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
            👤 <?= $_SESSION['user_prenom'] ?>
        </a>
        <ul class="dropdown-menu">
            <?php if ($_SESSION['role'] === 'administrateur'): ?>
                <li><a class="dropdown-item" href="/vite-et-gourmand/admin/menus.php">🛠️ Administration</a></li>
            <?php elseif ($_SESSION['role'] === 'employe'): ?>
                <li><a class="dropdown-item" href="/vite-et-gourmand/employee/index.php">💼 Espace employé</a></li>
            <?php else: ?>
                <li><a class="dropdown-item" href="/vite-et-gourmand/user/index.php">🏠 Mon espace</a></li>
            <?php endif; ?>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="/vite-et-gourmand/pages/logout.php">🚪 Déconnexion</a></li>
        </ul>
    </li>
<?php else: ?>
    <li class="nav-item">
        <a class="nav-link btn btn-primary text-white ms-2" href="/vite-et-gourmand/pages/login.php">
            🔐 Connexion
        </a>
    </li>
<?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>