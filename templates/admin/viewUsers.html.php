<?php
include '../templates/admin/adminSideMenu.html.php';
?>

<section class="right">

<h2>Users</h2>
<a class="new" href="/user/editUser">Add New User</a>

<table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Username</th>
                <th>User Type</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <?php foreach($users as $user){?>
                <td><?= $user->firstname ?> <?= $user->surname ?></td>
                <td><?= $user->username ?></td>
                <td><?= $user->getUserTypeName() ?></td>
                <td><a style="float: right" href="/user/editUser?id=<?= $user->id ?>">Edit User</a></td>
                <td>
                    <form method="post" action="/user/deleteUser">
                        <input type="hidden" name="id" value="<?= $user->id ?>" />
                        <input type="submit" name="submit" value="Delete" />
                    </form>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>


</section>