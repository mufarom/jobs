<p>Welcome to Jo's Jobs, we're a recruitment agency based in Northampton. We offer a range of different office jobs. Get in touch if you'd like to list a job with us.</a></p>

<h2>Select the type of job you are looking for:</h2>
<ul>
    <?php foreach ($records as $record) { ?>
    <li><a href="/job/viewJobs?categoryId=<?= $record->id ?>"><?= $record->name ?></a>
    </li>
    <?php } ?>
</ul>