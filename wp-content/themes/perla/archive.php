<?php
/*
Template Name: Archives
*/
get_header(); 

$categoria = "";
//if (isset($_GET['cat'])) {
//	$categoria 	= trim($_GET['cat']);
//}
//$pid = '';
//if (isset($_GET['p'])) {
//	$pid 	= trim($_GET['p']);
//}
	$categoria 	= "puntos";
	$cat_m 		= "agua-muestreo";

	global $post; 

	//Lista de parametros. Cada Parametro es una capa en el mapa
	$args_par = array(
    		'post_type'  	=> 'post',
			'numberposts'	=> -1,
			'category_name' => 'parametros'
	);
	//El ciclo inicial es con la lista de parametros porque vamos a crear las capas
	$tq 	= get_posts($args_par); 
	$total 	= 0; //Usado en la busqueda manual
	$verde 		= "http://dev.openlayers.org/examples/img/check-round-green.png";
	$amarillo 	= "http://dev.openlayers.org/examples/img/check-round-grey.png";
	$rojo 		= "http://dev.openlayers.org/examples/img/mobile-loc.png";
	$icon 		= "http://guardiaambiental.org/wp-content/themes/perla/images/amonio.png"; 
	//"http://dev.openlayers.org/examples/img/mobile-loc.png";
/** Include class */
include( 'GoogChart.class.php' );

/** Create chart */
$chart = new GoogChart();
// Set graph colors
$color = array('#99C754','#54C7C5','#999999',);

//Para la Tabla de datos en la Grafica
$td		= '<td align="center" bgcolor="#cccccc">'; 
$td_c	= '<td align="center" bgcolor="#fffff0">';

foreach ($tq as $post ) : setup_postdata($post);
	$id 	= get_the_ID(); //trim(ID);		
	$titulo = get_the_title(); //post_title; 								 			 			 			 
	$max	= trim(get_post_meta($id, 'Max', true ));
	if ($max) {
		$max = $max * 1.2; //Lo incrementamos un 20%
	} else {
		$max = 100;
	}
	$min	= trim(get_post_meta($id, 'Min', true ));
	if ($min) {
		$min = $min; 
	} else {
		$min = 0;
	}
	$medio	= trim(get_post_meta($id, 'Medio', true ));
	if ($medio) {
		$medio = $medio; 
	} else {
		$medio = 0;
	}
	$guia	= trim(get_post_meta($id, 'Guia', true ));
	if ($guia) {
		$guia = $guia; 
	} else {
		$guia = 0;
	}
	$icono	= trim(get_post_meta($id, 'Icono', true ));
	if (!$icono) {
		$icono = $icon;
	}
	//Consultar la lista de puntos geograficos. Cada capa contiene estos puntos.
	//Para cada Parametro se recorre la Lista de Puntos
	$args_pun = array(
    		'post_type'  	=> 'post',
			'numberposts'	=> -1,  //Muestra TODOS los posts
			'category_name' => 'puntos'
	);				
	$puntos = get_posts($args_pun); 		
	foreach ($puntos as $pu) {
		$pu_id 	= $pu->ID;
		$pu_ti 	= $pu->post_title;
		//Conseguir Lon y Lat del Punto
		$lon	= trim(get_post_meta($pu_id, 'Lon', true ));
		$lat	= trim(get_post_meta($pu_id, 'Lat', true ));			 		 	
		if ($lon) {
			//Conseguir la lista de Muestreo del Parametro
			$args = array(
				'post_type'  	=> 'post',
				'category_name' => 'agua-muestreo',
				'numberposts'	=> -1,
				'meta_query' 	=> 	array(
										'relation' => 'AND',
										array('key' => 'Parametro', 'value' => $id, 'compare' => 'like'), 
										array('key' => 'Punto_Geografico', 'value' => $pu_id, 'compare' => 'like')
									) 
			);				
			$muestreos 	= get_posts($args); 		
			$tot_mu 	= count( $muestreos ); 
			$hay 		= 0;
			$inc 		= 100/$tot_mu;
			$max_inc 	= 100/$max;
			$inf		= '';
			$alt		= false;
			//$info 		= "<p></p><p>Datos:</p>";
			//Definir por defaut el punto 0
			$datax['0'] = 0; 
			$datay['0'] = 0; 
			foreach ($muestreos as $mu) {
				$mu_id 		= trim($mu->ID);					
				$mu_va		= trim(get_post_meta($mu_id, 'Valor', true ));
				$hay 		= $hay + 1;					
				if (strpos($mu_va, ',')) {    
					$mu_va 	= str_replace(',', '.', $mu_va);
				}
				$mu_va 		= $mu_va * 1.0;
				$mu_ti 		= trim($mu->post_title).", Valor=".$mu_va;		
				$m 			= ''.$hay; //'M'.$hay
				//$info 		.= "<p>".$m.": ".$mu_ti."</p>";
				if ($alt){
					$alt	= false;
					$t		= $td;
				}else {
					$alt	= true;
					$t		= $td_c;
				}			
				$inf		.= '<tr>';
				$inf		.=$t.'</td>';
				$inf		.=$t.$m.'</td>';
				$inf		.=$t.trim($mu->post_title).'</td>';
				$inf		.=$t.$mu_va.'</td>';
				$inf		.= '</tr>';
				$total 		= $total + 1;
				// Set graph data
				$datax[$m] = $hay*$inc;	
				$datay[$m] = $mu_va*$max_inc;				
	 		} 
			if ($hay > 0) {
				//Agregar una linea final a la tabla
				if ($alt){
					$alt	= false;
					$t		= $td;
				}else {
					$alt	= true;
					$t		= $td_c;
				}			
				$inf		.= '<tr>';
				$inf		.=$t.'</td>';
				$inf		.=$t.'--------</td>';
				$inf		.=$t.'------------------------------------------------------------</td>';
				$inf		.=$t.'-------------</td>';
				$inf		.= '</tr>';
				//Crear la Tabla de datos
				$info	= '<table align="center" border="0">';
				$info	.= '<tr>';
				$info	.= $td.'</td>';
				$info	.= $td.'#</td>';
				$info	.= $td.'Fecha</td>';
				$info	.= $td.'Valor</td>';
				$info	.= '<tr>';
				$info	.= $inf;
				$info 	.= '</table>';
				// Graph
				$range 	= '0,0,'.$hay*$inc.','.$inc.'|1,0,'.$max; //'0,0,100,2|1,0,100,20'
	 			$chxt 	= 'x,y,r'; 
	 			$chxr 	= '0,0,'.$hay*$inc.','.$inc.'|1,0,'.$max; //'0,0,100,2|1,0,100,20'
	 			$chxl 	= '2:|guia|medio';
	 			$chxp 	= '2,'.$guia*$max_inc.','.$medio*$max_inc;  //'2,10,35,95';
	 			$chxs 	= '2,0000dd,13,-1,t,FF0000';
	 			$chxtc 	= '2,-900';
				
				$chart->setChartAttrs( array(
					'type' 		=> 'scatter',
					'title' 	=>  $titulo,
					'data' 		=> array($datax, $datay), //$data,
					'size' 		=> array( 900, 300 ), //400,300
					'color' 	=> $color,
					'labelsXY' 	=> true,
					'legend' 	=> false,
					'chxt' 		=> $chxt,
					'chxr' 		=> $chxr,
					'chxl' 		=> $chxl,
					'chxp' 		=> $chxp,
					'chxs' 		=> $chxs,
					'chxtc'		=> $chxtc					
				));			
				$oc = "";
				$oc .="<div class='row bg-items py-4 px-3 mb-5'>";	
       			$oc .="<div class='new-home'>";
       			$oc .="<div class='line-title my-4'></div>";				
				$ur = "#";
				//$oc .="<div><a href='".$ur."' class='btn-more'>".$titulo." &#8594</a></div>";					
				$oc .= $chart.$info;
				$oc .="</div>";
				$oc .="</div>";				
				//Formar el JSON
				$a[] = array($lon, $lat);
				$b[] = array("El ".$pu_ti." tiene ".$hay." muestras", $oc); // "<div></div>"
				$c[] = array($icono, $icono); //Modificar codigo para aceptar una vez $icono
				unset($datax);
				unset($datay);
			}
		}	
	}
	if ($a){
		$capa_t[] = $titulo;
		$capa_i[] = $icono;  //Icono de la capa
		$capa_a[] = $a;
		$capa_b[] = $b;
		$capa_c[] = $c;
		unset($a); unset($b); unset($c);
	}
endforeach;

?>

<section id="slider" class="slider-home">
	<div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="slider-content">
                     <h1>Visualizacion Puntos de Muestreos de la Cienaga de la Virgen</h1>                 
                </div>
            </div>
        </div>		
    </div>  
</section>

<section class="time-line py-5" id="b">
  <div class="container">
	  <?php if ($total>0){ 
			$texto = $total." Muestras";?>	
      		<h2 class="title-h2 line-title pb-2 mt-5"> <?php echo $texto; ?></h2>
	        <h5>Toque el Marcador y aparecerà al lado, la informaciòn de la Ubicaciòn.</h5>
			<h5>Toque el link y aparecerà la informacion dentro del Mapa.</h5>			
	  		<p><a href="http://guardiaambiental.org"> Ir a la pàgina principal</a></p>	
	  <?php } else {	
	  		$texto = "Ninguna Ubicaciòn encontrada. ";?>	
      		<h2 class="title-h2 line-title pb-2 mt-5"> <?php echo $texto; ?></h2>	  
	  		<p><a href="http://guardiaambiental.org"> Ir a la pàgina principal</a></p>	
	  <?php } ?>	
      <div class="item-tl my-4 p-3">
          <article>       
        	<link rel="stylesheet" href="http://dev.openlayers.org/theme/default/style.css" type="text/css">
      		<?php //<link rel="stylesheet" href="http://dev.openlayers.org/examples/style.css" type="text/css">	?>
			  <div class="noticias-actuales">
                <div class="row bg-items py-4 px-3 mb-5">
                  <div class="col-md-6">
                      <div class="img-thumb">
	        			<div id="map" style="width: 900px; height: 600px; border: 1px solid #ccc;"></div>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="new-home">       
						<div id="layerswitcher" class="olControlLayerSwitcher"></div>					    
                      </div>
                  </div>				
                  <div class="col-md-6">
                      <div class="new-home">       						
						 <ul id="style_chooser"></ul>
    	    			<div id="divList" style="float:left; min-width: 200px; margin-left: 6px"></div>		
                      </div>
                  </div>
	<?php if ($total>0){ ?>		
		<div style="clear:both;display:none" id="docs">			            
		<script type="application/json" id="desdePHP_0"><?php echo jh_osm_array_string_json($capa_a[0], $capa_b[0]); ?></script>
			<script type="application/json" id="titulo_0"><?php echo $capa_t[0]; ?></script>	
			<script type="application/json" id="icon_0"><?php echo $capa_i[0]; ?></script>
		<script type="application/json" id="desdePHP_1"><?php echo jh_osm_array_string_json($capa_a[1], $capa_b[1]); ?></script>
			<script type="application/json" id="titulo_1"><?php echo $capa_t[1]; ?></script>
			<script type="application/json" id="icon_1"><?php echo $capa_i[1]; ?></script>
		<script type="application/json" id="desdePHP_2"><?php echo jh_osm_array_string_json($capa_a[2], $capa_b[2]); ?></script>
			<script type="application/json" id="titulo_2"><?php echo $capa_t[2]; ?></script>	
			<script type="application/json" id="icon_2"><?php echo $capa_i[2]; ?></script>
		<script type="application/json" id="desdePHP_3"><?php echo jh_osm_array_string_json($capa_a[3], $capa_b[3]); ?></script>
			<script type="application/json" id="titulo_3"><?php echo $capa_t[3]; ?></script>	
			<script type="application/json" id="icon_3"><?php echo $capa_i[3]; ?></script>
		<script type="application/json" id="desdePHP_4"><?php echo jh_osm_array_string_json($capa_a[4], $capa_b[4]); ?></script>
			<script type="application/json" id="titulo_4"><?php echo $capa_t[4]; ?></script>	
			<script type="application/json" id="icon_4"><?php echo $capa_i[4]; ?></script>			
		<script type="application/json" id="desdePHP_5"><?php echo jh_osm_array_string_json($capa_a[5], $capa_b[5]); ?></script>
			<script type="application/json" id="titulo_5"><?php echo $capa_t[5]; ?></script>	
			<script type="application/json" id="icon_5"><?php echo $capa_i[5]; ?></script>
		<script type="application/json" id="desdePHP_6"><?php echo jh_osm_array_string_json($capa_a[6], $capa_b[6]); ?></script>
			<script type="application/json" id="titulo_6"><?php echo $capa_t[6]; ?></script>	
			<script type="application/json" id="icon_6"><?php echo $capa_i[6]; ?></script>
		<script type="application/json" id="desdePHP_7"><?php echo jh_osm_array_string_json($capa_a[7], $capa_b[7]); ?></script>
			<script type="application/json" id="titulo_7"><?php echo $capa_t[7]; ?></script>
			<script type="application/json" id="icon_7"><?php echo $capa_i[7]; ?></script>
		<script type="application/json" id="desdePHP_8"><?php echo jh_osm_array_string_json($capa_a[8], $capa_b[8]); ?></script>
			<script type="application/json" id="titulo_8"><?php echo $capa_t[8]; ?></script>	
			<script type="application/json" id="icon_8"><?php echo $capa_i[8]; ?></script>
		<script type="application/json" id="desdePHP_9"><?php echo jh_osm_array_string_json($capa_a[9], $capa_b[9]); ?></script>
			<script type="application/json" id="titulo_9"><?php echo $capa_t[9]; ?></script>	
			<script type="application/json" id="icon_9"><?php echo $capa_i[9]; ?></script>
		<script type="application/json" id="desdePHP_10"><?php echo jh_osm_array_string_json($capa_a[10], $capa_b[10]); ?></script>
			<script type="application/json" id="titulo_10"><?php echo $capa_t[10]; ?></script>	
			<script type="application/json" id="icon_10"><?php echo $capa_i[10]; ?></script>				
			<p id="desdeJS" ></p>
        </div>	
					
		<script src="http://dev.openlayers.org/OpenLayers.js"></script>
        <script src="<?php echo get_stylesheet_directory_uri(); ?>/lib/patches_OL-popup-autosize.js"></script>
        <script src="<?php echo get_stylesheet_directory_uri(); ?>/lib/FeaturePopups.js"></script>
				
        <script src="<?php echo get_stylesheet_directory_uri(); ?>/feature-popups-common.js"></script>
        <script src="<?php echo get_stylesheet_directory_uri(); ?>/feature-popups-external.js"></script>				
    <?php }else{ ?>
	<?php } ?>
	<?php wp_reset_query(); // reset the query ?>											
                </div> 
             </div>
          </article>
      </div>
  </div> 
</section>

<?php get_footer(); ?>