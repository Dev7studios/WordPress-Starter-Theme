<?php
if ( post_password_required() ) {
	return;
}
?>

<div id="comments">
	<?php if ( have_comments() ) { ?>
		<h3 class="comments-title"><?php comments_number( __( 'No Comments', 'dev7' ), __( 'One Comment', 'dev7' ), __( '% Comments', 'dev7' ) ); ?></h3>

		<ol class="media-list">
			<?php wp_list_comments(array('walker' => new Dev7_Walker_Comment)); ?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { ?>
			<nav>
				<ul class="pager">
					<?php if ( get_previous_comments_link() ) { ?>
						<li class="previous"><?php previous_comments_link( __( '&larr; Older comments', 'dev7' ) ); ?></li>
					<?php } ?>
					<?php if ( get_next_comments_link() ) { ?>
						<li class="next"><?php next_comments_link( __( 'Newer comments &rarr;', 'dev7' ) ); ?></li>
					<?php } ?>
				</ul>
			</nav>
		<?php } ?>
	<?php } ?>
</div><!-- /#comments -->

<div id="respond">
	<?php if (comments_open()) { ?>
		<h3><?php comment_form_title( __( 'Leave a Reply', 'dev7' ), __( 'Leave a Reply to %s', 'dev7' ) ); ?></h3>
		<p class="cancel-comment-reply"><?php cancel_comment_reply_link(); ?></p>
		<?php if ( get_option( 'comment_registration' ) && !is_user_logged_in() ) { ?>
			<p><?php printf( __( 'You must be <a href="%s">logged in</a> to post a comment.', 'dev7' ), wp_login_url( get_permalink() ) ); ?></p>
		<?php } else { ?>
			<form action="<?php echo get_option( 'siteurl' ); ?>/wp-comments-post.php" method="post" id="commentform">
				<?php if ( is_user_logged_in() ) { ?>
					<p>
						<?php printf( __( 'Logged in as <a href="%s/wp-admin/profile.php">%s</a>.', 'dev7' ), get_option( 'siteurl' ), $user_identity ); ?>
						<a href="<?php echo wp_logout_url( get_permalink() ); ?>" title="<?php _e( 'Log out of this account', 'dev7' ); ?>"><?php _e( 'Log out &raquo;', 'dev7' ); ?></a>
					</p>
				<?php } else { ?>
					<div class="form-group">
						<label for="author"><?php _e( 'Name', 'dev7' ); if ( $req ) _e( ' (required)', 'dev7' ); ?></label>
						<input type="text" class="form-control" name="author" id="author" value="<?php echo esc_attr( $comment_author ); ?>" size="22" <?php if ( $req ) echo 'aria-required="true"'; ?>>
					</div>
					<div class="form-group">
						<label for="email"><?php _e( 'Email (will not be published)', 'dev7' ); if ( $req ) _e( ' (required)', 'dev7' ); ?></label>
						<input type="email" class="form-control" name="email" id="email" value="<?php echo esc_attr( $comment_author_email ); ?>" size="22" <?php if ( $req ) echo 'aria-required="true"'; ?>>
					</div>
					<div class="form-group">
						<label for="url"><?php _e( 'Website', 'dev7' ); ?></label>
						<input type="url" class="form-control" name="url" id="url" value="<?php echo esc_attr( $comment_author_url ); ?>" size="22">
					</div>
				<?php } ?>
				<div class="form-group">
					<label for="comment"><?php _e( 'Comment', 'dev7' ); ?></label>
					<textarea name="comment" id="comment" class="form-control" rows="5" aria-required="true"></textarea>
				</div>
				<p><input name="submit" class="btn btn-primary" type="submit" id="submit" value="<?php _e( 'Submit Comment', 'dev7' ); ?>"></p>
				<?php comment_id_fields(); ?>
				<?php do_action( 'comment_form', $post->ID ); ?>
			</form>
		<?php } ?>
	<?php } ?>
</div><!-- /#respond -->