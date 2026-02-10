<?php
// Dynamische datuminstelling voor Februari 2026
$month = 2;
$year = 2026;
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$firstDayOfMonth = date('w', strtotime("$year-$month-01"));

// Hulpfunctie voor veiligheid (als e() niet bestaat)
if (!function_exists('safe')) {
    function safe($value) {
        return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
    }
}
?>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<div class="mt-4" x-data="{ selectedDate: null }">
    <div class="mb-6 flex justify-between items-end">
        <div>
            <div class="flex items-center space-x-2 text-cyber-purple mb-2">
                <span class="text-xs">🎮</span>
                <span class="text-[10px] uppercase font-bold tracking-[0.3em]">Operational Overview</span>
            </div>
            <h2 class="text-white font-orbitron text-xl uppercase italic tracking-wider">Februari 2026</h2>
        </div>
    </div>

    <div class="bg-cyber-dark/40 border border-white/5 p-6 rounded-sm backdrop-blur-sm relative overflow-visible">

        <div class="grid grid-cols-7 gap-4 mb-4 text-center">
            <?php foreach(['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'] as $dayName): ?>
                <span class="text-[10px] font-orbitron text-white/20 uppercase tracking-[0.2em]"><?= $dayName ?></span>
            <?php endforeach; ?>
        </div>

        <div class="grid grid-cols-7 gap-3">
            <?php for ($i = 0; $i < $firstDayOfMonth; $i++): ?>
                <div class="h-20 bg-white/5 opacity-20 rounded-sm"></div>
            <?php endfor; ?>

            <?php for ($day = 1; $day <= $daysInMonth; $day++):
                $dateKey = sprintf('%04d-%02d-%02d', $year, $month, $day);

                // Data ophalen (Checkt of 'parties' bestaat, anders lege array)
                $party = isset($parties[$dateKey]) ? $parties[$dateKey] : null;
                $hasParty = ($party !== null);

                // Veilige titel (pakt title OF name)
                $partyTitle = $hasParty ? ($party['title'] ?? $party['name'] ?? 'Event') : '';

                // Styling logic
                $glowClass = '';
                if ($hasParty) {
                    // Wissel af tussen paars en cyaan voor effect
                    $glowClass = ($day % 2 === 0)
                            ? 'border-cyber-purple bg-cyber-purple/10 shadow-[0_0_15px_rgba(188,19,254,0.15)]'
                            : 'border-cyber-cyan bg-cyber-cyan/10 shadow-[0_0_15px_rgba(0,242,255,0.15)]';
                } else {
                    $glowClass = 'border-white/5 hover:border-white/20 hover:bg-white/5';
                }
                ?>
                <div
                        @click="<?= $hasParty ? "selectedDate = (selectedDate === '$dateKey' ? null : '$dateKey')" : "" ?>"
                        class="relative h-20 border rounded-sm transition-all duration-300 p-2 cursor-pointer group flex flex-col justify-between <?= $glowClass ?>">

                    <span class="font-orbitron text-xs <?= $hasParty ? 'text-white font-bold' : 'text-white/20' ?>">
                        <?= $day ?>
                    </span>

                    <?php if ($hasParty): ?>
                        <div class="text-[9px] leading-tight uppercase font-bold truncate w-full <?= ($day % 2 === 0) ? 'text-cyber-purple' : 'text-cyber-cyan' ?>">
                            <?= safe($partyTitle) ?>
                        </div>
                        <div class="absolute top-2 right-2 w-1.5 h-1.5 rounded-full <?= ($day % 2 === 0) ? 'bg-cyber-purple shadow-[0_0_5px_#bc13fe]' : 'bg-cyber-cyan shadow-[0_0_5px_#00f2ff]' ?>"></div>
                    <?php endif; ?>

                    <?php if ($hasParty): ?>
                        <template x-if="selectedDate === '<?= $dateKey ?>'">
                            <div class="absolute z-50 top-full left-1/2 -translate-x-1/2 mt-2 w-64 bg-[#0b0c10] border border-cyber-cyan p-4 shadow-[0_0_50px_rgba(0,0,0,1)] rounded-sm ring-1 ring-white/10" @click.away="selectedDate = null">

                                <div class="flex justify-between items-start mb-3 border-b border-white/10 pb-2">
                                    <div>
                                        <h3 class="text-xs font-orbitron text-cyber-cyan uppercase italic font-bold"><?= safe($partyTitle) ?></h3>
                                        <p class="text-[9px] text-white/50 uppercase mt-1">📍 <?= safe($party['location'] ?? 'Locatie TBD') ?></p>
                                    </div>
                                    <button @click.stop="selectedDate = null" class="text-white/20 hover:text-white transition-colors">&times;</button>
                                </div>

                                <div class="space-y-2">
                                    <p class="text-[9px] text-cyber-purple font-bold uppercase tracking-widest text-left">Gereserveerd Materiaal:</p>
                                    <?php if (!empty($party['items'])): ?>
                                        <div class="bg-white/5 p-2 rounded border border-white/5">
                                            <?php foreach ($party['items'] as $item): ?>
                                                <div class="flex justify-between text-[10px] py-0.5">
                                                    <span class="text-white/80"><?= safe($item['name']) ?></span>
                                                    <span class="text-cyber-cyan font-mono"><?= safe($item['qty']) ?>x</span>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-[9px] italic text-white/20 text-center py-2">Geen materiaal gereserveerd.</p>
                                    <?php endif; ?>
                                </div>

                                <div class="mt-3 pt-2 border-t border-white/10 text-center">
                                    <span class="text-[8px] text-green-500 uppercase tracking-wider">● Status: Approved</span>
                                </div>
                            </div>
                        </template>
                    <?php endif; ?>

                </div>
            <?php endfor; ?>
        </div>
    </div>
    </div>

    </div>

    <!-- My Reservations Section -->
    <div class="mt-8">
        <div class="flex items-center space-x-2 text-cyber-cyan mb-4">
            <span class="text-xs">📦</span>
            <span class="text-[10px] uppercase font-bold tracking-[0.3em]">Resource Requisitions</span>
        </div>
        
        <div class="bg-cyber-dark/40 border border-white/5 rounded-sm backdrop-blur-sm overflow-hidden">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-white/5 border-b border-white/10 text-[10px] uppercase tracking-wider text-white/50 font-orbitron">
                        <th class="p-4 font-normal">Item</th>
                        <th class="p-4 font-normal">Category</th>
                        <th class="p-4 font-normal">Requested On</th>
                        <th class="p-4 font-normal">For Date</th>
                        <th class="p-4 font-normal text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php if (!empty($rentals)): ?>
                        <?php foreach ($rentals as $rental): ?>
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="p-4 text-sm text-white font-bold"><?= safe($rental->item_name) ?></td>
                            <td class="p-4 text-xs text-cyber-cyan font-mono"><?= safe($rental->category) ?></td>
                            <td class="p-4 text-xs text-gray-400 font-mono"><?= date('d M H:i', strtotime($rental->created_at)) ?></td>
                            <td class="p-4 text-xs text-white font-mono font-bold">
                                <?= !empty($rental->reservation_date) ? date('d M H:i', strtotime($rental->reservation_date)) : '-' ?>
                            </td>
                            <td class="p-4 text-right">
                                <?php
                                    $statusColors = [
                                        'pending' => 'text-yellow-400',
                                        'reserved' => 'text-green-400',
                                        'picked_up' => 'text-blue-400',
                                        'returned' => 'text-gray-500'
                                    ];
                                    $color = $statusColors[$rental->rental_status] ?? 'text-gray-400';
                                ?>
                                <span class="text-[10px] uppercase font-bold tracking-wider <?= $color ?> border border-current px-2 py-1 rounded-sm">
                                    <?= safe($rental->rental_status) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="p-8 text-center text-gray-500 text-sm italic">
                                Geen actieve reserveringen gevonden. <a href="/resources" class="text-cyber-cyan hover:underline">Ga naar The Armory</a>.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>