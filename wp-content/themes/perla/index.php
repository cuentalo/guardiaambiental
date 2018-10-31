<?php get_header(); ?>

<section class="slider-home">
   <?php 
    echo do_shortcode("[metaslider id=50]"); 
   ?>
</section>

<section class="programas-home">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <div class="title-programas">
                <h2>Áreas De Trabajo</h2>
                <h3>La Guardia  Ambiental  Colombiana ejecuta sus labores en las siguientes áreas</h3> 
            </div>                        
         </div>

         <?php
            $cprogramas = new WP_Query( 'category_name=programas' );
            if ( $cprogramas->have_posts() ) :
              while ( $cprogramas->have_posts() ): 
                   $cprogramas->the_post();
                   echo '<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">';
                     echo '<div class="perla-panel sombra">';
                        if ( has_post_thumbnail() ) { 
                              the_post_thumbnail( 'full', array( 'class' => 'img-fluid' ) );
                             }
                              echo '<div class="perla-panel-texto"><h3>',the_title(),'</h3></div></div></div>';
               endwhile;
            endif;
            wp_reset_query();
         ?>

      </div>
   </div>
</section>

   <section class="video-home">
    <div class="container">
      <div class="row">
        <div class="col-md-8">
          <div class="video-youtube">
          <iframe width="100%" height="450" src="https://www.youtube.com/embed/Z7jUO3Qn_no" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
          </div>
        </div>
          <div class="col-md-4">
            <div class="texto-video">
            <h2>Guardia Ambiental Audiovisual</h2>
            <p> Que es la guardia ambiental colombiana <a href="https://www.youtube.com/channel/UC-aSdjiVdO6LeKdus8iKnTQ" class="link-video"> Suscribete al canal</a></p>
          </div>
        </div>
      </div>      
    </div>
   </section>
  
<?php get_footer(); ?>