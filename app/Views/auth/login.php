<!DOCTYPE html>
<html lang="nl">
<head>
    <title>Login - LAN-Party</title>
</head>
<body>
    <h1>Inloggen</h1>
    <form action="/login" method="POST">
        <?= csrf_field() ?>
        <div>
            <label>Email:</label>
            <input type="email" name="email" required>
        </div>
        <div>
            <label>Wachtwoord:</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Inloggen</button>
    </form>
</body>
</html>
