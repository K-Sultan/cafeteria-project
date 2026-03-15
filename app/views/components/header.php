<?php
require_once __DIR__ . '/../../models/User.php';

$userId = $_SESSION['userId'] ?? null;
$user = $userId ? User::getUserById($userId) : null;
$role = $user['role'] ?? null;
$isLoggedIn = $user !== null;
$userName = $user['name'] ?? '';
$profilePic = !empty($user['profile_pic']) ? '/public/uploads/' . $user['profile_pic'] : 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=random';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafeteria</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="app/views/src/styles/style.css">
</head>
<body class="h-full bg-gray-900  flex flex-col">

<nav class="relative bg-gray-800/50 border-b border-white/10">
  <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
    <div class="relative flex h-16 items-center justify-between">

      <?php if ($isLoggedIn): ?>
      <!-- Mobile menu button -->
      <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
        <button type="button" id="mobile-menu-btn" class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-white/5 hover:text-white focus:outline-none">
          <svg id="icon-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-6">
            <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          <svg id="icon-close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-6 hidden">
            <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </button>
      </div>
      <?php endif; ?> 

      <!-- Logo + Nav links -->
      <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
        <div class="flex shrink-0 items-center">
          <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500" alt="Cafeteria" class="h-8 w-auto" />
        </div>
        <div class="hidden sm:ml-6 sm:block">
          <div class="flex space-x-4">
            <a href="/" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">Home</a>
            <?php if ($role === 'user'): ?>
              <a href="/my-orders" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">My Orders</a>
            <?php elseif ($role === 'admin'): ?>
              <a href="/products" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">Products</a>
              <a href="/users" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">Users</a>
              <a href="/manual-order" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">Manual Order</a>
              <a href="/checks" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">Checks</a>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <?php if ($isLoggedIn): ?>
      <!-- Right side: username + avatar dropdown + logout button -->
      <div class="absolute inset-y-0 right-0 flex items-center gap-3 pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
        <span class="text-sm font-medium text-white hidden md:block"><?= htmlspecialchars($userName) ?></span>

        <!-- Profile picture dropdown -->
        <div class="relative" id="profile-dropdown">
          <button id="profile-btn" type="button" class="flex rounded-full focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
            <span class="sr-only">Open user menu</span>
            <img src="<?= htmlspecialchars($profilePic) ?>" alt="Profile Picture" class="size-8 rounded-full bg-gray-800 ring-1 ring-white/10" />
          </button>
          <div id="profile-menu" class="hidden absolute right-0 z-50 mt-2 w-48 origin-top-right rounded-md bg-gray-800 py-1 shadow-lg ring-1 ring-white/10">
            <a href="/profile" class="block px-4 py-2 text-sm text-gray-300 hover:bg-white/5">Your profile</a>
            <a href="/logout" class="block px-4 py-2 text-sm text-red-400 hover:bg-white/5">Sign out</a>
          </div>
        </div>

        <!-- Always-visible logout button -->
        <a href="/logout" class="flex items-center gap-1.5 rounded-md px-3 py-1.5 text-sm font-medium text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
          </svg>
          Logout
        </a>
      </div>
      <?php endif; ?>

    </div>
  </div>

  <?php if ($isLoggedIn): ?>
  <!-- Mobile dropdown menu -->
  <div id="mobile-menu" class="hidden sm:hidden border-t border-white/10">
    <div class="space-y-1 px-2 pt-2 pb-3">
      <?php if ($role === 'user'): ?>
        <a href="/home" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-white/5 hover:text-white">Home</a>
        <a href="/my-orders" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-white/5 hover:text-white">My Orders</a>
      <?php elseif ($role === 'admin'): ?>
        <a href="/home" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-white/5 hover:text-white">Home</a>
        <a href="/products" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-white/5 hover:text-white">Products</a>
        <a href="/users" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-white/5 hover:text-white">Users</a>
        <a href="/manual-order" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-white/5 hover:text-white">Manual Order</a>
        <a href="/checks" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-white/5 hover:text-white">Checks</a>
      <?php endif; ?>
      <a href="/logout" class="block rounded-md px-3 py-2 text-base font-medium text-red-400 hover:bg-white/5">Logout</a>
    </div>
  </div>
  <?php endif; ?>
</nav>

<script>
  // Profile dropdown toggle
  const profileBtn = document.getElementById('profile-btn');
  const profileMenu = document.getElementById('profile-menu');
  if (profileBtn && profileMenu) {
    profileBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      profileMenu.classList.toggle('hidden');
    });
    document.addEventListener('click', () => profileMenu.classList.add('hidden'));
  }

  // Mobile menu toggle
  const mobileBtn = document.getElementById('mobile-menu-btn');
  const mobileMenu = document.getElementById('mobile-menu');
  const iconOpen  = document.getElementById('icon-open');
  const iconClose = document.getElementById('icon-close');
  if (mobileBtn && mobileMenu) {
    mobileBtn.addEventListener('click', () => {
      const isHidden = mobileMenu.classList.toggle('hidden');
      iconOpen.classList.toggle('hidden', !isHidden);
      iconClose.classList.toggle('hidden', isHidden);
    });
  }
</script>
