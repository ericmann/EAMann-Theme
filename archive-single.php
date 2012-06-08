<?php
/**
 * @package EAMann
 * @since EAMann 1.0
 */
?>

<?php $category = get_the_category( $post->ID ); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<a href="<?php the_permalink(); ?>" rel="bookmark">
		<h4 class="entry-title category-<?php echo $category[0]->slug ?>"><?php the_title(); ?></h4>
	</a>
	<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>">
		<img class="entry-image" src="<?php eamann_featured_image() ?>" alt="img-template" />
	</a>

	<?php the_excerpt(); ?>
</article>