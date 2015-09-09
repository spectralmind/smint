<div class="logo_container">
	<div class="spec_logo">
		<a href="http://www.spectralmind.com" target="_blank">
			<img src="<?php echo image_path('spec_logo_128_109.png')?>" alt="spectralmind audio intelligence" />
		</a>
	</div>
	<div class="search_logo">
		<a href="<?php echo url_for('search/index')?>">
			<img src="<?php echo image_path('search_logo_171_171_beta.png')?>" alt="SEARCH by Sound" />
		</a>
	</div>
	<div class="getty_logo">
		<a href="https://secure.gettyimages.co.uk/music" target="_blank">
			<img src="<?php echo image_path('getty_235x30.png')?>" alt="spectralmind audio intelligence" />
		</a>
	</div>
</div>

<div class="register-container">
 


<form action="<?php echo url_for('sfApply/apply') ?>" method="POST">
  <table class="regform">
  <tr>
      <td colspan="2">
       <h1 class="regtext">Register yourself</h1>
      </td>
    </tr>
    <?php echo $form ?>
    <tr>
      <td colspan="2">
        <input class="regbutton" type="submit" value="" />
      </td>
    </tr>
  </table>
</form> 

<div class="backlink">
<a href="<?php echo url_for('search/index')?>">
			Login
		</a>
</div>
<!--p class="forgotmessage">  <?php echo link_to('Technical Requirements', 'sfApply/technical' ); ?>.</p--> 
</div>


<p class="forgotmessage"><b>Forgot your password?</b> Please write to <?php echo mail_to('support@spectralmind.com'); ?>.</p>





