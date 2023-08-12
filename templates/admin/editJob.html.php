<?php
include '../templates/admin/adminSideMenu.html.php';
?>

<section class="right">
    <h2><?=(isset($_GET['id'])) ? 'Edit Job' : 'Add Job'?></h2>

    <form action="/job/editJob" method="POST">
    <?php if (count($errors) > 0){?>
        <p>Your Job Could Not Be Edited or Saved:</p>
        <ul>
            <?php foreach($errors as $error){?>
            <li><?= $error ?></li>
            <?php } ?>
        </ul>
        <?php } ?>
        <input type="hidden" name="job[userId]" value="<?= $job[0]->userId ?? $_SESSION['userId']?>"/>
        <input type="hidden" name="job[id]" value="<?= $job[0]->id ?? ''?>" />

        <label>Title</label>
        <input type="text" name="job[title]" value="<?= $job[0]->title ?? ''?>" />

        <label>Description</label>
        <textarea name="job[description]"><?= $job[0]->description ?? ''?></textarea>

        <label>Location</label>
        <input type="text" name="job[location]" value="<?= $job[0]->location ?? ''?>" />

        <label>Salary</label>
        <input type="text" name="job[salary]" value="<?= $job[0]->salary ?? ''?>" />

        <label>Category</label>
        <select name="job[categoryId]">
            <?php foreach ($stmt as $row) {
            $selected = ($job[0]->categoryId ?? null) == $row->id ?>
            <option <?= $selected ? 'selected' : '' ?> value="<?= $row->id ?? ''?>"><?= $row->name ?? ''?></option>
            <?php } ?>
        </select>
        
        <label>Closing Date</label>
        <input type="date" name="job[closingDate]" value="<?= $job[0]->closingDate ?? ''?>" />

        <label>Status</label>
        <select name="job[statusId]">
            <?php foreach ($jobStatus as $status) {
             $selected = ($job[0]->statusId ?? null) == $status->id ?>
            <option <?= $selected ? 'selected' : '' ?> value="<?= $status->id ?? ''?>"><?= $status->name ?? ''?></option>
            <?php } ?>
        </select>

        <input type="submit" name="submit" value="Save" />

    </form>
</section>