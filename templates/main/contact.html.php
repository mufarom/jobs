<section class="left">
    <ul>
        <?php foreach ($records as $record) { ?>
            <li><a href="/job/viewJobs?categoryId=<?= $record->id ?>"><?= $record->name ?></a></li>
        <?php } ?>
    </ul>
</section>

<section class="right">
<h2>Contact Us</h2>
<div class="contactForm">
    <form action="/enquiry/contact" method="POST">
    <?php if (count($errors) > 0){?>
        <p>Your Enquiry Could Not Be Sent:</p>
        <ul>
            <?php foreach($errors as $error){?>
            <li><?= $error ?></li>
            <?php } ?>
        </ul>
        <?php } ?>
        <input type="text" name="enquiry[firstname]" id="name" placeholder="Firstname">

        <input type="text" name="enquiry[surname]" id="name" placeholder="Surname">

        <input type="email" name="enquiry[email]" id="email" placeholder="Email">

        <input type="text" name="enquiry[telephone]" id="phone" placeholder="Phone Number">

        <textarea name="enquiry[enquiry]" id="enquiry" placeholder="Enquiry" style="width: 447px; height: 139px;"></textarea>

        <input type="submit" name="submit" value="Send Enquiry">
    </form>
</div>
</section>