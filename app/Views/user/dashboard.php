<?php
// 1. DYNAMISCHE DATUM LOGICA
$month = isset($_GET['m']) ? (int)$_GET['m'] : (int)date('n');
$year = isset($_GET['y']) ? (int)$_GET['y'] : (int)date('Y');

if ($month < 1 || $month > 12) { $month = (int)date('n'); }

$prevM = $month - 1; $prevY = $year;
if ($prevM == 0) { $prevM = 12; $prevY--; }

$nextM = $month + 1; $nextY = $year;
if ($nextM == 13) { $nextM = 1; $nextY++; }

$maanden = ['Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'];
$maandNaam = $maanden[$month - 1];

$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$firstDayIndex = date('w', strtotime("$year-$month-01"));
$paddingDays = ($firstDayIndex == 0) ? 6 : $firstDayIndex - 1;
?>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<div class="mt-4 w-full" x-data="{
    showPopup: false,
    pName: '',
    pDate: '',
    pLaptops: 0,
    pVr: 0,
    openParty(name, date, laptops, vr) {
        this.pName = name;
        this.pDate = date;
        this.pLaptops = laptops;
        this.pVr = vr;
        this.showPopup = true;
    }
}">

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-12 items-stretch">
        <div class="lg:col-span-3 bg-cyber-dark/40 border border-white/5 p-6 rounded-sm shadow-2xl relative backdrop-blur-sm h-full">

            <div class="flex justify-between items-end mb-6">
                <div>
                    <div class="flex items-center space-x-2 text-cyber-purple mb-1"><span class="text-xs">🎮</span><span class="text-[10px] uppercase font-bold tracking-[0.3em]">Operational Overview</span></div>
                    <div class="flex items-center space-x-4 mt-1">
                        <a href="?m=<?= $prevM ?>&y=<?= $prevY ?>" class="text-cyber-cyan/40 hover:text-cyber-cyan text-2xl transition-all">❮</a>

                        <h2 class="text-white font-orbitron text-2xl uppercase italic tracking-wider w-56 text-center flex flex-col leading-tight">
                            <span><?= $maandNaam ?></span>
                            <span><?= $year ?></span>
                        </h2>

                        <a href="?m=<?= $nextM ?>&y=<?= $nextY ?>" class="text-cyber-cyan/40 hover:text-cyber-cyan text-2xl transition-all">❯</a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-7 gap-3 relative">
                <?php for($i = 0; $i < $paddingDays; $i++): ?> <div class="h-32 bg-white/5 opacity-10 rounded-sm"></div> <?php endfor; ?>

                <?php
                for($day = 1; $day <= $daysInMonth; $day++):
                    $dateKey = sprintf('%04d-%02d-%02d', $year, $month, $day);
                    $party = $parties[$dateKey] ?? null;
                    $hasParty = ($party !== null);

                    $glowClass = 'border-white/5 bg-black/20 hover:border-white/20 hover:bg-white/5';
                    if ($hasParty) {
                        $glowClass = 'border-cyber-cyan bg-cyber-cyan/10 shadow-[0_0_15px_rgba(0,242,255,0.15)] hover:shadow-[0_0_25px_rgba(0,242,255,0.4)] cursor-pointer';
                    }
                    ?>

                    <div class="relative h-32 border rounded-sm transition-all duration-300 p-2 flex flex-col justify-between group <?= $glowClass ?>"
                            <?php if($hasParty): ?>
                                @click="openParty('<?= htmlspecialchars(addslashes($party['name']), ENT_QUOTES) ?>', '<?= date('d M Y', strtotime($dateKey)) ?>', <?= $party['laptops'] ?>, <?= $party['vr'] ?>)"
                            <?php endif; ?>
                    >
                        <span class="font-orbitron text-sm <?= $hasParty ? 'text-white font-bold' : 'text-white/20' ?>"><?= $day ?></span>

                        <?php if($hasParty): ?>
                            <div class="mt-0">
                                <span class="block text-xs uppercase font-extrabold leading-tight tracking-wide text-cyber-cyan line-clamp-2">
                                    <?= htmlspecialchars($party['name']) ?>
                                </span>
                            </div>
                            <div class="absolute top-2 right-2 w-2 h-2 rounded-full bg-cyber-cyan shadow-[0_0_5px_#00f2ff]"></div>
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>

                <div x-cloak x-show="showPopup" style="display: none;" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[9998]"></div>

                <div x-cloak x-show="showPopup" style="display: none;" @click.away="showPopup = false" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[400px] bg-[#0b0c10] border-2 border-cyber-cyan p-8 shadow-[0_0_50px_rgba(0,242,255,0.4)] z-[9999] rounded-xl">

                    <div class="flex justify-between items-start border-b border-white/10 pb-4 mb-5">
                        <h3 class="text-white font-orbitron text-base font-bold uppercase tracking-widest" x-text="'INTEL: ' + pName"></h3>
                        <button @click="showPopup = false" class="text-white/50 hover:text-cyber-cyan transition-colors text-xl font-bold">✖</button>
                    </div>

                    <div class="text-cyber-cyan text-xs font-bold mb-8 tracking-wider" x-text="pDate"></div>

                    <div class="space-y-8">
                        <div>
                            <div class="flex justify-between text-xs text-white font-bold uppercase mb-3">
                                <span>GAMING LAPTOPS: <span x-text="pLaptops"></span>/20</span>
                                <span class="text-cyber-cyan" x-text="Math.round((pLaptops / 20) * 100) + '%'"></span>
                            </div>
                            <div class="h-2 w-full bg-white/10 rounded-full overflow-hidden relative">
                                <div class="absolute top-0 left-0 h-full bg-cyber-cyan shadow-[0_0_10px_rgba(0,242,255,0.8)]" :style="'width: ' + Math.round((pLaptops / 20) * 100) + '%'"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between text-xs text-white font-bold uppercase mb-3">
                                <span>VR HEADSETS: <span x-text="pVr"></span>/5</span>
                                <span class="text-cyber-purple" x-text="Math.round((pVr / 5) * 100) + '%'"></span>
                            </div>
                            <div class="h-2 w-full bg-white/10 rounded-full overflow-hidden relative">
                                <div class="absolute top-0 left-0 h-full bg-cyber-purple shadow-[0_0_10px_rgba(188,19,254,0.8)]" :style="'width: ' + Math.round((pVr / 5) * 100) + '%'"></div>
                            </div>
                        </div>
                    </div>

                    <button @click="showPopup = false" class="mt-10 w-full py-4 border-2 border-cyber-cyan text-cyber-cyan text-xs font-bold uppercase tracking-[0.2em] hover:bg-cyber-cyan hover:text-black transition-all">
                        CLOSE_INTEL
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
                $shownResources = array_slice($resources ?? [], 0, 4);
                foreach($shownResources as $index => $item):
                    ?>
                    <div class="flex-1 group relative bg-black/40 border border-white/5 hover:border-cyber-cyan/50 rounded-xl transition-all duration-300 hover:-translate-y-1 overflow-hidden flex flex-row items-center p-3 gap-4 min-h-[100px]">
                        <div class="absolute inset-0 bg-gradient-to-r from-cyber-cyan/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="relative z-10 w-16 h-16 flex-shrink-0 flex items-center justify-center bg-black/30 rounded border border-white/5">
                            <?php if(!empty($item['image'])): ?>
                                <img src="/LAN-Party/public/uploads/items/<?= $item['image'] ?>" class="object-contain h-full w-full p-1">
                            <?php else: ?>
                                <span class="text-2xl filter drop-shadow-[0_0_10px_rgba(0,242,255,0.5)]">
                                    <?php
                                    $itemName = strtolower($item['name'] ?? '');
                                    if (strpos($itemName, 'monitor') !== false) {
                                        echo '🖥️';
                                    } elseif (strpos($itemName, 'keyboard') !== false || strpos($itemName, 'toetsenbord') !== false) {
                                        echo '⌨️';
                                    } else {
                                        echo ($index % 2 == 0) ? '💻' : '⌨️';
                                    }
                                    ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="relative z-10 flex flex-col items-start justify-center">
                            <h4 class="text-white font-orbitron text-sm tracking-wide uppercase mb-3 line-clamp-1">
                                <?= htmlspecialchars($item['name'] ?? 'Gear Item') ?>
                            </h4>
                            <a href="/resources" class="text-xs bg-cyber-cyan text-black px-5 py-2 rounded font-extrabold hover:bg-white transition-colors uppercase tracking-widest shadow-[0_0_10px_rgba(0,242,255,0.3)]">
                                CHECK
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
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