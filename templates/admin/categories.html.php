<?php
include '../templates/admin/adminSideMenu.html.php';
?>

<section class="right">
    <h2>Categories</h2>

    <a class="new" href="/category/editCategory">Add new category</a>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th style="width: 5%">&nbsp;</th>
                <th style="width: 5%">&nbsp;</th>
            </tr>
            <?php
foreach ($categories as $category) {
    ?>
            <tr>
                <td><?= $category->name?></td>
                <td><a style="float: right" href="/category/editCategory?id=<?= $category->id?>">Edit</a></td>
                <td>
                    <form method="post" action="/category/deleteCategory">
                        <input type="hidden" name="id" value="<?= $category->id ?>" />
                        <input type="submit" name="submit" value="Delete" />
                    </form>
                </td>
            </tr>
            <?php
}
?>
        </thead>
    </table>
</section>