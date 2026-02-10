<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreren - LAN-Party</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@300;500;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cyber: {
                            black: '#0b0c10',
                            dark: '#12141a',
                            cyan: '#00f2ff',
                            purple: '#bc13fe',
                            gray: '#c5c6c7'
                        }
                    },
                    fontFamily: {
                        orbitron: ['Orbitron', 'sans-serif'],
                        rajdhani: ['Rajdhani', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        .neon-border-cyan { box-shadow: 0 0 10px rgba(0, 242, 255, 0.5); }
        .neon-text-cyan { text-shadow: 0 0 8px rgba(0, 242, 255, 0.8); }
        .cyber-grid {
            background-image: linear-gradient(rgba(18, 20, 26, 0.9), rgba(18, 20, 26, 0.9)), url('https://www.transparenttextures.com/patterns/carbon-fibre.png');
        }
    </style>
</head>
<body class="bg-cyber-black text-cyber-gray font-rajdhani cyber-grid min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-cyber-dark border border-white/5 p-8 rounded-lg shadow-[0_0_20px_rgba(0,242,255,0.1)] relative overflow-hidden">
        <!-- Decoratieve border lines -->
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-cyber-cyan to-transparent"></div>
        <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-cyber-purple to-transparent"></div>

        <div class="text-center mb-8">
            <h1 class="font-orbitron text-3xl text-white tracking-widest neon-text-cyan mb-2">JOIN THE HUB</h1>
            <p class="text-xs text-cyber-cyan/70 uppercase tracking-[0.2em]">Create your identity</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="mb-6 bg-red-500/10 border border-red-500 text-red-500 p-3 rounded text-sm text-center">
                <?= e($error) ?>
            </div>
        <?php endif; ?>

        <form action="/register" method="POST" class="space-y-5">
            <?= csrf_field() ?>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs uppercase tracking-wider text-cyber-cyan/80 mb-1">Voornaam</label>
                    <input type="text" name="first_name" required 
                           class="w-full bg-black/30 border border-cyber-gray/20 rounded p-2 text-white focus:border-cyber-cyan focus:outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-wider text-cyber-cyan/80 mb-1">Achternaam</label>
                    <input type="text" name="last_name" required 
                           class="w-full bg-black/30 border border-cyber-gray/20 rounded p-2 text-white focus:border-cyber-cyan focus:outline-none transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-xs uppercase tracking-wider text-cyber-cyan/80 mb-1">Gebruikersnaam</label>
                <input type="text" name="username" required 
                       class="w-full bg-black/30 border border-cyber-gray/20 rounded p-2 text-white focus:border-cyber-cyan focus:outline-none transition-colors">
            </div>

            <div>
                <label class="block text-xs uppercase tracking-wider text-cyber-cyan/80 mb-1">Email</label>
                <input type="email" name="email" required 
                       class="w-full bg-black/30 border border-cyber-gray/20 rounded p-2 text-white focus:border-cyber-cyan focus:outline-none transition-colors">
            </div>

            <div>
                <label class="block text-xs uppercase tracking-wider text-cyber-cyan/80 mb-1">Wachtwoord</label>
                <input type="password" name="password" required 
                       class="w-full bg-black/30 border border-cyber-gray/20 rounded p-2 text-white focus:border-cyber-cyan focus:outline-none transition-colors">
            </div>

            <button type="submit" 
                    class="w-full py-3 bg-cyber-cyan/10 hover:bg-cyber-cyan/20 border border-cyber-cyan text-cyber-cyan font-bold tracking-widest uppercase transition-all neon-border-cyan group">
                <span class="group-hover:neon-text-cyan transition-all">Initialize Profile</span>
            </button>
        </form>

        <div class="mt-8 text-center border-t border-white/5 pt-4">
            <p class="text-sm text-gray-400">Already initialized?</p>
            <a href="/login" class="text-cyber-purple hover:text-white transition-colors text-sm font-bold uppercase tracking-wider">
                > Access System <
            </a>
        </div>
    </div>

</body>
</html>
