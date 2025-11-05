#!/usr/bin/env python3
"""
WordPress REST API Diagnostic Tool
Identifies why the REST API is blocked and provides solutions

Usage:
    python3 diagnose_rest_api.py
"""

import requests
import json

SITE_URL = "https://myyachtsinsurance.com"

def test_endpoint(url, description):
    """Test an endpoint and return status"""
    print(f"\n{'=' * 60}")
    print(f"Testing: {description}")
    print(f"URL: {url}")
    print(f"{'=' * 60}")

    try:
        response = requests.get(url, timeout=30)
        print(f"Status Code: {response.status_code}")

        if response.status_code == 200:
            print(f"✅ SUCCESS - Endpoint is accessible")
            try:
                data = response.json()
                print(f"Response Type: JSON")
                if isinstance(data, dict):
                    print(f"Keys: {', '.join(list(data.keys())[:10])}")
            except:
                print(f"Response: {response.text[:200]}")
        elif response.status_code == 403:
            print(f"❌ BLOCKED - 403 Forbidden")
            print(f"Response: {response.text[:500]}")
            print(f"\n🔍 This indicates:")
            print(f"   - Security plugin blocking REST API")
            print(f"   - Server firewall rule")
            print(f"   - .htaccess restriction")
            print(f"   - Code-level REST API disable")
        elif response.status_code == 404:
            print(f"❌ NOT FOUND - 404")
            print(f"🔍 This indicates:")
            print(f"   - Permalinks not configured (go to Settings > Permalinks, save)")
            print(f"   - REST API completely disabled")
        else:
            print(f"⚠️  Unexpected status: {response.status_code}")
            print(f"Response: {response.text[:200]}")

        return response.status_code

    except requests.exceptions.Timeout:
        print(f"❌ TIMEOUT - Server not responding")
        return None
    except requests.exceptions.ConnectionError:
        print(f"❌ CONNECTION ERROR - Cannot reach server")
        return None
    except Exception as e:
        print(f"❌ ERROR: {str(e)}")
        return None

def main():
    print(f"""
╔══════════════════════════════════════════════════════════╗
║   WordPress REST API Diagnostic Tool                    ║
║   Site: {SITE_URL}                    ║
╚══════════════════════════════════════════════════════════╝
""")

    # Test 1: Main REST API endpoint
    status_main = test_endpoint(
        f"{SITE_URL}/wp-json/",
        "Main REST API Endpoint"
    )

    # Test 2: WordPress core endpoint
    status_core = test_endpoint(
        f"{SITE_URL}/wp-json/wp/v2/posts?per_page=1",
        "Posts Endpoint (should be public)"
    )

    # Test 3: Users endpoint
    status_users = test_endpoint(
        f"{SITE_URL}/wp-json/wp/v2/users",
        "Users Endpoint (should be public)"
    )

    # Summary and recommendations
    print(f"\n{'=' * 60}")
    print(f"DIAGNOSTIC SUMMARY")
    print(f"{'=' * 60}\n")

    if status_main == 403:
        print(f"🔴 REST API is BLOCKED (403 Forbidden)\n")
        print(f"LIKELY CAUSES:\n")
        print(f"1. Security Plugin (Most Common):")
        print(f"   - Wordfence Security")
        print(f"   - iThemes Security")
        print(f"   - All In One WP Security")
        print(f"   - Disable REST API plugin\n")
        print(f"2. Server-Level Firewall:")
        print(f"   - Cloudflare WAF")
        print(f"   - ModSecurity rules")
        print(f"   - cPanel firewall\n")
        print(f"3. .htaccess Rules:")
        print(f"   - Custom blocking rules")
        print(f"   - Security hardening\n")

        print(f"\n{'=' * 60}")
        print(f"STEP-BY-STEP SOLUTIONS")
        print(f"{'=' * 60}\n")

        print(f"✅ SOLUTION 1: Check Security Plugins\n")
        print(f"1. Log in to WordPress Admin:")
        print(f"   {SITE_URL}/wp-admin\n")
        print(f"2. Go to: Plugins > Installed Plugins\n")
        print(f"3. Look for these plugins:")
        print(f"   - Wordfence")
        print(f"   - iThemes Security")
        print(f"   - Disable REST API")
        print(f"   - All In One WP Security\n")
        print(f"4. For each security plugin found:\n")
        print(f"   Wordfence:")
        print(f"   - Go to: Wordfence > All Options")
        print(f"   - Search for 'REST API'")
        print(f"   - Enable 'Allow REST API access'\n")
        print(f"   iThemes Security:")
        print(f"   - Go to: Security > Settings > WordPress Tweaks")
        print(f"   - Disable 'Disable REST API'")
        print(f"   - Or whitelist your IP address\n")
        print(f"   Disable REST API plugin:")
        print(f"   - Simply deactivate this plugin\n")

        print(f"\n✅ SOLUTION 2: Fix Permalinks (Quick Fix)\n")
        print(f"1. Go to: Settings > Permalinks")
        print(f"2. Don't change anything")
        print(f"3. Just click 'Save Changes'")
        print(f"4. This flushes rewrite rules and often fixes REST API\n")

        print(f"\n✅ SOLUTION 3: Check .htaccess File\n")
        print(f"1. Access via FTP or cPanel File Manager")
        print(f"2. Open: /public_html/.htaccess")
        print(f"3. Look for lines like:")
        print(f"   RewriteRule ^wp-json/ - [F,L]")
        print(f"   <Files wp-json>")
        print(f"       Deny from all")
        print(f"   </Files>\n")
        print(f"4. Comment out or remove these lines")
        print(f"5. Save and test again\n")

        print(f"\n✅ SOLUTION 4: Check theme/plugin code\n")
        print(f"1. Access via FTP: /wp-content/themes/[active-theme]/functions.php")
        print(f"2. Search for:")
        print(f"   rest_authentication_errors")
        print(f"   rest_disabled")
        print(f"3. Comment out any code that blocks REST API\n")

        print(f"\n✅ SOLUTION 5: Contact Your Host\n")
        print(f"If above solutions don't work:")
        print(f"1. Contact your hosting provider")
        print(f"2. Ask them to check:")
        print(f"   - ModSecurity rules blocking /wp-json/")
        print(f"   - Server firewall blocking REST API")
        print(f"   - Cloudflare WAF rules (if using Cloudflare)\n")

        print(f"\n{'=' * 60}")
        print(f"TESTING AFTER FIXES")
        print(f"{'=' * 60}\n")
        print(f"After making changes, test the REST API:")
        print(f"1. Visit in browser: {SITE_URL}/wp-json/")
        print(f"2. You should see a JSON response (structured text)")
        print(f"3. Run this script again to verify\n")

        print(f"\n{'=' * 60}")
        print(f"TEMPORARY WORKAROUND")
        print(f"{'=' * 60}\n")
        print(f"While REST API is blocked, you can:")
        print(f"1. Upload theme manually (theme/celeste7-modern.zip)")
        print(f"2. Create blog posts manually using topics.json as reference")
        print(f"3. Once REST API is fixed, use automation script\n")

    elif status_main == 404:
        print(f"🟡 REST API endpoint not found (404)\n")
        print(f"QUICK FIX:")
        print(f"1. Go to: {SITE_URL}/wp-admin/options-permalink.php")
        print(f"2. Click 'Save Changes' (don't change anything)")
        print(f"3. This will regenerate permalinks and fix REST API\n")

    elif status_main == 200:
        print(f"✅ REST API is WORKING!\n")
        print(f"You can now:")
        print(f"1. Generate Application Password:")
        print(f"   {SITE_URL}/wp-admin/profile.php")
        print(f"2. Set environment variables:")
        print(f"   export WP_SITE_URL='{SITE_URL}'")
        print(f"   export WP_USERNAME='your-email'")
        print(f"   export WP_APP_PASSWORD='your-password'")
        print(f"3. Run automation script:")
        print(f"   python3 scripts/auto_post.py\n")

    else:
        print(f"⚠️  Unexpected result - manual investigation required\n")

    print(f"\n{'=' * 60}")
    print(f"Need help? Check README.md for detailed troubleshooting")
    print(f"{'=' * 60}\n")

if __name__ == "__main__":
    main()
