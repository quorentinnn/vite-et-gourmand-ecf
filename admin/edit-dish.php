<?php
// admin/edit-dish.php
// Page pour modifier un plat

require_once '../config/database.php';

// Récupérer l'ID du plat
if (!isset($_GET['id'])) {
    header('Location: dishes.php');
    exit;
}

$id = (int)$_GET['id'];

// Récupérer les informations du plat
try {
    $pdo = getConnection();
    $sql = "SELECT * FROM dishes WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $dish = $stmt->fetch();
    
    if (!$dish) {
        header('Location: dishes.php?error=Plat introuvable');
        exit;
    }
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars($_POST['nom']);
    $type_plat = htmlspecialchars($_POST['type_plat']);
    $image = $dish['image']; // Garder l'ancienne image par défaut
    
    // Gestion de la nouvelle image si uploadée
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($extension, $allowed)) {
            // Supprimer l'ancienne image
            if ($dish['image']) {
                $oldImagePath = '../assets/images/dishes/' . $dish['image'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            // Upload la nouvelle image
            $newname = uniqid() . '.' . $extension;
            $destination = '../assets/images/dishes/' . $newname;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                $image = $newname;
            }
        }
    }
    
    // Mise à jour dans la base de données
    try {
        $sql = "UPDATE dishes SET nom = :nom, type_plat = :type_plat, image = :image WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nom' => $nom,
            'type_plat' => $type_plat,
            'image' => $image,
            'id' => $id
        ]);
        
        header('Location: dishes.php?success=modifie');
        exit;
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
    <title>Modifier un plat - Vite & Gourmand</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">✏️ Modifier un plat</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom du plat *</label>
                        <input type="text" class="form-control" id="nom" name="nom" 
                               value="<?= htmlspecialchars($dish['nom']) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="type_plat" class="form-label">Type de plat *</label>
                        <select class="form-select" id="type_plat" name="type_plat" required>
                            <option value="entree" <?= $dish['type_plat'] === 'entree' ? 'selected' : '' ?>>Entrée</option>
                            <option value="plat" <?= $dish['type_plat'] === 'plat' ? 'selected' : '' ?>>Plat</option>
                            <option value="dessert" <?= $dish['type_plat'] === 'dessert' ? 'selected' : '' ?>>Dessert</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Image actuelle</label>
                        <div>
                            <?php if ($dish['image']): ?>
                                <img src="../assets/images/dishes/<?= htmlspecialchars($dish['image']) ?>" 
                                     alt="<?= htmlspecialchars($dish['nom']) ?>" 
                                     style="width: 150px; height: 150px; object-fit: cover; border-radius: 10px;">
                            <?php else: ?>
                                <p class="text-muted">Aucune image</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="image" class="form-label">Changer l'image (optionnel)</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <small class="text-muted">Formats acceptés : JPG, JPEG, PNG, GIF</small>
                    </div>
                    
                    <button type="submit" class="btn btn-success">✅ Enregistrer les modifications</button>
                    <a href="dishes.php" class="btn btn-secondary">❌ Annuler</a>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>