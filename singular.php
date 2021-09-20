<?php
/**
 * The template for displaying single posts and pages.
 * @package WordPress
 */
wp_head();
?>

<main id="site-content" role="main">

	<?php

$content = apply_filters( 'the_content', get_the_content() );
echo $content;

	?>

</main><!-- #site-content -->

