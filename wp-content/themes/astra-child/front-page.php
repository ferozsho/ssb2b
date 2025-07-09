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

        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-container">
                <div class="hero-content">
                    <div class="hero-text">
                        <h1 class="hero-title">Your Connections Matter!</h1>
                        <p class="hero-description">
                            <em>Best connection at work is like the main fuel for smooth operations in a company</em>.
                        </p>
                        <div class="hero-buttons">
                            <a href="<?php echo get_permalink(get_page_by_path('lead')); ?>" class="btn btn-primary">Connect with Us!</a>
                        </div>
                    </div>
                    <div class="hero-image">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/hero-img.svg" alt="Business Connection Illustration" />
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section class="services-section">
            <div class="services-container">
                <div class="services-grid">
                    <div class="service-card">
                        <div class="service-icon">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/building.svg" alt="Local Business" />
                        </div>
                        <h3 class="service-title">Local Business</h3>
                        <p class="service-description">Connect with local businesses and build lasting partnerships in your community.</p>
                    </div>

                    <div class="service-card">
                        <div class="service-icon">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/shopping-bag.svg" alt="Online Store" />
                        </div>
                        <h3 class="service-title">Online Store</h3>
                        <p class="service-description">Expand your reach with comprehensive e-commerce solutions and digital presence.</p>
                    </div>

                    <div class="service-card">
                        <div class="service-icon">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/portfolio.svg" alt="Portfolio" />
                        </div>
                        <h3 class="service-title">Portfolio</h3>
                        <p class="service-description">Showcase your work and achievements with professional portfolio solutions.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="services-form">
            <div class="services-container">
                <?php get_template_part('page', 'lead-form'); ?>
            </div>
        </section>


        <!-- Why Choose Us Section -->
        <section class="why-choose-us-section" id="whyus">
            <div class="why-choose-container">
                <h2 class="section-title">Why Choose Us</h2>

                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/passionate.svg" alt="Passionate" />
                        </div>
                        <h3 class="feature-title">Passionate</h3>
                        <p class="feature-description">We bring enthusiasm and dedication to every project, ensuring exceptional results that exceed expectations.</p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/professional.svg" alt="Professional" />
                        </div>
                        <h3 class="feature-title">Professional</h3>
                        <p class="feature-description">Our team maintains the highest standards of professionalism in every interaction and deliverable.</p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/support.svg" alt="Support" />
                        </div>
                        <h3 class="feature-title">Support</h3>
                        <p class="feature-description">Comprehensive support throughout your journey, from initial consultation to ongoing maintenance.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Call to Action Section -->
        <section class="cta-section" id="contact">
            <div class="cta-container">
                <h2 class="cta-title">Get a Professional Connection Today!</h2>
                <div class="cta-buttons">
                    <a href="<?php echo get_permalink(get_page_by_title('Contact Us')); ?>" class="btn btn-secondary">Contact Us</a>
                </div>
            </div>
        </section>

        <!-- Additional Content Area -->
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <?php if (get_the_content()) : ?>
                <section class="additional-content">
                    <div class="content-container">
                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div>
                    </div>
                </section>
                <?php endif; ?>
            <?php endwhile; ?>
        <?php endif; ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>