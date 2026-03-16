<?php require_once __DIR__ . "/components/header.php"; ?>

<div class="bg-gray-900 min-h-screen pt-6 pb-10 relative">
    <div class="absolute top-0 inset-x-0 h-96 bg-gradient-to-b from-indigo-900/20 to-transparent pointer-events-none"></div>
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        <!-- Page Title -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white">My Orders</h1>
            <p class="text-gray-400 text-sm mt-1">Review your previous orders and their status.</p>
        </div>

        <?php if (empty($orders)): ?>

            <div class="bg-gray-800 border border-gray-700 rounded-xl p-10 text-center shadow-xl">
                <p class="text-gray-400 text-lg">You have not placed any orders yet.</p>
            </div>

        <?php else: ?>

        <!-- Orders Table -->
        <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">

                <table class="min-w-full text-sm">

                    <thead class="bg-gray-900 border-b border-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-gray-400 uppercase text-xs">Order ID</th>
                            <th class="px-6 py-4 text-left text-gray-400 uppercase text-xs">Room</th>
                            <th class="px-6 py-4 text-left text-gray-400 uppercase text-xs">Notes</th>
                            <th class="px-6 py-4 text-left text-gray-400 uppercase text-xs">Total</th>
                            <th class="px-6 py-4 text-left text-gray-400 uppercase text-xs">Status</th>
                            <th class="px-6 py-4 text-left text-gray-400 uppercase text-xs">Date</th>
                            <th class="px-6 py-4 text-left text-gray-400 uppercase text-xs">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-700">

                        <?php foreach ($orders as $order): ?>

                        <tr class="hover:bg-gray-700/30 transition">

                            <td class="px-6 py-4 text-white font-semibold">
                                #<?= $order['id'] ?>
                            </td>

                            <td class="px-6 py-4 text-gray-300">
                                <?= htmlspecialchars($order['room_no']) ?>
                            </td>

                            <td class="px-6 py-4 text-gray-300 max-w-xs truncate">
                                <?= htmlspecialchars($order['notes']) ?>
                            </td>

                            <td class="px-6 py-4 text-white font-medium">
                                <?= number_format($order['total_amount'],2) ?> LE
                            </td>

                            <!-- Status Badge -->
                            <td class="px-6 py-4">

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

                            </td>

                            <td class="px-6 py-4 text-gray-400 text-sm">
                                <?= htmlspecialchars($order['created_at']) ?>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 flex gap-2">

                                <a href="/orders/show?id=<?= $order['id'] ?>"
                                class="px-3 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-xs rounded-md transition">
                                    View
                                </a>

                                <?php if ($order['status'] === 'pending'): ?>

                                <form action="/orders/cancel" method="POST">

                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">

                                    <button type="submit"
                                            class="px-3 py-2 bg-red-600 hover:bg-red-500 text-white text-xs rounded-md transition">
                                        Cancel
                                    </button>

                                </form>

                                <?php endif; ?>

                            </td>

                        </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>
        </div>

        <?php endif; ?>

        <?php
            $pagination = $pagination ?? ['current_page' => 1, 'total_pages' => 1, 'total_items' => 0];
            $currentPage = (int)($pagination['current_page'] ?? 1);
            $totalPages = (int)($pagination['total_pages'] ?? 1);
        ?>

        <?php if ($totalPages > 1): ?>
            <div class="mt-6 flex items-center justify-between gap-3">
                <p class="text-sm text-gray-400">
                    Page <?= $currentPage ?> of <?= $totalPages ?>
                </p>
                <div class="flex items-center gap-2">
                    <?php if ($currentPage > 1): ?>
                        <a href="/my-orders?page=<?= $currentPage - 1 ?>" class="rounded-lg bg-gray-800/80 px-3 py-2 text-sm font-medium text-gray-200 ring-1 ring-gray-700 hover:bg-gray-700/80 transition-colors">Previous</a>
                    <?php endif; ?>

                    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                        <a href="/my-orders?page=<?= $p ?>" class="rounded-lg px-3 py-2 text-sm font-medium transition-colors <?= $p === $currentPage ? 'bg-indigo-500 text-white' : 'bg-gray-800/80 text-gray-200 ring-1 ring-gray-700 hover:bg-gray-700/80' ?>">
                            <?= $p ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages): ?>
                        <a href="/my-orders?page=<?= $currentPage + 1 ?>" class="rounded-lg bg-gray-800/80 px-3 py-2 text-sm font-medium text-gray-200 ring-1 ring-gray-700 hover:bg-gray-700/80 transition-colors">Next</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php require_once __DIR__ . "/components/footer.php"; ?>