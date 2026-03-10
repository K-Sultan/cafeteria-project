
<?php View::renderComponent("header"); ?>

    <h1 class="red">Users</h1>
    
<?php foreach ($users as $user): ?>
    <?php foreach ($user as $key => $value): ?>
        <?php echo "$key: $value <br>"; ?>
    <?php endforeach; ?>
    <br>
<?php endforeach; ?>

<?php View::renderComponent("footer"); ?>




