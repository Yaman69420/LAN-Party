<h1>Openstaande Reserveringen</h1>

<?php if (empty($rentals)): ?>
    <p>Geen openstaande aanvragen.</p>
<?php else: ?>
    <a href="/admin" class="inline-block mb-6 text-[10px] font-orbitron text-cyber-cyan/50 hover:text-cyber-cyan transition-all uppercase tracking-[0.3em]">
        <span class="mr-2">«</span> Back to Command Center
    </a>

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Aangevraagd Op</th>
                <th>Gewenste Datum</th>
                <th>Gebruiker</th>
                <th>Item</th>
                <th>Status</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rentals as $rental): ?>
            <tr>
                <td><?= date('d-m-Y H:i', strtotime($rental->created_at)) ?></td>
                <td>
                    <?php if (!empty($rental->reservation_date)): ?>
                        <?= date('d-m-Y H:i', strtotime($rental->reservation_date)) ?>
                    <?php else: ?>
                        <span style="color: gray;">(Direct)</span>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($rental->username) ?></td>
                <td><?= htmlspecialchars($rental->item_name) ?></td>
                <td>
                    <b>
                        <?php 
                        $statusMap = [
                            'pending' => 'Pending',
                            'reserved' => 'Approved',
                            'declined' => 'Denied'
                        ];
                        echo htmlspecialchars($statusMap[$rental->rental_status] ?? $rental->rental_status); 
                        ?>
                    </b>
                </td>
                <td>
                    <?php if ($rental->rental_status === 'pending'): ?>
                        <div style="display: flex; gap: 5px;">
                            <form action="/admin/reservations/update" method="POST" style="display:inline;">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= $rental->id ?>">
                                <button type="submit" name="status" value="reserved" class="text-green-500 hover:text-green-400 font-bold">✔ Approve</button>
                            </form>
                            <form action="/admin/reservations/update" method="POST" style="display:inline;">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= $rental->id ?>">
                                <button type="submit" name="status" value="declined" class="text-red-500 hover:text-red-400 font-bold">❌ Deny</button>
                            </form>
                        </div>
                    <?php else: ?>
                        <span class="text-gray-500 italic">Geen acties</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<p><a href="/admin">Terug naar Dashboard</a></p>
