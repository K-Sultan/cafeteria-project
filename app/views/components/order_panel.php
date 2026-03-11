<div class="col-span-1 bg-gray-800/60 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6 flex flex-col h-[calc(100vh-7rem)] sticky top-24 shadow-2xl">
    <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        Current Order
    </h2>
    
    <!-- Cart Items Container -->
    <div class="flex-1 overflow-y-auto space-y-4 pr-2 custom-scrollbar" id="cart-items">
        <!-- JS will populate this list -->
        <div class="flex flex-col items-center justify-center h-full text-center space-y-3 opacity-50">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            <p class="text-gray-400 text-sm">Cart is empty.<br>Select items from the menu.</p>
        </div>
    </div>

    <!-- Notes & Room -->
    <div class="mt-4 pt-4 border-t border-gray-700/50 space-y-5">
        <div>
            <label for="notes" class="block text-sm font-medium text-gray-300 mb-1.5">Notes</label>
            <textarea id="notes" name="notes" rows="2" class="block w-full rounded-lg border-0 bg-gray-900/50 py-2.5 text-white shadow-inner ring-1 ring-inset ring-gray-700 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6 placeholder:text-gray-500 transition-all resize-none p-5" placeholder="e.g. 1 Tea Extra Sugar..."></textarea>
        </div>
        
        <div class="flex items-center justify-between">
            <label for="room_no" class="block text-sm font-medium text-gray-300">Room</label>
            <select id="room_no" name="room_no" class="block w-2/3 rounded-lg border-0 bg-gray-900/50 py-2.5 pl-3 pr-10 text-white shadow-inner ring-1 ring-inset ring-gray-700 focus:ring-2 focus:ring-indigo-500 sm:text-sm sm:leading-6 transition-all">
                <option value="101">101</option>
                <option value="102">102</option>
                <option value="201">201</option>
            </select>
        </div>
    </div>

    <!-- Total & Confirm -->
    <div class="mt-6 flex flex-col gap-4">
        <div class="flex items-center justify-between border-t border-gray-700/50 pt-4">
            <span class="text-gray-400 text-sm">Total</span>
            <span class="text-3xl font-bold text-white tracking-tight flex items-baseline gap-1">
                EGP <span id="cart-total">0</span>
            </span>
        </div>
        <button type="button" id="confirm-order-btn" class="w-full rounded-lg bg-indigo-500 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-indigo-500/30 hover:bg-indigo-400 hover:shadow-indigo-500/50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500 transition-all transform active:scale-[0.98]">
            Confirm Order
        </button>
    </div>
</div>
