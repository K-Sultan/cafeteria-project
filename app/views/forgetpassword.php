<?php 
View::renderComponent("header");
?>

<div class="flex-1 bg-gray-900">
<div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8 mt-20">

  <div class="sm:mx-auto sm:w-full sm:max-w-sm">
    <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500"
         class="mx-auto h-10 w-auto" />
    <h2 class="mt-10 text-center text-2xl font-bold tracking-tight text-white">
      Reset your password
    </h2>
    <p class="mt-2 text-center text-sm text-gray-400">
      Enter your email and we will send you a password reset link.
    </p>
  </div>

  <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">

<?php

$errors =  $_SESSION["errors"]?? [];
$success = $_SESSION["success"] ?? "";
unset($_SESSION["errors"]);
unset($_SESSION["success"]);

 if(!empty($errors)):
  ?>
<div class="mb-6 rounded-lg border border-red-500/30 bg-red-900/40 p-4 text-red-200">
  <ul class="list-disc pl-5 space-y-1">
    <?php foreach($errors as $error): ?>
      <li><?= $error ?></li>
    <?php endforeach; ?>
  </ul>
</div>
<?php endif; ?>

<?php if(!empty($success)): ?>
<div class="mb-6 rounded-lg border border-green-500/30 bg-green-900/40 p-4 text-green-200">
  <?= $success  ?>
</div>
<?php endif; ?>

<form action="./forgotpassword" method="POST" class="space-y-6">

  <div>
    <label class="block text-sm font-medium text-gray-100">
      Email address
    </label>

    <div class="mt-2">
      <input
        type="email"
        name="email"
        required
        class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-white outline outline-1 outline-white/10 placeholder:text-gray-500 focus:outline-indigo-500"
      >
    </div>
  </div>

  <div>
    <button
      type="submit"
      class="flex w-full justify-center rounded-md bg-indigo-500 px-3 py-1.5 font-semibold text-white hover:bg-indigo-400"
    >
      Send reset link
    </button>
  </div>

  <div class="text-center text-sm">
    <a href="./login" class="text-indigo-400 hover:text-indigo-300">
      Back to login
    </a>
  </div>

</form>

  </div>
</div>
</div>

<?php View::renderComponent("footer"); ?>