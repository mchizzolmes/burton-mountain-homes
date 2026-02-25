<?php
/**
 * Template for displaying single property posts
 * Outputs own HTML structure to bypass GeneratePress containers
 *
 * @package Burton_Mountain_Homes
 */

// Get the property data
$post_id = get_the_ID();
$specs = bmh_get_specs($post_id);
$price = bmh_get_price($post_id);
$address = bmh_get_address($post_id);
$status = bmh_get_status($post_id);
$year_built = get_post_meta($post_id, '_bmh_year_built', true);
$mls_number = get_post_meta($post_id, '_bmh_mls_number', true);
$areas = get_the_terms($post_id, 'property_area');

// Get featured image
$hero_image = '';
if (has_post_thumbnail()) {
    $hero_image = get_the_post_thumbnail_url($post_id, 'property-hero');
} else {
    $hero_image = get_stylesheet_directory_uri() . '/assets/images/placeholder-property-hero.jpg';
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
</head>
<body <?php body_class('bmh-single-property'); ?>>
<?php wp_body_open(); ?>

<!-- Navigation -->
<nav class="bmh-nav bmh-nav-solid" style="
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 9999;
    padding: 1rem 4%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: rgba(26,39,68,0.97);
">
    <a href="<?php echo home_url(); ?>" style="
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 1.3rem;
        font-weight: 400;
        letter-spacing: 0.08em;
        color: #fff;
        text-decoration: none;
    ">Burton <span style="color: #c9a962;">Mountain</span> Homes</a>
    <div style="display: flex; gap: 2.5rem; align-items: center;">
        <a href="<?php echo home_url('/#properties'); ?>" style="color: rgba(255,255,255,0.9); text-decoration: none; font-size: 0.85rem; font-weight: 500; letter-spacing: 0.1em; text-transform: uppercase;">Properties</a>
        <a href="<?php echo home_url('/#about'); ?>" style="color: rgba(255,255,255,0.9); text-decoration: none; font-size: 0.85rem; font-weight: 500; letter-spacing: 0.1em; text-transform: uppercase;">About</a>
        <a href="<?php echo home_url('/#contact'); ?>" style="background: #c9a962; color: #1a2744; padding: 0.7rem 1.5rem; text-decoration: none; font-size: 0.85rem; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase;">Contact</a>
    </div>
</nav>

<!-- Spacer for fixed nav -->
<div style="height: 60px;"></div>

<?php while (have_posts()) : the_post(); ?>

<!-- Property Hero -->
<section class="bmh-single-property-hero">
    <img src="<?php echo esc_url($hero_image); ?>" alt="<?php the_title_attribute(); ?>">
</section>

<!-- Property Header -->
<header class="bmh-single-property-header">
    <div class="bmh-single-property-header-inner">
        <div class="bmh-single-property-title">
            <h1><?php the_title(); ?></h1>
            <p class="bmh-single-property-location">
                <?php echo esc_html($address); ?>
                <?php if ($areas && !is_wp_error($areas)) : ?>
                    • <?php echo esc_html($areas[0]->name); ?>
                <?php endif; ?>
            </p>
            <?php echo bmh_get_status_badge(); ?>
        </div>
        <div class="bmh-single-property-price">
            <?php echo $price; ?>
        </div>
    </div>
</header>

<!-- Property Content -->
<div class="bmh-single-property-content">
    <main class="bmh-property-description">
        <!-- Quick Specs -->
        <div style="display: flex; gap: 3rem; margin-bottom: 2.5rem; padding-bottom: 2.5rem; border-bottom: 1px solid rgba(0,0,0,0.1);">
            <?php if ($specs['bedrooms']) : ?>
                <div style="text-align: center;">
                    <div style="font-family: 'Playfair Display', serif; font-size: 2.5rem; color: #1a2744;"><?php echo esc_html($specs['bedrooms']); ?></div>
                    <div style="font-size: 0.8rem; color: #3d4f5f; text-transform: uppercase; letter-spacing: 0.1em;">Bedrooms</div>
                </div>
            <?php endif; ?>
            <?php if ($specs['bathrooms']) : ?>
                <div style="text-align: center;">
                    <div style="font-family: 'Playfair Display', serif; font-size: 2.5rem; color: #1a2744;"><?php echo esc_html($specs['bathrooms']); ?></div>
                    <div style="font-size: 0.8rem; color: #3d4f5f; text-transform: uppercase; letter-spacing: 0.1em;">Bathrooms</div>
                </div>
            <?php endif; ?>
            <?php if ($specs['sqft']) : ?>
                <div style="text-align: center;">
                    <div style="font-family: 'Playfair Display', serif; font-size: 2.5rem; color: #1a2744;"><?php echo number_format((int)$specs['sqft']); ?></div>
                    <div style="font-size: 0.8rem; color: #3d4f5f; text-transform: uppercase; letter-spacing: 0.1em;">Sq Ft</div>
                </div>
            <?php endif; ?>
        </div>
        
        <h2>About This Property</h2>
        <div class="bmh-property-content-text">
            <?php the_content(); ?>
        </div>
        
        <?php 
        // Gallery if ACF is installed
        if (function_exists('get_field')) :
            $gallery = get_field('gallery');
            if ($gallery && is_array($gallery)) : 
        ?>
        <div style="margin-top: 3rem;">
            <h2>Gallery</h2>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-top: 1.5rem;">
                <?php foreach ($gallery as $image) : ?>
                    <a href="<?php echo esc_url($image['url']); ?>" target="_blank">
                        <img src="<?php echo esc_url($image['sizes']['medium_large']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" style="width: 100%; height: 200px; object-fit: cover;">
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php 
            endif;
        endif; 
        ?>
    </main>
    
    <aside class="bmh-property-sidebar">
        <!-- Property Details -->
        <div class="bmh-property-details-list">
            <h3>Property Details</h3>
            
            <?php if ($specs['bedrooms']) : ?>
            <div class="bmh-detail-row">
                <span class="bmh-detail-label">Bedrooms</span>
                <span class="bmh-detail-value"><?php echo esc_html($specs['bedrooms']); ?></span>
            </div>
            <?php endif; ?>
            
            <?php if ($specs['bathrooms']) : ?>
            <div class="bmh-detail-row">
                <span class="bmh-detail-label">Bathrooms</span>
                <span class="bmh-detail-value"><?php echo esc_html($specs['bathrooms']); ?></span>
            </div>
            <?php endif; ?>
            
            <?php if ($specs['sqft']) : ?>
            <div class="bmh-detail-row">
                <span class="bmh-detail-label">Square Feet</span>
                <span class="bmh-detail-value"><?php echo number_format((int)$specs['sqft']); ?></span>
            </div>
            <?php endif; ?>
            
            <?php if ($year_built) : ?>
            <div class="bmh-detail-row">
                <span class="bmh-detail-label">Year Built</span>
                <span class="bmh-detail-value"><?php echo esc_html($year_built); ?></span>
            </div>
            <?php endif; ?>
            
            <?php if ($mls_number) : ?>
            <div class="bmh-detail-row">
                <span class="bmh-detail-label">MLS #</span>
                <span class="bmh-detail-value"><?php echo esc_html($mls_number); ?></span>
            </div>
            <?php endif; ?>
            
            <?php if ($status) : ?>
            <div class="bmh-detail-row">
                <span class="bmh-detail-label">Status</span>
                <span class="bmh-detail-value"><?php echo esc_html($status->name); ?></span>
            </div>
            <?php endif; ?>
            
            <?php if ($areas && !is_wp_error($areas)) : ?>
            <div class="bmh-detail-row">
                <span class="bmh-detail-label">Area</span>
                <span class="bmh-detail-value"><?php echo esc_html($areas[0]->name); ?></span>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Contact Card -->
        <div style="background: #1a2744; padding: 2rem; margin-top: 2rem; color: #fff;">
            <h3 style="color: #fff; font-family: 'Playfair Display', serif; font-size: 1.3rem; margin-bottom: 1rem;">Interested in This Property?</h3>
            <p style="color: rgba(255,255,255,0.8); font-size: 0.95rem; margin-bottom: 1.5rem;">
                Contact us today for a private showing or more information.
            </p>
            <a href="mailto:bburton@livsothebysrealty.com?subject=Inquiry: <?php echo urlencode(get_the_title()); ?>" class="bmh-btn bmh-btn-primary" style="display: block; text-align: center; width: 100%;">
                Request Information
            </a>
            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.2);">
                <p style="color: #c9a962; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 0.25rem;">Bret Burton</p>
                <p style="margin-bottom: 0.5rem;"><a href="tel:9706881819" style="color: #fff; text-decoration: none;">(970) 688-1819</a></p>
                <p style="color: rgba(255,255,255,0.7); font-size: 0.85rem; margin: 0;">LIV Sotheby's International Realty</p>
            </div>
        </div>
    </aside>
</div>

<?php endwhile; ?>

<!-- More Properties -->
<?php
$related_args = array(
    'post_type'      => 'property',
    'posts_per_page' => 3,
    'post__not_in'   => array(get_the_ID()),
    'orderby'        => 'rand',
);

if ($areas && !is_wp_error($areas)) {
    $related_args['tax_query'] = array(
        array(
            'taxonomy' => 'property_area',
            'field'    => 'term_id',
            'terms'    => $areas[0]->term_id,
        ),
    );
}

$related_query = new WP_Query($related_args);

if ($related_query->have_posts()) :
?>
<section class="bmh-section" style="background: #faf8f5;">
    <div class="bmh-section-header">
        <h2>More Properties</h2>
    </div>
    <div class="bmh-properties-grid" style="max-width: 1200px; margin: 0 auto;">
        <?php while ($related_query->have_posts()) : $related_query->the_post(); 
            $rel_specs = bmh_get_specs();
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
                        <?php if ($rel_specs['bedrooms']) : ?><span><?php echo esc_html($rel_specs['bedrooms']); ?> Beds</span><?php endif; ?>
                        <?php if ($rel_specs['bathrooms']) : ?><span><?php echo esc_html($rel_specs['bathrooms']); ?> Baths</span><?php endif; ?>
                        <?php if ($rel_specs['sqft']) : ?><span><?php echo number_format((int)$rel_specs['sqft']); ?> Sq Ft</span><?php endif; ?>
                    </div>
                </div>
            </a>
        </article>
        <?php endwhile; wp_reset_postdata(); ?>
    </div>
</section>
<?php endif; ?>

<!-- Contact CTA -->
<section class="bmh-contact-cta">
    <h2>Let's Find Your Mountain Home</h2>
    <p>
        Whether you're buying, selling, or exploring investment opportunities, 
        we'd love to learn about your goals.
    </p>
    <a href="mailto:bburton@livsothebysrealty.com" class="bmh-btn bmh-btn-primary">Start the Conversation</a>
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
