<?php View::renderComponent("header"); ?>

<div class="bg-gray-900 min-h-screen pt-6 pb-10 text-white"
    x-data="{ selectedUser: null, selectedOrder: null }">

    <div class="max-w-6xl mx-auto px-4">
        <h1 class="text-3xl font-bold mb-8">Checks</h1>

        <form method="GET" action="/checks" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8 bg-gray-800/40 p-6 rounded-2xl border border-gray-700/50">
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Date From</label>
                <input type="date" name="date_from" value="<?= $filters['date_from'] ?? '' ?>" class="w-full bg-gray-900 border-gray-700 rounded-xl text-white px-3 py-2">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Date To</label>
                <input type="date" name="date_to" value="<?= $filters['date_to'] ?? '' ?>" class="w-full bg-gray-900 border-gray-700 rounded-xl text-white px-3 py-2">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">User</label>
                <select name="user_id" class="w-full bg-gray-900 border-gray-700 rounded-xl text-white px-3 py-2">
                    <option value="">All Users</option>
                    <?php foreach ($users as $u): ?>
                        <option value="<?= $u['id'] ?>" <?= ($filters['user_id'] ?? '') == $u['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($u['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 py-2.5 rounded-xl font-bold transition">Filter</button>
            </div>
        </form>

        <div class="rounded-2xl border border-gray-700/50 overflow-hidden bg-gray-800/20">
            <table class="w-full text-left">
                <thead class="bg-gray-800 text-gray-400 text-xs uppercase tracking-widest">
                    <tr>
                        <th class="px-8 py-4">Name</th>
                        <th class="px-8 py-4 text-right">Total Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    <?php foreach ($checks as $userId => $data): ?>

                        <tr class="hover:bg-gray-700/30 cursor-pointer"
                            @click="selectedUser = (selectedUser === <?= $userId ?> ? null : <?= $userId ?>)">
                            <td class="px-8 py-5 flex items-center gap-4">
                                <div class="w-6 h-6 border border-indigo-500/50 rounded flex items-center justify-center bg-indigo-500/10">
                                    <span class="text-indigo-400 font-bold leading-none"
                                        x-text="selectedUser === <?= $userId ?> ? '−' : '+'"></span>
                                </div>
                                <span class="text-lg"><?= htmlspecialchars($data['user_name']) ?></span>
                            </td>
                            <td class="px-8 py-5 text-right text-indigo-400 font-bold">
                                <?= number_format($data['total_amount'], 2) ?> EGP
                            </td>
                        </tr>

                        <template x-if="selectedUser === <?= $userId ?>">
                            <tr>
                                <td colspan="2" class="bg-black/20 px-12 py-4">
                                    <div class="border border-gray-700 rounded-xl overflow-hidden">
                                        <table class="w-full text-sm">
                                            <thead class="bg-gray-800/50 text-gray-500">
                                                <tr>
                                                    <th class="px-6 py-2">Order Date</th>
                                                    <th class="px-6 py-2 text-right">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($data['orders'] as $order): ?>
                                                    <tr class="border-t border-gray-800 hover:bg-indigo-500/5 cursor-pointer"
                                                        @click.stop="selectedOrder = (selectedOrder === <?= $order['id'] ?> ? null : <?= $order['id'] ?>)">
                                                        <td class="px-6 py-4 flex items-center gap-3">
                                                            <div class="w-5 h-5 border border-gray-600 rounded flex items-center justify-center bg-gray-700">
                                                                <span class="text-white text-xs font-bold"
                                                                    x-text="selectedOrder === <?= $order['id'] ?> ? '−' : '+'"></span>
                                                            </div>
                                                            <?= date('Y-m-d h:i A', strtotime($order['created_at'])) ?>
                                                        </td>
                                                        <td class="px-6 py-4 text-right font-bold text-white"><?= number_format($order['total_amount'], 2) ?> LE</td>
                                                    </tr>

                                                    <tr x-show="selectedOrder === <?= $order['id'] ?>" x-cloak>
                                                        <td colspan="2" class="px-6 py-6 bg-gray-900">
                                                            <div class="flex flex-wrap gap-8">
                                                                <?php foreach ($order['items'] as $item): ?>
                                                                    <div class="text-center">
                                                                        <div class="relative inline-block mb-2">
                                                                            <img src="/public/uploads/<?= $item['image'] ?>" class="w-16 h-16 rounded-lg object-cover border border-gray-700">
                                                                            <span class="absolute -top-2 -right-2 bg-indigo-600 px-1.5 py-0.5 rounded text-[10px]"><?= $item['unit_price'] ?> LE</span>
                                                                        </div>
                                                                        <p class="text-[10px] uppercase text-gray-400"><?= htmlspecialchars($item['name']) ?></p>
                                                                        <p class="text-xs font-bold text-indigo-400">Qty: <?= $item['quantity'] ?></p>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </template>

                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php View::renderComponent("pagination", ["pagination" => $pagination, "baseUrl" => "/checks", "queryParams" => $filters ?? []]); ?>
    </div>
</div>

<?php View::renderComponent("footer"); ?>