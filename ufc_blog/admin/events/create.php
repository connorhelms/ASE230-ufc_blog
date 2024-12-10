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
        if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] === 0) {
            $image_url = uploadImage($_FILES['event_image'], '../../data/events');
        }

        // Insert event data
        $db->query(
            "INSERT INTO events (title, event_date, location, description, image_url) VALUES (?, ?, ?, ?, ?)",
            [
                $_POST['title'],
                $_POST['event_date'],
                $_POST['location'],
                $_POST['description'],
                $image_url
            ]
        );

        $success = "Event added successfully!";
    } catch (Exception $e) {
        $error = "Error creating event: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Event - Admin - UFC Blog</title>
    <link rel="stylesheet" href="/ufc_blog/theme/style.css">
</head>
<body>
    <?php include '../../theme/header.php'; ?>
    <main>
        <div class="container">
            <h1>Add New Event</h1>

            <?php if ($error): ?>
                <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="success-message"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="form">
                <div class="form-group">
                    <label>Event Title:
                        <input type="text" name="title" required>
                    </label>
                </div>

                <div class="form-group">
                    <label>Event Date and Time:
                        <input type="datetime-local" name="event_date" required>
                    </label>
                </div>

                <div class="form-group">
                    <label>Location:
                        <input type="text" name="location" required>
                    </label>
                </div>

                <div class="form-group">
                    <label>Description:
                        <textarea name="description" required rows="6"></textarea>
                    </label>
                </div>

                <div class="form-group">
                    <label>Event Image:
                        <input type="file" name="event_image" accept="image/*">
                    </label>
                </div>

                <div id="image-preview" class="image-preview"></div>

                <div class="button-group">
                    <button type="submit" class="button">Add Event</button>
                    <a href="/ufc_blog/events/" class="button">Cancel</a>
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

        // Set minimum date to today for event_date
        const dateInput = document.querySelector('input[type="datetime-local"]');
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        dateInput.min = now.toISOString().slice(0, 16);
    </script>
</body>
</html>