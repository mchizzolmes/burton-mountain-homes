<?php
/**
 * 404 Template
 *
 * @package Burton_Mountain_Homes
 */

get_header();
?>

<main style="min-height: 60vh; display: flex; align-items: center; justify-content: center; text-align: center; padding: 4rem 4%;">
    <div>
        <p class="bmh-section-label">404 — Page Not Found</p>
        <h1 style="font-family: 'Playfair Display', serif; color: #1a2744; font-size: clamp(2rem, 4vw, 3rem); margin-bottom: 1rem;">
            This Page Doesn't Exist
        </h1>
        <p style="color: #3d4f5f; font-size: 1.1rem; max-width: 500px; margin: 0 auto 2.5rem;">
            The page you're looking for may have moved or been removed.
            Let's get you back on track.
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="<?php echo home_url(); ?>" class="bmh-btn bmh-btn-primary">Back to Home</a>
            <a href="<?php echo get_post_type_archive_link('property'); ?>" class="bmh-btn bmh-btn-outline" style="color: #1a2744; border-color: rgba(0,0,0,0.3);">View Properties</a>
        </div>
    </div>
</main>

<?php get_footer(); ?>
