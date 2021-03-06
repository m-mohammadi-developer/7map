<?php use Hekmatinasser\Verta\Verta; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بخش مدیریت</title>
    <link href="favicon.png" rel="shortcut icon" type="image/png">

    <link rel="stylesheet" href="assets/css/styles.css<?="?v=" . rand(99, 9999999)?>" />

    <link rel="stylesheet" href="assets/css/views-style.css<?="?v=" . rand(99, 9999999)?>" />

    
</head>
<body>
    <div class="main-panel" style="width: 87%;">
        <h1>پنل مدیریت <span style="color:#007bec"><?= SITE_NAME; ?></span></h1>
        <div class="box">
            <a class="statusToggle" href="<?=SITE_URL?>" target="_blank">🏠</a>
            <a class="statusToggle active" href="?">همه</a>
            <a class="statusToggle active" href="?verified=1">فعال</a>
            <a class="statusToggle" href="?verified=0">غیرفعال</a>
            <a class="statusToggle" href="?logout=1" style="float:left">خروج</a>
            <a class="statusToggle" href="?action=types" style="float:left">دسته بندی ها</a>
        </div>
        <div class="box">
        <table class="tabe-locations">
        <thead>
        <tr>
        <th style="width:40%">عنوان مکان</th>
        <th style="width:40%">توضیحات مکان</th>
        <th style="width:40%">شماره تلفن</th>
        <th style="width:15%" class="text-center">تاریخ ثبت</th>
        <th style="width:10%" class="text-center">lat</th>
        <th style="width:10%" class="text-center">lng</th>
        <th style="width:25%">وضعیت</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($locations as $location): ?>
        <tr>
            <td><?= $location->title; ?></td>
            <td style="width: 20%;text-overflow:auto;"><?= $location->description; ?></td>
            <td><?= $location->phone; ?></td>
            <td class="text-center"><?= Verta::instance($location->created_at)->format('%d %B %Y H:i:s'); ?></td>
            <td class="text-center"><?= $location->lat; ?></td>
            <td class="text-center"><?= $location->lng; ?></td>
            <td>
                <button class="statusToggle center <?php echo  ($location->verified) ? 'active' : ''; ?>" data-loc='<?= $location->id; ?>'>تایید</button> 
                <?php if ($location->verified): ?>
                    <button class="preview" data-loc='<?= $location->id; ?>'>👁️‍🗨️</button> 
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>        
        </tbody>
        </table>
        </div>

    </div>

    <div class="modal-overlay" style="display: none;">
        <div class="modal" style="margin-top: 25px;">
            <span class="close">x</span>
            <div class="modal-content">
                <iframe style="padding: 10px 5px" id='mapWivdow' src="#" frameborder="0"></iframe>
            </div>
        </div>
    </div>



    <script src="assets/js/jquery.min.js"></script>
    <script>
    $(document).ready(function() {

        // toggle locations status
        $(".statusToggle").click(function () {
            const btn = $(this);

            $.ajax({    
                url: '<?= SITE_URL . 'process/statusToggle.php' ?>',
                method: 'POST',
                data: {loc: btn.attr('data-loc')},
                success: function (response) {
                    if (response == 1) {
                        btn.toggleClass('active');
                    }
                }
            });

        });

        // preview section
        $('.preview').click(function() {
            $('.modal-overlay').fadeIn();
            $('#mapWivdow').attr('src','<?= SITE_URL  ?>' + '?loc=' + $(this).attr('data-loc'));
        }); 
        $('.modal-overlay .close').click(function() {
            $('.modal-overlay').fadeOut();
        });
    });
    </script>
</body>
</html>
