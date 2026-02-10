<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Login | LAN-Party</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #020617; }
        .font-orbitron { font-family: 'Orbitron', sans-serif; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">

<div class="w-full max-w-md bg-slate-900/80 border border-cyan-500/30 p-8 rounded-sm backdrop-blur-md shadow-[0_0_50px_rgba(6,182,212,0.1)]">

    <div class="mb-8 text-center">
        <h1 class="font-orbitron text-2xl text-white uppercase italic tracking-widest italic">System Access</h1>
        <p class="text-[10px] text-cyan-500 uppercase tracking-[0.3em] mt-2">Authentication Required</p>
    </div>

    <?php if (isset($error)): ?>
        <div class="mb-6 bg-red-500/10 border border-red-500/50 text-red-500 p-3 rounded-sm text-xs text-center uppercase tracking-wider">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="/login" method="POST" class="space-y-6">
        <?= csrf_field() ?>

        <div>
            <label class="block text-[10px] text-slate-400 uppercase tracking-widest mb-2">Email Identity</label>
            <input type="email" name="email" required
                   class="w-full bg-slate-950 border border-slate-800 text-white px-4 py-3 rounded-sm focus:outline-none focus:border-cyan-500 transition-colors text-sm">
        </div>

        <div>
            <label class="block text-[10px] text-slate-400 uppercase tracking-widest mb-2">Security Key</label>
            <input type="password" name="password" required
                   class="w-full bg-slate-950 border border-slate-800 text-white px-4 py-3 rounded-sm focus:outline-none focus:border-cyan-500 transition-colors text-sm">
        </div>

        <button type="submit"
                class="w-full bg-cyan-600 hover:bg-cyan-500 text-white font-orbitron text-sm uppercase tracking-[0.2em] py-4 transition-all duration-300 shadow-lg shadow-cyan-900/20">
            Initialize Login
        </button>
    </form>

    <div class="mt-8 pt-6 border-t border-slate-800 text-center">
        <p class="text-[10px] text-slate-500 uppercase tracking-widest mb-4">No access credentials found?</p>
        <a href="/register" class="inline-block px-6 py-2 border border-cyan-500/30 text-cyan-500 text-[10px] font-orbitron uppercase tracking-widest hover:bg-cyan-500/10 hover:border-cyan-500 transition-all">
            [ Register New Unit ]
        </a>
    </div>
</div>

</body>
</html>