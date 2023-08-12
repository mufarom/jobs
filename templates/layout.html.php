<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="/styles.css" />
    <title>Jo's Jobs - <?= $title ?></title>
</head>
<body>
    <header>
        <section>
            <aside>
                <h3>Office Hours:</h3>
                <p>Mon-Fri: 09:00-17:30</p>
                <p>Sat: 09:00-17:00</p>
                <p>Sun: Closed</p>
                <a href="/admin/AdminForm"><input type="button" value="Admin Login"/></a>
            </aside>
            <h1>Jo's Jobs</h1>
        </section>
    </header>
    <?php require '../templates/nav.html.php'?>
    <img src="/images/randombanner.php" />
    <main class="<?= $class ?>">
        <?= $output ?>
    </main>
    <footer>
        &copy; Jo's Jobs <?= date('Y'); ?>
    </footer>
</body>
</html>