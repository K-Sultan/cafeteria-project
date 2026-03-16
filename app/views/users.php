<?php View::renderComponent("header"); ?>

<div class="flex-1 bg-gray-900">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
        <div class="sm:flex sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-white">All Users</h1>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0">
                <a href="/users/add" class="block rounded-md bg-indigo-500 px-3 py-2 text-center text-sm font-semibold text-white hover:bg-indigo-400">
                    Add User
                </a>
            </div>
        </div>

        <div class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-white/10 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-white/10 bg-gray-900/50">
                            <thead class="bg-white/5">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-white sm:pl-6">Name</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Room</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Image</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Ext.</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-white sm:pl-6">
                                            <?= htmlspecialchars($user['name']) ?>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                            <?= htmlspecialchars($user['room_no']) ?>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                            <?php if (!empty($user['profile_pic'])): ?>
                                                <img src="/public/uploads/<?= $user['profile_pic'] ?>"
                                                    alt="Profile"
                                                    class="h-10 w-10 rounded-full object-cover border border-white/10">
                                            <?php else: ?>
                                                <div class="h-10 w-10 rounded-full bg-white/10 flex items-center justify-center text-xs text-gray-500">N/A</div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                            <?= htmlspecialchars($user['extension'] ?? 'N/A') ?>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm font-medium space-x-3">
                                            <a href="/users/edit/<?= $user['id'] ?>"
                                                class="text-blue-400">Edit</a>

                                            <button onclick="deleteUser(<?= $user['id'] ?>)"
                                                class="text-red-400 ml-4">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <?php View::renderComponent("pagination", ["pagination" => $pagination, "baseUrl" => "/users"]); ?>
    </div>
</div>

<script>
    function deleteUser(id) {

        if (confirm("Are you sure you want to delete this user?")) {
            window.location.href = "/users/delete/" + id;
        }

    }
</script>

<?php View::renderComponent("footer"); ?>