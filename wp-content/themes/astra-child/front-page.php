<?php
/**
 * Custom Homepage Template
 * Template Name: Homepage Template
 *
 * @package SS Enterprises B2B
 * @since 1.0.0
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main homepage-template">

        <?php get_template_part('template-parts/homepage/hero'); ?>
        <?php get_template_part('template-parts/homepage/why-choose-us'); ?>
        <?php get_template_part('template-parts/homepage/plans'); ?>
        <?php get_template_part('template-parts/homepage/what-we-offer'); ?>
        <?php get_template_part('template-parts/homepage/dia'); ?>
        <?php get_template_part('template-parts/homepage/ss-advantage'); ?>
        <?php get_template_part('template-parts/homepage/testimonials'); ?>
        <?php get_template_part('template-parts/homepage/faq'); ?>
        <?php get_template_part('template-parts/homepage/final-cta'); ?>
        <?php get_template_part('template-parts/homepage/services'); ?>
        <?php get_template_part('template-parts/homepage/services-form'); ?>
        <?php get_template_part('template-parts/homepage/why-choose-us-passionate'); ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
