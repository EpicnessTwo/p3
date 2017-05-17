<?php

if (!defined('ABSPATH')) die;

if (!function_exists('pipdig_help_options_page')) {
	function pipdig_help_options_page() { 

	?>
	
	<div class="wrap">

	<h1><?php _e( 'Help / Support', 'p3' ); ?></h1>

	<div id="poststuff">

		<div id="post-body" class="metabox-holder columns-1">

			<!-- main content -->
			<div id="post-body-content">

				<div class="meta-box-sortables ui-sortable">
				
					<p>Need some help? <a href="https://support.pipdig.co/?utm_source=wordpress&utm_medium=p3&utm_campaign=supportpage" target="_blank">Click here</a> to access our Helpdesk.</p>
					
					<h3>Please use the form below to search for topics:</h3>
				
					<form role="search" class="search-form" data-search="true" action="//support.pipdig.co/" accept-charset="UTF-8" method="get" target="_blank">
						<input type="hidden" value="1" name="ht-kb-search">
						<input type="hidden" value="" name="lang">
						<input type="search" name="s" id="query" placeholder="Search" />
						<input type="submit" name="commit" class="button" value="Search" />
					</form>

				</div>
				<!-- .meta-box-sortables .ui-sortable -->

			</div>
			<!-- post-body-content -->

		</div>
		<!-- #post-body .metabox-holder .columns-1 -->

		<br class="clear">
	</div>
	<!-- #poststuff -->

</div> <!-- .wrap -->
	
	
	
	
	<?php

	}
}

