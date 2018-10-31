<?php
/*
Template Name: Agregar Posts
*/
?>
<?php get_header(); ?>

<?php
global $current_user;
$user 		= $current_user->user_login;
$user_id 	= get_current_user_id();
$redirect_to_login	= 'http://cuentalo.org/login';
//if (isset($_GET['accion']) && ($_GET['accion'] == 'editar')) {
//	if ( !$user )  {
//		wp_safe_redirect( $redirect_to );			
//	}
//}
//Agregar una Historia al Grupo
//Editar la Historia
//pid: Id del Post que deseamos editar. 
//Cuando pid=0 o no viene: queremos agregar 
//Si pid!=0 y viene con action=editar: queremos modificar 
//Si pid!=0 y viene con action=ver o sin action: queremos ver
//Datos comunes, tanto para los grupos como para las historias
$pid 			= 0; 
$post_id		= 0;
$titulo 		= '';
$categoria		= 'Historia'; //Categoria por Default
$imagen			= '';
$descripcion	= '';
$etiquetas 		= '';
$video 			= '';
//Datos propios de las Historias
$grupo_historia = '';
$narrativa 		= '';	
$protagonistas	= '';
//Datos propios de los grupos
$poblacion		= '';
$direccion 		= '';
$lon			= 0;
$lat			= 0;
//
$accion			= 'ver'; //Por default es Ver
$error 			= '';
$success		= '';
$redirect_to	= 'http://cuentalo.org/registrar-una-historia'; //Redirecciona a este link, despues que actualiza el Post
global $wpdb;
if (isset($_POST['his_form'])) {
	$user_id 		= get_current_user_id();
	$post_id		= 0;
	$titulo 		= '';
	$categoria		= 'Historia'; //Categoria por Default
	$imagen			= '';
	$descripcion	= '';
	$etiquetas 		= '';
	$video 			= '';
	//Datos propios de las Historias
	$grupo_historia = '';
	$narrativa 		= '';	
	$protagonistas	= '';
	//Datos propios de los grupos
	$poblacion		= '';
	$direccion 		= '';	
	$lon			= 0;
	$lat			= 0;
	$redirect_to	= 'http://cuentalo.org/registrar-una-historia';
	if (isset( $_POST['accion'] ) && ($_POST['accion'] != '') ) {
		$accion 		= $_POST['accion'] ;
	}
	if (isset( $_POST['post_id'] ) && ($_POST['post_id'] != '') ) {
		$post_id 		= $_POST['post_id'] ;
	}
	if ( ( isset( $_POST['titulo'] ) ) && ( $_POST['titulo'] != '') ) {
		$titulo 		= wp_filter_nohtml_kses($_POST['titulo']);
	}
	if ( ( isset( $_POST['descripcion'] ) ) && ( $_POST['descripcion'] != '') ) {
		$descripcion	= wp_filter_post_kses($_POST['descripcion']);
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
	}
	if (isset( $_POST['video'] ) && ($_POST['video'] != '') ) { 
		$video 			= wp_filter_nohtml_kses(trim($_POST['video']));
	}	
	if ($categoria=='Historia') {
		if (isset( $_POST['g_his'] ) && ($_POST['g_his'] != '') ) { 
			$grupo_historia = trim($_POST['g_his']);
			//$descripcion .= ' Probando: ---'.$grupo_historia.'---';
		}	
		if (isset( $_POST['narra'] ) && ($_POST['narra'] != '') ) { 
			$narrativa = wp_filter_post_kses(trim($_POST['narra']));
		}
		if (isset( $_POST['protagonistas'] ) && ($_POST['protagonistas'] != '') ) { 
			$protagonistas = stripslashes(trim($_POST['protagonistas']));
		}		
	}
	if ($categoria=='Grupo') {
		if (isset( $_POST['pobla'] ) && ($_POST['pobla'] != '') ) { 
			$poblacion = wp_filter_post_kses($_POST['pobla']);
		}	
		if (isset( $_POST['direc'] ) && ($_POST['direc'] != '') ) { 
			$direccion = wp_filter_post_kses($_POST['direc']);
		} 
	}	
	if (isset( $_POST['lon'] ) && ($_POST['lon'] != '') ) { 
		$lon 	= $_POST['lon'] ;
	}	
	if (isset( $_POST['lat'] ) && ($_POST['lat'] != '') ) { 
		$lat	= $_POST['lat'] ;
	} 
	
//	if ($categoria=='Historia') {
//		$meta = array(
//			'Video' 			=> $video
//			'Grupo_historia' 	=> $grupo_historia,
//			'Narrativa' 		=> $narrativa,
//			'Protagonistas' 	=> $protagonistas,
//			'Poblacion' 		=> $poblacion,
//			'Direccion' 		=> $direccion,
//			'Lon'				=> $lon,
//			'Lat'				=> $lat
//		);
//	}
//	if ($categoria=='Grupo') {
//		$meta = array(
//			'Video' 	=> $video,
//			'Poblacion' => $poblacion,
//			'Direccion' => $direccion
//		);
//	}
	$isnew = true;
	if ($post_id!=0){
		$rurl_ant = $redirect_to.'?pid='.$post_id; //Probando
		$isnew = false;
	} else {
		$rurl_ant = $redirect_to;
	}
	
	$args_my_post = array(
			'ID'    		=> $post_id,
			'post_title'    => $titulo, 
  			'post_content'  => $descripcion,
			'post_category'	=> array(get_cat_ID($categoria)), 		
			'tags_input'	=> $etiquetas, 
	  		'post_author'   => $user_id
//			'meta_input'	=> $meta	
	);		
	// Add o Update the post into the database
	$my_post = wp_insert_post( $args_my_post );
	if(!is_wp_error($my_post)){
		$post_id = $my_post;
		$rurl = $redirect_to.'?pid='.$my_post;
	 	// Si es nuevo post, y el nonce es valido
	 	if ( $isnew ){
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
				//echo $attachment_id->get_error_message();
				$error 	.= ' '.$attachment_id->get_error_message().'. ';	
				wp_redirect($rurl_ant);
			} else {
				// The image was uploaded successfully!
				set_post_thumbnail( $my_post, $attachment_id );
				//echo "Imagen guardada";
				$error 	.= ' '.'Imagen guardada.';
			}							
		  } else {			
			//echo "Sin imagen que guardar";
			$error 	.= ' '.'Error al guardar la Imagen.';
			wp_redirect($rurl_ant);
			// The security check failed, maybe show the user an error.
		  }					
		}	
		update_post_meta( $my_post, 'Grupo_historia', $grupo_historia);
		update_post_meta( $my_post, 'Video', $video);
		update_post_meta( $my_post, 'Protagonistas', $protagonistas);
		update_post_meta( $my_post, 'Narrativa', $narrativa); 
		update_post_meta( $my_post, 'Poblacion', $poblacion); //
		update_post_meta( $my_post, 'Direccion', $direccion); //
		update_post_meta( $my_post, 'Lon', $lon);
		update_post_meta( $my_post, 'Lat', $lat);
		//wp_safe_redirect( $rurl );
		wp_redirect($rurl);
	} else {
  		//there was an error in the post insertion, 
  		//echo $my_post->get_error_message();
  		$error 	.= ' Error al crear/actualizar la entrada. ';	
		wp_redirect($rurl_ant);
	}			
} else {
	if (isset($_GET['pid']) && ($_GET['pid'] != 0)) {
		$pid 			= $_GET['pid'];
	} else {
		$accion			= 'editar'; 
	}
    if (isset($_GET['grupo_his']) && ($_GET['grupo_his'] !='')) {
		$grupo_historia = trim($_GET['grupo_his']);
		$accion			= 'editar';  
    }
	if (isset($_GET['accion']) && ($_GET['accion'] == 'editar')) {
		$accion 		= $_GET['accion'];
	}
	if (isset($_GET['categoria']) && ($_GET['categoria'] == 'grupo')) {
		$categoria		= 'Grupo';
	}
	if ($accion == 'editar') {
		if ( !$user )  {
			wp_safe_redirect( $redirect_to_login );			
		}
	}
	
	//Fin aqui del GET?
	if ( $pid != 0 ) {
	  $post	 		= get_post( $pid ); 		
	  if ($post){				
		//$post->the_post(); //No funciono 
		$post_id		= $post->ID;
		$titulo 		= $post->post_title;
		// Obtener datos del autor (contacto)
		$author_id		= $post->post_author;
		$a_nickname		= get_the_author_meta('nickname', $post->post_author);
		$a_avatar 		= get_avatar_url( $post->post_author, 32, '/images/no_images.jpg', $a_nickname );  
		$a_name			= get_the_author_meta('display_name', $post->post_author);
		$a_email		= get_the_author_meta('user_email', $post->post_author);
		$a_url			= get_the_author_meta('user_url', $post->post_author);
		//
		$categories		= get_the_category($post_id);
		if ( ! empty( $categories ) ) {
    		$categoria 	= esc_html( $categories[0]->name );   
		}
		if ( has_post_thumbnail() ) {
			$imagen = get_post_meta( $post->ID, '_thumbnail_id', true );
    		$large_image_url = wp_get_attachment_image_src( $imagen, 'large' );
    		if ( ! empty( $large_image_url[0] ) ) {
				$imagen_url =  $large_image_url[0];
			}
		}				
		$descripcion 	= apply_filters( 'the_content', $post->post_content ); 
		$post_tags 		= get_the_tags($post_id); 
		if ( $post_tags ) {
    		foreach( $post_tags as $tag ) {
				$tag_array[] = $tag->name;
    		}	
        	$etiquetas = implode(', ',$tag_array);
		}	
		$video 			= get_post_meta( $post_id, 'Video', true );
		$protagonistas 	= get_post_meta( $post_id, 'Protagonistas', true );
		$narrativa 		= wpautop(get_post_meta( $post_id, 'Narrativa', true ));		
		$poblacion 		= get_post_meta( $post_id, 'Poblacion', true );		
		$direccion 		= get_post_meta( $post_id, 'Direccion', true );		
		$lon 			= get_post_meta( $post_id, 'Lon', true );		
		$lat 			= get_post_meta( $post_id, 'Lat', true );		  
		//$direccion 		.= ' Lon:'.$lon.' Lat:'.$lat;
		if ($grupo_historia == '') {
			$grupo_historia = get_post_meta( $post_id, 'Grupo_historia', true );		
		}	
	  }
    }
}
	// Variables del Form
	//  OJO: solo tomò 'descripcion'
	$editor_id 		= 'descripcion';
	$editor_id2 	= 'narrativa';
	$editor_pob 	= 'poblacion';
	$editor_dir 	= 'direccion';
   	$settings  		= array('textarea_id' => 'descripcion', 'textarea_rows' => 3); 
	$settings_narr  = array('textarea_name' => 'narrativa', 'textarea_rows' => 3); 
	$settings_pob  	= array('textarea_name' => 'poblacion', 'textarea_rows' => 3); 
	//$settings_pob  	= array('textarea_rows' => 3); 
	$settings_dir  	= array('textarea_name' => 'direccion', 'textarea_rows' => 3); 

	if ($accion=='editar') {
		if ($pid==0){
			$pagina_titulo		= 'Agregar '. $categoria;
			$label_submit 		= 'Enviar '. $categoria. ' para aprobación';	
		} else {
			$pagina_titulo		= 'Editar '. $categoria;
			$label_submit 		= 'Enviar Actualización de '. $categoria;
		}		
	} else {
		$pagina_titulo		= 'Ver '. $categoria;
		$label_submit 		= 'Error'; //No va a suceder porque no se usa en el boton
	}
	if ($categoria=='Historia'){
		$cat_map = 'historia';
		$label_titulo 		= 'Título de la Historia';
		$small_titulo 		= 'Escriba algunas palabras para describir el título de la historia';
		$small_descripcion 	= 'Detalle de la Historia. Sea muy fluido en su descripcion';
	} else {
		$cat_map = 'grupo';
		$label_titulo 		= 'Título del Grupo';
		$small_titulo 		= 'Escriba algunas palabras para describir el título del Grupo';
		$small_descripcion 	= 'Detalle del Grupo. Sea muy fluido en su descripcion';
	}
?>

<div></div>

<section class="py-5 bg-gris">
<div class="container">			
		<!--display error/success message-->
	<div id="message">
		<?php 
			if(! empty($error) ) :
				echo '<p class="error">'.$error.'';
			endif;
		?>		
		<?php if( !empty($success) ) {
				echo '<p class="error">'.$success.'';
		} ?>		
	</div>
	<form action="#" method="post" enctype="multipart/form-data">
		<h2 class="pb-1 mb-3 line-title2 font-weight-bold"><?php echo $pagina_titulo ?></h2>
		<div class="row">
			<div class="col">
			  <div class="bg-items p-5 grupo-campos card title-color-site">
				<h5 class="mb-4 font-weight-bold">Información General</h5>
				<div class="form-group">
					<?php if ($categoria=='Historia') { ?>
						<?php if ($accion!='editar') { ?>					
							<?php echo jh_buscar_grupo($post_id); ?>
							<?php if ( $user == $a_nickname ) { 
								$editar='http://cuentalo.org/registrar-una-historia/?pid='.$post_id.'&accion=editar'; ?>
								<p><a href="<?php echo $editar; ?>" class='btn-primary'>Editar ésta Historia</a></p>
								<?php if ( $grupo_historia != '' ) {  
									$a_otro = "http://cuentalo.org/registrar-una-historia/?grupo_his=".$grupo_historia; ?>
									<p><a href="<?php echo $a_otro; ?>" class='btn-primary'>Registrar otra historia al Grupo</a></p> 
								<?php } ?>			
							<?php } ?>										
						<?php } else { 
							// Buscar los Grupos del Usuario actual
							$args = array(
									'category_name' => 'Grupo',
  									'author'        =>  $user_id, 
  									'orderby'       =>  'post_date',
  									'order'         =>  'ASC',
								  	'posts_per_page' => -1 // no limit
							);
							$user_grupos = get_posts( $args );
							$total = count($user_grupos);				
							if ($total==0){?>
								<small class="form-text text-muted">Usted NO tiene Grupos registrados</small>
								<p><a href="http://cuentalo.org/registrar-una-historia?categoria=grupo" class='btn-primary'>Registrar un Grupo</a></p>
							<?php } else {?> 
								<label for="inputEmail4 ">Grupo de la Historia</label>
							  	<select id="g_his" name="g_his" class="form-control" >
							    	<option value="">Ninguno</option>
    								<?php foreach ($user_grupos as $ug) {
											$ug_id 	= $ug->ID;
											$ug_t 	= $ug->post_title;
											if ($ug_id==trim($grupo_historia)) {?>
												<option selected="selected" value="<?php echo $ug_id;?>"><?php echo $ug_t;?></option>		
											<?php } else {?>
												<option value="<?php echo $ug_id;?>"><?php echo $ug_t;?></option>
											<?php }
									}?>
								</select>	
								<small class="form-text text-muted">Seleccione el Grupo al que pertenece ésta historia</small>
   							<?php }?>												
						<?php } ?>	
					<?php } else {?>
						<?php if ($accion!='editar') { ?>
						   <?php echo jh_buscar_historias($post->ID); ?>
							<?php if ( $user == $a_nickname ) { 
								$editar	='http://cuentalo.org/registrar-una-historia/?pid='.$post_id.'&accion=editar';
								$reg	='http://cuentalo.org/registrar-una-historia/?grupo_his='.$id; ?>
								<p><a href="<?php echo $editar; ?>" class='btn-primary'>Editar éste Grupo</a></p>
								<p><a href="<?php echo $reg; ?>" class='btn-primary'>Registrar Historia al Grupo</a></p>
							<?php } ?>
						<?php } ?>
					<?php } ?>															
				</div>
				<div class="form-group">
					<label for="inputEmail4 "><?php echo $label_titulo ?></label>
					<?php if ($accion!='editar') { ?>
							<div class="form-control"><?php echo $titulo; ?></div>						 
					<?php } else { ?>
						<input type="text" name="titulo" id="titulo" required class="form-control" value ="<?php echo $titulo; ?>">
					<?php } ?>
					<small class="form-text text-muted"><?php echo $small_titulo ?></small>
				</div>

				<div class="form-group">
					<label for="exampleFormControlFile1">Imagen de portada</label>
					<?php if ($accion=='editar') { ?>
						<input type="file" class="form-control-file" name="imagen" id="imagen" multiple="false">
						<small class="form-text text-muted">Seleccione una imagen o si desea cambiar la actual</small>
					<?php } ?>	
					<?php wp_nonce_field( 'imagen', 'imagen_nonce' ); ?>
					<div class="img-thumb">
						<?php if ( ($pid !=0) && (has_post_thumbnail($post_id)) ) { 
							the_post_thumbnail( 'full', array( 'class' => 'card-img-top' ) );
						} ?>
					</div>					
				</div>
				  
				<div class="form-group">
					<label for="exampleFormControlTextarea1">Descripción</label>
					<?php if ($accion!='editar') { ?>
							<div class="form-control"><?php echo $descripcion; ?></div>					
					<?php } else { ?>
						<?php wp_editor($descripcion, $editor_id, $settings );    ?>
						<small class="form-text text-muted"><?php echo $small_descripcion ?></small>
					<?php } ?>
				</div>

				<div class="form-group">
					<label for="exampleFormControlTextarea1">Etiquetas</label>
					<?php if ($accion=='editar') { ?>
						<input type="text" name="etiquetas" class="form-control" value ="<?php echo $etiquetas ?>">
						<small class="form-text text-muted">Separar etiquetas con el signo coma ","</small>
					<?php } ?>
					<?php if ($etiquetas!='') { ?>										
						<div class="form-group">
							<i class="fa fa-tags" aria-hidden="true"></i> <?php the_tags( ' ', ' ', '<br />' ); ?>
						</div>
					<?php } ?>
				</div>
			  </div>
			</div>
			  <div class="col">
					<div class="bg-items p-5 grupo-campos card title-color-site">
						<h5 class="mb-4 font-weight-bold">Audiovisual</h5>
						<div class="form-group">
							<label for="video">Video</label>
							<?php if ($accion=='editar') { ?>								
								<input type="text" class="form-control" name="video" value ="<?php echo $video ?>">
								<small class="form-text text-muted">Coloque el enlace completo de youtube donde está el video</small>
							<?php } ?>										
							<?php if ($video!='') { ?>										
								<div class="img-thumb">
									<?php if (strlen($video) < 12) {
										echo wp_oembed_get( 'http://www.youtube.com/watch?v=' . $video,  array('width'=>400) );
									 } else {											
										echo wp_oembed_get( $video, array('width'=>400) );
									 } ?>													
								</div>		
							<?php } ?>			
						</div>
						<?php if ($categoria=='Historia') { ?>
							<div class="form-group">
								<label for="exampleFormControlTextarea1">Protagonistas</label>
								<?php if ($accion!='editar') { ?>
									<div class="form-control"><?php echo $protagonistas; ?></div>						 
								<?php } else { ?>					
									<input type="text" class="form-control" id="protagonistas" name="protagonistas" value ="<?php echo $protagonistas ?>">
									<small class="form-text text-muted">Separar Protagonistas con el signo coma ","</small>				
								<?php } ?>	
							</div>
							<div class="form-group">
                            	<label for="exampleFormControlTextarea1">Narativa digital</label>
								<?php if ($accion!='editar') { ?>
									<div class="form-control"><?php echo $narrativa; ?></div>						 
								<?php } else { ?>					
									<textarea class="form-control" id="narra" name="narra" rows="3"><?php echo $narrativa; ?></textarea>									
									<small class="form-text text-muted">Escriba aquí la narrativa de la Historia</small>
								<?php } ?>	
							</div>			
						<?php } ?>			
						<?php if ($categoria=='Grupo') { ?>
							<div class="form-group">
								<label for="exampleFormControlTextarea1">Población</label>
								<?php if ($accion!='editar') { ?>
									<div class="form-control"><?php echo $poblacion; ?></div>						 
								<?php } else { ?>					
									<textarea class="form-control" id="pobla" name="pobla" rows="3"><?php echo $poblacion; ?></textarea>
									<small class="form-text text-muted">Descripción detallada de la Población a la que va dirigida éste Grupo</small>	
								<?php } ?>	
							</div>
							<div class="form-group">
								<label for="exampleFormControlTextarea1">Dirección</label>
								<?php if ($accion!='editar') { ?>
									<div class="form-control"><?php echo $direccion; ?></div>		
								<?php } else { ?>					
									<textarea class="form-control" id="direc" name="direc" rows="3"><?php echo $direccion; ?></textarea>									
									<small class="form-text text-muted">Dirección exacta de la ubicación, y pistas sobre como llegar al lugar</small>
								<?php } ?>	
							</div>					
						<?php } ?>	
						<?php if ($lon!=0) {
								$rega	='http://cuentalo.org/mapas/?cat='.$cat_map; //$post_id; 
							?>
								<p><a href="<?php echo $rega; ?>" class='btn-primary'>Ver en el Mapa</a></p>
						<?php } ?>											 
						<?php if ($accion!='editar') { ?>
							<h5 class="mb-4 font-weight-bold">Datos de Contacto</h5>
							<div class="more-date card-text">
								<img src="<?php echo esc_url($a_avatar) ?>" />
								<p class="card-text"><small class="text-muted">Por: <?php echo $a_nickname ?></small></p>
								<p><i class="fa fa-envelope" aria-hidden="true"></i> <a href="mailto:<?php echo $a_email ?>"><?php echo $a_email ?></a></p>
								<p><i class="fa fa-globe" aria-hidden="true"></i> <a href="<?php echo $a_url ?>"><?php echo $a_url ?></a></p>
							</div>															
						<?php } ?>	
						
						<div class="form-group">
							<?php if ($accion=='editar') { ?>
								<input type="hidden" name="post_id" id="post_id" value="<?php echo $post_id ?>">
								<input type="hidden" name="categoria" id="categoria" value="<?php echo $categoria ?>">
								<input type="hidden" name="lon" id="lon" value="<?php echo $lon ?>">
								<input type="hidden" name="lat" id="lat" value="<?php echo $lat ?>">
								<input type="hidden" name="accion" id="accion" value="<?php echo $accion ?>">
								<input type="hidden" name="his_form">					
								<input type="submit" class="btn-primary" value="<?php echo $label_submit ?>"/>	
								<?php
  									$url_back = htmlspecialchars($_SERVER['HTTP_REFERER']);
  									echo "<p><a href='$url_back' class='btn-secondary'>       Regresar       </a></p>"; 
								?>								
							<?php } else { ?>
							<?php $url_back = htmlspecialchars($_SERVER['HTTP_REFERER']);
  								echo "<p><a href='$url_back' class='btn-secondary'>     Regresar     </a></p>"; ?>		
							<?php } ?>	
						</div>									
					 </div>
			  </div>
			</div>
		  </form>
								<div>
								<h5 class="mb-4 font-weight-bold">Compartir y Comentarios</h5>
								<div class="link-social-group pb-3">
									<a href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>?"><i class="fa fa-facebook-square"></i></a>
									<a href="http://twitter.com/share?text=&url=<?php the_permalink(); ?>"><i class="fa fa-twitter-square"></i></a>
									<a href="https://plus.google.com/share?url=<?php the_permalink(); ?>"><i class="fa fa-google"></i></a>						
								</div>
								<?php 
								if ( comments_open() || get_comments_number() ) :
									comments_template();
								endif;?>
							</div>							

	</div>
 </section>

<?php get_footer(); ?>