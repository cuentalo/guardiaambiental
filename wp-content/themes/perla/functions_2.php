<?php
// Escrito por hernandezmercadoj@gmail.com - 2018-03
// Rating
// Add custom meta (ratings) fields to the default comment form
// Default comment form includes name, email and URL
// Default comment form elements are hidden when user is logged in

add_filter('comment_form_default_fields','custom_fields');
function custom_fields($fields) {

		$commenter = wp_get_current_commenter();
		$req = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );

		$fields[ 'author' ] = '<p class="comment-form-author">'.
			'<label for="author">' . __( 'Name' ) . '</label>'.
			( $req ? '<span class="required">*</span>' : '' ).
			'<input id="author" name="author" type="text" value="'. esc_attr( $commenter['comment_author'] ) . 
			'" size="30" tabindex="1"' . $aria_req . ' /></p>';
		
		$fields[ 'email' ] = '<p class="comment-form-email">'.
			'<label for="email">' . __( 'Email' ) . '</label>'.
			( $req ? '<span class="required">*</span>' : '' ).
			'<input id="email" name="email" type="text" value="'. esc_attr( $commenter['comment_author_email'] ) . 
			'" size="30"  tabindex="2"' . $aria_req . ' /></p>';
					
		$fields[ 'url' ] = '<p class="comment-form-url">'.
			'<label for="url">' . __( 'Website' ) . '</label>'.
			'<input id="url" name="url" type="text" value="'. esc_attr( $commenter['comment_author_url'] ) . 
			'" size="30"  tabindex="3" /></p>';

	return $fields;
}

// Add fields after default fields above the comment box, always visible
add_action( 'comment_form_logged_in_after', 'additional_fields' );
add_action( 'comment_form_after_fields', 'additional_fields' );

function additional_fields () {

	echo '<p class="comment-form-rating">'.
	'<label for="rating">'. __('Rating') . '<span class="required">*</span></label>
	<span class="commentratingbox">';
	
	for( $i=1; $i <= 5; $i++ )
	echo '<span class="commentrating"><input type="radio" name="rating" id="rating" value="'. $i .'"/>'. $i .'</span>';

	echo'</span></p>';

}

// Save the comment meta data along with comment
add_action( 'comment_post', 'save_comment_meta_data' );
function save_comment_meta_data( $comment_id ) {
	if ( ( isset( $_POST['rating'] ) ) && ( $_POST['rating'] != '') )
	$rating = wp_filter_nohtml_kses($_POST['rating']);
	add_comment_meta( $comment_id, 'rating', $rating );
}


// Add the filter to check if the comment meta data has been filled or not
add_filter( 'preprocess_comment', 'verify_comment_meta_data' );
function verify_comment_meta_data( $commentdata ) {
	if ( ! isset( $_POST['rating'] ) )
	wp_die( __( 'Error: You did not add your rating. Hit the BACK button of your Web browser and resubmit your comment with rating.' ) );
	return $commentdata;
}

//Add an edit option in comment edit screen  
add_action( 'add_meta_boxes_comment', 'extend_comment_add_meta_box' );
function extend_comment_add_meta_box() {
    add_meta_box( 'title', __( 'Comment Metadata - Extend Comment' ), 'extend_comment_meta_box', 'comment', 'normal', 'high' );
}
 
function extend_comment_meta_box ( $comment ) {
    $rating = get_comment_meta( $comment->comment_ID, 'rating', true );
    wp_nonce_field( 'extend_comment_update', 'extend_comment_update', false );
    ?>
    <p>
        <label for="rating"><?php _e( 'Rating: ' ); ?></label>
			<span class="commentratingbox">
			<?php for( $i=1; $i <= 5; $i++ ) {
				echo '<span class="commentrating"><input type="radio" name="rating" id="rating" value="'. $i .'"';
				if ( $rating == $i ) echo ' checked="checked"';
				echo ' />'. $i .' </span>'; 
				}
			?>
			</span>
    </p>
    <?php
}

// Update comment meta data from comment edit screen 
add_action( 'edit_comment', 'extend_comment_edit_metafields' );
function extend_comment_edit_metafields( $comment_id ) {
    if( ! isset( $_POST['extend_comment_update'] ) || ! wp_verify_nonce( $_POST['extend_comment_update'], 'extend_comment_update' ) ) return;

	if ( ( isset( $_POST['rating'] ) ) && ( $_POST['rating'] != '') ):
	$rating = wp_filter_nohtml_kses($_POST['rating']);
	update_comment_meta( $comment_id, 'rating', $rating );
	else :
	delete_comment_meta( $comment_id, 'rating');
	endif;
	
}

// Add the comment meta (saved earlier) to the comment text 
// You can also output the comment meta values directly in comments template  
add_filter( 'comment_text', 'modify_comment');
function modify_comment( $text ){

	if( $commentrating = get_comment_meta( get_comment_ID(), 'rating', true ) ) {	
		$commentrating = '<p class="comment-rating"> <img src="'.'/wp-content/themes/jrk_theme/images/'. $commentrating . 'star.gif"/><br/>Rating: <strong>'. $commentrating .' / 5</strong></p>';
		$text = $text . $commentrating;
		return $text;
	} else {
		return $text;		
	}	 
}

// Obtener el Rating promedio del Post actual
function average_rating() {
	global $wpdb;
	$post_id = get_the_ID();
	$ratings = $wpdb->get_results("
    	SELECT $wpdb->commentmeta.meta_value
    	FROM $wpdb->commentmeta
    	INNER JOIN $wpdb->comments on $wpdb->comments.comment_id=$wpdb->commentmeta.comment_id
    	WHERE $wpdb->commentmeta.meta_key='rating' 
    	AND $wpdb->comments.comment_post_id=$post_id 
    	AND $wpdb->comments.comment_approved =1
    ");
	$counter = 0;
	$average_rating = 0.0;    
	if ($ratings) {
    	foreach ($ratings as $rating) {
        	$average_rating = $average_rating + $rating->meta_value;
	        $counter++;
    	} 
    	//round the average to the nearast 1/2 point
    	$average_rating = (round(($average_rating/$counter)*2,0)/2);  
    	
		if( $average_rating <= 1.0) {				
			$intrating = 1;
		} elseif( $average_rating <= 2.0)  {	
			$intrating = 2;
		} elseif( $average_rating <= 3.0)  {	
			$intrating = 3;
		} elseif( $average_rating <= 4.0)  {	
			$intrating = 4;
		} elseif( $average_rating <= 5.0)  {	
			$intrating = 5;
		} 			
		$commentrating = '<p class="comment-rating"> <img src="'.'/wp-content/themes/jrk_theme/images/'. $intrating . 'star.gif"/> Rating: <strong>'. $average_rating .' / 5</strong></p>';
		return $commentrating;
	} else {
    	return '<p class="comment-rating">no rating</p>';
	}
}
// End-Rating---- 

function jh_the_slug($echo=true){
  $slug = basename(get_permalink());
  do_action('before_slug', $slug);
  $slug = apply_filters('slug_filter', $slug);
  if( $echo ) echo $slug;
  do_action('after_slug', $slug);
  return $slug;
}

//Obtiene el id del post de acuerdo al meta_field
//incompleto
function jh_post_id_by_meta_key_and_value( $meta_key, $meta_value ){
	global $wpdb;
	$ids = $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %s", $meta_key, $meta_value ) );
 
	if( count( $ids ) > 1 ) 
		return $ids; // return array
	else
		return $ids[0]; // return int
}

//incompleto
function jh_get_historias_number($grupo_historia){
	jh_post_id_by_meta_key_and_value( 'grupo_historia', $grupo_historia );
	
}
//Funcion para crear la cadena JSON para pasarlo a openlayers
function jh_osm_string_json($x, $y, $c1, $c2, $c3){
	$meta = array( 'type' => 'FeatureCollection', 
					'features' => [
						array('type'=> 'Feature', 'geometry' => array('type' => 'Point', 'coordinates' => [$x, $y] ),
							'properties' => array('Name' => $c1, 'Country' => $c2, 'City' => $c3))
					]					
	);
	$myJSON = json_encode($meta);	
	return $myJSON;
}

function jh_osm_array_string_json($a, $b){	
	$l=count($a);
	for($x=0;$x<$l;$x++){
		$me[] = array('type'=> 'Feature', 'geometry' => array('type' => 'Point', 'coordinates' => [$a[$x][0], $a[$x][1]] ),
							'properties' => array('name' => $b[$x][0], 'description' => $b[$x][1]));				
   	}
	$meta = array( 'type' => 'FeatureCollection', 'features' => $me);
	$myJSON = wp_json_encode($meta);	
	return $myJSON;
}
//Calculary mostrar  la cantidad de historias del Grupo.
//Devuelve HTML (Link y titulo)
function jh_buscar_historias($id_Grupo) {
	$id = $id_Grupo;	
	$args = array(
		'category_name' => 'historia',
		'meta_query' => array(array('key' => 'Grupo_historia', 'value' => $id))
    );
	$gh = get_posts( $args );
	$c_gh = count($gh);
	$s_gh = ' Sin Historias asociadas';
	$s_url = "#";
	$res = "<i class='fa fa-history' aria-hidden='true'></i>".$s_gh;
	if ( $c_gh != 0 ) {
		if ($c_gh == 1) {			
			//Buscar el ID de la Historia asociada
			foreach ($gh as $g) {
				$gh_id 	= $g->ID;
			}			
			$s_gh = 'Ver Historia asociada';
			$s_url = "http://cuentalo.org/registrar-una-historia?pid=".$gh_id;
		} else {
			//Muestra el conjunto de Historias asociadas
			$s_gh = $c_gh.'Ver Historias asociadas';
			$s_url = "http://cuentalo.org/?s=&post_type=post&categoria=historia&grupo_historia=".$id;
		}	
		$res = "<p><a href='".$s_url."' class='btn-primary'>".$s_gh."</a></p>";
	}
	return $res;														
}
//Obtiene y devuelve el HTML (Link y titulo) de Grupo aosciado a la Historia
function jh_buscar_grupo($id_His) {
	//Obtener el Id del Grupo asociado
	$id = get_post_meta( $id_His, 'Grupo_historia', true );		
	$s_gh = ' Sin Grupo asociado';
	$s_url = "#";	
	$res = "<i class='fa fa-history' aria-hidden='true'></i>".$s_gh;
	if ( $id != '' ) {
		$post_g	= get_post( $id ); 
		if ( $post_g ) {
			$s_gh = 'Ver Grupo asociado';
			$s_url = "http://cuentalo.org/registrar-una-historia?pid=".$post_g->ID;
			//$titulo = $post_g->post_title;	
			$res = "<p><a href='".$s_url."' class='btn-primary'>".$s_gh."</a></p>";
		}
	}
	return $res;		
}

add_action( 'login_form_login', 'redirect_to_custom_login');
/**
 * Redirect the user to the custom login page instead of wp-login.php.
 */
function redirect_to_custom_login() {
    if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
        $redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : null;
     
        if ( is_user_logged_in() ) {
            $this->redirect_logged_in_user( $redirect_to );
            exit;
        }
 
        // The rest are redirected to the login page
        $login_url = home_url( 'member-login' );
        if ( ! empty( $redirect_to ) ) {
            $login_url = add_query_arg( 'redirect_to', $redirect_to, $login_url );
        }
 
        wp_redirect( $login_url );
        exit;
    }
}
/**
 * Redirects the user to the correct page depending on whether he / she
 * is an admin or not.
 *
 * @param string $redirect_to   An optional redirect_to URL for admin users
 */
function redirect_logged_in_user( $redirect_to = null ) {
    $user = wp_get_current_user();
    if ( user_can( $user, 'manage_options' ) ) {
        if ( $redirect_to ) {
            wp_safe_redirect( $redirect_to );
        } else {
            wp_redirect( admin_url() );
        }
    } else {
        wp_redirect( home_url( 'member-account' ) );
    }
}


add_action('wp_logout','auto_redirect_after_logout');
function auto_redirect_after_logout(){
	wp_redirect( 'http://cuentalo.org/registrar-usuario');
	exit();
}

// Funciones para guardar y obtener las Entradas (grupos e historias)
// Tenemos acceso a las variables $_POST por medio del post del admin
function jh_guardar_post_especial() {
	$user_id 		= get_current_user_id();
	$post_id		= 0;
	$titulo 		= '';
	$descripcion	= '';		
	$categoria		= 'Historia'; //Categoria por Default
	$imagen			= '';
	$etiquetas 		= '';
	$video 			= '';
	//Datos propios de las Historias
	$grupo_historia = '';
	$narrativa 		= '';	
	$protagonistas	= '';
	//Datos propios de los grupos
	$poblacion		= '';
	$direccion 		= '';
	if (isset( $_POST['post_id'] ) && ($_POST['post_id'] != '') ) {
		$post_id 		= $_POST['post_id'] ;
	}
	if ( ( isset( $_POST['titulo'] ) ) && ( $_POST['titulo'] != '') ) {
		$titulo 		= wp_filter_nohtml_kses($_POST['titulo']);
	}
	if ( ( isset( $_POST['descripcion'] ) ) && ( $_POST['descripcion'] != '') ) {
		$descripcion	= wp_filter_nohtml_kses($_POST['descripcion']);
	}
	if (isset( $_POST['categoria'] ) && ($_POST['categoria'] != '') ) { 
		$categoria 		= $_POST['categoria'];
		if (is_array($categoria)) {
			$categoria 	= $categoria[0]; //Garantizamos un string, el primero
		}
	}
	if (isset( $_POST['etiquetas'] ) && ($_POST['etiquetas'] != '') ) { 
		$etiquetas 		= $_POST['etiquetas'];
		if (!is_array($etiquetas)){
			$etiquetas = explode( ',', trim($etiquetas, " \n\t\r\0\x0B,") );
		}
		//$etiquetas = is_array($etiquetas) ? $etiquetas : explode( ',', trim($etiquetas, " \n\t\r\0\x0B,") );
	}
	if (isset( $_POST['video'] ) && ($_POST['video'] != '') ) { 
		$video 			= $_POST['video'];
	}	
	//$cat=get_cat_ID( $categoria);
	if ($categoria=='Historia') {
		if (isset( $_POST['grupo_historia'] ) && ($_POST['grupo_historia'] != '') ) { 
			$grupo_historia = $_POST['grupo_historia'];
		}	
		if (isset( $_POST['narrativa'] ) && ($_POST['narrativa'] != '') ) { 
			$narrativa = $_POST['narrativa'];
		}
		if (isset( $_POST['protagonistas'] ) && ($_POST['protagonistas'] != '') ) { 
			$protagonistas= $_POST['protagonistas'];
		}		
	}
	if ($categoria=='Grupo') {
		if (isset( $_POST['poblacion'] ) && ($_POST['poblacion'] != '') ) { 
			$poblacion = $_POST['poblacion'];
		}	
		if (isset( $_POST['direccion'] ) && ($_POST['direccion'] != '') ) { 
			$direccion = $_POST['direccion'];
		}
	}	
	if ($categoria=='Historia') {
		$meta = array(
			'Video' 			=> $video,
			'Grupo_historia' 	=> $grupo_historia,
			'Narrativa' 		=> $narrativa,
			'Protagonistas' 	=> $protagonistas
		);
	}
	if ($categoria=='Grupo') {
		$meta = array(
			'Video' 	=> $video,
			'Poblacion' => $poblacion,
			'Direccion' => $direccion
		);
	}
	$args_my_post = array(
			'ID'    		=> $post_id,
			'post_title'    => $titulo, 
  			'post_content'  => $descripcion,
			'post_category'	=> array(get_cat_ID($categoria)), 		//array('category' => $categoria), 
			'tags_input'	=> $etiquetas, //array($etiquetas),
	  		'post_author'   => $user_id, 
			'meta_input'	=> $meta	
	);		
	// Add o Update the post into the database
	$my_post = wp_insert_post( $args_my_post );
	if(!is_wp_error($my_post)){
		$post_id = $my_post->ID;
	 	// Check that the nonce is valid
		if ( isset( $_POST['imagen_nonce']) && wp_verify_nonce( $_POST['imagen_nonce'], 'imagen' ) ) {			 	  
			// The nonce was valid, it is safe to continue.
				// These files need to be included as dependencies when on the front end.
				require_once( ABSPATH . 'wp-admin/includes/post.php' ); //No estaba aqui
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				require_once( ABSPATH . 'wp-admin/includes/media.php' );
	
				// Let WordPress handle the upload.
				// Remember, 'imagen' is the name of our file input in our form above.
				$attachment_id = media_handle_upload( 'imagen', $my_post);	
				if ( is_wp_error( $attachment_id ) ) {
					// There was an error uploading the image.
					echo $attachment_id->get_error_message();
				} else {
					// The image was uploaded successfully!
					set_post_thumbnail( $my_post, $attachment_id );
					echo "Imagen guardada";
				}				
			} else {
				echo "Sin imagen que guardar";
				// The security check failed, maybe show the user an error.
			}			
			$rurl = '/registrar-una-historia?pid='.$my_post;
		wp_redirect(home_url($rurl));
	} else {
  		 	//there was an error in the post insertion, 
  		 	echo $my_post->get_error_message();
	}			
}
add_action( 'admin_post_nopriv_historia_form', 'jh_guardar_post' );
add_action( 'admin_post_historia_form', 'jh_guardar_post' );

// Construyendo el sistema de busqueda
// 1. Definir la estructura de datos
// 2. Registrar las variables de consulta personalizada (varsQ, son variables publicas usadas via URL)
// 3. Obtener (Get) los valores de las varsQ y usarlas para construir una consulta personalizada
// 4. Construir una forma (programaticamente) para generar los valores de los campos

/**
 * 2. Register custom query vars
 *
 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/query_vars
 * 
 * http://example.com/?autor=XXX&categoria=YYY&etiqueta=ZZZ
 */
function jh_register_query_vars( $vars ) {
	$vars[] = 'id';	
	$vars[] = 'varios';
    $vars[] = 'autor';
	$vars[] = 'categoria';
    $vars[] = 'etiqueta';
	$vars[] = 'narrativa';
	$vars[] = 'protagonista';
	$vars[] = 'direccion';
	$vars[] = 'poblacion';	
	$vars[] = 'grupo_historia';	
    return $vars;
}
add_filter( 'query_vars', 'jh_register_query_vars' );

function jh_pre_get_posts( $query ) {
    // check if the user is requesting an admin page 
    // or current query is not the main query
    if ( is_admin() || ! $query->is_main_query() ){
        return;
    }
	
	$va =  get_query_var( 'varios' ) ;	//get_search_query();
	$et = '';	
	$na = '';	
	$pr = '';	
	$di = '';	
	$po = '';	 
	if( !empty( $va ) ){
		$na = $va;	
		$pr = $va;	
		$di = $va;	
		$po = $va;	         
		$gh = $va;
    }
	if( !empty( get_query_var( 'id' ) ) ){
		$query->set( 'ID', get_query_var( 'id' ) );       
    }
	if( !empty( get_query_var( 'autor' ) )  && get_query_var( 'autor' )!='todos' ){
		$query->set( 'author_name', get_query_var( 'autor' ) );       
    }
	
	if( !empty( get_query_var( 'categoria' ) ) ){
		$query->set( 'category_name', get_query_var( 'categoria' ) );       		
    }	
	if( !empty( get_query_var( 'etiqueta' ) ) ){
		$et = get_query_var( 'etiqueta' );
    }	
	if( !empty( $et ) && $et!='todas' ){
		$query->set( 'tag', $et );       		
    }	
	//$query->set( 'posts_per_page', 2 ); 

	$query->set( 'no_found_rows', 1 );
	
	if( !empty( get_query_var( 'post_type' ) ) ){
        $query->set( 'post_type', get_query_var( 'post_type' ) );
    }	
	
	// Define meta_query 
	$meta_query = array();

    // add meta_query elements
    if( !empty( get_query_var( 'narrativa' ) ) ){
		$na = get_query_var( 'narrativa' );	
    }
	if( !empty( $na ) ){
        $meta_query[] = array( 'key' => 'Narrativa', 'value' => $na, 'compare' => 'LIKE' );
    }

    if( !empty( get_query_var( 'protagonista' ) ) ){
		$pr = get_query_var( 'protagonista' );	
    }
    if( !empty( $pr ) ){
        $meta_query[] = array( 'key' => 'Protagonistas', 'value' => $pr, 'compare' => 'LIKE' );
    }

	if( !empty( get_query_var( 'direccion' ) ) ){
		$di = get_query_var( 'direccion' );	
    }
	if( !empty( $di ) ){
        $meta_query[] = array( 'key' => 'Direccion', 'value' => $di, 'compare' => 'LIKE' );
    }
	
	if( !empty( get_query_var( 'poblacion' ) ) ){
		$po = get_query_var( 'poblacion' );	
	}
	if( !empty( $po ) ){
        $meta_query[] = array( 'key' => 'Poblacion', 'value' => $po, 'compare' => 'LIKE' );
    }

	if( !empty( get_query_var( 'grupo_historia' ) ) ){
		$gh = get_query_var( 'grupo_historia' );	
    }
	if( !empty( $gh ) ){
        $meta_query[] = array( 'key' => 'Grupo_historia', 'value' => $gh, 'compare' => 'LIKE' );
    }
	
    if( count( $meta_query ) > 1 ){
        $meta_query['relation'] = 'OR';
    }

    if( count( $meta_query ) > 0 ){
        $query->set( 'meta_query', $meta_query );
    }
	
}
add_action( 'pre_get_posts', 'jh_pre_get_posts', 1 );

// Create a shortcode that will allow the site admin to include a search form in posts and pages of the website.
// Our shortcode will be hooked to the init action.
function jh_setup() {
    add_shortcode( 'jh_search_form', 'jh_search_form' );
}
add_action( 'init', 'jh_setup' );

// $args is an array of the shortcode attributes.
function jh_search_form( $args ){
	
	$select_cat = '';
	$select_cat .= '<select name="categoria">';
	//$select_cat .= '<option value="" selected="selected">' . __( 'Seleccione categoria', 'smashing_plugin' ) . '</option>';
	$select_cat .= '<option value="grupo" selected="selected">' . __( 'Grupo', 'smashing_plugin' ) . '</option>';
	$select_cat .= '<option value="historia">' . __( 'Historia', 'smashing_plugin' ) . '</option>';
	$select_cat .= '</select>';

	$select_etq  = '';
	$select_etq .= '<select name="etiqueta">';
    $select_etq .= '<option value="">Todas</option>';
    $tags = get_tags();
    foreach ($tags as $tag) {
    	$select_etq .= '<option value="'.$tag->slug.'">'.$tag->name.'</option>';
    };
  	$select_etq .= '</select>';
	
	$select_aut = '';
	$select_aut .= '<select name="autor">';
    $select_aut .= '<option value="">Todos</option>';
    $autores = get_users(); 								 
    foreach ($autores as $autor) {
    	$select_aut .= '<option value="'.$autor->nickname.'">'.$autor->display_name.'</option>';
    };
  	$select_aut .= '</select>';	
	
	$output = '<div class="buscar-home">';
	$output .= '<form action="' . esc_url( home_url() ) . '" method="GET" role="search">';	
	$output .= '<fieldset class="buscar-content-list">';
        $output .= '<div class="buscar-boder-caja2">';
        $output .= ' <input class="buscar-caja-list b-caja1" type="text" name="s" placeholder="Buscar en el titulo ..." value="' . get_search_query() . '" />';
        $output .= '<input class="buscar-caja-list b-caja2" type="text" name="varios" placeholder="Buscar en los otros campos ..." value="' . get_query_var( 'varios' ) . '" />';
             
        $output .= '<span>'.$select_cat;
        $output .= $select_etq.'</span>';
        $output .= '<span>'.$select_aut.'</span>';
        $output .= '<input type="hidden" name="post_type" value="post" />';
        $output .= '<input type="submit" value="Buscar" class="buscar-boton btn btn-warning"  />';
    $output .= '</fieldset>';
    $output .= '</div>';
        
	
	$output .= '</form>';
	$output .= '</div>';
	return $output;

}
// Ayuda a controlar la forma de la busqueda
add_filter( 'get_search_form', 'jh_search_form' );

// Hasta Aqui

register_nav_menus( array(
	'menu-principal' => __('Area principal de navegación', 'perla'),
	'menu-footer'  => __( 'footer menu', 'perla' ),
) );

require_once('wp_bootstrap_navwalker.php');

// Ajustar el máximo ancho de las imagenes de acuerdo al diseño de este modo cualquier imagen que insertemos en el contenido de un artículo va a tener como máximo este ancho
if ( ! isset( $content_width ) )
	$content_width = 750; //El ancho máximo será de 750 pixeles
 
// Creamos una función para registrar algunas características del tema
function opciones_tema()  {
 
	// Permitimos que el sitio soporte RSS Automáticos
	add_theme_support( 'automatic-feed-links' );
 
	// Permitimos qe el tema soporte imagenes destacadas
	add_theme_support( 'post-thumbnails');	

}
 
// Ejecutamos la función y registra las características
add_action( 'after_setup_theme', 'opciones_tema' );


//---------------------------------------------------------------------
// REGISTRAMOS EL SIDEBAR
//---------------------------------------------------------------------
 
//Con la función register_sidebar, registramos una zona dinámica para 
//nuestro tema y le pasamos algunos parámetros
register_sidebar(array(
	'name' => __('widget-bar', 'perla'), //El nombre del área dinámica
	'id' => 'widget-bar', //Un identificador único para la zona
	'description' => __( 'Este es el área de widgets del sitio.', 'perla'), //Una breve descripción
	'before_widget' => '<div id="%1$s" class="widget %2$s">', //Algo de HTML que irá antes de cada widget
	'after_widget'  => '</div>', //Algo de HTML que irá después de cada widget
	'before_title' => '<h3>', //La etiqueta que irá antes del título de cada widget
	'after_title' => '</h3>' //La etiqueta que irá después del título de cada widget
));
register_sidebar(array(
	'name' => __('footer1', 'perla'), //El nombre del área dinámica
	'id' => 'footer1', //Un identificador único para la zona
	'description' => __( 'Este es el área de widgets del sitio.', 'perla'), //Una breve descripción
	'before_widget' => '<div id="%1$s" class="widget %2$s">', //Algo de HTML que irá antes de cada widget
	'after_widget'  => '</div>', //Algo de HTML que irá después de cada widget
	'before_title' => '<h4>', //La etiqueta que irá antes del título de cada widget
	'after_title' => '</h4>' //La etiqueta que irá después del título de cada widget
));

function mi_inicio() {
	if (!is_admin()) {
		wp_enqueue_script('jquery');
	}
}
add_action('init', 'mi_inicio');


function wpdocs_custom_excerpt_length( $length ) {
    return 15;
}
add_filter( 'excerpt_length', 'wpdocs_custom_excerpt_length', 999 );

function new_excerpt_more( $more ) {
	return '...';
}
add_filter('excerpt_more', 'new_excerpt_more');

//---------------------------------------------------------------------
// CARGANDO ESTILOS DEL TEMA
//---------------------------------------------------------------------
//Creamos una función para cargar los estilos
function perla_styles() { 
 
	//Registramos la fuente Open Sans
	wp_register_style( 'font-sans', 'https://fonts.googleapis.com/css?family=Montserrat', '', '', 'all' );

 
	//Registramos Bootstrap
	wp_register_style( 'bootstrap', get_stylesheet_directory_uri().'/css/bootstrap.min.css', '', '3.0.0', 'all' );
 
	//registramos la hoja de estilos del tema
	wp_register_style( 'perla-style', get_stylesheet_uri(), array('font-sans', 'bootstrap'), '1.0.0', 'all' );
 
	//Ahora cargamos los estilos. Nota que sólo cargamos 'amk-style' ya que en esta hoja de estilos declaramos dependendencia de 'font-sans' y 'bootstrap', éstas cargaran de manera automática
	wp_enqueue_style( 'perla-style' );

	wp_enqueue_script('bootstrap-scripts', get_stylesheet_directory_uri().'/js/bootstrap.min.js','','','true');
}
add_action('wp_enqueue_scripts', 'perla_styles'); //Ejecutamos la función

//Habilitar thumbnails
add_theme_support('post-thumbnails');
set_post_thumbnail_size(300, 300, true);
?>