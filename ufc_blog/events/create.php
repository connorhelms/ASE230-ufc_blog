// events/create.php
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
        if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] === 0) {
            $image_url = uploadImage($_FILES['event_image'], '../data/events');
        }

        // Insert event data
        $db->query(
            "INSERT INTO events (title, event_date, location, description, image_url) 
             VALUES (?, ?, ?, ?, ?)",
            [
                $_POST['title'],
                $_POST['event_date'],
                $_POST['location'],
                $_POST['description'],
                $image_url
            ]
        );

        $success = "Event added successfully!";
        // Optional: Redirect to events list
        // header('Location: /ufc_blog/events/');
        // exit();
    } catch (Exception $e) {
        $error = "Error creating event: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event - UFC Blog</title>
    <link rel="stylesheet" href="/ufc_blog/theme/style.css">
</head>
<body>
    <?php include '../theme/header.php'; ?>
    
    <main>
        <div class="container">
            <h1>Add New Event</h1>

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
                    <label for="title">Event Title:</label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           required 
                           value="<?= isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '' ?>"
                           placeholder="UFC 300: Main Event">
                </div>

                <div class="form-group">
                    <label for="event_date">Event Date and Time:</label>
                    <input type="datetime-local" 
                           id="event_date" 
                           name="event_date" 
                           required
                           value="<?= isset($_POST['event_date']) ? htmlspecialchars($_POST['event_date']) : '' ?>">
                </div>

                <div class="form-group">
                    <label for="location">Location:</label>
                    <input type="text" 
                           id="location" 
                           name="location" 
                           required
                           value="<?= isset($_POST['location']) ? htmlspecialchars($_POST['location']) : '' ?>"
                           placeholder="T-Mobile Arena, Las Vegas, NV">
                </div>

                <div class="form-group">
                    <label for="description">Event Description:</label>
                    <textarea id="description" 
                              name="description" 
                              required 
                              rows="6"
                              placeholder="Enter event details, fight card, and other information..."><?= isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '' ?></textarea>
                </div>

                <div class="form-group">
                    <label for="event_image">Event Poster/Image:</label>
                    <input type="file" 
                           id="event_image" 
                           name="event_image" 
                           accept="image/*">
                    <div class="file-help">
                        Accepted formats: JPG, PNG, GIF. Max size: 5MB
                    </div>
                </div>

                <div class="form-group">
                    <div id="image-preview" class="image-preview"></div>
                </div>

                <div class="button-group">
                    <button type="submit" class="button primary">Add Event</button>
                    <a href="/ufc_blog/events/" class="button secondary">Cancel</a>
                </div>
            </form>
        </div>
    </main>

    <?php include '../theme/footer.php'; ?>

    <script>
        // Image preview functionality
        document.getElementById('event_image').addEventListener('change', function(e) {
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

        // Set minimum date to today
        const dateInput = document.getElementById('event_date');
        const today = new Date();
        today.setMinutes(today.getMinutes() - today.getTimezoneOffset());
        dateInput.min = today.toISOString().slice(0, 16);
    </script>
</body>
</html>