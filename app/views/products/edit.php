<?php View::renderComponent("header"); ?>

<?php
$errors = $_SESSION['product_edit_errors'] ?? [];
$old    = $_SESSION['product_edit_old'] ?? [];
unset($_SESSION['product_edit_errors'], $_SESSION['product_edit_old']);

$formData = [
    'id' => $product['id'],
    'name' => $old['name'] ?? $product['name'],
    'price' => $old['price'] ?? $product['price'],
    'category_id' => $old['category_id'] ?? $product['category_id'],
    'is_available' => isset($old['is_available']) ? (int)$old['is_available'] : (int)$product['is_available'],
    'image' => $product['image'] ?? null
];
?>

<div class="bg-gray-900 min-h-95vh pt-6 pb-10 relative">
    <div class="absolute top-0 inset-x-0 h-96 bg-gradient-to-b from-indigo-900/20 to-transparent pointer-events-none"></div>
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="mb-6 flex items-center justify-between gap-3">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-white">Edit Product</h1>
                <p class="mt-2 text-sm text-gray-400">Update product details and optionally upload a new image.</p>
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

        <div class="rounded-2xl border border-gray-700/50 bg-gradient-to-r from-gray-800/80 to-gray-800/40 p-6 sm:p-8 backdrop-blur-md shadow-lg">
            <form action="/products/update" method="POST" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="id" value="<?= (int)$formData['id'] ?>">

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-200 mb-2">Product Name</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="<?= htmlspecialchars($formData['name']) ?>"
                        class="block w-full rounded-xl border-0 bg-gray-900/80 py-3 px-4 text-white shadow-inner ring-1 ring-inset ring-gray-700 placeholder:text-gray-500 focus:ring-2 focus:ring-indigo-500 sm:text-sm"
                        placeholder="Cappuccino"
                        required
                    >
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-200 mb-2">Price</label>
                        <input
                            id="price"
                            name="price"
                            type="number"
                            step="0.01"
                            min="0"
                            value="<?= htmlspecialchars((string)$formData['price']) ?>"
                            class="block w-full rounded-xl border-0 bg-gray-900/80 py-3 px-4 text-white shadow-inner ring-1 ring-inset ring-gray-700 placeholder:text-gray-500 focus:ring-2 focus:ring-indigo-500 sm:text-sm"
                            placeholder="35"
                            required
                        >
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-200 mb-2">Category</label>
                        <select
                            id="category_id"
                            name="category_id"
                            class="block w-full rounded-xl border-0 bg-gray-900/80 py-3 px-4 text-white shadow-inner ring-1 ring-inset ring-gray-700 focus:ring-2 focus:ring-indigo-500 sm:text-sm"
                            required
                        >
                            <option value="">Select category</option>
                            <?php foreach (($categories ?? []) as $category): ?>
                                <option
                                    value="<?= htmlspecialchars($category['id']) ?>"
                                    <?= ((string)$formData['category_id'] === (string)$category['id']) ? 'selected' : '' ?>
                                >
                                    <?= htmlspecialchars($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-2">Current Image</label>
                    <?php if (!empty($formData['image'])): ?>
                        <img
                            src="/public/uploads/<?= htmlspecialchars($formData['image']) ?>"
                            alt="<?= htmlspecialchars($formData['name']) ?>"
                            class="h-24 w-24 rounded-xl object-cover border border-white/10"
                        >
                    <?php else: ?>
                        <p class="text-sm text-gray-400">No image uploaded.</p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium text-gray-200 mb-2">Replace Image (Optional)</label>
                    <input
                        id="image"
                        name="image"
                        type="file"
                        accept=".jpg,.jpeg,.png,.gif,.webp"
                        class="block w-full text-sm text-gray-300 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-500/90 file:px-4 file:py-2 file:font-medium file:text-white hover:file:bg-indigo-500 cursor-pointer"
                    >
                    <p class="mt-2 text-xs text-gray-500">Allowed formats: JPG, JPEG, PNG, GIF, WEBP.</p>
                </div>

                <div class="flex items-center gap-3 rounded-xl border border-gray-700/50 bg-gray-900/60 px-4 py-3">
                    <input
                        id="is_available"
                        name="is_available"
                        type="checkbox"
                        value="1"
                        <?= (int)$formData['is_available'] === 1 ? 'checked' : '' ?>
                        class="h-4 w-4 rounded border-gray-600 bg-gray-800 text-indigo-500 focus:ring-indigo-500"
                    >
                    <label for="is_available" class="text-sm font-medium text-gray-200">Available now</label>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-indigo-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 hover:bg-indigo-400 transition-colors">Update Product</button>
                    <a href="/products" class="inline-flex items-center justify-center rounded-xl bg-gray-800/80 px-6 py-3 text-sm font-semibold text-gray-200 ring-1 ring-gray-700 hover:bg-gray-700/80 transition-colors">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php View::renderComponent("footer"); ?>
