<?php get_template_part( 'parts/header' ); ?>

	<section class="main col-sm-8" role="main">
		<?php if ( !have_posts() ) { ?>
			<?php get_template_part( 'parts/no-posts' ); ?>
		<?php } ?>

		<?php while (have_posts()) : the_post(); ?>
			<?php get_template_part( 'content', get_post_format() ); ?>
		<?php endwhile; ?>

		<?php get_template_part( 'parts/pagination' ); ?>
	</section><!-- /.main -->

<?php get_sidebar(); ?>

<?php get_template_part( 'parts/footer' ); ?>