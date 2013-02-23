<?PHP

	/*

		Plugin Name: Posts GeoTag
		Description: Add geotags a blog post and generates the KML Data source and if used, a google map page
		Version: 0.2
		Author: pgogy		
		Author URI: http://www.pgogy.com
		Plugin URI: http://www.pgogy.com/code/groups/wordpress/posts-geotag/
		
	*/

	function draw_geotag_menu(){

		?><p>
		<form>
			<?PHP

				wp_nonce_field('geotag_savepost','geotag_savepost');
				$counter = 0;	

				$data = get_post_meta($_GET['post'], "geo_tags");

				if($data!=""){

					$geo_data = unserialize($data[0]);

				}

			?>
			<p>Add new</p><p>
			<label>Place</label>
			<input name="geotag_place_0" type="text" size="100" /><br />
			<label>Latitude</label>
			<input name = "geotag_lat_0" type="text" />
			<label>Longitude</label>
			<input name = "geotag_long_0" type="text" /></p>			
			<?PHP

				if($geo_data!=""){

					foreach($geo_data as $place){

					  if($place['place']!=""){

					$counter++;

					?><p style="border-top:1px solid black"><label>Place</label>
					  <input name="geotag_place_<?PHP echo $counter; ?>" type="text" size="100" value="<?PHP echo $place['place']; ?>" />
					  <label>Latitude</label>
		 			  <input name = "geotag_lat_<?PHP echo $counter; ?>" type="text" value="<?PHP echo $place['lat']; ?>" />
				         <label>Longitude</label>
					  <input name = "geotag_long_<?PHP echo $counter; ?>" type="text" value="<?PHP echo $place['long']; ?>" />
				         <label>Delete</label>
					  <input name = "geotag_delete_<?PHP echo $counter; ?>" type="checkbox" /></p><?PHP

					  }

					}

				}

			?>
		</form>
		</p><?PHP 

	}

	function save_geotag($post_id){

		if ( !wp_verify_nonce( $_POST['geotag_savepost'], 'geotag_savepost' )) {				
    		return $post_id;
  		}

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
			return $post_id;

		if('page'==$_POST['post_type']){
    		if(!current_user_can('edit_page',$post_id))
      			return $post_id;
  		}else{
    		if ( !current_user_can('edit_post',$post_id))
      			return $post_id;
  		}	
  		  
		$geotags = array();

  		foreach($_POST as $key => $value){
	
			$id = explode("_",$key);

			if(isset($id[2])){

				$id = $id[2];

			}else{

				unset($id);

			}

			if(strpos($key,"geotag")!==FALSE){

				if(strpos($key,"place")!==FALSE){

					$geotags[$id]['place'] = $value;

				}

				if(strpos($key,"lat")!==FALSE){

					$geotags[$id]['lat'] = $value;

				}

				if(strpos($key,"long")!==FALSE){

					$geotags[$id]['long'] = $value;

				}

				if(strpos($key,"delete")!==FALSE){

					$geotags[$id] = FALSE;

				}
			
			}

		}

		$geotags = array_filter($geotags);

		update_post_meta($post_id, "geo_tags", serialize($geotags));
		

	}

	function geotag_add_menu($output){

		$post_types=get_post_types('','names'); 

		foreach ($post_types as $post_type ) {

			add_meta_box( 'geotag_id', 'Add Geo Tags',"draw_geotag_menu",$post_type,"normal","high");
			
		}
		
	}

	include "post_geotag_kml.php";
	include "post_geotag_sitemap.php";
	include "post_geotag_admin.php";

	add_action("add_meta_boxes", "geotag_add_menu" );
	add_action('save_post', 'save_geotag');

?>