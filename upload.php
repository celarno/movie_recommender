<?php
session_start();
$username = $_SESSION['username'];

// file name
$filename = $_FILES['file']['name'];

// location
$location = 'profile_pics/'.$filename;

// file extension
$file_extension = pathinfo($location, PATHINFO_EXTENSION);
$file_extension = strtolower($file_extension);

// new file name
$location = 'profile_pics/'.$username.".".$file_extension;

// Valid image extensions
$image_ext = array("jpg","png","jpeg","gif");

$response = 0;
if(in_array($file_extension,$image_ext)){

    // remove existing profile image(s)
    $files = glob("profile_pics/$username.*");
    if (count($files) > 0)
        foreach ($files as $file)
        {
            unlink($file);
        }

    // upload new file
    if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
        $response = $location;
    }
}




echo $response;