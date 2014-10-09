<?php
/*
Template Name: Full Width
*/
get_template_part( 'parts/header' ); ?>

	<section class="main" role="main">
		<?php the_content(); ?>
		<?php wp_link_pages(array('before' => '<nav class="pagination">', 'after' => '</nav>')); ?>
	</section><!-- /.main -->

<?php get_template_part( 'parts/footer' ); ?>