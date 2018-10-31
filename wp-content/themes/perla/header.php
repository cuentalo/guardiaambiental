<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset');?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<title><?php wp_title('|', true, 'right');?> <?php bloginfo('name');?></title>
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php if ( is_singular() ) wp_enqueue_script( "comment-reply" ); ?>
	<?php wp_head(); ?>
	
</head>
<body <?php body_class(); ?>>
<?php 
	$user_msg = 'Entrar al sitio';
	global $current_user;
	$user = $current_user->user_login;				
	if ( $user ) { 
		$user_msg = 'Hola, '.$user;	
	} 
?>
<div id="io"></div> <!-- contenedor UIO  -->
<div class="mini-nav"> <!-- Mini Menu superio -->
	<div class="container">
		<div class="row">
			<div class="col-md-6 d-none d-md-block">				
				<div class="bar-info-tel">
					<ul >
						<li><i class="sprite sprite-tel"></i><span><?php the_field('telefono', 131); ?></span></li>
						<li><i class="sv"></i></li>
						<li><i class="sprite sprite-correo"></i><span><?php the_field('correo_electronico', 131); ?></span></li>
					</ul>
				</div>
			</div>
			<div class="col-md-6">
					<div class="bar-info-contac float-md-right">				
						<ul>
<?php
//<li><img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDE2LjAuMCwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPgo8IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHg9IjBweCIgeT0iMHB4IiB3aWR0aD0iMTZweCIgaGVpZ2h0PSIxNnB4IiB2aWV3Qm94PSIwIDAgMTI4IDEyOCIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgMTI4IDEyOCIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxnIGlkPSJVc2VyIj4KCTxnPgoJCTxwYXRoIGQ9Ik0xMTguOTUsOTIuMDEyYy0zLjc3OC0zLjc3Ny0xMC4xLTcuMDc0LTIwLjA2Ni0xMi4wNTljLTUuMDI0LTIuNTEyLTEzLjM4Ni02LjY5MS0xNS40MTMtOC42MDUgICAgYzguNDg5LTEwLjQzNCwxMy40MTYtMjIuMjE5LDEzLjQxNi0zMi41MzVjMC03LDAtMTUuNzExLTMuOTE4LTIzLjQ4Qzg5LjQzNyw4LjMzNiw4MS41NDQsMCw2NC4wMDIsMCAgICBDNDYuNDU2LDAsMzguNTYzLDguMzM2LDM1LjAzNSwxNS4zMzJjLTMuOTIzLDcuNzctMy45MjMsMTYuNDgtMy45MjMsMjMuNDhjMCwxMC4zMiw0LjkyMywyMi4xMDIsMTMuNDE3LDMyLjUzNSAgICBjLTIuMDMyLDEuOTE4LTEwLjM5Myw2LjA5OC0xNS40MTcsOC42MDVjLTkuOTYzLDQuOTg0LTE2LjI4NSw4LjI4MS0yMC4wNjYsMTIuMDU5Yy04LjM2OSw4LjM3NS05LjAwMiwyMi40MjYtOS4wNDUsMjUuMTYgICAgYy0wLjA0MywyLjg1MiwxLjA1OSw1LjYwOSwzLjA2Nyw3LjY0OGMyLDIuMDMxLDQuNzQzLDMuMTgsNy41OTUsMy4xOGgxMDYuNjY5YzIuODYsMCw1LjU5Ni0xLjE0OCw3LjYtMy4xOCAgICBjMi4wMDQtMi4wMzksMy4xMS00Ljc5NywzLjA2Ny03LjY1MkMxMjcuOTU2LDExNC40MzgsMTI3LjMxOCwxMDAuMzg3LDExOC45NSw5Mi4wMTJ6IE0xMTkuMjM1LDExOS4yMDMgICAgYy0wLjUwOCwwLjUxMi0xLjE4NCwwLjc5Ny0xLjkwMywwLjc5N0gxMC42NjNjLTAuNzA3LDAtMS4zOTgtMC4yODktMS44OTUtMC43OTdjLTAuNDk2LTAuNTA0LTAuNzc3LTEuMTk5LTAuNzctMS45MSAgICBjMC4wMjMtMS4zNCwwLjM5MS0xMy4zMDUsNi43MDUtMTkuNjIxYzIuOTE1LTIuOTE0LDkuMDE3LTYuMDc0LDE3Ljk4OC0xMC41NjNjOS41NzYtNC43ODUsMTQuODg2LTcuNjM3LDE3LjMzMi05Ljk0OWw1LjM5OS01LjEwNSAgICBsLTQuNjg4LTUuNzU4Yy03LjM4NC05LjA3LTExLjYyMy0xOS4wOS0xMS42MjMtMjcuNDg0YzAtNi40NzMsMC0xMy44MDUsMy4wNjMtMTkuODc1QzQ1Ljg0MiwxMS42OCw1My4xNzksOCw2NC4wMDIsOCAgICBjMTAuODE0LDAsMTguMTU5LDMuNjgsMjEuODI0LDEwLjkzNGMzLjA2Myw2LjA3NCwzLjA2MywxMy40MDYsMy4wNjMsMTkuODc5YzAsOC4zOTEtNC4yMzUsMTguNDEtMTEuNjI4LDI3LjQ4NGwtNC42ODgsNS43NjIgICAgbDUuNCw1LjEwMmMyLjQ0NSwyLjMwOSw3Ljc1MSw1LjE2LDE3LjMzMSw5Ljk0OWM4Ljk3MSw0LjQ4NCwxNS4wNzMsNy42NDUsMTcuOTg4LDEwLjU2M2M1LjEzOCw1LjEzNyw2LjYzNCwxNC43NSw2LjcwNCwxOS42MjEgICAgQzEyMC4wMDksMTE4LjAwNCwxMTkuNzMxLDExOC42OTksMTE5LjIzNSwxMTkuMjAzeiIgZmlsbD0iI0ZGRkZGRiIvPgoJPC9nPgo8L2c+Cjwvc3ZnPgo=" /> <a href="http://guardiaambiental.org/" >
?>
							<?php //echo $user_msg; 
							?>
							<?php //</a></li>
							?>
							<li><a href="<?php the_field('facebook_url', 131); ?>"><i class="sprite sprite-fb"></i></a></li>
							<li><a href="<?php the_field('instagram_url', 131); ?>"><i class="sprite sprite-ig"></i></a></li>						
							<li><a href="<?php the_field('twitter_url', 131); ?>"><i class="sprite sprite-tw"></i></a></li>
							<li><a href="<?php the_field('youtube_url', 131); ?>"><i class="sprite sprite-yt"></i></a></li>
							<li><span class="texto-contacto d-none d-lg-block">Envianos un E-mail</span></li>
							<li><a href="#" class="btn-blue d-none d-sm-block"><?php the_field('texto_boton_contacto', 131); ?></a></li>
						</ul>
					</div>
			</div>			
		</div>
	</div>
</div>

<header class="d-none d-md-block"> <!-- header -->
		<div class="container header-main">
			<div class="row">
				<div class="col-md-3">
					<a href="#"><img src="<?php the_field('logo_principal', 131); ?>" alt="" class="img-fluid"></a>
				</div>
				<div class="col-md-9 d-flex justify-content-end">
					<?php wp_nav_menu(array(
					'theme_location'  => 'menu-principal',
					'container'       => 'nav',
					'container_class' => 'menu-home',
					'items_wrap'      => '<ul id="%1$s" class="menu_navi">%3$s</ul>'));
				?>
				</div>
			</div>
		</div>
	</header>
<!-- MEnu para dispositivos moviles -->
<div class="d-block d-sm-block d-md-none">
	<header class="navbar navbar-expand-md navbar-light bg-light" role="navigation">
		<div class="container">
		<!-- Brand and toggle get grouped for better mobile display -->
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-controls="bs-example-navbar-collapse-1" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<a class="navbar-brand" href="#">Guardia Ambiental Colombiana</a>
			<?php
			wp_nav_menu( array(
				'theme_location'    => 'menu-principal',
				'depth'             => 2,
				'container'         => 'div',
				'container_class'   => 'collapse navbar-collapse',
				'container_id'      => 'bs-example-navbar-collapse-1',
				'menu_class'        => 'nav navbar-nav',
				'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
				'walker'            => new WP_Bootstrap_Navwalker(),
			) );
			?>
		</div>
	</header>
</div>
<div id="tabla-contenido"></div>