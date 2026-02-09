<?php
// Dynamische datuminstelling voor Februari 2026
$month = 2;
$year = 2026;
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$firstDayOfMonth = date('w', strtotime("$year-$month-01"));
?>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<div class="mt-4" x-data="{ selectedDate: null }">
    <div class="mb-6">
        <div class="flex items-center space-x-2 text-cyber-purple mb-2">
            <span class="text-xs">🎮</span>
            <span class="text-[10px] uppercase font-bold tracking-[0.3em]">Operational Overview</span>
        </div>
        <h2 class="text-white font-orbitron text-xl uppercase italic tracking-wider">Februari 2026</h2>
    </div>

    <div class="bg-cyber-dark/40 border border-white/5 p-6 rounded-sm backdrop-blur-sm relative overflow-visible">
        <div class="grid grid-cols-7 gap-4 mb-4 text-center">
            <?php foreach(['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'] as $dayName): ?>
                <span class="text-[10px] font-orbitron text-white/20 uppercase tracking-[0.2em]"><?= $dayName ?></span>
            <?php endforeach; ?>
        </div>

        <div class="grid grid-cols-7 gap-3">
            <?php for ($i = 0; $i < $firstDayOfMonth; $i++): ?>
                <div class="h-16"></div>
            <?php endfor; ?>

            <?php for ($day = 1; $day <= $daysInMonth; $day++):
                $dateKey = sprintf('%04d-%02d-%02d', $year, $month, $day);
                $party = $parties[$dateKey] ?? null;
                $hasParty = ($party !== null);
                // Om en om cyaan of paars voor de look van de foto
                $glowColor = ($day % 2 === 0) ? 'border-cyber-purple shadow-[0_0_10px_rgba(188,19,254,0.2)]' : 'border-cyber-cyan shadow-[0_0_10px_rgba(0,242,255,0.2)]';
                ?>
                <div
                    @click="<?= $hasParty ? "selectedDate = (selectedDate === '$dateKey' ? null : '$dateKey')" : "" ?>"
                    class="relative h-16 border transition-all duration-300 flex items-center justify-center cursor-pointer group
                    <?= $hasParty
                        ? "border-2 $glowColor bg-white/5"
                        : "border-white/5 hover:border-white/20 text-white/20" ?>">

                    <span class="font-orbitron text-xs <?= $hasParty ? 'text-white' : '' ?>"><?= $day ?></span>

                    <template x-if="selectedDate === '<?= $dateKey ?>'">
                        <div class="absolute z-50 top-full left-1/2 -translate-x-1/2 mt-2 w-64 bg-cyber-dark border border-cyber-cyan p-4 shadow-[0_0_30px_rgba(0,0,0,0.9)]" @click.away="selectedDate = null">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="text-[10px] font-orbitron text-cyber-cyan uppercase italic"><?= e($party['title']) ?></h3>
                                    <p class="text-[8px] text-white/40 uppercase">📍 <?= e($party['location']) ?></p>
                                </div>
                                <button class="text-white/20 hover:text-white">&times;</button>
                            </div>

                            <div class="space-y-2">
                                <p class="text-[9px] text-cyber-purple font-bold uppercase tracking-widest border-b border-white/10 pb-1 text-left">Gereserveerd:</p>
                                <?php if (!empty($party['items'])): ?>
                                    <?php foreach ($party['items'] as $item): ?>
                                        <div class="flex justify-between text-[10px]">
                                            <span class="text-white/70"><?= e($item['name']) ?></span>
                                            <span class="text-cyber-cyan"><?= e((string)$item['qty']) ?>x</span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-[9px] italic text-white/20">Geen materiaal.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </template>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</div>
