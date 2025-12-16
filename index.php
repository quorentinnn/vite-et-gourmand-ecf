<?php
// index.php
// Page d'accueil publique

$pageTitle = "Accueil - Vite & Gourmand";
include 'includes/header.php';
?>

<!-- Section Hero -->
<section class="hero-section text-center">
    <div class="container">
        <h1 class="display-3 fw-bold mb-4">🍽️ Vite & Gourmand</h1>
        <p class="lead mb-4">Votre traiteur de confiance à Bordeaux depuis 25 ans</p>
        <a href="/vite-et-gourmand/pages/menus.php" class="btn btn-light btn-lg">
            📋 Découvrir nos menus
        </a>
    </div>
</section>

<!-- Section Présentation -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4">
                <h2 class="mb-4">Qui sommes-nous ?</h2>
                <p class="lead">
                    Julie et José vous accueillent depuis 1999 pour préparer tous vos événements : 
                    mariages, anniversaires, événements d'entreprise, réceptions...
                </p>
                <p>
                    Notre passion : vous offrir des plats savoureux préparés avec des produits frais 
                    et locaux. Nous nous adaptons à tous vos besoins : menus végétariens, vegan, 
                    sans gluten, halal...
                </p>
            </div>
            <div class="col-md-6 mb-4">
                <img src="https://images.unsplash.com/photo-1555244162-803834f70033?w=600&h=400&fit=crop" 
                     alt="Équipe Vite & Gourmand" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</section>

<!-- Section Nos atouts -->
<section class="bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-5">Pourquoi nous choisir ?</h2>
        <div class="row">
            <div class="col-md-4 text-center mb-4">
                <div class="display-1 mb-3">🎖️</div>
                <h4>25 ans d'expérience</h4>
                <p class="text-muted">Un savoir-faire reconnu à Bordeaux</p>
            </div>
            <div class="col-md-4 text-center mb-4">
                <div class="display-1 mb-3">🌱</div>
                <h4>Produits frais & locaux</h4>
                <p class="text-muted">Des ingrédients de qualité sélectionnés</p>
            </div>
            <div class="col-md-4 text-center mb-4">
                <div class="display-1 mb-3">🎯</div>
                <h4>Sur mesure</h4>
                <p class="text-muted">Nous adaptons nos menus à vos besoins</p>
            </div>
        </div>
    </div>
</section>

<!-- Section Avis clients (vide pour l'instant) -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Ce que disent nos clients</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="mb-2">⭐⭐⭐⭐⭐</div>
                        <p class="card-text">"Excellent service pour notre mariage ! Les plats étaient délicieux."</p>
                        <p class="text-muted small mb-0">- Marie B.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="mb-2">⭐⭐⭐⭐⭐</div>
                        <p class="card-text">"Parfait pour notre événement d'entreprise. Je recommande !"</p>
                        <p class="text-muted small mb-0">- Pierre D.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="mb-2">⭐⭐⭐⭐⭐</div>
                        <p class="card-text">"Équipe professionnelle et à l'écoute. Merci Julie et José !"</p>
                        <p class="text-muted small mb-0">- Sophie M.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>