<?php
require_once '../autoload.php';
require '../database.php';

$CategoryTable = new \CSY2028\DatabaseTable($pdo, 'category', 'id');
$categories = $CategoryTable->findAll();
?>
<nav>
    <ul>
        <li><a href="/">Home</a></li>
        <li>Jobs
            <ul>
                <?php foreach ($categories as $record) { ?>
                    <li><a href="/job/viewJobs?categoryId=<?= $record->id ?>"><?= $record->name ?></a></li>
                <?php } ?>
            </ul>
        </li>
        <li><a href="/jobMain/about">About Us</a></li>
        <li><a href="/enquiry/contactForm">Contact Us</a></li>
        <li><a href="/jobMain/faqs">FAQs</a></li>
    </ul>
</nav>