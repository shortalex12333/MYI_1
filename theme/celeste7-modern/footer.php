</main><!-- #primary -->

<footer class="site-footer bg-deep-black text-ghost-white">
    <div class="container mx-auto px-4 py-12">
        <!-- Newsletter Section -->
        <div class="newsletter-section bg-gradient-to-r from-signal-blue to-electric-blue rounded-lg p-8 mb-12 text-deep-black">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-3xl font-bold mb-3">Stay Informed</h2>
                <p class="mb-6 text-lg">Get expert insights on yacht insurance, maritime safety, and industry updates delivered to your inbox.</p>

                <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="flex flex-col sm:flex-row gap-3 max-w-xl mx-auto">
                    <input type="hidden" name="action" value="celeste7_newsletter">
                    <input
                        type="email"
                        name="newsletter_email"
                        placeholder="Enter your email address"
                        required
                        class="flex-1 px-6 py-3 rounded-lg border-0 focus:outline-none focus:ring-2 focus:ring-deep-black"
                    >
                    <button type="submit" class="bg-deep-black text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-800 transition-colors whitespace-nowrap">
                        Subscribe
                    </button>
                </form>

                <?php if (isset($_GET['newsletter']) && $_GET['newsletter'] === 'subscribed'): ?>
                    <div class="mt-4 p-3 bg-success-green text-white rounded-lg">
                        ✓ Thank you for subscribing! Check your email for confirmation.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Footer Widgets -->
        <div class="footer-content grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <!-- About Section -->
            <div class="footer-widget">
                <h3 class="text-xl font-semibold mb-4 text-signal-blue">
                    <?php bloginfo('name'); ?>
                </h3>
                <p class="text-gray-300 mb-4">
                    <?php
                    $description = get_bloginfo('description');
                    echo $description ? esc_html($description) : 'Premium yacht insurance solutions with maritime expertise and technical precision.';
                    ?>
                </p>
                <?php if (is_active_sidebar('footer-1')): ?>
                    <?php dynamic_sidebar('footer-1'); ?>
                <?php endif; ?>
            </div>

            <!-- Quick Links -->
            <div class="footer-widget">
                <h3 class="text-xl font-semibold mb-4 text-signal-blue">Quick Links</h3>
                <?php if (is_active_sidebar('footer-2')): ?>
                    <?php dynamic_sidebar('footer-2'); ?>
                <?php else: ?>
                    <ul class="space-y-2">
                        <li><a href="<?php echo esc_url(home_url('/about')); ?>" class="text-gray-300 hover:text-signal-blue transition-colors">About Us</a></li>
                        <li><a href="<?php echo esc_url(home_url('/coverage')); ?>" class="text-gray-300 hover:text-signal-blue transition-colors">Coverage Options</a></li>
                        <li><a href="<?php echo esc_url(home_url('/claims')); ?>" class="text-gray-300 hover:text-signal-blue transition-colors">Claims Process</a></li>
                        <li><a href="<?php echo esc_url(home_url('/blog')); ?>" class="text-gray-300 hover:text-signal-blue transition-colors">Blog & Resources</a></li>
                        <li><a href="<?php echo esc_url(home_url('/contact')); ?>" class="text-gray-300 hover:text-signal-blue transition-colors">Contact Us</a></li>
                    </ul>
                <?php endif; ?>
            </div>

            <!-- Services -->
            <div class="footer-widget">
                <h3 class="text-xl font-semibold mb-4 text-signal-blue">Services</h3>
                <?php if (is_active_sidebar('footer-3')): ?>
                    <?php dynamic_sidebar('footer-3'); ?>
                <?php else: ?>
                    <ul class="space-y-2">
                        <li><a href="<?php echo esc_url(home_url('/superyacht-insurance')); ?>" class="text-gray-300 hover:text-signal-blue transition-colors">Superyacht Insurance</a></li>
                        <li><a href="<?php echo esc_url(home_url('/crew-insurance')); ?>" class="text-gray-300 hover:text-signal-blue transition-colors">Crew Insurance</a></li>
                        <li><a href="<?php echo esc_url(home_url('/marine-liability')); ?>" class="text-gray-300 hover:text-signal-blue transition-colors">Marine Liability</a></li>
                        <li><a href="<?php echo esc_url(home_url('/risk-assessment')); ?>" class="text-gray-300 hover:text-signal-blue transition-colors">Risk Assessment</a></li>
                        <li><a href="<?php echo esc_url(home_url('/claims-support')); ?>" class="text-gray-300 hover:text-signal-blue transition-colors">24/7 Claims Support</a></li>
                    </ul>
                <?php endif; ?>
            </div>

            <!-- Contact Info -->
            <div class="footer-widget">
                <h3 class="text-xl font-semibold mb-4 text-signal-blue">Get in Touch</h3>
                <ul class="space-y-3 text-gray-300">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span>+44 (0) 20 1234 5678</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <a href="mailto:info@myyachtsinsurance.com" class="hover:text-signal-blue transition-colors">info@myyachtsinsurance.com</a>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>London, United Kingdom</span>
                    </li>
                </ul>

                <!-- Social Media -->
                <div class="flex space-x-4 mt-6">
                    <a href="#" class="text-gray-300 hover:text-signal-blue transition-colors" aria-label="LinkedIn">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-300 hover:text-signal-blue transition-colors" aria-label="Twitter">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-300 hover:text-signal-blue transition-colors" aria-label="Facebook">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom border-t border-gray-700 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center text-gray-400 text-sm">
                <p class="mb-4 md:mb-0">
                    &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.
                </p>

                <?php if (has_nav_menu('footer')): ?>
                    <nav class="footer-nav">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'footer',
                            'container'      => false,
                            'menu_class'     => 'flex space-x-6',
                            'depth'          => 1,
                        ));
                        ?>
                    </nav>
                <?php else: ?>
                    <nav class="flex space-x-6">
                        <a href="<?php echo esc_url(home_url('/privacy-policy')); ?>" class="hover:text-signal-blue transition-colors">Privacy Policy</a>
                        <a href="<?php echo esc_url(home_url('/terms-of-service')); ?>" class="hover:text-signal-blue transition-colors">Terms of Service</a>
                        <a href="<?php echo esc_url(home_url('/sitemap.xml')); ?>" class="hover:text-signal-blue transition-colors">Sitemap</a>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

<!-- Back to Top Button -->
<button id="back-to-top" class="fixed bottom-8 right-8 bg-electric-blue text-white p-3 rounded-full shadow-lg opacity-0 pointer-events-none transition-opacity duration-300 hover:bg-apple-blue z-40" aria-label="Back to top">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
    </svg>
</button>

</body>
</html>
