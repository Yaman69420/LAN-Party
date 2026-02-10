<div class="max-w-2xl mx-auto p-6 bg-white/5 rounded-lg border border-white/10">
    <h1 class="text-2xl font-bold text-white mb-6">Item Bewerken: <?= e($item->name) ?></h1>

    <form action="/admin/resources/edit" method="POST" class="space-y-4">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= $item->id ?>">

        <div>
            <label class="block text-sm text-gray-400 mb-1">Naam Item</label>
            <input type="text" name="name" value="<?= e($item->name) ?>" required class="w-full bg-black/50 border border-white/20 rounded p-2 text-white focus:border-cyber-cyan focus:outline-none">
        </div>

        <div>
            <label class="block text-sm text-gray-400 mb-1">Categorie</label>
            <select name="category" class="w-full bg-black/50 border border-white/20 rounded p-2 text-white focus:border-cyber-cyan focus:outline-none">
                <option value="hardware" <?= $item->category === 'hardware' ? 'selected' : '' ?>>Hardware</option>
                <option value="peripheral" <?= $item->category === 'peripheral' ? 'selected' : '' ?>>Peripheral</option>
                <option value="monitor" <?= $item->category === 'monitor' ? 'selected' : '' ?>>Monitor</option>
                <option value="other" <?= $item->category === 'other' ? 'selected' : '' ?>>Overig</option>
            </select>
        </div>

        <div>
            <label class="block text-sm text-gray-400 mb-1">Totale Voorraad</label>
            <input type="number" name="total_stock" value="<?= $item->total_stock ?>" min="0" required class="w-24 bg-black/50 border border-white/20 rounded p-2 text-white focus:border-cyber-cyan focus:outline-none">
        </div>

        <div class="flex justify-end gap-2 pt-4">
            <a href="/admin/resources" class="px-4 py-2 text-gray-400 hover:text-white">Annuleren</a>
            <button type="submit" class="bg-cyber-cyan text-black font-bold py-2 px-6 rounded hover:bg-cyber-cyan/80">Updaten</button>
        </div>
    </form>
</div>
