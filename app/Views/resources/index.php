<div class="mt-4" x-data="{ search: '', category: 'all', showModal: false, selectedItem: null }">
    <!-- Success Notification -->
    <?php if (($_GET['status'] ?? '') === 'success'): ?>
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
         class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50 bg-[#0b0c10] border border-green-500/50 text-green-400 px-8 py-6 rounded-sm shadow-[0_0_50px_rgba(74,222,128,0.4)] flex flex-col items-center gap-4 backdrop-blur-md text-center">
        <div class="text-4xl mb-2">✅</div>
        <div>
            <h4 class="font-orbitron text-lg font-bold uppercase tracking-wider mb-1">PROTOCOL INITIATED</h4>
            <p class="font-mono text-sm text-white/70">Requisition request transmitted to command.</p>
        </div>
        <button @click="show = false" class="text-xs uppercase tracking-widest text-white/30 hover:text-white mt-2">[ DISMISS ]</button>
    </div>
    <?php endif; ?>

    <!-- Error Notification -->
    <?php if (($_GET['status'] ?? '') === 'error'): ?>
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
         class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50 bg-[#0b0c10] border border-red-500/50 text-red-400 px-8 py-6 rounded-sm shadow-[0_0_50px_rgba(239,68,68,0.4)] flex flex-col items-center gap-4 backdrop-blur-md text-center">
        <div class="text-4xl mb-2">🚫</div>
        <div>
            <h4 class="font-orbitron text-lg font-bold uppercase tracking-wider mb-1">ACCESS DENIED</h4>
            <p class="font-mono text-sm text-white/70">
                <?php 
                    $msg = $_GET['message'] ?? 'Unknown error';
                    if ($msg === 'out_of_stock') echo 'Resource depleted. Requisition failed.';
                    else echo htmlspecialchars($msg);
                ?>
            </p>
        </div>
        <button @click="show = false" class="text-xs uppercase tracking-widest text-white/30 hover:text-white mt-2">[ DISMISS ]</button>
    </div>
    <?php endif; ?>

    <!-- Reservation Modal -->
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="showModal = false"></div>
        <div class="relative bg-[#0b0c10] border border-white/10 p-8 rounded-sm max-w-md w-full shadow-[0_0_50px_rgba(0,0,0,0.8)] overflow-hidden">
            
            <!-- Tech Corners -->
            <div class="absolute top-0 left-0 w-2 h-2 border-t border-l border-cyber-cyan"></div>
            <div class="absolute top-0 right-0 w-2 h-2 border-t border-r border-cyber-cyan"></div>
            <div class="absolute bottom-0 left-0 w-2 h-2 border-b border-l border-cyber-cyan"></div>
            <div class="absolute bottom-0 right-0 w-2 h-2 border-b border-r border-cyber-cyan"></div>

            <h3 class="text-xl font-orbitron text-white mb-6 uppercase tracking-wider text-center">
                <span class="text-cyber-cyan">Requisition</span> Protocol
            </h3>

            <p class="text-gray-400 mb-8 font-mono text-xs text-center">
                You are about to requisition item:<br>
                <span class="text-white font-bold text-sm mt-1 block" x-text="selectedItem?.name"></span>
            </p>
            
            <form action="/rentals/store" method="POST" class="space-y-6">
                <?= csrf_field() ?>
                <input type="hidden" name="item_id" :value="selectedItem?.id">
                
                <!-- LAN Party Selection -->
                <div>
                    <label class="block text-[10px] text-cyber-cyan uppercase tracking-widest mb-2">Target Mission (LAN)</label>
                    <select name="lan_party_id" required 
                           class="w-full bg-[#1a1c23] border border-white/10 text-white px-4 py-3 focus:border-cyber-cyan focus:outline-none transition-all font-mono text-sm uppercase appearance-none">
                        <?php if (empty($upcomingLans)): ?>
                            <option value="" disabled selected>NO ACTIVE MISSIONS</option>
                        <?php else: ?>
                            <?php foreach ($upcomingLans as $l): ?>
                                <option value="<?= $l['id'] ?>">
                                    <?= htmlspecialchars($l['name']) ?> [<?= date('d M', strtotime($l['start_date'])) ?>]
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Quantity -->
                <div>
                    <label class="block text-[10px] text-cyber-cyan uppercase tracking-widest mb-2">Quantity</label>
                    <input type="number" name="quantity" value="1" min="1" max="5" required 
                           class="w-full bg-[#1a1c23] border border-white/10 text-white px-4 py-3 focus:border-cyber-cyan focus:outline-none transition-all font-mono text-sm">
                </div>

                <!-- Date/Time (Optional specific time) -->

                <div class="pt-2 space-y-3">
                    <button type="submit"
                            class="w-full group relative px-6 py-4 bg-cyber-cyan/10 border border-cyber-cyan hover:bg-cyber-cyan/20 transition-all duration-300 overflow-hidden">
                        <div class="absolute inset-0 w-0 bg-cyber-cyan transition-all duration-[250ms] ease-out group-hover:w-full opacity-10"></div>
                        <span class="relative text-cyber-cyan font-orbitron uppercase tracking-[0.2em] group-hover:text-white transition-colors font-bold text-xs">
                            Initialize Requisition
                        </span>
                    </button>
                    
                    <button type="button" @click="showModal = false" class="w-full text-[10px] text-white/30 hover:text-red-400 uppercase tracking-widest transition-colors py-2">
                        [ Abort Sequence ]
                    </button>
                </div>
            </form>
        </div>
    </div>
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
                                
                                <button 
                                    @click="selectedItem = { id: <?= $item->id ?>, name: '<?= e($item->name) ?>' }; showModal = true"
                                    class="bg-cyber-cyan/10 border border-cyber-cyan text-cyber-cyan hover:bg-cyber-cyan hover:text-black px-4 py-2 text-[10px] font-bold uppercase tracking-[0.15em] transition-all shadow-[0_0_10px_rgba(0,242,255,0.1)] hover:shadow-[0_0_20px_rgba(0,242,255,0.4)]"
                                >
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
