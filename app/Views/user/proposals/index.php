<div class="max-w-4xl mx-auto" x-data>
    <?php if (($_GET['status'] ?? '') === 'joined'): ?>
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
         class="fixed bottom-10 right-10 z-50 bg-[#0b0c10] border-l-4 border-cyber-cyan text-white px-6 py-4 rounded-sm shadow-[0_0_20px_rgba(0,242,255,0.3)] flex items-center gap-4">
        <div class="text-xl">📡</div>
        <div>
            <h4 class="font-orbitron text-sm font-bold uppercase tracking-wider text-cyber-cyan">Uplink Established</h4>
            <p class="font-mono text-xs text-white/70">You have successfully joined the operation.</p>
        </div>
        <button @click="show = false" class="text-white/20 hover:text-white">&times;</button>
    </div>
    <?php endif; ?>

    <div class="mb-8 border-b border-white/5 pb-4 flex justify-between items-end">
        <div>
            <div class="flex items-center space-x-2 text-cyber-cyan mb-2">
                <span class="text-xs">📡</span>
                <span class="text-[10px] uppercase font-bold tracking-[0.3em]">Active Transmissions</span>
            </div>
            <h2 class="text-white font-orbitron text-3xl uppercase italic tracking-wider">Open Proposals</h2>
            <p class="text-cyber-gray text-xs mt-2">Stem op jouw favoriete LAN-party ideeën of sluit je aan bij een operatie.</p>
        </div>
        <a href="/propose" class="group flex items-center px-4 py-2 border border-cyber-purple/50 bg-cyber-purple/10 hover:bg-cyber-purple/20 transition-all rounded-sm">
            <span class="mr-2 text-lg text-cyber-purple group-hover:text-white transition-colors">+</span>
            <span class="text-[10px] font-bold uppercase tracking-widest text-cyber-purple group-hover:text-white transition-colors">New Proposal</span>
        </a>
    </div>

    <!-- Active Proposals Section -->
    <div class="">
        <?php if (empty($proposals)): ?>
            <div class="text-center p-12 border border-white/5 border-dashed rounded-sm bg-white/5">
                <p class="text-white/30 font-mono text-xs uppercase tracking-widest mb-4">No active signals detected.</p>
                <a href="/propose" class="text-cyber-cyan hover:text-white text-xs font-bold underline decoration-cyber-cyan underline-offset-4">Initiate Protocol &rarr;</a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 gap-6">
                <?php foreach ($proposals as $prop): ?>
                    <div class="bg-cyber-dark/60 border border-white/10 p-6 rounded-sm hover:border-cyber-cyan/30 transition-all group relative overflow-hidden">
                        <!-- Glow effect -->
                        <div class="absolute -left-1 top-0 bottom-0 w-1 bg-cyber-cyan/20 group-hover:bg-cyber-cyan transition-colors"></div>

                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-xl text-white font-orbitron tracking-wide mb-1 group-hover:text-cyber-cyan transition-colors">
                                    <?= htmlspecialchars($prop['name']) ?>
                                </h3>
                                <p class="text-[10px] text-cyber-purple uppercase tracking-widest mb-3">
                                    Operator: <?= htmlspecialchars($prop['organizer'] ?? 'Unknown') ?>
                                </p>
                                <p class="text-sm text-gray-400 font-rajdhani mb-4 max-w-2xl">
                                    <?= nl2br(htmlspecialchars($prop['description'])) ?>
                                </p>
                                
                                <!-- Meta Data -->
                                <div class="flex space-x-6 text-[10px] uppercase tracking-wider text-white/40 font-mono">
                                    <span class="flex items-center"><span class="mr-2 text-cyber-cyan">📅</span> <?= date('d M Y', strtotime($prop['start_date'])) ?></span>
                                    <span class="flex items-center"><span class="mr-2 text-cyber-cyan">👥</span> Target: <?= $prop['expected_attendees'] ?> Units</span>
                                </div>
                            </div>

                            <!-- Vote / Join Action -->
                            <div class="text-right">
                                <?php if ($prop['has_voted']): ?>
                                    <form action="/proposals/unjoin" method="POST">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="proposal_id" value="<?= $prop['id'] ?>">
                                        <button type="submit" class="px-6 py-2 bg-red-500/10 border border-red-500/50 text-red-500 hover:bg-red-500 hover:text-white font-bold uppercase tracking-widest text-[10px] transition-all">
                                            × Abort Uplink
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <form action="/proposals/join" method="POST">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="proposal_id" value="<?= $prop['id'] ?>">
                                        <button type="submit" class="px-6 py-2 bg-cyber-cyan/10 border border-cyber-cyan text-cyber-cyan hover:bg-cyber-cyan hover:text-black font-bold uppercase tracking-widest text-[10px] transition-all">
                                            + Establish Uplink
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Attendees List -->
                        <div class="mt-6 pt-4 border-t border-white/5">
                            <p class="text-[9px] text-white/30 uppercase tracking-widest mb-3">
                                Confirmed Uplinks (<?= count($prop['votes']) ?>)
                            </p>
                            <div class="flex flex-wrap gap-2">
                                <?php foreach ($prop['votes'] as $voter): ?>
                                    <a href="/user/profile?id=<?= $voter['id'] ?>" class="group/user relative">
                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($voter['username']) ?>&background=random&color=fff&size=32" 
                                             class="w-8 h-8 rounded border border-white/10 group-hover/user:border-cyber-cyan transition-colors" 
                                             alt="<?= htmlspecialchars($voter['username']) ?>"
                                             title="<?= htmlspecialchars($voter['username']) ?>">
                                    </a>
                                <?php endforeach; ?>
                                <?php if (empty($prop['votes'])): ?>
                                    <span class="text-[9px] text-white/20 italic">Awaiting signals...</span>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
