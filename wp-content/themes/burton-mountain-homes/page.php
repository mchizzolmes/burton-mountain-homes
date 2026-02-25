<?php
/**
 * Standard Page Template
 * Used for generic WordPress pages (About, Contact, etc.)
 *
 * @package Burton_Mountain_Homes
 */

get_header();
?>

<main style="min-height: 60vh; padding: 4rem 4%; max-width: 900px; margin: 0 auto;">
    <?php while (have_posts()) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

            <header style="margin-bottom: 2.5rem; border-bottom: 1px solid rgba(0,0,0,0.08); padding-bottom: 2rem;">
                <h1 style="font-family: 'Playfair Display', serif; color: #1a2744; margin-bottom: 0;">
                    <?php the_title(); ?>
                </h1>
            </header>

            <div class="entry-content" style="font-size: 1.05rem; line-height: 1.8; color: #3d4f5f;">
                <?php the_content(); ?>
            </div>

        </article>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
