<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package EAMann
 * @since EAMann 1.0
 */
?>
		<div id="secondary" class="widget-area" role="complementary">
			<?php do_action( 'before_sidebar' ); ?>
			<aside id="site-header">
				<h1 class="site-title"><a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			</aside>

			<aside id="search" class="widget widget_search">
				<?php get_search_form(); ?>
			</aside>

			<aside id="post-meta">
				<?php
					global $post;
					setup_postdata( $post );
				?>
				<p class="meta"><strong>Creator</strong> <?php the_author_posts_link(); ?></p>
				<p class="meta"><strong>Post published</strong> <?php the_date(); ?></p>
				<p class="meta"><strong>Last modified</strong> <?php the_modified_date(); ?></p>
				<p class="meta"><strong>Rights</strong> <a href="http://creativecommons.org/licenses/by-sa/3.0/us/">CC BY-SA 3.0</a></p>
				<p class="meta"><strong>Tagged</strong> <?php the_tags( "", ", " ) ?></p>
			</aside>

			<?php if ( ! dynamic_sidebar( 'sidebar-single' ) ) : ?>

			<?php endif; // end sidebar widget area ?>
		</div><!-- #secondary .widget-area -->
