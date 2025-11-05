<?php
/**
 * Celeste7 Modern Theme Functions
 *
 * Performance-optimized WordPress theme with Tailwind CSS integration
 * Built for Core Web Vitals excellence and maritime brand identity
 *
 * @package Celeste7_Modern
 * @version 1.0.0
 */

// Security: Prevent direct file access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Setup
 */
function celeste7_theme_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(1200, 675, true);

    // Add custom image sizes
    add_image_size('celeste7-hero', 1920, 1080, true);
    add_image_size('celeste7-featured', 800, 450, true);
    add_image_size('celeste7-thumbnail', 400, 225, true);

    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'celeste7-modern'),
        'footer' => __('Footer Menu', 'celeste7-modern'),
    ));

    // Switch default core markup to HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Add theme support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for Block Styles
    add_theme_support('wp-block-styles');

    // Add support for full and wide align images
    add_theme_support('align-wide');

    // Add support for editor styles
    add_theme_support('editor-styles');

    // Add support for responsive embedded content
    add_theme_support('responsive-embeds');
}
add_action('after_setup_theme', 'celeste7_theme_setup');

/**
 * Enqueue Scripts and Styles
 */
function celeste7_enqueue_assets() {
    // Main stylesheet
    wp_enqueue_style('celeste7-style', get_stylesheet_uri(), array(), '1.0.0');

    // Google Fonts - Poppins (preconnect for performance)
    wp_enqueue_style(
        'celeste7-fonts',
        'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap',
        array(),
        null
    );

    // Tailwind CSS from CDN (for development - replace with compiled version in production)
    wp_enqueue_script(
        'tailwind-cdn',
        'https://cdn.tailwindcss.com',
        array(),
        null,
        false
    );

    // Tailwind configuration
    wp_add_inline_script('tailwind-cdn', "
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'ghost-white': '#F8F8F0',
                        'signal-blue': '#badde9',
                        'electric-blue': '#00a4ff',
                        'apple-blue': '#0070ff',
                        'deep-black': '#181818',
                        'success-green': '#10B981',
                        'alert-orange': '#F97316',
                        'light-grey': '#E5E5E5',
                        'document-white': '#F8FAFC',
                    },
                    fontFamily: {
                        'primary': ['Poppins', 'sans-serif'],
                        'mono': ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    ", 'before');

    // Main JavaScript (deferred for performance)
    wp_enqueue_script(
        'celeste7-main',
        get_template_directory_uri() . '/js/main.js',
        array(),
        '1.0.0',
        true
    );

    // Add comments script if needed
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'celeste7_enqueue_assets');

/**
 * Performance Optimization: Defer JavaScript
 */
function celeste7_defer_scripts($tag, $handle, $src) {
    // Scripts to defer (exclude certain critical scripts)
    $defer_scripts = array('celeste7-main');

    if (in_array($handle, $defer_scripts)) {
        return str_replace(' src', ' defer src', $tag);
    }

    return $tag;
}
add_filter('script_loader_tag', 'celeste7_defer_scripts', 10, 3);

/**
 * Performance Optimization: Add Preconnect for Google Fonts
 */
function celeste7_add_resource_hints($urls, $relation_type) {
    if (wp_style_is('celeste7-fonts', 'queue') && 'preconnect' === $relation_type) {
        $urls[] = array(
            'href' => 'https://fonts.googleapis.com',
            'crossorigin',
        );
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
    }
    return $urls;
}
add_filter('wp_resource_hints', 'celeste7_add_resource_hints', 10, 2);

/**
 * Performance Optimization: Lazy Load Images
 */
function celeste7_add_lazy_loading($content) {
    // Add loading="lazy" to images
    $content = preg_replace('/<img((?![^>]*loading=)[^>]*)>/', '<img$1 loading="lazy">', $content);
    return $content;
}
add_filter('the_content', 'celeste7_add_lazy_loading');

/**
 * Register Widget Areas
 */
function celeste7_widgets_init() {
    // Footer widgets
    for ($i = 1; $i <= 3; $i++) {
        register_sidebar(array(
            'name'          => sprintf(__('Footer Widget %d', 'celeste7-modern'), $i),
            'id'            => 'footer-' . $i,
            'description'   => sprintf(__('Footer widget area %d', 'celeste7-modern'), $i),
            'before_widget' => '<div id="%1$s" class="widget %2$s mb-4">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title text-lg font-semibold mb-3">',
            'after_title'   => '</h3>',
        ));
    }
}
add_action('widgets_init', 'celeste7_widgets_init');

/**
 * Custom Excerpt Length
 */
function celeste7_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'celeste7_excerpt_length');

/**
 * Custom Excerpt More
 */
function celeste7_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'celeste7_excerpt_more');

/**
 * Add JSON-LD Structured Data for Articles
 */
function celeste7_add_jsonld_schema() {
    if (is_single()) {
        global $post;

        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => get_the_title(),
            'description' => get_the_excerpt(),
            'author' => array(
                '@type' => 'Person',
                'name' => get_the_author()
            ),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'publisher' => array(
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'logo' => array(
                    '@type' => 'ImageObject',
                    'url' => get_site_icon_url()
                )
            )
        );

        if (has_post_thumbnail()) {
            $schema['image'] = get_the_post_thumbnail_url(null, 'full');
        }

        echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>';
    }
}
add_action('wp_head', 'celeste7_add_jsonld_schema');

/**
 * Add Organization Schema
 */
function celeste7_add_organization_schema() {
    if (is_front_page()) {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'url' => home_url(),
            'logo' => get_site_icon_url(),
            'description' => get_bloginfo('description'),
            'contactPoint' => array(
                '@type' => 'ContactPoint',
                'contactType' => 'customer service'
            )
        );

        echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>';
    }
}
add_action('wp_head', 'celeste7_add_organization_schema');

/**
 * Security: Remove WordPress Version
 */
remove_action('wp_head', 'wp_generator');

/**
 * Performance: Remove Emoji Scripts
 */
function celeste7_disable_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
}
add_action('init', 'celeste7_disable_emojis');

/**
 * Newsletter Form Shortcode
 */
function celeste7_newsletter_form($atts) {
    $atts = shortcode_atts(array(
        'title' => 'Stay Updated',
        'description' => 'Get the latest insights on yacht insurance and maritime safety.',
    ), $atts);

    ob_start();
    ?>
    <div class="newsletter-form bg-signal-blue p-6 rounded-lg">
        <h3 class="text-xl font-semibold mb-2"><?php echo esc_html($atts['title']); ?></h3>
        <p class="mb-4 text-sm"><?php echo esc_html($atts['description']); ?></p>
        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="flex gap-2">
            <input type="hidden" name="action" value="celeste7_newsletter">
            <input
                type="email"
                name="newsletter_email"
                placeholder="Enter your email"
                required
                class="flex-1 px-4 py-2 rounded border-0"
            >
            <button type="submit" class="btn btn-primary whitespace-nowrap">
                Subscribe
            </button>
        </form>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('newsletter', 'celeste7_newsletter_form');

/**
 * Handle Newsletter Submission
 */
function celeste7_handle_newsletter_submission() {
    if (isset($_POST['newsletter_email'])) {
        $email = sanitize_email($_POST['newsletter_email']);

        // Here you would typically integrate with your email service
        // For now, we'll just store in WordPress options or database

        // Example: Store in options (not recommended for production)
        $subscribers = get_option('celeste7_newsletter_subscribers', array());
        if (!in_array($email, $subscribers)) {
            $subscribers[] = $email;
            update_option('celeste7_newsletter_subscribers', $subscribers);
        }

        // Redirect back with success message
        wp_redirect(add_query_arg('newsletter', 'subscribed', wp_get_referer()));
        exit;
    }
}
add_action('admin_post_celeste7_newsletter', 'celeste7_handle_newsletter_submission');
add_action('admin_post_nopriv_celeste7_newsletter', 'celeste7_handle_newsletter_submission');

/**
 * Custom Body Classes
 */
function celeste7_body_classes($classes) {
    // Add class for blog posts
    if (is_single()) {
        $classes[] = 'single-post-layout';
    }

    // Add class for pages
    if (is_page()) {
        $classes[] = 'page-layout';
    }

    return $classes;
}
add_filter('body_class', 'celeste7_body_classes');

/**
 * Custom Post Navigation
 */
function celeste7_post_navigation() {
    $prev_post = get_previous_post();
    $next_post = get_next_post();

    if (!$prev_post && !$next_post) {
        return;
    }
    ?>
    <nav class="post-navigation flex justify-between items-center py-8 border-t border-gray-200">
        <div class="prev-post flex-1">
            <?php if ($prev_post): ?>
                <a href="<?php echo get_permalink($prev_post); ?>" class="flex items-center text-electric-blue hover:text-apple-blue">
                    <span class="mr-2">←</span>
                    <span><?php echo esc_html(get_the_title($prev_post)); ?></span>
                </a>
            <?php endif; ?>
        </div>
        <div class="next-post flex-1 text-right">
            <?php if ($next_post): ?>
                <a href="<?php echo get_permalink($next_post); ?>" class="flex items-center justify-end text-electric-blue hover:text-apple-blue">
                    <span><?php echo esc_html(get_the_title($next_post)); ?></span>
                    <span class="ml-2">→</span>
                </a>
            <?php endif; ?>
        </div>
    </nav>
    <?php
}

/**
 * Custom Comment Callback
 */
function celeste7_comment_callback($comment, $args, $depth) {
    ?>
    <li id="comment-<?php comment_ID(); ?>" <?php comment_class('flex space-x-4 bg-ghost-white p-5 rounded-lg'); ?>>
        <div class="flex-shrink-0">
            <?php echo get_avatar($comment, 50, '', '', array('class' => 'rounded-full')); ?>
        </div>
        <div class="flex-1">
            <div class="flex items-center justify-between mb-2">
                <div class="font-semibold text-deep-black">
                    <?php comment_author_link(); ?>
                </div>
                <div class="text-xs text-gray-500">
                    <a href="<?php echo esc_url(get_comment_link($comment, $args)); ?>" class="text-gray-500 hover:text-electric-blue">
                        <?php printf(__('%s ago', 'celeste7-modern'), human_time_diff(get_comment_time('U'), current_time('timestamp'))); ?>
                    </a>
                </div>
            </div>
            <div class="text-gray-700 mb-3">
                <?php comment_text(); ?>
            </div>
            <div class="flex items-center space-x-4 text-sm">
                <?php
                comment_reply_link(array_merge($args, array(
                    'depth'      => $depth,
                    'max_depth'  => $args['max_depth'],
                    'reply_text' => 'Reply',
                    'before'     => '<span class="text-electric-blue hover:text-apple-blue cursor-pointer">',
                    'after'      => '</span>',
                )));
                ?>
                <?php if (get_edit_comment_link()): ?>
                    <a href="<?php echo get_edit_comment_link(); ?>" class="text-gray-500 hover:text-electric-blue">Edit</a>
                <?php endif; ?>
            </div>
        </div>
    </li>
    <?php
}

/**
 * Custom Navigation Walker
 */
class Celeste7_Walker_Nav_Menu extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $output .= '<li' . $class_names . '>';

        $atts = array();
        $atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel']    = !empty($item->xfn) ? $item->xfn : '';
        $atts['href']   = !empty($item->url) ? $item->url : '';
        $atts['class']  = 'text-deep-black hover:text-electric-blue transition-colors font-medium';

        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}
