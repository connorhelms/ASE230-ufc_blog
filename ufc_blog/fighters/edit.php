<?php
require_once '../lib/db.php';
require_once '../lib/auth.php';
require_once '../lib/upload.php';

requireAdmin();
$db = new Database();

$fighter = $db->query(
    "SELECT * FROM fighters WHERE fighter_id = ?",
    [$_GET['id']]
)->fetch(PDO::FETCH_ASSOC);

if (!$fighter) {
    header('Location: /fighters/');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $image_url = $fighter['image_url'];
    
    if (isset($_FILES['fighter_image']) && $_FILES['fighter_image']['error'] === 0) {
        if ($fighter['image_url']) {
            unlink(".." . $fighter['image_url']);
        }
        $image_url = uploadImage($_FILES['fighter_image'], '../data/fighters');
    }
    
    $db->query(
        "UPDATE fighters SET 
         name = ?, weight_class = ?, record = ?, bio = ?, image_url = ? 
         WHERE fighter_id = ?",
        [
            $_POST['name'],
            $_POST['weight_class'],
            $_POST['record'],
            $_POST['bio'],
            $image_url,
            $_GET['id']
        ]
    );
    
    header('Location: /fighters/detail.php?id=' . $_GET['id']);
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit <?= htmlspecialchars($fighter['name']) ?> - UFC Blog</title>
</head>
<body>
    <?php include '../theme/header.php'; ?>
    <main>
        <h1>Edit Fighter Profile</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Name:
                    <input type="text" name="name" 
                           value="<?= htmlspecialchars($fighter['name']) ?>" required>
                </label>
            </div>
            <div class="form-group">
                <label>Weight Class:
                    <select name="weight_class" required>
                        <option value="Flyweight" <?= $fighter['weight_class'] === 'Flyweight' ? 'selected' : '' ?>>
                            Flyweight
                        </option>
                        <option value="Bantamweight" <?= $fighter['weight_class'] === 'Bantamweight' ? 'selected' : '' ?>>
                            Bantamweight
                        </option>
                        <option value="Featherweight" <?= $fighter['weight_class'] === 'Featherweight' ? 'selected' : '' ?>>
                            Featherweight
                        </option>
                        <option value="Lightweight" <?= $fighter['weight_class'] === 'Lightweight' ? 'selected' : '' ?>>
                            Lightweight
                        </option>
                        <option value="Welterweight" <?= $fighter['weight_class'] === 'Welterweight' ? 'selected' : '' ?>>
                            Welterweight
                        </option>
                        <option value="Middleweight" <?= $fighter['weight_class'] === 'Middleweight' ? 'selected' : '' ?>>
                            Middleweight
                        </option>
                        <option value="Light Heavyweight" <?= $fighter['weight_class'] === 'Light Heavyweight' ? 'selected' : '' ?>>
                            Light Heavyweight
                        </option>
                        <option value="Heavyweight" <?= $fighter['weight_class'] === 'Heavyweight' ? 'selected' : '' ?>>
                            Heavyweight
                        </option>
                    </select>
                </label>
            </div>
            <div class="form-group">
                <label>Record:
                    <input type="text" name="record" 
                           value="<?= htmlspecialchars($fighter['record']) ?>" required>
                </label>
            </div>
            <div class="form-group">
                <label>Biography:
                    <textarea name="bio" required rows="6"><?= htmlspecialchars($fighter['bio']) ?></textarea>
                </label>
            </div>
            <div class="form-group">
                <?php if ($fighter['image_url']): ?>
                    <div class="current-image">
                        <p>Current Image:</p>
                        <img src="<?= htmlspecialchars($fighter['image_url']) ?>" 
                             alt="<?= htmlspecialchars($fighter['name']) ?>" style="max-width: 200px">
                    </div>
                <?php endif; ?>
                <label>Update Image:
                    <input type="file" name="fighter_image" accept="image/*">
                </label>
            </div>
            <button type="submit" class="button">Update Fighter</button>
        </form>
    </main>
    <?php include '../theme/footer.php'; ?>
</body>
</html>
