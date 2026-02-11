<div class="bg-cyber-dark/60 p-8 rounded-sm border border-white/5 shadow-2xl">
    <a href="/admin" class="inline-block mb-6 text-[10px] font-orbitron text-cyber-cyan/50 hover:text-cyber-cyan transition-all uppercase tracking-[0.3em]">
        <span class="mr-2">«</span> Back to Command Center
    </a>

    <h2 class="text-white font-orbitron text-2xl mb-8 uppercase italic tracking-widest border-b border-cyber-cyan/30 pb-4 neon-text-cyan">
        LAN Operation Control
    </h2>

    <table class="w-full text-left border-collapse">
        <thead>
        <tr class="text-[11px] font-orbitron text-cyber-cyan/50 uppercase tracking-[0.2em] border-b border-white/10">
            <th class="pb-4 pl-4 w-1/3">Operation & Intel</th>
            <th class="pb-4 w-1/5">Contact & Units</th>
            <th class="pb-4 w-1/5">When</th> <th class="pb-4 text-right pr-4">Status & Control</th>
        </tr>
        </thead>
        <tbody class="text-sm">
        <?php foreach ($parties as $lan): ?>
            <tr class="border-b border-white/5 hover:bg-white/5 transition-colors group">

                <td class="py-6 pl-4">
                    <div class="text-lg text-white font-orbitron tracking-tight group-hover:neon-text-cyan transition-all">
                        <?= htmlspecialchars($lan['name']) ?>
                    </div>

                    <div class="text-xs text-cyber-gray/60 italic mt-2 max-w-md leading-relaxed">
                        <?= htmlspecialchars($lan['description'] ?? 'No intel provided for this mission.') ?>
                    </div>

                    <div class="text-[9px] text-white/40 font-mono mt-3 uppercase tracking-widest">
                        > REQUEST LOGGED: <span class="text-white/70"><?= date('d M Y @ H:i', strtotime($lan['created_at'])) ?></span>
                    </div>
                </td>

                <td class="py-6">
                    <div class="flex flex-col space-y-1">
                        <span class="text-cyber-cyan font-mono text-xs tracking-tighter uppercase">
                            [ <?= htmlspecialchars($lan['contact_email'] ?? 'UNKNOWN_SOURCE') ?> ]
                        </span>
                        <span class="text-[10px] text-cyber-purple font-black tracking-[0.2em] uppercase">
                            <?= $lan['expected_attendees'] ?? 0 ?> Atendees Active
                        </span>
                    </div>
                </td>

                <td class="py-6">
                    <div class="flex flex-col space-y-1">
                        <span class="text-cyber-cyan font-mono text-xs tracking-tighter uppercase">
                            <?= date('d M Y', strtotime($lan['start_date'])) ?>
                        </span>
                        <?php if ($lan['start_date'] !== $lan['end_date']): ?>
                            <span class="text-[10px] text-cyber-purple font-black tracking-[0.2em] uppercase">
                                TO <?= date('d M Y', strtotime($lan['end_date'])) ?>
                            </span>
                        <?php else: ?>
                            <span class="text-[10px] text-cyber-purple font-black tracking-[0.2em] uppercase">
                                1-DAY EVENT
                            </span>
                        <?php endif; ?>
                    </div>
                </td>

                <td class="py-6 text-right pr-4">
                    <div class="flex flex-col items-end space-y-4">
                        <form action="/admin/lans/status" method="POST" class="inline-block relative">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= $lan['id'] ?>">

                            <?php
                            $borderColor = match($lan['status']) {
                                'proposed' => 'border-yellow-500/50 text-yellow-500',
                                'approved' => 'border-cyber-cyan text-cyber-cyan',
                                'declined' => 'border-red-500 text-red-500',
                                default    => 'border-white/20 text-white'
                            };
                            ?>

                            <div class="relative group/select">
                                <select name="status" onchange="this.form.submit()"
                                        class="appearance-none bg-black/60 border-2 <?= $borderColor ?> font-orbitron text-[10px] font-bold py-2 pl-4 pr-10 cursor-pointer focus:outline-none focus:ring-1 focus:ring-white transition-all uppercase tracking-widest shadow-[0_0_10px_rgba(0,0,0,0.5)]">
                                    <option value="proposed" <?= $lan['status'] === 'proposed' ? 'selected' : '' ?>>🟡 Proposed</option>
                                    <option value="approved" <?= $lan['status'] === 'approved' ? 'selected' : '' ?>>🔵 Approved</option>
                                    <option value="declined" <?= $lan['status'] === 'declined' ? 'selected' : '' ?>>🔴 Declined</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 <?= $borderColor ?>">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                    </svg>
                                </div>
                            </div>
                        </form>

                        <a href="/admin/lans/edit?id=<?= $lan['id'] ?>" class="flex items-center px-4 py-2 bg-cyber-purple/10 border border-cyber-purple/50 text-cyber-purple hover:bg-cyber-purple hover:text-white text-[10px] font-bold uppercase tracking-widest transition-all group/edit">
                            <span class="mr-2 group-hover/edit:animate-pulse">✏️</span> Edit Operation
                        </a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>