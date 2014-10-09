<?php
if ( $wp_query->max_num_pages > 1 ) {
	global $wp_query;
	$big = 999999999; // need an unlikely integer
	$pages = paginate_links( array(
		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format' => '?paged=%#%',
		'current' => max( 1, get_query_var( 'paged' ) ),
		'total' => $wp_query->max_num_pages,
		'prev_next' => false,
		'type'  => 'array',
		'prev_next'   => TRUE,
		'prev_text'    => __( '«', 'dev7' ),
		'next_text'    => __( '»', 'dev7' )
	) );

	if ( is_array( $pages ) ) {
		$paged = ( get_query_var( 'paged' ) == 0 ) ? 1 : get_query_var( 'paged' ); ?>
		<ul class="pagination">
		<?php foreach ( $pages as $page ) { ?>
			<li><?php echo $page ?></li>
		<?php } ?>
		</ul>
		<?php
	}
}
?>