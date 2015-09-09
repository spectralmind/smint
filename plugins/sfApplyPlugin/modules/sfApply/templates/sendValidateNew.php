Thanks for applying for an account with <?php echo $sf_request->getHost() ?>!

To prevent abuse of the site, we require that you activate your
account by clicking on the following link:

<?php echo url_for("sfApply/confirm?validate=$validate", true) ?>

Thanks again for joining us!
