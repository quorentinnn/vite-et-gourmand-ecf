<?php
// user/index.php
// Dashboard utilisateur

session_start();

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'utilisateur') {
    header('Location: ../pages/login.php');
    exit;
}

$pageTitle = "Mon espace - Vite & Gourmand";
include '../includes/header.php';
?>

<!-- Section Header -->
<section class="bg-primary text-white py-5">
    <div class="container text-center">
        <h1 class="display-4 fw-bold">👋 Bienvenue <?= $_SESSION['user_prenom'] ?> !</h1>
        <p class="lead">Votre espace personnel</p>
    </div>
</section>

<!-- Section Dashboard -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <div class="display-1 mb-3">👤</div>
                        <h5 class="card-title">Mon profil</h5>
                        <p class="card-text text-muted">Gérer mes informations personnelles</p>
                        <a href="profile.php" class="btn btn-primary">Accéder</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <div class="display-1 mb-3">🛒</div>
                        <h5 class="card-title">Mes commandes</h5>
                        <p class="card-text text-muted">Voir l'historique de mes commandes</p>
                        <a href="orders.php" class="btn btn-primary">Accéder</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <div class="display-1 mb-3">📋</div>
                        <h5 class="card-title">Nos menus</h5>
                        <p class="card-text text-muted">Découvrir et commander nos menus</p>
                        <a href="../pages/menus.php" class="btn btn-primary">Accéder</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Informations du compte -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title mb-4">📊 Informations de votre compte</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Nom :</strong> <?= $_SESSION['user_nom'] ?></p>
                                <p><strong>Prénom :</strong> <?= $_SESSION['user_prenom'] ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Email :</strong> <?= $_SESSION['user_email'] ?></p>
                                <p><strong>Rôle :</strong> <span class="badge bg-success">Utilisateur</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>