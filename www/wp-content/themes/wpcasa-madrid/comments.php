<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to wpsight_madrid_comment() which is
 * located in the inc/template-tags.php file.
 *
 * @package WPCasa Madrid
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() )
	return;
?>

	<div id="comments" class="comments-area">

	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>

		<h4 class="comments-title title">
			<?php
				printf( _nx( 'One Thought on &ldquo;%2$s&rdquo;', '%1$s Thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'wpcasa-madrid' ),
					number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
			?>
		</h4>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		
		<nav id="comment-nav-above" class="comment-navigation" role="navigation">
			<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'wpcasa-madrid' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'wpcasa-madrid' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'wpcasa-madrid' ) ); ?></div>
		</nav><!-- #comment-nav-above -->

		<?php endif; // check for comment navigation ?>

		<ol class="comment-list">
			<?php
				/* Loop through and list the comments. Tell wp_list_comments()
				 * to use wpsight_madrid_comment() to format the comments.
				 * If you want to override this in a child theme, then you can
				 * define wpsight_madrid_comment() and that will be used instead.
				 * See wpsight_madrid_comment() in inc/template-tags.php for more.
				 */
				wp_list_comments( array( 'callback' => 'wpsight_madrid_comment', 'avatar_size' => 75 ) );
			?>
		</ol><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		
		<nav id="comment-nav-below" class="comment-navigation" role="navigation">
			<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'wpcasa-madrid' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'wpcasa-madrid' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'wpcasa-madrid' ) ); ?></div>
		</nav><!-- #comment-nav-below -->

		<?php endif; // check for comment navigation ?>

	<?php endif; // have_comments() ?>

	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php _e( 'Comments are closed.', 'wpcasa-madrid' ); ?></p>
	<?php endif; ?>
	
	<?php
		global $user_identity, $id;
		
		$commenter = wp_get_current_commenter();
		
		// Check if name and email are required
		$req = get_option( 'require_name_email' );
		
		// Add support for Accessible Rich Internet Applications
		$aria_req = ( $req ? ' aria-required="true"' : '' );
		
		$args = array(
		
		    'fields' => array(
		    
		    	'author' =>	
		    	
		    		'<p class="form-group comment-form-section comment-form-author">' .
		    		'<label for="author">' . __( 'Name', 'wpcasa-madrid' ) . ( $req ? '<span class="required">*</span>' : '' ) . '</label> ' .
		    		'<input id="author" class="form-control" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" tabindex="1"' . $aria_req . ' />' .
		    		'</p><!-- .comment-form-author -->',
		
		    	'email' =>	
		    	
		    		'<p class="form-group comment-form-section comment-form-email">' .
		    		'<label for="email">' . __( 'Email', 'wpcasa-madrid' ) . ( $req ? '<span class="required">*</span>' : '' ) . '</label> ' .
		    		'<input id="email" class="form-control" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" tabindex="2"' . $aria_req . ' />' .
		    		'</p><!-- .comment-form-email -->',
		
		    	'url' =>		
		    	
		    		'<p class="form-group comment-form-section comment-form-url">' .
		    		'<label for="url">' . __( 'Website', 'wpcasa-madrid' ) . '</label>' .
		    		'<input id="url" class="form-control" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" tabindex="3" />' .
		    		'</p><!-- .comment-form-url -->'
		    ),
		
		    'comment_field' =>	
		    
		        '<p class="comment-form-section comment-form-comment">' .
		        '<label for="comment">' . __( 'Comment', 'wpcasa-madrid' ) . '<span class="required">*</span></label> ' .
		        '<textarea id="comment" class="form-control" name="comment" cols="45" rows="8" tabindex="4" aria-required="true"></textarea>' .
		        '</p><!-- .comment-form-comment -->',
		        
		    'must_log_in' =>
		    
		    	'<div class="alert must-log-in">' .  sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.', 'wpcasa-madrid' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</div>',
		    	
		    'logged_in_as' =>
		    
		    	'<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'wpcasa-madrid' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</p>',
		
			'title_reply_before'	=> '<h4 id="reply-title" class="comment-reply-title">',
			'title_reply_after'		=> '</h4>',
		    'title_reply'			=> __( 'Leave a Comment', 'wpcasa-madrid' ),
		    'comment_notes_before'	=> '<p class="comment-notes">' . __( 'Your email address will not be published.', 'wpcasa-madrid' ) . '</p>',
		    'comment_notes_after'	=> '',
		    'cancel_reply_link'		=> __( 'Cancel reply', 'wpcasa-madrid' ),
		    'class_submit'			=> 'btn btn-primary',
		    'id_submit'				=> 'submit',
		    'label_submit'			=> __( 'Post Comment', 'wpcasa-madrid' )
		    
		);
		
		$args = apply_filters( 'wpsight_madrid_comment_form_args', $args, $user_identity, $id, $commenter, $req, $aria_req );
		
		// Finally output comment form
		comment_form( $args, $id );
	?>

</div><!-- #comments -->