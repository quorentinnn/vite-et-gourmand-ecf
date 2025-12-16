<?php
// admin/add-dish.php
// Page pour ajouter un plat

require_once '../config/database.php';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars($_POST['nom']);
    $type_plat = htmlspecialchars($_POST['type_plat']);
    
    // Gestion de l'upload d'image
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($extension, $allowed)) {
            $newname = uniqid() . '.' . $extension;
            $destination = '../assets/images/dishes/' . $newname;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                $image = $newname;
            }
        }
    }
    
    // Insertion dans la base de données
    try {
        $pdo = getConnection();
        $sql = "INSERT INTO dishes (nom, type_plat, image) VALUES (:nom, :type_plat, :image)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nom' => $nom,
            'type_plat' => $type_plat,
            'image' => $image
        ]);
        
        $success = "✅ Plat ajouté avec succès !";
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
    <title>Ajouter un plat - Vite & Gourmand</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/admin-nav.php'; ?>
    <div class="container mt-5">
        <h1 class="mb-4">🍽️ Ajouter un plat</h1>
        
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom du plat *</label>
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="type_plat" class="form-label">Type de plat *</label>
                        <select class="form-select" id="type_plat" name="type_plat" required>
                            <option value="">-- Choisir --</option>
                            <option value="entree">Entrée</option>
                            <option value="plat">Plat</option>
                            <option value="dessert">Dessert</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="image" class="form-label">Image du plat</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <small class="text-muted">Formats acceptés : JPG, JPEG, PNG, GIF</small>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">✅ Ajouter le plat</button>
                    <a href="dishes.php" class="btn btn-secondary">📋 Voir tous les plats</a>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>