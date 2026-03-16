<?php View::renderComponent("header"); ?>
<div class="mx-auto max-w-7xl px-4 py-10">
    <h2 class="text-2xl font-bold text-white mb-6">Add New User</h2>
    <?php
    // Display errors if any
    if (isset($_SESSION['errors'])) {
        foreach ($_SESSION['errors'] as $error) echo "<p class='text-red-500'>$error</p>";
        unset($_SESSION['errors']);
    }
    ?>
    <?php View::renderComponent("userForm"); ?>
</div>
<?php View::renderComponent("footer"); ?>