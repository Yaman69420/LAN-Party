<!DOCTYPE html>
<html lang="nl">
<head>
    <title>Registreren - LAN-Party</title>
</head>
<body>
    <h1>Registreren</h1>
    
    <?php if(isset($error)): ?>
        <p style="color: red;"><?= e($error) ?></p>
    <?php endif; ?>

    <form action="/register" method="POST">
        <?= csrf_field() ?>
        <div>
            <label>Gebruikersnaam:</label>
            <input type="text" name="username" required>
        </div>
        <div>
            <label>Email:</label>
            <input type="email" name="email" required>
        </div>
        <div>
            <label>Wachtwoord:</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Registreren</button>
    </form>
    <p>Al een account? <a href="/login">Inloggen</a></p>
</body>
</html>
