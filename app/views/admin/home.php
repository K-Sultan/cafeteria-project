<?php View::renderComponent("header"); ?>


<div class="flex-1 bg-gray-900 bg-slate-900 text-white p-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">



        <!-- PAGE TITLE -->
        <div class="flex justify-between items-center mb-6">

            <h1 class="text-3xl font-bold">Orders</h1>

        </div>


    <?php View::renderComponent("order_filter", ["users" => $users, "rooms" => $rooms, "filters" => $filters ?? []]); ?>



        <!-- ORDERS LIST -->
        <div class="overflow-x-auto rounded-xl border border-slate-700 bg-slate-800">
            <table class="min-w-full text-sm text-left text-slate-200">
                <thead class="bg-slate-900/80 text-slate-300 uppercase text-xs tracking-wide">
                    <tr>
                        <th class="px-4 py-3">Order Date</th>
                        <th class="px-4 py-3">User</th>
                        <th class="px-4 py-3">Room</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Ext</th>
                        <th class="px-4 py-3">Total</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-slate-300">
                                No orders found for the selected filters.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                            <tr class="border-t border-slate-700 hover:bg-slate-700/30">
                                <td class="px-4 py-3 whitespace-nowrap"><?= htmlspecialchars($order['created_at']) ?></td>
                                <td class="px-4 py-3 whitespace-nowrap"><?= htmlspecialchars($order['user_name']) ?></td>
                                <td class="px-4 py-3 whitespace-nowrap"><?= htmlspecialchars($order['room_no']) ?></td>
                                <td class="px-4 py-3 whitespace-nowrap" id="status-label-<?= (int) $order['id'] ?>">
                                    <?= htmlspecialchars(str_replace('_', ' ', $order['status'])) ?>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap"><?= htmlspecialchars($order['user_extension'] ?: 'N/A') ?></td>
                                <td class="px-4 py-3 whitespace-nowrap text-green-400 font-medium">
                                    EGP <?= htmlspecialchars(number_format((float) $order['total_amount'], 2)) ?>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <select
                                            class="bg-slate-900 border border-slate-700 px-3 py-2 rounded-lg text-sm"
                                            data-order-id="<?= (int) $order['id'] ?>"
                                            data-current-status="<?= htmlspecialchars($order['status']) ?>"
                                        >
                                            <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Processing</option>
                                            <option value="out_for_delivery" <?= $order['status'] === 'out_for_delivery' ? 'selected' : '' ?>>Out For Delivery</option>
                                            <option value="done" <?= $order['status'] === 'done' ? 'selected' : '' ?>>Done</option>
                                            <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                        </select>

                                        <button
                                            type="button"
                                            class="cursor-pointer text-gray-300 text-sm hover:text-white transition-colors"
                                            onclick="toggleOrderDetails(<?= (int) $order['id'] ?>)"
                                        >
                                            View items
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr id="order-details-<?= (int) $order['id'] ?>" class="hidden border-t border-slate-700 bg-slate-900/40">
                                <td colspan="7" class="px-4 py-4">
                                    <div class="space-y-4">
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
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php
            $pagination = $pagination ?? ['current_page' => 1, 'total_pages' => 1, 'total_items' => 0];
            $currentPage = (int)($pagination['current_page'] ?? 1);
            $totalPages = (int)($pagination['total_pages'] ?? 1);
            $queryParams = $filters ?? [];
            unset($queryParams['page']);

            $buildPageUrl = function ($page) use ($queryParams) {
                $query = http_build_query(array_merge($queryParams, ['page' => $page]));
                return '/?' . $query;
            };
        ?>

        <?php if ($totalPages > 1): ?>
            <div class="mt-6 flex items-center justify-between gap-3">
                <p class="text-sm text-slate-300">Page <?= $currentPage ?> of <?= $totalPages ?></p>
                <div class="flex items-center gap-2">
                    <?php if ($currentPage > 1): ?>
                        <a href="<?= htmlspecialchars($buildPageUrl($currentPage - 1)) ?>" class="rounded-lg bg-slate-800 px-3 py-2 text-sm font-medium text-slate-200 ring-1 ring-slate-700 hover:bg-slate-700 transition-colors">Previous</a>
                    <?php endif; ?>

                    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                        <a href="<?= htmlspecialchars($buildPageUrl($p)) ?>" class="rounded-lg px-3 py-2 text-sm font-medium transition-colors <?= $p === $currentPage ? 'bg-indigo-500 text-white' : 'bg-slate-800 text-slate-200 ring-1 ring-slate-700 hover:bg-slate-700' ?>">
                            <?= $p ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages): ?>
                        <a href="<?= htmlspecialchars($buildPageUrl($currentPage + 1)) ?>" class="rounded-lg bg-slate-800 px-3 py-2 text-sm font-medium text-slate-200 ring-1 ring-slate-700 hover:bg-slate-700 transition-colors">Next</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>





    </div>
</div>















<script>
    const APP_BASE_PATH = <?= json_encode(rtrim(dirname($_SERVER['SCRIPT_NAME']), '/')) ?>;

    function appUrl(path) {
        const normalizedPath = path.startsWith('/') ? path : `/${path}`;
        return `${APP_BASE_PATH}${normalizedPath}`;
    }

    function toggleOrderDetails(orderId) {
        const detailsRow = document.getElementById(`order-details-${orderId}`);
        if (!detailsRow) {
            return;
        }

        detailsRow.classList.toggle('hidden');
    }

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
            const response = await fetch(appUrl('/orders/status'), {
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

    document.addEventListener('DOMContentLoaded', () => {
        const statusSelects = document.querySelectorAll('select[data-order-id]');
        statusSelects.forEach((selectEl) => {
            selectEl.addEventListener('change', () => updateOrderStatus(selectEl));
        });
    });
</script>

<?php View::renderComponent("footer"); ?>