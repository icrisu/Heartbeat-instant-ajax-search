<div class="row">
	<div class="col s12">
		<!--tabs menu-->
		<ul class="tabs">
			<li class="tab col s3"><a class="active" href="#index-options">Indexing options</a></li>
			<li class="tab col s3"><a href="#settings">Settings</a></li>
			<li class="tab col s3"><a href="#shortcodes">Shortcodes</a></li>
		</ul>
		<!--/tabs menu-->
	</div>


	<!--tabs container-->
	<div class="admin-tabs-container">

		<!--index options container-->
		<div class="row" id="index-options">
			<div class="col s12">

				<div class="admin-tab-content">
					<div class="row">
						<div class="col s4">
							<p><b>Last index info</b></p>
							<blockquote class="heartbead-meta-info-ui2"></blockquote>
							<p><a class="indexDbBtn heartbeat-admin-btn waves-effect waves-light btn">Index db now</a></p>						
							
							<div class="progress-index">
								<p class="progress-label">Please wait ...</p>
								<div class="progress teal lighten-4">
									<div class="indeterminate teal lighten-3"></div>
								</div>								
							</div>						

						</div>
						<div class="col s4">
							<p><b>Choose which post types to index</b></p>
							<ul class="collection heartbeat-search-terms"></ul>
						</div>
					</div>					
				</div>
			</div>
		</div>
		<!--/index options container-->


		<!--settings tab-->
		<div id="settings" class="row">
			<div class="col s12">
				<div class="admin-tab-content">
					<div class="row">
						<div class="col s4">
							<p><b>Maximum search results</b></p>
							<p class="range-field">
						      <input class="hb_max_results" type="range" min="1" max="15" value="5" />
						    </p>
						    <p><a class="saveSettingsBtn heartbeat-admin-btn waves-effect waves-light btn">Save settings</a>
						</div>
						<div class="col s8">
						    <p><b>Custom CSS</b></p>
						    <textarea id="hb_custom_css"></textarea>							
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--/settings tab-->

		<!--shortcodes tab-->
		<div id="shortcodes" class="row">
			<div class="col s12">
				<div class="admin-tab-content">
					<div class="row">
						<div class="col s4">							
							<p><b>Material Design Form Shortcode</b></p>
							<blockquote class="heartbead-meta-info-ui2"><span class="hb-shortcode-display">[hbmd-search placeholder="Search"]</span></blockquote>
							<p><b>Simple Form Shortcode</b></p>
							<blockquote class="heartbead-meta-info-ui2"><span class="hb-shortcode-display">[hb-search placeholder="Search"]</span></blockquote>							
						</div>
						<div class="col s4">
							<p><b>Integrate with existing search forms</b></p>
						 	<p class="hb-activate-integration"></p>

						 	<p class="hb-jq-selector-ui">						 		
          					</p>

          					<p><a class="saveSettingsBtn heartbeat-admin-btn waves-effect waves-light btn">Save</a>
						</div>

						<div class="col s4">
          					<blockquote>
							<p>In order to integrate HeartBeat Search with your existing theme's search forms you should:</p>
							<p>1. Visit your website with Chrome browser.</p>
							<p>2. Right click within the input of an existing search form.</p>
							<p>3. Click the inspect button.</p>
							<p>4. Get the class name or the id of the input and place it above.</p>
							<p>5. Make sure you add a dot before the class ( Ex: .search-field ) or the # character before the id ( Ex:  #some-id).</p>
							<p>6. You can find out more about this within the documentation.</p>
          					</blockquote>							
						</div>

					</div>
				</div>
			</div>
		</div>
		<!--/shortcodes tab-->	

	</div>
	<!--/tabs container-->

	<!-- modal -->
	<div id="hb-modal" class="modal">
		<div class="modal-content">
			<h5 class="hb-modal-header"></h5>
			<div class="hb-modal-content"></div>
		</div>
		<div class="modal-footer">
			<a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">OK</a>
		</div>
	</div>
	<!-- /modal -->		

</div>
