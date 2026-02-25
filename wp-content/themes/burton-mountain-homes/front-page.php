<?php
/**
 * Template Name: BMH Homepage
 * Template Post Type: page
 * 
 * The front page template for Burton Mountain Homes
 * This template outputs its own HTML structure to bypass GeneratePress containers
 *
 * @package Burton_Mountain_Homes
 */

// Get ACF fields if available, otherwise use defaults
$hero_image = '';
if (function_exists('get_field')) {
    $hero_image = get_field('hero_image');
}
$hero_image = $hero_image ?: get_stylesheet_directory_uri() . '/assets/images/hero-default.jpg';
$hero_image_url = is_array($hero_image) ? $hero_image['url'] : $hero_image;

$hero_headline = 'Your Trusted Partners in Vail Valley Real Estate';
$hero_subtitle = 'With record-setting sales and deep local roots, Bret Burton and Ilse Cervantes deliver the expertise, relationships, and results that luxury mountain real estate demands.';

if (function_exists('get_field')) {
    $hero_headline = get_field('hero_headline') ?: $hero_headline;
    $hero_subtitle = get_field('hero_subtitle') ?: $hero_subtitle;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@300;400;500;600;700&family=Source+Sans+3:wght@300;400;500;600&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
    <style>
        /* Reset any GeneratePress interference */
        body { margin: 0; padding: 0; }
        * { box-sizing: border-box; }
    </style>
</head>
<body <?php body_class('bmh-homepage'); ?>>
<?php wp_body_open(); ?>

<?php get_template_part('template-parts/nav', null, ['transparent' => true]); ?>

<!-- Hero Section -->
<section class="bmh-hero">
    <div class="bmh-hero-bg" style="background-image: url('<?php echo esc_url($hero_image_url); ?>');"></div>
    <div class="bmh-hero-overlay"></div>
    <div class="bmh-hero-content">
        <p class="bmh-hero-credibility">LIV Sotheby's International Realty • Vail Valley</p>
        <h1><?php echo esc_html($hero_headline); ?></h1>
        <p class="bmh-hero-subtitle"><?php echo esc_html($hero_subtitle); ?></p>
        <div class="bmh-hero-cta">
            <a href="#contact" class="bmh-btn bmh-btn-primary">Schedule a Consultation</a>
            <a href="<?php echo get_post_type_archive_link('property'); ?>" class="bmh-btn bmh-btn-outline">View Properties</a>
        </div>
    </div>
</section>

<!-- Stats Bar -->
<div class="bmh-stats-bar">
    <div class="bmh-stat">
        <div class="bmh-stat-number">$18M</div>
        <div class="bmh-stat-label">Record Sale • Bachelor Gulch</div>
    </div>
    <div class="bmh-stat">
        <div class="bmh-stat-number">10+</div>
        <div class="bmh-stat-label">Years in Vail Valley</div>
    </div>
    <div class="bmh-stat">
        <div class="bmh-stat-number">8</div>
        <div class="bmh-stat-label">Personal Investment Properties</div>
    </div>
    <div class="bmh-stat">
        <div class="bmh-stat-number">Bilingual</div>
        <div class="bmh-stat-label">English & Spanish</div>
    </div>
</div>

<!-- Featured Properties Section -->
<section class="bmh-section" id="properties" style="background: #fff;">
    <div class="bmh-section-header">
        <p class="bmh-section-label">Portfolio</p>
        <h2>Featured & Recent Sales</h2>
        <p>A selection of exceptional properties we've represented across the Vail Valley.</p>
    </div>
    
    <div class="bmh-properties-grid">
        <?php
        $featured_query = new WP_Query(array(
            'post_type'      => 'property',
            'posts_per_page' => 3,
            'orderby'        => 'date',
            'order'          => 'DESC'
        ));
        
        if ($featured_query->have_posts()) :
            while ($featured_query->have_posts()) : $featured_query->the_post();
                $specs = bmh_get_specs();
        ?>
            <article class="bmh-property-card">
                <a href="<?php the_permalink(); ?>">
                    <div class="bmh-property-image">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('property-card'); ?>
                        <?php else : ?>
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/placeholder-property.jpg" alt="<?php the_title_attribute(); ?>">
                        <?php endif; ?>
                        <?php echo bmh_get_status_badge(); ?>
                    </div>
                    <div class="bmh-property-details">
                        <div class="bmh-property-price"><?php echo bmh_get_price(); ?></div>
                        <div class="bmh-property-address"><?php echo esc_html(bmh_get_address()); ?></div>
                        <div class="bmh-property-specs">
                            <?php if ($specs['bedrooms']) : ?><span><?php echo esc_html($specs['bedrooms']); ?> Beds</span><?php endif; ?>
                            <?php if ($specs['bathrooms']) : ?><span><?php echo esc_html($specs['bathrooms']); ?> Baths</span><?php endif; ?>
                            <?php if ($specs['sqft']) : ?><span><?php echo number_format((int)$specs['sqft']); ?> Sq Ft</span><?php endif; ?>
                        </div>
                    </div>
                </a>
            </article>
        <?php 
            endwhile;
            wp_reset_postdata();
        else :
        ?>
            <!-- Placeholder cards when no properties exist -->
            <div class="bmh-property-card">
                <div class="bmh-property-image">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/modern-interior.jpg" alt="Bachelor Gulch Record Sale">
                    <span class="bmh-property-badge sold">Sold</span>
                </div>
                <div class="bmh-property-details">
                    <div class="bmh-property-price">$18,000,000</div>
                    <div class="bmh-property-address">Bachelor Gulch • Record Sale</div>
                    <div class="bmh-property-specs">
                        <span>5 Beds</span>
                        <span>6 Baths</span>
                        <span>8,200 Sq Ft</span>
                    </div>
                </div>
            </div>
            <div class="bmh-property-card">
                <div class="bmh-property-image">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/interior-dining.jpg" alt="Wildridge Listing">
                    <span class="bmh-property-badge">Active</span>
                </div>
                <div class="bmh-property-details">
                    <div class="bmh-property-price">$4,250,000</div>
                    <div class="bmh-property-address">2325 Colorow Road • Wildridge</div>
                    <div class="bmh-property-specs">
                        <span>4 Beds</span>
                        <span>4 Baths</span>
                        <span>4,100 Sq Ft</span>
                    </div>
                </div>
            </div>
            <div class="bmh-property-card">
                <div class="bmh-property-image">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/pool.jpg" alt="Beaver Creek Sale">
                    <span class="bmh-property-badge sold">Sold</span>
                </div>
                <div class="bmh-property-details">
                    <div class="bmh-property-price">$9,500,000</div>
                    <div class="bmh-property-address">Beaver Creek Village</div>
                    <div class="bmh-property-specs">
                        <span>5 Beds</span>
                        <span>5 Baths</span>
                        <span>6,400 Sq Ft</span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <div style="text-align: center; margin-top: 3rem;">
        <a href="<?php echo get_post_type_archive_link('property'); ?>" class="bmh-btn bmh-btn-primary">View All Properties</a>
    </div>
</section>

<!-- About Section -->
<section class="bmh-section" id="about" style="background: #faf8f5;">
    <div class="bmh-two-col">
        <div class="bmh-agents-row">
            <div class="bmh-agent-card">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/bret-burton.jpg" alt="Bret Burton" class="bmh-agent-photo">
                <h3 class="bmh-agent-name">Bret Burton</h3>
                <p class="bmh-agent-title">Broker Associate</p>
            </div>
            <div class="bmh-agent-card">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/ilse-cervantes.jpg" alt="Ilse Cervantes" class="bmh-agent-photo">
                <h3 class="bmh-agent-name">Ilse Cervantes</h3>
                <p class="bmh-agent-title">Real Estate Advisor</p>
            </div>
        </div>
        <div>
            <p class="bmh-section-label">Meet Your Team</p>
            <h2>Local Expertise Meets Global Reach</h2>
            <p style="margin: 1.5rem 0;">
                We're not just real estate agents—we're investors, neighbors, and dedicated advocates 
                for our clients. With backgrounds in finance and five-star hospitality, we bring a 
                rare combination of analytical rigor and white-glove service to every transaction.
            </p>
            <p style="margin-bottom: 2rem;">
                Our partnership offers the best of both worlds: Bret's deep expertise in property 
                valuation, creative financing, and investment strategy, combined with Ilse's local 
                roots, bilingual service, and genuine passion for connecting people with their 
                perfect mountain home.
            </p>
            <div class="bmh-highlights">
                <div class="bmh-highlight">
                    <div class="bmh-highlight-icon"></div>
                    <p><strong>Ritz-Carlton trained</strong> — Five-star service is our standard</p>
                </div>
                <div class="bmh-highlight">
                    <div class="bmh-highlight-icon"></div>
                    <p><strong>Finance background</strong> — Michigan State University, deep investment expertise</p>
                </div>
                <div class="bmh-highlight">
                    <div class="bmh-highlight-icon"></div>
                    <p><strong>Local investors</strong> — We own 8+ properties across Eagle County</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="bmh-section bmh-testimonials" id="testimonials">
    <div class="bmh-section-header">
        <p class="bmh-section-label">Client Experiences</p>
        <h2>What Our Clients Say</h2>
        <p>Building relationships that extend well beyond the sale.</p>
    </div>
    <div class="bmh-testimonials-grid">
        <div class="bmh-testimonial-card">
            <p class="bmh-testimonial-quote">
                "Of my over 30 years and numerous real estate transactions in the Vail Valley, 
                this was by far the easiest and smoothest I have had. Bret displayed his knowledge 
                of the business and the market from listing to closing."
            </p>
            <p class="bmh-testimonial-author">— M.G., Longtime Vail Valley Owner</p>
        </div>
        <div class="bmh-testimonial-card">
            <p class="bmh-testimonial-quote">
                "Bret told us about a deed program that enabled us to secure the funds to buy 
                our home in the neighborhood we've always dreamed of. The property never even 
                went on the market—seamless transition from one home to the next."
            </p>
            <p class="bmh-testimonial-author">— P.F., Edwards</p>
        </div>
        <div class="bmh-testimonial-card">
            <p class="bmh-testimonial-quote">
                "Bret's expertise is top notch. Always gives the honest opinion and looks at 
                the numbers rather than just the drapes. He has a strong entrepreneurial spirit 
                and unmatched professionalism."
            </p>
            <p class="bmh-testimonial-author">— N.J., Local Seller</p>
        </div>
    </div>
</section>

<!-- Why Vail Valley Section -->
<section class="bmh-section" id="lifestyle" style="background: #fff;">
    <div class="bmh-two-col-wide">
        <div class="bmh-lifestyle-image">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/lifestyle-default.jpg" alt="Vail Valley Lifestyle">
            <span class="bmh-lifestyle-caption">Bravo! Vail at Gerald R. Ford Amphitheater</span>
        </div>
        <div>
            <p class="bmh-section-label">The Vail Valley Lifestyle</p>
            <h2>More Than a Destination—A Way of Life</h2>
            <p style="margin: 1.5rem 0;">
                The Vail Valley offers an unparalleled combination of world-class recreation, 
                cultural richness, and natural beauty. Whether you're seeking a legacy property, 
                an investment opportunity, or your family's mountain retreat, we know every 
                neighborhood, every trail, and every hidden gem.
            </p>
            <div class="bmh-features">
                <div class="bmh-feature">
                    <div class="bmh-feature-icon"></div>
                    <p><strong>World-class skiing</strong> — Vail, Beaver Creek, and access to the Epic Pass network</p>
                </div>
                <div class="bmh-feature">
                    <div class="bmh-feature-icon"></div>
                    <p><strong>Summer adventures</strong> — Hiking, biking, golf, and whitewater rafting</p>
                </div>
                <div class="bmh-feature">
                    <div class="bmh-feature-icon"></div>
                    <p><strong>Cultural events</strong> — Bravo! Vail, Vail Dance Festival, and year-round programming</p>
                </div>
                <div class="bmh-feature">
                    <div class="bmh-feature-icon"></div>
                    <p><strong>Easy access</strong> — 2 hours from Denver, private jet service to Eagle County Regional</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact CTA -->
<section class="bmh-contact-cta" id="contact">
    <h2>Let's Find Your Mountain Home</h2>
    <p>
        Whether you're buying, selling, or exploring investment opportunities, 
        we'd love to learn about your goals and share our insights on the Vail Valley market.
    </p>
    <a href="mailto:bburton@livsothebysrealty.com" class="bmh-btn bmh-btn-primary">Start the Conversation</a>
    <div class="bmh-contact-info">
        <div class="bmh-contact-item">
            <p class="bmh-contact-label">Bret Burton</p>
            <p class="bmh-contact-value"><a href="tel:9706881819">(970) 688-1819</a></p>
        </div>
        <div class="bmh-contact-item">
            <p class="bmh-contact-label">Email</p>
            <p class="bmh-contact-value"><a href="mailto:bburton@livsothebysrealty.com">bburton@livsothebysrealty.com</a></p>
        </div>
        <div class="bmh-contact-item">
            <p class="bmh-contact-label">Office</p>
            <p class="bmh-contact-value">Beaver Creek Village</p>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bmh-footer">
    <p class="bmh-footer-brand">Burton <span>Mountain</span> Homes</p>
    <p class="bmh-footer-affiliation">LIV Sotheby's International Realty</p>
    <p class="bmh-footer-legal">© <?php echo date('Y'); ?> Burton Mountain Homes. All rights reserved.</p>
</footer>


<?php wp_footer(); ?>
</body>
</html>
