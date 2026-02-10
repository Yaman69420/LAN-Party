<div class="max-w-4xl mx-auto">

    <div class="mb-8 border-b border-white/5 pb-4 flex justify-between items-end">
        <div>
            <div class="flex items-center space-x-2 text-cyber-purple mb-2">
                <span class="text-xs">🛰️</span>
                <span class="text-[10px] uppercase font-bold tracking-[0.3em]">New Operation</span>
            </div>
            <h2 class="text-white font-orbitron text-3xl uppercase italic tracking-wider">Propose LAN Event</h2>
            <p class="text-cyber-gray text-xs mt-2">Dien een aanvraag in. Een Admin zal deze beoordelen en goedkeuren.</p>
        </div>
    </div>

    <div class="bg-cyber-dark/40 border border-white/5 p-8 rounded-sm backdrop-blur-sm shadow-[0_0_50px_rgba(0,0,0,0.5)] relative">

        <div class="absolute top-0 left-0 w-2 h-2 border-t border-l border-cyber-cyan"></div>
        <div class="absolute top-0 right-0 w-2 h-2 border-t border-r border-cyber-cyan"></div>
        <div class="absolute bottom-0 left-0 w-2 h-2 border-b border-l border-cyber-cyan"></div>
        <div class="absolute bottom-0 right-0 w-2 h-2 border-b border-r border-cyber-cyan"></div>

        <form action="/propose" method="POST" class="space-y-6">
            <?= csrf_field() ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] text-cyber-purple uppercase tracking-widest mb-2">Operator ID (Naam)</label>
                    <input type="text" name="proposed_username" value="<?= htmlspecialchars($_SESSION['user']['username'] ?? '') ?>" required
                           class="w-full bg-cyber-dark border border-white/10 text-white px-4 py-3 text-sm focus:border-cyber-cyan focus:outline-none transition-all font-mono">
                </div>
                <div>
                    <label class="block text-[10px] text-cyber-purple uppercase tracking-widest mb-2">Comms Channel (Email)</label>
                    <input type="email" name="proposed_email" value="<?= htmlspecialchars($_SESSION['user']['email'] ?? '') ?>" required placeholder="jouw@email.com"
                           class="w-full bg-cyber-dark border border-white/10 text-white px-4 py-3 text-sm focus:border-cyber-cyan focus:outline-none transition-all font-mono">
                </div>
            </div>

            <div class="h-px bg-white/5 my-4"></div>

            <div>
                <label class="block text-[10px] text-cyber-cyan uppercase tracking-widest mb-2">Operation Name</label>
                <input type="text" name="name" required placeholder="bv. Neon Nights: The Tournament"
                       class="w-full bg-cyber-dark border border-white/10 text-white px-4 py-3 focus:border-cyber-cyan focus:outline-none focus:shadow-[0_0_15px_rgba(0,242,255,0.1)] transition-all font-orbitron tracking-wide placeholder-white/20">
            </div>

            <div>
                <label class="block text-[10px] text-cyber-cyan uppercase tracking-widest mb-2">Event Description</label>
                <textarea name="description" rows="2" placeholder="Vertel kort wat het plan is (games, prijzen, etc...)"
                          class="w-full bg-cyber-dark border border-white/10 text-white px-4 py-3 focus:border-cyber-cyan focus:outline-none transition-all text-sm"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] text-cyber-cyan uppercase tracking-widest mb-2">Start Datum</label>
                    <input type="date" name="start_date" required
                           class="w-full bg-cyber-dark border border-white/10 text-white px-4 py-3 focus:border-cyber-cyan focus:outline-none transition-all [color-scheme:dark]">
                </div>
                <div>
                    <label class="block text-[10px] text-cyber-cyan uppercase tracking-widest mb-2">Eind Datum (Optioneel)</label>
                    <input type="date" name="end_date"
                           class="w-full bg-cyber-dark border border-white/10 text-white px-4 py-3 focus:border-cyber-cyan focus:outline-none transition-all [color-scheme:dark]">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] text-cyber-cyan uppercase tracking-widest mb-2">Start Tijd (00:00 - 23:59)</label>
                    <input type="time" name="start_time" required
                           class="w-full bg-cyber-dark border border-white/10 text-white px-4 py-3 focus:border-cyber-cyan focus:outline-none transition-all [color-scheme:dark]">
                </div>
                <div>
                    <label class="block text-[10px] text-cyber-cyan uppercase tracking-widest mb-2">Eind Tijd</label>
                    <input type="time" name="end_time" required
                           class="w-full bg-cyber-dark border border-white/10 text-white px-4 py-3 focus:border-cyber-cyan focus:outline-none transition-all [color-scheme:dark]">
                    <p class="text-[9px] text-white/30 mt-1">* Indien vroeger dan start, telt als volgende dag.</p>
                </div>
            </div>

            <div>
                <label class="block text-[10px] text-cyber-cyan uppercase tracking-widest mb-2">Verwachte Aantal Personen</label>
                <input type="number" name="attendees" min="2" max="100" required placeholder="bv. 15"
                       class="w-full md:w-[calc(50%-12px)] bg-cyber-dark border border-white/10 text-white px-4 py-3 focus:border-cyber-cyan focus:outline-none transition-all">
            </div>

            <div class="pt-6">
                <button type="submit"
                        class="w-full group relative px-6 py-4 bg-cyber-cyan/10 border border-cyber-cyan hover:bg-cyber-cyan/20 transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 w-0 bg-cyber-cyan transition-all duration-[250ms] ease-out group-hover:w-full opacity-10"></div>
                    <span class="relative text-cyber-cyan font-orbitron uppercase tracking-[0.2em] group-hover:text-white transition-colors font-bold">
                        Initialize Protocol
                    </span>
                </button>
            </div>

        </form>
    </div>
</div>