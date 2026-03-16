<?php View::renderComponent("header"); ?>

<?php
$errors = $_SESSION['category_errors'] ?? [];
$success = $_SESSION['category_success'] ?? null;
unset($_SESSION['category_errors'], $_SESSION['category_success']);
?>

<div class="bg-gray-900 min-h-screen pt-6 pb-10 relative">
    <div class="absolute top-0 inset-x-0 h-96 bg-gradient-to-b from-indigo-900/20 to-transparent pointer-events-none"></div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-white">Manage Categories</h1>
                <p class="mt-1 text-sm text-gray-400">Create and delete product categories.</p>
            </div>
            <a href="/products" class="inline-flex items-center rounded-lg bg-gray-800/80 px-4 py-2 text-sm font-medium text-gray-200 ring-1 ring-gray-700 hover:bg-gray-700/80 transition-colors">Back to Products</a>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="mb-6 rounded-2xl border border-red-500/30 bg-red-500/10 p-4 text-red-200 backdrop-blur-sm">
                <h2 class="text-sm font-semibold">Please fix the following errors:</h2>
                <ul class="mt-2 space-y-1 text-sm">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="mb-6 rounded-2xl border border-emerald-500/30 bg-emerald-500/10 p-4 text-emerald-200 backdrop-blur-sm">
                <p class="text-sm font-semibold"><?= htmlspecialchars($success) ?></p>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1 rounded-2xl border border-gray-700/50 bg-gradient-to-r from-gray-800/80 to-gray-800/40 p-6 backdrop-blur-md shadow-lg">
                <h2 class="text-lg font-semibold text-white mb-4">Add Category</h2>
                <form action="/categories" method="POST" class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-200 mb-2">Category Name</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            class="block w-full rounded-xl border-0 bg-gray-900/80 py-3 px-4 text-white shadow-inner ring-1 ring-inset ring-gray-700 placeholder:text-gray-500 focus:ring-2 focus:ring-indigo-500 sm:text-sm"
                            placeholder="Coffee"
                            required
                        >
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-indigo-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 hover:bg-indigo-400 transition-colors cursor-pointer">Create Category</button>
                </form>
            </div>

            <div class="lg:col-span-2 rounded-2xl border border-gray-700/50 bg-gray-800/40 shadow-lg overflow-hidden backdrop-blur-sm">
                <div class="px-5 py-4 border-b border-white/10">
                    <h2 class="text-lg font-semibold text-white">Existing Categories</h2>
                </div>

                <?php if (empty($categories)): ?>
                    <div class="py-14 text-center text-sm text-gray-500">No categories found.</div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-white/10">
                            <thead class="bg-white/5">
                                <tr>
                                    <th class="py-3.5 pl-5 pr-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">#</th>
                                    <th class="py-3.5 px-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">Name</th>
                                    <th class="py-3.5 px-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <?php foreach ($categories as $i => $category): ?>
                                    <tr class="hover:bg-white/[0.03] transition-colors">
                                        <td class="whitespace-nowrap py-4 pl-5 pr-3 text-sm text-gray-500"><?= $i + 1 ?></td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm font-medium text-white"><?= htmlspecialchars($category['name']) ?></td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                                            <form method="POST" action="/categories/delete" class="inline" onsubmit="return confirm('Delete category \'<?= htmlspecialchars(addslashes($category['name'])) ?>\'?')">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="id" value="<?= (int)$category['id'] ?>">
                                                <button type="submit" class="inline-flex items-center rounded-lg bg-red-500/20 px-3 py-1.5 text-xs font-semibold text-red-300 ring-1 ring-red-500/30 hover:bg-red-500/30 hover:text-red-200 transition-colors cursor-pointer">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php View::renderComponent("footer"); ?>