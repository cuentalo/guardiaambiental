<?php
/*
Template Name: Login
*/
?>
<?php get_header(); ?>

<?php
global $current_user;
$user 			= $current_user->user_login;
$site_url		= 'http://guardiaambiental.org/'; //get_site_url();
$mi_url			= $site_url.'login';
$redirect_to	= $site_url.'registrar-usuario';   //'http://cuentalo.org/registrar-usuario'
if ( $user )  {
	wp_safe_redirect( $redirect_to );			
}
$url			= $redirect_to.'?accion=agregar'; //Link para registrarse
//$redirect_to	= 'http://cuentalo.org/?s=&post_type=post&categoria=historia'; //Redirigir despues de Login
$redirect_to	= $site_url; //'http://cuentalo.org/';
$nickname		= "";
$pass			= "";
$rec			= "";
if(isset($_POST['login_Submit'])) {
	$creds                  = array();
	$creds['user_login']    = stripslashes( trim( $_POST['user_login'] ) );
	$creds['user_password'] = stripslashes( trim( $_POST['user_pass'] ) );
	$creds['remember']      = isset( $_POST['rememberMe'] ) ? sanitize_text_field( $_POST['rememberMe'] ) : '';
	$redirect_to            = esc_url_raw( $_POST['redirect_to'] );
	$secure_cookie          = null;
		
	if($redirect_to == '')
		$redirect_to= get_site_url(). '/dashboard/' ; 
		
		if ( ! force_ssl_admin() ) {
			$user = is_email( $creds['user_login'] ) ? get_user_by( 'email', $creds['user_login'] ) : get_user_by( 'login', sanitize_user( $creds['user_login'] ) );

		if ( $user && get_user_option( 'use_ssl', $user->ID ) ) {
			$secure_cookie = true;
			force_ssl_admin( true );
		}
	}

	if ( force_ssl_admin() ) {
		$secure_cookie = true;
	}

	if ( is_null( $secure_cookie ) && force_ssl_admin() ) {
		$secure_cookie = false;
	}

	$user = wp_signon( $creds, $secure_cookie );
	//$user = wp_authenticate($creds['user_login'], $creds['user_password']);
	
	if ( $secure_cookie && strstr( $redirect_to, 'wp-admin' ) ) {
		$redirect_to = str_replace( 'http:', 'https:', $redirect_to );
	}

	if ( ! is_wp_error( $user ) ) {
		//wp_set_auth_cookie($user->ID, $creds['remember'], $secure_cookie);
		//do_action('wp_login', $user->user_login);
		
		//wp_set_current_user($user->ID);
		wp_safe_redirect( $redirect_to );			
	} else {			
		if ( $user->errors ) {
			$errors['invalid_user'] = __('<strong>ERROR</strong>: Usuario o clave incorrecta.'); 
		} else {
			$errors['invalid_user_credentials'] = __( 'Por favor, entre su nombre de usuario y clave para entrar al sitio' );
		}
	}		 
}
?>

<?php if(!empty($errors)) {  //  to print errors,
	foreach($errors as $err )
	echo $err; 
} ?>
<section class="py-5 bg-gris">
	<div class="container">		
		<form name="loginform" method="post" action="<?php echo $mi_url; ?>">
			<h2 class="pb-1 mb-3 line-title2 font-weight-bold">Entrar al sitio</h2>
			<div class="row">
			  <div class="col">
			  	<div class="bg-items p-5 grupo-campos card title-color-site">
					<div class="form-group">
						<label for="inputEmail4 ">Nombre de Usuario</label>
						<input type="text" name="user_login" id="user_login" class="form-control" value ="<?php echo $nickname; ?>">		
                	</div>
	                <div class="form-group">
    	                <label for="inputEmail4 ">Contraseña</label>	
						<input type="password" name="user_pass" id="user_pass" class="form-control" value ="<?php echo $pass; ?>">
        	        </div>			
				    <div class="form-group">
    	                <label for="inputEmail4 "><input name="rememberMe" type="checkbox" id="rememberme" class="form-control" value ="<?php echo $rec; ?>"> Recuerdame</label>	
        	        </div>							
					<div class="form-group">
						<input type="hidden" name="login_Submit" >
						<input type="submit" class="btn-primary" value="Entrar al sitio"/>	
						<input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>">
					</div>	
				</div>
			</div>
			  <div class="col">
					<div class="bg-items p-5 grupo-campos card title-color-site">
						<h5 class="mb-4 font-weight-bold">Registrarme como Usuario</h5>
						<div class="form-group">
							<label for="inputEmail4">Para participar en actividades dentro del sitio, usted necesita tener una cuenta.</label>		
						</div>																	
						<div class="form-group">
							<label for="inputEmail4">Cree la cuenta haciendo un click en el link "Crear cuenta" y luego diligencie un sencillo formulario</label>
							<p><a href="<?php echo $url ?>" class="btn-primary">Crear cuenta</a></p>
						</div>
					 </div>
			  </div>																	
		</div>
	    </form>		
	</div>
</section>

<?php get_footer(); ?>
