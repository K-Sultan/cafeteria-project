<?php require_once __DIR__ . "/components/header.php"; ?>

<div class="bg-gray-900 min-h-screen pt-6 pb-10 relative">
    <div class="absolute top-0 inset-x-0 h-96 bg-gradient-to-b from-indigo-900/20 to-transparent pointer-events-none"></div>
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white">
                Order #<?= $order['id'] ?>
            </h1>
            <p class="text-gray-400 text-sm mt-1">Detailed view of your order and selected items.</p>
        </div>

        <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-400 mb-1">Status</p>

                    <?php if ($order['status'] === 'pending'): ?>
                        <span class="px-3 py-1 text-xs rounded-full bg-yellow-500/20 text-yellow-300 border border-yellow-500/30">
                            Pending
                        </span>
                    <?php elseif ($order['status'] === 'processing'): ?>
                        <span class="px-3 py-1 text-xs rounded-full bg-blue-500/20 text-blue-300 border border-blue-500/30">
                            Processing
                        </span>
                    <?php elseif ($order['status'] === 'delivered'): ?>
                        <span class="px-3 py-1 text-xs rounded-full bg-green-500/20 text-green-300 border border-green-500/30">
                            Delivered
                        </span>
                    <?php elseif ($order['status'] === 'cancelled'): ?>
                        <span class="px-3 py-1 text-xs rounded-full bg-red-500/20 text-red-300 border border-red-500/30">
                            Cancelled
                        </span>
                    <?php else: ?>
                        <span class="px-3 py-1 text-xs rounded-full bg-gray-500/20 text-gray-300">
                            <?= htmlspecialchars($order['status']) ?>
                        </span>
                    <?php endif; ?>
                </div>

                <div>
                    <p class="text-gray-400 mb-1">Room</p>
                    <p class="text-white font-medium"><?= htmlspecialchars($order['room_no']) ?></p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-gray-400 mb-1">Notes</p>
                    <p class="text-white font-medium">
                        <?= !empty($order['notes']) ? htmlspecialchars($order['notes']) : 'No notes' ?>
                    </p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-gray-400 mb-1">Date</p>
                    <p class="text-white font-medium"><?= htmlspecialchars($order['created_at']) ?></p>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-900 border-b border-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-gray-400 uppercase text-xs">Product</th>
                            <th class="px-6 py-4 text-left text-gray-400 uppercase text-xs">Image</th>
                            <th class="px-6 py-4 text-left text-gray-400 uppercase text-xs">Price</th>
                            <th class="px-6 py-4 text-left text-gray-400 uppercase text-xs">Quantity</th>
                            <th class="px-6 py-4 text-left text-gray-400 uppercase text-xs">Subtotal</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-700">
                        <?php foreach ($items as $item): ?>
                            <tr class="hover:bg-gray-700/30 transition">
                                <td class="px-6 py-4 text-white font-medium">
                                    <?= htmlspecialchars($item['name']) ?>
                                </td>

                                <td class="px-6 py-4">
                                    <img 
                                        src="/public/uploads/<?= $item['image'] ?>" 
                                        class="w-16 h-16 object-cover rounded-lg border border-gray-700"
                                    >
                                </td>

                                <td class="px-6 py-4 text-gray-300">
                                    $<?= number_format($item['unit_price'], 2) ?>
                                </td>

                                <td class="px-6 py-4 text-gray-300">
                                    <?= $item['quantity'] ?>
                                </td>

                                <td class="px-6 py-4 text-white font-semibold">
                                    $<?= number_format($item['subtotal'], 2) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <div class="bg-gray-800 border border-gray-700 rounded-xl px-6 py-4 shadow-lg">
                <p class="text-gray-400 text-sm mb-1">Total Amount</p>
                <p class="text-2xl font-bold text-white">
                    $<?= number_format($order['total_amount'], 2) ?>
                </p>
            </div>
        </div>

    </div>
</div>

<?php require_once __DIR__ . "/components/footer.php"; ?>