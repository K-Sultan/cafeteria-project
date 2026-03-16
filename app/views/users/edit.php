<?php View::renderComponent("header"); ?>
<div class="mx-auto max-w-7xl px-4 py-10">
    <h2 class="text-2xl font-bold text-white mb-6">Edit User: <?= htmlspecialchars($user['name']) ?></h2>
    <?php View::renderComponent("userForm", ["user" => $user]); ?>
</div>
<?php View::renderComponent("footer"); ?>