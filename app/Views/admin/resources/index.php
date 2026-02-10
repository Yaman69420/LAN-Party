<div class="max-w-4xl mx-auto p-6 bg-white/5 rounded-lg border border-white/10">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">Resource Management</h1>
        <a href="/admin/resources/create" class="bg-cyber-cyan text-black font-bold py-2 px-4 rounded hover:bg-cyber-cyan/80 transition-colors">
            + Nieuw Item
        </a>
    </div>

    <table class="w-full text-left text-sm text-gray-400">
        <thead class="bg-white/5 uppercase text-xs">
            <tr>
                <th class="p-3">ID</th>
                <th class="p-3">Naam</th>
                <th class="p-3">Categorie</th>
                <th class="p-3 text-center">Voorraad</th>
                <th class="p-3 text-right">Acties</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/10">
            <?php foreach ($items as $item): ?>
            <tr class="hover:bg-white/5 transition-colors">
                <td class="p-3 font-mono text-xs text-gray-500">#<?= $item->id ?></td>
                <td class="p-3 text-white font-medium"><?= e($item->name) ?></td>
                <td class="p-3"><?= e($item->category ?? '-') ?></td>
                <td class="p-3 text-center"><?= $item->total_stock ?></td>
                <td class="p-3 text-right space-x-2">
                    <a href="/admin/resources/edit?id=<?= $item->id ?>" class="text-cyber-cyan hover:underline">Bewerk</a>
                    <form action="/admin/resources/delete" method="POST" class="inline" onsubmit="return confirm('Weet je het zeker?');">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= $item->id ?>">
                        <button type="submit" class="text-red-500 hover:underline">Verwijder</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            
            <?php if (empty($items)): ?>
            <tr>
                <td colspan="5" class="p-6 text-center italic text-gray-600">Nog geen resources gevonden.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
