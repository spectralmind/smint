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


<div class="text-container">
<br/>
<p><img src="<?php echo image_path('tagline_sbs_tutorial.png')?>" alt="Search by Sound Tutorial" /></p>
<br/>
<p><img src="<?php echo image_path('tagline_sbs_how_to.png')?>" alt="How to Search?" /></p>
<br/>
<hr class="line"/>
<br/>
<p class="tutorial_bold">Classic Meta-Data Search</p>
<br/>
<p class="tutorial_normal">Each song has proper Meta-Data associated with it. You can search songs by simply
typing Artist, Title of the song or Genre into the Search field and pressing ENTER or
SEARCH. You can listen to each song of the result list by clicking within the grey bar.</p>
<div class="img_container"><img src="<?php echo image_path('metaresults.png')?>" alt="Meta Data Search" /></div>
<hr class="line" />
<br/>
<p class="tutorial_bold">Acoustic Similarity Search</p>
<br/>
<p class="tutorial_normal">Have you found a song that you like with meta-data Search? Then use the similarity
feature to find similar sounding songs within the library.</p>

<ol>
	<li class="tutorial_normal">Select a song out of the library via the classic meta-data search</li>
	<li class="tutorial_normal">Each displayed result can be played and has the option to “search similar”.
This option is provided in the dropdown menu at the side of each displayed result
	<div class="img_container"><img src="<?php echo image_path('search_similar.png')?>" alt="Search Similar" /></div>
	</li>
	<li class="tutorial_normal">Now you get a list of results of the acoustic similarity search. You also see a waveform
of the seed song.</li>
	<li class="tutorial_normal">Please keep in mind that any meta-data in the search field is added to the Search
condition. <br/>If you want to have a pure acoustic similarity result remove any meta-data
from the search field.</li>
	<li class="tutorial_normal">If you like a song you can license the track from Getty images. This option is provided
in the dropdown menu at the side of each displayed result.</li>
</ol>
<br/>
<hr class="line"/>
<br/>
<p class="tutorial_bold">Uploading a Seed Song</p>
<br/>
<p class="tutorial_normal">You can use any mp3 file as a seed song for similarity Search or Segmented Search.
Use therefore the Upload Your Own Track bar.</p>
<div class="img_container"><img src="<?php echo image_path('uploadtrack.png')?>" alt="Uploading Seed Song" /></div>
<hr class="line"/>
<br/>
<p class="tutorial_bold">Searching for Music Segments</p>
<ol>
	<li class="tutorial_normal">Play the track of your choice (either via upload of track or from the results of entering
artist, title or genre) and select the portion of the track in the waveform for
which you want to find something acoustically similar.The selection should be at
least of 6 sec.
<div class="img_container"><img src="<?php echo image_path('segment_select.png')?>" alt="Segment Selection" /></div>
</li>
	<li class="tutorial_normal">Once the portion is selected, the results are automatically updated with similar
sounding segments highlighted in blue.
<div class="img_container"><img src="<?php echo image_path('segment_results.png')?>" alt="Segment Selection" /></div>
</li>
	<li class="tutorial_normal">With the help of play and skip button, you can jump through the segmented results
quickly and easily.</li>
	<li class="tutorial_normal">One can always combine the upload feature and metadata feature to apply more
filters to the segmented search results.</li>
</div>
