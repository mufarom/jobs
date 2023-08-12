<?php
include '../templates/admin/adminSideMenu.html.php';
?>

<section class="right">
    <h2><?=(isset($_GET['id'])) ? 'Edit Category' : 'Add Category'?></h2>

    
    <form action="" method="POST">
    <?php if (count($errors) > 0){?>
        <p>Category Could Not Be Edited/Saved:</p>
        <ul>
            <?php foreach($errors as $error){?>
            <li><?= $error ?></li>
            <?php } ?>
        </ul>
        <?php } ?>
            <input type="hidden" name="category[id]" value="<?= $currentCategory[0]->id ?? '' ?>" />
            <label>Name</label>
            <input type="text" name="category[name]" value="<?= $currentCategory[0]->name ?? '' ?>" />
            <input type="submit" name="submit" value="Save Category" />
        </form>
    
</section>