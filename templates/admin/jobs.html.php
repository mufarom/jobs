<?php
include '../templates/admin/adminSideMenu.html.php';
?>

<section class="right">
    <h2>Jobs</h2>

    <a class="new" href="/job/editJob">Add new job</a>

    <form class="filter-form" action="/job/viewJobsAdmin" method="GET">
        <select style="float: right" name="categoryId">
            <?php
        foreach ($stmt as $row) {
            ?>
            <option value="<?= $row->id ?>"><?= $row->name ?></option>
            <?php }
        ?>
        </select>
        <input type="submit" value="Filter" style="float: right" id="filter-submit">
    </form>

    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th style="width: 15%">Salary</th>
                <th style="width: 15%">Category</th>
                <th style="width: 5%">Status</th>
                <th style="width: 5%">&nbsp;</th>
                <th style="width: 5%">&nbsp;</th>
            </tr>
            <?php
foreach ($jobs as $job) {?>
            <tr>
                <td><?= $job->title ?></td>
                <td><?= $job->salary ?></td>
                <td><?= $job->getCategoryName()?></td>
                <td><?= $job->getStatusName() ?></td>
                <td><a style="float: right" href="/job/editJob?id=<?= $job->id?>">Edit</a></td>
                <td><a style="float: right" href="/job/listApplicants?id=<?= $job->id ?>">View applicants
                        (<?= $job->getApplicantsCount() ?>)</a></td>
                <td>
                <form method="post" action="/job/deleteJob">
                    <input type="hidden" name="id" value="<?= $job->id ?>" />
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