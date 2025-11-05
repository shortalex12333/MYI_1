<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <?php wp_head(); ?>
</head>

<body <?php body_class('antialiased'); ?>>
<?php wp_body_open(); ?>

<header class="site-header bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between py-4">
            <!-- Logo -->
            <div class="site-logo">
                <?php if (has_custom_logo()): ?>
                    <?php the_custom_logo(); ?>
                <?php else: ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="text-2xl font-bold text-deep-black hover:text-electric-blue transition-colors no-underline">
                        <?php bloginfo('name'); ?>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex items-center space-x-8">
                <?php
                if (has_nav_menu('primary')) {
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'container'      => false,
                        'menu_class'     => 'flex items-center space-x-6',
                        'fallback_cb'    => false,
                        'depth'          => 2,
                        'walker'         => new Celeste7_Walker_Nav_Menu(),
                    ));
                } else {
                    // Fallback menu
                    ?>
                    <ul class="flex items-center space-x-6">
                        <li><a href="<?php echo esc_url(home_url('/')); ?>" class="text-deep-black hover:text-electric-blue transition-colors font-medium">Home</a></li>
                        <li><a href="<?php echo esc_url(home_url('/about')); ?>" class="text-deep-black hover:text-electric-blue transition-colors font-medium">About</a></li>
                        <li><a href="<?php echo esc_url(home_url('/blog')); ?>" class="text-deep-black hover:text-electric-blue transition-colors font-medium">Blog</a></li>
                        <li><a href="<?php echo esc_url(home_url('/contact')); ?>" class="text-deep-black hover:text-electric-blue transition-colors font-medium">Contact</a></li>
                    </ul>
                    <?php
                }
                ?>

                <!-- Search Icon -->
                <button id="search-toggle" class="text-deep-black hover:text-electric-blue transition-colors" aria-label="Toggle search">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>

                <!-- CTA Button -->
                <a href="<?php echo esc_url(home_url('/get-quote')); ?>" class="btn btn-primary">
                    Get Quote
                </a>
            </nav>

            <!-- Mobile Menu Toggle -->
            <button id="mobile-menu-toggle" class="lg:hidden text-deep-black hover:text-electric-blue transition-colors" aria-label="Toggle menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path id="menu-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    <path id="close-icon" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Mobile Navigation -->
        <nav id="mobile-menu" class="hidden lg:hidden pb-4">
            <?php
            if (has_nav_menu('primary')) {
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'space-y-2',
                    'fallback_cb'    => false,
                    'depth'          => 2,
                ));
            } else {
                ?>
                <ul class="space-y-2">
                    <li><a href="<?php echo esc_url(home_url('/')); ?>" class="block py-2 text-deep-black hover:text-electric-blue transition-colors">Home</a></li>
                    <li><a href="<?php echo esc_url(home_url('/about')); ?>" class="block py-2 text-deep-black hover:text-electric-blue transition-colors">About</a></li>
                    <li><a href="<?php echo esc_url(home_url('/blog')); ?>" class="block py-2 text-deep-black hover:text-electric-blue transition-colors">Blog</a></li>
                    <li><a href="<?php echo esc_url(home_url('/contact')); ?>" class="block py-2 text-deep-black hover:text-electric-blue transition-colors">Contact</a></li>
                </ul>
                <?php
            }
            ?>
            <div class="mt-4">
                <a href="<?php echo esc_url(home_url('/get-quote')); ?>" class="btn btn-primary block text-center">
                    Get Quote
                </a>
            </div>
        </nav>

        <!-- Search Modal -->
        <div id="search-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-start justify-center pt-20">
            <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl mx-4">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold">Search</h3>
                        <button id="search-close" class="text-gray-400 hover:text-deep-black transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                        <div class="flex gap-2">
                            <input
                                type="search"
                                name="s"
                                placeholder="Search..."
                                class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-electric-blue focus:ring-2 focus:ring-electric-blue focus:ring-opacity-20"
                                value="<?php echo get_search_query(); ?>"
                                autofocus
                            >
                            <button type="submit" class="btn btn-primary px-6">
                                Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<main id="primary" class="site-main">
