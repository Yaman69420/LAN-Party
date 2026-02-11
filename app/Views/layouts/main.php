<?php
// START VAN MAIN.PHP
$sessionData = $_SESSION['user'] ?? null;

// FIX: Zorg dat het ALTIJD een array is, ook als de database een object geeft
$user = null;
if ($sessionData) {
    $user = (array)$sessionData;
}

// Variabelen veilig instellen
$username = $user['username'] ?? 'Guest';
$role = $user['role'] ?? 'visitor';
$isLoggedIn = ($user !== null);
$navImg = $user['profile_image'] ?? null;

// --- DYNAMISCHE NAVIGATIE LOGICA ---
// Haal het huidige pad op (bijv. '/dashboard' of '/LAN-Party/dashboard')
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Helper functie om te checken of een menu-item actief is
function getNavClass(string $targetPath, string $currentPath, string $activeColorClass = 'text-cyber-cyan', string $activeShadowClass = 'drop-shadow-[0_0_8px_#00f2ff]'): string {
    // Checken of het targetPath voorkomt in de huidige URL
    $isActive = (strpos($currentPath, $targetPath) !== false);

    if ($isActive) {
        // Wat we teruggeven als de pagina actief is (Inclusief het blauwe lijntje aan de linkerkant!)
        return "opacity-100 $activeColorClass $activeShadowClass relative before:absolute before:left-0 before:w-1 before:h-8 before:bg-cyber-cyan before:shadow-[0_0_15px_#00f2ff] before:rounded-r-full";
    } else {
        // Wat we teruggeven als hij NIET actief is
        return "opacity-60 text-white hover:opacity-100 hover:$activeColorClass hover:$activeShadowClass";
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cyber-LAN Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@300;500;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cyber: { black: '#0b0c10', dark: '#12141a', cyan: '#00f2ff', purple: '#bc13fe', gray: '#c5c6c7' }
                    },
                    fontFamily: { orbitron: ['Orbitron', 'sans-serif'], rajdhani: ['Rajdhani', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        .neon-border-cyan { box-shadow: 0 0 10px rgba(0, 242, 255, 0.5); }
        .neon-text-cyan { text-shadow: 0 0 8px rgba(0, 242, 255, 0.8); }
        .cyber-grid { background-color: #0b0c10; background-image: linear-gradient(rgba(18, 20, 26, 0.9), rgba(18, 20, 26, 0.9)), url('https://www.transparenttextures.com/patterns/carbon-fibre.png'); }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #0b0c10; }
        ::-webkit-scrollbar-thumb { background: #1f2937; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #00f2ff; }
    </style>
</head>
<body class="bg-cyber-black text-cyber-gray font-rajdhani cyber-grid min-h-screen text-base overflow-hidden">

<div class="flex h-screen">
    <aside class="w-28 bg-cyber-dark/40 backdrop-blur-xl border-r border-white/5 flex flex-col py-8 z-20 relative">
        <div class="px-4 mb-12 flex justify-center">
            <div class="relative group">
                <div class="absolute -inset-2 bg-cyber-cyan opacity-20 blur group-hover:opacity-40 transition duration-300"></div>
                <svg class="relative w-10 h-10 text-cyber-cyan" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M6 12L3.269 9.725C2.56 9.133 2.56 8.067 3.269 7.475L7.475 3.269C8.067 2.56 9.133 2.56 9.725 3.269L12 6M18 12L20.731 14.275C21.44 14.867 21.44 15.933 20.731 16.525L16.525 20.731C15.933 21.44 14.867 21.44 14.275 20.731L12 18M12 12L15 9M12 12L9 15" stroke-linecap="round"/>
                    <circle cx="12" cy="12" r="2" fill="currentColor" class="animate-pulse"/>
                </svg>
            </div>
        </div>

        <nav class="flex-1 flex flex-col items-center space-y-8 relative w-full">

            <a href="/dashboard" class="group flex flex-col items-center justify-center w-full transition-all duration-300 <?= getNavClass('/dashboard', $currentPath) ?>">
                <svg class="w-8 h-8 mb-2 transition-colors duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="3" y="3" width="7" height="7" rx="1"/>
                    <rect x="14" y="3" width="7" height="7" rx="1"/>
                    <rect x="14" y="14" width="7" height="7" rx="1"/>
                    <rect x="3" y="14" width="7" height="7" rx="1"/>
                </svg>
                <span class="text-[9px] font-orbitron tracking-widest uppercase text-center transition-colors duration-300">Dashboard</span>
            </a>

            <a href="/propose" class="group flex flex-col items-center justify-center w-full transition-all duration-300 <?= getNavClass('/propose', $currentPath, 'text-cyber-purple', 'drop-shadow-[0_0_8px_#bc13fe]') ?>">
                <svg class="w-8 h-8 mb-2 transition-colors duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="text-[9px] font-orbitron tracking-widest uppercase text-center transition-colors duration-300">Propose LAN</span>
            </a>

            <a href="/resources" class="group flex flex-col items-center justify-center w-full transition-all duration-300 <?= getNavClass('/resources', $currentPath) ?>">
                <svg class="w-8 h-8 mb-2 transition-colors duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="text-[9px] font-orbitron tracking-widest uppercase text-center transition-colors duration-300">The Armory</span>
            </a>

            <a href="/proposals" class="group flex flex-col items-center justify-center w-full transition-all duration-300 <?= getNavClass('/proposals', $currentPath, 'text-cyber-purple', 'drop-shadow-[0_0_8px_#bc13fe]') ?>">
                <svg class="w-8 h-8 mb-2 transition-colors duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M4 11a9 9 0 0 1 9 9M4 4a16 16 0 0 1 16 16" stroke-linecap="round"/><circle cx="5" cy="19" r="1"/>
                </svg>
                <span class="text-[9px] font-orbitron tracking-widest uppercase text-center transition-colors duration-300">Proposals</span>
            </a>

            <a href="/profile" class="group flex flex-col items-center justify-center w-full transition-all duration-300 <?= getNavClass('/profile', $currentPath) ?>">
                <svg class="w-8 h-8 mb-2 transition-colors duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
                <span class="text-[9px] font-orbitron tracking-widest uppercase text-center transition-colors duration-300">Profile</span>
            </a>

            <?php if ($role === 'admin'): ?>
                <div class="mt-auto pb-4 w-full">
                    <a href="/admin" class="group flex flex-col items-center justify-center w-full transition-all duration-300 <?= getNavClass('/admin', $currentPath, 'text-red-500', 'drop-shadow-[0_0_8px_#ef4444]') ?>">
                        <svg class="w-6 h-6 mb-1 transition-colors duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                        </svg>
                        <span class="text-[8px] font-orbitron tracking-[0.2em] uppercase transition-colors duration-300">Admin</span>
                    </a>
                </div>
            <?php endif; ?>
        </nav>
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

                        <div class="w-10 h-10 rounded border border-cyber-cyan p-0.5 shadow-[0_0_15px_rgba(0,242,255,0.2)] overflow-hidden bg-black">
                            <?php
                            if (!empty($navImg)) {
                                $navSrc = '/LAN-Party/public/uploads/avatars/' . rawurlencode($navImg);
                            } else {
                                $navSrc = 'https://ui-avatars.com/api/?name=' . urlencode($username) . '&background=0b0c10&color=00f2ff&bold=true';
                            }
                            ?>

                            <img src="<?= $navSrc ?>"
                                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=<?= urlencode($username) ?>&background=0b0c10&color=00f2ff&bold=true';"
                                 class="rounded w-full h-full object-cover"
                                 alt="Avatar">
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