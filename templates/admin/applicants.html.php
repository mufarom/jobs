<?php
include '../templates/admin/adminSideMenu.html.php';
?>

<section class="right">
    <h2>Applicants for <?=$job[0]->title?></h2>
    <table>
        <thead>
            <tr>
                <th style="width: 10%">Name</th>
                <th style="width: 10%">Email</th>
                <th style="width: 65%">Details</th>
                <th style="width: 15%">CV</th>
            </tr>

            <?php
			foreach ($jobs as $applicant) {?>
            <tr>
                <td><?= $applicant->name ?></td>
                <td><?= $applicant->email?></td>
                <td><?= $applicant->details ?></td>
                <td><a href="/cvs/<?= $applicant->cv ?>">Download CV</a></td>
            </tr>
            <?php }
			?>
        </thead>
    </table>
</section>