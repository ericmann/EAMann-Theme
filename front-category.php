<?php
/**
 * @package EAMann
 * @since EAMann 1.0
 */
?>

<?php $category = get_the_category( $post->ID ); ?>

<article id="category-<?php $category[0]->slug; ?>" <?php post_class( 'small-cats' ); ?>>
	<a href="<?php echo get_category_link( $category[0]->term_id ); ?>">
		<h4><?php echo $category[0]->cat_name; ?></h4>
	</a>
	<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>">
		<img class="entry-image" src="<?php eamann_featured_image() ?>" alt="img-template" />
	</a>
	<ul>
        <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>