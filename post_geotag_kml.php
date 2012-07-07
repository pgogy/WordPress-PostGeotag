<?PHP

	function geo_post_tag_kml_feed(){

		header('Content-Type: application/xml');
    
	    echo '<?xml version="1.0" encoding="UTF-8"?>
	<kml xmlns="http://earth.google.com/kml/2.2">
	<Document>
	  <name>' . get_option('post_geo_tag_kml_title') . '</name>
	  <description><![CDATA[' . get_option('post_geo_tag_kml_description') . ']]></description>
	  <Style id="style1">
	    <IconStyle>
	      <Icon>
	        <href>http://maps.gstatic.com/mapfiles/ms2/micons/blue-dot.png</href>
	      </Icon>
	    </IconStyle>
	  </Style>';

           $post_types=get_post_types('','names'); 

	    $posts = array();

	    foreach ($post_types as $post_type ) {

			array_push($posts,$post_type);
			
	    }

	    $args = array(
	    'numberposts'     => -1,
	    'post_status'	 => 'publish',
            'post_type' => 'any');


	    $posts_array = get_posts( $args );

	    foreach($posts_array as $post_data){

	       $data = get_post_meta($post_data->ID, "geo_tags");

		if(isset($data[0])){

			$geo_info = unserialize($data[0]);

			foreach($geo_info as $geo_values){

				if($geo_values['place']!=""){

				?><Placemark>
				    <name><?PHP echo $geo_values['place']; ?></name>
				    <description><![CDATA[Mentioned in <a href="<?PHP echo $post_data->guid; ?>"><?PHP echo $post_data->post_title; ?></a> by <?PHP echo get_the_author_meta( "display_name", $post_data->post_author ); ?>]]></description>
				    <styleUrl>#style1</styleUrl>
				    <Point>
				      <coordinates><?PHP echo $geo_values['long']; ?>,<?PHP echo $geo_values['lat']; ?></coordinates>
				    </Point>
				  </Placemark><?PHP

				}

			}

		}

	    }

	    echo "</Document></kml>";

	}  

	add_action('do_feed_geo_tag_kml', 'geo_post_tag_kml_feed');

?>