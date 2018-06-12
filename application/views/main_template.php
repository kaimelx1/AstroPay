<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=isset($seo_title) ? $seo_title : 'DEV' ?></title>
    <meta name="description" content="<?=isset($seo_description) ? $seo_description : 'DEV'?>">
    <meta name="keywords" content="<?=isset($seo_keywords) ? $seo_keywords : 'DEV'?>">
    <meta name="author" content="dev.salvatory.info">

    <!-- Bootstrap  CSS -->
    <link rel="stylesheet" href="/files/libs/bootstrap3/css/bootstrap.min.css">

    <!-- Moment JS-->
    <script src="/files/js/moment/moment.js"></script>

    <!-- jQuery 3.2.1 -->
    <script src="/files/js/jquery/jquery-3.2.1.min.js"></script>

    <!-- jQuery form -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.min.js"></script>

    <!-- jQuery Mask-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="/files/libs/bootstrap3/js/bootstrap.min.js"></script>

    <!-- Bootstrap select -->
    <link rel="stylesheet" href="/files/libs/bootstrap-select/css/bootstrap-select.css" type="text/css" media="screen" />
    <script type="text/javascript" src="/files/libs/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Notify JS-->
    <script src="/files/js/notify/notify.min.js" async></script>

    <!-- Datetime Picker -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>

    <!-- JConfirm-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

    <!-- Custom styles -->
    <link rel="stylesheet" href="/files/css/style.css" >

    <!-- Font Awesome-->
    <link rel="stylesheet" href="/files/libs/font-awesome-4.7.0/css/font-awesome.min.css" async>

    <!-- head -->
    <?php
    if (@file_exists(APPPATH."/views/". $page_center."_head.php"))
    {
        $this->load->view($page_center."_head");
    }
    ?>

</head>
<body>

<!-- content -->
<?php $this->load->view($page_center); ?>

<!-- footer -->
<?php
if (@file_exists(APPPATH."/views/". $page_center."_footer.php"))
{
    $this->load->view($page_center."_footer");
}
?>

</body>
</html>