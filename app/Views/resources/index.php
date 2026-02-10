<div class="mt-4" x-data="{ search: '', category: 'all' }">
    <div class="mb-6 flex flex-col md:flex-row justify-between items-end gap-4">
        <div>
            <div class="flex items-center space-x-2 text-cyber-cyan mb-2">
                <span class="text-xs">🛡️</span>
                <span class="text-[10px] uppercase font-bold tracking-[0.3em]">Equipment Requisition</span>
            </div>
            <h2 class="text-white font-orbitron text-xl uppercase italic tracking-wider">The Armory</h2>
        </div>
        
        <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
            <!-- Category Filters -->
            <div class="flex space-x-1 bg-black/50 p-1 rounded border border-white/10">
                <?php foreach(['all' => 'ALL', 'hardware' => 'HARDWARE', 'peripheral' => 'GEAR', 'monitor' => 'SCREENS'] as $val => $label): ?>
                    <button 
                        @click="category = '<?= $val ?>'"
                        :class="category === '<?= $val ?>' ? 'bg-cyber-cyan text-black shadow-[0_0_10px_rgba(0,242,255,0.4)]' : 'text-white/50 hover:text-white'"
                        class="px-3 py-1 text-[9px] font-bold uppercase tracking-widest rounded-sm transition-all"
                    >
                        <?= $label ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <!-- Search Input -->
            <div class="relative group">
                <input 
                    type="text" 
                    x-model="search"
                    placeholder="SEARCH.EXE..." 
                    class="bg-black/50 border border-white/10 text-xs text-white px-3 py-1.5 pl-8 rounded-sm focus:border-cyber-cyan focus:outline-none font-mono w-full md:w-48 transition-all group-hover:border-white/30"
                >
                <div class="absolute left-2.5 top-1.5 text-white/30 text-xs">🔍</div>
            </div>
        </div>
    </div>

    <!-- Dashboard-style Container Panel -->
    <div class="bg-cyber-dark/40 border border-white/5 p-6 rounded-sm backdrop-blur-sm relative overflow-visible min-h-[400px]">
        
        <?php if (empty($items)): ?>
            <div class="border border-red-500/30 bg-red-500/5 p-8 rounded-sm text-center">
                <p class="text-red-400 font-orbitron tracking-widest uppercase">No resources available.</p>
                <p class="text-xs text-white/50 mt-2">System administrator notification pending...</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($items as $item): ?>
                    <div 
                        x-show="(category === 'all' || category === '<?= $item->category ?>') && '<?= strtolower($item->name) ?>'.includes(search.toLowerCase())"
                        x-transition.duration.300ms
                        class="group relative bg-[#0b0c10] border border-white/10 hover:border-cyber-cyan/50 hover:shadow-[0_0_20px_rgba(0,242,255,0.15)] transition-all duration-300 overflow-hidden flex flex-col h-full"
                    >
                        
                        <!-- Decorative Top Border -->
                        <div class="absolute top-0 left-0 w-full h-[2px] bg-gradient-to-r from-transparent via-cyber-cyan/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>

                        <div class="p-5 flex-1 flex flex-col relative z-10">
                            <!-- Header: Category & Stock -->
                            <div class="flex justify-between items-start mb-3">
                                <span class="text-[9px] text-cyber-cyan/70 uppercase tracking-widest border border-cyber-cyan/20 px-1.5 py-0.5 rounded-sm">
                                    <?= e($item->category ?? 'GEAR') ?>
                                </span>
                                <span class="text-[9px] <?= $item->total_stock > 0 ? 'text-green-400 shadow-[0_0_5px_rgba(74,222,128,0.4)]' : 'text-red-500' ?> font-mono uppercase tracking-widest">
                                    [<?= $item->total_stock > 0 ? 'ONLINE' : 'OFFLINE' ?>]
                                </span>
                            </div>

                            <!-- Item Name -->
                            <h3 class="text-lg font-orbitron text-white font-bold tracking-wide mb-2 group-hover:text-cyber-cyan transition-colors">
                                <?= e($item->name) ?>
                            </h3>
                            
                            <!-- Divider -->
                            <div class="h-px w-full bg-white/5 my-3 group-hover:bg-gradient-to-r from-cyber-cyan/20 to-transparent transition-colors"></div>

                            <!-- Details -->
                            <div class="mt-auto flex justify-between items-end">
                                <div class="text-[10px] text-white/40 font-mono leading-relaxed">
                                    ID: #<?= sprintf('%03d', $item->id) ?><br>
                                    STOCK: <span class="text-white/80"><?= $item->total_stock ?></span> UNITS
                                </div>
                                
                                <button class="bg-cyber-cyan/10 border border-cyber-cyan text-cyber-cyan hover:bg-cyber-cyan hover:text-black px-4 py-2 text-[10px] font-bold uppercase tracking-[0.15em] transition-all shadow-[0_0_10px_rgba(0,242,255,0.1)] hover:shadow-[0_0_20px_rgba(0,242,255,0.4)]">
                                    RESERVEREN
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <!-- Empty State for Search -->
                <div x-show="$el.parentElement.children.length > 1 && [...$el.parentElement.children].filter(c => c.style.display !== 'none').length === 1" class="col-span-full text-center py-10 text-white/30 hidden">
                    <p class="font-mono text-xs">NO RESULTS FOUND IN ARMORY DATABASE.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
