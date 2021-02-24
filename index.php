<?php

include 'bootstrap/init.php';

//  get locations by id
if (isset($_GET['loc'])) {
    $location = getLocation($_GET['loc']);
}
// login user 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login_user'])) {
   
    if (loginUser($_POST['username'], $_POST['password'])) {
        echo "<script>alert('با موفقیت وارد شدید');</script>";
    } else {
        echo "<script>alert('نام کاربری یا رمز عبور اشتباه است');</script>";
    }
}

// locations vetified
$locations = (array)getLocations(['verified' => 1]);
$locations_str = json_encode($locations);



include 'views/index-view.php';

// dd($locations_str);
?>
