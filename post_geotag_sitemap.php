<?php

function geotag_create_map(){

	echo get_option("post_geo_tag_before_map");

	?>
	<div id="map_canvas" style="width:<?PHP echo get_option("post_geo_tag_map_width"); ?>; height:<?PHP echo get_option("post_geo_tag_map_height"); ?>"></div>
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=<?PHP echo get_option("post_geo_tag_api_key"); ?>&sensor=true"></script>
		  <script type="text/javascript">
	  
		  function initialize() {
		  
			alert("HERE I AM");
		  
			var myOptions = {
				  center: new google.maps.LatLng(0,0),
				  zoom: 2,
				  mapTypeId: google.maps.MapTypeId.ROADMAP
			   };
			   var map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);

			var ctaLayer = new google.maps.KmlLayer('<?PHP echo get_site_url(); ?>/?feed=geo_tag_kml');
			ctaLayer.setMap(map);

		  }
		  
		  if (window.addEventListener) {
			  window.addEventListener('load', initialize, false);
		  }
		  else if (window.attachEvent) {
			  window.attachEvent('onload', initialize );
		  }

	 </script>
	<?PHP

	echo get_option("post_geo_tag_post_map");

}

function geotag_map_create($the_content) {	

	global $post;

	if($post->post_type=="page"&&$post->ID==get_option("post_geo_tag_page_replace")){
	
		geotag_create_map();

	}else{
		
		return $the_content;
		
	}		
		
}

function post_geo_tag_map_shortcode( $atts ) {		

	geotag_map_create();
		
}
	
add_shortcode('post_geo_tag_map', 'post_geo_tag_map_shortcode' );

add_action( 'the_content', 'geotag_map_create' );

?>