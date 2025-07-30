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
        <section class="hero-section new-hero">
            <div class="hero-container">
                <div class="hero-content">
                    <div class="hero-text">
                        <h1 class="hero-title">Enterprise Leased Line Service</h1>
                        <p class="hero-description">
                            Highly reliable Internet Leased Line services to keep your business always online. Our services go beyond just offering high-speed internet by providing a robust and secure network that ensures seamless connectivity for all your enterprise needs.
                        </p>
                        <div class="hero-buttons">
                            <a href="#" class="btn btn-primary">Get a Quote</a>
                        </div>
                    </div>
                    <div class="hero-form">
                        <h3>Get in Touch</h3>
                        <form>
                            <div class="form-group">
                                <input type="text" placeholder="Your Name">
                            </div>
                            <div class="form-group">
                                <input type="email" placeholder="Your Email">
                            </div>
                            <div class="form-group">
                                <input type="tel" placeholder="Phone Number">
                            </div>
                            <div class="form-group">
                                <textarea placeholder="Your Message"></textarea>
                            </div>
                            <button type="submit" class="btn btn-secondary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why Choose Us Section -->
        <section class="why-choose-us-section" id="whyus">
            <div class="why-choose-container">
                <h2 class="section-title">Why choose SS Enterprises Leased Line over other internet?</h2>

                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-title">Office Broadband</div>
                        <p class="feature-description">Shared bandwidth across users, leading to fluctuating speeds</p>
                        <p class="feature-description">Limited reliability</p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-title">Enterprise Leased Line</div>
                        <p class="feature-description">Guaranteed, uninterrupted speeds for consistent performance</p>
                        <p class="feature-description">Highly reliable with robust SLAs for maximum uptime</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Plans Section -->
        <section class="plans-section">
            <div class="plans-container">
                <h2 class="section-title">Get the best Leased Line Plan for your Business</h2>
                <div class="plans-grid">
                    <div class="plan-card">
                        <div class="plan-title">10 Mbps</div>
                        <div class="plan-description">Best for Small Offices</div>
                        <a href="#" class="btn btn-primary">Get a Quote</a>
                    </div>
                    <div class="plan-card">
                        <div class="plan-title">20 Mbps</div>
                        <div class="plan-description">Ideal for Medium Businesses</div>
                        <a href="#" class="btn btn-primary">Get a Quote</a>
                    </div>
                    <div class="plan-card">
                        <div class="plan-title">50 Mbps</div>
                        <div class="plan-description">Perfect for Growing Companies</div>
                        <a href="#" class="btn btn-primary">Get a Quote</a>
                    </div>
                    <div class="plan-card">
                        <div class="plan-title">100 Mbps</div>
                        <div class="plan-description">Great for Large Enterprises</div>
                        <a href="#" class="btn btn-primary">Get a Quote</a>
                    </div>
                    <div class="plan-card">
                        <div class="plan-title">200 Mbps</div>
                        <div class="plan-description">For High-Demand Operations</div>
                        <a href="#" class="btn btn-primary">Get a Quote</a>
                    </div>
                    <div class="plan-card">
                        <div class="plan-title">500 Mbps</div>
                        <div class="plan-description">Ultimate Performance</div>
                        <a href="#" class="btn btn-primary">Get a Quote</a>
                    </div>
                    <div class="plan-card">
                        <div class="plan-title">1 Gbps</div>
                        <div class="plan-description">Future-Proof Connectivity</div>
                        <a href="#" class="btn btn-primary">Get a Quote</a>
                    </div>
                    <div class="plan-card">
                        <div class="plan-title">Above 1 Gbps</div>
                        <div class="plan-description">Customized Solutions</div>
                        <a href="#" class="btn btn-primary">Get a Quote</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- What We Offer Section -->
        <section class="what-we-offer-section">
            <div class="what-we-offer-container">
                <h2 class="section-title">What We Offer</h2>
                <div class="what-we-offer-grid">
                    <div class="what-we-offer-card">
                        <div class="what-we-offer-title">Dedicated Internet Access (DIA)</div>
                    </div>
                    <div class="what-we-offer-card">
                        <div class="what-we-offer-title">High-Availability Internet</div>
                    </div>
                    <div class="what-we-offer-card">
                        <div class="what-we-offer-title">P2P & MPLS</div>
                    </div>
                    <div class="what-we-offer-card">
                        <div class="what-we-offer-title">Broadband</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- DIA Section -->
        <section class="dia-section">
            <div class="dia-container">
                <div class="dia-content">
                    <div class="dia-text">
                        <h2 class="section-title">Dedicated Internet Access (DIA)</h2>
                        <p>Guaranteed, high-performance internet leased line provider with symmetric bandwidth for enterprises of all sizes. We provide a robust network with a dedicated, uncontested connection for uninterrupted, high-speed data transfer.</p>
                    </div>
                    <div class="dia-image">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/dia.jpg" alt="Dedicated Internet Access">
                    </div>
                </div>
            </div>
        </section>

        <!-- Ishan Advantage Section -->
        <section class="ishan-advantage-section">
            <div class="ishan-advantage-container">
                <h2 class="section-title">The SS Enterprises Advantage</h2>
                <div class="ishan-advantage-grid">
                    <div class="ishan-advantage-card">
                        <div class="ishan-advantage-title">Quick Delivery</div>
                        <p>Our quick setup and deployment process ensures minimal downtime and faster go-live timelines.</p>
                    </div>
                    <div class="ishan-advantage-card">
                        <div class="ishan-advantage-title">Flexible Bandwidth</div>
                        <p>We offer scalable bandwidth options that can be adjusted as your business needs evolve.</p>
                    </div>
                    <div class="ishan-advantage-card">
                        <div class="ishan-advantage-title">DDoS Protected Network</div>
                        <p>Our network is resilient to DDoS attacks, ensuring your services remain online with enhanced security protocols.</p>
                    </div>
                    <div class="ishan-advantage-card">
                        <div class="ishan-advantage-title">Nationwide Coverage</div>
                        <p>Benefit from our extensive network coverage, with a presence in multiple locations with SS Enterprises's extensive leased line.</p>
                    </div>
                    <div class="ishan-advantage-card">
                        <div class="ishan-advantage-title">24/7 Support</div>
                        <p>Our dedicated support team is available round-the-clock to resolve any issues.</p>
                    </div>
                    <div class="ishan-advantage-card">
                        <div class="ishan-advantage-title">Multiple Failover Partners</div>
                        <p>We partner with multiple upstream providers to ensure high availability and resilient networks.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Client Experiences Section -->
        <section class="client-experiences-section">
            <div class="client-experiences-container">
                <h2 class="section-title">Client Experiences</h2>
                <div class="client-experiences-grid">
                    <div class="client-experience-card">
                        <p>"SS Enterprises, our IT partner, exhibited design as well as project management capabilities to meet our Wi-Fi requirements. SS Enterprises's project teams displayed maturity and perseverance to support our needs. They helped us deliver the critical milestones on time. I am happy to say that we have a reliable partner for our IT needs and I would recommend SS Enterprises to anyone with similar needs elsewhere."</p>
                        <div class="client-info">
                            <div class="client-name">Haresh Patel</div>
                            <div class="client-company">Director, Ekaaya</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="faq-section">
            <div class="faq-container">
                <h2 class="section-title">Frequently Asked Questions (FAQs)</h2>
                <div class="faq-accordion">
                    <div class="faq-item">
                        <div class="faq-question">What is an Internet Leased Line?</div>
                        <div class="faq-answer">
                            <p>An Internet Leased Line is a dedicated, high-speed internet connection that provides a fixed bandwidth for your business. Unlike broadband, it's a private line exclusively for your use, ensuring consistent speeds and reliability.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question">How is a dedicated internet line different from broadband?</div>
                        <div class="faq-answer">
                            <p>A dedicated internet line offers symmetric upload and download speeds, a dedicated bandwidth (not shared with other users), and a service level agreement (SLA) that guarantees uptime. Broadband, on the other hand, is a shared connection with asymmetric speeds and typically no SLA.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question">What are the benefits of a leased line for businesses?</div>
                        <div class="faq-answer">
                            <p>The key benefits include guaranteed bandwidth, high reliability and uptime, symmetric speeds for faster uploads, enhanced security, and 24/7 customer support. It's ideal for businesses that rely heavily on internet connectivity for their operations.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question">What speeds are available for leased lines?</div>
                        <div class="faq-answer">
                            <p>We offer a wide range of speeds, from 10 Mbps to over 1 Gbps. We can also provide customized solutions for businesses with specific bandwidth requirements.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question">How do I determine the right speed for my business needs?</div>
                        <div class="faq-answer">
                            <p>Our team of experts can help you assess your internet usage, number of users, and the types of applications you use to recommend the most suitable bandwidth for your business. Contact us for a free consultation.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Final CTA Section -->
        <section class="final-cta-section">
            <div class="final-cta-container">
                <h2 class="section-title">GET THE MOST RELIABLE LEASED LINE NETWORK</h2>
                <p>Contact us today for a customized quote and consultation</p>
                <a href="#" class="btn btn-primary">Get in Touch</a>
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
                </div>
            </div>
        </section>



    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>