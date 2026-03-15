<?php require_once __DIR__ . "/components/header.php"; ?>

<div class="container mx-auto px-6 py-8">

    <h1 class="text-2xl font-bold mb-6">
        Order #<?= $order['id'] ?>
    </h1>

    <div class="bg-white shadow rounded p-6 mb-6">

        <p><strong>Status:</strong> <?= $order['status'] ?></p>
        <p><strong>Room:</strong> <?= $order['room_no'] ?></p>
        <p><strong>Notes:</strong> <?= htmlspecialchars($order['notes']) ?></p>
        <p><strong>Date:</strong> <?= $order['created_at'] ?></p>

    </div>

    <div class="bg-white shadow rounded">

        <table class="w-full text-left border">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border">Product</th>
                    <th class="p-3 border">Image</th>
                    <th class="p-3 border">Price</th>
                    <th class="p-3 border">Quantity</th>
                    <th class="p-3 border">Subtotal</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($items as $item): ?>

                <tr>

                    <td class="p-3 border">
                        <?= htmlspecialchars($item['name']) ?>
                    </td>

                    <td class="p-3 border">
                        <img 
                            src="/public/uploads/<?= $item['image'] ?>" 
                            class="w-16 h-16 object-cover"
                        >
                    </td>

                    <td class="p-3 border">
                        $<?= number_format($item['unit_price'],2) ?>
                    </td>

                    <td class="p-3 border">
                        <?= $item['quantity'] ?>
                    </td>

                    <td class="p-3 border">
                        $<?= number_format($item['subtotal'],2) ?>
                    </td>

                </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>

    <div class="mt-6 text-right text-xl font-bold">
        Total: $<?= number_format($order['total_amount'],2) ?>
    </div>

</div>

<?php require_once __DIR__ . "/components/footer.php"; ?>