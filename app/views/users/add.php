<?php View::renderComponent("header"); ?>

<div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-2xl">
        <h2 class="text-3xl font-bold tracking-tight text-white mb-10">Add User</h2>

        <form action="/users" method="POST" enctype="multipart/form-data" class="space-y-6">
            
            <div class="grid grid-cols-3 items-center gap-4">
                <label class="text-sm font-medium text-gray-200">Name</label>
                <div class="col-span-2">
                    <input type="text" name="name" required class="block w-full rounded-md bg-white/5 border-white/10 text-white p-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                    <span class="error-msg text-red-400 text-xs mt-1 hidden"></span>
                </div>
            </div>

            <div class="grid grid-cols-3 items-center gap-4">
                <label class="text-sm font-medium text-gray-200">Email</label>
                <div class="col-span-2">
                    <input type="email" name="email" required class="block w-full rounded-md bg-white/5 border-white/10 text-white p-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                    <span class="error-msg text-red-400 text-xs mt-1 hidden"></span>
                </div>
            </div>

            <div class="grid grid-cols-3 items-center gap-4">
                <label class="text-sm font-medium text-gray-200">Password</label>
                <div class="col-span-2">
                    <input type="password" name="password" required class="block w-full rounded-md bg-white/5 border-white/10 text-white p-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                    <span class="error-msg text-red-400 text-xs mt-1 hidden"></span>
                </div>
            </div>

            <div class="grid grid-cols-3 items-center gap-4">
                <label class="text-sm font-medium text-gray-200">Confirm Password</label>
                <div class="col-span-2">
                    <input type="password" name="confirm_password" required class="block w-full rounded-md bg-white/5 border-white/10 text-white p-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                    <span class="error-msg text-red-400 text-xs mt-1 hidden"></span>
                </div>
            </div>

            <div class="grid grid-cols-3 items-center gap-4">
                <label class="text-sm font-medium text-gray-200">Room No.</label>
                <div class="col-span-2">
                    <input type="number" name="room_no" class="block w-full rounded-md bg-white/5 border-white/10 text-white p-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                    <span class="error-msg text-red-400 text-xs mt-1 hidden"></span>
                </div>
            </div>

            <div class="grid grid-cols-3 items-center gap-4">
                <label class="text-sm font-medium text-gray-200">Ext.</label>
                <div class="col-span-2">
                    <input type="text" name="extension" class="block w-full rounded-md bg-white/5 border-white/10 text-white p-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                    <span class="error-msg text-red-400 text-xs mt-1 hidden"></span>
                </div>
            </div>

            <div class="grid grid-cols-3 items-center gap-4">
                <label class="text-sm font-medium text-gray-200">Profile picture</label>
                <div class="col-span-2">
                    <input type="file" name="profile_pic" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-white/10 file:text-white hover:file:bg-white/20 cursor-pointer">
                    <span class="error-msg text-red-400 text-xs mt-1 hidden"></span>
                </div>
            </div>

            <div class="flex justify-center gap-6 pt-6">
                <button type="submit" class="rounded border border-black bg-gray-200 px-6 py-1 text-sm font-bold text-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] active:shadow-none active:translate-x-[1px] active:translate-y-[1px]">save</button>
                <button type="reset" class="rounded border border-black bg-gray-200 px-6 py-1 text-sm font-bold text-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] active:shadow-none active:translate-x-[1px] active:translate-y-[1px]">Reset</button>
            </div>
        </form>
    </div>
</div>

<script src="/app/views/src/scripts/UserValidation.js"></script>

<?php View::renderComponent("footer"); ?>