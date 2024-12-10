<?php
require_once '../../lib/db.php';
require_once '../../lib/auth.php';
require_once '../../lib/upload.php';

requireAdmin();

$db = new Database();
$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Handle image upload
        $image_url = '';
        if (isset($_FILES['fighter_image']) && $_FILES['fighter_image']['error'] === 0) {
            $image_url = uploadImage($_FILES['fighter_image'], '../../data/fighters');
        }

        // Insert fighter data
        $db->query(
            "INSERT INTO fighters (name, weight_class, record, bio, image_url) VALUES (?, ?, ?, ?, ?)",
            [
                $_POST['name'],
                $_POST['weight_class'],
                $_POST['record'],
                $_POST['bio'],
                $image_url
            ]
        );

        $success = "Fighter added successfully!";
    } catch (Exception $e) {
        $error = "Error creating fighter: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Fighter - Admin - UFC Blog</title>
    <link rel="stylesheet" href="/ufc_blog/theme/style.css">
</head>
<body>
    <?php include '../../theme/header.php'; ?>
    <main>
        <div class="container">
            <h1>Add New Fighter</h1>

            <?php if ($error): ?>
                <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="success-message"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="form">
                <div class="form-group">
                    <label>Name:
                        <input type="text" name="name" required>
                    </label>
                </div>

                <div class="form-group">
                    <label>Weight Class:
                        <select name="weight_class" required>
                            <option value="Flyweight">Flyweight</option>
                            <option value="Bantamweight">Bantamweight</option>
                            <option value="Featherweight">Featherweight</option>
                            <option value="Lightweight">Lightweight</option>
                            <option value="Welterweight">Welterweight</option>
                            <option value="Middleweight">Middleweight</option>
                            <option value="Light Heavyweight">Light Heavyweight</option>
                            <option value="Heavyweight">Heavyweight</option>
                        </select>
                    </label>
                </div>

                <div class="form-group">
                    <label>Record:
                        <input type="text" name="record" placeholder="0-0-0" required>
                    </label>
                </div>

                <div class="form-group">
                    <label>Biography:
                        <textarea name="bio" required rows="6"></textarea>
                    </label>
                </div>

                <div class="form-group">
                    <label>Fighter Image:
                        <input type="file" name="fighter_image" accept="image/*">
                    </label>
                </div>

                <div id="image-preview" class="image-preview"></div>

                <div class="button-group">
                    <button type="submit" class="button">Add Fighter</button>
                    <a href="/ufc_blog/fighters/" class="button">Cancel</a>
                </div>
            </form>
        </div>
    </main>
    <?php include '../../theme/footer.php'; ?>
    
    <script>
        document.querySelector('input[type="file"]').addEventListener('change', function(e) {
            const preview = document.getElementById('image-preview');
            const file = e.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" style="max-width: 300px;">`;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>