<?php
include 'FaceDetector.php';

// Login info for the database
$config = array(
    'username' => 'root',
    'password' => ''
);

// Function to connect to the database
function connect($config)
{
	try{
		$conn = new PDO('mysql:host=localhost;dbname=imgdb',
			            $config['username'],
			            $config['password']
			            );

		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		return $conn;
	} catch(Exception $e) {
		return false;
	}
}


function query($query, $bindings, $conn)
{
	$stmt = $conn->prepare($query);
	$stmt->execute($bindings);
	return $stmt;
}

// Function to get all the data from the database table named 'images'
function get($query, $conn)
{
	try{
		$result = $conn->query('SELECT * FROM images');
		return ( $result->rowCount() > 0)
			? $result
			: false;
	} catch(Exception $e) {
		return false;
	}
}

/* 
    Function to download all the images from the given website, then to
    search for faces on these images. If any face is found the URL of
    that image will be saved into the database.
*/
function insert_urls($conn)
{
	
	$target_url = $_POST['t_url']; // t_url is taken from user input through the text box
	$html = new simple_html_dom();
	if(!$html->load_file($target_url)) {
		$i = 1;
		foreach($html->find('img') as $image)
		{
			$image_url = $image->src;
			
			$pp_image = imagecreatefromstring(file_get_contents($image_url));
			imagejpeg($pp_image, 'temp_images/img'.$i.'.jpeg'); // Saves the image as a jpeg file
			$detector = new svay\FaceDetector('detection.dat');
			if ($detector->faceDetect('temp_images/img'.$i.'.jpeg')) { // If the detector detects a face
				$sql = "INSERT INTO images (url) VALUES ('$image_url')"; // Insert that url into the database
				$stmt = $conn->prepare($sql);
				$stmt->execute();
			}

			$i++;
		} 
    } else {
		echo '<br /><div id="strongtext"><p><strong>Palun sisesta mõni muu aadress.</strong></p></div>';
		var_dump($html->load_file($target_url));
	}


	$temp_files = glob('temp_images/*'); // After the foreach loop is done checking all of the images
	foreach($temp_files as $temp_file) // that were downloaded, delete them all
	{
		if(is_file($temp_file)) {
			unlink($temp_file);
		}
	}
	echo '<script>alertFunction();</script>'; // Alert the user that the script has finished working
}

/*
    Function to clear the database of all entries and if any image files were left into
    the temporary folder due to an error, delete them all just in case.
*/
function clear_db($conn)
{
	$sql = "TRUNCATE images";

	$stmt = $conn->prepare($sql);
	$stmt->execute();

	$temp_files = glob('temp_images/*');
	foreach($temp_files as $temp_file)
	{
		if(is_file($temp_file)) {
			unlink($temp_file);
		}
	}
}

/*
    Function to display all the images from the URLs that are stored in the database
    using the bootstrap thumbnail gallery.
*/
function show_images($conn)
{
    $result = get('images', $conn);
    $pictures = array();
    $i = 1;
    
    if (!$result)
    {
    	echo 'Database is empty';
    } else {
    	foreach($result as $picture)
    	{
			$pictures[$picture['id']] = $picture['url'];
			echo '<div class="col-lg-3 col-md-4 col-xs-6 thumb"><br /><img class="img-responsive" src="'.$pictures[$i].'" alt="Pilt number '.$i.'"></div>';
			$i++;
	    }
    }
}
