<div class="max-w-2xl mx-auto bg-cyber-dark/60 p-8 rounded-sm border border-yellow-500/30 shadow-[0_0_20px_rgba(234,179,8,0.1)]">
    <h2 class="text-white font-orbitron text-2xl mb-8 uppercase italic tracking-widest border-b border-white/10 pb-4 text-yellow-500">
        Register New Asset
    </h2>

    <form action="/admin/resources/create" method="POST" class="space-y-6">
        <?= csrf_field() ?>

        <div>
            <label class="block text-[10px] text-yellow-500 uppercase font-bold tracking-[0.2em] mb-2">Asset Designation (Name)</label>
            <input type="text" name="name" required
                   placeholder="e.g. RTX 4090 - Unit A1"
                   class="w-full bg-black/40 border border-white/10 px-4 py-3 text-white focus:border-yellow-500 outline-none transition-all font-mono placeholder:text-white/10">
        </div>

        <div>
            <label class="block text-[10px] text-yellow-500 uppercase font-bold tracking-[0.2em] mb-2">Classification</label>
            <div class="relative">
                <select name="category"
                        class="w-full bg-black/40 border border-white/10 px-4 py-3 text-white focus:border-yellow-500 outline-none cursor-pointer appearance-none font-bold text-xs uppercase tracking-widest">
                    <option value="hardware">Hardware</option>
                    <option value="peripheral">Peripheral</option>
                    <option value="monitor">Monitor</option>
                    <option value="other">Other / Tactical</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-yellow-500">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                </div>
            </div>
        </div>

        <div>
            <label class="block text-[10px] text-yellow-500 uppercase font-bold tracking-[0.2em] mb-2">Initial Stock Quantity</label>
            <input type="number" name="total_stock" value="1" min="0" required
                   class="w-32 bg-black/40 border border-white/10 px-4 py-3 text-white focus:border-yellow-500 outline-none transition-all font-mono text-center text-lg">
        </div>

        <div class="flex items-center justify-between pt-6 border-t border-white/5">
            <a href="/admin/resources" class="text-white/40 hover:text-white text-[10px] uppercase font-bold tracking-widest transition-colors">
                [ Abort Registration ]
            </a>
            <button type="submit" class="bg-yellow-500 text-black px-8 py-3 uppercase font-black text-xs tracking-[0.2em] hover:bg-white transition-all shadow-[0_0_15px_rgba(234,179,8,0.3)]">
                Deploy Asset
            </button>
        </div>
    </form>
</div>