// fighters/create.php
<?php
require_once '../lib/db.php';
require_once '../lib/auth.php';
require_once '../lib/upload.php';

// Check if user is admin
requireAdmin();

$db = new Database();
$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Handle image upload
        $image_url = '';
        if (isset($_FILES['fighter_image']) && $_FILES['fighter_image']['error'] === 0) {
            $image_url = uploadImage($_FILES['fighter_image'], '../data/fighters');
        }

        // Insert fighter data
        $db->query(
            "INSERT INTO fighters (name, weight_class, record, bio, image_url) 
             VALUES (?, ?, ?, ?, ?)",
            [
                $_POST['name'],
                $_POST['weight_class'],
                $_POST['record'],
                $_POST['bio'],
                $image_url
            ]
        );

        $success = "Fighter added successfully!";
        // Optional: Redirect to fighter list
        // header('Location: /ufc_blog/fighters/');
        // exit();
    } catch (Exception $e) {
        $error = "Error creating fighter: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Fighter - UFC Blog</title>
    <link rel="stylesheet" href="/ufc_blog/theme/style.css">
</head>
<body>
    <?php include '../theme/header.php'; ?>
    
    <main>
        <div class="container">
            <h1>Add New Fighter</h1>

            <?php if ($error): ?>
                <div class="error-message">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="success-message">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="form-container">
                <div class="form-group">
                    <label for="name">Fighter Name:</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           required 
                           value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">
                </div>

                <div class="form-group">
                    <label for="weight_class">Weight Class:</label>
                    <select id="weight_class" name="weight_class" required>
                        <option value="">Select Weight Class</option>
                        <option value="Flyweight">Flyweight</option>
                        <option value="Bantamweight">Bantamweight</option>
                        <option value="Featherweight">Featherweight</option>
                        <option value="Lightweight">Lightweight</option>
                        <option value="Welterweight">Welterweight</option>
                        <option value="Middleweight">Middleweight</option>
                        <option value="Light Heavyweight">Light Heavyweight</option>
                        <option value="Heavyweight">Heavyweight</option>
                        <option value="Women's Strawweight">Women's Strawweight</option>
                        <option value="Women's Flyweight">Women's Flyweight</option>
                        <option value="Women's Bantamweight">Women's Bantamweight</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="record">Record (W-L-D):</label>
                    <input type="text" 
                           id="record" 
                           name="record" 
                           placeholder="0-0-0" 
                           required
                           pattern="\d+-\d+-\d+"
                           title="Format: Wins-Losses-Draws (e.g., 10-2-1)"
                           value="<?= isset($_POST['record']) ? htmlspecialchars($_POST['record']) : '' ?>">
                </div>

                <div class="form-group">
                    <label for="bio">Biography:</label>
                    <textarea id="bio" 
                              name="bio" 
                              required 
                              rows="6"><?= isset($_POST['bio']) ? htmlspecialchars($_POST['bio']) : '' ?></textarea>
                </div>

                <div class="form-group">
                    <label for="fighter_image">Fighter Image:</label>
                    <input type="file" 
                           id="fighter_image" 
                           name="fighter_image" 
                           accept="image/*">
                    <div class="file-help">
                        Accepted formats: JPG, PNG, GIF. Max size: 5MB
                    </div>
                </div>

                <div class="form-group">
                    <div id="image-preview" class="image-preview"></div>
                </div>

                <div class="button-group">
                    <button type="submit" class="button primary">Add Fighter</button>
                    <a href="/ufc_blog/fighters/" class="button secondary">Cancel</a>
                </div>
            </form>
        </div>
    </main>

    <?php include '../theme/footer.php'; ?>

    <script>
        // Image preview functionality
        document.getElementById('fighter_image').addEventListener('change', function(e) {
            const preview = document.getElementById('image-preview');
            const file = e.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                }
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '';
            }
        });
    </script>
</body>
</html>