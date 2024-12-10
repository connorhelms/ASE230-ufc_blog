<?php
require_once '../lib/db.php';
require_once '../lib/auth.php';

requireAdmin();
$db = new Database();

$users = $db->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
$posts = $db->query(
    "SELECT p.*, u.username FROM posts p 
    JOIN users u ON p.user_id = u.user_id 
    ORDER BY p.created_at DESC LIMIT 10"
)->fetchAll(PDO::FETCH_ASSOC);
$events = $db->query(
    "SELECT * FROM events 
    WHERE event_date >= CURDATE() 
    ORDER BY event_date ASC LIMIT 5"
)->fetchAll(PDO::FETCH_ASSOC);
$fighters = $db->query("SELECT * FROM fighters ORDER BY name ASC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - UFC Blog</title>
    <link rel="stylesheet" href="/ufc_blog/theme/style.css">
</head>
<body>
    <?php include '../theme/header.php'; ?>
    <main>
        <div class="container">
            <h1>Admin Dashboard</h1>

            <div class="admin-actions">
                <h2>Quick Actions</h2>
                <div class="button-group">
                    <a href="/ufc_blog/admin/fighters/create.php" class="button">Add New Fighter</a>
                    <a href="/ufc_blog/admin/events/create.php" class="button">Add New Event</a>
                    <a href="/ufc_blog/posts/create.php" class="button">Add New Post</a>
                </div>
            </div>
            
            <div class="dashboard-grid">
                <section class="admin-card">
                    <h2>Recent Users</h2>
                    <a href="/ufc_blog/admin/users/" class="button">Manage Users</a>
                    <table>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['username']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= htmlspecialchars($user['role']) ?></td>
                                <td>
                                    <a href="/ufc_blog/admin/users/edit.php?id=<?= $user['user_id'] ?>">Edit</a>
                                    <?php if ($user['user_id'] !== $_SESSION['user_id']): ?>
                                        <form method="POST" action="/ufc_blog/admin/users/delete.php" style="display: inline;">
                                            <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                                            <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </section>

                <section class="admin-card">
                    <h2>Recent Posts</h2>
                    <a href="/ufc_blog/admin/posts/" class="button">Manage Posts</a>
                    <table>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                        <?php foreach ($posts as $post): ?>
                            <tr>
                                <td><?= htmlspecialchars($post['title']) ?></td>
                                <td><?= htmlspecialchars($post['username']) ?></td>
                                <td><?= date('Y-m-d', strtotime($post['created_at'])) ?></td>
                                <td>
                                    <a href="/ufc_blog/posts/edit.php?id=<?= $post['post_id'] ?>">Edit</a>
                                    <form method="POST" action="/ufc_blog/posts/delete.php" style="display: inline;">
                                        <input type="hidden" name="post_id" value="<?= $post['post_id'] ?>">
                                        <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </section>

                <section class="admin-card">
                    <h2>Fighters</h2>
                    <a href="/ufc_blog/admin/fighters/" class="button">Manage Fighters</a>
                    <table>
                        <tr>
                            <th>Name</th>
                            <th>Weight Class</th>
                            <th>Record</th>
                            <th>Actions</th>
                        </tr>
                        <?php foreach ($fighters as $fighter): ?>
                            <tr>
                                <td><?= htmlspecialchars($fighter['name']) ?></td>
                                <td><?= htmlspecialchars($fighter['weight_class']) ?></td>
                                <td><?= htmlspecialchars($fighter['record']) ?></td>
                                <td>
                                    <a href="/ufc_blog/admin/fighters/edit.php?id=<?= $fighter['fighter_id'] ?>">Edit</a>
                                    <form method="POST" action="/ufc_blog/admin/fighters/delete.php" style="display: inline;">
                                        <input type="hidden" name="fighter_id" value="<?= $fighter['fighter_id'] ?>">
                                        <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </section>

                <section class="admin-card">
                    <h2>Upcoming Events</h2>
                    <a href="/ufc_blog/admin/events/" class="button">Manage Events</a>
                    <table>
                        <tr>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Actions</th>
                        </tr>
                        <?php foreach ($events as $event): ?>
                            <tr>
                                <td><?= htmlspecialchars($event['title']) ?></td>
                                <td><?= date('Y-m-d', strtotime($event['event_date'])) ?></td>
                                <td><?= htmlspecialchars($event['location']) ?></td>
                                <td>
                                    <a href="/ufc_blog/admin/events/edit.php?id=<?= $event['event_id'] ?>">Edit</a>
                                    <form method="POST" action="/ufc_blog/admin/events/delete.php" style="display: inline;">
                                        <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                                        <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </section>
            </div>
        </div>
    </main>
    <?php include '../theme/footer.php'; ?>
</body>
</html>