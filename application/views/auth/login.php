<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>DNA Trucking LLC</title>

   <!-- Bootstrap  CSS -->
   <link rel="stylesheet" href="/files/libs/bootstrap3/css/bootstrap.min.css">
    <!-- Font awesome icons -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>

    <style>
        body {
            background-color: #f7f7f7;
        }
        .custom_container {
            border-radius: 6px;
            background-color: #fff;
            margin-top: 20px;
            margin-bottom: 20px;
        }
    </style>

</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 custom_container">

          <!--<h1><?php /*echo lang('login_heading');*/?></h1>-->
          <p>Use your email and password to login.</p>
          <!--<p><?php /*echo lang('login_subheading');*/?></p>-->

           <div id="infoMessage"><b style="color: red"><?php echo $message;?></b></div>

         <?php echo form_open("auth/login");?>

          <p>
            <?php /*echo lang('login_identity_label', 'identity');*/?>
            <b>Email</b>
            <?php echo form_input($identity);?>
          </p>

          <p>
            <?php /*echo lang('login_password_label', 'password');*/?>
            <b>Password</b>
            <?php echo form_input($password);?>
          </p>

          <!--<p>
            <?php /*echo lang('login_remember_label', 'remember');*/?>
            <?php /*echo form_checkbox('remember', '1', FALSE, 'id="remember"');*/?>
          </p>-->


          <p><?php echo form_submit('submit', lang('login_submit_btn'), 'class="btn btn-warning"');?></p>

          <?php echo form_close();?>

         <!-- <p><a href="forgot_password"><?php /*echo lang('login_forgot_password');*/?></a></p>-->

        </div>
    </div>
</div>

</body>
</html>