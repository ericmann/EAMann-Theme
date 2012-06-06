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

$paged = get_query_var('paged') ? get_query_var('paged') : 1;

get_header();

if ( 1 == $paged ) {
	get_template_part( 'index', 'front' );
} else {
	get_template_part( 'index', 'older' );
}

get_footer();
?>