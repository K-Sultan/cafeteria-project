<?php $filters = $filters ?? []; ?>

<form method="GET" action="/" class="bg-slate-800 rounded-xl p-4 mb-8 flex flex-wrap gap-4 items-center">
    <input
        type="date"
        name="date"
        value="<?= htmlspecialchars($filters['date'] ?? '') ?>"
        class="bg-slate-900 border border-slate-700 px-4 py-2 rounded-lg"
    />

    <select name="status" class="bg-slate-900 border border-slate-700 px-4 py-2 rounded-lg">
        <option value="">All Status</option>
        <option value="processing" <?= ($filters['status'] ?? '') === 'processing' ? 'selected' : '' ?>>Processing</option>
        <option value="out_for_delivery" <?= ($filters['status'] ?? '') === 'out_for_delivery' ? 'selected' : '' ?>>Out For Delivery</option>
        <option value="done" <?= ($filters['status'] ?? '') === 'done' ? 'selected' : '' ?>>Done</option>
        <option value="cancelled" <?= ($filters['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
    </select>

    <select name="room_no" class="bg-slate-900 border border-slate-700 px-4 py-2 rounded-lg">
        <option value="">All Rooms</option>
        <?php foreach ($rooms as $room): ?>
            <?php $roomNo = (string) ($room['room_no'] ?? ''); ?>
            <option value="<?= htmlspecialchars($roomNo) ?>" <?= ($filters['room_no'] ?? '') === $roomNo ? 'selected' : '' ?>>
                <?= htmlspecialchars($roomNo) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="user_id" class="bg-slate-900 border border-slate-700 px-4 py-2 rounded-lg">
        <option value="">All Users</option>
        <?php foreach ($users as $user): ?>
            <?php $userId = (string) ($user['id'] ?? ''); ?>
            <option value="<?= htmlspecialchars($userId) ?>" <?= ($filters['user_id'] ?? '') === $userId ? 'selected' : '' ?>>
                <?= htmlspecialchars($user['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-lg">
        Filter
    </button>
</form>