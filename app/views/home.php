<?php View::renderComponent("header"); ?>

<div class="bg-gray-900 min-h-95vh pt-4 relative">
    <!-- Decorative background elements -->
    <div class="absolute top-0 inset-x-0 h-96 bg-gradient-to-b from-indigo-900/20 to-transparent pointer-events-none"></div>
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Side: Order Panel Constraints -->
            <div class="lg:col-span-1 order-2 lg:order-1 mt-8 lg:mt-0">
                <?php View::renderComponent("order_panel", [
                    'isAdmin' => $isAdmin ?? false,
                    'users' => $users ?? []
                ]); ?>
            </div>

            <!-- Right Side: Products Grid Constraints -->
            <div class="lg:col-span-2 order-1 lg:order-2">
                <?php View::renderComponent("products_grid", [
                    'products' => $products ?? [],
                    'isAdmin' => $isAdmin ?? false,
                    'users' => $users ?? [],
                    'latestOrderItems' => $latestOrderItems ?? []
                ]); ?>
            </div>
        </div>
    </div>
</div>

<script>
    // Minimal Cart Logic for Demo
    let cart = {};

    function addToCart(id, name, price) {
        if (!cart[id]) {
            cart[id] = { name, price, quantity: 1 };
        } else {
            cart[id].quantity++;
        }
        renderCart();
    }

    function updateQuantity(id, change) {
        if (cart[id]) {
            cart[id].quantity += change;
            if (cart[id].quantity <= 0) {
                delete cart[id];
            }
            renderCart();
        }
    }

    function removeCartItem(id) {
        if (cart[id]) {
            delete cart[id];
            renderCart();
        }
    }

    function renderCart() {
        const cartContainer = document.getElementById('cart-items');
        const cartTotalEl = document.getElementById('cart-total');
        
        let html = '';
        let total = 0;
        
        const keys = Object.keys(cart);
        if (keys.length === 0) {
            cartContainer.innerHTML = `
                <div class="flex flex-col items-center justify-center h-full text-center space-y-3 opacity-50 py-10">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <p class="text-gray-400 text-sm">Cart is empty.<br>Select items from the menu.</p>
                </div>
            `;
            cartTotalEl.innerText = '0';
            return;
        }

        keys.forEach(id => {
            const item = cart[id];
            const itemTotal = item.price * item.quantity;
            total += itemTotal;
            
            html += `
            <div class="flex items-center justify-between bg-gray-900/50 rounded-xl p-3 border border-gray-700/50 hover:border-gray-600 transition-colors group">
                <div class="flex-1 truncate pr-2">
                    <h4 class="text-white font-medium text-sm truncate">${item.name}</h4>
                    <span class="text-xs text-gray-400">${item.price} LE</span>
                </div>
                
                <div class="flex items-center gap-3">
                    <!-- Quantity Controls -->
                    <div class="flex items-center bg-gray-800 rounded-lg p-0.5 border border-gray-700/50">
                        <button type="button" onclick="updateQuantity('${id}', -1)" class="w-7 h-7 flex items-center justify-center rounded-md text-gray-400 hover:text-white hover:bg-gray-700 transition-colors focus:outline-none">-</button>
                        <span class="w-6 text-center text-sm font-semibold text-white">${item.quantity}</span>
                        <button type="button" onclick="updateQuantity('${id}', 1)" class="w-7 h-7 flex items-center justify-center rounded-md text-gray-400 hover:text-white hover:bg-gray-700 transition-colors focus:outline-none">+</button>
                    </div>
                    
                    <div class="text-right w-14">
                        <span class="text-sm font-bold text-white block">${itemTotal}</span>
                    </div>
                    
                    <button type="button" onclick="removeCartItem('${id}')" class="text-red-400 opacity-0 group-hover:opacity-100 transition-opacity hover:text-red-300 p-1 focus:outline-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
            `;
        });

        cartContainer.innerHTML = html;
        cartTotalEl.innerText = total.toLocaleString();
    }

    // Checkout Logic
    document.getElementById('confirm-order-btn').addEventListener('click', async function() {
        const keys = Object.keys(cart);
        if (keys.length === 0) {
            alert("Your cart is empty!");
            return;
        }

        const notes = document.getElementById('notes').value;
        const roomNo = document.getElementById('room_no').value;
        
        // For Admin
        const userSelect = document.getElementById('user_select');
        const billToUserId = userSelect ? userSelect.value : null;

        if (userSelect && !billToUserId) {
            alert("Please select a user to bill this order to.");
            return;
        }

        let totalAmount = 0;
        const items = keys.map(id => {
            const item = cart[id];
            totalAmount += (item.price * item.quantity);
            return {
                id: id,
                price: item.price,
                quantity: item.quantity
            };
        });

        const payload = {
            notes: notes,
            room_no: roomNo,
            bill_to_user_id: billToUserId,
            total_amount: totalAmount,
            items: items
        };

        try {
            this.disabled = true;
            this.innerText = "Processing...";

            const response = await fetch('/orders', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const result = await response.json();

            if (result.success) {
                alert("Order placed successfully! Order ID: " + result.order_id);
                // Reset cart
                cart = {};
                document.getElementById('notes').value = '';
                if(userSelect) userSelect.value = '';
                renderCart();
            } else {
                alert("Error placing order: " + (result.message || "Unknown error"));
            }
        } catch (error) {
            console.error(error);
            alert("A network error occurred.");
        } finally {
            this.disabled = false;
            this.innerText = "Confirm Order";
        }
    });
    // Search Logic
    const searchInput = document.getElementById('product-search');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('.product-card');
            let hasVisible = false;

            cards.forEach(card => {
                const name = card.getAttribute('data-name').toLowerCase();
                if (name.includes(term)) {
                    card.style.display = '';
                    hasVisible = true;
                } else {
                    card.style.display = 'none';
                }
            });

            // Handle empty state showing/hiding
            const emptyState = document.getElementById('no-products-empty-state');
            if (emptyState) {
                emptyState.style.display = hasVisible ? 'none' : 'block';
            }
        });
    }
</script>

<?php View::renderComponent("footer"); ?>