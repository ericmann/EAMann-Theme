<div id="primary" class="site-content">
	<div id="content" role="main">

	<?php
	$offset = ((int) $paged - 2) * 10 + 4;

	$args = array(
		'posts_per_page' => 10,
		'offset'         => $offset,
		'tax_query'      => array(
			array(
				'taxonomy' => 'post_format',
				'field'    => 'slug',
				'terms'    => array( 'post-format-aside', 'post-format-status' ),
				'operator' => 'NOT IN'
			)
		)
	);

	$query = new WP_Query( $args );
	?>

	<?php if ( $query->have_posts() ) : ?>

		<?php eamann_content_nav( 'nav-above' ); ?>

		<?php /* Start the Loop */ ?>
		<?php while ( $query->have_posts() ) : $query->the_post(); ?>

			<?php get_template_part( 'front', 'single' ); ?>

		<?php endwhile; ?>

		<?php eamann_content_nav( 'nav-below' ); ?>

	<?php elseif ( current_user_can( 'edit_posts' ) ) : ?>

		<?php get_template_part( 'no-results', 'index' ); ?>

	<?php endif; ?>

	</div><!-- #content -->
</div><!-- #primary .site-content -->

<?php get_sidebar( 'front' ); ?>
<?php get_footer(); ?>