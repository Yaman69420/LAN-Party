<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cyber-LAN Hub</title>
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
        /* Custom glow effecten die Tailwind standaard niet heeft */
        .neon-border-cyan { box-shadow: 0 0 10px rgba(0, 242, 255, 0.5); }
        .neon-text-cyan { text-shadow: 0 0 8px rgba(0, 242, 255, 0.8); }
        .cyber-grid {
            background-image: linear-gradient(rgba(18, 20, 26, 0.9), rgba(18, 20, 26, 0.9)), url('https://www.transparenttextures.com/patterns/carbon-fibre.png');
        }
    </style>
</head>
<body class="bg-cyber-black text-cyber-gray font-rajdhani cyber-grid min-h-screen">

<div class="flex h-screen overflow-hidden">

    <aside class="w-64 bg-cyber-dark border-r border-white/5 flex flex-col py-6">
        <div class="px-8 mb-12 flex justify-center">
            <div class="w-12 h-12 bg-cyber-cyan/10 rounded-lg flex items-center justify-center border border-cyber-cyan neon-border-cyan">
                <span class="text-cyber-cyan text-2xl">🎮</span>
            </div>
        </div>

        <nav class="flex-1">
            <a href="/dashboard" class="flex items-center px-8 py-4 text-cyber-cyan bg-cyber-cyan/5 border-l-4 border-cyber-cyan transition-all">
                <span class="mr-4 opacity-80">⬢</span>
                <span class="font-bold tracking-wider uppercase text-sm neon-text-cyan">Dashboard</span>
            </a>

            <a href="/propose" class="flex items-center px-8 py-4 text-cyber-gray/50 hover:text-cyber-purple hover:bg-white/5 transition-all">
                <span class="mr-4 opacity-30">⬢</span>
                <span class="font-medium tracking-wider uppercase text-sm">Propose LAN</span>
            </a>
        </nav>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden">

        <header class="h-20 bg-white/5 backdrop-blur-md border-b border-white/5 flex items-center justify-between px-10">
            <h1 class="font-orbitron text-xl tracking-widest text-white uppercase italic">
                Cyber-Lan Hub
            </h1>

            <div class="flex items-center space-x-6">
                    <span class="bg-yellow-400 text-black text-[10px] font-bold px-3 py-1 rounded-full tracking-tighter shadow-[0_0_10px_rgba(250,204,21,0.5)]">
                        PENDING PROPOSALS
                    </span>

                <div class="flex items-center space-x-3 border-l border-white/10 pl-6">
                    <div class="text-right">
                        <p class="text-xs text-white font-bold leading-none">Magaly B.</p>
                        <p class="text-[10px] text-cyber-cyan opacity-70 uppercase tracking-tighter">Admin</p>
                    </div>
                    <div class="w-10 h-10 rounded-full border border-cyber-cyan p-0.5 shadow-inner">
                        <img src="https://ui-avatars.com/api/?name=Magaly+B&background=0b0c10&color=00f2ff" class="rounded-full" alt="Avatar">
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-10 bg-gradient-to-br from-transparent to-cyber-purple/5">
            <?php echo $content; ?>
        </main>
    </div>
</div>

</body>
</html>