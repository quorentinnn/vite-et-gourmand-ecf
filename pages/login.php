<?php
// pages/login.php
// Page de connexion

session_start();
require_once '../config/database.php';

$pageTitle = "Connexion - Vite & Gourmand";
$error = false;

// Si déjà connecté, rediriger selon le rôle
if (isset($_SESSION['user_id'])) {
    switch ($_SESSION['role']) {
        case 'administrateur':
            header('Location: ../admin/menus.php');
            break;
        case 'employe':
            header('Location: ../employee/index.php');
            break;
        default:
            header('Location: ../user/index.php');
            break;
    }
    exit;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = "Tous les champs sont obligatoires.";
    } else {
        try {
            $pdo = getConnection();
            
            // Récupérer l'utilisateur
            $sql = "SELECT * FROM users WHERE email = :email AND compte_actif = 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();
            
            // Vérifier le mot de passe
            if ($user && password_verify($password, $user['password'])) {
                // Connexion réussie ! Créer la session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_nom'] = $user['nom'];
                $_SESSION['user_prenom'] = $user['prenom'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                
                // Rediriger selon le rôle
                switch ($user['role']) {
                    case 'administrateur':
                        header('Location: ../admin/menus.php');
                        break;
                    case 'employe':
                        header('Location: ../employee/index.php');
                        break;
                    default:
                        header('Location: ../user/index.php');
                        break;
                }
                exit;
            } else {
                $error = "Email ou mot de passe incorrect.";
            }
        } catch (PDOException $e) {
            $error = "Erreur : " . $e->getMessage();
        }
    }
}

include '../includes/header.php';
?>

<!-- Section Header -->
<section class="bg-primary text-white py-5">
    <div class="container text-center">
        <h1 class="display-4 fw-bold">🔐 Connexion</h1>
        <p class="lead">Connectez-vous à votre compte</p>
    </div>
</section>

<!-- Section Formulaire -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <?php if ($error): ?>
                            <div class="alert alert-danger">
                                ❌ <?= $error ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required
                                       placeholder="votre.email@exemple.fr"
                                       value="<?= isset($email) ? $email : '' ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe *</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            
                            <div class="mb-3">
                                <a href="forgot-password.php" class="text-muted small">Mot de passe oublié ?</a>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                                🔐 Se connecter
                            </button>
                            
                            <div class="text-center">
                                <p class="mb-0">
                                    Pas encore de compte ? 
                                    <a href="register.php">Inscrivez-vous ici</a>
                                </p>
                            </div>
                        </form>
                        
                        <!-- Identifiants de test -->
                        <div class="mt-4 p-3 bg-light rounded">
                            <small class="text-muted">
                                <strong>🧪 Comptes de test :</strong><br>
                                <strong>Admin :</strong> jose@vitegourmand.fr / Admin123!<br>
                                <strong>Employé :</strong> julie@vitegourmand.fr / Employe123!<br>
                                <strong>Utilisateur :</strong> test@example.com / Test1234!@
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>