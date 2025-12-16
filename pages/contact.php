<?php
// pages/contact.php
// Page de contact

require_once '../config/database.php';

$pageTitle = "Contact - Vite & Gourmand";
$success = false;
$error = false;

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = htmlspecialchars($_POST['titre']);
    $description = htmlspecialchars($_POST['description']);
    $email = htmlspecialchars($_POST['email']);
    
    // Validation
    if (empty($titre) || empty($description) || empty($email)) {
        $error = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email invalide.";
    } else {
        // Envoyer l'email (simulé pour l'instant)
        // Dans un vrai projet, tu utiliserais mail() ou PHPMailer
        
        // Pour l'instant, on simule juste l'envoi
        $to = "contact@vitegourmand.fr";
        $subject = "Contact : " . $titre;
        $message = "Email de : " . $email . "\n\n" . $description;
        $headers = "From: " . $email;
        
        // En production, décommente cette ligne :
        // mail($to, $subject, $message, $headers);
        
        $success = true;
    }
}

include '../includes/header.php';
?>

<!-- Section Header -->
<section class="bg-primary text-white py-5">
    <div class="container text-center">
        <h1 class="display-4 fw-bold">📧 Contactez-nous</h1>
        <p class="lead">Une question ? Un projet ? N'hésitez pas à nous écrire !</p>
    </div>
</section>

<!-- Section Formulaire -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Colonne gauche : Formulaire -->
            <div class="col-md-8 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="mb-4">📝 Envoyez-nous un message</h3>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                ✅ Votre message a bien été envoyé ! Nous vous répondrons dans les plus brefs délais.
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger">
                                ❌ <?= $error ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label for="titre" class="form-label">Objet de votre message *</label>
                                <input type="text" class="form-control" id="titre" name="titre" required
                                       placeholder="Ex: Demande de devis pour un mariage">
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Votre email *</label>
                                <input type="email" class="form-control" id="email" name="email" required
                                       placeholder="votre.email@exemple.fr">
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Votre message *</label>
                                <textarea class="form-control" id="description" name="description" rows="6" required
                                          placeholder="Décrivez-nous votre projet ou posez-nous vos questions..."></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                📧 Envoyer le message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Colonne droite : Informations -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title">📍 Nos coordonnées</h5>
                        <ul class="list-unstyled mt-3">
                            <li class="mb-2">
                                <strong>📧 Email :</strong><br>
                                contact@vitegourmand.fr
                            </li>
                            <li class="mb-2">
                                <strong>📞 Téléphone :</strong><br>
                                05 56 12 34 56
                            </li>
                            <li class="mb-2">
                                <strong>📍 Adresse :</strong><br>
                                15 Rue des Vignes<br>
                                33000 Bordeaux
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">⏰ Horaires</h5>
                        <ul class="list-unstyled mt-3">
                            <li>Lundi - Vendredi : 9h - 18h</li>
                            <li>Samedi : 9h - 14h</li>
                            <li>Dimanche : Fermé</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>