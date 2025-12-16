<?php
// pages/register.php
// Page d'inscription

require_once '../config/database.php';

$pageTitle = "Inscription - Vite & Gourmand";
$errors = [];
$success = false;

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $telephone = htmlspecialchars(trim($_POST['telephone']));
    $adresse_postale = htmlspecialchars(trim($_POST['adresse_postale']));
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    
    // Validation
    if (empty($nom)) $errors[] = "Le nom est obligatoire.";
    if (empty($prenom)) $errors[] = "Le prénom est obligatoire.";
    if (empty($email)) $errors[] = "L'email est obligatoire.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide.";
    if (empty($telephone)) $errors[] = "Le téléphone est obligatoire.";
    if (empty($adresse_postale)) $errors[] = "L'adresse postale est obligatoire.";
    
    // Validation du mot de passe (10 caractères min, 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial)
    if (strlen($password) < 10) {
        $errors[] = "Le mot de passe doit contenir au moins 10 caractères.";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Le mot de passe doit contenir au moins une majuscule.";
    }
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = "Le mot de passe doit contenir au moins une minuscule.";
    }
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Le mot de passe doit contenir au moins un chiffre.";
    }
    if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
        $errors[] = "Le mot de passe doit contenir au moins un caractère spécial.";
    }
    
    if ($password !== $password_confirm) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }
    
    // Si pas d'erreurs, créer le compte
    if (empty($errors)) {
        try {
            $pdo = getConnection();
            
            // Vérifier si l'email existe déjà
            $sqlCheck = "SELECT id FROM users WHERE email = :email";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->execute(['email' => $email]);
            
            if ($stmtCheck->fetch()) {
                $errors[] = "Cet email est déjà utilisé.";
            } else {
                // Hasher le mot de passe
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                
                // Insérer l'utilisateur
                $sql = "INSERT INTO users (nom, prenom, email, telephone, adresse_postale, password, role, compte_actif) 
                        VALUES (:nom, :prenom, :email, :telephone, :adresse_postale, :password, 'utilisateur', 1)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $email,
                    'telephone' => $telephone,
                    'adresse_postale' => $adresse_postale,
                    'password' => $password_hash
                ]);
                
                // Envoyer un email de bienvenue (simulé)
                // mail($email, "Bienvenue chez Vite & Gourmand", "Merci pour votre inscription !");
                
                $success = true;
            }
        } catch (PDOException $e) {
            $errors[] = "Erreur : " . $e->getMessage();
        }
    }
}

include '../includes/header.php';
?>

<!-- Section Header -->
<section class="bg-primary text-white py-5">
    <div class="container text-center">
        <h1 class="display-4 fw-bold">📝 Inscription</h1>
        <p class="lead">Créez votre compte pour commander nos menus</p>
    </div>
</section>

<!-- Section Formulaire -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                ✅ <strong>Compte créé avec succès !</strong><br>
                                Vous pouvez maintenant vous <a href="login.php" class="alert-link">connecter</a>.
                            </div>
                        <?php else: ?>
                            <?php if (!empty($errors)): ?>
                                <div class="alert alert-danger">
                                    <strong>❌ Erreurs :</strong>
                                    <ul class="mb-0 mt-2">
                                        <?php foreach ($errors as $error): ?>
                                            <li><?= $error ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            
                            <form method="POST">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nom" class="form-label">Nom *</label>
                                        <input type="text" class="form-control" id="nom" name="nom" required
                                               value="<?= isset($nom) ? $nom : '' ?>">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="prenom" class="form-label">Prénom *</label>
                                        <input type="text" class="form-control" id="prenom" name="prenom" required
                                               value="<?= isset($prenom) ? $prenom : '' ?>">
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" required
                                           value="<?= isset($email) ? $email : '' ?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="telephone" class="form-label">Téléphone *</label>
                                    <input type="tel" class="form-control" id="telephone" name="telephone" required
                                           placeholder="Ex: 0612345678"
                                           value="<?= isset($telephone) ? $telephone : '' ?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="adresse_postale" class="form-label">Adresse postale *</label>
                                    <textarea class="form-control" id="adresse_postale" name="adresse_postale" rows="2" required><?= isset($adresse_postale) ? $adresse_postale : '' ?></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password" class="form-label">Mot de passe *</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <small class="text-muted">
                                        Minimum 10 caractères : 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial
                                    </small>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password_confirm" class="form-label">Confirmer le mot de passe *</label>
                                    <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                                    ✅ Créer mon compte
                                </button>
                                
                                <div class="text-center">
                                    <p class="mb-0">
                                        Vous avez déjà un compte ? 
                                        <a href="login.php">Connectez-vous ici</a>
                                    </p>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>