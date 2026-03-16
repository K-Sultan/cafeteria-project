<?php View::renderComponent("header"); ?>

<?php
$errors = $_SESSION['errors'] ?? [];
$old    = $_SESSION['old']    ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
?>

<div class="bg-gray-900 min-h-95vh pt-6 pb-10 relative">
	<div class="absolute top-0 inset-x-0 h-96 bg-gradient-to-b from-indigo-900/20 to-transparent pointer-events-none"></div>
	<div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

	<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
		<div class="mb-6 flex items-center justify-between gap-3">
			<div>
				<h1 class="text-3xl font-extrabold tracking-tight text-white">Add Product</h1>
				<p class="mt-2 text-sm text-gray-400">Create a new menu item with image, category, and availability.</p>
			</div>
			<a href="/home" class="inline-flex items-center rounded-lg bg-gray-800/80 px-4 py-2 text-sm font-medium text-gray-200 ring-1 ring-gray-700 hover:bg-gray-700/80 transition-colors">Back to Home</a>
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
			<form action="/products" method="POST" enctype="multipart/form-data" class="space-y-6">
				<div>
					<label for="name" class="block text-sm font-medium text-gray-200 mb-2">Product Name</label>
					<input
						id="name"
						name="name"
						type="text"
						value="<?= htmlspecialchars($old['name'] ?? '') ?>"
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
							value="<?= htmlspecialchars($old['price'] ?? '') ?>"
							class="block w-full rounded-xl border-0 bg-gray-900/80 py-3 px-4 text-white shadow-inner ring-1 ring-inset ring-gray-700 placeholder:text-gray-500 focus:ring-2 focus:ring-indigo-500 sm:text-sm"
							placeholder="35"
							required
						>
					</div>

					<div>
						<label for="category_id" class="block text-sm font-medium text-gray-200 mb-2">Category</label>
						<div class="flex gap-2">
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
										<?= ((string)($old['category_id'] ?? '') === (string)$category['id']) ? 'selected' : '' ?>
									>
										<?= htmlspecialchars($category['name']) ?>
									</option>
								<?php endforeach; ?>
							</select>
							<button type="button" onclick="openCategoryModal()" class="inline-flex items-center justify-center rounded-xl bg-gray-800/80 px-4 py-3 text-sm font-medium text-gray-200 ring-1 ring-gray-700 hover:bg-gray-700/80 transition-colors whitespace-nowrap cursor-pointer">
								+ Add
							</button>
						</div>
					</div>
				</div>

				<div>
					<label for="image" class="block text-sm font-medium text-gray-200 mb-2">Product Image</label>
					<input
						id="image"
						name="image"
						type="file"
						accept=".jpg,.jpeg,.png,.gif,.webp"
						class="block w-full text-sm text-gray-300 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-500/90 file:px-4 file:py-2 file:font-medium file:text-white hover:file:bg-indigo-500 cursor-pointer"
						required
					>
					<p class="mt-2 text-xs text-gray-500">Allowed formats: JPG, JPEG, PNG, GIF, WEBP.</p>
				</div>

				<div class="flex items-center gap-3 rounded-xl border border-gray-700/50 bg-gray-900/60 px-4 py-3">
					<input
						id="is_available"
						name="is_available"
						type="checkbox"
						value="1"
						<?= !isset($old['is_available']) || (int)$old['is_available'] === 1 ? 'checked' : '' ?>
						class="h-4 w-4 rounded border-gray-600 bg-gray-800 text-indigo-500 focus:ring-indigo-500"
					>
					<label for="is_available" class="text-sm font-medium text-gray-200">Available now</label>
				</div>

				<div class="flex flex-col sm:flex-row gap-3 pt-2">
					<button type="submit" class="inline-flex items-center justify-center rounded-xl bg-indigo-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 hover:bg-indigo-400 transition-colors cursor-pointer">Save Product</button>
					<a href="/home" class="inline-flex items-center justify-center rounded-xl bg-gray-800/80 px-6 py-3 text-sm font-semibold text-gray-200 ring-1 ring-gray-700 hover:bg-gray-700/80 transition-colors cursor-pointer">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>

<?php View::renderComponent("footer"); ?>

<!-- Category Modal -->
<div id="categoryModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" onclick="closeCategoryModal()"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md ring-1 ring-gray-700">
                <div class="bg-gray-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg font-semibold leading-6 text-white" id="modal-title">Add New Category</h3>
                            <div class="mt-4">
                                <input type="text" id="new_category_name" class="block w-full rounded-xl border-0 bg-gray-900/80 py-3 px-4 text-white shadow-inner ring-1 ring-inset ring-gray-700 placeholder:text-gray-500 focus:ring-2 focus:ring-indigo-500 sm:text-sm" placeholder="Category Name" onkeydown="if(event.key === 'Enter'){ saveCategory(); event.preventDefault(); }">
                                <p id="category_error" class="mt-2 text-sm text-red-500 hidden"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-800/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" onclick="saveCategory()" class="inline-flex w-full justify-center rounded-xl bg-indigo-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-400 sm:ml-3 sm:w-auto transition-colors cursor-pointer">Save</button>
                    <button type="button" onclick="closeCategoryModal()" class="mt-3 inline-flex w-full justify-center rounded-xl bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-200 shadow-sm ring-1 ring-inset ring-gray-600 hover:bg-gray-600 sm:mt-0 sm:w-auto transition-colors cursor-pointer">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openCategoryModal() {
    document.getElementById('category_error').classList.add('hidden');
    document.getElementById('new_category_name').value = '';
    document.getElementById('categoryModal').classList.remove('hidden');
    document.getElementById('new_category_name').focus();
}

function closeCategoryModal() {
    document.getElementById('categoryModal').classList.add('hidden');
}

async function saveCategory() {
    const nameInput = document.getElementById('new_category_name');
    const name = nameInput.value.trim();
    const errorEl = document.getElementById('category_error');
    
    if (!name) {
        errorEl.textContent = 'Category name is required.';
        errorEl.classList.remove('hidden');
        nameInput.focus();
        return;
    }

    try {
        const formData = new FormData();
        formData.append('name', name);

        const response = await fetch('/categories', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            // Add new option to dropdown and select it
            const select = document.getElementById('category_id');
            const option = document.createElement('option');
            option.value = data.category.id;
            // set HTML safely
            const tempDiv = document.createElement('div');
            tempDiv.textContent = data.category.name;
            option.textContent = tempDiv.innerHTML;
            
            select.appendChild(option);
            select.value = data.category.id;
            
            closeCategoryModal();
        } else {
            errorEl.textContent = data.message || 'Failed to add category.';
            errorEl.classList.remove('hidden');
        }
    } catch (e) {
        errorEl.textContent = 'An error occurred. Please try again.';
        errorEl.classList.remove('hidden');
    }
}
</script>
