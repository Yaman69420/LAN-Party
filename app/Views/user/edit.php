<div class="max-w-3xl mx-auto">
    <a href="/profile" class="inline-block mb-6 text-[10px] font-orbitron text-cyber-cyan/50 hover:text-cyber-cyan transition-all uppercase tracking-[0.3em]">
        <span class="mr-2">«</span> Back to Profile
    </a>

    <div class="bg-cyber-dark/60 p-8 rounded-sm border border-cyber-cyan/30 shadow-2xl relative">
        <h2 class="text-white font-orbitron text-2xl mb-10 uppercase italic tracking-widest border-b border-white/10 pb-4 neon-text-cyan">
            Update Operative Identity
        </h2>

        <form action="/profile/update" method="POST" enctype="multipart/form-data" class="space-y-8">
            <?= csrf_field() ?>

            <div class="flex flex-col md:flex-row items-center gap-8 mb-10">
                <div class="w-24 h-24 rounded border-2 border-cyber-cyan p-1 shadow-[0_0_15px_rgba(0,242,255,0.2)] flex-shrink-0 overflow-hidden">
                    <?php
                    $imageName = $user['profile_image'] ?? '';
                    // FIX: rawurlencode zorgt dat spaties in de URL werken
                    $profileSrc = (!empty($imageName))
                        ? '/uploads/avatars/' . rawurlencode($imageName)
                        : 'https://ui-avatars.com/api/?name=' . urlencode($user['username'] ?? 'User') . '&background=0b0c10&color=00f2ff&bold=true';
                    ?>
                    <img src="<?= $profileSrc ?>" class="w-full h-full object-cover" alt="Current Visual ID">
                </div>

                <div class="flex-1 space-y-2">
                    <label class="block text-[10px] text-cyber-cyan uppercase font-bold tracking-widest italic">Update Visual ID Card</label>
                    <input type="file" name="avatar" class="text-[10px] text-white/40 file:bg-cyber-cyan/10 file:border file:border-cyber-cyan/30 file:text-cyber-cyan file:px-4 file:py-2 file:mr-4 file:font-orbitron hover:file:bg-cyber-cyan hover:file:text-black transition-all cursor-pointer">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <div>
                    <label class="block text-[10px] text-cyber-gray uppercase font-bold tracking-widest mb-2 italic">First Name</label>
                    <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" class="w-full bg-black/60 border border-white/10 px-4 py-3 text-white focus:border-cyber-cyan outline-none">
                </div>
                <div>
                    <label class="block text-[10px] text-cyber-gray uppercase font-bold tracking-widest mb-2 italic">Last Name</label>
                    <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>" class="w-full bg-black/60 border border-white/10 px-4 py-3 text-white focus:border-cyber-cyan outline-none">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[10px] text-cyber-gray uppercase font-bold tracking-widest mb-2 italic">Primary Email Address</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required class="w-full bg-black/60 border border-white/10 px-4 py-3 text-white focus:border-cyber-cyan outline-none">
                </div>
            </div>

            <div class="flex items-center justify-between pt-10 border-t border-white/5 mt-10">
                <a href="/profile" class="text-white/40 hover:text-white text-[10px] uppercase font-bold tracking-[0.3em]">[ Abort ]</a>
                <button type="submit" class="bg-cyber-cyan text-black px-10 py-4 font-black text-[11px] uppercase tracking-[0.3em] hover:bg-white transition-all shadow-neon-cyan">
                    Sync Identity Data
                </button>
            </div>
        </form>
    </div>
</div>