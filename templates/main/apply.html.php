<section class="left">
    <ul>
        <li><a href="/jobMain/home">Jobs</a></li>
    </ul>
</section>

<section class="right">
    <h2>Apply for <?= $job[0]->title ?? ''?></h2>
    <form action='/job/apply' method='POST' enctype='multipart/form-data'>
    <?php if (count($errors) > 0){?>
        <p>Your Application Could Not Be Processed:</p>
        <ul>
            <?php foreach($errors as $error){?>
            <li><?= $error ?></li>
            <?php } ?>
        </ul>
        <?php } ?>
        <label>Your name</label>
        <input type='text' name='applicants[name]' />

        <label>E-mail address</label>
        <input type='text' name='applicants[email]' />

        <label>Cover letter</label>
        <textarea name='applicants[details]'></textarea>

        <label>CV</label>
        <input type='file' name='cv' />

        <input type='hidden' name='applicants[jobId]' value='<?= $job[0]->id ?? '' ?>' />

        <input type='submit' name='submit' value='Apply' />
    </form>
</section>