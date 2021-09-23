<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package pencil
 */

get_header(); ?>
<div class="row">
		<div id="primary" class="content-area col-md-8">
		<main id="main" class="site-main row" role="main">

			<?php
                woocommerce_content();
            ?>


		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
</div><!-- .row -->
<?php get_footer(); ?>
