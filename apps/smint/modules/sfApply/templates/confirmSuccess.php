<div class="logo_container_login">
	<div class="spec_logo_login">
		<a href="http://www.spectralmind.com" target="_blank">
			<img src="<?php echo image_path('spec_logo_128_109.png')?>" alt="spectralmind audio intelligence" />
		</a>
	</div>
	<div class="search_logo_reg">
		<a href="<?php echo url_for('search/index')?>">
			<img src="<?php echo image_path('search_logo_171_171.png')?>" alt="SEARCH by Sound" />
		</a>
	</div>
</div>


<div class="text-container">
<p>
You have successfully registered to SEARCH by Sound portal.
Click here to continue to your login page.
</p>
<p>
<?php echo button_to("Continue", "@homepage") ?>
</p>
</div>
