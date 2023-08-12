<section class="left">
    <ul>
        <?php foreach ($records as $record) { ?>
            <li><a href="/job/viewJobs?categoryId=<?= $record->id ?>"><?= $record->name ?></a></li>
        <?php } ?>
    </ul>
</section>
<?php if (isset($jobs) && count($jobs) > 0) { ?>
    <section class="right">

        <h1><?= $jobs[0]->getCategoryName() ?> Jobs</h1>

        <ul class="listing">
            <?php
            foreach ($jobs as $job) { ?>
                <li>
                    <div class="details">
                        <h2><?= $job->title ?></h2>
                        <h3><?= $job->salary ?></h3>
                        <p><?= nl2br($job->description) ?></p>
                        <a class="more" href="/job/applyForm?id=<?= $job->id ?>">Apply for this job</a>
                    </div>
                </li>
            <?php
            } ?>
        </ul>
    </section><?php }