<div class="row">
	<div class="col s12">
		<!--tabs menu-->
		<ul class="tabs">
			<li class="tab col s3"><a class="active" href="#index-options">Indexing options</a></li>
			<li class="tab col s3"><a href="#settings">Settings</a></li>
			<li class="tab col s3"><a href="#test3">Shortcodes</a></li>
			<li class="tab col s3"><a href="#test4">Quick help</a></li>
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
						    <p><b>Custom CSS</b></p>
						    <textarea id="hb_custom_css"></textarea>
						    <p><a class="saveSettingsBtn heartbeat-admin-btn waves-effect waves-light btn">Save settings</a>		
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--/settings tab-->

		<div id="test3" class="col s12">Test 3</div>
		<div id="test4" class="col s12">Test 4</div>
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
