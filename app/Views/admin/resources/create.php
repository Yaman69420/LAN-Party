<div class="max-w-2xl mx-auto p-6 bg-white/5 rounded-lg border border-white/10">
    <h1 class="text-2xl font-bold text-white mb-6">Nieuw Item Toevoegen</h1>

    <form action="/admin/resources/create" method="POST" class="space-y-4">
        <?= csrf_field() ?>

        <div>
            <label class="block text-sm text-gray-400 mb-1">Naam Item</label>
            <input type="text" name="name" required class="w-full bg-black/50 border border-white/20 rounded p-2 text-white focus:border-cyber-cyan focus:outline-none">
        </div>

        <div>
            <label class="block text-sm text-gray-400 mb-1">Categorie</label>
            <select name="category" class="w-full bg-black/50 border border-white/20 rounded p-2 text-white focus:border-cyber-cyan focus:outline-none">
                <option value="hardware">Hardware</option>
                <option value="peripheral">Peripheral</option>
                <option value="monitor">Monitor</option>
                <option value="other">Overig</option>
            </select>
        </div>

        <div>
            <label class="block text-sm text-gray-400 mb-1">Totale Voorraad</label>
            <input type="number" name="total_stock" value="1" min="0" required class="w-24 bg-black/50 border border-white/20 rounded p-2 text-white focus:border-cyber-cyan focus:outline-none">
        </div>

        <div class="flex justify-end gap-2 pt-4">
            <a href="/admin/resources" class="px-4 py-2 text-gray-400 hover:text-white">Annuleren</a>
            <button type="submit" class="bg-cyber-cyan text-black font-bold py-2 px-6 rounded hover:bg-cyber-cyan/80">Opslaan</button>
        </div>
    </form>
</div>
