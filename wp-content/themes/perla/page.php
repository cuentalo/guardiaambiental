<?php

get_header(); ?>

<section class="container home-contenido">
		<section class="row">

		  	<article class="col-md-12">		  	
			  	<div class="noticias-actuales">
			  		<?php if (have_posts()) :  while (have_posts()) : the_post(); ?> 
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						
						<div class="texto-post"><?php the_content(); ?></div class="texto-post">
						<?php endwhile; else: ?>							  
					<?php endif; ?>			  		
				 </div>
			</article>
	    </section>
   </section>

<?php get_footer(); ?>
