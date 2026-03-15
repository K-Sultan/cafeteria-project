<?php require_once __DIR__ . "/components/header.php"; ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">My Orders</h1>

    <?php if (empty($orders)): ?>
        <div class="bg-yellow-100 text-yellow-800 p-4 rounded">
            You do not have any orders yet.
        </div>
    <?php else: ?>
        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 border">Order ID</th>
                        <th class="px-4 py-3 border">Room No</th>
                        <th class="px-4 py-3 border">Notes</th>
                        <th class="px-4 py-3 border">Total</th>
                        <th class="px-4 py-3 border">Status</th>
                        <th class="px-4 py-3 border">Date</th>
                        <th class="px-4 py-3 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr class="text-center">
                            <td class="px-4 py-3 border"><?= $order['id'] ?></td>
                            <td class="px-4 py-3 border"><?= htmlspecialchars($order['room_no']) ?></td>
                            <td class="px-4 py-3 border"><?= htmlspecialchars($order['notes']) ?></td>
                            <td class="px-4 py-3 border">$<?= number_format($order['total_amount'], 2) ?></td>
                            <td class="px-4 py-3 border"><?= htmlspecialchars($order['status']) ?></td>
                            <td class="px-4 py-3 border"><?= htmlspecialchars($order['created_at']) ?></td>
                            <td class="px-4 py-3 border">
                                <a href="/orders/show?id=<?= $order['id'] ?>" class="bg-blue-500 text-white px-3 py-1 rounded mr-2">
                                    View
                                </a>

                                <?php if ($order['status'] === 'pending'): ?>
                                    <form action="/orders/cancel" method="POST" class="inline">
                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded">
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
    <?php endif; ?>
</div>

<?php require_once __DIR__ . "/components/footer.php"; ?>
