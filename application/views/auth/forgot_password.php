<!doctype html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>USA Trucks</title>

     <!-- Bootstrap  CSS -->
     <link rel="stylesheet" href="/files/libs/bootstrap3/css/bootstrap.min.css">

    <style>
        body {
            background-color: #f7f7f7;
        }
        .custom_container {
            border-radius: 6px;
            background-color: #fff;
            margin-top: 20px;;
        }
    </style>

</head>
<body>

<div class="container">
      <div class="row">
            <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 custom_container">

                  <h1><?php echo lang('forgot_password_heading');?></h1>
                  <p><?php echo sprintf(lang('forgot_password_subheading'), $identity_label);?></p>

                  <div id="infoMessage"><?php echo $message;?></div>

                  <?php echo form_open("auth/forgot_password");?>

                        <p>
                          <label for="identity"><?php echo (($type=='email') ? sprintf(lang('forgot_password_email_label'), $identity_label) : sprintf(lang('forgot_password_identity_label'), $identity_label));?></label> <br />
                          <?php echo form_input($identity);?>
                        </p>

                        <p><?php echo form_submit('submit', lang('forgot_password_submit_btn'), 'class="btn btn-warning"');?> <a class="btn btn-primary" href="/auth/login">Go back</a></p>

                  <?php echo form_close();?>

            </div>
      </div>
</div>

</body>
</html>
