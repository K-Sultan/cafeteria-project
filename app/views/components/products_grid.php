<div class="col-span-1 lg:col-span-2 flex flex-col space-y-8">
    
    <!-- Top Bar: Title & Search -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h1 class="text-3xl font-extrabold text-white tracking-tight hidden sm:block">Menu</h1>
        <div class="relative w-full sm:w-80 ml-auto">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                </svg>
            </div>
            <input type="text" id="product-search" class="block w-full rounded-full border-0 bg-gray-800/60 py-2.5 pl-11 pr-4 text-white shadow-inner ring-1 ring-inset ring-gray-700 placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6 backdrop-blur-xl transition-all" placeholder="Search menu...">
        </div>
    </div>

    <!-- Conditional Section: Admin vs User -->
    <div class="bg-gradient-to-r from-gray-800/80 to-gray-800/40 rounded-2xl p-6 border border-gray-700/50 backdrop-blur-md shadow-lg">
        <?php if ($isAdmin ?? false): ?>
            <!-- Admin View: Select User -->
             <div class="flex items-center gap-3 mb-2">
                <div class="p-2 bg-indigo-500/20 rounded-lg">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <h3 class="text-lg font-semibold text-white">Place order for</h3>
            </div>
            <div class="max-w-md">
                <select id="user_select" name="user_id" class="block w-full rounded-xl border-0 bg-gray-900/80 py-3 pl-4 pr-10 text-white shadow-inner ring-1 ring-inset ring-gray-700 focus:ring-2 focus:ring-indigo-500 sm:text-base sm:leading-6 transition-all appearance-none cursor-pointer">
                    <option value="" disabled selected>Select a user to bill...</option>
                    <?php if(isset($users)) foreach ($users as $user): ?>
                        <option value="<?= htmlspecialchars($user['id']) ?>"><?= htmlspecialchars($user['name']) ?> (<?= htmlspecialchars($user['email']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php else: ?>
            <!-- User View: Latest Order -->
            <div class="flex items-center gap-3 mb-2">
                <div class="p-2 bg-indigo-500/20 rounded-lg">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-lg font-semibold text-white">Latest Order</h3>
            </div>
            
            <?php if (empty($latestOrderItems)): ?>
                <p class="text-sm text-gray-400 italic bg-gray-900/30 p-4 rounded-xl inline-block border border-gray-700/30">You have no recent orders yet. Items you order will appear here for quick access!</p>
            <?php else: ?>
                <div class="flex space-x-4 overflow-x-auto pb-4 custom-scrollbar">
                    <?php foreach ($latestOrderItems as $item): ?>
                        <div class="flex-shrink-0 w-24 sm:w-28 group relative bg-gray-900/40 rounded-xl border border-gray-700/50 overflow-hidden cursor-pointer hover:bg-gray-700/60 transition-colors" onclick="addToCart(<?= $item['id'] ?>, '<?= htmlspecialchars(addslashes($item['name'])) ?>', <?= $item['price'] ?>)">
                            <div class="aspect-square bg-gray-800/50 p-2 flex items-center justify-center relative">
                                <?php if (!empty($item['image'])): ?>
                                    <img src="<?= htmlspecialchars('/public/uploads/' . $item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="object-cover w-full h-full rounded-lg" onerror="this.src='https://placehold.co/100x100/1f2937/a5b4fc?text=<?= urlencode(substr($item['name'], 0, 1)) ?>'">
                                <?php else: ?>
                                    <div class="w-full h-full rounded-lg bg-gradient-to-br from-gray-700 to-gray-800 flex items-center justify-center border border-gray-600/30">
                                        <span class="text-2xl font-bold text-gray-500"><?= htmlspecialchars(substr($item['name'], 0, 1)) ?></span>
                                    </div>
                                <?php endif; ?>
                                <div class="absolute inset-0 bg-indigo-500/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </div>
                            </div>
                            <div class="p-2 text-center text-xs truncate text-gray-300 group-hover:text-white transition-colors">
                                <?= htmlspecialchars($item['name']) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Products Grid -->
    <div>
        <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
            <?php if(isset($products)) foreach ($products as $product): ?>
            <!-- Product Card -->
            <div class="product-card group relative bg-gray-800/40 rounded-2xl border border-gray-700/50 overflow-hidden backdrop-blur-sm hover:bg-gray-700/60 transition-all duration-300 hover:shadow-[0_8px_30px_rgb(0,0,0,0.4)] hover:shadow-indigo-500/10 hover:-translate-y-1 cursor-pointer" data-name="<?= htmlspecialchars(addslashes($product['name'])) ?>" onclick="addToCart(<?= $product['id'] ?>, '<?= htmlspecialchars(addslashes($product['name'])) ?>', <?= $product['price'] ?>)">
                <!-- Price Badge -->
                <div class="absolute top-3 right-3 z-10 bg-gray-900/80 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg backdrop-blur-md border border-gray-700/50 group-hover:bg-indigo-500 group-hover:border-indigo-400 transition-colors">
                    <?= number_format($product['price'], 0) ?> LE
                </div>
                
                <div class="aspect-square bg-gray-900/30 p-2 sm:p-4 flex items-center justify-center relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity z-0"></div>
                    
                    <?php if (!empty($product['image'])): ?>
                        <img src="<?= htmlspecialchars('/public/uploads/' . $product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="object-cover w-full h-full rounded-xl transition-transform duration-700 group-hover:scale-110 relative z-10" onerror="this.src='https://placehold.co/400x400/1f2937/a5b4fc?text=<?= urlencode(substr($product['name'], 0, 1)) ?>'">
                    <?php else: ?>
                        <!-- Fallback Placeholder -->
                        <div class="w-full h-full rounded-xl bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center group-hover:from-gray-700 group-hover:to-gray-800 transition-colors relative z-10 border border-gray-700/30">
                            <span class="text-5xl font-bold text-gray-600 group-hover:text-indigo-400/50 transition-colors"><?= htmlspecialchars(substr($product['name'], 0, 1)) ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Quick Add Icon overlay -->
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 z-20 scale-50 group-hover:scale-100">
                        <div class="bg-indigo-500 text-white rounded-full p-3 shadow-xl transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </div>
                    </div>
                </div>
                
                <div class="p-4 text-center border-t border-gray-700/50 bg-gray-800/90 relative z-20">
                    <h3 class="font-bold text-gray-200 truncate text-base sm:text-lg group-hover:text-white transition-colors"><?= htmlspecialchars($product['name']) ?></h3>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php if (empty($products)): ?>
            <div class="text-center py-20 bg-gray-800/20 rounded-3xl border border-gray-700/30 border-dashed backdrop-blur-sm mt-6 mb-12">
                <svg class="mx-auto h-16 w-16 text-gray-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 13.5h3.86a2.25 2.25 0 012.012 1.244l.256.512a2.25 2.25 0 002.013 1.244h3.218a2.25 2.25 0 002.013-1.244l.256-.512a2.25 2.25 0 012.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 00-2.15-1.588H6.911a2.25 2.25 0 00-2.15 1.588L2.35 13.177a2.25 2.25 0 00-.1.661z" />
                </svg>
                <h3 class="mt-2 text-xl font-semibold text-gray-300">No products available</h3>
                <p class="mt-1 text-sm text-gray-500">Check back later or contact an administrator to add items to the menu.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
