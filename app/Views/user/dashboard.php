<?php
// 1. DYNAMISCHE DATUM LOGICA
// Kijk of er een maand/jaar in de URL zit, anders pakken we de huidige datum
$month = isset($_GET['m']) ? (int)$_GET['m'] : (int)date('n');
$year = isset($_GET['y']) ? (int)$_GET['y'] : (int)date('Y');

// Beveiliging: zorg dat de maand altijd tussen 1 en 12 is
if ($month < 1 || $month > 12) { $month = (int)date('n'); }

// Bereken Vorige Maand
$prevM = $month - 1; $prevY = $year;
if ($prevM == 0) { $prevM = 12; $prevY--; }

// Bereken Volgende Maand
$nextM = $month + 1; $nextY = $year;
if ($nextM == 13) { $nextM = 1; $nextY++; }

// Maandnamen voor de titel
$maanden = ['Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'];
$maandNaam = $maanden[$month - 1];

// Kalender wiskunde
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$firstDayIndex = date('w', strtotime("$year-$month-01"));
$paddingDays = ($firstDayIndex == 0) ? 6 : $firstDayIndex - 1;
?>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<div class="mt-4 w-full"
     x-data="{
        activeParty: null,
        showPopup: false,
        openParty(party) {
            this.activeParty = party;
            this.showPopup = true;
        }
     }">

    <?php if (($_GET['status'] ?? '') === 'proposal_sent'): ?>
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             class="fixed top-24 left-1/2 transform -translate-x-1/2 z-50 bg-[#0b0c10] border border-cyber-purple/50 text-cyber-purple px-8 py-6 rounded-sm shadow-[0_0_50px_rgba(188,19,254,0.4)] flex flex-col items-center gap-4 backdrop-blur-md text-center">
            <div class="text-4xl mb-2">🛰️</div>
            <div>
                <h4 class="font-orbitron text-lg font-bold uppercase tracking-wider mb-1">TRANSMISSION SENT</h4>
                <p class="font-mono text-sm text-white/70">Operation proposal uploaded to command center.</p>
            </div>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-12 items-stretch">

        <div class="lg:col-span-3 bg-cyber-dark/40 border border-white/5 p-6 rounded-sm shadow-2xl relative backdrop-blur-sm h-full">

            <div class="flex justify-between items-end mb-6">
                <div>
                    <div class="flex items-center space-x-2 text-cyber-purple mb-1">
                        <span class="text-xs">🎮</span>
                        <span class="text-[10px] uppercase font-bold tracking-[0.3em]">Operational Overview</span>
                    </div>

                    <div class="flex items-center space-x-4 mt-1">
                        <a href="?m=<?= $prevM ?>&y=<?= $prevY ?>" class="text-cyber-cyan/40 hover:text-cyber-cyan transition-colors text-2xl hover:-translate-x-1 transform duration-200">
                            ❮
                        </a>

                        <h2 class="text-white font-orbitron text-2xl uppercase italic tracking-wider w-56 text-center">
                            <?= $maandNaam ?> <?= $year ?>
                        </h2>

                        <a href="?m=<?= $nextM ?>&y=<?= $nextY ?>" class="text-cyber-cyan/40 hover:text-cyber-cyan transition-colors text-2xl hover:translate-x-1 transform duration-200">
                            ❯
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-7 mb-2 text-center border-b border-white/5 pb-2">
                <?php foreach(['MA', 'DI', 'WO', 'DO', 'VR', 'ZA', 'ZO'] as $dayName): ?>
                    <div class="text-[10px] text-cyber-gray/50 font-bold uppercase tracking-widest"><?= $dayName ?></div>
                <?php endforeach; ?>
            </div>

            <div class="grid grid-cols-7 gap-3 relative">
                <?php for($i = 0; $i < $paddingDays; $i++): ?>
                    <div class="h-32 bg-white/5 opacity-10 rounded-sm"></div>
                <?php endfor; ?>

                <?php
                for($day = 1; $day <= $daysInMonth; $day++):
                    $dateKey = sprintf('%04d-%02d-%02d', $year, $month, $day);
                    $party = $parties[$dateKey] ?? null;
                    $hasParty = ($party !== null);

                    $glowClass = '';
                    $isEvenParty = false;

                    if ($hasParty) {
                        $partyId = $party['id'] ?? 1;
                        $isEvenParty = ($partyId % 2 === 0);

                        $glowClass = $isEvenParty
                                ? 'border-cyber-purple bg-cyber-purple/10 shadow-[0_0_15px_rgba(188,19,254,0.15)] group-hover:shadow-[0_0_25px_rgba(188,19,254,0.4)]'
                                : 'border-cyber-cyan bg-cyber-cyan/10 shadow-[0_0_15px_rgba(0,242,255,0.15)] group-hover:shadow-[0_0_25px_rgba(0,242,255,0.4)]';
                    } else {
                        // Check of deze dag VANDAAG is voor een subtiele highlight
                        $isToday = ($dateKey === date('Y-m-d'));
                        if ($isToday) {
                            $glowClass = 'border-white/30 bg-white/10 hover:border-white/50 hover:bg-white/20';
                        } else {
                            $glowClass = 'border-white/5 bg-black/20 hover:border-white/20 hover:bg-white/5';
                        }
                    }
                    ?>
                    <div class="relative h-32 border rounded-sm transition-all duration-300 p-2 cursor-pointer group flex flex-col justify-between <?= $glowClass ?>"
                            <?php if($hasParty): ?>
                                @click="openParty({
                                name: '<?= addslashes($party['name'] ?? $party['title']) ?>',
                                date: '<?= date('d M Y', strtotime($dateKey)) ?>',
                                laptops: '<?= rand(8, 18) ?>',
                                keyboards: '<?= rand(12, 40) ?>',
                                vr: '<?= rand(2, 5) ?>'
                            })"
                            <?php endif; ?>
                    >
                        <span class="font-orbitron text-sm <?= $hasParty ? 'text-white font-bold' : 'text-white/20' ?> <?= ($dateKey === date('Y-m-d') && !$hasParty) ? 'text-white font-bold' : '' ?>"><?= $day ?></span>

                        <?php if($hasParty): ?>
                            <div class="mt-2">
                                <span class="block text-[9px] uppercase font-bold leading-tight <?= $isEvenParty ? 'text-cyber-purple' : 'text-cyber-cyan' ?>">
                                    <?= htmlspecialchars($party['name'] ?? $party['title']) ?>
                                </span>
                            </div>
                            <div class="absolute top-2 right-2 w-1.5 h-1.5 rounded-full <?= $isEvenParty ? 'bg-cyber-purple shadow-[0_0_5px_#bc13fe]' : 'bg-cyber-cyan shadow-[0_0_5px_#00f2ff]' ?>"></div>
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>

                <div x-show="showPopup"
                     style="display: none;"
                     @click.away="showPopup = false"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-90"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 bg-[#0b0c10] border border-cyber-cyan p-6 shadow-[0_0_50px_rgba(0,242,255,0.3)] z-50 rounded-sm">

                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-white font-orbitron text-sm uppercase tracking-widest" x-text="activeParty?.name"></h3>
                        <div class="text-cyber-cyan text-[10px] font-bold border border-cyber-cyan px-2 py-1 rounded" x-text="activeParty?.date"></div>
                    </div>

                    <div class="space-y-4 border-t border-white/10 pt-4">
                        <div>
                            <div class="flex justify-between text-[10px] text-cyber-gray uppercase mb-1">
                                <span>Gaming Setups</span>
                                <span x-text="activeParty?.laptops + '/20'"></span>
                            </div>
                            <div class="h-1.5 w-full bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-cyber-cyan shadow-[0_0_8px_rgba(0,242,255,0.8)]" style="width: 65%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-[10px] text-cyber-gray uppercase mb-1">
                                <span>VR Units</span>
                                <span x-text="activeParty?.vr + '/5'"></span>
                            </div>
                            <div class="h-1.5 w-full bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-cyber-purple shadow-[0_0_8px_rgba(188,19,254,0.8)]" style="width: 40%"></div>
                            </div>
                        </div>
                    </div>

                    <button @click="showPopup = false" class="mt-8 w-full py-3 bg-cyber-cyan/10 hover:bg-cyber-cyan/20 border border-cyber-cyan/50 text-cyber-cyan text-[10px] font-bold uppercase tracking-[0.2em] transition-all">
                        Close Intel
                    </button>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 flex flex-col h-full gap-4">

            <h2 class="text-white font-orbitron text-lg tracking-widest uppercase pl-2 border-l-2 border-cyber-cyan h-6 flex items-center shrink-0">
                The Armory
            </h2>

            <div class="flex-1 flex flex-col gap-4">
                <?php
                $shownResources = array_slice($resources ?? [], 0, 3);
                foreach($shownResources as $index => $item):
                    ?>
                    <div class="flex-1 group relative bg-black/40 border border-white/5 hover:border-cyber-cyan/50 rounded-xl transition-all duration-300 hover:-translate-y-1 overflow-hidden flex flex-row items-center p-3 gap-4 min-h-[100px]">
                        <div class="absolute inset-0 bg-gradient-to-r from-cyber-cyan/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="relative z-10 w-16 h-16 flex-shrink-0 flex items-center justify-center bg-black/30 rounded border border-white/5">
                            <?php if(!empty($item['image'])): ?>
                                <img src="/LAN-Party/public/uploads/items/<?= $item['image'] ?>" class="object-contain h-full w-full p-1">
                            <?php else: ?>
                                <span class="text-2xl filter drop-shadow-[0_0_10px_rgba(0,242,255,0.5)]">
                                    <?= ($index == 0) ? '💻' : (($index == 1) ? '⌨️' : '🥽') ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="relative z-10 flex flex-col items-start justify-center">
                            <h4 class="text-white font-orbitron text-xs tracking-wide uppercase mb-1 line-clamp-1">
                                <?= htmlspecialchars($item['name'] ?? 'Gear Item') ?>
                            </h4>
                            <a href="/resources" class="text-[8px] bg-cyber-cyan text-black px-2 py-1 rounded font-bold hover:bg-white transition-colors uppercase tracking-widest">
                                CHECK
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="flex-1 bg-gradient-to-br from-cyber-dark to-black border border-cyber-purple/30 p-4 rounded-xl relative overflow-hidden group cursor-pointer flex flex-col justify-center min-h-[100px]">
                    <div class="absolute -right-4 -top-4 w-20 h-20 bg-cyber-purple/20 blur-3xl rounded-full group-hover:bg-cyber-purple/40 transition-all"></div>
                    <div class="relative z-10">
                        <h3 class="text-white font-orbitron text-sm italic uppercase mb-0">NeonBeast X1</h3>
                        <p class="text-cyber-gray text-[8px] mb-2">RTX 4090 / 64GB RAM</p>
                        <button class="w-full bg-cyber-purple text-white py-2 font-black uppercase tracking-widest text-[8px] hover:bg-white hover:text-black transition-all shadow-[0_0_15px_rgba(188,19,254,0.4)]">
                            RENT CONFIG
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8">
        <div class="flex items-center space-x-2 text-cyber-cyan mb-4">
            <span class="text-xs">📦</span>
            <span class="text-[10px] uppercase font-bold tracking-[0.3em]">Active Requisitions</span>
        </div>

        <div class="bg-cyber-dark/40 border border-white/5 rounded-sm backdrop-blur-sm overflow-hidden shadow-lg">
            <table class="w-full text-left">
                <thead>
                <tr class="bg-white/5 border-b border-white/10 text-[10px] uppercase tracking-wider text-white/50 font-orbitron">
                    <th class="p-4 font-normal">Item</th>
                    <th class="p-4 font-normal">Category</th>
                    <th class="p-4 font-normal">Date</th>
                    <th class="p-4 font-normal text-right">Status</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                <?php if (!empty($rentals)): ?>
                <?php foreach ($rentals as $rental): 
                    $rental = (array)$rental;
                ?>
                    <tr class="hover:bg-white/5 transition-colors">
                            <td class="p-4 text-sm text-white font-bold"><?= htmlspecialchars($rental['item_name'] ?? 'Unknown') ?></td>
                            <td class="p-4 text-xs text-cyber-cyan font-mono"><?= htmlspecialchars($rental['category'] ?? '-') ?></td>
                            <td class="p-4 text-xs text-white font-mono font-bold">
                                <?= !empty($rental['reservation_date']) ? date('d M', strtotime($rental['reservation_date'])) : '-' ?>
                            </td>
                            <td class="p-4 text-right">
                                <?php
                                $status = $rental['rental_status'] ?? 'pending';
                                $class = match($status) {
                                    'reserved' => 'text-green-400 border-green-400/30',
                                    'picked_up' => 'text-blue-400 border-blue-400/30',
                                    'returned' => 'text-gray-500 border-gray-500/30',
                                    default => 'text-yellow-400 border-yellow-400/30'
                                };
                                ?>
                                <span class="text-[9px] uppercase font-bold tracking-wider <?= $class ?> border px-2 py-1 rounded-sm">
                                    <?= htmlspecialchars($status) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="p-6 text-center text-gray-500 text-xs italic">
                            NO ACTIVE REQUISITIONS.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>