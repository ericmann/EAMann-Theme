<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package EAMann
 * @since EAMann 1.0
 */

get_header(); ?>

		<div id="primary" class="site-content">
			<div id="content" role="main">

			<?php
				$latest_args = array(
					'posts_per_page' => 4,
					'tax_query'      => array(
						array(
							'taxonomy' => 'post_format',
							'field'    => 'slug',
							'terms'    => array( 'post-format-aside', 'post-format-status' ),
							'operator' => 'NOT IN'
						)
					)
				);

				$status_args = array(
					'posts_per_page' => 1,
					'tax_query'      => array(
						array(
							'taxonomy' => 'post_format',
							'field'    => 'slug',
							'terms'    => array( 'post-format-status' ),
						)
					)
				);

				$latest = new WP_Query( $latest_args );
				$status = new WP_Query( $status_args );
			?>
			<?php if ( $latest->have_posts() ) : ?>

				<?php while ( $latest->have_posts() ) : $latest->the_post(); ?>
					<?php get_template_part( 'front', 'single' ); ?>
				<?php endwhile; ?>

			<?php elseif ( current_user_can( 'edit_posts' ) ) : ?>

				<?php get_template_part( 'no-results', 'index' ); ?>

			<?php endif; ?>

			<?php if ( $status->have_posts() ) : while ( $status->have_posts() ) : $status->the_post(); ?>

				<aside class="status">
					<?php the_content(); ?>
				</aside>

			<?php endwhile; endif; ?>

			<?php foreach ( array( 'biz', 'writing', 'faith', 'tech' ) as $category ) {
				$args = array(
					'posts_per_page' => 5,
					'tax_query'      => array(
						array(
							'taxonomy' => 'post_format',
							'field'    => 'slug',
							'terms'    => array( 'post-format-aside', 'post-format-status' ),
							'operator' => 'NOT IN'
						)
					),
					'post__not_in'   => wp_list_pluck( $latest->posts, 'ID' ),
					'category_name'  => $category
				);

				$cat_query = new WP_Query( $args );

				$first = true;

				if ( $cat_query->have_posts() ) : while ( $cat_query->have_posts() ) : $cat_query->the_post();
					if ( $first ) {
						$first = false;
						get_template_part( 'front', 'category' );
					} else { ?>
						<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
					<?php } ?>
				<?php endwhile; ?>
					</ul>
					</article>
				<?php endif; ?>

			<?php } ?>

			</div><!-- #content -->
		</div><!-- #primary .site-content -->

<?php get_sidebar( 'front' ); ?>
<?php get_footer(); ?>