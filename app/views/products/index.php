<?php View::renderComponent("header"); ?>

<div class="bg-gray-900 min-h-screen pt-6 pb-10 relative">
    <div class="absolute top-0 inset-x-0 h-96 bg-gradient-to-b from-indigo-900/20 to-transparent pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        <!-- Header row -->
        <div class="flex items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-white">Products</h1>
                <p class="mt-1 text-sm text-gray-400">All menu items &mdash; <?= count($products) ?> total</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="/categories"
                   class="inline-flex items-center gap-2 rounded-xl bg-gray-800/80 px-5 py-2.5 text-sm font-semibold text-gray-200 ring-1 ring-gray-700 hover:bg-gray-700/80 transition-colors">
                    Manage Categories
                </a>
                <a href="/products/create"
                   class="inline-flex items-center gap-2 rounded-xl bg-indigo-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 hover:bg-indigo-400 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Product
                </a>
            </div>
        </div>

        <!-- Filter bar -->
        <div class="mb-6 flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1 max-w-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <input id="search-input" type="text" placeholder="Search products…"
                       class="block w-full rounded-xl border-0 bg-gray-800/70 py-2.5 pl-9 pr-4 text-sm text-white ring-1 ring-inset ring-gray-700 placeholder:text-gray-500 focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <?php
                $seenCats = [];
                foreach ($products as $p) {
                    if (!empty($p['category_id']) && !isset($seenCats[$p['category_id']])) {
                        $seenCats[$p['category_id']] = $p['category_name'] ?? $p['category_id'];
                    }
                }
            ?>
            <select id="category-filter"
                    class="rounded-xl border-0 bg-gray-800/70 py-2.5 px-4 text-sm text-white ring-1 ring-inset ring-gray-700 focus:ring-2 focus:ring-indigo-500 outline-none">
                <option value="">All categories</option>
                <?php foreach ($seenCats as $catId => $catName): ?>
                    <option value="<?= htmlspecialchars($catId) ?>">
                        <?= htmlspecialchars($catName) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select id="availability-filter"
                    class="rounded-xl border-0 bg-gray-800/70 py-2.5 px-4 text-sm text-white ring-1 ring-inset ring-gray-700 focus:ring-2 focus:ring-indigo-500 outline-none">
                <option value="">All statuses</option>
                <option value="1">Available</option>
                <option value="0">Unavailable</option>
            </select>
        </div>

        <!-- Table card -->
        <div class="rounded-2xl border border-gray-700/50 bg-gray-800/40 shadow-lg overflow-hidden backdrop-blur-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/10" id="products-table">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="py-3.5 pl-5 pr-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">#</th>
                            <th class="py-3.5 px-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">Image</th>
                            <th class="py-3.5 px-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">Name</th>
                            <th class="py-3.5 px-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">Category</th>
                            <th class="py-3.5 px-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">Price</th>
                            <th class="py-3.5 px-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">Status</th>
                            <th class="py-3.5 px-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5" id="products-body">
                        <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="7" class="py-16 text-center text-sm text-gray-500">No products found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($products as $i => $product): ?>
                                <tr class="product-row hover:bg-white/[0.03] transition-colors"
                                    data-name="<?= htmlspecialchars(strtolower($product['name'])) ?>"
                                    data-category="<?= htmlspecialchars($product['category_id']) ?>"
                                    data-available="<?= $product['is_available'] ?>">

                                    <td class="whitespace-nowrap py-4 pl-5 pr-3 text-sm text-gray-500">
                                        <?= $i + 1 ?>
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-3">
                                        <?php if (!empty($product['image'])): ?>
                                            <img src="/public/uploads/<?= htmlspecialchars($product['image']) ?>"
                                                 alt="<?= htmlspecialchars($product['name']) ?>"
                                                 class="h-12 w-12 rounded-xl object-cover border border-white/10"
                                                 onerror="this.src='https://placehold.co/48x48/1f2937/a5b4fc?text=<?= urlencode(substr($product['name'], 0, 1)) ?>'">
                                        <?php else: ?>
                                            <div class="h-12 w-12 rounded-xl bg-gray-700/60 border border-white/10 flex items-center justify-center">
                                                <span class="text-lg font-bold text-gray-500"><?= htmlspecialchars(substr($product['name'], 0, 1)) ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-4 text-sm font-medium text-white">
                                        <?= htmlspecialchars($product['name']) ?>
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">
                                        <?= htmlspecialchars($product['category_name'] ?? '—') ?>
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-4 text-sm font-semibold text-white">
                                        <?= number_format($product['price'], 0) ?> <span class="text-gray-500 font-normal">LE</span>
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-4">
                                        <?php if ($product['is_available']): ?>
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/10 px-2.5 py-1 text-xs font-semibold text-emerald-400 ring-1 ring-inset ring-emerald-500/20">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                                                Available
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-red-500/10 px-2.5 py-1 text-xs font-semibold text-red-400 ring-1 ring-inset ring-red-500/20">
                                                <span class="h-1.5 w-1.5 rounded-full bg-red-400"></span>
                                                Unavailable
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                                        <div class="flex items-center gap-2">
                                            <form method="POST" action="/products/availability" class="inline">
                                                <input type="hidden" name="id" value="<?= $product['id'] ?>">
                                                <input type="hidden" name="is_available" value="<?= $product['is_available'] ? '0' : '1' ?>">
                                                <button type="submit"
                                                        class="inline-flex items-center rounded-lg px-3 py-1.5 text-xs font-semibold transition-colors cursor-pointer <?= $product['is_available']
                                                            ? 'bg-amber-500/20 text-amber-300 ring-1 ring-amber-500/30 hover:bg-amber-500/30 hover:text-amber-200'
                                                            : 'bg-emerald-500/20 text-emerald-300 ring-1 ring-emerald-500/30 hover:bg-emerald-500/30 hover:text-emerald-200' ?>">
                                                    <?= $product['is_available'] ? 'Mark Unavailable' : 'Mark Available' ?>
                                                </button>
                                            </form>
                                            <a href="/products/edit?id=<?= $product['id'] ?>"
                                               class="inline-flex items-center rounded-lg bg-indigo-500/20 px-3 py-1.5 text-xs font-semibold text-indigo-300 ring-1 ring-indigo-500/30 hover:bg-indigo-500/30 hover:text-indigo-200 transition-colors">Edit</a>
                                            <form method="POST" action="/products/delete" class="inline"
                                                  onsubmit="return confirm('Delete \'<?= htmlspecialchars(addslashes($product['name'])) ?>\'?')">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="id" value="<?= $product['id'] ?>">
                                                <button type="submit" class="inline-flex items-center rounded-lg bg-red-500/20 px-3 py-1.5 text-xs font-semibold text-red-300 ring-1 ring-red-500/30 hover:bg-red-500/30 hover:text-red-200 transition-colors hover:bg-red-500/30 hover:text-red-200 transition-colors cursor-pointer">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Empty-state overlay shown by JS when filters match nothing -->
            <div id="no-results" class="hidden py-16 text-center text-sm text-gray-500">
                No products match your filters.
            </div>
        </div>
    </div>
</div>

<script>
    const searchInput      = document.getElementById('search-input');
    const categoryFilter   = document.getElementById('category-filter');
    const availabilityFilter = document.getElementById('availability-filter');
    const rows             = document.querySelectorAll('.product-row');
    const noResults        = document.getElementById('no-results');

    function applyFilters() {
        const term     = searchInput.value.toLowerCase();
        const catId    = categoryFilter.value;
        const avail    = availabilityFilter.value;
        let visible    = 0;

        rows.forEach(row => {
            const nameMatch  = row.dataset.name.includes(term);
            const catMatch   = !catId  || row.dataset.category  === catId;
            const availMatch = avail === '' || row.dataset.available === avail;

            if (nameMatch && catMatch && availMatch) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });

        noResults.classList.toggle('hidden', visible > 0);
    }

    searchInput.addEventListener('input', applyFilters);
    categoryFilter.addEventListener('change', applyFilters);
    availabilityFilter.addEventListener('change', applyFilters);
</script>

<?php View::renderComponent("footer"); ?>
