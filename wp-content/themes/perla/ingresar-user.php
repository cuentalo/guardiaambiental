<?php
/*
Template Name: Agregar Usuario
*/
?>
<?php get_header(); ?>
<?php

global $current_user;
$user 			= $current_user->user_login;
$nickname		= "";
$pass			= "";
$avatar 		= "";
$name			= "";
$nombres		= "";
$apellidos		= "";
$descripcion	= "";
$email			= "";
$url			= "";
// Tipo de Accion: 0: Ver, 1: Agregar, 2: Editar
$tipo_accion 	= 0; 
// El sgte link se utiliza para refrescar la pagina o para crear el enlace de Editar
$site_url		= get_site_url().'/';
$redirect_to	= $site_url.'registrar-usuario';
$mi_url			= $redirect_to;
//$redirect_to	= 'http://cuentalo.org/registrar-usuario'; 
// Resultado del POST
$success		= '';
$error 			= '';
global $wpdb, $PasswordHash, $user_ID;
 
if (isset($_POST['action']) && $_POST['action'] == 'usuario_form' ) {
	if (!$user) { //Es mejor $user_ID?
		$tipo_accion	= 1;	
	} else {
		$tipo_accion	= 2;	
	}	
	$username 	= stripslashes(trim($_POST['nickname']));
	$password1 	= stripslashes(trim($_POST['pass'])); 
	$first_name	= stripslashes(trim($_POST['nombres']));
	$last_name 	= stripslashes(trim($_POST['apellidos']));
	$email 		= stripslashes(trim($_POST['email']));
	$desc 		= stripslashes(trim($_POST['descripcion']));
	$url		= stripslashes(trim($_POST['url']));
	$rol		= stripslashes(trim($_POST['rol']));
	if( $email == "" ) {
		$error = 'Es requerido un Email. Por favor escriba uno';
	} else if( ($tipo_accion==1) && ($password1 == "")) {
		$error = 'Es requerido una Clave. Por favor escriba una';
	} else if( ($tipo_accion==1) && ($username == "")) {
		$error = 'Es requerido un Nombre de usuario. Por favor escriba uno';
	} else if( $first_name == "") {
		$error = 'Es requerido un Nombre. Por favor escriba uno';
	} else if( $last_name == "") {
		$error = 'Es requerido un Apellido. Por favor escriba uno';
	} else if (($tipo_accion==1) && (username_exists($username))){
		$error = 'El nombre de usuario YA existe. Por favor, escriba otro nombre de usuario';
	} else if (($tipo_accion==1) && (strlen($username) < 6)){
		$error = 'Nombre de usuario demasiado corto. Se requiere por lo menos 6 caracteres';
	} else if (($tipo_accion==1) && (!validate_username( $username ))){
		$error = 'El nombre de usuario no es valido';
	} else if (($tipo_accion==1) && (strlen($password1) < 6)){
		$error = 'La clave es demasiado corta. Se requiere por lo menos 6 caracteres';	
	} else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$error = 'Email invalido.';
	} else if(($tipo_accion==1) && (email_exists($email))) {
		$error = 'Email YA existe. Por favor, escriba otro email';
	} else {		
		if ($tipo_accion==1) { // Nuevo usuario
			$args = array (
				'user_login' 	=> apply_filters('pre_user_user_login', $username), 
				'user_pass' 	=> apply_filters('pre_user_user_pass', $password1), 
				'first_name' 	=> apply_filters('pre_user_first_name', $first_name), 
				'last_name'		=> apply_filters('pre_user_last_name', $last_name), 								
				'user_email' 	=> apply_filters('pre_user_user_email', $email), 
				'description' 	=> apply_filters('pre_user_description', $desc), 
				'user_url'		=> $url  
			);
		} else { //Actualizando datos
			$user_id 	= get_current_user_id();
			$args = array (
				'ID'			=> $user_id, 
				'user_login' 	=> apply_filters('pre_user_user_login', $username), 
				'first_name' 	=> apply_filters('pre_user_first_name', $first_name), 
				'last_name'		=> apply_filters('pre_user_last_name', $last_name), 								
				'user_email' 	=> apply_filters('pre_user_user_email', $email), 
				'description' 	=> apply_filters('pre_user_description', $desc), 
				'user_url'		=> $url  
			);			
		}
		$user_id 	= wp_insert_user($args);
		if( is_wp_error($user_id) ) {
			$error 			= 'Error al crear el usuario.';	
		} else {				
			if ($tipo_accion==1) {
				$success 		= 'Su cuenta ha sido exitosamente registrada. Ya esta dentro del sitio.';
				// Login de nuevo, porque al actualizar el usuario borra el cache
				//$user = is_email($username) ? get_user_by( 'email', $username) : get_user_by( 'login', sanitize_user( $username) );
				//$user = get_userdatabylogin( $username );
        		//$user_id = $user->ID;
        		wp_set_current_user( $user_id, $username );
        		wp_set_auth_cookie( $user_id );
        		do_action( 'wp_login', $username );
				// Usuario ha sido Login
			} else {
				$success 		= 'Su cuenta ha sido exitosamente actualizada';						
			}
			//Refrescamos la pagina
			wp_safe_redirect( $redirect_to );
		}	
	}	
	$pass 			= $password1;
	$nombres 		= $first_name;
	$apellidos		= $last_name;
	$nickname		= $username;
	$descripcion 	= $desc;
} else  {	
	if (!$user) {
		if (isset($_GET['accion']) && ($_GET['accion'] == 'agregar')) {
			$tipo_accion 	= 1;
		} else {
			// Si no hay hay una sesion de usuario abierta y no es agregar entonces lo llevamos a hacer Login
			// En Login tiene la opcion de Registrarse o re-generar la clave
			$redirect_to	= $site_url.'login'; //'http://cuentalo.org/login';
			wp_safe_redirect( $redirect_to );			
		}	
	} else {
		// Tenemos un usuario actual
		// Debemos obtener sus datos, ya sea para mostrar o para editarlos
		if (isset($_GET['accion']) && ($_GET['accion'] == 'editar')) {
			$tipo_accion 	= 2;			
		} else {
			$tipo_accion 	= 0;			
		}
		$user_id 	= Get_current_user_id();
		$nickname	= get_the_author_meta('nickname', $user_id);
		$pass		= get_the_author_meta('user_pass', $user_id);
		$avatar 	= get_avatar_url( $user_id, 32, '/images/no_images.jpg', $nickname);  
		$name		= get_the_author_meta('display_name', $user_id);
		$nombres	= get_the_author_meta('first_name', $user_id);
		$apellidos	= get_the_author_meta('last_name', $user_id);
		$descripcion = get_the_author_meta('description', $user_id);
		$email		= get_the_author_meta('user_email', $user_id);
		$url		= get_the_author_meta('user_url', $user_id);				
	}
}
if ($tipo_accion==0){
	$accion			= 'ver'; 
	$pagina_titulo	= 'Mi Cuenta  ';
	$label_submit 	= 'Regresar ';			
} else if ($tipo_accion==1){
	$accion			= 'editar'; 
	$pagina_titulo	= 'Crear una cuenta';
	$label_submit 	= 'Registrarme';			
} else {
	$accion			= 'editar'; 
	$pagina_titulo	= 'Editar Mis datos';
	$label_submit 	= 'Actualizar Mis datos';				
}
?>

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
		<form name="registrarform" action="<?php echo $mi_url; ?>" method="post" enctype="multipart/form-data">
			<h2 class="pb-1 mb-3 line-title2 font-weight-bold"><?php echo $pagina_titulo ?></h2>
			<div class="row">
			  <div class="col">
			  	<div class="bg-items p-5 grupo-campos card title-color-site">
					<?php if ($accion!='entrar') { ?>
						<h5 class="mb-4 font-weight-bold">Datos personales</h5>
					<?php } ?>					
				<div class="form-group">
					<label for="inputEmail4 ">Nombre de Usuario</label>
					<?php if ($tipo_accion==1) { ?>
						<input type="text" name="nickname" id="nickname" class="form-control" value ="<?php echo $nickname; ?>">		
						<small class="form-text text-muted">Nickname del usuario</small>
					<?php } else { ?>
						<input type="hidden" name="nickname" id="nickname" value ="<?php echo $nickname; ?>">		
						<div class="form-control"><?php echo $nickname; ?></div>						 
					<?php } ?>					
                </div>
                <div class="form-group">
                    <label for="inputEmail4 ">Contraseña</label>	
					<?php if ($tipo_accion==1) { ?>
						<input type="password" name="pass" id="pass" class="form-control" value ="<?php echo $pass; ?>">
						<small class="form-text text-muted">Contraseña o clave del usuario</small>
					<?php } else { ?>
						<input type="hidden" name="pass" id="pass" value ="<?php echo $pass; ?>">
						<div class="form-control">********</div>						 
					<?php } ?>									
                </div>
				<div class="form-group">
					<label for="exampleFormControlFile1">Imagen de perfil</label>					
					<?php if ($accion=='editar') { ?>
						<input type="file" class="form-control-file" name="avatar" id="avatar">
						<small class="form-text text-muted">Seleccione una foto para actualizar su perfil</small>
					<?php } else { ?>
						<div class="form-control"><img src="<?php echo esc_url($avatar) ?>" /></div>	
					<?php } ?>														
				</div>					
                <div class="form-group">
                    <label for="inputEmail4 ">Nombres</label>
					<?php if ($accion=='editar') { ?>
						<input type="text" name="nombres" id="nombres" class="form-control" value ="<?php echo $nombres; ?>">		
						<small class="form-text text-muted">Nombre del usuario</small>
					<?php } else { ?>
						<div class="form-control"><?php echo $nombres; ?></div>						 
					<?php } ?>
                </div>                
                <div class="form-group">
                    <label for="inputEmail4 ">Apellidos</label>
					<?php if ($accion=='editar') { ?>
						<input type="text" name="apellidos" id="apellidos" class="form-control" value ="<?php echo $apellidos; ?>">		
						<small class="form-text text-muted">Nombres del usuario</small>
					<?php } else { ?>
						<div class="form-control"><?php echo $apellidos; ?></div>						 
					<?php } ?>
                </div>
				<div class="form-group">
					<label for="exampleFormControlTextarea1">Información biográfica</label>
					<?php if ($accion=='editar') { ?>
						<textarea name="descripcion" id="descripcion" class="form-control" rows="3"><?php echo $descripcion; ?></textarea>
						<small class="form-text text-muted">Haga una descripcion de su vida</small>
					<?php } else { ?>
						<div class="form-control"><?php echo $descripcion; ?></div>						 
					<?php } ?>							
				</div>					
			  </div>
 	
			</div>
			  <div class="col">
					<div class="bg-items p-5 grupo-campos card title-color-site">
			<?php if ($accion!='entrar') { ?>						
						<h5 class="mb-4 font-weight-bold">Información de Contacto</h5>
						<div class="form-group">
							<label for="inputEmail4">Correo Electrónico</label>					
							<?php if ($accion=='editar') { ?>
								<input type="text" name="email" id="email" class="form-control" value ="<?php echo $email; ?>">	
								<small class="form-text text-muted">Escriba un correo electronico valido</small>
							<?php } else { ?>								
								<div class="form-control"><p><i class="fa fa-envelope" aria-hidden="true"></i> <a href="mailto:<?php echo $email ?>"><?php echo $email ?></a></p></div>						 
							<?php } ?>					
						</div>																	
						<div class="form-group">
							<label for="exampleFormControlTextarea1">URL Pagina Web</label>
							<?php if ($accion=='editar') { ?>
								<input type="text" name="url" id="url" class="form-control" value ="<?php echo $url; ?>">	
								<small class="form-text text-muted">Escriba un URL de su pagina web</small>
							<?php } else { ?>
								<div class="form-control"><p><i class="fa fa-globe" aria-hidden="true"></i> <a href="<?php echo $url ?>"><?php echo $url ?></a></p></div>								
							<?php } ?>							
						</div>
						<div class="form-group">
							<?php if ($accion=='editar') { ?>
								<input type="hidden" name="action" value="usuario_form">					
								<input type="hidden" name="rol" value="Colaborador">					
								<input type="submit" class="btn-primary" value="<?php echo $label_submit ?>"/>																<button type="button" class="btn-secondary">Cancelar</button>
							<?php } else { ?>
								<p><a href="<?php echo wp_logout_url(); ?>">Desconectar</a></p>
								<p><a href="<?php echo $redirect_to.'?accion=editar'; ?>">Editar</a></p>
								<?php
  									$url_back = htmlspecialchars($_SERVER['HTTP_REFERER']);
  									echo "<p><a href='$url_back'>Regresar</a></p>"; 
								?>								
							<?php } ?>	
						</div>									
				<?php } else { ?>										
						<h5 class="mb-4 font-weight-bold"></h5>
				<?php } ?>	
					 </div>
			  </div>
			</div>
		  </form>
	</div>
   </section>

<?php get_footer(); ?>
