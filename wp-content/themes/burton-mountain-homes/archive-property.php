<?php
/**
 * Template for displaying property archive
 * Outputs own HTML structure to bypass GeneratePress containers
 *
 * @package Burton_Mountain_Homes
 */
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
<body <?php body_class('bmh-archive-property'); ?>>
<?php wp_body_open(); ?>

<?php get_template_part('template-parts/nav', null, ['transparent' => false]); ?>

<!-- Spacer for fixed nav -->
<div style="height: 72px;"></div>

<!-- Page Header -->
<section class="bmh-hero" style="height: 50vh; min-height: 400px;">
    <div class="bmh-hero-bg" style="background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/images/properties-hero.jpg');"></div>
    <div class="bmh-hero-overlay"></div>
    <div class="bmh-hero-content">
        <p class="bmh-hero-credibility">Burton Mountain Homes</p>
        <h1>Our Properties</h1>
        <p class="bmh-hero-subtitle">
            Discover exceptional homes across the Vail Valley
        </p>
    </div>
</section>

<!-- Filter Bar -->
<div style="background: #fff; padding: 1.5rem 4%; border-bottom: 1px solid rgba(0,0,0,0.1);">
    <div style="max-width: 1400px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <!-- Status Filter -->
            <div>
                <?php
                $current_status = get_query_var('property_status');
                $statuses = get_terms(array('taxonomy' => 'property_status', 'hide_empty' => true));
                ?>
                <select onchange="if(this.value) window.location.href=this.value" style="padding: 0.75rem 2rem 0.75rem 1rem; border: 1px solid rgba(0,0,0,0.2); font-family: 'Source Sans 3', sans-serif; font-size: 0.9rem; background: #fff; cursor: pointer;">
                    <option value="<?php echo get_post_type_archive_link('property'); ?>">All Statuses</option>
                    <?php foreach ($statuses as $status) : ?>
                        <option value="<?php echo get_term_link($status); ?>" <?php selected($current_status, $status->slug); ?>>
                            <?php echo esc_html($status->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Area Filter -->
            <div>
                <?php
                $current_area = get_query_var('property_area');
                $areas = get_terms(array('taxonomy' => 'property_area', 'hide_empty' => true));
                ?>
                <select onchange="if(this.value) window.location.href=this.value" style="padding: 0.75rem 2rem 0.75rem 1rem; border: 1px solid rgba(0,0,0,0.2); font-family: 'Source Sans 3', sans-serif; font-size: 0.9rem; background: #fff; cursor: pointer;">
                    <option value="<?php echo get_post_type_archive_link('property'); ?>">All Areas</option>
                    <?php foreach ($areas as $area) : ?>
                        <option value="<?php echo get_term_link($area); ?>" <?php selected($current_area, $area->slug); ?>>
                            <?php echo esc_html($area->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div style="font-size: 0.9rem; color: #3d4f5f;">
            <?php
            global $wp_query;
            $total = $wp_query->found_posts;
            printf(_n('%s Property', '%s Properties', $total, 'burton-mountain-homes'), number_format($total));
            ?>
        </div>
    </div>
</div>

<!-- Properties Grid -->
<section class="bmh-section" style="background: #faf8f5;">
    <?php if (have_posts()) : ?>
        <div class="bmh-properties-grid" style="max-width: 1400px; margin: 0 auto;">
            <?php while (have_posts()) : the_post(); 
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
                                <?php if ($specs['bedrooms']) : ?>
                                    <span><?php echo esc_html($specs['bedrooms']); ?> Beds</span>
                                <?php endif; ?>
                                <?php if ($specs['bathrooms']) : ?>
                                    <span><?php echo esc_html($specs['bathrooms']); ?> Baths</span>
                                <?php endif; ?>
                                <?php if ($specs['sqft']) : ?>
                                    <span><?php echo number_format((int)$specs['sqft']); ?> Sq Ft</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                </article>
            <?php endwhile; ?>
        </div>
        
        <!-- Pagination -->
        <?php
        $pagination = paginate_links(array(
            'prev_text' => '&larr; Previous',
            'next_text' => 'Next &rarr;',
            'type'      => 'array',
        ));
        
        if ($pagination) :
        ?>
        <div class="bmh-pagination">
            <?php foreach ($pagination as $link) : ?>
                <?php echo $link; ?>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
    <?php else : ?>
        <div style="text-align: center; max-width: 600px; margin: 0 auto; padding: 4rem 2rem;">
            <h2 style="font-family: 'Playfair Display', serif; color: #1a2744;">No Properties Found</h2>
            <p style="margin: 1rem 0 2rem; color: #3d4f5f;">
                We don't have any properties matching your criteria at the moment. 
                Contact us to learn about upcoming listings or off-market opportunities.
            </p>
            <a href="<?php echo home_url('/#contact'); ?>" class="bmh-btn bmh-btn-primary">Contact Us</a>
        </div>
    <?php endif; ?>
</section>

<!-- Contact CTA -->
<section class="bmh-contact-cta">
    <h2>Looking for Something Specific?</h2>
    <p>
        Let us know what you're looking for. We often have access to off-market 
        properties and upcoming listings before they hit the market.
    </p>
    <a href="mailto:bburton@livsothebysrealty.com" class="bmh-btn bmh-btn-primary">Get in Touch</a>
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
