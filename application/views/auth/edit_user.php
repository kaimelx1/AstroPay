<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DNA Trucking LLC</title>

    <!-- Bootstrap  CSS -->
    <link rel="stylesheet" href="/files/libs/bootstrap3/css/bootstrap.min.css">

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

            <h1><?php echo lang('edit_user_heading');?></h1>
            <!--<p><?php /*echo lang('edit_user_subheading');*/?></p>-->

            <div id="infoMessage"><b style="color: red"><?php echo $message;?></b></div>

            <?php echo form_open(uri_string());?>

                  <p>
                        <?php echo lang('edit_user_password_label', 'password');?> <br />
                        <?php echo form_input($password);?>
                  </p>

                  <p>
                        <?php echo lang('edit_user_password_confirm_label', 'password_confirm');?><br />
                        <?php echo form_input($password_confirm);?>
                  </p>

                  <?php if ($this->ion_auth->is_admin()): ?>

                      <h3><?php echo lang('edit_user_groups_heading');?></h3>
                      <?php foreach ($groups as $group):?>
                          <label class="checkbox" style="display: inline-block; margin: 0 20px 20px 20px;">
                          <?php
                              $gID=$group['id'];
                              $checked = null;
                              $item = null;
                              foreach($currentGroups as $grp) {
                                  if ($gID == $grp->id) {
                                      $checked= ' checked="checked"';
                                  break;
                                  }
                              }
                          ?>
                          <input type="checkbox" name="groups[]" value="<?php echo $group['id'];?>"<?php echo $checked;?>>
                          <?php echo htmlspecialchars($group['name'],ENT_QUOTES,'UTF-8');?>
                          </label>
                      <?php endforeach?>

                  <?php endif ?>

                  <?php echo form_hidden('id', $user->id);?>
                  <?php echo form_hidden($csrf); ?>

                  <p><?php echo form_submit('submit', lang('edit_user_submit_btn'), 'class="btn btn-warning"');?> <a class="btn btn-primary" href="/">Go back</a></p>

            <?php echo form_close();?>

        </div>
    </div>
</div>

</body>
</html>
