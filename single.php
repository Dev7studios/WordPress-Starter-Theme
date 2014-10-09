<?php get_template_part( 'parts/header' ); ?>

	<section class="main col-sm-8" role="main">
		<?php while (have_posts()) : the_post(); ?>
			<?php get_template_part( 'content', get_post_format() ); ?>
		<?php endwhile; ?>
	</section><!-- /.main -->

<?php get_sidebar(); ?>

<?php get_template_part( 'parts/footer' ); ?>