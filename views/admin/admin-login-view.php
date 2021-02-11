<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود به بخش مدیریت</title>
    <link href="favicon.png" rel="shortcut icon" type="image/png">


    <link rel="stylesheet" href="assets/css/styles.css<?="?v=" . rand(99, 9999999)?>" />
    <link rel="stylesheet" href="assets/css/views-style.css<?="?v=" . rand(99, 9999999)?>" />
    
</head>
<body>
    <div class="main-panel">
    <h1>ورود به پنل مدیریت <span style="color:#007bec"><?= SITE_NAME; ?></span></h1>
        <div class="box">
            <form action="<?= site_url('adm.php?action=login') ?>" method="post">
                <input type="text" name="username" placeholder="Username" autocomplete="off"><br>
                <input type="password" name="password" placeholder="Password" autocomplete="off"><br>
                <input type="submit" value="Login" style="text-align: center">
            </form>
        </div>

        <div style="text-align: center;"><a style="text-decoration: none;color: #007bec;" href="<?= site_url(''); ?>">بازگشت به صفحه اصلی</a></div>

    </div>

</body>
</html>
