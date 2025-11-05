<?php
/**
 * Page Template
 *
 * @package Celeste7_Modern
 */

get_header();
?>

<div class="max-w-4xl mx-auto px-4 py-12">
    <?php while (have_posts()) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(''); ?>>
            <!-- Page Header -->
            <header class="entry-header mb-8">
                <h1 class="text-4xl md:text-5xl font-bold text-deep-black mb-4">
                    <?php the_title(); ?>
                </h1>

                <?php if (has_excerpt()) : ?>
                    <div class="text-xl text-gray-600 leading-relaxed">
                        <?php the_excerpt(); ?>
                    </div>
                <?php endif; ?>
            </header>

            <!-- Featured Image -->
            <?php if (has_post_thumbnail()) : ?>
                <div class="featured-image mb-8 rounded-lg overflow-hidden">
                    <?php the_post_thumbnail('celeste7-hero', array('class' => 'w-full h-auto')); ?>
                </div>
            <?php endif; ?>

            <!-- Page Content -->
            <div class="entry-content prose prose-lg max-w-none">
                <?php
                the_content();

                wp_link_pages(array(
                    'before' => '<div class="page-links flex items-center space-x-2 mt-8 pt-8 border-t border-gray-200"><span class="font-semibold">' . esc_html__('Pages:', 'celeste7-modern') . '</span>',
                    'after'  => '</div>',
                ));
                ?>
            </div>

            <!-- Comments -->
            <?php
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;
            ?>
        </article>

    <?php endwhile; ?>
</div>

<?php get_footer(); ?>
