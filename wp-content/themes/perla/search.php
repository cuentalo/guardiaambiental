<?php get_header(); ?>
<?php
	$iscat = 'historia';
	if( !empty( get_query_var( 'categoria' ) ) ){
		if( get_query_var( 'categoria' )=='grupo') {
			$iscat = 'grupo';
		}		
	}
	$count = 0;
	$the_query = $GLOBALS['wp_query'];
	//global $wp_query;
	//if (have_posts()) {
		$count = sizeof( $the_query->posts );		
	//} 	
	global $current_user;
    //wp_get_currentuserinfo();
    $user = $current_user->user_login;

	//global $query_string;
	//$cuser = '?s='.get_search_query().'&post_type=post&autor='.$user;
	$cuser = 'http://cuentalo.org/?s=&post_type=post&autor='.$user;
	$msg = "";
	if ( $iscat == 'historia' ) {
		if ( $count == 0 ) { 
			$msg = "Nada para mostrar. Por favor, cambie los parametros de busqueda";
		}else if ( $count == 1 ) { 
			$msg = $count. " Historia";
		} else {
			$msg = $count. " Historias";
		} 
	}else{ 			 					 
		if ( $count == 0 ) { 
			$msg = "Nada para mostrar. Por favor, cambie los parametros de busqueda";
		}else if ( $count == 1 ) { 
			$msg = $count. " Grupo";
		} else {
			$msg = $count. " Grupos";
		} 
	}

?>

<section id="slider" class="slider-home py-1">
	<div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
              <?php get_search_form(); ?>	
            </div>
        </div>		
    </div>  
</section>

<section class="time-line py-5" id="b">
	<div class="container">	 	  	  
	<div class="menu-grupo py-2">
		<?php if ( $iscat == 'historia' ) { ?>					
			<a href="http://cuentalo.org/registrar-una-historia/"><i class="fa fa-plus" aria-hidden="true"></i> Registrar una Historia</a>		
			<a href="<?php echo $cuser.'&categoria=historia'; ?>"><i class="fa fa-search" aria-hidden="true"></i> Ver mis Historias</a>	
			<a href="http://cuentalo.org/?s=&post_type=post&categoria=historia"><i class="fa fa-search" aria-hidden="true"></i> Ver Todas las Historias</a>					
			<a href="http://cuentalo.org/mapas?cat=historia"><i class="fa fa-map" aria-hidden="true"></i> Mapa</a>
		<?php }else{ ?>			 					 
			<a href="http://cuentalo.org/registrar-una-historia?categoria=grupo"><i class="fa fa-plus" aria-hidden="true"></i> Registrar un Grupo</a>
			<a href="http://cuentalo.org/?s=&post_type=post&categoria=historia"><i class="fa fa-history" aria-hidden="true"></i> Ver todas las Historias</a>
			<a href="http://cuentalo.org/mapas?cat=grupo"><i class="fa fa-map" aria-hidden="true"></i> Mapa</a>	
			<a href="<?php echo $cuser.'&categoria=grupo'; ?>"><i class="fa fa-search" aria-hidden="true"></i> Ver mis Grupos</a>	
			<a href="http://cuentalo.org/?s=&post_type=post&categoria=grupo"><i class="fa fa-search" aria-hidden="true"></i> Ver Todos los Grupos</a>				 
		<?php } ?>			
	</div>
    <h2 class="title-h2 line-title pb-2 mt-5"><?php echo $msg; ?></h2>		
	<div class="item-tl my-4 p-3">
	<div class="row pb-5">
        <?php if (have_posts()) :  while (have_posts()) : the_post(); ?>
        	<div class="col-md-3">
				<div class="card mt-5" style="width: 100%;">
                	<div class="img-thumb">
                    	<?php if ( has_post_thumbnail() ) { 
                        	the_post_thumbnail( 'full', array( 'class' => 'w-100 h-100') );
                        } else {
                            echo('<img src="http://cuentalo.org/wp-content/themes/jrk_theme/images/no-img.png" alt="" class="img-fluid">');
						}?>                  
                   	</div>
                   	<div class="card-body">                          
                   		<h5 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                        <p class="card-text"><?php the_excerpt(); ?></p>
                        <p><span>Tags:</span> <?php the_tags( ' ', ' ', '<br />' ); ?> </p>
                    	
						<small>
							<?php if ( $iscat == 'grupo' ) { ?>
								<?php echo jh_buscar_historias($post->ID); ?>
							<?php } else {//Es una Historia. Entonces, buscar el Grupo al que pertenece 
							?>	
								<?php echo jh_buscar_grupo($post->ID); ?>
							
							<?php } ?>          
							</small>                  

                            <div class="more-date">                            
							  <p class="card-text"><small class="text-muted">Por: <?php the_author_meta('nickname'); ?></small></p>             
                              <p><i class="fa fa-envelope" aria-hidden="true"></i> <a href="mailto:<?php the_author_meta('user_email'); ?>"><?php the_author_meta('user_email'); ?></a></p>
                              <p><i class="fa fa-globe" aria-hidden="true"></i> <a href="<?php the_author_meta('user_url'); ?>"><?php the_author_meta('user_url'); ?></a></p>
                            </div>
							
							<div class="link-social-group pb-3">
								<a href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>?"><i class="fa fa-facebook-square"></i></a>
								<a href="http://twitter.com/share?text=&url=<?php the_permalink(); ?>"><i class="fa fa-twitter-square"></i></a>
								<a href="https://plus.google.com/share?url=<?php the_permalink(); ?>"><i class="fa fa-google"></i></a>
								<?php 
								$comm = 'Comentarios';
								if ( comments_open() || get_comments_number() ) :
									$comm .= '(' . get_comments_number() . ')';
								endif;?>															
								<a href="<?php the_permalink(); ?>#comments"><small class="card-link"><?php echo $comm ?></small></a>									
								<a href="<?php the_permalink(); ?>#comments" ><small class="card-link"><?php echo average_rating(); ?></small></a>
							</div>														
                            
                          <div>
							<?php if ( $user == get_the_author_meta('nickname')) { 
									$editar='http://cuentalo.org/registrar-una-historia/?pid='.$post->ID.'&accion=editar';?>
									<a href="<?php echo $editar; ?>">Editar <?php echo $iscat; ?></a>		
									<?php if ( $iscat == 'grupo' ) { ?>
										<a href="http://cuentalo.org/registrar-una-historia/?grupo_his=<?php echo $id; ?>"><i class="fa fa-plus" aria-hidden="true"></i> Historia</a>							  
							  		<?php } ?>		
							<?php } ?>
                          </div>
                      </div>
					</div>
                  </div>                
                
                <?php endwhile; else: ?>                
              <?php endif; ?>                     
		</div> 
      </div>
  </div> 
</section>
<?php get_footer(); ?>
