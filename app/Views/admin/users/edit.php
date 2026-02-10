<div class="max-w-2xl mx-auto bg-cyber-dark/60 p-8 rounded-sm border border-cyber-cyan/30 shadow-neon-cyan">
    <h2 class="text-white font-orbitron text-2xl mb-8 uppercase italic tracking-widest border-b border-white/10 pb-4">
        Modify Operative Intel
    </h2>

    <form action="/admin/users/edit" method="POST" class="space-y-6">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= $user['id'] ?>">

        <div>
            <label class="block text-[10px] text-cyber-cyan uppercase font-bold tracking-[0.2em] mb-2">Username</label>
            <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>"
                   class="w-full bg-black/40 border border-white/10 px-4 py-3 text-white focus:border-cyber-cyan outline-none transition-all font-mono">
        </div>

        <div>
            <label class="block text-[10px] text-cyber-cyan uppercase font-bold tracking-[0.2em] mb-2">Email Address</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>"
                   class="w-full bg-black/40 border border-white/10 px-4 py-3 text-white focus:border-cyber-cyan outline-none transition-all font-mono">
        </div>

        <div>
            <label class="block text-[10px] text-cyber-cyan uppercase font-bold tracking-[0.2em] mb-2">Access Level</label>
            <select name="role" class="w-full bg-black/40 border border-white/10 px-4 py-3 text-white focus:border-cyber-cyan outline-none cursor-pointer uppercase font-bold text-xs">
                <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Standard User</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>System Admin</option>
            </select>
        </div>

        <div class="flex items-center justify-between pt-6">
            <a href="/admin/users" class="text-white/40 hover:text-white text-[10px] uppercase font-bold tracking-widest transition-colors">
                [ Abort Mission ]
            </a>
            <button type="submit" class="bg-cyber-cyan text-black px-8 py-3 uppercase font-black text-xs tracking-widest hover:bg-white transition-all shadow-neon-cyan">
                Update Operative
            </button>
        </div>
    </form>
</div>