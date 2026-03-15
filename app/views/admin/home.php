<?php View::renderComponent("header"); ?>


<div class="flex-1 bg-gray-900 bg-slate-900 text-white p-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">



        <!-- PAGE TITLE -->
        <div class="flex justify-between items-center mb-6">

            <h1 class="text-3xl font-bold">Orders</h1>

        </div>


    <?php View::renderComponent("order_filter", ["users" => $users, "rooms" => $rooms, "filters" => $filters ?? []]); ?>



        <!-- ORDERS LIST -->
        <div class="space-y-6">
            <?php if (empty($orders)): ?>
                <div class="bg-slate-800 rounded-xl p-6 text-slate-300">
                    No orders found for the selected filters.
                </div>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <div x-data="{open:false}" class="bg-slate-800 rounded-xl">
                        <div class="flex justify-between items-center p-6 cursor-pointer" @click="open=!open">
                            <div class="flex gap-10 flex-wrap">
                                <div>
                                    <p class="text-gray-400 text-sm">Order Date</p>
                                    <p><?= htmlspecialchars($order['created_at']) ?></p>
                                </div>

                                <div>
                                    <p class="text-gray-400 text-sm">User</p>
                                    <p><?= htmlspecialchars($order['user_name']) ?></p>
                                </div>

                                <div>
                                    <p class="text-gray-400 text-sm">Room</p>
                                    <p><?= htmlspecialchars($order['room_no']) ?></p>
                                </div>

                                <div>
                                    <p class="text-gray-400 text-sm">Status</p>
                                    <p id="status-label-<?= (int) $order['id'] ?>"><?= htmlspecialchars(str_replace('_', ' ', $order['status'])) ?></p>
                                </div>

                                <div>
                                    <p class="text-gray-400 text-sm">Ext</p>
                                    <p><?= htmlspecialchars($order['user_extension'] ?: 'N/A') ?></p>
                                </div>
                            </div>

                            <div class="flex items-center gap-4" @click.stop>
                                <select
                                    class="bg-slate-900 border border-slate-700 px-3 py-2 rounded-lg text-sm"
                                    data-order-id="<?= (int) $order['id'] ?>"
                                    data-current-status="<?= htmlspecialchars($order['status']) ?>"
                                    @click.stop
                                    @change.stop="updateOrderStatus($event.target)"
                                >
                                    <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Processing</option>
                                    <option value="out_for_delivery" <?= $order['status'] === 'out_for_delivery' ? 'selected' : '' ?>>Out For Delivery</option>
                                    <option value="done" <?= $order['status'] === 'done' ? 'selected' : '' ?>>Done</option>
                                    <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                </select>

                                <button type="button" class="cursor-pointer text-gray-400 text-sm hover:text-white transition-colors" @click.stop="open = !open">
                                    Click to view
                                </button>
                            </div>
                        </div>

                        <div x-show="open" x-transition class="px-6 pb-6 space-y-4">
                            <div>
                                <p class="text-gray-400 text-sm mb-4">Items</p>
                                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                                    <?php foreach ($order['items'] as $item): ?>
                                        <div class="text-center bg-slate-900 rounded-lg p-4 border border-slate-700">
                                            <div class="aspect-square rounded-lg overflow-hidden bg-slate-800 mb-3 flex items-center justify-center">
                                                <?php if (!empty($item['image'])): ?>
                                                    <img
                                                        src="<?= htmlspecialchars('/public/uploads/' . $item['image']) ?>"
                                                        alt="<?= htmlspecialchars($item['name']) ?>"
                                                        class="object-cover w-full h-full"
                                                        onerror="this.src='https://placehold.co/200x200/0f172a/cbd5e1?text=<?= urlencode(substr($item['name'], 0, 1)) ?>'"
                                                    >
                                                <?php else: ?>
                                                    <div class="w-full h-full flex items-center justify-center text-4xl font-bold text-slate-500">
                                                        <?= htmlspecialchars(substr($item['name'], 0, 1)) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <p class="text-white font-medium truncate"><?= htmlspecialchars($item['name']) ?></p>
                                            <span class="text-gray-400 text-sm block mt-1">
                                                Qty: <?= htmlspecialchars($item['quantity']) ?>
                                            </span>
                                            <p class="text-green-400 text-sm mt-1">
                                                <?= htmlspecialchars(number_format((float) $item['subtotal'], 2)) ?> LE
                                            </p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <?php if (!empty($order['notes'])): ?>
                                <div>
                                    <p class="text-gray-400 text-sm mb-2">Notes</p>
                                    <p class="text-white"><?= htmlspecialchars($order['notes']) ?></p>
                                </div>
                            <?php endif; ?>

                            <div class="flex justify-between items-center mt-6 border-t border-slate-700 pt-4">
                                <p class="text-lg font-semibold">
                                    Total:
                                    <span class="text-green-400">
                                        EGP <?= htmlspecialchars(number_format((float) $order['total_amount'], 2)) ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>





    </div>
</div>















<script>
    function formatStatusLabel(status) {
        return status.replaceAll('_', ' ');
    }

    async function updateOrderStatus(input) {
        const selectEl = input?.target ? input.target : input;
        if (!selectEl || !selectEl.dataset) {
            return;
        }

        const orderId = selectEl.dataset.orderId;
        const previousStatus = selectEl.dataset.currentStatus || '';
        const newStatus = selectEl.value;

        if (!orderId) {
            return;
        }

        selectEl.disabled = true;

        try {
            const response = await fetch('/orders/status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    order_id: Number(orderId),
                    status: newStatus
                })
            });

            const result = await response.json();
            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Failed to update status');
            }


            selectEl.dataset.currentStatus = newStatus;
            const statusLabel = document.getElementById(`status-label-${orderId}`);
            if (statusLabel) {
                statusLabel.textContent = formatStatusLabel(newStatus);
            }
        } catch (error) {
            selectEl.value = previousStatus;
            alert(error.message || 'Failed to update order status');
        } finally {
            selectEl.disabled = false;
        }
    }
</script>

<?php View::renderComponent("footer"); ?>