<?php
/**
 * REST API Status Checker
 *
 * USAGE:
 * 1. Upload this file to your WordPress root directory
 * 2. Visit: https://myyachtsinsurance.com/rest-api-check.php
 * 3. You'll see a detailed status report
 * 4. DELETE this file after testing (security risk to leave it)
 *
 * OR add to functions.php temporarily:
 * Copy the code inside check_rest_api_status() and add to your active theme's functions.php
 */

// Prevent direct access when used as plugin
if (!defined('ABSPATH') && !isset($_GET['standalone'])) {
    // Load WordPress
    require_once(__DIR__ . '/wp-load.php');
}

function check_rest_api_status() {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>REST API Status Check - My Yachts Insurance</title>
        <style>
            body {
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                max-width: 800px;
                margin: 50px auto;
                padding: 20px;
                background: #f5f5f5;
            }
            .container {
                background: white;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            }
            h1 { color: #00a4ff; margin-top: 0; }
            h2 { color: #333; border-bottom: 2px solid #badde9; padding-bottom: 10px; }
            .status {
                padding: 15px;
                margin: 10px 0;
                border-radius: 5px;
                font-weight: bold;
            }
            .success { background: #10B981; color: white; }
            .warning { background: #F97316; color: white; }
            .error { background: #ef4444; color: white; }
            .info { background: #badde9; color: #181818; }
            .code {
                background: #f5f5f5;
                padding: 15px;
                border-left: 4px solid #00a4ff;
                overflow-x: auto;
                font-family: monospace;
                font-size: 14px;
            }
            .check { margin: 15px 0; padding: 10px; background: #f8f8f0; border-radius: 4px; }
            .check-title { font-weight: bold; margin-bottom: 5px; }
            .icon-success { color: #10B981; }
            .icon-error { color: #ef4444; }
            .icon-warning { color: #F97316; }
            ul { margin: 10px 0; padding-left: 20px; }
            li { margin: 5px 0; }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>🔍 REST API Status Check</h1>
            <p><strong>Site:</strong> <?php echo home_url(); ?></p>
            <p><strong>WordPress Version:</strong> <?php echo get_bloginfo('version'); ?></p>

            <?php
            // Check 1: Is REST API enabled?
            $rest_enabled = !defined('REST_API_DISABLED') || !REST_API_DISABLED;
            ?>

            <h2>1. REST API Configuration</h2>
            <div class="check">
                <div class="check-title">
                    <?php if ($rest_enabled): ?>
                        <span class="icon-success">✅</span> REST API is enabled in WordPress
                    <?php else: ?>
                        <span class="icon-error">❌</span> REST API is DISABLED via constant
                    <?php endif; ?>
                </div>
                <?php if (!$rest_enabled): ?>
                    <div class="status error">
                        Found: <code>define('REST_API_DISABLED', true);</code> in wp-config.php
                    </div>
                    <p><strong>Solution:</strong> Remove or comment out this line in wp-config.php</p>
                <?php endif; ?>
            </div>

            <?php
            // Check 2: Test REST API endpoint
            $rest_url = rest_url();
            $response = wp_remote_get($rest_url);
            $is_rest_working = !is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200;
            ?>

            <div class="check">
                <div class="check-title">
                    <?php if ($is_rest_working): ?>
                        <span class="icon-success">✅</span> REST API endpoint is accessible
                    <?php else: ?>
                        <span class="icon-error">❌</span> REST API endpoint is NOT accessible
                    <?php endif; ?>
                </div>
                <p><strong>Tested URL:</strong> <code><?php echo $rest_url; ?></code></p>
                <?php if (!$is_rest_working): ?>
                    <div class="status error">
                        Error: <?php echo is_wp_error($response) ? $response->get_error_message() : 'HTTP ' . wp_remote_retrieve_response_code($response); ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php
            // Check 3: Active security plugins
            $security_plugins = array(
                'wordfence/wordfence.php' => 'Wordfence Security',
                'better-wp-security/better-wp-security.php' => 'iThemes Security',
                'all-in-one-wp-security-and-firewall/wp-security.php' => 'All In One WP Security',
                'disable-rest-api/disable-rest-api.php' => 'Disable REST API',
                'disable-json-api/disable-json-api.php' => 'Disable JSON API',
            );

            $active_security = array();
            foreach ($security_plugins as $plugin_path => $plugin_name) {
                if (is_plugin_active($plugin_path)) {
                    $active_security[] = $plugin_name;
                }
            }
            ?>

            <h2>2. Security Plugins</h2>
            <div class="check">
                <?php if (empty($active_security)): ?>
                    <div class="check-title">
                        <span class="icon-success">✅</span> No known REST API blocking plugins detected
                    </div>
                <?php else: ?>
                    <div class="check-title">
                        <span class="icon-warning">⚠️</span> Found security plugins that may block REST API:
                    </div>
                    <ul>
                        <?php foreach ($active_security as $plugin): ?>
                            <li><strong><?php echo $plugin; ?></strong></li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="status warning">
                        These plugins often block REST API by default. Check their settings.
                    </div>
                <?php endif; ?>
            </div>

            <?php
            // Check 4: REST API authentication errors filter
            $has_auth_filter = has_filter('rest_authentication_errors');
            ?>

            <div class="check">
                <div class="check-title">
                    <?php if (!$has_auth_filter): ?>
                        <span class="icon-success">✅</span> No custom authentication filters detected
                    <?php else: ?>
                        <span class="icon-warning">⚠️</span> Custom REST authentication filter detected
                    <?php endif; ?>
                </div>
                <?php if ($has_auth_filter): ?>
                    <div class="status warning">
                        A plugin or theme is adding custom REST API authentication.
                        This may be blocking access.
                    </div>
                <?php endif; ?>
            </div>

            <h2>3. Recommended Actions</h2>

            <?php if (!$is_rest_working): ?>
                <div class="status error">
                    <strong>🔴 REST API is currently blocked</strong>
                </div>

                <h3>Try these solutions in order:</h3>
                <ol>
                    <li>
                        <strong>Fix Permalinks (Quick Fix):</strong>
                        <ul>
                            <li>Go to Settings > Permalinks</li>
                            <li>Click "Save Changes" (don't change anything)</li>
                            <li>This often fixes REST API issues</li>
                        </ul>
                    </li>

                    <?php if (!empty($active_security)): ?>
                    <li>
                        <strong>Configure Security Plugins:</strong>
                        <ul>
                            <?php foreach ($active_security as $plugin): ?>
                                <li><?php echo $plugin; ?>: Check settings for REST API options</li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <li>
                        <strong>Check .htaccess:</strong>
                        <ul>
                            <li>Look for rules blocking /wp-json/</li>
                            <li>Comment out any REST API blocking rules</li>
                        </ul>
                    </li>

                    <li>
                        <strong>Contact Hosting Provider:</strong>
                        <ul>
                            <li>Ask about ModSecurity rules</li>
                            <li>Check server firewall settings</li>
                        </ul>
                    </li>
                </ol>
            <?php else: ?>
                <div class="status success">
                    <strong>✅ REST API is working!</strong>
                </div>

                <h3>Next Steps:</h3>
                <ol>
                    <li>
                        <strong>Generate Application Password:</strong>
                        <ul>
                            <li>Go to Users > Profile</li>
                            <li>Scroll to "Application Passwords"</li>
                            <li>Create new password for API access</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Use Automation Script:</strong>
                        <div class="code">export WP_SITE_URL="<?php echo home_url(); ?>"
export WP_USERNAME="your-email@example.com"
export WP_APP_PASSWORD="your-app-password"
python3 scripts/auto_post.py</div>
                    </li>
                </ol>
            <?php endif; ?>

            <h2>4. Test REST API Manually</h2>
            <p>Visit this URL in your browser:</p>
            <div class="code">
                <a href="<?php echo rest_url(); ?>" target="_blank"><?php echo rest_url(); ?></a>
            </div>
            <p>You should see a JSON response with site information.</p>

            <div class="status info">
                <strong>⚠️ SECURITY:</strong> DELETE this file (rest-api-check.php) after testing!
            </div>
        </div>
    </body>
    </html>
    <?php
}

// Run the check
check_rest_api_status();
?>
