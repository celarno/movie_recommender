<?php

// find profile picture
$files = glob("profile_pics/$username.*");
if (count($files) > 0)
    foreach ($files as $file)
    {
        $p = $file;
    }
else
    $p = "profile_pics/user-circle.png";