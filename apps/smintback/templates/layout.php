<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="icon" type="image/png" href="<?php echo image_path("favicon.png") ?>" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
  </head>
  <body>
    <div id="containerheader">
      <div id="header">
          <h1><a href="<?php echo url_for("/") ?>"></a></h1>
          <?php if ($sf_user->isAuthenticated()) { ?>
            <div class="menu_header">
              <p><b>smint</b> beta demo<br/><br/></p>
              <ul>
                  <li><?php echo link_to('Logout', 'sfGuardAuth/signout') ?></li>
                  <li><?php echo link_to('Home', 'home/index') ?></li>
              </ul>
            </div>
          <?php } ?>
      </div>
      <div id="smintback_content"><?php echo $sf_content ?></div>   

    </div>  


      <div id="content">

        <?php if ($sf_user->hasFlash('notice')): ?>
          <div class="flash_notice">
            <?php echo $sf_user->getFlash('notice') ?>
          </div>
        <?php endif; ?>

        <?php if ($sf_user->hasFlash('error')): ?>
          <div class="flash_error">
            <?php echo $sf_user->getFlash('error') ?>
          </div>
        <?php endif; ?>

          <div id="query_result"></div>        

      </div>

  </body>
</html>
