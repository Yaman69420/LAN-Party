<div class="bg-cyber-dark/60 p-8 rounded-sm border border-white/5 shadow-2xl">
    <a href="/admin" class="inline-block mb-6 text-[10px] font-orbitron text-cyber-cyan/50 hover:text-cyber-cyan transition-all uppercase tracking-[0.3em]">
        <span class="mr-2">«</span> Back to Command Center
    </a>

    <h2 class="text-white font-orbitron text-2xl mb-8 uppercase italic tracking-widest border-b border-cyber-cyan/30 pb-4 neon-text-cyan">
        Openstaande Reserveringen
    </h2>

    <?php if (empty($rentals)): ?>
        <p class="text-cyber-gray/60 italic font-orbitron text-sm">Geen actieve reserveringen in de database.</p>
    <?php else: ?>
        <table class="w-full text-left border-collapse">
            <thead>
            <tr class="text-[11px] font-orbitron text-cyber-cyan/50 uppercase tracking-[0.2em] border-b border-white/10">
                <th class="pb-4 pl-4 w-1/3">Item & Unit</th>
                <th class="pb-4 w-1/4">Timeline</th>
                <th class="pb-4 text-right pr-4">Operation Status</th>
            </tr>
            </thead>
            <tbody class="text-sm">
            <?php foreach ($rentals as $rental): ?>
                <tr class="border-b border-white/5 hover:bg-white/5 transition-colors group">

                    <td class="py-6 pl-4">
                        <div class="text-lg text-white font-orbitron tracking-tight group-hover:neon-text-cyan transition-all">
                            <?= htmlspecialchars($rental->item_name) ?>
                        </div>
                        <div class="text-[10px] text-cyber-purple font-black tracking-[0.2em] uppercase mt-1">
                            [ <?= htmlspecialchars($rental->username) ?> ]
                        </div>
                    </td>

                    <td class="py-6">
                        <div class="flex flex-col space-y-1">
                                <span class="text-cyber-cyan font-mono text-[10px] uppercase tracking-tighter">
                                    Target: <?= !empty($rental->reservation_date) ? date('d-m-Y H:i', strtotime($rental->reservation_date)) : 'IMMEDIATE' ?>
                                </span>
                            <span class="text-[9px] text-cyber-gray/50 font-mono uppercase">
                                    Logged: <?= date('d-m-Y H:i', strtotime($rental->created_at)) ?>
                                </span>
                        </div>
                    </td>

                    <td class="py-6 text-right pr-4">
                        <div class="flex flex-col items-end">
                            <form action="/admin/reservations/update" method="POST" class="inline-block relative">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= $rental->id ?>">

                                <?php
                                $borderColor = match($rental->rental_status) {
                                    'pending'  => 'border-yellow-500/50 text-yellow-500',
                                    'reserved' => 'border-cyber-cyan text-cyber-cyan',
                                    'declined' => 'border-red-500 text-red-500',
                                    default    => 'border-white/20 text-white'
                                };
                                ?>

                                <div class="relative group/select">
                                    <select name="status" onchange="this.form.submit()"
                                            class="appearance-none bg-black/60 border-2 <?= $borderColor ?> font-orbitron text-[10px] font-bold py-2 pl-4 pr-10 cursor-pointer focus:outline-none focus:ring-1 focus:ring-white transition-all uppercase tracking-widest shadow-[0_0_10px_rgba(0,0,0,0.5)]">
                                        <option value="pending" <?= $rental->rental_status === 'pending' ? 'selected' : '' ?>>🟡 Pending</option>
                                        <option value="reserved" <?= $rental->rental_status === 'reserved' ? 'selected' : '' ?>>🔵 Approved</option>
                                        <option value="declined" <?= $rental->rental_status === 'declined' ? 'selected' : '' ?>>🔴 Declined</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 <?= $borderColor ?>">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                        </svg>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>