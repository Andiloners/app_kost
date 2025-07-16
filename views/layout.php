<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Aplikasi Kost' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-primary text-white p-3">
        <h1 class="container">Selamat Datang di Kost Milik Andi</h1>
    </header>
    <main class="container my-4">
        <?= $content ?? '' ?>
    </main>
    <footer class="bg-light text-center p-3">
        <small>&copy; <?= date('Y') ?> Andi</small>
    </footer>
</body>
</html> 