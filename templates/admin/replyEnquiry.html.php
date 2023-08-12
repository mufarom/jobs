<?php
include '../templates/admin/adminSideMenu.html.php';
?>

<section class="right">
    <h2>Reply Enquiry</h2>
    <p><?= $enquiry[0]->firstname ?> <?= $enquiry[0]->surname ?></p>
    <p><?= $enquiry[0]->email ?></p>
    <p><?= $enquiry[0]->telephone ?></p>
    <p><?= $enquiry[0]->enquiry ?></p>
    <form action="/enquiry/replyEnquiry" method="POST">
    <?php if (count($errors) > 0){?>
        <p>Your Reply Could Not Be Sent:</p>
        <ul>
            <?php foreach($errors as $error){?>
            <li><?= $error ?></li>
            <?php } ?>
        </ul>
        <?php } ?>
        <input type="hidden" name="enquiry[id]" value="<?= $enquiry[0]->id ?>"/>
        <input type="hidden" name="enquiry[userId]" value="<?=$_SESSION['userId']?>"/>
        <textarea name="enquiry[reply]" placeholder="Enquiry Reply" style="width: 447px; height: 139px;"><?= $enquiry[0]->reply ?? ''?></textarea>   
        <input type="submit" name="submit" value="Reply"/>  
    </form>
</section>