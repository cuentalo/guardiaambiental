<footer>
	<div class="container">
		<div class="row">
			<div class="col-12">
			<img src="<?php the_field('logo', 117); ?>" class="logo-fot">
			<h2><?php the_field('titulo_footer', 117); ?></h2>
			<p><?php the_field('descripcion_footer', 117); ?></p>
			<p>
				<i class="sprite sprite-fb"></i>
				<i class="sprite sprite-tw"></i>
				<i class="sprite sprite-yt"></i>
				<i class="sprite sprite-ig"></i>
			</p>
			<p><?php the_field('coyright', 117); ?></p>
		</div>
	</div>
	</div>
</footer>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>

<?php wp_footer(); ?>
</body>
</html>