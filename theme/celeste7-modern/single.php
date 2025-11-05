<?php
/**
 * Single Post Template
 *
 * @package Celeste7_Modern
 */

get_header();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('max-w-4xl mx-auto px-4 py-12'); ?>>
    <?php while (have_posts()) : the_post(); ?>

        <!-- Featured Image -->
        <?php if (has_post_thumbnail()): ?>
            <div class="featured-image mb-8 rounded-lg overflow-hidden">
                <?php the_post_thumbnail('celeste7-hero', array('class' => 'w-full h-auto', 'loading' => 'eager')); ?>
            </div>
        <?php endif; ?>

        <!-- Post Header -->
        <header class="entry-header mb-8">
            <!-- Category -->
            <div class="entry-categories mb-3">
                <?php
                $categories = get_the_category();
                if (!empty($categories)) {
                    foreach ($categories as $category) {
                        echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="inline-block bg-signal-blue text-deep-black px-3 py-1 rounded-full text-sm font-medium mr-2 hover:bg-electric-blue hover:text-white transition-colors">' . esc_html($category->name) . '</a>';
                    }
                }
                ?>
            </div>

            <!-- Title -->
            <h1 class="entry-title text-4xl md:text-5xl font-bold text-deep-black mb-4 leading-tight">
                <?php the_title(); ?>
            </h1>

            <!-- Meta Information -->
            <div class="entry-meta flex flex-wrap items-center text-gray-600 text-sm gap-4">
                <!-- Author -->
                <div class="flex items-center">
                    <?php echo get_avatar(get_the_author_meta('ID'), 32, '', '', array('class' => 'rounded-full mr-2')); ?>
                    <span class="font-medium">
                        <?php the_author_posts_link(); ?>
                    </span>
                </div>

                <!-- Date -->
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                        <?php echo get_the_date(); ?>
                    </time>
                </div>

                <!-- Reading Time -->
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>
                        <?php
                        $content = get_post_field('post_content', get_the_ID());
                        $word_count = str_word_count(strip_tags($content));
                        $reading_time = ceil($word_count / 200); // 200 words per minute
                        echo $reading_time . ' min read';
                        ?>
                    </span>
                </div>

                <!-- Comment Count -->
                <?php if (comments_open()): ?>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <?php comments_number('0 Comments', '1 Comment', '% Comments'); ?>
                    </div>
                <?php endif; ?>
            </div>
        </header>

        <!-- Post Content -->
        <div class="entry-content prose prose-lg max-w-none mb-12">
            <?php
            the_content(sprintf(
                wp_kses(
                    __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'celeste7-modern'),
                    array('span' => array('class' => array()))
                ),
                get_the_title()
            ));

            wp_link_pages(array(
                'before' => '<div class="page-links flex items-center space-x-2 mt-8 pt-8 border-t border-gray-200"><span class="font-semibold">' . esc_html__('Pages:', 'celeste7-modern') . '</span>',
                'after'  => '</div>',
            ));
            ?>
        </div>

        <!-- Tags -->
        <?php
        $tags = get_the_tags();
        if ($tags):
        ?>
            <div class="entry-tags mb-8 pb-8 border-b border-gray-200">
                <div class="flex flex-wrap items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <?php foreach ($tags as $tag): ?>
                        <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm hover:bg-electric-blue hover:text-white transition-colors">
                            <?php echo esc_html($tag->name); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Author Bio -->
        <?php
        $author_bio = get_the_author_meta('description');
        if ($author_bio):
        ?>
            <div class="author-bio bg-ghost-white rounded-lg p-6 mb-12">
                <div class="flex items-start">
                    <div class="flex-shrink-0 mr-4">
                        <?php echo get_avatar(get_the_author_meta('ID'), 80, '', '', array('class' => 'rounded-full')); ?>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold mb-2">
                            About <?php the_author(); ?>
                        </h3>
                        <p class="text-gray-700 mb-3">
                            <?php echo esc_html($author_bio); ?>
                        </p>
                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="text-electric-blue hover:text-apple-blue font-medium">
                            View all posts by <?php the_author(); ?> →
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Post Navigation -->
        <?php celeste7_post_navigation(); ?>

        <!-- Related Posts -->
        <?php
        $related_posts = get_posts(array(
            'category__in'   => wp_get_post_categories(get_the_ID()),
            'numberposts'    => 3,
            'post__not_in'   => array(get_the_ID()),
            'orderby'        => 'rand',
        ));

        if ($related_posts):
        ?>
            <div class="related-posts mt-12 pt-12 border-t border-gray-200">
                <h2 class="text-3xl font-bold mb-8">Related Articles</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <?php foreach ($related_posts as $related_post): ?>
                        <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow">
                            <?php if (has_post_thumbnail($related_post->ID)): ?>
                                <a href="<?php echo esc_url(get_permalink($related_post->ID)); ?>">
                                    <?php echo get_the_post_thumbnail($related_post->ID, 'celeste7-featured', array('class' => 'w-full h-48 object-cover')); ?>
                                </a>
                            <?php endif; ?>
                            <div class="p-5">
                                <h3 class="text-lg font-semibold mb-2">
                                    <a href="<?php echo esc_url(get_permalink($related_post->ID)); ?>" class="text-deep-black hover:text-electric-blue transition-colors">
                                        <?php echo esc_html(get_the_title($related_post->ID)); ?>
                                    </a>
                                </h3>
                                <p class="text-gray-600 text-sm mb-3">
                                    <?php echo wp_trim_words(get_the_excerpt($related_post->ID), 15); ?>
                                </p>
                                <a href="<?php echo esc_url(get_permalink($related_post->ID)); ?>" class="text-electric-blue hover:text-apple-blue font-medium text-sm">
                                    Read More →
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php
        endif;
        wp_reset_postdata();
        ?>

        <!-- Comments -->
        <?php
        if (comments_open() || get_comments_number()):
            comments_template();
        endif;
        ?>

    <?php endwhile; ?>
</article>

<?php get_footer(); ?>
