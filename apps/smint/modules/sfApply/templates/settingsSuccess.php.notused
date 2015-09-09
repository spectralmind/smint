<?php use_helper('Validation') ?>
<h2 class="sf_apply_heading">Account Settings</h2>
<?php echo form_tag('sfApply/settings', array('name' => 'sf_apply_settings', 'id' => 'sf_apply_settings')) ?>
<div class="sf_apply_row">
<label for="fullname">Full Name: </label>
<div class="sf_apply_row_content">
<?php echo form_error('fullname') ?>
<?php echo input_tag('fullname', $sf_user->getGuardUser()->getProfile()->getFullname()) ?>
</div>
</div>
<?php echo submit_tag('Save') ?> 
<?php echo button_to('Cancel', '@homepage') ?>
</form>
<?php echo form_tag('sfApply/resetRequest', array('name' => 'sf_apply_reset_request', 'id' => 'sf_apply_reset_request')) ?>
<p>
Click the button below to change your password. For security reasons, you 
will receive a confirmation email containing a link allowing you to complete 
the password change. 
</p>
<?php echo input_hidden_tag('username', $sf_user->getGuardUser()->getUsername()) ?>
<?php echo submit_tag('Reset Password') ?>
</form>
