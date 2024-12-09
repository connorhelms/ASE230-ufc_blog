<?php
require_once '../../lib/db.php';
require_once '../../lib/auth.php';
require_once '../../lib/upload.php';

requireAdmin();
$db = new Database();

$fighter = $db->query(
    "SELECT * FROM fighters WHERE fighter_id = ?",
    [$_GET['id']]
)->fetch(PDO::FETCH_ASSOC);

if (!$fighter) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $image_url = $fighter['image_url'];
        
        if (isset($_FILES['fighter_image']) && $_FILES['fighter_image']['error'] === 0) {
            if ($fighter['image_url']) {
                unlink("../../" . $fighter['image_url']);
            }
            $image_url = uploadImage($_FILES['fighter_image'], '../../data/fighters');
        }
        
        $db->query(
            "UPDATE fighters SET name = ?, weight_class = ?, record = ?, bio = ?, image_url = ? 
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
        
        header('Location: index.php');
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Fighter - Admin - UFC Blog</title>
    <link rel="stylesheet" href="/ufc_blog/theme/style.css">
</head>
<body>
    <?php include '../../theme/header.php'; ?>
    <main>
        <div class="container">
            <h1>Edit Fighter</h1>
            
            <?php if (isset($error)): ?>
                <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Name:
                        <input type="text" name="name" value="<?= htmlspecialchars($fighter['name']) ?>" required>
                    </label>
                </div>
                
                <div class="form-group">
                    <label>Weight Class:
                        <select name="weight_class" required>
                            <?php
                            $weight_classes = [
                                'Flyweight', 'Bantamweight', 'Featherweight', 'Lightweight',
                                'Welterweight', 'Middleweight', 'Light Heavyweight', 'Heavyweight'
                            ];
                            foreach ($weight_classes as $class) {
                                $selected = ($fighter['weight_class'] === $class) ? 'selected' : '';
                                echo "<option value=\"$class\" $selected>$class</option>";
                            }
                            ?>
                        </select>
                    </label>
                </div>
                
                <div class="form-group">
                    <label>Record:
                        <input type="text" name="record" value="<?= htmlspecialchars($fighter['record']) ?>" required>
                    </label>
                </div>
                
                <div class="form-group">
                    <label>Biography:
                        <textarea name="bio" required rows="6"><?= htmlspecialchars($fighter['bio']) ?></textarea>
                    </label>
                </div>
                
                <?php if ($fighter['image_url']): ?>
                    <div class="current-image">
                        <p>Current Image:</p>
                        <img src="<?= htmlspecialchars($fighter['image_url']) ?>" alt="Fighter" style="max-width: 200px">
                    </div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label>Update Image:
                        <input type="file" name="fighter_image" accept="image/*">
                    </label>
                </div>
                
                <div class="button-group">
                    <button type="submit" class="button">Update Fighter</button>
                    <a href="index.php" class="button">Cancel</a>
                </div>
            </form>
        </div>
    </main>
    <?php include '../../theme/footer.php'; ?>
</body>
</html>