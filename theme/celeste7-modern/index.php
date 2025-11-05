<?php
/**
 * Main Template File
 *
 * @package Celeste7_Modern
 */

get_header();
?>

<div class="max-w-6xl mx-auto px-4 py-12">
    <?php if (have_posts()) : ?>

        <!-- Page Header -->
        <?php if (is_home() && !is_front_page()) : ?>
            <header class="page-header mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-deep-black mb-4">
                    <?php single_post_title(); ?>
                </h1>
            </header>
        <?php elseif (is_archive()) : ?>
            <header class="page-header mb-12">
                <?php
                the_archive_title('<h1 class="text-4xl md:text-5xl font-bold text-deep-black mb-4">', '</h1>');
                the_archive_description('<div class="text-lg text-gray-600">', '</div>');
                ?>
            </header>
        <?php elseif (is_search()) : ?>
            <header class="page-header mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-deep-black mb-4">
                    <?php printf(esc_html__('Search Results for: %s', 'celeste7-modern'), '<span class="text-electric-blue">' . get_search_query() . '</span>'); ?>
                </h1>
            </header>
        <?php else : ?>
            <header class="page-header mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-deep-black mb-4">
                    Latest Articles
                </h1>
                <p class="text-lg text-gray-600">
                    Expert insights on yacht insurance, maritime safety, and industry best practices
                </p>
            </header>
        <?php endif; ?>

        <!-- Posts Grid -->
        <div class="posts-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            while (have_posts()) :
                the_post();
            ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1'); ?>>
                    <!-- Featured Image -->
                    <?php if (has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>" class="block overflow-hidden">
                            <?php the_post_thumbnail('celeste7-featured', array('class' => 'w-full h-56 object-cover hover:scale-105 transition-transform duration-300', 'loading' => 'lazy')); ?>
                        </a>
                    <?php else : ?>
                        <a href="<?php the_permalink(); ?>" class="block bg-gradient-to-br from-signal-blue to-electric-blue h-56 flex items-center justify-center">
                            <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </a>
                    <?php endif; ?>

                    <div class="p-6">
                        <!-- Category -->
                        <div class="mb-3">
                            <?php
                            $categories = get_the_category();
                            if (!empty($categories)) {
                                $category = $categories[0];
                                echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="text-xs font-semibold text-electric-blue hover:text-apple-blue uppercase tracking-wide">' . esc_html($category->name) . '</a>';
                            }
                            ?>
                        </div>

                        <!-- Title -->
                        <h2 class="text-xl font-bold mb-3">
                            <a href="<?php the_permalink(); ?>" class="text-deep-black hover:text-electric-blue transition-colors">
                                <?php the_title(); ?>
                            </a>
                        </h2>

                        <!-- Excerpt -->
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                            <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                        </p>

                        <!-- Meta -->
                        <div class="flex items-center justify-between text-xs text-gray-500 pt-4 border-t border-gray-100">
                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                <?php echo get_the_date(); ?>
                            </time>
                            <span>
                                <?php
                                $content = get_post_field('post_content', get_the_ID());
                                $word_count = str_word_count(strip_tags($content));
                                $reading_time = ceil($word_count / 200);
                                echo $reading_time . ' min read';
                                ?>
                            </span>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>

        <!-- Pagination -->
        <div class="pagination mt-12 flex justify-center">
            <?php
            the_posts_pagination(array(
                'mid_size'           => 2,
                'prev_text'          => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg> Previous',
                'next_text'          => 'Next <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>',
                'before_page_number' => '<span class="sr-only">Page </span>',
                'class'              => 'flex items-center space-x-2',
            ));
            ?>
        </div>

    <?php else : ?>

        <!-- No Posts Found -->
        <div class="no-results text-center py-20">
            <svg class="w-24 h-24 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>

            <h1 class="text-3xl font-bold text-deep-black mb-4">
                <?php
                if (is_search()) {
                    esc_html_e('No results found', 'celeste7-modern');
                } else {
                    esc_html_e('Nothing Found', 'celeste7-modern');
                }
                ?>
            </h1>

            <p class="text-gray-600 mb-8 max-w-md mx-auto">
                <?php
                if (is_search()) {
                    printf(
                        esc_html__('Sorry, no posts matched your search for "%s". Please try different keywords.', 'celeste7-modern'),
                        '<strong>' . get_search_query() . '</strong>'
                    );
                } else {
                    esc_html_e('It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'celeste7-modern');
                }
                ?>
            </p>

            <div class="max-w-md mx-auto">
                <?php get_search_form(); ?>
            </div>
        </div>

    <?php endif; ?>
</div>

<?php get_footer(); ?>
