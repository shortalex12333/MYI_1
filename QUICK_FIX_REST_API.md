# Quick Fix: WordPress REST API (403 Forbidden)

## ⚡ Solution 1: Flush Permalinks (Try This First)

1. Log in to WordPress Admin: https://myyachtsinsurance.com/wp-admin
2. Go to **Settings → Permalinks**
3. **Don't change anything** - just click **Save Changes**
4. Test: Visit https://myyachtsinsurance.com/wp-json/ (should see JSON)

**Why this works:** Regenerates .htaccess rewrite rules that REST API depends on.

---

## ⚡ Solution 2: Fix .htaccess (Apache Servers)

**Location:** `/public_html/.htaccess`

### Standard WordPress .htaccess

Replace your entire .htaccess with this:

```apache
# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
# END WordPress
```

**Key line for REST API:**
```apache
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
```
This passes authentication headers to PHP (required for REST API auth).

### Remove These if Present

Delete or comment out (add `#`) any lines like:

```apache
# Bad - blocks REST API
RewriteRule ^wp-json/ - [F,L]

# Bad - blocks REST API
<Files wp-json>
    Deny from all
</Files>

# Bad - blocks REST API
RewriteCond %{REQUEST_URI} wp-json
RewriteRule .* - [F,L]
```

---

## ⚡ Solution 3: Fix Nginx Configuration

**Location:** `/etc/nginx/sites-available/yourdomain.conf`

### Standard WordPress Nginx Config

```nginx
server {
    listen 80;
    server_name myyachtsinsurance.com www.myyachtsinsurance.com;
    root /var/www/html;
    index index.php index.html;

    # REST API routing
    location / {
        try_files $uri $uri/ /index.php?$args;
    }

    # Pass PHP to PHP-FPM
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;

        # REST API auth headers
        fastcgi_param HTTP_AUTHORIZATION $http_authorization;
    }

    # Deny access to sensitive files
    location ~ /\.ht {
        deny all;
    }
}
```

**Key parts for REST API:**
```nginx
location / {
    try_files $uri $uri/ /index.php?$args;  # Routes /wp-json/ correctly
}

fastcgi_param HTTP_AUTHORIZATION $http_authorization;  # Passes auth headers
```

**After editing:**
```bash
sudo nginx -t                    # Test config
sudo systemctl reload nginx      # Apply changes
```

---

## ⚡ Solution 4: Security Plugin Check

### Wordfence

```
Wordfence → All Options
Search: "REST API"
Enable: "Allow REST API access"
```

### iThemes Security

```
Security → Settings → WordPress Tweaks
Find: "Disable REST API"
Toggle: OFF (disabled)
```

### Disable REST API Plugin

```
Plugins → Installed Plugins
Find: "Disable REST API" or "Disable JSON API"
Action: Deactivate
```

---

## 🧪 Test After Each Fix

### Browser Test
Visit: https://myyachtsinsurance.com/wp-json/

**Success = See JSON:**
```json
{
  "name": "My Yachts Insurance",
  "description": "...",
  "url": "https://myyachtsinsurance.com",
  "namespaces": ["wp/v2", "wp/v1"]
}
```

**Failure = See:**
- "Access denied"
- 403 error
- Blank page

### Command Line Test
```bash
curl -I https://myyachtsinsurance.com/wp-json/

# Success: HTTP/1.1 200 OK
# Failure: HTTP/1.1 403 Forbidden
```

---

## 🔒 REST API Security Options

### Option 1: Fully Open (Recommended for Automation)

**Best for:**
- Auto-posting scripts
- Zapier/n8n integrations
- Mobile apps
- Third-party tools

**Current theme needs:** YES (for auto_post.py)

**No changes needed** - just ensure Application Passwords are used.

---

### Option 2: Logged-In Users Only

**Add to functions.php:**

```php
// Restrict REST API to authenticated users only
add_filter('rest_authentication_errors', function($result) {
    if (!is_user_logged_in()) {
        return new WP_Error(
            'rest_forbidden',
            'REST API restricted to authenticated users.',
            array('status' => 401)
        );
    }
    return $result;
});
```

**Drawback:** Breaks automation script (not recommended for your use case).

---

### Option 3: Whitelist Specific Endpoints

**Allow only posts/pages (block users/plugins):**

```php
add_filter('rest_authentication_errors', function($result) {
    // Allow these endpoints without auth
    $allowed = array(
        '/wp/v2/posts',
        '/wp/v2/pages',
        '/wp/v2/media',
        '/wp/v2/categories',
        '/wp/v2/tags'
    );

    $current = $_SERVER['REQUEST_URI'];

    foreach ($allowed as $endpoint) {
        if (strpos($current, $endpoint) !== false) {
            return $result; // Allow
        }
    }

    // Block everything else for non-authenticated users
    if (!is_user_logged_in()) {
        return new WP_Error('rest_forbidden', 'Unauthorized', array('status' => 401));
    }

    return $result;
});
```

---

### Option 4: IP Whitelist (Best for Automation Scripts)

**Add to .htaccess (Apache):**

```apache
# Allow REST API only from specific IPs
<If "%{REQUEST_URI} =~ m#^/wp-json/#">
    Require ip 1.2.3.4           # Your server IP
    Require ip 5.6.7.8           # Your office IP
    Require all denied
</If>
```

**Or use plugin:** "REST API IP Whitelist"

---

## 🎯 Recommended Security Setup for Your Use Case

**For myyachtsinsurance.com with auto-posting:**

1. ✅ **Keep REST API fully open** (needed for automation)
2. ✅ **Use Application Passwords** (not main admin password)
3. ✅ **Limit user permissions** (create dedicated API user with only "Author" role)
4. ✅ **Monitor API usage** (install "REST API Monitor" plugin)
5. ✅ **Use HTTPS only** (already have SSL)
6. ✅ **Rate limiting** (hosting provider or Cloudflare)

**Don't block REST API** - it's needed for your automation workflow.

---

## 📝 Quick Reference

| Issue | Fix |
|-------|-----|
| 403 on /wp-json/ | Flush permalinks (Settings → Permalinks → Save) |
| .htaccess blocking | Add standard WordPress rewrite rules |
| Nginx routing wrong | Update `location /` with `try_files` |
| Security plugin | Check Wordfence/iThemes settings |
| Need automation | Keep REST API open + use App Passwords |
| Need security | Use Application Passwords (not restrictions) |

---

## ✅ After REST API Works

1. **Generate Application Password:**
   - Users → Profile → Application Passwords
   - Name: "Auto Poster Script"
   - Copy password (format: `xxxx xxxx xxxx xxxx xxxx xxxx`)

2. **Set environment variables:**
   ```bash
   export WP_SITE_URL="https://myyachtsinsurance.com"
   export WP_USERNAME="SHORTALEX@HOTMAIL.CO.UK"
   export WP_APP_PASSWORD="paste-generated-password-here"
   ```

3. **Run automation:**
   ```bash
   python3 scripts/auto_post.py
   ```

4. **Review drafts in WordPress:**
   - Posts → All Posts (3 new drafts created)
   - Edit, add featured images, publish

---

**Most Common Fix:** Settings → Permalinks → Save Changes (literally 10 seconds)
