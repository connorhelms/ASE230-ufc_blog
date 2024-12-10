<?php
require_once '../lib/db.php';
require_once '../lib/auth.php';

$db = new Database();
$fighters = $db->query(
    "SELECT * FROM fighters ORDER BY name ASC"
)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Fighters - UFC Blog</title>
</head>
<body>
    <?php include '../theme/header.php'; ?>
    <main>

        <div class="container">
            <div class="page-header">
                <h1>UFC Fighters</h1>
                <?php if (isAdmin()): ?>
                <a href="/ufc_blog/admin/fighters/create.php" class="button">Add New Fighter</a>
                <?php endif; ?>
            </div>
        
        <div class="fighters-grid">
            <?php foreach ($fighters as $fighter): ?>
                <div class="fighter-card">
                    <?php if ($fighter['image_url']): ?>
                        <img src="<?= htmlspecialchars($fighter['image_url']) ?>" 
                             alt="<?= htmlspecialchars($fighter['name']) ?>">
                    <?php endif; ?>
                    <h2><?= htmlspecialchars($fighter['name']) ?></h2>
                    <p>Weight Class: <?= htmlspecialchars($fighter['weight_class']) ?></p>
                    <p>Record: <?= htmlspecialchars($fighter['record']) ?></p>
                    <a href="/fighters/detail.php?id=<?= $fighter['fighter_id'] ?>">
                        
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
    <?php include '../theme/footer.php'; ?>
</body>
</html>