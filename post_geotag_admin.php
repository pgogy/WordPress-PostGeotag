<?PHP

	function post_geotag_options_page() {

	  ?>
		<div class="wrap">
		<h2>Post Geo Tag Configuration</h2>
		<form method="post" action=""><?PHP
		
			wp_nonce_field('post_geotag','post_geotag');
			settings_fields( 'post_geotag' );
		
			$args = array(
							'child_of' => 0,
							'sort_order' => 'ASC',
							'sort_column' => 'post_title',
							'hierarchical' => 1,
							'parent' => -1,
							'offset' => 0,
							'post_type' => 'page',
							'post_status' => 'publish'
						); 
		
			$pages = get_pages( $args ); 
			
			?><label>Which page do you want to hold the site geomap?</label> <select name='post_geo_tag_page_replace'><?PHP
			
			$selected_page = get_option("post_geo_tag_page_replace");		

			foreach($pages as $page){
			
				echo "<option ";

				if($page->ID == $selected_page){
				
					echo " selected ";
				
				}

				echo " value=\"" . $page->ID . "\">" . $page->post_title . "</option>";
			
			}
			
			?></select><br/>
			<label>Which page do you want to host URL based KML files?</label> <select name='post_geo_tag_kml_page_replace'><?PHP
			
			$selected_page = get_option("post_geo_tag_kml_page_replace");		

			foreach($pages as $page){
			
				echo "<option ";

				if($page->ID == $selected_page){
				
					echo " selected ";
				
				}

				echo " value=\"" . $page->ID . "\">" . $page->post_title . "</option>";
			
			}
			
			?></select>
			<p>
				<label>Limit KML URLs to the following host</label>
				<input type="text" name="post_geo_tag_map_KML" size=100 value="<?PHP echo get_option("post_geo_tag_map_KML","Enter the URL to limit KML from"); ?>" />
			</p>
			<p>
				<label>Enter the height for the map</label>
				<input type="text" name="post_geo_tag_map_height" size=100 value="<?PHP echo get_option("post_geo_tag_map_height","Enter the height for the map ( CSS % or px)"); ?>" />
			</p>
			<p>
				<label>Enter the width for the map</label>
				<input type="text" name="post_geo_tag_map_width" size=100 value="<?PHP echo get_option("post_geo_tag_map_width","Enter the width for the map ( CSS % or px)"); ?>" />
			</p>
			<p>
				<label>Enter your google maps API Key</label>
				<input type="text" name="post_geo_tag_api_key" size=100 value="<?PHP echo get_option("post_geo_tag_api_key","Enter your API Key here"); ?>" />
			</p>
			<p>
				<label>Enter the title for your KML File</label>
				<input type="text" name="post_geo_tag_kml_title" size=100 value="<?PHP echo get_option("post_geo_tag_kml_title","Enter the title for your KML file here"); ?>" />
			</p>
			<p>
				<label>Enter the description for your KML File</label><br />
				<textarea name="post_geo_tag_kml_description" rows=5 style="width:100%"><?PHP echo get_option("post_geo_tag_kml_description","Enter the description for your KML file here"); ?></textarea>
			</p>
			<p>
				<label>Enter the HTML to display on the page before the map</label><br />
				<textarea name="post_geo_tag_before_map" rows=5 style="width:100%"><?PHP echo get_option("post_geo_tag_before_map","Enter the text / HTML to display before the map on the map page here."); ?></textarea>
			</p>
			<p>
				<label>Enter the HTML to display on the page after the map</label><br />
				<textarea name="post_geo_tag_post_map" rows=5 style="width:100%"><?PHP echo get_option("post_geo_tag_post_map","Enter the text / HTML to display after the map on the map page here."); ?></textarea>
			</p>
		<p class="submit">
		<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
		</form>
	</div>
	  
	  <?php
	}
	
	function post_geotag_postform(){
	
		if (!empty($_POST['post_geotag'])){

			if(!wp_verify_nonce($_POST['post_geotag'],'post_geotag') ){
			
				print 'Sorry, your nonce did not verify.';
				exit;
				
			}else{			
			
				if($_POST['option_page']=="post_geotag"){
					
					update_option("post_geo_tag_map_KML",$_POST['post_geo_tag_map_KML']);
					update_option("post_geo_tag_map_height",$_POST['post_geo_tag_map_height']);
					update_option("post_geo_tag_map_width",$_POST['post_geo_tag_map_width']);
					update_option("post_geo_tag_page_replace",$_POST['post_geo_tag_page_replace']);
					update_option("post_geo_tag_kml_page_replace",$_POST['post_geo_tag_kml_page_replace']);
					update_option("post_geo_tag_api_key",$_POST['post_geo_tag_api_key']);
					update_option("post_geo_tag_kml_title",stripslashes($_POST['post_geo_tag_kml_title']));
					update_option("post_geo_tag_kml_description",stripslashes($_POST['post_geo_tag_kml_description']));
					update_option("post_geo_tag_before_map",stripslashes($_POST['post_geo_tag_before_map']));
					update_option("post_geo_tag_post_map",stripslashes($_POST['post_geo_tag_post_map']));
					
				}
			
			}
		
		}
	
	}
	
	function post_geotag_menu_option() {
	
		add_options_page('Post Geo Tag Options', 'Post Geo Tag Options', 'manage_options', 'post_geotag', 'post_geotag_options_page');
		
	}
	
	add_action('admin_menu', 'post_geotag_menu_option');
	add_action('admin_head', 'post_geotag_postform');

?>