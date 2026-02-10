<?php
// Veilig variabelen ophalen uit de sessie om crashes te voorkomen
$user = $_SESSION['user'] ?? null;
$username = $user['username'] ?? 'Guest';
$role = $user['role'] ?? 'visitor';
$isLoggedIn = ($user !== null);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cyber-LAN Hub</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@300;500;700&display=swap" rel="stylesheet">
    
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

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
        /* Extra effecten */
        .neon-border-cyan { box-shadow: 0 0 10px rgba(0, 242, 255, 0.5); }
        .neon-text-cyan { text-shadow: 0 0 8px rgba(0, 242, 255, 0.8); }
        .cyber-grid {
            background-color: #0b0c10;
            background-image: linear-gradient(rgba(18, 20, 26, 0.9), rgba(18, 20, 26, 0.9)), url('https://www.transparenttextures.com/patterns/carbon-fibre.png');
        }
        /* Scrollbar styling */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #0b0c10; }
        ::-webkit-scrollbar-thumb { background: #1f2937; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #00f2ff; }
    </style>
</head>
<body class="bg-cyber-black text-cyber-gray font-rajdhani cyber-grid min-h-screen text-base overflow-hidden">

<div class="flex h-screen">

    <aside class="w-64 bg-cyber-dark border-r border-white/5 flex flex-col py-6 shadow-2xl z-20">

        <div class="px-8 mb-10 flex justify-center">
            <div class="w-16 h-16 bg-cyber-cyan/5 rounded-lg flex items-center justify-center border border-cyber-cyan neon-border-cyan transform hover:scale-105 transition-transform duration-300">
                <span class="text-cyber-cyan text-3xl">🎮</span>
            </div>
        </div>

        <nav class="flex-1 space-y-2">
            <a href="/dashboard" class="group flex items-center px-8 py-3 text-cyber-gray hover:text-cyber-cyan hover:bg-cyber-cyan/5 border-l-4 border-transparent hover:border-cyber-cyan transition-all duration-300">
                <span class="mr-4 text-lg opacity-70 group-hover:text-cyber-cyan group-hover:opacity-100 transition-opacity">📊</span>
                <span class="font-bold tracking-wider uppercase text-sm group-hover:neon-text-cyan">Dashboard</span>
            </a>

            <a href="/resources" class="group flex items-center px-8 py-3 text-cyber-gray hover:text-cyber-cyan hover:bg-cyber-cyan/5 border-l-4 border-transparent hover:border-cyber-cyan transition-all duration-300">
                <span class="mr-4 text-lg opacity-70 group-hover:text-cyber-cyan group-hover:opacity-100 transition-opacity">🛡️</span>
                <span class="font-medium tracking-wider uppercase text-sm">The Armory</span>
            </a>

            <a href="/propose" class="group flex items-center px-8 py-3 text-cyber-gray hover:text-cyber-purple hover:bg-cyber-purple/5 border-l-4 border-transparent hover:border-cyber-purple transition-all duration-300">
                <span class="mr-4 text-lg opacity-70 group-hover:text-cyber-purple group-hover:opacity-100 transition-opacity">🛰️</span>
                <span class="font-medium tracking-wider uppercase text-sm">Propose LAN</span>
            </a>

            <?php if ($role === 'admin'): ?>
                <div class="mt-8 pt-4 border-t border-white/5 mx-4">
                    <p class="px-4 text-[10px] text-cyber-purple uppercase tracking-[0.2em] font-bold mb-2">Command Center</p>
                    <a href="/admin" class="group flex items-center px-4 py-2 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded transition-all">
                        <span class="mr-3">⚙️</span>
                        <span class="text-xs font-bold uppercase tracking-wider">Admin Panel</span>
                    </a>
                </div>
            <?php endif; ?>
        </nav>

        <div class="px-8 pt-6 border-t border-white/5">
            <p class="text-[10px] text-white/20 uppercase tracking-widest text-center">System v2.0</p>
        </div>
    </aside>


    <div class="flex-1 flex flex-col relative overflow-hidden">

        <header class="h-20 bg-cyber-dark/80 backdrop-blur-md border-b border-white/5 flex items-center justify-between px-10 z-10">
            <h1 class="font-orbitron text-xl tracking-[0.2em] text-white uppercase italic bg-gradient-to-r from-white to-white/50 bg-clip-text text-transparent">
                Cyber-Lan Hub
            </h1>

            <div class="flex items-center space-x-6">
                <div class="hidden md:flex items-center space-x-2 px-3 py-1 bg-cyber-cyan/5 border border-cyber-cyan/20 rounded-full">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse shadow-[0_0_8px_#22c55e]"></span>
                    <span class="text-[10px] text-cyber-cyan font-bold tracking-widest uppercase">System Online</span>
                </div>

                <div class="flex items-center space-x-4 border-l border-white/10 pl-6 h-10">
                    <?php if ($isLoggedIn): ?>
                        <div class="text-right hidden sm:block">
                            <p class="text-sm text-white font-bold leading-none tracking-wide"><?= htmlspecialchars($username) ?></p>
                            <p class="text-[10px] text-cyber-cyan opacity-80 uppercase tracking-[0.1em] mt-0.5"><?= htmlspecialchars($role) ?></p>
                        </div>

                        <div class="w-10 h-10 rounded border border-cyber-cyan p-0.5 shadow-[0_0_15px_rgba(0,242,255,0.2)]">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($username) ?>&background=0b0c10&color=00f2ff&bold=true" class="rounded w-full h-full object-cover" alt="Avatar">
                        </div>

                        <a href="/logout" class="ml-2 text-white/30 hover:text-red-500 transition-colors duration-300" title="Uitloggen">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </a>
                    <?php else: ?>
                        <a href="/login" class="text-xs text-cyber-cyan border border-cyber-cyan px-4 py-2 hover:bg-cyber-cyan hover:text-black transition-all uppercase font-bold tracking-wider">
                            Login
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-10 relative">
            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-cyber-cyan/5 via-transparent to-cyber-purple/5 pointer-events-none"></div>

            <div class="relative z-10">
                <?= $content ?? '' ?>
            </div>
        </main>
    </div>
</div>

</body>
</html>