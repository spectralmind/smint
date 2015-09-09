

<div class="logo_container_login">
	<div class="spec_logo_login">
		<a href="http://www.spectralmind.com" target="_blank">
			<img src="<?php echo image_path('spec_logo_128_109.png')?>" alt="spectralmind audio intelligence" />
		</a>
	</div>
	
</div>
<div class="login-container">
    
    <form action="<?php echo url_for('@sf_guard_signin') ?>" method="post">
      <table class="loginform">
        <?php echo $form ?>
      </table>

      <input class="login-button" type="submit" value="" />
    </form>


		<p class="new">New user?</p>
		<a class="register" href="<?php echo url_for('sfApply/apply') ?>"><img src="<?php echo image_path('btn_signup_01_88x30.png')?>" alt="Sign Up"/></a> 
				
	
</div>  
<p>&nbsp;</p>
<p>&nbsp;</p>
<p class="forgotmessage"><b>Forgot your password?</b> Please write to <?php echo mail_to('support@spectralmind.com'); ?>.</p>

<div class="terms">
<div class="login-box"><img class="login-headline" src="<?php echo image_path('headlines/headline_01_182x22.png')?>" alt="SEARCH FOR A SONG" /><p class="login-text">that meets your needs exactly by uploading a similar track, describing the genre, album or title of a similar track or combining both.
</p></div>
<div class="login-box"><img class="login-headline" src="<?php echo image_path('headlines/headline_02_215x22.png')?>" alt="SEARCH FOR A SEGMENT"/><p class="login-text">Zero in on a particular segment of a song and search for similar sounds in the entire library, on the basis of acoustic similarity.</p></div>
<div class="login-box"><img class="login-headline" src="<?php echo image_path('headlines/headline_03_154x22.png')?>" alt="GET THE LICENSE" /><p class="login-text">If you have found what you are looking for, then you can license it directly from Music by Getty Images.</p></div>

<p class="terms">All rights reserved © 2012 Spectralmind</p>
</div>
 <script type="text/javascript">
 $(document).ready(function(){
	$('.error_list').parent().parent().css({position: 'absolute', width: 300+'px'});
 
 });
 
 </script>     
