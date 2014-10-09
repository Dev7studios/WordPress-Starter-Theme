<?php get_template_part( 'parts/header' ); ?>

	<section class="main col-sm-8" role="main">
		<div class="alert alert-warning">
			<?php _e( 'Sorry, but the page you were trying to view does not exist.', 'dev7' ); ?>
		</div>
		<?php get_search_form(); ?>
	</section><!-- /.main -->

<?php get_sidebar(); ?>

<?php get_template_part( 'parts/footer' ); ?>