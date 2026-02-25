<?php
/**
 * Reusable Navigation Partial
 * Usage: get_template_part('template-parts/nav', null, ['transparent' => true]);
 * Pass ['transparent' => true] for homepage (gradient), omit for solid nav.
 *
 * @package Burton_Mountain_Homes
 */

$transparent = $args['transparent'] ?? false;
$bg_style = $transparent
    ? 'background: linear-gradient(to bottom, rgba(26,39,68,0.85) 0%, rgba(26,39,68,0) 100%);'
    : 'background: rgba(26,39,68,0.97); box-shadow: 0 2px 20px rgba(0,0,0,0.15);';
?>

<nav class="bmh-nav<?php echo $transparent ? '' : ' bmh-nav-solid'; ?>" id="bmh-navbar" style="
    position: fixed;
    top: 0; left: 0; right: 0;
    z-index: 9999;
    padding: 1.25rem 4%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: background 0.3s ease;
    <?php echo $bg_style; ?>
">
    <a href="<?php echo home_url(); ?>" class="bmh-nav-logo">
        Burton <span>Mountain</span> Homes
    </a>

    <!-- Desktop links -->
    <div class="bmh-nav-links">
        <a href="<?php echo $transparent ? '#properties' : get_post_type_archive_link('property'); ?>">Properties</a>
        <a href="<?php echo $transparent ? '#about' : home_url('/#about'); ?>">About</a>
        <a href="<?php echo $transparent ? '#testimonials' : home_url('/#testimonials'); ?>">Reviews</a>
        <a href="<?php echo $transparent ? '#contact' : home_url('/#contact'); ?>" class="bmh-nav-cta">Contact</a>
    </div>

    <!-- Hamburger (mobile) -->
    <button class="bmh-hamburger" id="bmh-hamburger" aria-label="Open menu" aria-expanded="false">
        <span></span>
        <span></span>
        <span></span>
    </button>
</nav>

<!-- Mobile menu -->
<div class="bmh-mobile-menu" id="bmh-mobile-menu" aria-hidden="true">
    <a href="<?php echo $transparent ? '#properties' : get_post_type_archive_link('property'); ?>">Properties</a>
    <a href="<?php echo $transparent ? '#about' : home_url('/#about'); ?>">About</a>
    <a href="<?php echo $transparent ? '#testimonials' : home_url('/#testimonials'); ?>">Reviews</a>
    <a href="<?php echo $transparent ? '#contact' : home_url('/#contact'); ?>">Contact</a>
</div>
