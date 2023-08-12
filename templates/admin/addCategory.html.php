<?php
include '../templates/admin/adminSideMenu.html.php';
?>

<section class="right">
    <h2>Add Category</h2>

    <form action="" method="POST">
        <label>Name</label>
        <input type="text" name="category[name]" />
        <input type="submit" name="submit" value="Add Category" />
    </form>
</section>