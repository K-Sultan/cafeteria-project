<?php
// Determine if we are editing or adding
$isEdit = isset($user) && !empty($user['id']);
$actionUrl = $isEdit ? "/users/update/" . $user['id'] : "/users";
?>

<form action="<?= $actionUrl ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6 bg-gray-700/50 p-6 rounded-lg">
        <div class="sm:col-span-4">
            <label for="name" class="block text-sm font-medium text-white">Full Name</label>
            <input type="text" name="name" id="name" value="<?= $isEdit ? htmlspecialchars($user['name']) : '' ?>"
                class="mt-1 block w-full p-2 border-white/10 bg-white/5 text-white focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        </div>

        <div class="sm:col-span-4">
            <label for="email" class="block text-sm font-medium text-white">Email Address</label>
            <input type="email" name="email" id="email" value="<?= $isEdit ? htmlspecialchars($user['email']) : '' ?>"
                class="mt-1 block w-full p-2 border-white/10 bg-white/5 text-white focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        </div>

        <div class="sm:col-span-3">
            <label for="password" class="block text-sm font-medium text-white">
                <?= $isEdit ? "New Password (leave blank to keep current)" : "Password" ?>
            </label>
            <input type="password" name="password" id="password"
                class="mt-1 block w-full p-2 border-white/10 bg-white/5 text-white focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        </div>

        <div class="sm:col-span-3">
            <label for="confirm_password" class="block text-sm font-medium text-white">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password"
                class="mt-1 block w-full p-2 border-white/10 bg-white/5 text-white focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        </div>

        <div class="sm:col-span-3">
            <label for="room_no" class="block text-sm font-medium text-white">Room No.</label>
            <input type="text" name="room_no" id="room_no" value="<?= $isEdit ? htmlspecialchars($user['room_no']) : '' ?>"
                class="mt-1 block w-full p-2 border-white/10 bg-white/5 text-white focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        </div>

        <div class="sm:col-span-3">
            <label for="extension" class="block text-sm font-medium text-white">Extension</label>
            <input type="text" name="extension" id="extension" value="<?= $isEdit ? htmlspecialchars($user['extension']) : '' ?>"
                class="mt-1 block w-full p-2 border-white/10 bg-white/5 text-white focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        </div>

        <div class="sm:col-span-6">
            <label class="block text-sm font-medium text-white">Profile Picture</label>
            <div class="mt-2 flex items-center gap-x-3">
                <?php if ($isEdit && !empty($user['profile_pic'])): ?>
                    <img src="/public/uploads/<?= $user['profile_pic'] ?>" class="h-12 w-12 rounded-full object-cover">
                <?php endif; ?>
                <input type="file" name="profile_pic" class="text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-500 file:text-white hover:file:bg-indigo-400">
            </div>
        </div>
    </div>

    <div class="pt-5">
        <div class="flex justify-end gap-x-3">
            <a href="/users" class="rounded-md bg-white/10 px-3 py-2 text-sm font-semibold text-white hover:bg-white/20">Cancel</a>
            <button type="submit" class="rounded-md bg-indigo-500 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-400">
                <?= $isEdit ? "Update User" : "Save User" ?>
            </button>
        </div>
    </div>
</form>