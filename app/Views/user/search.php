<div class="bg-cyber-dark/60 p-8 rounded-sm border border-white/5 shadow-2xl">
    <h2 class="font-orbitron text-white text-xl tracking-widest uppercase mb-6 italic">Find Operatives</h2>

    <form action="/user/search" method="GET" class="mb-10 flex space-x-4">
        <input type="text" name="q" value="<?= e($query ?? '') ?>"
               placeholder="Enter username..."
               class="flex-1 bg-black/40 border border-white/10 px-4 py-2 text-white font-rajdhani focus:border-cyber-cyan outline-none transition-all">
        <button type="submit" class="bg-cyber-cyan/10 border border-cyber-cyan text-cyber-cyan px-6 py-2 uppercase font-bold text-xs hover:bg-cyber-cyan hover:text-black transition-all">
            Scan
        </button>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if(empty($results) && !empty($query)): ?>
            <p class="text-white/20 italic col-span-full">No operatives found matching your criteria.</p>
        <?php elseif(!empty($results)): ?>
            <?php foreach($results as $row): ?>
                <div class="p-4 bg-white/5 border border-white/5 flex items-center justify-between group hover:border-cyber-purple transition-all">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 border border-cyber-cyan/30 p-0.5">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($row['username']) ?>&background=0b0c10&color=00f2ff" alt="Avatar">
                        </div>
                        <span class="text-white font-bold tracking-wide"><?= e($row['username']) ?></span>
                    </div>
                    <a href="/user/profile/<?= e($row['slug']) ?>" class="text-[10px] text-cyber-purple border border-cyber-purple/30 px-3 py-1 hover:bg-cyber-purple hover:text-white transition-all uppercase font-black">
                        View Intel
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-white/20 italic col-span-full">Enter a username above to start the scan.</p>
        <?php endif; ?>
    </div>
</div>