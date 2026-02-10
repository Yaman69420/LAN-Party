<!DOCTYPE html>
<html lang="nl">
<head>
    <title>Login - LAN-Party</title>
</head>
<body>
    <h1>Inloggen</h1>
    <form action="/login" method="POST" class="space-y-6">
        <?php if (isset($error)): ?>
            <div class="bg-red-500/10 border border-red-500 text-red-500 p-3 rounded text-sm text-center">
                <?= e($error) ?>
            </div>
        <?php endif; ?>
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
    
    <div class="mt-6 text-center border-t border-white/5 pt-4">
        <p class="text-xs text-cyber-gray">No access credentials?</p>
        <a href="/register" class="text-cyber-cyan hover:text-white transition-colors text-xs font-bold uppercase tracking-wider mt-2 inline-block p-2 border border-cyber-cyan/30 hover:border-cyber-cyan bg-cyber-cyan/5 rounded">
            [ Initialize New User ]
        </a>
    </div>
