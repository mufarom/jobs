<?php
include '../templates/admin/adminSideMenu.html.php';
?>

<section class="right">
    <h2>Enquiries</h2>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Enquiry</th>
                <th>Enquiry Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <?php foreach($enquiries as $enquiry){?>
                <td><?= $enquiry->firstname ?> <?= $enquiry->surname ?></td>
                <td><?= $enquiry->email ?></td>
                <td><?= $enquiry->telephone ?></td>
                <td><?= $enquiry->enquiry ?></td>
                <td><?= $enquiry->getEnquiryStatus() ?></td>
                <td><a style="float: right" href="/enquiry/replyEnquiry?id=<?= $enquiry->id?>">Reply</a></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</section>