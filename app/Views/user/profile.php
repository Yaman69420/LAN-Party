<?php if ($isOwnProfile && !empty($pendingRequests)): ?>
    <div class="mb-8 bg-cyber-purple/10 border border-cyber-purple p-4 rounded-sm shadow-[0_0_15px_rgba(188,19,254,0.1)]">
        <h3 class="text-cyber-purple font-orbitron text-xs font-black tracking-widest uppercase mb-4 italic">
            ⚠️ Incoming Squad Requests
        </h3>
        <div class="space-y-3">
            <?php foreach ($pendingRequests as $request): ?>
                <div class="flex items-center justify-between bg-black/40 p-3 border border-white/5">
                    <span class="text-white text-sm font-bold tracking-wide">
                        <?= e($request['username']) ?> wants to join your squad.
                    </span>
                    <div class="flex space-x-2">
                        <form action="/user/accept-friend" method="POST">
                            <?= csrf_field() ?>
                            <input type="hidden" name="request_id" value="<?= $request['request_id'] ?>">
                            <button type="submit" class="bg-cyber-cyan text-black text-[9px] font-black px-4 py-2 uppercase tracking-tighter hover:bg-white transition-all shadow-[0_0_10px_rgba(0,242,255,0.3)]">
                                Accept
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <div class="bg-cyber-dark/60 p-6 rounded-sm border border-white/5 shadow-2xl h-fit">
        <div class="flex flex-col items-center text-center">
            <div class="w-24 h-24 rounded border border-cyber-cyan p-1 shadow-[0_0_15px_rgba(0,242,255,0.2)] mb-4">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['username']) ?>&background=0b0c10&color=00f2ff&bold=true" class="rounded w-full h-full object-cover" alt="Avatar">
            </div>
            <h2 class="font-orbitron text-xl text-white tracking-widest uppercase italic"><?= e($user['username']) ?></h2>
            <p class="text-[10px] text-cyber-cyan font-bold tracking-[0.3em] uppercase mt-2 opacity-80">
                Status: <?= $isOwnProfile ? 'COMMANDER' : 'OPERATIVE' ?>
            </p>

            <?php if (!$isOwnProfile && !$friendshipStatus): ?>
                <form action="/user/add-friend" method="POST" class="mt-4 w-full">
                    <?= csrf_field() ?>
                    <input type="hidden" name="friend_id" value="<?= $user['id'] ?>">
                    <button type="submit" class="w-full py-2 bg-cyber-purple/20 border border-cyber-purple text-cyber-purple text-[10px] font-black uppercase tracking-widest hover:bg-cyber-purple hover:text-white transition-all">
                        Send Friend Request
                    </button>
                </form>
            <?php elseif (!$isOwnProfile && $friendshipStatus === 'pending'): ?>
                <div class="mt-4 w-full py-2 border border-yellow-500/50 text-yellow-500 text-[10px] uppercase font-bold text-center">
                    Request Pending
                </div>
            <?php endif; ?>
        </div>

        <div class="mt-10 space-y-6">
            <div>
                <h3 class="text-[10px] text-cyber-purple font-black tracking-[0.2em] uppercase mb-4 border-b border-white/5 pb-2">Squad Members</h3>
                <div class="grid grid-cols-4 gap-2">
                    <?php if(empty($friends)): ?>
                        <p class="col-span-4 text-[10px] text-white/20 italic">No squad members found.</p>
                    <?php else: ?>
                        <?php foreach($friends as $friend): ?>
                            <a href="/user/profile/<?= e($friend['slug']) ?>" title="<?= e($friend['username']) ?>">
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($friend['username']) ?>&background=12141a&color=bc13fe" class="w-10 h-10 border border-white/10 hover:border-cyber-purple transition-all" alt="Friend">
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <a href="/user/search" class="block w-full text-center py-2 border border-cyber-cyan/30 text-cyber-cyan text-[10px] font-bold uppercase tracking-widest hover:bg-cyber-cyan/10 transition-all">
                Search Operatives
            </a>
        </div>
    </div>

    <div class="lg:col-span-2 space-y-6">
        <div class="bg-cyber-dark/60 p-8 rounded-sm border border-white/5 shadow-2xl relative overflow-hidden">
            <h3 class="font-orbitron text-white text-md tracking-widest uppercase italic mb-6 flex items-center">
                <span class="w-2 h-2 bg-cyber-cyan rounded-full mr-3 animate-pulse"></span>
                Upcoming Missions
            </h3>

            <div class="space-y-4">
                <?php if(!$isOwnProfile && $friendshipStatus !== 'accepted'): ?>
                    <p class="text-xs text-white/20 italic uppercase tracking-widest text-center py-4 border border-white/5">Intel classified. Become friends to view missions.</p>
                <?php elseif(empty($upcoming)): ?>
                    <p class="text-xs text-white/20 uppercase tracking-widest text-center py-4 border border-white/5">No active deployments found</p>
                <?php else: ?>
                    <?php foreach($upcoming as $lan): ?>
                        <div class="flex justify-between items-center p-4 bg-black/20 border-l-2 border-cyber-cyan">
                            <div>
                                <h4 class="text-white font-bold uppercase text-sm"><?= e($lan['name']) ?></h4>
                                <p class="text-[10px] text-cyber-gray opacity-60 uppercase"><?= date('d M Y', strtotime($lan['start_date'])) ?></p>
                            </div>
                            <span class="text-[10px] font-mono <?= $lan['status'] === 'approved' ? 'text-cyber-cyan' : 'text-cyber-purple' ?>">
                                [ <?= strtoupper($lan['status']) ?> ]
                            </span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <?php if($isOwnProfile || $friendshipStatus === 'accepted'): ?>
            <div class="bg-cyber-dark/40 p-8 rounded-sm border border-white/5 opacity-70">
                <h3 class="font-orbitron text-white/50 text-md tracking-widest uppercase italic mb-6">Archived Logs</h3>
                <div class="space-y-2">
                    <?php if(empty($history)): ?>
                        <p class="text-[10px] text-white/20 italic">No archived missions.</p>
                    <?php else: ?>
                        <?php foreach($history as $lan): ?>
                            <div class="flex justify-between items-center p-3 border-b border-white/5">
                                <span class="text-xs text-white/40 uppercase"><?= e($lan['name']) ?></span>
                                <span class="text-[9px] font-mono text-white/20"><?= date('Y', strtotime($lan['start_date'])) ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>