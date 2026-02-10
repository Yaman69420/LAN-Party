<div class="bg-cyber-dark/60 p-8 rounded-sm border border-white/5 shadow-2xl">
    <a href="/admin" class="inline-block mb-6 text-[10px] font-orbitron text-cyber-cyan/50 hover:text-cyber-cyan transition-all uppercase tracking-[0.3em]">
        <span class="mr-2">«</span> Back to Command Center
    </a>

    <div class="flex justify-between items-center mb-8 border-b border-yellow-500/30 pb-4">
        <h2 class="text-white font-orbitron text-2xl uppercase italic tracking-widest text-yellow-500 shadow-sm">
            Tactical Inventory
        </h2>
        <a href="/admin/resources/create" class="bg-yellow-500/10 border border-yellow-500 text-yellow-500 font-orbitron text-[10px] py-2 px-6 rounded-sm hover:bg-yellow-500 hover:text-black transition-all tracking-widest font-black">
            + DEPLOY NEW ITEM
        </a>
    </div>

    <table class="w-full text-left border-collapse">
        <thead>
        <tr class="text-[11px] font-orbitron text-yellow-500/50 uppercase tracking-[0.2em] border-b border-white/10">
            <th class="pb-4 pl-4">Asset ID</th>
            <th class="pb-4">Description</th>
            <th class="pb-4">Classification</th>
            <th class="pb-4 text-center">Stock Level</th>
            <th class="pb-4 text-right pr-4">Authorization</th>
        </tr>
        </thead>
        <tbody class="text-sm">
        <?php foreach ($items as $item): ?>
            <tr class="border-b border-white/5 hover:bg-white/5 transition-colors group">
                <td class="py-6 pl-4 font-mono text-xs text-gray-600">
                    #<?= str_pad((string)$item->id, 3, '0', STR_PAD_LEFT) ?>
                </td>

                <td class="py-6 text-white font-orbitron tracking-tight group-hover:text-yellow-500 transition-all text-lg">
                    <?= htmlspecialchars($item->name) ?>
                </td>

                <td class="py-6">
                    <span class="text-[10px] text-cyber-gray uppercase tracking-widest">
                        <?= htmlspecialchars($item->category ?? 'UNCLASSIFIED') ?>
                    </span>
                </td>

                <td class="py-6 text-center font-mono">
                    <span class="<?= $item->total_stock < 5 ? 'text-red-500 animate-pulse' : 'text-cyber-cyan' ?>">
                        <?= $item->total_stock ?> UNITS
                    </span>
                </td>

                <td class="py-6 text-right pr-4">
                    <div class="flex justify-end space-x-6 items-center">
                        <a href="/admin/resources/edit/<?= e($item->slug) ?>" class="text-[10px] font-black uppercase text-white/40 hover:text-white transition-colors tracking-widest">
                            Edit
                        </a>

                        <form action="/admin/resources/delete" method="POST" class="inline" onsubmit="return confirm('[ ALERT ] Permanent deletion sequence. Proceed?');">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= $item->id ?>">
                            <button type="submit" class="text-[10px] font-black uppercase text-red-500 hover:text-red-400 transition-colors tracking-widest">
                                SCRAP
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>

        <?php if (empty($items)): ?>
            <tr>
                <td colspan="5" class="p-12 text-center">
                    <p class="font-orbitron text-xs text-white/20 uppercase tracking-[0.5em]">Inventory empty. No assets found in the Armory.</p>
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>