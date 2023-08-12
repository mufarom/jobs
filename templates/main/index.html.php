<p>Welcome to Jo's Jobs, we're a recruitment agency based in Northampton. We offer a range of different office jobs. Get
    in touch if you'd like to list a job with us.
</p>

<form class="filter-location" style="float: right" action="/jobMain/home" method="GET">
        <input type="text" name= "location" placeholder="Search By Location"/>
        <input type="submit" value="Filter" id="lctn-submit">
</form>

<h2>Current Job Listings Ending Soon:</h2>

<?php foreach($jobs as $job){?>
    <div class="job-card">
        <div class="job-title"><?= $job->title ?></div>
        <div class="job-info">
            <p>Salary: <?= $job->salary ?></p>
            <p>Category: <?= $job->getCategoryName() ?></p>
            <p>Location: <?= $job->location ?></p>
            <p>Closing Date: <?= $job->closingDate ?></p>
        </div>
        <div class="job-description">
            <h3>Description:</h3>
            <p><?= $job->description ?></p>
        </div>
        <div class="job-apply">
            <a href="/job/applyForm?id=<?= $job->id ?>">Apply for this job</a>
        </div>
    </div>
<?php } ?>