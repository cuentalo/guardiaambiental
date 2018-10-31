<?php
// Crea el formato JSON para la API de OpenLayers
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

// Crea el formato JSON para la API de OpenLayers
// externalGraphic, iconUrl, graphic, image
// "externalGraphic": "/media/buoy-icon.png",       "iconSize": "24,24",        "iconOffset": "-16,16"
//  graphicOpacity: 1.0,
//        graphicWith: 16,
//        graphicHeight: 26,
//        graphicYOffset: -26
function jh_osm_array_string_json_2($a, $b, $c){	
	$l=count($a);
	for($x=0;$x<$l;$x++){
		$me[] = array('type'=> 'Feature', 'geometry' => array('type' => 'Point', 'coordinates' => [$a[$x][0], $a[$x][1]] ), 
					  'properties' => array('name' => $b[$x][0], 'description' => $b[$x][1], 
											'iconUrl' => $c[$x][1], 'iconSize' => '16,26', 'iconOffset' => '-26,26'));
   	}
	$meta = array( 'type' => 'FeatureCollection', 'features' => $me);
	$myJSON = wp_json_encode($meta);	
	return $myJSON;
}
//----------//
register_nav_menus( array(
	'menu-principal' => __('Area principal de navegación', 'perla'),
	'menu-secundario'  => __( 'footer menu', 'perla' ),
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
	'name' => __('Bara-lateral', 'invasa'), //El nombre del área dinámica
	'id' => 'barra-lateral', //Un identificador único para la zona
	'description' => __( 'Este es el área de widgets del sitio.', 'invasa'), //Una breve descripción
	'before_widget' => '<div id="%1$s" class="widget %2$s">', //Algo de HTML que irá antes de cada widget
	'after_widget'  => '</div>', //Algo de HTML que irá después de cada widget
	'before_title' => '<h3>', //La etiqueta que irá antes del título de cada widget
	'after_title' => '</h3>' //La etiqueta que irá después del título de cada widget
));

function mi_inicio() {
	if (!is_admin()) {
		wp_enqueue_script('jquery');
	}
}
add_action('init', 'mi_inicio');

//---------------------------------------------------------------------
// CARGANDO ESTILOS DEL TEMA
//---------------------------------------------------------------------
//Creamos una función para cargar los estilos
function perla_styles() { 
 
	//Registramos la fuente Open Sans
	wp_register_style( 'font-sans', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,400,700', '', '', 'all' );

 
	//Registramos Bootstrap
	wp_register_style( 'bootstrap', get_stylesheet_directory_uri().'/css/bootstrap.min.css', '', '3.0.0', 'all' );
 
	//registramos la hoja de estilos del tema
	wp_register_style( 'perla-style', get_stylesheet_uri(), array('font-sans', 'bootstrap'), '1.0.0', 'all' );
 
	//Ahora cargamos los estilos. Nota que sólo cargamos 'perla-style' ya que en esta hoja de estilos declaramos dependendencia de 'font-sans' y 'bootstrap', éstas cargaran de manera automática
	wp_enqueue_style( 'perla-style' );

	wp_enqueue_script('bootstrap-scripts', get_stylesheet_directory_uri().'/js/bootstrap.min.js','','','true');
}
add_action('wp_enqueue_scripts', 'perla_styles'); //Ejecutamos la función

//Habilitar thumbnails
add_theme_support('post-thumbnails');
set_post_thumbnail_size(300, 300, true);
?>

