<?php




/*
Plugin Name: WP smtiggerjumpingslidingmenu
Plugin URI: http://wp-plugins.donimedia-servicetique.net/
Description: Wordpress extension ( plugin ) which allows to display , on your website , a customizable Flash set of sliding menus , whose submenus are animated by a similar movement to jumps of cartoon character "Tigger" . <a href="http://www.donimedia-servicetique.net/newsletters" title="Be well informed about our latest creations or updates">Newsletter</a> | <a href="http://www.donimedia-servicetique.net/support-forum-donimedia-servicetique-cms-extensions">Support Forum</a> | <a href="http://wp-plugins.donimedia-servicetique.net/">Other available plugins</a>
Version: 1.0.1
Author: David DONISA
Author URI: http://wp-plugins.donimedia-servicetique.net/
*/





	global $admin_panel_title, $wp_tijusm_plugin_prefix, $fullSizeImagesUploadDirectory, $thumbnailDirectory, $wp_tijusm_flash_component_width, $wp_tijusm_flash_component_height, $wp_tijusm_settings_group_ID, $wp_tijusm_settings_group_ID_request, $wp_tijusm_content;

	//  The function below allows to generate the swf code corresponding to each settings group , created by the administrator :

	function wp_tijusm_settings_group_swf_code($wp_tijusm_settings_group_ID) {

		$plugin_dir = basename(dirname(__FILE__));
		global $wp_tijusm_flash_component_width,$wp_tijusm_flash_component_height, $wp_tijusm_plugin_prefix, $wp_tijusm_settings_group_ID;
		
		$settings_file_name = 'movieclip_parameters'.$wp_tijusm_settings_group_ID.'.xml';
		$settings_file_path = WP_PLUGIN_DIR."/{$plugin_dir}/component/$settings_file_name";

		if ( get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID.'_'.'flash_component_width' ) != "") { 

			$wp_tijusm_flash_component_width  = get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID.'_'.'flash_component_width' ); 

		} else { 

			$settings_group_is_to_be_deleted = get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID.'_gallery_is_to_be_deleted' );

			if ( $settings_group_is_to_be_deleted == 'false'  ) {

				update_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID.'_'.'flash_component_width', '600' );
				$wp_tijusm_flash_component_width  = get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID.'_'.'flash_component_width' ); 

			} else {
			
				delete_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID.'_gallery_is_to_be_deleted' );

			};  //  Else 2 End 

		};  //  Else 1 End 
		
		if ( get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID.'_'.'flash_component_height' ) != "") { 

			$wp_tijusm_flash_component_height  = get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID.'_'.'flash_component_height' ); 

		} else { 

			$settings_group_is_to_be_deleted = get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID.'_gallery_is_to_be_deleted' );

			if ( $settings_group_is_to_be_deleted == 'false'  ) {

				update_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID.'_'.'flash_component_height', '600' );
				$wp_tijusm_flash_component_height  = get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID.'_'.'flash_component_height' ); 

			} else {
			
				delete_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID.'_gallery_is_to_be_deleted' );

			};  //  Else 2 End 

		};  //  Else 1 End

		if ($wp_tijusm_flash_component_width == 0 || $wp_tijusm_flash_component_height == 0) { return ''; };

		$swf_code = '<center>';
		$swf_code .= '<object width="'.$wp_tijusm_flash_component_width.'" height="'.$wp_tijusm_flash_component_height.'">';
		$swf_code .= '<param name="movie" value="'.WP_PLUGIN_URL."/{$plugin_dir}/component/wp_sliding_menu_type5.swf".'"></param>';
		$swf_code .= '<param name="scale" value="showall"></param>';
		$swf_code .= '<param name="salign" value="default"></param>';
		$swf_code .= '<param name="wmode" value="transparent"></param>';
		$swf_code .= '<param name="allowScriptAccess" value="sameDomain"></param>';
		$swf_code .= '<param name=FlashVars value="settings_group_ID_request='.$wp_tijusm_settings_group_ID.'">'."\n";
		$swf_code .= '<param name="allowFullScreen" value="true"></param>';
		$swf_code .= '<param name="sameDomain" value="true"></param>';
		$swf_code .= '<embed type="application/x-shockwave-flash" width="'.$wp_tijusm_flash_component_width.'" height="'.$wp_tijusm_flash_component_height.'" src="'.WP_PLUGIN_URL."/{$plugin_dir}/component/wp_sliding_menu_type5.swf".'" scale="showall" salign="default" wmode="transparent" allowScriptAccess="sameDomain" allowFullScreen="true" FlashVars="settings_group_ID_request='.$wp_tijusm_settings_group_ID.'"';
		$swf_code .= '></embed>';
		$swf_code .= '</object>';
		$swf_code .= '</center>';
		return $swf_code;

}  //   wp_tijusm_settings_group_swf_code End






	//  The function below allows to insert a script between the <head> and </head> tag , which detects automatically the Flash player :

	function wp_tijusm_settings_group_load_swf() {
		wp_enqueue_script('swfobject');
	}






function wp_tijusm_content_update($wp_tijusm_settings_group_ID){

	global $wp_tijusm_settings_group_ID, $wp_tijusm_content;


	$post_ID = get_the_ID();



	//  Connexion à la Base de données :

	mysql_connect(DB_HOST,DB_USER,DB_PASSWORD);  
	mysql_select_db(DB_NAME);      

	$query = "SELECT post_content FROM wp1_posts WHERE ID='".$post_ID."'";
  
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);   

	$post_content = $row["post_content"];


	

	//  $myrows = $wpdb->get_row( "SELECT $wpdb->post_content FROM $wpdb->wp1_posts WHERE $wpdb->ID='".$post_ID."'" );

	//  $post_content = $myrows->post_content;




	if ( get_option( 'wp_tijusm_plugin_group_counter' ) != "") { 

		$settings_group_counter  = get_option( 'wp_tijusm_plugin_group_counter' ); 

	} else { 

		update_option( 'wp_tijusm_plugin_group_counter', '0' );
		$settings_group_counter  = 0;

	};




	$wp_tijusm_content  = $post_content;  //  get_the_content('');

	for ( $i = 0; $i <= $settings_group_counter ; $i++ ) {

		$wp_tijusm_settings_group_ID = $i;

		$wp_tijusm_content = preg_replace_callback('|\[wp_tijusm'.$wp_tijusm_settings_group_ID.'\s*()?\s*\](.*)\[/wp_tijusm'.$wp_tijusm_settings_group_ID.'\]|i', 'wp_tijusm_settings_group_swf_code', $wp_tijusm_content);  //  Remplace pattern par résultat-fonction dans $wp_tijusm_content

	};	



	$new_content = $wp_tijusm_content;



	$query = "UPDATE wp1_posts SET post_content = '".$new_content."' WHERE ID = '".$post_ID."'";
  
	$result = mysql_query($query);  



	return $wp_tijusm_content;


}  //  function wp_tijusm_content_update($wp_tijusm_settings_group_ID ) End




	add_action('save_post','wp_tijusm_content_update');
	add_action('init', 'wp_tijusm_settings_group_load_swf');






	//  The function below allows to delete a file or to clear a directory ( without removing it ) :

    function wp_tijusm_clear_directory_or_file($path_to_directory_or_file) {

        if (is_dir($path_to_directory_or_file)) {

             $dir_pointer = opendir($path_to_directory_or_file); // lecture

             while ($dir_entry = readdir($dir_pointer)) {

             	if ($dir_entry != '.' && $dir_entry != '..') {

				$file = $path_to_directory_or_file.$dir_entry; // chemin fichier
             		if (is_dir($file)) {

					wp_tijusm_clear_directory_or_file($file); // rapel la fonction de manière récursive

				} else {

					unlink($file); // sup le fichier 

				} //  if 3 End
             	}  //  if  2 End
                }  // while End

		closedir($dir_pointer);
                             
	}  else {

                unlink($path_to_directory_or_file);  // sup le fichier
         }

}  //  function wp_tijusm_clear_directory_or_file End







//  The function below allows to remove accents from a given sentence :

function wp_tijusm_delete_accents($sentence) { 

	$charset='utf-8';
    	$sentence = htmlentities($sentence, ENT_NOQUOTES, $charset); 
     
    	$sentence = preg_replace('#\&([A-za-z])(?:acute|cedil|circ|grave|ring|tilde|uml)\;#', '\1', $sentence);
    	$sentence = preg_replace('#\&([A-za-z]{2})(?:lig)\;#', '\1', $sentence);
    	$sentence = preg_replace('#\&[^;]+\;#', '', $sentence);
	$sentence = preg_replace( '/\s+/', '_', $sentence);     

    	return $sentence;

}  //  function wp_tijusm_delete_accents End






//  The function below returns the ID of the first valid menu which is located below the one whose menu ID equals to $indice_menu_ID  :

function wp_tijusm_first_valid_menu_ID( $wp_tijusm_plugin_prefix, $indice_menu_ID ) {

	$wp_tijusm_first_valid_menu_ID = $indice_menu_ID - 1;

	//  Decreasing While loop to seek first valid menu ID :

	while ( ( get_option( $wp_tijusm_plugin_prefix.'menu'.intval($wp_tijusm_first_valid_menu_ID).'_title' ) == "" )  && ( $wp_tijusm_first_valid_menu_ID > 0 ) ) {

		$wp_tijusm_first_valid_menu_ID  -= 1;

	}; //  Loop While End

	return $wp_tijusm_first_valid_menu_ID;

}  //  function wp_tijusm_first_valid_menu_ID  End








//  The function below returns the ID of the first valid submenu which is located below the one whose submenu ID equals to $indice_submenu_ID  :

function wp_tijusm_first_valid_submenu_ID( $wp_tijusm_plugin_prefix, $indice_submenu_ID ) {

	$wp_tijusm_first_valid_submenu_ID = $indice_submenu_ID - 1;

	//  Decreasing While loop to seek first valid submenu ID :

	while ( ( get_option( $wp_tijusm_plugin_prefix.'submenu'.intval($wp_tijusm_first_valid_submenu_ID).'_title' ) == "" )  && ( $wp_tijusm_first_valid_submenu_ID > 0 ) ) {

		$wp_tijusm_first_valid_submenu_ID  -= 1;

	}; //  Loop While End

	return $wp_tijusm_first_valid_submenu_ID;

}  //  function wp_tijusm_first_valid_submenu_ID  End






//  The function below returns the ID of the first valid settings group which is located below the one whose settings group ID equals to $indice_submenu_ID  :

function wp_tijusm_first_valid_settings_group_ID( $wp_tijusm_plugin_prefix, $wp_tijusm_settings_group_ID ) {

	$wp_tijusm_first_valid_settings_group_ID = $wp_tijusm_settings_group_ID - 1;

	//  Decreasing While loop to seek first valid settings group ID :

	while ( ( get_option( $wp_tijusm_plugin_prefix.'settings_group_ID' ) == "" )  && ( $wp_tijusm_first_valid_settings_group_ID > 0 ) ) {

		$wp_tijusm_first_valid_settings_group_ID  -= 1;

	}; //  Loop While End

	return $wp_tijusm_first_valid_settings_group_ID;

}  //  function wp_tijusm_first_valid_settings_group_ID  End







//  The function below allows to handle the different requests launched by the user , from the admin panel :

function wp_tijusm_mytheme_add_admin() {
 
global $admin_panel_title, $wp_tijusm_flash_component_width, $wp_tijusm_flash_component_height, $wp_tijusm_plugin_prefix, $wp_tijusm_settings_group_ID, $wp_tijusm_settings_group_ID_request, $wp_tijusm_settings_group_ID_reception;

if ( $_GET['page'] == basename(__FILE__) ) {

	switch ( $_REQUEST['action'] ) {

		case 'save_settings_group_ID' :

			 $wp_tijusm_settings_group_ID_reception = $_REQUEST['plugin_prefix_settings_group_ID'];

			update_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_reception, 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_reception.'_' );
			update_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_reception.'_settings_group_ID', $wp_tijusm_settings_group_ID_reception );


			//  This instruction creates an indicator which determines if the settings group options must be totally deleted or only reset .
			//  That is to be said , if its value is set to ""false" , then the settings group is no to be deleted , thus the settings group options will only be reset .

			update_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_reception.'_settings_group_is_to_be_deleted', 'false' );

			
			//  Incrementation of the settings group counter :

			if ( get_option( 'wp_tijusm_plugin_group_counter' ) != "") { 

				$settings_group_counter  = intval(get_option( 'wp_tijusm_plugin_group_counter' )); 

			} else { 

				update_option( 'wp_tijusm_plugin_group_counter', '0' );
				$settings_group_counter  = 0;

			};

			//  Greater settings group ID storage :

			if ( intval($wp_tijusm_settings_group_ID_reception) > $settings_group_counter ) {

				update_option( 'wp_tijusm_plugin_group_counter', $wp_tijusm_settings_group_ID_reception );			

			};

			header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&settings_group_ID_saved=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."");
			die;

    			break;





		case 'save_title' :
			
			$wp_tijusm_plugin_prefix_reception = $_REQUEST['plugin_prefix_request'];
			$wp_tijusm_settings_group_ID_reception = $_REQUEST['settings_group_ID_request'];

			update_option( $wp_tijusm_plugin_prefix_reception.'title', $_REQUEST[$wp_tijusm_plugin_prefix_reception.'title'] ); 

			header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&title_saved=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."&plugin_prefix_request=".$wp_tijusm_plugin_prefix_reception."");
			die;

    			break;


		case 'save_subtitle' :
			
			$wp_tijusm_plugin_prefix_reception = $_REQUEST['plugin_prefix_request'];
			$wp_tijusm_settings_group_ID_reception = $_REQUEST['settings_group_ID_request'];

			update_option( $wp_tijusm_plugin_prefix_reception.'subtitle', $_REQUEST[$wp_tijusm_plugin_prefix_reception.'subtitle'] ); 

			header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&title_saved=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."&plugin_prefix_request=".$wp_tijusm_plugin_prefix_reception."");
			die;

    			break;


		case 'save_flash_component_width' :
			
			$wp_tijusm_plugin_prefix_reception = $_REQUEST['plugin_prefix_request'];
			$wp_tijusm_settings_group_ID_reception = $_REQUEST['settings_group_ID_request'];

			update_option( $wp_tijusm_plugin_prefix_reception.'flash_component_width', $_REQUEST[$wp_tijusm_plugin_prefix_reception.'flash_component_width'] ); 

			header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&flash_component_width_saved=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."&plugin_prefix_request=".$wp_tijusm_plugin_prefix_reception."");
			die;

    			break;


		case 'save_flash_component_height' :
			
			$wp_tijusm_plugin_prefix_reception = $_REQUEST['plugin_prefix_request'];
			$wp_tijusm_settings_group_ID_reception = $_REQUEST['settings_group_ID_request'];

			update_option( $wp_tijusm_plugin_prefix_reception.'flash_component_height', $_REQUEST[$wp_tijusm_plugin_prefix_reception.'flash_component_height'] ); 

			header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&flash_component_height_saved=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."&plugin_prefix_request=".$wp_tijusm_plugin_prefix_reception."");
			die;

    			break;














		case 'save_menus_vertical_position' :
			
			$wp_tijusm_plugin_prefix_reception = $_REQUEST['plugin_prefix_request'];
			$wp_tijusm_settings_group_ID_reception = $_REQUEST['settings_group_ID_request'];

			update_option( $wp_tijusm_plugin_prefix_reception.'menus_vertical_position', $_REQUEST[$wp_tijusm_plugin_prefix_reception.'menus_vertical_position'] ); 

			header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&menus_vertical_position_saved=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."&plugin_prefix_request=".$wp_tijusm_plugin_prefix_reception."");
			die;

    			break;



		case 'save_menus_horizontal_position' :
			
			$wp_tijusm_plugin_prefix_reception = $_REQUEST['plugin_prefix_request'];
			$wp_tijusm_settings_group_ID_reception = $_REQUEST['settings_group_ID_request'];

			update_option( $wp_tijusm_plugin_prefix_reception.'menus_horizontal_position', $_REQUEST[$wp_tijusm_plugin_prefix_reception.'menus_horizontal_position'] ); 

			header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&menus_horizontal_position_saved=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."&plugin_prefix_request=".$wp_tijusm_plugin_prefix_reception."");
			die;

    			break;



		case 'save_movement_curvature_radius' :
			
			$wp_tijusm_plugin_prefix_reception = $_REQUEST['plugin_prefix_request'];
			$wp_tijusm_settings_group_ID_reception = $_REQUEST['settings_group_ID_request'];

			update_option( $wp_tijusm_plugin_prefix_reception.'movement_curvature_radius', $_REQUEST[$wp_tijusm_plugin_prefix_reception.'movement_curvature_radius'] ); 

			header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&movement_curvature_radius_saved=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."&plugin_prefix_request=".$wp_tijusm_plugin_prefix_reception."");
			die;

    			break;



		case 'save_submenus_vertical_position' :
			
			$wp_tijusm_plugin_prefix_reception = $_REQUEST['plugin_prefix_request'];
			$wp_tijusm_settings_group_ID_reception = $_REQUEST['settings_group_ID_request'];

			update_option( $wp_tijusm_plugin_prefix_reception.'submenus_vertical_position', $_REQUEST[$wp_tijusm_plugin_prefix_reception.'submenus_vertical_position'] ); 

			header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&submenus_vertical_position_saved=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."&plugin_prefix_request=".$wp_tijusm_plugin_prefix_reception."");
			die;

    			break;



		case 'save_submenus_horizontal_position' :
			
			$wp_tijusm_plugin_prefix_reception = $_REQUEST['plugin_prefix_request'];
			$wp_tijusm_settings_group_ID_reception = $_REQUEST['settings_group_ID_request'];

			update_option( $wp_tijusm_plugin_prefix_reception.'submenus_horizontal_position', $_REQUEST[$wp_tijusm_plugin_prefix_reception.'submenus_horizontal_position'] ); 

			header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&submenus_horizontal_position_saved=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."&plugin_prefix_request=".$wp_tijusm_plugin_prefix_reception."");
			die;

    			break;



		case 'save_submenus_horizontal_position' :
			
			$wp_tijusm_plugin_prefix_reception = $_REQUEST['plugin_prefix_request'];
			$wp_tijusm_settings_group_ID_reception = $_REQUEST['settings_group_ID_request'];

			update_option( $wp_tijusm_plugin_prefix_reception.'submenus_horizontal_position', $_REQUEST[$wp_tijusm_plugin_prefix_reception.'submenus_horizontal_position'] ); 

			header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&submenus_horizontal_position_saved=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."&plugin_prefix_request=".$wp_tijusm_plugin_prefix_reception."");
			die;

    			break;












		case 'save_menu_text_color' :
			
			$wp_tijusm_plugin_prefix_reception = $_REQUEST['plugin_prefix_request'];
			$wp_tijusm_settings_group_ID_reception = $_REQUEST['settings_group_ID_request'];

			update_option( $wp_tijusm_plugin_prefix_reception.'menu_text_color', $_REQUEST[$wp_tijusm_plugin_prefix_reception.'menu_text_color'] ); 

			header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&menu_text_color_saved=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."&plugin_prefix_request=".$wp_tijusm_plugin_prefix_reception."");
			die;

    			break;


		case 'save_menu_background_color' :
			
			$wp_tijusm_plugin_prefix_reception = $_REQUEST['plugin_prefix_request'];
			$wp_tijusm_settings_group_ID_reception = $_REQUEST['settings_group_ID_request'];

			update_option( $wp_tijusm_plugin_prefix_reception.'menu_background_color', $_REQUEST[$wp_tijusm_plugin_prefix_reception.'menu_background_color'] ); 

			header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&menu_background_color_saved=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."&plugin_prefix_request=".$wp_tijusm_plugin_prefix_reception."");
			die;

    			break;


		case 'save_menu_border_color' :
			
			$wp_tijusm_plugin_prefix_reception = $_REQUEST['plugin_prefix_request'];
			$wp_tijusm_settings_group_ID_reception = $_REQUEST['settings_group_ID_request'];

			update_option( $wp_tijusm_plugin_prefix_reception.'menu_border_color', $_REQUEST[$wp_tijusm_plugin_prefix_reception.'menu_border_color'] ); 

			header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&menu_border_color_saved=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."&plugin_prefix_request=".$wp_tijusm_plugin_prefix_reception."");
			die;

    			break;


		case 'save_menu_index_text_color' :
			
			$wp_tijusm_plugin_prefix_reception = $_REQUEST['plugin_prefix_request'];
			$wp_tijusm_settings_group_ID_reception = $_REQUEST['settings_group_ID_request'];

			update_option( $wp_tijusm_plugin_prefix_reception.'menu_index_text_color', $_REQUEST[$wp_tijusm_plugin_prefix_reception.'menu_index_text_color'] ); 

			header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&menu_index_text_color_saved=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."&plugin_prefix_request=".$wp_tijusm_plugin_prefix_reception."");
			die;

    			break;


		case 'save_menu_index_background_color' :
			
			$wp_tijusm_plugin_prefix_reception = $_REQUEST['plugin_prefix_request'];
			$wp_tijusm_settings_group_ID_reception = $_REQUEST['settings_group_ID_request'];

			update_option( $wp_tijusm_plugin_prefix_reception.'menu_index_background_color', $_REQUEST[$wp_tijusm_plugin_prefix_reception.'menu_index_background_color'] ); 

			header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&menu_index_background_color_saved=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."&plugin_prefix_request=".$wp_tijusm_plugin_prefix_reception."");
			die;

    			break;


		case 'save_menu_index_border_color' :
			
			$wp_tijusm_plugin_prefix_reception = $_REQUEST['plugin_prefix_request'];
			$wp_tijusm_settings_group_ID_reception = $_REQUEST['settings_group_ID_request'];

			update_option( $wp_tijusm_plugin_prefix_reception.'menu_index_border_color', $_REQUEST[$wp_tijusm_plugin_prefix_reception.'menu_index_border_color'] ); 

			header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&menu_index_border_color_saved=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."&plugin_prefix_request=".$wp_tijusm_plugin_prefix_reception."");
			die;

    			break;


		case 'save_vertical_menus_spacing' :
			
			$wp_tijusm_plugin_prefix_reception = $_REQUEST['plugin_prefix_request'];
			$wp_tijusm_settings_group_ID_reception = $_REQUEST['settings_group_ID_request'];

			update_option( $wp_tijusm_plugin_prefix_reception.'vertical_menus_spacing', $_REQUEST[$wp_tijusm_plugin_prefix_reception.'vertical_menus_spacing'] ); 

			header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&vertical_menus_spacing_saved=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."&plugin_prefix_request=".$wp_tijusm_plugin_prefix_reception."");
			die;

    			break;


		case 'save_menus_rollover_color' :
			
			$wp_tijusm_plugin_prefix_reception = $_REQUEST['plugin_prefix_request'];
			$wp_tijusm_settings_group_ID_reception = $_REQUEST['settings_group_ID_request'];

			update_option( $wp_tijusm_plugin_prefix_reception.'menus_rollover_color', $_REQUEST[$wp_tijusm_plugin_prefix_reception.'menus_rollover_color'] ); 

			header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&menus_rollover_color_saved=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."&plugin_prefix_request=".$wp_tijusm_plugin_prefix_reception."");
			die;

    			break;


		case 'save_menus_rollover_transparency' :
			
			$wp_tijusm_plugin_prefix_reception = $_REQUEST['plugin_prefix_request'];
			$wp_tijusm_settings_group_ID_reception = $_REQUEST['settings_group_ID_request'];

			update_option( $wp_tijusm_plugin_prefix_reception.'menus_rollover_transparency', $_REQUEST[$wp_tijusm_plugin_prefix_reception.'menus_rollover_transparency'] ); 

			header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&menus_rollover_transparency_saved=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."&plugin_prefix_request=".$wp_tijusm_plugin_prefix_reception."");
			die;

    			break;


		case 'save_submenu_text_color' :
			
			$wp_tijusm_plugin_prefix_reception = $_REQUEST['plugin_prefix_request'];
			$wp_tijusm_settings_group_ID_reception = $_REQUEST['settings_group_ID_request'];

			update_option( $wp_tijusm_plugin_prefix_reception.'submenu_text_color', $_REQUEST[$wp_tijusm_plugin_prefix_reception.'submenu_text_color'] ); 

			header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&submenu_text_color_saved=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."&plugin_prefix_request=".$wp_tijusm_plugin_prefix_reception."");
			die;

    			break;


		case 'save_submenu_background_color' :
			
			$wp_tijusm_plugin_prefix_reception = $_REQUEST['plugin_prefix_request'];
			$wp_tijusm_settings_group_ID_reception = $_REQUEST['settings_group_ID_request'];

			update_option( $wp_tijusm_plugin_prefix_reception.'submenu_background_color', $_REQUEST[$wp_tijusm_plugin_prefix_reception.'submenu_background_color'] ); 

			header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&submenu_background_color_saved=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."&plugin_prefix_request=".$wp_tijusm_plugin_prefix_reception."");
			die;

    			break;


		case 'save_submenu_border_color' :
			
			$wp_tijusm_plugin_prefix_reception = $_REQUEST['plugin_prefix_request'];
			$wp_tijusm_settings_group_ID_reception = $_REQUEST['settings_group_ID_request'];

			update_option( $wp_tijusm_plugin_prefix_reception.'submenu_border_color', $_REQUEST[$wp_tijusm_plugin_prefix_reception.'submenu_border_color'] ); 

			header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&submenu_border_color_saved=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."&plugin_prefix_request=".$wp_tijusm_plugin_prefix_reception."");
			die;

    			break;


		case 'save_vertical_submenus_spacing' :
			
			$wp_tijusm_plugin_prefix_reception = $_REQUEST['plugin_prefix_request'];
			$wp_tijusm_settings_group_ID_reception = $_REQUEST['settings_group_ID_request'];

			update_option( $wp_tijusm_plugin_prefix_reception.'vertical_submenus_spacing', $_REQUEST[$wp_tijusm_plugin_prefix_reception.'vertical_submenus_spacing'] ); 

			header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&vertical_submenus_spacing_saved=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."&plugin_prefix_request=".$wp_tijusm_plugin_prefix_reception."");
			die;

    			break;


		case 'save_horizontal_submenus_spacing' :
			
			$wp_tijusm_plugin_prefix_reception = $_REQUEST['plugin_prefix_request'];
			$wp_tijusm_settings_group_ID_reception = $_REQUEST['settings_group_ID_request'];

			update_option( $wp_tijusm_plugin_prefix_reception.'horizontal_submenus_spacing', $_REQUEST[$wp_tijusm_plugin_prefix_reception.'horizontal_submenus_spacing'] ); 

			header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&horizontal_submenus_spacing_saved=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."&plugin_prefix_request=".$wp_tijusm_plugin_prefix_reception."");
			die;

    			break;


		case 'save_submenus_rollover_color' :
			
			$wp_tijusm_plugin_prefix_reception = $_REQUEST['plugin_prefix_request'];
			$wp_tijusm_settings_group_ID_reception = $_REQUEST['settings_group_ID_request'];

			update_option( $wp_tijusm_plugin_prefix_reception.'submenus_rollover_color', $_REQUEST[$wp_tijusm_plugin_prefix_reception.'submenus_rollover_color'] ); 

			header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&submenus_rollover_color_saved=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."&plugin_prefix_request=".$wp_tijusm_plugin_prefix_reception."");
			die;

    			break;


		case 'save_submenus_rollover_transparency' :
			
			$wp_tijusm_plugin_prefix_reception = $_REQUEST['plugin_prefix_request'];
			$wp_tijusm_settings_group_ID_reception = $_REQUEST['settings_group_ID_request'];

			update_option( $wp_tijusm_plugin_prefix_reception.'submenus_rollover_transparency', $_REQUEST[$wp_tijusm_plugin_prefix_reception.'submenus_rollover_transparency'] ); 

			header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&submenus_rollover_transparency_saved=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."&plugin_prefix_request=".$wp_tijusm_plugin_prefix_reception."");
			die;

    			break;
















		case 'save_new_menu_declaration' :
			
			$wp_tijusm_plugin_prefix_reception = $_REQUEST['plugin_prefix_request'];
			$wp_tijusm_settings_group_ID_reception = $_REQUEST['settings_group_ID_request'];
			$indice_menu_reception = $_REQUEST['indice_menu'];
			$menu_title_reception = $_REQUEST[$wp_tijusm_plugin_prefix_reception.'menu'.intval($indice_menu_reception).'_title'];
				
			//  Sup menus counter retrieval :
			
			if ( get_option( $wp_tijusm_plugin_prefix_reception.'sup_menus_counter' ) != "") { 

				$sup_menus_counter  = intval(get_option( $wp_tijusm_plugin_prefix_reception.'sup_menus_counter' )); 

			} else { 

				update_option( $wp_tijusm_plugin_prefix_reception.'sup_menus_counter', '-1' );
				$sup_menus_counter  = -1;

			};

			if ( $menu_title_reception != "" ) {

				update_option( $wp_tijusm_plugin_prefix_reception.'menu'.intval($indice_menu_reception).'_title', $menu_title_reception ); 
				update_option( $wp_tijusm_plugin_prefix_reception.'menu'.intval($indice_menu_reception).'_url', $_REQUEST[$wp_tijusm_plugin_prefix_reception.'menu'.intval($indice_menu_reception).'_url'] ); 
				update_option( $wp_tijusm_plugin_prefix_reception.'indice_menu'.$indice_menu_reception, $_REQUEST['indice_menu'] ); 

			

				//  ( Greater sup menus counter storage )

				if ( intval($indice_menu_reception) > $sup_menus_counter ) {

					update_option( $wp_tijusm_plugin_prefix_reception.'sup_menus_counter', $indice_menu_reception );			

				};



				header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&new_menu_saved=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."&plugin_prefix_request=".$wp_tijusm_plugin_prefix_reception."");

				die;

    				break;

			} else {

					header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&empty_menu_title=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."&plugin_prefix_request=".$wp_tijusm_plugin_prefix_reception."");

					die;

    					break;

			};   //  if ( $menu_title_reception != "" ) End



		case 'delete_menu' :

			$wp_tijusm_plugin_prefix_reception = $_REQUEST['plugin_prefix_request'];
			$wp_tijusm_settings_group_ID_reception = $_REQUEST['settings_group_ID_request'];
			$indice_menu_reception = $_REQUEST['indice_menu'];


			delete_option( $wp_tijusm_plugin_prefix_reception.'menu'.intval($indice_menu_reception).'_title' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'menu'.intval($indice_menu_reception).'_url' ); 
			delete_option( $wp_tijusm_plugin_prefix_reception.'indice_menu'.$indice_menu_reception ); 



			// Decrementation of the sup menus counter :

			$sup_menus_counter = intval(get_option( $wp_tijusm_plugin_prefix_reception.'sup_menus_counter' ));

			//  Greater sup menus counter storage : If user removed a menu whose ID was the greatest of all ( for instance , 4 ) , then the new greatest ID 
			//  is the integer that precedes ( Therefore : 3 ) . 

			//  Where ( $indice_menu_reception > $sup_menus_counter ) is not discussed because this case never met here .

			if ( ($indice_menu_reception == $sup_menus_counter) && ( $sup_menus_counter != 0 ) ) { 

				//  In this case, using a function , we must seek first valid menu ID ( that is to say, the first menu with a plugin_prefix ) , 
				//  which is located below the one whose menu ID equals to $sup_menus_counter :

				update_option( 'wp_tijusm_first_valid_menu_ID', wp_tijusm_first_valid_menu_ID( $wp_tijusm_plugin_prefix_reception,$indice_menu_reception ) );

				if ( wp_tijusm_first_valid_menu_ID( $wp_tijusm_plugin_prefix_reception,$indice_menu_reception ) >= 0 ) {

					update_option( $wp_tijusm_plugin_prefix_reception.'sup_menus_counter', wp_tijusm_first_valid_menu_ID( $wp_tijusm_plugin_prefix_reception,$indice_menu_reception ) );				

				};

			} elseif ( ($indice_menu_reception == $sup_menus_counter) && ( $sup_menus_counter == 0 ) ) {

					update_option( $wp_tijusm_plugin_prefix_reception.'sup_menus_counter', -1 );

			}; //  elseif () End



			header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&menu_deleted=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."&plugin_prefix_request=".$wp_tijusm_plugin_prefix_reception."");
			die;

    			break;











		case 'save_new_submenu_declaration' :
			
			$wp_tijusm_plugin_prefix_reception = $_REQUEST['plugin_prefix_request'];
			$wp_tijusm_settings_group_ID_reception = $_REQUEST['settings_group_ID_request'];
			$indice_submenu_reception = $_REQUEST['indice_submenu'];
			$submenu_title_reception = $_REQUEST[$wp_tijusm_plugin_prefix_reception.'submenu'.intval($indice_submenu_reception).'_title'];
			$submenu_parent_menu_id_reception = $_REQUEST[$wp_tijusm_plugin_prefix_reception.'submenu'.intval($indice_submenu_reception).'_parent_menu_id'];


				
			//  Sup submenus counter retrieval :
			
			if ( get_option( $wp_tijusm_plugin_prefix_reception.'sup_submenus_counter' ) != "") { 

				$sup_submenus_counter  = intval(get_option( $wp_tijusm_plugin_prefix_reception.'sup_submenus_counter' )); 

			} else { 

				update_option( $wp_tijusm_plugin_prefix_reception.'sup_submenus_counter', '-1' );
				$sup_submenus_counter  = -1;

			};

			if ( ($submenu_title_reception != "") && ( $submenu_parent_menu_id_reception != "" ) ) {

				update_option( $wp_tijusm_plugin_prefix_reception.'submenu'.intval($indice_submenu_reception).'_title', $submenu_title_reception ); 
				update_option( $wp_tijusm_plugin_prefix_reception.'submenu'.intval($indice_submenu_reception).'_url', $_REQUEST[$wp_tijusm_plugin_prefix_reception.'submenu'.intval($indice_submenu_reception).'_url'] ); 
				update_option( $wp_tijusm_plugin_prefix_reception.'submenu'.intval($indice_submenu_reception).'_parent_menu_id', $_REQUEST[$wp_tijusm_plugin_prefix_reception.'submenu'.intval($indice_submenu_reception).'_parent_menu_id'] ); 
				update_option( $wp_tijusm_plugin_prefix_reception.'indice_submenu'.$indice_submenu_reception, $_REQUEST['indice_submenu'] ); 


			

				//  ( Greater sup submenus counter storage )

				if ( intval($indice_submenu_reception) > $sup_submenus_counter ) {

					update_option( $wp_tijusm_plugin_prefix_reception.'sup_submenus_counter', $indice_submenu_reception );			

				};



				header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&new_submenu_saved=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."&plugin_prefix_request=".$wp_tijusm_plugin_prefix_reception."");

				die;

    				break;

			} else {

					header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&empty_submenu_title_or_parent_menu_id=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."&plugin_prefix_request=".$wp_tijusm_plugin_prefix_reception."");

					die;

    					break;

			};   //  if ( $submenu_title_reception != "" ) End



		case 'delete_submenu' :

			$wp_tijusm_plugin_prefix_reception = $_REQUEST['plugin_prefix_request'];
			$wp_tijusm_settings_group_ID_reception = $_REQUEST['settings_group_ID_request'];
			$indice_submenu_reception = $_REQUEST['indice_submenu'];


			delete_option( $wp_tijusm_plugin_prefix_reception.'submenu'.intval($indice_submenu_reception).'_title' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'submenu'.intval($indice_submenu_reception).'_url' ); 
			delete_option( $wp_tijusm_plugin_prefix_reception.'submenu'.intval($indice_submenu_reception).'_parent_menu_id' ); 
			delete_option( $wp_tijusm_plugin_prefix_reception.'indice_submenu'.$indice_submenu_reception ); 



			// Decrementation of the sup submenus counter :

			$sup_submenus_counter = intval(get_option( $wp_tijusm_plugin_prefix_reception.'sup_submenus_counter' ));

			//  Greater sup submenus counter storage : If user removed a submenu whose ID was the greatest of all ( for instance , 4 ) , then the new greatest ID 
			//  is the integer that precedes ( Therefore : 3 ) . 

			//  Where ( $indice_submenu_reception > $sup_submenus_counter ) is not discussed because this case never met here .

			if ( ($indice_submenu_reception == $sup_submenus_counter) && ( $sup_submenus_counter != 0 ) ) { 

				//  In this case, using a function , we must seek first valid submenu ID ( that is to say, the first submenu with a plugin_prefix ) , 
				//  which is located below the one whose submenu ID equals to $sup_submenus_counter :

				update_option( 'wp_tijusm_first_valid_submenu_ID', wp_tijusm_first_valid_submenu_ID( $wp_tijusm_plugin_prefix_reception,$indice_submenu_reception ) );

				if ( wp_tijusm_first_valid_submenu_ID( $wp_tijusm_plugin_prefix_reception,$indice_submenu_reception ) >= 0 ) {

					update_option( $wp_tijusm_plugin_prefix_reception.'sup_submenus_counter', wp_tijusm_first_valid_submenu_ID( $wp_tijusm_plugin_prefix_reception,$indice_submenu_reception ) );				

				};

			} elseif ( ($indice_submenu_reception == $sup_submenus_counter) && ( $sup_submenus_counter == 0 ) ) {

					update_option( $wp_tijusm_plugin_prefix_reception.'sup_submenus_counter', -1 );

			}; //  elseif () End



			header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&submenu_deleted=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."&plugin_prefix_request=".$wp_tijusm_plugin_prefix_reception."");
			die;

    			break;








		case 'reset' :

			$wp_tijusm_plugin_prefix_reception = $_REQUEST['plugin_prefix_request'];
			$wp_tijusm_settings_group_ID_reception = $_REQUEST['settings_group_ID_request'];


			delete_option( $wp_tijusm_plugin_prefix_reception.'title' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'subtitle');
			delete_option( $wp_tijusm_plugin_prefix_reception.'flash_component_width');
			delete_option( $wp_tijusm_plugin_prefix_reception.'flash_component_height' );

			delete_option( $wp_tijusm_plugin_prefix_reception.'menus_vertical_position' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'menus_horizontal_position' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'movement_curvature_radius' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'submenus_vertical_position' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'submenus_horizontal_position' );

			delete_option( $wp_tijusm_plugin_prefix_reception.'menu_text_color' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'menu_background_color' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'menu_border_color' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'menu_index_text_color' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'menu_index_background_color' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'menu_index_border_color' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'vertical_menus_spacing' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'menus_rollover_color' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'menus_rollover_transparency' );

			delete_option( $wp_tijusm_plugin_prefix_reception.'submenu_text_color' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'submenu_background_color' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'submenu_border_color' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'vertical_submenus_spacing' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'horizontal_submenus_spacing' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'submenus_rollover_color' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'submenus_rollover_transparency' );

			header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&reset=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."&plugin_prefix_request=".$wp_tijusm_plugin_prefix_reception."");
			die; 

    			break;







		case 'delete_settings_group' :

			$wp_tijusm_plugin_prefix_reception = $_REQUEST['plugin_prefix_request'];
			$wp_tijusm_settings_group_ID_reception = $_REQUEST['settings_group_ID_request'];


			//  Sup menus counter retrieval :
			
			if ( get_option( $wp_tijusm_plugin_prefix_reception.'sup_menus_counter' ) != "") { 

				$sup_menus_counter  = intval(get_option( $wp_tijusm_plugin_prefix_reception.'sup_menus_counter' )); 

			} else { 

				update_option( $wp_tijusm_plugin_prefix_reception.'sup_menus_counter', '-1' );
				$sup_menus_counter  = -1;

			};




			//  Sup submenus counter retrieval :
			
			if ( get_option( $wp_tijusm_plugin_prefix_reception.'sup_menus_counter' ) != "") { 

				$sup_submenus_counter  = intval(get_option( $wp_tijusm_plugin_prefix_reception.'sup_submenus_counter' )); 

			} else { 

				update_option( $wp_tijusm_plugin_prefix_reception.'sup_submenus_counter', '-1' );
				$sup_submenus_counter  = -1;

			};


			//  Menus deletion :

			for ($i = 0; $i <= $sup_menus_counter; $i++) {
    			
				delete_option( $wp_tijusm_plugin_prefix_reception.'indice_menu'.$i );
				delete_option( $wp_tijusm_plugin_prefix_reception.'menu'.$i.'_title' ); 
				delete_option( $wp_tijusm_plugin_prefix_reception.'menu'.$i.'_url' ); 
			
			};


			//  Submenus deletion :

			for ($i = 0; $i <= $sup_submenus_counter; $i++) {

				delete_option( $wp_tijusm_plugin_prefix_reception.'indice_submenu'.$i );
				delete_option( $wp_tijusm_plugin_prefix_reception.'submenu'.$i.'_parent_menu_id' ); 
				delete_option( $wp_tijusm_plugin_prefix_reception.'submenu'.$i.'_title' ); 
				delete_option( $wp_tijusm_plugin_prefix_reception.'submenu'.$i.'_url' ); 
			
			};





			//  Submenus and menus options deletion :

			delete_option( $wp_tijusm_plugin_prefix_reception.'title' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'subtitle');
			delete_option( $wp_tijusm_plugin_prefix_reception.'flash_component_width');
			delete_option( $wp_tijusm_plugin_prefix_reception.'flash_component_height' );

			delete_option( $wp_tijusm_plugin_prefix_reception.'menus_vertical_position' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'menus_horizontal_position' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'movement_curvature_radius' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'submenus_vertical_position' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'submenus_horizontal_position' );

			delete_option( $wp_tijusm_plugin_prefix_reception.'menu_text_color' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'menu_background_color' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'menu_border_color' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'menu_index_text_color' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'menu_index_background_color' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'menu_index_border_color' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'vertical_menus_spacing' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'menus_rollover_color' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'menus_rollover_transparency' );

			delete_option( $wp_tijusm_plugin_prefix_reception.'submenu_text_color' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'submenu_background_color' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'submenu_border_color' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'vertical_submenus_spacing' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'horizontal_submenus_spacing' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'submenus_rollover_color' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'submenus_rollover_transparency' );


			// Decrementation of the settings group counter :

			$settings_group_counter = intval(get_option( $wp_tijusm_plugin_prefix.'settings_group_counter' ));

			//  Greater settings group ID storage : If user removed a settings group whose ID was the greatest of all ( for instance , 4 ) , then the new greatest ID 
			//  is the integer that precedes ( Therefore : 3 ) . 

			//  Where ( $wp_tijusm_settings_group_ID_reception > $settings_group_counter ) is not discussed because this case never met here .

			if ( $wp_tijusm_settings_group_ID_reception == $settings_group_counter ) { 

				//  In this case, using a function , we must seek first valid settings group ID ( that is to say, the first settings group with a plugin_prefix ) , 
				//  which is located below the one whose settings group ID equals to $settings_group_counter :

				update_option( 'wp_tijusm_first_valid_settings_group_ID', wp_tijusm_first_valid_settings_group_ID( $wp_tijusm_plugin_prefix_reception, $wp_tijusm_settings_group_ID_reception ) );

				if ( wp_tijusm_first_valid_settings_group_ID( $wp_tijusm_plugin_prefix_reception, $wp_tijusm_settings_group_ID_reception ) != 0 ) {

					update_option( $wp_tijusm_plugin_prefix_reception.'settings_group_counter', wp_tijusm_first_valid_settings_group_ID( $wp_tijusm_plugin_prefix_reception, $wp_tijusm_settings_group_ID_reception ) );				

				} else {

						delete_option( $wp_tijusm_plugin_prefix_reception.'settings_group_counter' );
						delete_option( $wp_tijusm_plugin_prefix_reception.'settings_group_ID' );
						delete_option( $wp_tijusm_plugin_prefix_reception.'sup_menus_counter' );
						delete_option( $wp_tijusm_plugin_prefix_reception.'sup_submenus_counter' );
						delete_option( 'wp_tijusm_plugin_group_counter' );
						delete_option( $wp_tijusm_plugin_prefix_reception ); 


				};
			};

			//  This instruction updates the indicator which determines if the gallery options must be totally deleted or only reset .
			//  In this case , its value is set to "true" in order to indicate that the gallery is to be deleted , thus the gallery options will be totally deleted .

			update_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_reception.'_settings_group_is_to_be_deleted', 'true' );

			delete_option( $wp_tijusm_plugin_prefix_reception.'settings_group_counter' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'settings_group_ID' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'sup_menus_counter' );
			delete_option( $wp_tijusm_plugin_prefix_reception.'sup_submenus_counter' );
			delete_option( 'wp_tijusm_plugin_group_counter' );
			delete_option( $wp_tijusm_plugin_prefix_reception ); 

			
			wp_tijusm_clear_directory_or_file('../wp-content/plugins/wp_smtiggerjumpingslidingmenu/component/movieclip_parameters'.$wp_tijusm_settings_group_ID_reception.'.xml');



			//  Redirection to admin page with appropriate message :

			header("Location: admin.php?page=wp_smtiggerjumpingslidingmenu.php&delete_settings_group=true&settings_group_ID_request=".$wp_tijusm_settings_group_ID_reception."&plugin_prefix_request=".$wp_tijusm_plugin_prefix_reception."");
			die; 

    			break;

	}  //  switch End

}  // if ( $_GET['page'] End






$admin_panel_title = "WP SM Tigger Jumping Sliding Menu";

//  add_menu_page($admin_panel_title, $admin_panel_title, 'administrator', basename(__FILE__), 'wp_tijusm_mytheme_admin');  //  This instruction allows to display the plugin options in a top level menu .
add_options_page($admin_panel_title, $admin_panel_title, 'administrator', basename(__FILE__), 'wp_tijusm_mytheme_admin');   //  This instruction allows to display the plugin options in a submenu of the SETTINGS menu .

}  //  wp_tijusm_mytheme_add_admin End

//  The function below allows to add the addresses of the plugin stylesheet and of a JQuery script , between the <HEAD> and </HEAD> tags :

function wp_tijusm_mytheme_add_init() {

	$file_dir=get_bloginfo('url')."/wp-content/plugins/wp_smtiggerjumpingslidingmenu";
	wp_enqueue_style("wp_tijusm_pluginStylesheet", $file_dir."/styles/styles.css", false, "1.0", "all");
	wp_enqueue_script("pluginJQueryScript", $file_dir."/scripts/script.js", false, "1.0");
}

//  The function below allows to display the different admin panel options and the infos messages appropriate to the user actions :

function wp_tijusm_mytheme_admin() {
 
	global $admin_panel_title, $wp_tijusm_flash_component_width, $wp_tijusm_flash_component_height, $wp_tijusm_plugin_prefix, $fullSizeImagesUploadDirectory, $thumbnailDirectory, $wp_tijusm_settings_group_ID, $wp_tijusm_settings_group_ID_request;

	$i=0;
	$image_width = 0;

	if ( $_REQUEST['settings_group_ID_saved'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>Settings group ID saved .</strong></p></div>'; };

	if ( $_REQUEST['title_saved'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>Title saved .</strong></p></div>'; };
	if ( $_REQUEST['subtitle_saved'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>Subtitle saved .</strong></p></div>'; };
	if ( $_REQUEST['flash_component_width_saved'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>Flash component width saved .</strong></p></div>'; };
	if ( $_REQUEST['flash_component_height_saved'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>Flash component height saved .</strong></p></div>'; };
	if ( $_REQUEST['menus_vertical_position_saved'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>Menus vertical position saved .</strong></p></div>'; };
	if ( $_REQUEST['menus_horizontal_position_saved'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>Menus horizontal position  saved .</strong></p></div>'; };
	if ( $_REQUEST['movement_curvature_radius_saved'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>Movement curvature radius saved .</strong></p></div>'; };
	if ( $_REQUEST['submenus_vertical_position_saved'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>Submenus vertical position saved .</strong></p></div>'; };
	if ( $_REQUEST['submenus_horizontal_position_saved'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>Submenus horizontal position saved .</strong></p></div>'; };
	
	if ( $_REQUEST['menu_text_color_saved'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>Menu text color saved .</strong></p></div>'; };
	if ( $_REQUEST['menu_background_color_saved'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>Menu background color saved .</strong></p></div>'; };
	if ( $_REQUEST['menu_border_color_saved'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>Menu border color saved .</strong></p></div>'; };
	if ( $_REQUEST['menu_index_text_color_saved'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>Menu index text color saved .</strong></p></div>'; };
	if ( $_REQUEST['menu_index_background_color_saved'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>Menu index background color saved .</strong></p></div>'; };
	if ( $_REQUEST['menu_index_border_color_saved'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>Menu index border color saved .</strong></p></div>'; };
	if ( $_REQUEST['vertical_menus_spacing_saved'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>Vertical menus spacing saved .</strong></p></div>'; };
	if ( $_REQUEST['menus_rollover_color_saved'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>Menus rollover color saved .</strong></p></div>'; };
	if ( $_REQUEST['menus_rollover_transparency_saved'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>Menus rollover transparency saved .</strong></p></div>'; };

	if ( $_REQUEST['submenu_text_color_saved'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>Submenu text color saved .</strong></p></div>'; };
	if ( $_REQUEST['submenu_background_color_saved'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>Submenu background color saved .</strong></p></div>'; };
	if ( $_REQUEST['submenu_border_color_saved'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>Submenu border color saved .</strong></p></div>'; };
	if ( $_REQUEST['vertical_submenus_spacing_saved'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>Vertical submenus spacing saved .</strong></p></div>'; };
	if ( $_REQUEST['horizontal_submenus_spacing_saved'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>Horizontal submenus spacing saved .</strong></p></div>'; };
	if ( $_REQUEST['submenus_rollover_color_saved'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>Submenus rollover color saved .</strong></p></div>'; };
	if ( $_REQUEST['submenus_rollover_transparency_saved'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>Submenus rollover transparency saved .</strong></p></div>'; };

	if ( $_REQUEST['new_menu_saved'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>New menu saved .</strong></p></div>'; };
	if ( $_REQUEST['menu_deleted'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>Menu deleted .</strong></p></div>'; };
	if ( $_REQUEST['empty_menu_title'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>Menu not saved : thank you to fill in the "Menu Title" field , please !</strong></p></div>'; };

	if ( $_REQUEST['new_submenu_saved'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>New submenu saved .</strong></p></div>'; };
	if ( $_REQUEST['submenu_deleted'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>Submenu deleted .</strong></p></div>'; };
	if ( $_REQUEST['empty_submenu_title_or_parent_menu_id'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>Submenu not saved : thank you to fill in the "Submenu Title" and the "Parent Menu ID" fields  , please !</strong></p></div>'; };

	if ( $_REQUEST['images_total_error'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong> You must not upload more than 10 images in a gallery to avoid images overlapping . </strong></p></div>'; };
	if ( $_REQUEST['all_full_size_image_deleted'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>All full size images deleted ( the associated thumbnail has been automatically deleted too ! ) . </strong></p></div>'; };

	if ( $_REQUEST['reset'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>'.$admin_panel_title.' settings reset .</strong></p></div>'; };
	if ( $_REQUEST['delete_settings_group'] ) { $wp_tijusm_plugin_prefix_request  = $_REQUEST['plugin_prefix_request']; $wp_tijusm_settings_group_ID_request  = $_REQUEST['settings_group_ID_request']; echo '<div id="message" class="updated fade"><p><strong>Settings group '.$wp_tijusm_settings_group_ID_request.' deleted . </strong></p></div>'; };




	//  $wp_tijusm_plugin_prefix CHECK and ASSIGNMENT	:
	//  -----------------------------------
	
	
	if ( get_option( $wp_tijusm_plugin_prefix_request.$wp_tijusm_settings_group_ID_request ) != "") { 

		$wp_tijusm_plugin_prefix  = get_option( $wp_tijusm_plugin_prefix_request.$wp_tijusm_settings_group_ID_request );
		echo "wp_tijusm_plugin_prefix_request.$wp_tijusm_settings_group_ID_request = ".$wp_tijusm_plugin_prefix_request.$wp_tijusm_settings_group_ID_request; 

	} else { 

		$settings_group_is_to_be_deleted = get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

		if ( $settings_group_is_to_be_deleted == 'false'  ) {

			update_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request, 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_');  //  This instruction is very useful to store the option as soon as the plugin runs ( and more precisely , before the user modify it )
			$wp_tijusm_plugin_prefix  = 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_';

		} else {
			
			delete_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

		};  //  Else 2 End 

	};  //  Else 1 End









//  Gestion de l'affichage côté BACKEND :
//  -----------------------------------

$admin_panel_title = "WP SM Tigger Jumping Sliding Menu";

echo '<div class="wrap container">'."\n";
echo '<h2>'.$admin_panel_title.' Settings</h2>'."\n";

echo '<div class="container_options">'."\n";

echo '<div class="subdivision">'."\n";
echo '<div class="title"><h3><img src="../component/images/trans.png" class="close" alt=""">Settings group</h3>'."\n";
echo '<div class="clear_both"></div></div>'."\n";
echo '<div class="options">'."\n";

if ( get_option( $wp_tijusm_plugin_prefix.'settings_group_ID' ) != "") { 

	$wp_tijusm_settings_group_ID  = get_option( $wp_tijusm_plugin_prefix.'settings_group_ID' ); 

} else { 

	$settings_group_is_to_be_deleted = get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	if ( $settings_group_is_to_be_deleted == 'false'  ) {

		update_option( $wp_tijusm_plugin_prefix.'settings_group_ID', '0' );  //  This instruction is very useful to store the option as soon as the plugin runs ( and more precisely , before the user modify it )	
		$wp_tijusm_settings_group_ID  = "0"; 

	} else {
			
		delete_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	};  //  Else 2 End 

};  //  Else 1 End

echo '<form action=admin.php?page=wp_smtiggerjumpingslidingmenu.php&action=save_settings_group_ID" method="post">'."\n";
echo '<div class="input text">'."\n";
echo '	<label for="'.'plugin_prefix_settings_group_ID'.'">Settings group identifier : <br /> ( Unique number <b class="red_color">> 0</b> )<br /> ( default ID is 0 )</label>'."\n";
echo ' 	<input name="'.'plugin_prefix_settings_group_ID'.'" id="'.'plugin_prefix_settings_group_ID'.'" type="text" value="'.$wp_tijusm_settings_group_ID.'" />'."\n";
echo ' <span class="submit"><input name="save_settings_group_ID" type="submit" value="Save the settings group ID" /></span>'."\n"; 
if ( $wp_tijusm_settings_group_ID ) { echo '	<small class="first_option">To display the sliding menu , in your post , with settings group Number '.$wp_tijusm_settings_group_ID.' options , copy the following code and paste it into your post :<br /><b class="red_color">[wp_tijusm'.$wp_tijusm_settings_group_ID.'][/wp_tijusm'.$wp_tijusm_settings_group_ID.']</b></small><div class="clear_both"></div>'."\n"; };
echo ' </div>'."\n";
echo '	<input type="hidden" name="action" value="save_settings_group_ID" />'."\n";
echo '</form>'."\n";









//  Title declaration :
//  ------------------

if ( get_option( $wp_tijusm_plugin_prefix.'title' ) != "") { 

	$title  = get_option( $wp_tijusm_plugin_prefix.'title' ); 

} else { 

	$settings_group_is_to_be_deleted = get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	if ( $settings_group_is_to_be_deleted == 'false'  ) {

		update_option( $wp_tijusm_plugin_prefix.'title','You can put your main title here' );  //  This instruction is very useful to store the option as soon as the plugin runs ( and more precisely , before the user modify it )	
		$title  = 'You can put your main title here'; 

	} else {
			
		delete_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	};  //  Else 2 End 

};  //  Else 1 End

echo '<form action="admin.php?page=wp_smtiggerjumpingslidingmenu.php&action=save_title&settings_group_ID_request='.$wp_tijusm_settings_group_ID_request.'&plugin_prefix_request='.$wp_tijusm_plugin_prefix.'" method="post">'."\n";
echo '<div class="input text">'."\n";
echo '	<label for="'.$wp_tijusm_plugin_prefix.'title'.'">Title : </label>'."\n";
echo ' 	<input name="'.$wp_tijusm_plugin_prefix.'title'.'" id="'.$wp_tijusm_plugin_prefix.'title'.'" type="text" value="'.$title.'" />'."\n";
echo ' <span class="submit"><input name="save_title" type="submit" value="Save the settings group title" /></span>'."\n";
 
echo ' </div>'."\n";
echo '	<input type="hidden" name="action" value="save_title" />'."\n";
echo '</form>'."\n";








//  Subtitle declaration :
//  ---------------------


if ( get_option( $wp_tijusm_plugin_prefix.'subtitle' ) != "") { 

	$subtitle  = get_option( $wp_tijusm_plugin_prefix.'subtitle' ); 

} else { 

	$settings_group_is_to_be_deleted = get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	if ( $settings_group_is_to_be_deleted == 'false'  ) {

		update_option( $wp_tijusm_plugin_prefix.'subtitle','You can put a subtitle here' );  //  This instruction is very useful to store the option as soon as the plugin runs ( and more precisely , before the user modify it )	
		$subtitle  = 'You can put a subtitle here'; 

	} else {
			
		delete_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	};  //  Else 2 End 

};  //  Else 1 End

echo '<form action="admin.php?page=wp_smtiggerjumpingslidingmenu.php&action=save_subtitle&settings_group_ID_request='.$wp_tijusm_settings_group_ID_request.'&plugin_prefix_request='.$wp_tijusm_plugin_prefix.'" method="post">'."\n";
echo '<div class="input text">'."\n";
echo '	<label for="'.$wp_tijusm_plugin_prefix.'subtitle'.'">Subtitle : </label>'."\n";
echo ' 	<input name="'.$wp_tijusm_plugin_prefix.'subtitle'.'" id="'.$wp_tijusm_plugin_prefix.'subtitle'.'" type="text" value="'.$subtitle.'" />'."\n";
echo ' <span class="submit"><input name="save_subtitle" type="submit" value="Save the settings group subtitle" /></span>'."\n";
 
echo ' </div>'."\n";
echo '	<input type="hidden" name="action" value="save_subtitle" />'."\n";
echo '</form>'."\n";







//  Flash component width declaration :
//  ---------------------------------


if ( get_option( $wp_tijusm_plugin_prefix.'flash_component_width' ) != "") { 

	$wp_tijusm_flash_component_width  = get_option( $wp_tijusm_plugin_prefix.'flash_component_width' ); 

} else { 

	$settings_group_is_to_be_deleted = get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	if ( $settings_group_is_to_be_deleted == 'false'  ) {

		update_option( $wp_tijusm_plugin_prefix.'flash_component_width','600' );  //  This instruction is very useful to store the option as soon as the plugin runs ( and more precisely , before the user modify it )	
		$wp_tijusm_flash_component_width  = '600'; 

	} else {
			
		delete_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	};  //  Else 2 End 

};  //  Else 1 End

echo '<form action="admin.php?page=wp_smtiggerjumpingslidingmenu.php&action=save_flash_component_width&settings_group_ID_request='.$wp_tijusm_settings_group_ID_request.'&plugin_prefix_request='.$wp_tijusm_plugin_prefix.'" method="post">'."\n";
echo '<div class="input text">'."\n";
echo '	<label for="'.$wp_tijusm_plugin_prefix.'flash_component_width'.'">Flash component width : <br /> ( default is 600 pixels )</label>'."\n";
echo ' 	<input name="'.$wp_tijusm_plugin_prefix.'flash_component_width'.'" id="'.$wp_tijusm_plugin_prefix.'flash_component_width'.'" type="text" value="'.$wp_tijusm_flash_component_width.'" />'."\n";
echo ' <span class="submit"><input name="save_flash_component_width" type="submit" value="Save the flash component width" /></span>'."\n";
 
echo ' </div>'."\n";
echo '	<input type="hidden" name="action" value="save_flash_component_width" />'."\n";
echo '</form>'."\n";






//  Flash component height declaration :
//  ----------------------------------



if ( get_option( $wp_tijusm_plugin_prefix.'flash_component_height' ) != "") { 

	$wp_tijusm_flash_component_height  = get_option( $wp_tijusm_plugin_prefix.'flash_component_height' ); 

} else { 

	$settings_group_is_to_be_deleted = get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	if ( $settings_group_is_to_be_deleted == 'false'  ) {

		update_option( $wp_tijusm_plugin_prefix.'flash_component_height','900' );  //  This instruction is very useful to store the option as soon as the plugin runs ( and more precisely , before the user modify it )	
		$wp_tijusm_flash_component_height  = '900'; 

	} else {
			
		delete_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	};  //  Else 2 End 

};  //  Else 1 End

echo '<form action="admin.php?page=wp_smtiggerjumpingslidingmenu.php&action=save_flash_component_height&settings_group_ID_request='.$wp_tijusm_settings_group_ID_request.'&plugin_prefix_request='.$wp_tijusm_plugin_prefix.'" method="post">'."\n";
echo '<div class="input text">'."\n";
echo '	<label for="'.$wp_tijusm_plugin_prefix.'flash_component_height'.'">Flash component height : <br /> ( default is 900 pixels )</label>'."\n";
echo ' 	<input name="'.$wp_tijusm_plugin_prefix.'flash_component_height'.'" id="'.$wp_tijusm_plugin_prefix.'flash_component_height'.'" type="text" value="'.$wp_tijusm_flash_component_height.'" />'."\n";
echo ' <span class="submit"><input name="save_flash_component_height" type="submit" value="Save the flash component height" /></span>'."\n";
 
echo ' </div>'."\n";
echo '	<input type="hidden" name="action" value="save_flash_component_height" />'."\n";
echo '</form>'."\n";







//  Menus vertical position :
//  -----------------------



if ( get_option( $wp_tijusm_plugin_prefix.'menus_vertical_position' ) != "") { 

	$menus_vertical_position  = get_option( $wp_tijusm_plugin_prefix.'menus_vertical_position' ); 

} else { 

	$settings_group_is_to_be_deleted = get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	if ( $settings_group_is_to_be_deleted == 'false'  ) {

		update_option( $wp_tijusm_plugin_prefix.'menus_vertical_position','300' );  //  This instruction is very useful to store the option as soon as the plugin runs ( and more precisely , before the user modify it )	
		$menus_vertical_position  = '300'; 

	} else {
			
		delete_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	};  //  Else 2 End 

};  //  Else 1 End

echo '<form action="admin.php?page=wp_smtiggerjumpingslidingmenu.php&action=save_menus_vertical_position&settings_group_ID_request='.$wp_tijusm_settings_group_ID_request.'&plugin_prefix_request='.$wp_tijusm_plugin_prefix.'" method="post">'."\n";
echo '<div class="input text">'."\n";
echo '	<label for="'.$wp_tijusm_plugin_prefix.'menus_vertical_position'.'">Menus vertical position : <br /> ( default is 300 pixels )</label>'."\n";
echo ' 	<input name="'.$wp_tijusm_plugin_prefix.'menus_vertical_position'.'" id="'.$wp_tijusm_plugin_prefix.'menus_vertical_position'.'" type="text" value="'.$menus_vertical_position.'" />'."\n";
echo ' <span class="submit"><input name="save_menus_vertical_position" type="submit" value="Save the menus vertical position" /></span>'."\n";
 
echo ' </div>'."\n";
echo '	<input type="hidden" name="action" value="save_menus_vertical_position" />'."\n";
echo '</form>'."\n";













//  Menus horizontal position :
//  -------------------------



if ( get_option( $wp_tijusm_plugin_prefix.'menus_horizontal_position' ) != "") { 

	$menus_horizontal_position  = get_option( $wp_tijusm_plugin_prefix.'menus_horizontal_position' ); 

} else { 

	$settings_group_is_to_be_deleted = get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	if ( $settings_group_is_to_be_deleted == 'false'  ) {

		update_option( $wp_tijusm_plugin_prefix.'menus_horizontal_position','150' );  //  This instruction is very useful to store the option as soon as the plugin runs ( and more precisely , before the user modify it )	
		$menus_horizontal_position  = '150'; 

	} else {
			
		delete_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	};  //  Else 2 End 

};  //  Else 1 End

echo '<form action="admin.php?page=wp_smtiggerjumpingslidingmenu.php&action=save_menus_horizontal_position&settings_group_ID_request='.$wp_tijusm_settings_group_ID_request.'&plugin_prefix_request='.$wp_tijusm_plugin_prefix.'" method="post">'."\n";
echo '<div class="input text">'."\n";
echo '	<label for="'.$wp_tijusm_plugin_prefix.'menus_horizontal_position'.'">Menus horizontal position : <br /> ( default is 150 pixels )</label>'."\n";
echo ' 	<input name="'.$wp_tijusm_plugin_prefix.'menus_horizontal_position'.'" id="'.$wp_tijusm_plugin_prefix.'menus_horizontal_position'.'" type="text" value="'.$menus_horizontal_position.'" />'."\n";
echo ' <span class="submit"><input name="save_menus_horizontal_position" type="submit" value="Save the menus horizontal position" /></span>'."\n";
 
echo ' </div>'."\n";
echo '	<input type="hidden" name="action" value="save_menus_horizontal_position" />'."\n";
echo '</form>'."\n";








//  Movement curvature radius :
//  --------------------



if ( get_option( $wp_tijusm_plugin_prefix.'movement_curvature_radius' ) != "") { 

	$movement_curvature_radius  = get_option( $wp_tijusm_plugin_prefix.'movement_curvature_radius' ); 

} else { 

	$settings_group_is_to_be_deleted = get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	if ( $settings_group_is_to_be_deleted == 'false'  ) {

		update_option( $wp_tijusm_plugin_prefix.'movement_curvature_radius','150' );  //  This instruction is very useful to store the option as soon as the plugin runs ( and more precisely , before the user modify it )	
		$movement_curvature_radius  = '150'; 

	} else {
			
		delete_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	};  //  Else 2 End 

};  //  Else 1 End

echo '<form action="admin.php?page=wp_smtiggerjumpingslidingmenu.php&action=save_movement_curvature_radius&settings_group_ID_request='.$wp_tijusm_settings_group_ID_request.'&plugin_prefix_request='.$wp_tijusm_plugin_prefix.'" method="post">'."\n";
echo '<div class="input text">'."\n";
echo '	<label for="'.$wp_tijusm_plugin_prefix.'movement_curvature_radius'.'">Movement curvature radius : <br /> ( default is 150 pixels )</label>'."\n";
echo ' 	<input name="'.$wp_tijusm_plugin_prefix.'movement_curvature_radius'.'" id="'.$wp_tijusm_plugin_prefix.'movement_curvature_radius'.'" type="text" value="'.$movement_curvature_radius.'" />'."\n";
echo ' <span class="submit"><input name="save_movement_curvature_radius" type="submit" value="Save the movement curvature radius" /></span>'."\n";
 
echo ' </div>'."\n";
echo '	<input type="hidden" name="action" value="save_movement_curvature_radius" />'."\n";
echo '</form>'."\n";









//  Submenus vertical position :
//  --------------------------



if ( get_option( $wp_tijusm_plugin_prefix.'submenus_vertical_position' ) != "") { 

	$submenus_vertical_position  = get_option( $wp_tijusm_plugin_prefix.'submenus_vertical_position' ); 

} else { 

	$settings_group_is_to_be_deleted = get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	if ( $settings_group_is_to_be_deleted == 'false'  ) {

		update_option( $wp_tijusm_plugin_prefix.'submenus_vertical_position','125' );  //  This instruction is very useful to store the option as soon as the plugin runs ( and more precisely , before the user modify it )	
		$submenus_vertical_position  = '125'; 

	} else {
			
		delete_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	};  //  Else 2 End 

};  //  Else 1 End

echo '<form action="admin.php?page=wp_smtiggerjumpingslidingmenu.php&action=save_submenus_vertical_position&settings_group_ID_request='.$wp_tijusm_settings_group_ID_request.'&plugin_prefix_request='.$wp_tijusm_plugin_prefix.'" method="post">'."\n";
echo '<div class="input text">'."\n";
echo '	<label for="'.$wp_tijusm_plugin_prefix.'submenus_vertical_position'.'">submenus vertical position : <br /> ( default is 125 pixels )</label>'."\n";
echo ' 	<input name="'.$wp_tijusm_plugin_prefix.'submenus_vertical_position'.'" id="'.$wp_tijusm_plugin_prefix.'submenus_vertical_position'.'" type="text" value="'.$submenus_vertical_position.'" />'."\n";
echo ' <span class="submit"><input name="save_submenus_vertical_position" type="submit" value="Save the submenus vertical position" /></span>'."\n";
 
echo ' </div>'."\n";
echo '	<input type="hidden" name="action" value="save_submenus_vertical_position" />'."\n";
echo '</form>'."\n";






//  Submenus horizontal position :
//  ----------------------------



if ( get_option( $wp_tijusm_plugin_prefix.'submenus_horizontal_position' ) != "") { 

	$submenus_horizontal_position  = get_option( $wp_tijusm_plugin_prefix.'submenus_horizontal_position' ); 

} else { 

	$settings_group_is_to_be_deleted = get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	if ( $settings_group_is_to_be_deleted == 'false'  ) {

		update_option( $wp_tijusm_plugin_prefix.'submenus_horizontal_position','400' );  //  This instruction is very useful to store the option as soon as the plugin runs ( and more precisely , before the user modify it )	
		$submenus_horizontal_position  = '400'; 

	} else {
			
		delete_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	};  //  Else 2 End 

};  //  Else 1 End

echo '<form action="admin.php?page=wp_smtiggerjumpingslidingmenu.php&action=save_submenus_horizontal_position&settings_group_ID_request='.$wp_tijusm_settings_group_ID_request.'&plugin_prefix_request='.$wp_tijusm_plugin_prefix.'" method="post">'."\n";
echo '<div class="input text">'."\n";
echo '	<label for="'.$wp_tijusm_plugin_prefix.'submenus_horizontal_position'.'">submenus horizontal position : <br /> ( default is 400 pixels )</label>'."\n";
echo ' 	<input name="'.$wp_tijusm_plugin_prefix.'submenus_horizontal_position'.'" id="'.$wp_tijusm_plugin_prefix.'submenus_horizontal_position'.'" type="text" value="'.$submenus_horizontal_position.'" />'."\n";
echo ' <span class="submit"><input name="save_submenus_horizontal_position" type="submit" value="Save the submenus horizontal position" /></span>'."\n";
 
echo ' </div>'."\n";
echo '	<input type="hidden" name="action" value="save_submenus_horizontal_position" />'."\n";
echo '</form>'."\n";











echo '<div style="position: relative; margin-bottom: 50px;">'."\n";

echo '<form action="admin.php?page=wp_smtiggerjumpingslidingmenu.php&action=reset&settings_group_ID_request='.$wp_tijusm_settings_group_ID_request.'&plugin_prefix_request='.$wp_tijusm_plugin_prefix.'" method="post">'."\n";
echo '	<p class="input_left" >'."\n";
echo '		<input name="reset" type="submit" value="Reset all options !" />'."\n";
echo '		<input type="hidden" name="action" value="reset" />'."\n";
echo '	</p>'."\n";
echo '</form>'."\n";

echo '<form action="admin.php?page=wp_smtiggerjumpingslidingmenu.php&action=delete_settings_group&settings_group_ID_request='.$wp_tijusm_settings_group_ID_request.'&plugin_prefix_request='.$wp_tijusm_plugin_prefix.'" method="post">'."\n";
echo '	<p class="input_right" >'."\n";
echo '		<input name="delete_settings_group" type="submit" value="Delete this settings group !" />'."\n";
echo '		<input type="hidden" name="action" value="delete_settings_group" />'."\n";
echo '	</p>'."\n";
echo '</form>'."\n";

echo '</div>'."\n";
echo '</div>'."\n";
echo '</div>'."\n";
echo '<br />'."\n";









//  MENUS Management :
//  -----------------


echo '<div class="subdivision">'."\n";
echo '<div class="title"><h3><img src="../component/images/trans.png" class="close" alt=""">Menus settings</h3>'."\n";
echo '<div class="clear_both"></div>'."\n";
echo '</div>'."\n";
echo '<div class="options">'."\n";


//  A RETIRER $remaining_menus = 10 - intval(get_option( $wp_tijusm_plugin_prefix.'compteur_menus' ));





//  Menu text color :
//  ----------------



if ( get_option( $wp_tijusm_plugin_prefix.'menu_text_color' ) != "") { 

	$menu_text_color  = get_option( $wp_tijusm_plugin_prefix.'menu_text_color' ); 

} else { 

	$settings_group_is_to_be_deleted = get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	if ( $settings_group_is_to_be_deleted == 'false'  ) {

		update_option( $wp_tijusm_plugin_prefix.'menu_text_color','ffffff' );  //  This instruction is very useful to store the option as soon as the plugin runs ( and more precisely , before the user modify it )	
		$menu_text_color  = 'ffffff'; 

	} else {
			
		delete_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	};  //  Else 2 End 

};  //  Else 1 End

echo '<form action="admin.php?page=wp_smtiggerjumpingslidingmenu.php&action=save_menu_text_color&settings_group_ID_request='.$wp_tijusm_settings_group_ID_request.'&plugin_prefix_request='.$wp_tijusm_plugin_prefix.'" method="post">'."\n";
echo '<div class="input text">'."\n";
echo '	<label for="'.$wp_tijusm_plugin_prefix.'menu_text_color'.'">Menu text color : <br /> ( default is ffffff )</label>'."\n";
echo ' 	<input name="'.$wp_tijusm_plugin_prefix.'menu_text_color'.'" id="'.$wp_tijusm_plugin_prefix.'menu_text_color'.'" type="text" value="'.$menu_text_color.'" />'."\n";
echo ' <span class="submit"><input name="save_menu_text_color" type="submit" value="Save the menu text color" /></span>'."\n";
 
echo ' </div>'."\n";
echo '	<input type="hidden" name="action" value="save_menu_text_color" />'."\n";
echo '</form>'."\n";








//  Menu background color :
//  -----------------------



if ( get_option( $wp_tijusm_plugin_prefix.'menu_background_color' ) != "") { 

	$menu_background_color  = get_option( $wp_tijusm_plugin_prefix.'menu_background_color' ); 

} else { 

	$settings_group_is_to_be_deleted = get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	if ( $settings_group_is_to_be_deleted == 'false'  ) {

		update_option( $wp_tijusm_plugin_prefix.'menu_background_color','990033' );  //  This instruction is very useful to store the option as soon as the plugin runs ( and more precisely , before the user modify it )	
		$menu_background_color  = '990033'; 

	} else {
			
		delete_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	};  //  Else 2 End 

};  //  Else 1 End

echo '<form action="admin.php?page=wp_smtiggerjumpingslidingmenu.php&action=save_menu_background_color&settings_group_ID_request='.$wp_tijusm_settings_group_ID_request.'&plugin_prefix_request='.$wp_tijusm_plugin_prefix.'" method="post">'."\n";
echo '<div class="input text">'."\n";
echo '	<label for="'.$wp_tijusm_plugin_prefix.'menu_background_color'.'">Menu background color : <br /> ( default is 990033 )</label>'."\n";
echo ' 	<input name="'.$wp_tijusm_plugin_prefix.'menu_background_color'.'" id="'.$wp_tijusm_plugin_prefix.'menu_background_color'.'" type="text" value="'.$menu_background_color.'" />'."\n";
echo ' <span class="submit"><input name="save_menu_background_color" type="submit" value="Save the menu background color" /></span>'."\n";
 
echo ' </div>'."\n";
echo '	<input type="hidden" name="action" value="save_menu_background_color" />'."\n";
echo '</form>'."\n";








//  Menu border color :
//  ------------------



if ( get_option( $wp_tijusm_plugin_prefix.'menu_border_color' ) != "") { 

	$menu_border_color  = get_option( $wp_tijusm_plugin_prefix.'menu_border_color' ); 

} else { 

	$settings_group_is_to_be_deleted = get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	if ( $settings_group_is_to_be_deleted == 'false'  ) {

		update_option( $wp_tijusm_plugin_prefix.'menu_border_color','000000' );  //  This instruction is very useful to store the option as soon as the plugin runs ( and more precisely , before the user modify it )	
		$menu_border_color  = '000000'; 

	} else {
			
		delete_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	};  //  Else 2 End 

};  //  Else 1 End

echo '<form action="admin.php?page=wp_smtiggerjumpingslidingmenu.php&action=save_menu_border_color&settings_group_ID_request='.$wp_tijusm_settings_group_ID_request.'&plugin_prefix_request='.$wp_tijusm_plugin_prefix.'" method="post">'."\n";
echo '<div class="input text">'."\n";
echo '	<label for="'.$wp_tijusm_plugin_prefix.'menu_border_color'.'">Menu border color : <br /> ( default is 000000 )</label>'."\n";
echo ' 	<input name="'.$wp_tijusm_plugin_prefix.'menu_border_color'.'" id="'.$wp_tijusm_plugin_prefix.'menu_border_color'.'" type="text" value="'.$menu_border_color.'" />'."\n";
echo ' <span class="submit"><input name="save_menu_border_color" type="submit" value="Save the menu border color" /></span>'."\n";

echo ' </div>'."\n";
echo '	<input type="hidden" name="action" value="save_menu_border_color" />'."\n";
echo '</form>'."\n";






//  Menu index text color :
//  ---------------------



if ( get_option( $wp_tijusm_plugin_prefix.'menu_index_text_color' ) != "") { 

	$menu_index_text_color  = get_option( $wp_tijusm_plugin_prefix.'menu_index_text_color' ); 

} else { 

	$settings_group_is_to_be_deleted = get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	if ( $settings_group_is_to_be_deleted == 'false'  ) {

		update_option( $wp_tijusm_plugin_prefix.'menu_index_text_color','ffffff' );  //  This instruction is very useful to store the option as soon as the plugin runs ( and more precisely , before the user modify it )	
		$menu_index_text_color  = 'ffffff'; 

	} else {
			
		delete_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	};  //  Else 2 End 

};  //  Else 1 End

echo '<form action="admin.php?page=wp_smtiggerjumpingslidingmenu.php&action=save_menu_index_text_color&settings_group_ID_request='.$wp_tijusm_settings_group_ID_request.'&plugin_prefix_request='.$wp_tijusm_plugin_prefix.'" method="post">'."\n";
echo '<div class="input text">'."\n";
echo '	<label for="'.$wp_tijusm_plugin_prefix.'menu_index_text_color'.'">Menu index text color : <br /> ( default is ffffff )</label>'."\n";
echo ' 	<input name="'.$wp_tijusm_plugin_prefix.'menu_index_text_color'.'" id="'.$wp_tijusm_plugin_prefix.'menu_index_text_color'.'" type="text" value="'.$menu_index_text_color.'" />'."\n";
echo ' <span class="submit"><input name="save_menu_index_text_color" type="submit" value="Save the menu index text color" /></span>'."\n";

echo ' </div>'."\n";
echo '	<input type="hidden" name="action" value="save_menu_index_text_color" />'."\n";
echo '</form>'."\n";








//  Menu index background color :
//  ---------------------------



if ( get_option( $wp_tijusm_plugin_prefix.'menu_index_background_color' ) != "") { 

	$menu_index_background_color  = get_option( $wp_tijusm_plugin_prefix.'menu_index_background_color' ); 

} else { 

	$settings_group_is_to_be_deleted = get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	if ( $settings_group_is_to_be_deleted == 'false'  ) {

		update_option( $wp_tijusm_plugin_prefix.'menu_index_background_color','000000' );  //  This instruction is very useful to store the option as soon as the plugin runs ( and more precisely , before the user modify it )	
		$menu_index_background_color  = '000000'; 

	} else {
			
		delete_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	};  //  Else 2 End 

};  //  Else 1 End

echo '<form action="admin.php?page=wp_smtiggerjumpingslidingmenu.php&action=save_menu_index_background_color&settings_group_ID_request='.$wp_tijusm_settings_group_ID_request.'&plugin_prefix_request='.$wp_tijusm_plugin_prefix.'" method="post">'."\n";
echo '<div class="input text">'."\n";
echo '	<label for="'.$wp_tijusm_plugin_prefix.'menu_index_background_color'.'">Menu index background color : <br /> ( default is 000000 )</label>'."\n";
echo ' 	<input name="'.$wp_tijusm_plugin_prefix.'menu_index_background_color'.'" id="'.$wp_tijusm_plugin_prefix.'menu_index_background_color'.'" type="text" value="'.$menu_index_background_color.'" />'."\n";
echo ' <span class="submit"><input name="save_menu_index_background_color" type="submit" value="Save the menu index background color" /></span>'."\n";

echo ' </div>'."\n";
echo '	<input type="hidden" name="action" value="save_menu_index_background_color" />'."\n";
echo '</form>'."\n";








//  Menu index border color :
//  ------------------------



if ( get_option( $wp_tijusm_plugin_prefix.'menu_index_border_color' ) != "") { 

	$menu_index_border_color  = get_option( $wp_tijusm_plugin_prefix.'menu_index_border_color' ); 

} else { 

	$settings_group_is_to_be_deleted = get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	if ( $settings_group_is_to_be_deleted == 'false'  ) {

		update_option( $wp_tijusm_plugin_prefix.'menu_index_border_color','990033' );  //  This instruction is very useful to store the option as soon as the plugin runs ( and more precisely , before the user modify it )	
		$menu_index_border_color  = '990033'; 

	} else {
			
		delete_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	};  //  Else 2 End 

};  //  Else 1 End

echo '<form action="admin.php?page=wp_smtiggerjumpingslidingmenu.php&action=save_menu_index_border_color&settings_group_ID_request='.$wp_tijusm_settings_group_ID_request.'&plugin_prefix_request='.$wp_tijusm_plugin_prefix.'" method="post">'."\n";
echo '<div class="input text">'."\n";
echo '	<label for="'.$wp_tijusm_plugin_prefix.'menu_index_border_color'.'">Menu index border color : <br /> ( default is 990033 )</label>'."\n";
echo ' 	<input name="'.$wp_tijusm_plugin_prefix.'menu_index_border_color'.'" id="'.$wp_tijusm_plugin_prefix.'menu_index_border_color'.'" type="text" value="'.$menu_index_border_color.'" />'."\n";
echo ' <span class="submit"><input name="save_menu_index_border_color" type="submit" value="Save the menu index border color" /></span>'."\n";

echo ' </div>'."\n";
echo '	<input type="hidden" name="action" value="save_menu_index_border_color" />'."\n";
echo '</form>'."\n";







//  Vertical menus spacing :
//  ----------------------



if ( get_option( $wp_tijusm_plugin_prefix.'vertical_menus_spacing' ) != "") { 

	$vertical_menus_spacing  = get_option( $wp_tijusm_plugin_prefix.'vertical_menus_spacing' ); 

} else { 

	$settings_group_is_to_be_deleted = get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	if ( $settings_group_is_to_be_deleted == 'false'  ) {

		update_option( $wp_tijusm_plugin_prefix.'vertical_menus_spacing','10' );  //  This instruction is very useful to store the option as soon as the plugin runs ( and more precisely , before the user modify it )	
		$vertical_menus_spacing  = '10';

	} else {
			
		delete_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	};  //  Else 2 End 

};  //  Else 1 End

echo '<form action="admin.php?page=wp_smtiggerjumpingslidingmenu.php&action=save_vertical_menus_spacing&settings_group_ID_request='.$wp_tijusm_settings_group_ID_request.'&plugin_prefix_request='.$wp_tijusm_plugin_prefix.'" method="post">'."\n";
echo '<div class="input text">'."\n";
echo '	<label for="'.$wp_tijusm_plugin_prefix.'vertical_menus_spacing'.'">Vertical menus spacing : <br /> ( default is 10 pixels )</label>'."\n";
echo ' 	<input name="'.$wp_tijusm_plugin_prefix.'vertical_menus_spacing'.'" id="'.$wp_tijusm_plugin_prefix.'vertical_menus_spacing'.'" type="text" value="'.$vertical_menus_spacing.'" />'."\n";
echo ' <span class="submit"><input name="save_vertical_menus_spacing" type="submit" value="Save the vertical menus spacing" /></span>'."\n";

echo ' </div>'."\n";
echo '	<input type="hidden" name="action" value="save_vertical_menus_spacing" />'."\n";
echo '</form>'."\n";







//  Menus rollover color :
//  --------------------



if ( get_option( $wp_tijusm_plugin_prefix.'menus_rollover_color' ) != "") { 

	$menus_rollover_color  = get_option( $wp_tijusm_plugin_prefix.'menus_rollover_color' ); 

} else { 

	$settings_group_is_to_be_deleted = get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	if ( $settings_group_is_to_be_deleted == 'false'  ) {

		update_option( $wp_tijusm_plugin_prefix.'menus_rollover_color','ffffff' );  //  This instruction is very useful to store the option as soon as the plugin runs ( and more precisely , before the user modify it )	
		$menus_rollover_color  = 'ffffff';

	} else {
			
		delete_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	};  //  Else 2 End 

};  //  Else 1 End

echo '<form action="admin.php?page=wp_smtiggerjumpingslidingmenu.php&action=save_menus_rollover_color&settings_group_ID_request='.$wp_tijusm_settings_group_ID_request.'&plugin_prefix_request='.$wp_tijusm_plugin_prefix.'" method="post">'."\n";
echo '<div class="input text">'."\n";
echo '	<label for="'.$wp_tijusm_plugin_prefix.'menus_rollover_color'.'">Menus rollover color : <br /> ( default is ffffff )</label>'."\n";
echo ' 	<input name="'.$wp_tijusm_plugin_prefix.'menus_rollover_color'.'" id="'.$wp_tijusm_plugin_prefix.'menus_rollover_color'.'" type="text" value="'.$menus_rollover_color.'" />'."\n";
echo ' <span class="submit"><input name="save_menus_rollover_color" type="submit" value="Save the menus rollover color" /></span>'."\n";

echo ' </div>'."\n";
echo '	<input type="hidden" name="action" value="save_menus_rollover_color" />'."\n";
echo '</form>'."\n";







//  Menus rollover transparency :
//  ---------------------------



if ( get_option( $wp_tijusm_plugin_prefix.'menus_rollover_transparency' ) != "") { 

	$menus_rollover_transparency  = get_option( $wp_tijusm_plugin_prefix.'menus_rollover_transparency' ); 

} else { 

	$settings_group_is_to_be_deleted = get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	if ( $settings_group_is_to_be_deleted == 'false'  ) {

		update_option( $wp_tijusm_plugin_prefix.'menus_rollover_transparency','50' );  //  This instruction is very useful to store the option as soon as the plugin runs ( and more precisely , before the user modify it )	
		$menus_rollover_transparency  = '50';

	} else {
			
		delete_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	};  //  Else 2 End 

};  //  Else 1 End

echo '<form action="admin.php?page=wp_smtiggerjumpingslidingmenu.php&action=save_menus_rollover_transparency&settings_group_ID_request='.$wp_tijusm_settings_group_ID_request.'&plugin_prefix_request='.$wp_tijusm_plugin_prefix.'" method="post">'."\n";
echo '<div class="input text">'."\n";
echo '	<label for="'.$wp_tijusm_plugin_prefix.'menus_rollover_transparency'.'">Menus rollover transparency : <br /> ( default is 50 , unit is % )</label>'."\n";
echo ' 	<input name="'.$wp_tijusm_plugin_prefix.'menus_rollover_transparency'.'" id="'.$wp_tijusm_plugin_prefix.'menus_rollover_transparency'.'" type="text" value="'.$menus_rollover_transparency.'" />'."\n";
echo ' <span class="submit"><input name="save_menus_rollover_transparency" type="submit" value="Save the menus rollover transparency" /></span>'."\n";

echo ' </div>'."\n";
echo '	<input type="hidden" name="action" value="save_menus_rollover_transparency" />'."\n";
echo '</form>'."\n";





 
echo '</div>'."\n";
echo '</div>'."\n";
echo '<br />'."\n";






//  SUBMENUS Management :
//  -------------------


echo '<div class="subdivision">'."\n";
echo '<div class="title"><h3><img src="../component/images/trans.png" class="close" alt=""">Submenus settings</h3>'."\n";
echo '<div class="clear_both"></div></div>'."\n";
echo '<div class="options">'."\n";





//  Submenu text color :
//  ------------------



if ( get_option( $wp_tijusm_plugin_prefix.'submenu_text_color' ) != "") { 

	$submenu_text_color  = get_option( $wp_tijusm_plugin_prefix.'submenu_text_color' ); 

} else { 

	$settings_group_is_to_be_deleted = get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	if ( $settings_group_is_to_be_deleted == 'false'  ) {

		update_option( $wp_tijusm_plugin_prefix.'submenu_text_color','000000' );  //  This instruction is very useful to store the option as soon as the plugin runs ( and more precisely , before the user modify it )	
		$submenu_text_color  = '000000';

	} else {
			
		delete_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	};  //  Else 2 End 

};  //  Else 1 End

echo '<form action="admin.php?page=wp_smtiggerjumpingslidingmenu.php&action=save_submenu_text_color&settings_group_ID_request='.$wp_tijusm_settings_group_ID_request.'&plugin_prefix_request='.$wp_tijusm_plugin_prefix.'" method="post">'."\n";
echo '<div class="input text">'."\n";
echo '	<label for="'.$wp_tijusm_plugin_prefix.'submenu_text_color'.'">Submenu text color : <br /> ( default is 000000 )</label>'."\n";
echo ' 	<input name="'.$wp_tijusm_plugin_prefix.'submenu_text_color'.'" id="'.$wp_tijusm_plugin_prefix.'submenu_text_color'.'" type="text" value="'.$submenu_text_color.'" />'."\n";
echo ' <span class="submit"><input name="save_submenu_text_color" type="submit" value="Save the submenu text color" /></span>'."\n";

echo ' </div>'."\n";
echo '	<input type="hidden" name="action" value="save_submenu_text_color" />'."\n";
echo '</form>'."\n";






//  Submenu background color :
//  ------------------------



if ( get_option( $wp_tijusm_plugin_prefix.'submenu_background_color' ) != "") { 

	$submenu_background_color  = get_option( $wp_tijusm_plugin_prefix.'submenu_background_color' ); 

} else { 

	$settings_group_is_to_be_deleted = get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	if ( $settings_group_is_to_be_deleted == 'false'  ) {

		update_option( $wp_tijusm_plugin_prefix.'submenu_background_color','ffffff' );  //  This instruction is very useful to store the option as soon as the plugin runs ( and more precisely , before the user modify it )	
		$submenu_background_color  = 'ffffff';

	} else {
			
		delete_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	};  //  Else 2 End 

};  //  Else 1 End

echo '<form action="admin.php?page=wp_smtiggerjumpingslidingmenu.php&action=save_submenu_background_color&settings_group_ID_request='.$wp_tijusm_settings_group_ID_request.'&plugin_prefix_request='.$wp_tijusm_plugin_prefix.'" method="post">'."\n";
echo '<div class="input text">'."\n";
echo '	<label for="'.$wp_tijusm_plugin_prefix.'submenu_background_color'.'">Submenu background color : <br /> ( default is ffffff )</label>'."\n";
echo ' 	<input name="'.$wp_tijusm_plugin_prefix.'submenu_background_color'.'" id="'.$wp_tijusm_plugin_prefix.'submenu_background_color'.'" type="text" value="'.$submenu_background_color.'" />'."\n";
echo ' <span class="submit"><input name="save_submenu_background_color" type="submit" value="Save the submenu background color" /></span>'."\n";

echo ' </div>'."\n";
echo '	<input type="hidden" name="action" value="save_submenu_background_color" />'."\n";
echo '</form>'."\n";







//  Submenu border color :
//  --------------------



if ( get_option( $wp_tijusm_plugin_prefix.'submenu_border_color' ) != "") { 

	$submenu_border_color  = get_option( $wp_tijusm_plugin_prefix.'submenu_border_color' ); 

} else { 

	$settings_group_is_to_be_deleted = get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	if ( $settings_group_is_to_be_deleted == 'false'  ) {

		update_option( $wp_tijusm_plugin_prefix.'submenu_border_color','000000' );  //  This instruction is very useful to store the option as soon as the plugin runs ( and more precisely , before the user modify it )	
		$submenu_border_color  = '000000';

	} else {
			
		delete_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	};  //  Else 2 End 

};  //  Else 1 End

echo '<form action="admin.php?page=wp_smtiggerjumpingslidingmenu.php&action=save_submenu_border_color&settings_group_ID_request='.$wp_tijusm_settings_group_ID_request.'&plugin_prefix_request='.$wp_tijusm_plugin_prefix.'" method="post">'."\n";
echo '<div class="input text">'."\n";
echo '	<label for="'.$wp_tijusm_plugin_prefix.'submenu_border_color'.'">Submenu border color : <br /> ( default is 000000 )</label>'."\n";
echo ' 	<input name="'.$wp_tijusm_plugin_prefix.'submenu_border_color'.'" id="'.$wp_tijusm_plugin_prefix.'submenu_border_color'.'" type="text" value="'.$submenu_border_color.'" />'."\n";
echo ' <span class="submit"><input name="save_submenu_border_color" type="submit" value="Save the submenu border color" /></span>'."\n";

echo ' </div>'."\n";
echo '	<input type="hidden" name="action" value="save_submenu_border_color" />'."\n";
echo '</form>'."\n";






//  Vertical submenus spacing :
//  -------------------------



if ( get_option( $wp_tijusm_plugin_prefix.'vertical_submenus_spacing' ) != "") { 

	$vertical_submenus_spacing  = get_option( $wp_tijusm_plugin_prefix.'vertical_submenus_spacing' ); 

} else { 

	$settings_group_is_to_be_deleted = get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	if ( $settings_group_is_to_be_deleted == 'false'  ) {

		update_option( $wp_tijusm_plugin_prefix.'vertical_submenus_spacing','15' );  //  This instruction is very useful to store the option as soon as the plugin runs ( and more precisely , before the user modify it )	
		$vertical_submenus_spacing  = '15';

	} else {
			
		delete_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	};  //  Else 2 End 

};  //  Else 1 End

echo '<form action="admin.php?page=wp_smtiggerjumpingslidingmenu.php&action=save_vertical_submenus_spacing&settings_group_ID_request='.$wp_tijusm_settings_group_ID_request.'&plugin_prefix_request='.$wp_tijusm_plugin_prefix.'" method="post">'."\n";
echo '<div class="input text">'."\n";
echo '	<label for="'.$wp_tijusm_plugin_prefix.'vertical_submenus_spacing'.'">Vertical submenus spacing : <br /> ( default is 15 pixels )</label>'."\n";
echo ' 	<input name="'.$wp_tijusm_plugin_prefix.'vertical_submenus_spacing'.'" id="'.$wp_tijusm_plugin_prefix.'vertical_submenus_spacing'.'" type="text" value="'.$vertical_submenus_spacing.'" />'."\n";
echo ' <span class="submit"><input name="save_vertical_submenus_spacing" type="submit" value="Save the vertical submenus spacing" /></span>'."\n";

echo ' </div>'."\n";
echo '	<input type="hidden" name="action" value="save_vertical_submenus_spacing" />'."\n";
echo '</form>'."\n";







//  Horizontal submenus spacing :
//  -------------------------



if ( get_option( $wp_tijusm_plugin_prefix.'horizontal_submenus_spacing' ) != "") { 

	$horizontal_submenus_spacing  = get_option( $wp_tijusm_plugin_prefix.'horizontal_submenus_spacing' ); 

} else { 

	$settings_group_is_to_be_deleted = get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	if ( $settings_group_is_to_be_deleted == 'false'  ) {

		update_option( $wp_tijusm_plugin_prefix.'horizontal_submenus_spacing','5' );  //  This instruction is very useful to store the option as soon as the plugin runs ( and more precisely , before the user modify it )	
		$horizontal_submenus_spacing  = '5';

	} else {
			
		delete_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	};  //  Else 2 End 

};  //  Else 1 End

echo '<form action="admin.php?page=wp_smtiggerjumpingslidingmenu.php&action=save_horizontal_submenus_spacing&settings_group_ID_request='.$wp_tijusm_settings_group_ID_request.'&plugin_prefix_request='.$wp_tijusm_plugin_prefix.'" method="post">'."\n";
echo '<div class="input text">'."\n";
echo '	<label for="'.$wp_tijusm_plugin_prefix.'horizontal_submenus_spacing'.'">Horizontal submenus spacing : <br /> ( default is 5 pixels )</label>'."\n";
echo ' 	<input name="'.$wp_tijusm_plugin_prefix.'horizontal_submenus_spacing'.'" id="'.$wp_tijusm_plugin_prefix.'horizontal_submenus_spacing'.'" type="text" value="'.$horizontal_submenus_spacing.'" />'."\n";
echo ' <span class="submit"><input name="save_horizontal_submenus_spacing" type="submit" value="Save the horizontal submenus spacing" /></span>'."\n";

echo ' </div>'."\n";
echo '	<input type="hidden" name="action" value="save_horizontal_submenus_spacing" />'."\n";
echo '</form>'."\n";









//  Submenus rollover color :
//  -------------------------



if ( get_option( $wp_tijusm_plugin_prefix.'submenus_rollover_color' ) != "") { 

	$submenus_rollover_color  = get_option( $wp_tijusm_plugin_prefix.'submenus_rollover_color' ); 

} else { 

	$settings_group_is_to_be_deleted = get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	if ( $settings_group_is_to_be_deleted == 'false'  ) {

		update_option( $wp_tijusm_plugin_prefix.'submenus_rollover_color','ff0000' );  //  This instruction is very useful to store the option as soon as the plugin runs ( and more precisely , before the user modify it )	
		$submenus_rollover_color  = 'ff0000';

	} else {
			
		delete_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	};  //  Else 2 End 

};  //  Else 1 End

echo '<form action="admin.php?page=wp_smtiggerjumpingslidingmenu.php&action=save_submenus_rollover_color&settings_group_ID_request='.$wp_tijusm_settings_group_ID_request.'&plugin_prefix_request='.$wp_tijusm_plugin_prefix.'" method="post">'."\n";
echo '<div class="input text">'."\n";
echo '	<label for="'.$wp_tijusm_plugin_prefix.'submenus_rollover_color'.'">Submenus rollover color : <br /> ( default is ff0000 )</label>'."\n";
echo ' 	<input name="'.$wp_tijusm_plugin_prefix.'submenus_rollover_color'.'" id="'.$wp_tijusm_plugin_prefix.'submenus_rollover_color'.'" type="text" value="'.$submenus_rollover_color.'" />'."\n";
echo ' <span class="submit"><input name="save_submenus_rollover_color" type="submit" value="Save the submenus rollover color" /></span>'."\n";

echo ' </div>'."\n";
echo '	<input type="hidden" name="action" value="save_submenus_rollover_color" />'."\n";
echo '</form>'."\n";








//  Submenus rollover transparency :
//  ------------------------------



if ( get_option( $wp_tijusm_plugin_prefix.'submenus_rollover_transparency' ) != "") { 

	$submenus_rollover_transparency  = get_option( $wp_tijusm_plugin_prefix.'submenus_rollover_transparency' ); 

} else { 

	$settings_group_is_to_be_deleted = get_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	if ( $settings_group_is_to_be_deleted == 'false'  ) {

		update_option( $wp_tijusm_plugin_prefix.'submenus_rollover_transparency','50' );  //  This instruction is very useful to store the option as soon as the plugin runs ( and more precisely , before the user modify it )	
		$submenus_rollover_transparency  = '50';

	} else {
			
		delete_option( 'wp_tijusm_plugin_prefix'.$wp_tijusm_settings_group_ID_request.'_settings_group_is_to_be_deleted' );

	};  //  Else 2 End 

};  //  Else 1 End

echo '<form action="admin.php?page=wp_smtiggerjumpingslidingmenu.php&action=save_submenus_rollover_transparency&settings_group_ID_request='.$wp_tijusm_settings_group_ID_request.'&plugin_prefix_request='.$wp_tijusm_plugin_prefix.'" method="post">'."\n";
echo '<div class="input text">'."\n";
echo '	<label for="'.$wp_tijusm_plugin_prefix.'submenus_rollover_transparency'.'">Submenus rollover transparency : <br /> ( default is 50 , unit is % )</label>'."\n";
echo ' 	<input name="'.$wp_tijusm_plugin_prefix.'submenus_rollover_transparency'.'" id="'.$wp_tijusm_plugin_prefix.'submenus_rollover_transparency'.'" type="text" value="'.$submenus_rollover_transparency.'" />'."\n";
echo ' <span class="submit"><input name="save_submenus_rollover_transparency" type="submit" value="Save the submenus rollover transparency" /></span>'."\n";

echo ' </div>'."\n";
echo '	<input type="hidden" name="action" value="save_submenus_rollover_transparency" />'."\n";
echo '</form>'."\n";






echo '</div>'."\n";
echo '</div>'."\n";
echo '<br />'."\n";











//  MENUS Declarations :
//  ------------------


echo '<div class="subdivision">'."\n";
echo '<div class="title"><h3><img src="../component/images/trans.png" class="close" alt=""">Menus declarations :</h3>'."\n";
echo '<div class="clear_both"></div></div>'."\n";
echo '<div class="options">'."\n";









//  Menu Title and Menu Url declarations :
//  ------------------------------------


if ( get_option( $wp_tijusm_plugin_prefix.'sup_menus_counter' ) != "") { 

			$sup_menus_counter = intval(get_option( $wp_tijusm_plugin_prefix.'sup_menus_counter' )); 

} else { 

			update_option( $wp_tijusm_plugin_prefix.'sup_menus_counter', '-1' );
			$sup_menus_counter = -1;

};





echo '<form action="admin.php?page=wp_smtiggerjumpingslidingmenu.php&action=save_new_menu_declaration&indice_menu='.intval($sup_menus_counter + 1).'&settings_group_ID_request='.$wp_tijusm_settings_group_ID_request.'&plugin_prefix_request='.$wp_tijusm_plugin_prefix.'" method="post">'."\n";
echo '<div class="input text">'."\n";


echo '	<label for="'.$wp_tijusm_plugin_prefix.'menu'.intval($sup_menus_counter + 1).'_title'.'">Menu Title : </label>'."\n";
echo ' 	<input name="'.$wp_tijusm_plugin_prefix.'menu'.intval($sup_menus_counter + 1).'_title'.'" id="'.$wp_tijusm_plugin_prefix.'menu'.intval($sup_menus_counter + 1).'_title'.'" type="text" value="" /><br />'."\n";

echo '	<label for="'.$wp_tijusm_plugin_prefix.'menu'.intval($sup_menus_counter + 1).'_url'.'">Default Menu Url : </label>'."\n";
echo ' 	<input name="'.$wp_tijusm_plugin_prefix.'menu'.intval($sup_menus_counter + 1).'_url'.'" id="'.$wp_tijusm_plugin_prefix.'menu'.intval($sup_menus_counter + 1).'_url'.'" type="text" value="" /><br />'."\n";


echo ' <span class="new_menu_submit"><input name="new_menu_submit" type="submit" value="Add a new menu" /></span>'."\n";
 



echo ' </div>'."\n";
echo '	<input type="hidden" name="action" value="save_new_menu_declaration" />'."\n";
echo '</form>'."\n";


//  The block of instructions below creates arrays of parameters :

$menu_id = array();
$menu_title = array();
$menu_url = array();



if ( $sup_menus_counter >= 0 ) {


	echo '	<table class="new_menu">'."\n";
	echo '	  <tr>'."\n";
	echo '	    <th style="width: 10%;">Menu ID</th>'."\n";
	echo '	    <th style="width: 20%;">Menu Title</th>'."\n";
	echo '	    <th style="width: 50%;">Default Menu Url</th>'."\n";
	echo '	    <th style="width: 20%;">Operations</th>'."\n";
	echo '	  </tr>'."\n";

	for ( $i = 0; $i <= $sup_menus_counter; $i++ ) {

		$menu_id[$i] = get_option( $wp_tijusm_plugin_prefix.'indice_menu'.$i );
		$menu_title[$i] = get_option( $wp_tijusm_plugin_prefix.'menu'.$i.'_title' );
		$menu_url[$i] = get_option( $wp_tijusm_plugin_prefix.'menu'.$i.'_url' );

		if ( $menu_title[$i] != "" ) {

			echo '	  <tr>'."\n";
			echo '	    <td>'.$menu_id[$i].'</td>'."\n";
			echo '	    <td>'.$menu_title[$i].'</td>'."\n";
			echo '	    <td ><a href="'.$menu_url[$i].'" target="_blank" >'.$menu_url[$i].'</a></td>'."\n";
			echo '	    <td>'."\n";

			echo '				<form action="admin.php?page=wp_smtiggerjumpingslidingmenu.php&action=delete_menu&indice_menu='.$menu_id[$i].'&settings_group_ID_request='.$wp_tijusm_settings_group_ID_request.'&plugin_prefix_request='.$wp_tijusm_plugin_prefix.'" method="post">'."\n";
			echo ' 						<span class="delete_menu_submit"><input name="delete_menu_submit" type="submit" value="Remove" /></span>'."\n";
			echo '						<input type="hidden" name="action" value="delete_menu" />'."\n";
			echo '			   </form>'."\n";

			echo '		 </td>'."\n";
			echo '	 </tr>'."\n";

		};  //  if ( $menu_title[$i] != "" ) End
		
	};  // for End
	

	echo '	</table>'."\n";

}  // if ( $sup_menus_counter >= 0 ) End





echo '</div>'."\n";
echo '</div>'."\n";
echo '<br />'."\n";




















//  SUBMENUS Declarations :
//  ---------------------


echo '<div class="subdivision">'."\n";
echo '<div class="title"><h3><img src="../component/images/trans.png" class="close" alt=""">Submenus declarations :</h3>'."\n";
echo '<div class="clear_both"></div></div>'."\n";
echo '<div class="options">'."\n";









//  Submenu Title and Submenu Url declarations :
//  ------------------------------------------


if ( get_option( $wp_tijusm_plugin_prefix.'sup_submenus_counter' ) != "") { 

			$sup_submenus_counter = intval(get_option( $wp_tijusm_plugin_prefix.'sup_submenus_counter' )); 

} else { 

			update_option( $wp_tijusm_plugin_prefix.'sup_submenus_counter', '-1' );
			$sup_submenus_counter = -1;

};





echo '<form action="admin.php?page=wp_smtiggerjumpingslidingmenu.php&action=save_new_submenu_declaration&indice_submenu='.intval($sup_submenus_counter + 1).'&settings_group_ID_request='.$wp_tijusm_settings_group_ID_request.'&plugin_prefix_request='.$wp_tijusm_plugin_prefix.'" method="post">'."\n";
echo '<div class="input text">'."\n";


echo '	<label for="'.$wp_tijusm_plugin_prefix.'submenu'.intval($sup_submenus_counter + 1).'_title'.'">Submenu Title : </label>'."\n";
echo ' 	<input name="'.$wp_tijusm_plugin_prefix.'submenu'.intval($sup_submenus_counter + 1).'_title'.'" id="'.$wp_tijusm_plugin_prefix.'submenu'.intval($sup_submenus_counter + 1).'_title'.'" type="text" value="" /><br />'."\n";

echo '	<label for="'.$wp_tijusm_plugin_prefix.'submenu'.intval($sup_submenus_counter + 1).'_url'.'">Submenu Url : </label>'."\n";
echo ' 	<input name="'.$wp_tijusm_plugin_prefix.'submenu'.intval($sup_submenus_counter + 1).'_url'.'" id="'.$wp_tijusm_plugin_prefix.'submenu'.intval($sup_submenus_counter + 1).'_url'.'" type="text" value="" /><br />'."\n";

echo '	<label for="'.$wp_tijusm_plugin_prefix.'submenu'.intval($sup_submenus_counter + 1).'_parent_menu_id'.'">Parent Menu ID : </label>'."\n";
echo ' 	<input name="'.$wp_tijusm_plugin_prefix.'submenu'.intval($sup_submenus_counter + 1).'_parent_menu_id'.'" id="'.$wp_tijusm_plugin_prefix.'submenu'.intval($sup_submenus_counter + 1).'_parent_menu_id'.'" type="text" value="" /><br />'."\n";


echo ' <span class="new_submenu_submit"><input name="new_submenu_submit" type="submit" value="Add a new submenu" /></span>'."\n";
 



echo ' </div>'."\n";
echo '	<input type="hidden" name="action" value="save_new_submenu_declaration" />'."\n";
echo '</form>'."\n";


//  The block of instructions below creates arrays of parameters :

$submenu_id = array();
$submenu_title = array();
$submenu_url = array();



if ( $sup_submenus_counter >= 0 ) {


	echo '	<table class="new_submenu">'."\n";
	echo '	  <tr>'."\n";
	echo '	    <th style="width: 10%;">Submenu ID</th>'."\n";
	echo '	    <th style="width: 20%;">Submenu Title</th>'."\n";
	echo '	    <th style="width: 40%;">Submenu Url</th>'."\n";
	echo '	    <th style="width: 10%;">Parent Menu ID</th>'."\n";
	echo '	    <th style="width: 20%;">Operations</th>'."\n";
	echo '	  </tr>'."\n";

	for ( $i = 0; $i <= $sup_submenus_counter; $i++ ) {

		$submenu_id[$i] = get_option( $wp_tijusm_plugin_prefix.'indice_submenu'.$i );
		$submenu_title[$i] = get_option( $wp_tijusm_plugin_prefix.'submenu'.$i.'_title' );
		$submenu_url[$i] = get_option( $wp_tijusm_plugin_prefix.'submenu'.$i.'_url' );
		$submenu_parent_menu_id[$i] = get_option( $wp_tijusm_plugin_prefix.'submenu'.$i.'_parent_menu_id' );

		if ( $submenu_title[$i] != "" ) {

			echo '	  <tr>'."\n";
			echo '	    <td>'.$submenu_id[$i].'</td>'."\n";
			echo '	    <td>'.$submenu_title[$i].'</td>'."\n";
			echo '	    <td ><a href="'.$submenu_url[$i].'" target="_blank" >'.$submenu_url[$i].'</a></td>'."\n";
			echo '	    <td>'.$submenu_parent_menu_id[$i].'</td>'."\n";
			echo '	    <td>'."\n";

			echo '				<form action="admin.php?page=wp_smtiggerjumpingslidingmenu.php&action=delete_submenu&indice_submenu='.$submenu_id[$i].'&settings_group_ID_request='.$wp_tijusm_settings_group_ID_request.'&plugin_prefix_request='.$wp_tijusm_plugin_prefix.'" method="post">'."\n";
			echo ' 						<span class="delete_submenu_submit"><input name="delete_submenu_submit" type="submit" value="Remove" /></span>'."\n";
			echo '						<input type="hidden" name="action" value="delete_submenu" />'."\n";
			echo '			   </form>'."\n";

			echo '		 </td>'."\n";
			echo '	 </tr>'."\n";

		};  //  if ( $submenu_title[$i] != "" ) End
		
	};  // for End
	

	echo '	</table>'."\n";

}  // if ( $sup_submenus_counter >= 0 ) End




echo '</div>'."\n";
echo '</div>'."\n";
echo '<br />'."\n";








//  The block of instructions below retrieves plugin parameters and options values stored in database :
//  -------------------------------------------------------------------------------------------------


$flash_component_title = get_option( $wp_tijusm_plugin_prefix.'title' );
$flash_component_subtitle  = get_option( $wp_tijusm_plugin_prefix.'subtitle' );
$wp_tijusm_flash_component_width  = get_option( $wp_tijusm_plugin_prefix.'flash_component_width' );
$wp_tijusm_flash_component_height  = get_option( $wp_tijusm_plugin_prefix.'flash_component_height' );

$menus_vertical_position  = get_option( $wp_tijusm_plugin_prefix.'menus_vertical_position' );
$menus_horizontal_position  = get_option( $wp_tijusm_plugin_prefix.'menus_horizontal_position' );
$movement_curvature_radius  = get_option( $wp_tijusm_plugin_prefix.'movement_curvature_radius' );
$submenus_vertical_position  = get_option( $wp_tijusm_plugin_prefix.'submenus_vertical_position' );
$submenus_horizontal_position  = get_option( $wp_tijusm_plugin_prefix.'submenus_horizontal_position' );

$menu_text_color  = get_option( $wp_tijusm_plugin_prefix.'menu_text_color' );
$menu_background_color  = get_option( $wp_tijusm_plugin_prefix.'menu_background_color' );
$menu_border_color  = get_option( $wp_tijusm_plugin_prefix.'menu_border_color' );
$menu_index_text_color  = get_option( $wp_tijusm_plugin_prefix.'menu_index_text_color' );
$menu_index_background_color = get_option( $wp_tijusm_plugin_prefix.'menu_index_background_color' );
$menu_index_border_color = get_option( $wp_tijusm_plugin_prefix.'menu_index_border_color' );
$vertical_menus_spacing = get_option( $wp_tijusm_plugin_prefix.'vertical_menus_spacing' );
$menus_rollover_color = get_option( $wp_tijusm_plugin_prefix.'menus_rollover_color' );
$menus_rollover_transparency = get_option( $wp_tijusm_plugin_prefix.'menus_rollover_transparency' );

$submenu_text_color = get_option( $wp_tijusm_plugin_prefix.'submenu_text_color' );
$submenu_background_color = get_option( $wp_tijusm_plugin_prefix.'submenu_background_color' );
$submenu_border_color = get_option( $wp_tijusm_plugin_prefix.'submenu_border_color' );
$vertical_submenus_spacing = get_option( $wp_tijusm_plugin_prefix.'vertical_submenus_spacing' );
$horizontal_submenus_spacing = get_option( $wp_tijusm_plugin_prefix.'horizontal_submenus_spacing' );
$submenus_rollover_color = get_option( $wp_tijusm_plugin_prefix.'submenus_rollover_color' );
$submenus_rollover_transparency = get_option( $wp_tijusm_plugin_prefix.'submenus_rollover_transparency' );





//  The instruction below creates an Instance of DOMDocument class :

$doc_xml = new DOMDocument();

//  The instructions below defines the XML file version and encoding :

$doc_xml->version = '1.0'; 
$doc_xml->encoding = 'ISO-8859-1';



$parameters_group = $doc_xml->createElement("parameters_group");		//  This instruction creates the root element and associates it to the XML document .
$doc_xml->appendChild($parameters_group);								//  This instruction adds the element previously created  as a "child node" of the existing structure of the XML document .



			$item = $doc_xml->createElement("item");						//  This instruction creates the "item" element and associates it to the XML document .
			$parameters_group->appendChild($item);						//  This instruction adds the element previously created  as a "child node" of the existing structure of the XML document .
			$item->setAttribute('flashComponentTitle', trim($flash_component_title));

			$item = $doc_xml->createElement("item");						//  This instruction creates the "item" element and associates it to the XML document .
			$parameters_group->appendChild($item);						//  This instruction adds the element previously created  as a "child node" of the existing structure of the XML document .
			$item->setAttribute('flashComponentSubtitle', trim($flash_component_subtitle));

			$item = $doc_xml->createElement("item");						//  This instruction creates the "item" element and associates it to the XML document .
			$parameters_group->appendChild($item);						//  This instruction adds the element previously created  as a "child node" of the existing structure of the XML document .
			$item->setAttribute('flashComponentWidth', trim($wp_tijusm_flash_component_width));

			$item = $doc_xml->createElement("item");						//  This instruction creates the "item" element and associates it to the XML document .
			$parameters_group->appendChild($item);						//  This instruction adds the element previously created  as a "child node" of the existing structure of the XML document .
			$item->setAttribute('flashComponentHeight', trim($wp_tijusm_flash_component_height));

			$item = $doc_xml->createElement("item");						//  This instruction creates the "item" element and associates it to the XML document .
			$parameters_group->appendChild($item);						//  This instruction adds the element previously created  as a "child node" of the existing structure of the XML document .
			$item->setAttribute('menusVerticalPosition', trim($menus_vertical_position));

			$item = $doc_xml->createElement("item");						//  This instruction creates the "item" element and associates it to the XML document .
			$parameters_group->appendChild($item);						//  This instruction adds the element previously created  as a "child node" of the existing structure of the XML document .
			$item->setAttribute('menusHorizontalPosition', trim($menus_horizontal_position));

			$item = $doc_xml->createElement("item");						//  This instruction creates the "item" element and associates it to the XML document .
			$parameters_group->appendChild($item);						//  This instruction adds the element previously created  as a "child node" of the existing structure of the XML document .
			$item->setAttribute('movementCurvatureRadius', trim($movement_curvature_radius));

			$item = $doc_xml->createElement("item");						//  This instruction creates the "item" element and associates it to the XML document .
			$parameters_group->appendChild($item);						//  This instruction adds the element previously created  as a "child node" of the existing structure of the XML document .
			$item->setAttribute('submenusVerticalPosition', trim($submenus_vertical_position));

			$item = $doc_xml->createElement("item");						//  This instruction creates the "item" element and associates it to the XML document .
			$parameters_group->appendChild($item);						//  This instruction adds the element previously created  as a "child node" of the existing structure of the XML document .
			$item->setAttribute('submenusHorizontalPosition', trim($submenus_horizontal_position));

			$item = $doc_xml->createElement("item");						//  This instruction creates the "item" element and associates it to the XML document .
			$parameters_group->appendChild($item);						//  This instruction adds the element previously created  as a "child node" of the existing structure of the XML document .
			$item->setAttribute('menuTextColor', trim($menu_text_color));

			$item = $doc_xml->createElement("item");						//  This instruction creates the "item" element and associates it to the XML document .
			$parameters_group->appendChild($item);						//  This instruction adds the element previously created  as a "child node" of the existing structure of the XML document .
			$item->setAttribute('menuBackgroundColor', trim($menu_background_color));

			$item = $doc_xml->createElement("item");						//  This instruction creates the "item" element and associates it to the XML document .
			$parameters_group->appendChild($item);						//  This instruction adds the element previously created  as a "child node" of the existing structure of the XML document .
			$item->setAttribute('menuBorderColor', trim($menu_border_color));

			$item = $doc_xml->createElement("item");						//  This instruction creates the "item" element and associates it to the XML document .
			$parameters_group->appendChild($item);						//  This instruction adds the element previously created  as a "child node" of the existing structure of the XML document .
			$item->setAttribute('menuIndexTextColor', trim($menu_index_text_color));

			$item = $doc_xml->createElement("item");						//  This instruction creates the "item" element and associates it to the XML document .
			$parameters_group->appendChild($item);						//  This instruction adds the element previously created  as a "child node" of the existing structure of the XML document .
			$item->setAttribute('menuIndexBackgroundColor', trim($menu_index_background_color));

			$item = $doc_xml->createElement("item");						//  This instruction creates the "item" element and associates it to the XML document .
			$parameters_group->appendChild($item);						//  This instruction adds the element previously created  as a "child node" of the existing structure of the XML document .
			$item->setAttribute('menuIndexBorderColor', trim($menu_index_border_color));


			$item = $doc_xml->createElement("item");						//  This instruction creates the "item" element and associates it to the XML document .
			$parameters_group->appendChild($item);						//  This instruction adds the element previously created  as a "child node" of the existing structure of the XML document .
			$item->setAttribute('verticalMenusSpacing', trim($vertical_menus_spacing));

			$item = $doc_xml->createElement("item");						//  This instruction creates the "item" element and associates it to the XML document .
			$parameters_group->appendChild($item);						//  This instruction adds the element previously created  as a "child node" of the existing structure of the XML document .
			$item->setAttribute('menusRolloverColor', trim($menus_rollover_color));

			$item = $doc_xml->createElement("item");						//  This instruction creates the "item" element and associates it to the XML document .
			$parameters_group->appendChild($item);						//  This instruction adds the element previously created  as a "child node" of the existing structure of the XML document .
			$item->setAttribute('menusRolloverTransparency', trim($menus_rollover_transparency));

			$item = $doc_xml->createElement("item");						//  This instruction creates the "item" element and associates it to the XML document .
			$parameters_group->appendChild($item);						//  This instruction adds the element previously created  as a "child node" of the existing structure of the XML document .
			$item->setAttribute('submenuTextColor', trim($submenu_text_color));

			$item = $doc_xml->createElement("item");						//  This instruction creates the "item" element and associates it to the XML document .
			$parameters_group->appendChild($item);						//  This instruction adds the element previously created  as a "child node" of the existing structure of the XML document .
			$item->setAttribute('submenuBackgroundColor', trim($submenu_background_color));

			$item = $doc_xml->createElement("item");						//  This instruction creates the "item" element and associates it to the XML document .
			$parameters_group->appendChild($item);						//  This instruction adds the element previously created  as a "child node" of the existing structure of the XML document .
			$item->setAttribute('submenuBorderColor', trim($submenu_border_color));

			$item = $doc_xml->createElement("item");						//  This instruction creates the "item" element and associates it to the XML document .
			$parameters_group->appendChild($item);						//  This instruction adds the element previously created  as a "child node" of the existing structure of the XML document .
			$item->setAttribute('verticalSubmenusSpacing', trim($vertical_submenus_spacing ));

			$item = $doc_xml->createElement("item");						//  This instruction creates the "item" element and associates it to the XML document .
			$parameters_group->appendChild($item);						//  This instruction adds the element previously created  as a "child node" of the existing structure of the XML document .
			$item->setAttribute('horizontalSubmenusSpacing', trim($horizontal_submenus_spacing ));

			$item = $doc_xml->createElement("item");						//  This instruction creates the "item" element and associates it to the XML document .
			$parameters_group->appendChild($item);						//  This instruction adds the element previously created  as a "child node" of the existing structure of the XML document .
			$item->setAttribute('submenusRolloverColor', trim($submenus_rollover_color));

			$item = $doc_xml->createElement("item");						//  This instruction creates the "item" element and associates it to the XML document .
			$parameters_group->appendChild($item);						//  This instruction adds the element previously created  as a "child node" of the existing structure of the XML document .
			$item->setAttribute('submenusRolloverTransparency', trim($submenus_rollover_transparency));




for ( $i = 0; $i <= ( $sup_menus_counter ); $i++ ) {

		$item = $doc_xml->createElement("item");					//  This instruction creates the "item" element which contains a parameters data and associates it to the XML document .
		$parameters_group->appendChild($item);					//  This instruction adds the element previously created  as a "child node" of the existing structure of the XML document .


		//   Attributes are assigned to the "item" element, knowing that each "item" element must be in the following form :
		//  <item menuName="Menu 1" urlMenuName="http://" menuIdFromMenuName="17" />

		$item->setAttribute('menuName', trim($menu_title[$i]));
		$item->setAttribute('urlMenuName', trim($menu_url[$i]));
		$item->setAttribute('menuIdFromMenuName', trim($menu_id[$i]));

}  //  For End


for ( $i = 0; $i <= ( $sup_submenus_counter ); $i++ ) {

		$item = $doc_xml->createElement("item");					//  This instruction creates the "item" element which contains a parameters data and associates it to the XML document .
		$parameters_group->appendChild($item);					//  This instruction adds the element previously created  as a "child node" of the existing structure of the XML document .

		//   Attributes are assigned to the "item" element, knowing that each "item" element must be in the following form :
		//  <item subMenuName="Submenu 1" urlSubMenuName="http://" menuIdFromSubMenuName="30" />

		$item->setAttribute('subMenuName', trim($submenu_title[$i]));
		$item->setAttribute('urlSubMenuName', trim($submenu_url[$i]));
		$item->setAttribute('menuIdFromSubMenuName', trim($submenu_parent_menu_id[$i]));

}  //  For End






//  The instruction below improves the XML document presentation :
$doc_xml->formatOutput = true;

//  The instruction below displays the XML document , only on the screen :
//  echo $doc_xml->saveXML();

//  The instruction below saves the XML document in a file whose name is in the following form : movieclip_parameters'.$wp_tijusm_settings_group_ID_request.'.xml
$doc_xml->save('../wp-content/plugins/wp_smtiggerjumpingslidingmenu/component/movieclip_parameters'.$wp_tijusm_settings_group_ID_request.'.xml');










}

add_action('admin_init', 'wp_tijusm_mytheme_add_init');
add_action('admin_menu', 'wp_tijusm_mytheme_add_admin');

?>