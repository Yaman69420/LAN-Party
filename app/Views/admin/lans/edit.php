<div class="max-w-4xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <a href="/admin/lans" class="text-[10px] text-cyber-cyan uppercase tracking-widest hover:text-white transition-colors mb-2 inline-block">
                « Back to Operations
            </a>
            <h2 class="text-3xl text-white font-orbitron uppercase italic tracking-wider">
                Edit Operation Data
            </h2>
        </div>
    </div>

    <div class="bg-cyber-dark/60 border border-white/5 p-8 rounded-sm shadow-2xl">
        <form action="/admin/lans/update" method="POST" class="space-y-6">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= $lan['id'] ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] text-cyber-purple uppercase tracking-widest mb-2">Operation Name</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($lan['name']) ?>" required
                           class="w-full bg-black/40 border border-white/10 text-white px-4 py-3 focus:border-cyber-purple focus:outline-none transition-all font-orbitron">
                </div>
                <div>
                    <label class="block text-[10px] text-cyber-purple uppercase tracking-widest mb-2">Contact Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($lan['contact_email']) ?>" required
                           class="w-full bg-black/40 border border-white/10 text-white px-4 py-3 focus:border-cyber-purple focus:outline-none transition-all font-mono text-sm">
                </div>
            </div>

            <div>
                <label class="block text-[10px] text-cyber-purple uppercase tracking-widest mb-2">Description / Intel</label>
                <textarea name="description" rows="4" required
                          class="w-full bg-black/40 border border-white/10 text-white px-4 py-3 focus:border-cyber-purple focus:outline-none transition-all text-sm leading-relaxed"><?= htmlspecialchars($lan['description']) ?></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                 <div>
                    <label class="block text-[10px] text-cyber-cyan uppercase tracking-widest mb-2">Expected Units</label>
                    <input type="number" name="attendees" value="<?= $lan['expected_attendees'] ?>" required
                           class="w-full bg-black/40 border border-white/10 text-white px-4 py-3 focus:border-cyber-cyan focus:outline-none transition-all font-mono">
                </div>
                 <!-- Empty div to keep grid alignment if needed, or just let it flow -->
                 <div class="hidden md:block"></div>
            </div>

            <!-- Start Date & Time -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] text-cyber-cyan uppercase tracking-widest mb-2">Start Datum</label>
                    <input type="date" name="start_date" value="<?= date('Y-m-d', strtotime($lan['start_date'])) ?>" required
                           class="w-full bg-black/40 border border-white/10 text-white px-4 py-3 focus:border-cyber-cyan focus:outline-none transition-all [color-scheme:dark]">
                </div>
                <div>
                    <label class="block text-[10px] text-cyber-cyan uppercase tracking-widest mb-2">Start Tijd (00:00 - 23:59)</label>
                    <input type="time" name="start_time" value="<?= date('H:i', strtotime($lan['start_date'])) ?>" required
                           class="w-full bg-black/40 border border-white/10 text-white px-4 py-3 focus:border-cyber-cyan focus:outline-none transition-all [color-scheme:dark]">
                </div>
            </div>

            <!-- End Date & Time -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                     <label class="block text-[10px] text-cyber-cyan uppercase tracking-widest mb-2">Eind Datum</label>
                     <input type="date" name="end_date" value="<?= date('Y-m-d', strtotime($lan['end_date'])) ?>" required
                            class="w-full bg-black/40 border border-white/10 text-white px-4 py-3 focus:border-cyber-cyan focus:outline-none transition-all [color-scheme:dark]">
                </div>
                 <div>
                    <label class="block text-[10px] text-cyber-cyan uppercase tracking-widest mb-2">Eind Tijd</label>
                    <input type="time" name="end_time" value="<?= date('H:i', strtotime($lan['end_date'])) ?>" required
                           class="w-full bg-black/40 border border-white/10 text-white px-4 py-3 focus:border-cyber-cyan focus:outline-none transition-all [color-scheme:dark]">
                    <p class="text-[9px] text-white/30 mt-1">* Indien vroeger dan start, telt als volgende dag.</p>
                </div>
            </div>

            <div class="pt-6 border-t border-white/5 flex justify-end space-x-4">
                <a href="/admin/lans" class="px-6 py-3 border border-white/10 text-white/50 text-[10px] font-bold uppercase tracking-widest hover:text-white hover:border-white/30 transition-all">
                    Cancel
                </a>
                <button type="submit" class="px-8 py-3 bg-cyber-purple/20 border border-cyber-purple text-cyber-purple hover:bg-cyber-purple hover:text-white font-bold uppercase tracking-widest text-[10px] transition-all shadow-[0_0_15px_rgba(188,19,254,0.2)]">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
