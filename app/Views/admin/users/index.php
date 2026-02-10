<div class="bg-cyber-dark/60 p-8 rounded-sm border border-white/5 shadow-2xl">
    <a href="/admin" class="inline-block mb-6 text-[10px] font-orbitron text-cyber-cyan/50 hover:text-cyber-cyan transition-all uppercase tracking-[0.3em]">
        <span class="mr-2">«</span> Back to Command Center
    </a>

    <h2 class="text-white font-orbitron text-2xl mb-8 uppercase italic tracking-widest border-b border-cyber-cyan/30 pb-4 neon-text-cyan">
        User Access Control
    </h2>

    <table class="w-full text-left border-collapse">
        <thead>
        <tr class="text-[11px] font-orbitron text-cyber-cyan/50 uppercase tracking-[0.2em] border-b border-white/10">
            <th class="pb-4 pl-4">Operative</th>
            <th class="pb-4">Access Level</th>
            <th class="pb-4">Status</th>
            <th class="pb-4 text-right pr-4">Authorization</th>
        </tr>
        </thead>
        <tbody class="text-sm">
        <?php foreach ($users as $user): ?>
            <tr class="border-b border-white/5 hover:bg-white/5 transition-colors group">
                <td class="py-6 pl-4">
                    <div class="text-lg text-white font-orbitron tracking-tight group-hover:neon-text-cyan transition-all">
                        <?= htmlspecialchars($user['username']) ?>
                    </div>
                    <div class="text-xs text-cyber-gray/60 italic mt-1 font-mono">
                        ID: [ <?= str_pad((string)$user['id'], 4, '0', STR_PAD_LEFT) ?> ] // <?= htmlspecialchars($user['email']) ?>
                    </div>
                </td>

                <td class="py-6">
                        <span class="px-3 py-1 border <?= $user['role'] === 'admin' ? 'border-cyber-purple text-cyber-purple' : 'border-white/20 text-white/50' ?> text-[10px] font-black uppercase tracking-widest">
                            <?= strtoupper($user['role']) ?>
                        </span>
                </td>

                <td class="py-6">
                    <?php if ($user['is_active']): ?>
                        <span class="text-cyber-cyan text-[10px] font-bold uppercase tracking-widest animate-pulse">● Active</span>
                    <?php else: ?>
                        <span class="text-red-500 text-[10px] font-bold uppercase tracking-widest">○ Blocked</span>
                    <?php endif; ?>
                </td>

                <td class="py-6 text-right pr-4">
                    <div class="flex justify-end space-x-4">
                        <a href="/admin/users/edit?id=<?= $user['id'] ?>" class="text-white/40 hover:text-white transition-colors uppercase text-[10px] font-black tracking-widest border-b border-transparent hover:border-white">
                            Edit
                        </a>

                        <form id="toggle-form-<?= $user['id'] ?>" action="/admin/users/toggle" method="POST" class="inline">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= $user['id'] ?>">
                            <input type="hidden" name="current_status" value="<?= $user['is_active'] ?>">
                            <button type="button"
                                    onclick="confirmToggle(<?= $user['id'] ?>, <?= $user['is_active'] ?>)"
                                    class="text-[10px] font-black uppercase tracking-widest <?= $user['is_active'] ? 'text-red-500 hover:text-red-400' : 'text-cyber-cyan hover:text-white' ?> transition-colors cursor-pointer">
                                <?= $user['is_active'] ? '[ Deactivate ]' : '[ Reactivate ]' ?>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmToggle(id, isActive) {
        const action = isActive ? 'BLOCK' : 'UNBLOCK';
        const color = isActive ? '#ff003c' : '#00f2ff';

        Swal.fire({
            title: `<span style="font-family: 'Orbitron', sans-serif; color: ${color}; uppercase tracking-widest">[ SECURITY ALERT ]</span>`,
            html: `<p style="color: white; font-family: 'Rajdhani', sans-serif;">Are you sure you want to ${action} this operative?</p>`,
            background: '#0b0c10',
            border: `1px solid ${color}`,
            showCancelButton: true,
            confirmButtonColor: color,
            cancelButtonColor: '#333',
            confirmButtonText: `YES, ${action}`,
            cancelButtonText: 'ABORT',
            customClass: {
                popup: 'cyber-border'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('toggle-form-' + id).submit();
            }
        });
    }
</script>

<style>
    .cyber-border {
        border: 1px solid #00f2ff !important;
        border-radius: 0 !important;
        box-shadow: 0 0 20px rgba(0, 242, 255, 0.2) !important;
    }
</style>