<?php
include '../templates/admin/adminSideMenu.html.php';
?>

<section class="right">

    <h2><?= $title ?></h2>

    <div class="userForm">
        <form action="/user/editUser" method="POST">
        <?php if (count($errors) > 0){?>
        <p>User Could Not Be Edited/Saved:</p>
        <ul>
            <?php foreach($errors as $error){?>
            <li><?= $error ?></li>
            <?php } ?>
        </ul>
        <?php } ?>
            <?php if(isset($user[0]->id)): ?>
            <input type="hidden" name="user[id]" value="<?= $user[0]->id ?>" />
            <?php endif; ?>
            <input type="text" name="user[firstname]" value="<?= $user[0]->firstname ?? '' ?>"
                placeholder="Firstname" />
            <input type="text" name="user[surname]" value="<?= $user[0]->surname ?? '' ?>" placeholder="Surname" />
            <input type="text" name="user[username]" value="<?= $user[0]->username ?? '' ?>" placeholder="Username" />
            <input type="password" name="user[password_hash]" value="<?= $user[0]->password_hash ?? '' ?>"
                placeholder="Password" />
            <select name="user[userTypeId]">
                <?php
    foreach ($userTypes as $userType) {
        $selected = ($user[0]->userTypeId ?? null) == $userType->id;
        ?>
                <option <?= $selected ? 'selected' : '' ?> value="<?= $userType->id ?? ''?>"><?= $userType->name ?? ''?>
                </option>
                <?php } ?>
            </select>

            <input type="submit" name="submit" value="<?= isset($_GET['id']) ? 'Edit User' : 'Add User' ?>">
        </form>
    </div>
</section>