<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? "$pageTitle - " : "" ?>UFC Blog</title>
    <link rel="stylesheet" href="/ufc_blog/theme/style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <a href="/ufc_blog/">UFC Blog</a>
            </div>
            <ul class="nav-links">
                <li><a href="/ufc_blog/fighters/">Fighters</a></li>
                <li><a href="/ufc_blog/events/">Events</a></li>
                <li><a href="/ufc_blog/posts/">Posts</a></li>
                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                        <li><a href="/ufc_blog/admin/dashboard.php">Admin</a></li>
                    <?php endif; ?>
                    <li><a href="/ufc_blog/posts/create.php">Create Post</a></li>
                    <li class="user-menu">
                        <span><?= htmlspecialchars(getUsername()) ?></span>
                        <a href="/ufc_blog/auth/logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li><a href="/ufc_blog/auth/login.php">Login</a></li>
                    <li><a href="/ufc_blog/auth/register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>