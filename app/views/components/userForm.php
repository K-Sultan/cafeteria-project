<?php
// Determine if we are editing or adding
$isEdit = isset($user) && !empty($user['id']);
$actionUrl = $isEdit ? "/users/update/" . $user['id'] : "/users";
?>

<div class="rounded-2xl border border-gray-700/50 bg-gradient-to-r from-gray-800/80 to-gray-800/40 p-6 sm:p-8 backdrop-blur-md shadow-lg">
    <form action="<?= $actionUrl ?>" method="POST" enctype="multipart/form-data" class="space-y-6">

        <div>
            <label for="name" class="block text-sm font-medium text-gray-200 mb-2">Full Name</label>
            <input
                id="name"
                name="name"
                type="text"
                value="<?= $isEdit ? htmlspecialchars($user['name']) : '' ?>"
                class="block w-full rounded-xl border-0 bg-gray-900/80 py-3 px-4 text-white shadow-inner ring-1 ring-inset ring-gray-700 placeholder:text-gray-500 focus:ring-2 focus:ring-indigo-500 sm:text-sm"
                placeholder="John Doe"
                required>
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-200 mb-2">Email Address</label>
            <input
                id="email"
                name="email"
                type="email"
                value="<?= $isEdit ? htmlspecialchars($user['email']) : '' ?>"
                class="block w-full rounded-xl border-0 bg-gray-900/80 py-3 px-4 text-white shadow-inner ring-1 ring-inset ring-gray-700 placeholder:text-gray-500 focus:ring-2 focus:ring-indigo-500 sm:text-sm"
                placeholder="john@example.com"
                required>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="password" class="block text-sm font-medium text-gray-200 mb-2">
                    <?= $isEdit ? "New Password" : "Password" ?>
                </label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    <?= !$isEdit ? 'required' : '' ?>
                    class="block w-full rounded-xl border-0 bg-gray-900/80 py-3 px-4 text-white shadow-inner ring-1 ring-inset ring-gray-700 focus:ring-2 focus:ring-indigo-500 sm:text-sm">
                <?php if ($isEdit): ?>
                    <p class="mt-1 text-[10px] text-gray-500 italic">Leave blank to keep current password</p>
                <?php endif; ?>
            </div>

            <div>
                <label for="confirm_password" class="block text-sm font-medium text-gray-200 mb-2">Confirm Password</label>
                <input
                    id="confirm_password"
                    name="confirm_password"
                    type="password"
                    <?= !$isEdit ? 'required' : '' ?>
                    class="block w-full rounded-xl border-0 bg-gray-900/80 py-3 px-4 text-white shadow-inner ring-1 ring-inset ring-gray-700 focus:ring-2 focus:ring-indigo-500 sm:text-sm">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="room_no" class="block text-sm font-medium text-gray-200 mb-2">Room No.</label>
                <input
                    id="room_no"
                    name="room_no"
                    type="text"
                    value="<?= $isEdit ? htmlspecialchars($user['room_no']) : '' ?>"
                    class="block w-full rounded-xl border-0 bg-gray-900/80 py-3 px-4 text-white shadow-inner ring-1 ring-inset ring-gray-700 focus:ring-2 focus:ring-indigo-500 sm:text-sm"
                    required>
            </div>

            <div>
                <label for="extension" class="block text-sm font-medium text-gray-200 mb-2">Extension</label>
                <input
                    id="extension"
                    name="extension"
                    type="text"
                    value="<?= $isEdit ? htmlspecialchars($user['extension'] ?? '') : '' ?>"
                    class="block w-full rounded-xl border-0 bg-gray-900/80 py-3 px-4 text-white shadow-inner ring-1 ring-inset ring-gray-700 focus:ring-2 focus:ring-indigo-500 sm:text-sm"
                    required>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-200 mb-2">Profile Picture</label>
            <div class="flex items-center gap-4">
                <?php if ($isEdit && !empty($user['profile_pic'])): ?>
                    <img src="/public/uploads/<?= $user['profile_pic'] ?>" class="h-14 w-14 rounded-xl object-cover ring-2 ring-indigo-500/50">
                <?php endif; ?>
                <input
                    id="profile_pic"
                    name="profile_pic"
                    type="file"
                    accept="image/*"
                    class="block w-full text-sm text-gray-300 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-500/90 file:px-4 file:py-2 file:font-medium file:text-white hover:file:bg-indigo-500 cursor-pointer"
                    <?= !$isEdit ? 'required' : '' ?>>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 pt-4">
            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-indigo-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 hover:bg-indigo-400 transition-colors">
                <?= $isEdit ? "Update User" : "Save User" ?>
            </button>
            <a href="/users" class="inline-flex items-center justify-center rounded-xl bg-gray-800/80 px-6 py-3 text-sm font-semibold text-gray-200 ring-1 ring-gray-700 hover:bg-gray-700/80 transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>