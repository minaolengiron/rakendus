<?php
include 'simple_html_dom.php';
include 'db.php';
include 'head.php';

// Connect to the database
$conn = connect($config);
if ( !$conn ) die('Problem connecting to the db.');


include 'content.php';

if(isset($_POST['submit-btn']))
{
	insert_urls($conn);
}

if(isset($_POST['showimages']))
{
    show_images($conn);
}

if(isset($_POST['cleardb']))
{
    clear_db($conn);
    echo '<p id="content">Database cleared</p>';
}

include 'footer.php';
