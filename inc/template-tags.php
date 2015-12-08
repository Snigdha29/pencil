<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package pencil
 */

if ( ! function_exists( 'the_posts_navigation' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 */
function the_posts_navigation() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation posts-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php esc_html_e( 'Posts navigation', 'pencil' ); ?></h2>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( esc_html__( 'Older posts', 'pencil' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( esc_html__( 'Newer posts', 'pencil' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'the_post_navigation' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 */
function the_post_navigation() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'pencil' ); ?></h2>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="nav-previous">%link</div>', '%title' );
				next_post_link( '<div class="nav-next">%link</div>', '%title' );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'pencil_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function pencil_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	/*if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}*/

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) )
                //esc_html( get_the_date() )//,
		//esc_attr( get_the_modified_date( 'c' ) ),
		//esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		esc_html_x( '%s ago', 'post date', 'pencil' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		esc_html_x( ' by %s', 'post author', 'pencil' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);
        
        if ( is_singular() ) {
        
            echo '<div class="author-avatar">' . get_avatar( get_the_author_meta( 'ID' ) ) . '</div><span class="byline"> ' . $byline . '</span><span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.
        
        } else {
            
            echo '<span class="byline"> ' . $byline . '</span><span class="posted-on"> / ' . $posted_on . '</span>';
            
        }
}
endif;

if ( ! function_exists( 'pencil_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function pencil_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		//$categories_list = get_the_category_list( esc_html__( ', ', 'pencil' ) );
		//if ( $categories_list && pencil_categorized_blog() ) {
		//	printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'pencil' ) . '</span>', $categories_list ); // WPCS: XSS OK.
		//}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html__( ', ', 'pencil' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . esc_html__( 'Tagged: %1$s', 'pencil' ) . '</span>', $tags_list ); // WPCS: XSS OK.
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( esc_html__( 'Leave a comment', 'pencil' ), esc_html__( '1 Comment', 'pencil' ), esc_html__( '% Comments', 'pencil' ) );
		echo '</span>';
	}

	edit_post_link( esc_html__( 'Edit', 'pencil' ), '<span class="edit-link">', '</span>' );
}
endif;

if ( ! function_exists( 'the_archive_title' ) ) :
/**
 * Shim for `the_archive_title()`.
 *
 * Display the archive title based on the queried object.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the title. Default empty.
 * @param string $after  Optional. Content to append to the title. Default empty.
 */
function the_archive_title( $before = '', $after = '' ) {
	if ( is_category() ) {
		$title = sprintf( esc_html__( 'Category: %s', 'pencil' ), single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		$title = sprintf( esc_html__( 'Tag: %s', 'pencil' ), single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		$title = sprintf( esc_html__( 'Author: %s', 'pencil' ), '<span class="vcard">' . get_the_author() . '</span>' );
	} elseif ( is_year() ) {
		$title = sprintf( esc_html__( 'Year: %s', 'pencil' ), get_the_date( esc_html_x( 'Y', 'yearly archives date format', 'pencil' ) ) );
	} elseif ( is_month() ) {
		$title = sprintf( esc_html__( 'Month: %s', 'pencil' ), get_the_date( esc_html_x( 'F Y', 'monthly archives date format', 'pencil' ) ) );
	} elseif ( is_day() ) {
		$title = sprintf( esc_html__( 'Day: %s', 'pencil' ), get_the_date( esc_html_x( 'F j, Y', 'daily archives date format', 'pencil' ) ) );
	} elseif ( is_tax( 'post_format' ) ) {
		if ( is_tax( 'post_format', 'post-format-aside' ) ) {
			$title = esc_html_x( 'Asides', 'post format archive title', 'pencil' );
		} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
			$title = esc_html_x( 'Galleries', 'post format archive title', 'pencil' );
		} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
			$title = esc_html_x( 'Images', 'post format archive title', 'pencil' );
		} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
			$title = esc_html_x( 'Videos', 'post format archive title', 'pencil' );
		} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
			$title = esc_html_x( 'Quotes', 'post format archive title', 'pencil' );
		} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
			$title = esc_html_x( 'Links', 'post format archive title', 'pencil' );
		} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
			$title = esc_html_x( 'Statuses', 'post format archive title', 'pencil' );
		} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
			$title = esc_html_x( 'Audio', 'post format archive title', 'pencil' );
		} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
			$title = esc_html_x( 'Chats', 'post format archive title', 'pencil' );
		}
	} elseif ( is_post_type_archive() ) {
		$title = sprintf( esc_html__( 'Archives: %s', 'pencil' ), post_type_archive_title( '', false ) );
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
		$title = sprintf( esc_html__( '%1$s: %2$s', 'pencil' ), $tax->labels->singular_name, single_term_title( '', false ) );
	} else {
		$title = esc_html__( 'Archives', 'pencil' );
	}

	/**
	 * Filter the archive title.
	 *
	 * @param string $title Archive title to be displayed.
	 */
	$title = apply_filters( 'get_the_archive_title', $title );

	if ( ! empty( $title ) ) {
		echo $before . $title . $after;  // WPCS: XSS OK.
	}
}
endif;

if ( ! function_exists( 'the_archive_description' ) ) :
/**
 * Shim for `the_archive_description()`.
 *
 * Display category, tag, or term description.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the description. Default empty.
 * @param string $after  Optional. Content to append to the description. Default empty.
 */
function the_archive_description( $before = '', $after = '' ) {
	$description = apply_filters( 'get_the_archive_description', term_description() );

	if ( ! empty( $description ) ) {
		/**
		 * Filter the archive description.
		 *
		 * @see term_description()
		 *
		 * @param string $description Archive description to be displayed.
		 */
		echo $before . $description . $after;  // WPCS: XSS OK.
	}
}
endif;

if ( ! function_exists( 'pencil_categorized_blog' ) ) :
/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function pencil_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'pencil_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'pencil_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so pencil_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so pencil_categorized_blog should return false.
		return false;
	}
}
endif;

/**
 * Flush out the transients used in pencil_categorized_blog.
 */
function pencil_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'pencil_categories' );
}
add_action( 'edit_category', 'pencil_category_transient_flusher' );
add_action( 'save_post',     'pencil_category_transient_flusher' );

if ( ! function_exists( 'pencil_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since pencil 1.0
 */
function pencil_comment( $comment, $args, $depth ) {
        $GLOBALS['comment'] = $comment;
        ?>
        <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
            <article id="comment-<?php comment_ID(); ?>" class="comment">
                <footer>
                    <div class="comment-author vcard">
                        <?php $avatar = get_avatar( $comment, $args['avatar_size'] ); ?>
                        <?php if( ! empty( $avatar ) ) : ?>
                        <div class="comments-avatar">
                        <?php echo $avatar; ?>
                        </div>    
                        <?php endif; ?>
                        <div class="comment-meta commentmetadata">
                            <?php printf( sprintf( '<cite class="fn"><b>%s</b></cite>', get_comment_author_link() ) ); ?>
                            <br />
                            <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time pubdate datetime="<?php comment_time( 'c' ); ?>">
                            <?php
                            /* translators: 1: date, 2: time */
                            printf( esc_html__( '%s ago', 'pencil' ), human_time_diff( get_comment_time( 'U' ), current_time( 'timestamp' ) ) );                                    ?>
                            </time></a>
                            <span class="reply"><?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => 'REPLY', 'before' => ' &#8901; ' ) ) ); ?></span><!-- .reply -->
                            <?php edit_comment_link( __( 'Edit', 'pencil' ), ' &#8901; ' );
                            ?>
                        </div><!-- .comment-meta .commentmetadata -->
                    </div><!-- .comment-author .vcard -->
                    <?php if ( $comment->comment_approved == '0' ) : ?>
                        <em><?php _e( 'Your comment is awaiting moderation.', 'pencil' ); ?></em>
                        <br />
                    <?php endif; ?>

                </footer>

                <div class="comment-content"><?php comment_text(); ?></div>

            </article><!-- #comment-## -->
        <?php
}
endif; // ends check for pencil_comment()

if ( ! function_exists( 'pencil_comments_fields' ) ) :

function pencil_comments_fields($fields) {
    
        $commenter = wp_get_current_commenter();
	//$user = wp_get_current_user();
	//$user_identity = $user->exists() ? $user->display_name : '';

	if ( ! isset( $args['format'] ) )
		$args['format'] = current_theme_supports( 'html5', 'comment-form' ) ? 'html5' : 'xhtml';

	$req      = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$html_req = ( $req ? " required='required'" : '' );
	$html5    = 'html5' === $args['format'];
    
        $fields   =  array(
		'author' => '<div class="comment-fields"><p class="comment-form-author">' . '<label for="author">' . esc_html__( 'Name', 'pencil' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
		            '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '"' . $aria_req . $html_req . '" placeholder="' . esc_html__( 'Name', 'pencil' ) .'" /></p>',
		'email'  => '<p class="comment-form-email"><label for="email">' . esc_html__( 'Email', 'pencil' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
		            '<input id="email" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr(  $commenter['comment_author_email'] ) . '" aria-describedby="email-notes"' . $aria_req . $html_req  . '" placeholder="' . esc_html__( 'Email', 'pencil' ) .'" /></p>',
		'url'    => '<p class="comment-form-ur"><label for="url">' . esc_html__( 'Website', 'pencil' ) . '</label> ' .
		            '<input id="url" name="url" ' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_url'] ) . '" placeholder="' . esc_html__( 'Website', 'pencil' ) .'" /></p></div>',
        );

return $fields;
}
add_filter('comment_form_default_fields','pencil_comments_fields');
endif;

/*
 * Gets the excerpt using the post ID outside the loop.
 *
 * @author      Withers David
 * @link        http://uplifted.net/programming/wordpress-get-the-excerpt-automatically-using-the-post-id-outside-of-the-loop/
 * @param       int $post_id
 * @return      string
 */
function pencil_get_excerpt_by_id($post_id){
    $the_post = get_post($post_id); //Gets post ID
    $the_excerpt = $the_post->post_content; //Gets post_content to be used as a basis for the excerpt
    $excerpt_length = 35; //Sets excerpt length by word count
    $the_excerpt = strip_tags(strip_shortcodes($the_excerpt)); //Strips tags and images
    $words = explode(' ', $the_excerpt, $excerpt_length + 1);

    if (count($words) > $excerpt_length) :
        array_pop($words);
        array_push($words, '...');
        $the_excerpt = implode(' ', $words);
    endif;

    $the_excerpt = '<p>' . $the_excerpt . '</p>';
    return $the_excerpt;
}

if ( ! function_exists( 'pencil_custom_popular_posts_html_list' ) ) :
/*
 * Builds custom HTML
 *
 * With this function, I can alter WPP's HTML output from my theme's functions.php.
 * This way, the modification is permanent even if the plugin gets updated.
 *
 * @param   array   $mostpopular
 * @param   array   $instance
 * @return  string
 */
function pencil_custom_popular_posts_html_list( $mostpopular, $instance ){
    $output = '<ul class="fat-wpp-list">';

    // loop the array of popular posts objects
    foreach( $mostpopular as $popular ) {
        //var_dump($popular->id);
/*        $stats = array(); // placeholder for the stats tag

        // Comment count option active, display comments
        if ( $instance['stats_tag']['comment_count'] ) {
            // display text in singular or plural, according to comments count
            $stats[] = '<span class="wpp-comments">' . sprintf(
                _n('1 comment', '%s comments', $popular->comment_count, 'wordpress-popular-posts'),
                number_format_i18n($popular->comment_count)
            ) . '</span>';
        }

        // Pageviews option checked, display views
        if ( $instance['stats_tag']['views'] ) {

            // If sorting posts by average views
            if ($instance['order_by'] == 'avg') {
                // display text in singular or plural, according to views count
                $stats[] = '<span class="wpp-views">' . sprintf(
                    _n('1 view per day', '%s views per day', intval($popular->pageviews), 'wordpress-popular-posts'),
                    number_format_i18n($popular->pageviews, 2)
                ) . '</span>';
            } else { // Sorting posts by views
                // display text in singular or plural, according to views count
                $stats[] = '<span class="wpp-views">' . sprintf(
                    _n('1 view', '%s views', intval($popular->pageviews), 'wordpress-popular-posts'),
                    number_format_i18n($popular->pageviews)
                ) . '</span>';
            }
        }
*/
 /*
        // Author option checked
        if ($instance['stats_tag']['author']) {
            $author = get_the_author_meta('display_name', $popular->uid);
            $display_name = '<a href="' . get_author_posts_url($popular->uid) . '">' . $author . '</a>';
            $stats[] = '<span class="wpp-author">' . sprintf(__('by %s', 'wordpress-popular-posts'), $display_name). '</span>';
        }

        // Date option checked
        if ($instance['stats_tag']['date']['active']) {
            $date = date_i18n($instance['stats_tag']['date']['format'], strtotime($popular->date));
            $stats[] = '<span class="wpp-date">' . sprintf(__('posted on %s', 'wordpress-popular-posts'), $date) . '</span>';
        }
*/
        // Category option checked
       /* if ($instance['stats_tag']['category']) {
            $post_cat = get_the_category($popular->id);
            $post_cat = (isset($post_cat[0]))
              ? '<a href="' . get_category_link($post_cat[0]->term_id) . '">' . $post_cat[0]->cat_name . '</a>'
              : '';

            if ($post_cat != '') {
                $stats[] = '<span class="wpp-category">' . sprintf(__('%s', 'wordpress-popular-posts'), $post_cat) . '</span>';
            }
        }*/
/*
        // Build stats tag
        if ( !empty($stats) ) {
            $stats = '<div class="wpp-stats">' . join( ' | ', $stats ) . '</div>';
        }
        
        $excerpt = ''; // Excerpt placeholder

        // Excerpt option checked, build excerpt tag
        if ($instance['post-excerpt']['active']) {

            $excerpt = pencil_get_excerpt_by_id( $popular->id );
            if ( !empty($excerpt) ) {
                $excerpt = '<div class="wpp-excerpt">' . $excerpt . '</div>';
            }

        }
*/
        $post_cat = get_the_category_list( esc_html__( ' &#x2f; ', 'pencil' ), '', $popular->id );

        $thumb = get_the_post_thumbnail( $popular->id, 'medium' );
        
        $output .= "<li>";
        $output .= ( ! empty ($thumb)) ? "<div class=\"fat-wpp-image\"><a href=\"" . esc_url( get_the_permalink( $popular->id ) ) . "\" title=\"" . esc_attr( $popular->title ) . "\">" /* . pencil_post_format_icon( $popular->id ) */ . $thumb . "</a>" : "";
        $output .= pencil_post_format_icon( $popular->id ) . "<div class=\"fat-wpp-image-cat\">". $post_cat ."</div>";
        $output .= ( ! empty ($thumb)) ? "</div>" : "";
        $output .= "<h2 class=\"entry-title\"><a href=\"" . esc_url( get_the_permalink( $popular->id ) ) . "\" title=\"" . esc_attr( $popular->title ) . "\">" . $popular->title . "</a></h2>";
        //$output .= ( ! empty ($stats)) ? $stats : "";
        //$output .= $excerpt;
        $output .= "</li>";

    }

    $output .= '</ul>';

    return $output;
}
if ( ! get_theme_mod( 'wpp_styling' ) ){
    add_filter( 'wpp_custom_html', 'pencil_custom_popular_posts_html_list', 10, 2 );
}
endif;

if ( ! function_exists( 'pencil_gallery_content' ) ) :
/**
 * Template for cutting images from gallery post format.
 *
 * @since pencil 1.0
 */
function pencil_gallery_content() {

        $content = get_the_content( sprintf( __( 'Read more %s <span class="meta-nav">&rarr;</span>', 'pencil' ), the_title( '<span class="screen-reader-text">"', '"</span>', false )));
        $pattern = '#\[gallery[^\]]*\]#';
        $replacement = '';

        $newcontent = preg_replace($pattern, $replacement, $content, 1);
        $newcontent = apply_filters( 'the_content', $newcontent );
        $newcontent = str_replace( ']]>', ']]&gt;', $newcontent );
        echo $newcontent;
}
endif;

if ( ! function_exists( 'pencil_media_content' ) ) :
/**
 * Template for cutting media from audio/video post formats.
 *
 * @since pencil 1.0
 */
function pencil_media_content() {
        $content = get_the_content( sprintf( esc_html__( 'Read more %s <span class="meta-nav">&rarr;</span>', 'pencil' ), the_title( '<span class="screen-reader-text">"', '"</span>', false )));
        $content = apply_filters( 'the_content', $content );
        $content = str_replace( ']]>', ']]&gt;', $content );

        $tags = 'audio|video|object|embed|iframe|img';

        $replacement = '';

        $newcontent = preg_replace('#<(?P<tag>' . $tags . ')[^<]*?(?:>[\s\S]*?<\/(?P=tag)>|\s*\/>)#', $replacement, $content, 1);

        echo $newcontent;    
}
endif;

/**
 * Adds a meta box to the post editing screen
 */
/*function pencil_custom_meta() {
    add_meta_box( 'pencil_meta_slider', __( 'Featured Post', 'pencil' ), 'pencil_meta_slider_callback', 'post', 'side' );
}
add_action( 'add_meta_boxes', 'pencil_custom_meta' );
*/
/**
 * Outputs the content of the meta box
 */
/*function pencil_meta_slider_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'pencil_slider_meta_nonce' );
    $pencil_meta_slider_stored_meta = get_post_meta( $post->ID );
    ?>
    <div class="prfx-row-content">
        <label for="pencil-meta-slider-checkbox">
            <input type="checkbox" name="pencil-meta-slider-checkbox" id="pencil-meta-slider-checkbox" value="1" <?php if ( isset ( $pencil_meta_slider_stored_meta['pencil-meta-slider-checkbox'] ) ) checked( $pencil_meta_slider_stored_meta['pencil-meta-slider-checkbox'][0], '1' ); ?> />
            <?php _e( 'Display in Slider on Home Page ', 'pencil' )?>
        </label>
    </div>
 
    <?php
}
*/
/**
 * Saves the custom meta input
 */
/*function pencil_custom_meta_save( $post_id ) {
 
    // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'pencil_slider_meta_nonce' ] ) && wp_verify_nonce( $_POST[ 'pencil_slider_meta_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
 
    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }
    
    // Checks for input and saves
if( isset( $_POST[ 'pencil-meta-slider-checkbox' ] ) ) {
    update_post_meta( $post_id, 'pencil-meta-slider-checkbox', '1' );
} else {
    update_post_meta( $post_id, 'pencil-meta-slider-checkbox', '' );
}
 
}
add_action( 'save_post', 'pencil_custom_meta_save' );
*/

if ( ! function_exists( 'pencil_gallery_shortcode' ) ) :
    
function pencil_gallery_shortcode( $output = '', $atts, $instance ) {
	$return = $output; // fallback

	$atts = array( 'size' => 'medium' );

	return $output;
}

add_filter( 'post_gallery', 'pencil_gallery_shortcode', 10, 3 );
endif;

if ( ! function_exists( 'pencil_post_format_icon' ) ) :
/*
 * Function for getting post format icon 
 */
function pencil_post_format_icon( $post_id ){
    
        if ( empty( $post_id ) ) {
            return;
        }
        
        $format = get_post_format( $post_id );

        if ( ! $format ) {
 
                return;
            
        } else {

                if( $format === 'audio' ){
                    return '<div class="pencil-post-format-icon"><span class="fa fa-music"></span></div>';
                } elseif( $format === 'video' ) {
                    return '<div class="pencil-post-format-icon"><span class="fa fa-video-camera"></span></div>';
                } elseif( $format === 'gallery' ) {
                    return '<div class="pencil-post-format-icon"><span class="fa fa-camera"></span></div>';
                }
        }
}
endif;