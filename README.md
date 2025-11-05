# Celeste7 Modern WordPress Theme - Deployment Guide

## 🎯 Project Overview

Complete WordPress rebuild for **My Yachts Insurance** (https://myyachtsinsurance.com) featuring:
- Modern, performance-optimized WordPress theme with Celeste7 branding
- Tailwind CSS integration for responsive design
- Core Web Vitals optimization (target: 90+ mobile/desktop)
- Automated blog post generation system
- SEO-optimized templates with JSON-LD structured data

---

## 📦 Project Structure

```
MYI_1/
├── theme/
│   └── celeste7-modern/          # WordPress theme (ready to upload)
│       ├── style.css              # Main stylesheet with Celeste7 branding
│       ├── functions.php          # Theme functions & performance optimizations
│       ├── header.php             # Responsive header with navigation
│       ├── footer.php             # Footer with newsletter form
│       ├── single.php             # Blog post template with JSON-LD
│       ├── index.php              # Main blog listing
│       ├── page.php               # Page template
│       ├── comments.php           # Comments template
│       └── js/
│           └── main.js            # Navigation & interactions
├── scripts/
│   ├── auto_post.py               # Automated post creation script
│   ├── audit_wp.py                # WordPress audit script
│   └── test_api.py                # API connectivity test
├── topics.json                    # Sample blog topics (3 posts ready)
├── reports/
│   ├── sitemap.xml                # Sitemap template
│   └── robots.txt                 # SEO robots file
└── README.md                      # This file
```

---

## 🚀 Deployment Instructions

### Step 1: Upload Theme to WordPress

1. **Create theme ZIP file:**
   ```bash
   cd theme/
   zip -r celeste7-modern.zip celeste7-modern/
   ```

2. **Upload to WordPress:**
   - Log in to WordPress Admin: https://myyachtsinsurance.com/wp-admin
   - Navigate to **Appearance > Themes**
   - Click **Add New > Upload Theme**
   - Select `celeste7-modern.zip`
   - Click **Install Now**
   - Click **Activate**

3. **Alternative - FTP Upload:**
   ```bash
   # Upload via FTP/SFTP to:
   /wp-content/themes/celeste7-modern/
   ```

### Step 2: Configure WordPress REST API

**⚠️ IMPORTANT:** The WordPress REST API is currently blocked (403 error). To enable:

1. **Check Security Plugin Settings:**
   - Go to WordPress Admin > Plugins
   - If using Wordfence, iThemes Security, or similar:
     - Navigate to plugin settings
     - Find "REST API" or "API Access" section
     - Whitelist your IP or enable REST API access

2. **Check .htaccess Rules:**
   - Connect via FTP/cPanel
   - Check `/public_html/.htaccess` for REST API blocking rules
   - Remove or comment out any rules blocking `/wp-json/`

3. **Generate Application Password:**
   - Go to **Users > Profile**
   - Scroll to **Application Passwords**
   - Enter name: "Auto Poster Script"
   - Click **Add New Application Password**
   - Copy the generated password (format: `xxxx xxxx xxxx xxxx xxxx xxxx`)

### Step 3: Configure Environment Variables

```bash
# Set these in your terminal or CI/CD environment
export WP_SITE_URL="https://myyachtsinsurance.com"
export WP_USERNAME="SHORTALEX@HOTMAIL.CO.UK"
export WP_APP_PASSWORD="your-generated-app-password"
```

### Step 4: Run Automated Post Creation

```bash
# Install dependencies
pip3 install requests

# Test API connection first
python3 scripts/test_api.py

# Run post creation (creates 3 sample posts as drafts)
python3 scripts/auto_post.py

# Check the log file for results
cat posts_log_*.json
```

### Step 5: Upload SEO Files

1. **Upload robots.txt:**
   ```bash
   # Upload reports/robots.txt to:
   /public_html/robots.txt
   ```

2. **Install SEO Plugin (Recommended):**
   - Install **Yoast SEO** or **Rank Math** plugin
   - These automatically generate sitemaps
   - Configure plugin to use `/sitemap_index.xml`

3. **Submit to Search Engines:**
   - Google Search Console: https://search.google.com/search-console
   - Bing Webmaster Tools: https://www.bing.com/webmasters

### Step 6: Configure Theme Settings

1. **Set Up Menus:**
   - Go to **Appearance > Menus**
   - Create "Primary Menu" with main pages
   - Create "Footer Menu" with legal pages
   - Assign to locations

2. **Configure Widget Areas:**
   - Go to **Appearance > Widgets**
   - Add widgets to Footer 1, 2, 3 areas

3. **Set Featured Images:**
   - For each post/page, set a featured image
   - Recommended size: 1200x675px (16:9 ratio)

4. **Install Recommended Plugins:**
   - **Yoast SEO** - SEO optimization
   - **WP Super Cache** - Performance caching
   - **Smush** - Image optimization
   - **Contact Form 7** - Contact forms

---

## 🎨 Brand Guidelines Applied

### Color Palette
- **Ghost White:** `#F8F8F0` (backgrounds)
- **Signal Blue:** `#badde9` (accents)
- **Electric Blue:** `#00a4ff` (primary actions)
- **Deep Black:** `#181818` (text)
- **Success Green:** `#10B981` (confirmations)

### Typography
- **Primary:** Poppins (400, 500, 600, 700)
- **Monospace:** JetBrains Mono (technical data)
- **Display:** Eloquia Text (headers)

### Design Principles
- ✅ Simplicity first
- ✅ Technical precision
- ✅ Premium quality
- ✅ Performance-optimized
- ✅ Mobile-first responsive

---

## ⚡ Performance Optimizations Included

1. **JavaScript:**
   - Deferred loading for non-critical scripts
   - Minified and optimized
   - No jQuery dependency

2. **CSS:**
   - Critical CSS inlined
   - Tailwind CSS loaded via CDN (switch to compiled version in production)
   - Minimal custom CSS

3. **Images:**
   - Lazy loading enabled by default
   - WebP format support
   - Responsive images with srcset

4. **Caching:**
   - Browser caching headers
   - Static asset caching
   - Install WP Super Cache plugin

5. **SEO:**
   - JSON-LD structured data for Articles
   - Organization schema
   - Meta descriptions
   - Open Graph tags

---

## 📝 Content Creation Workflow

### Using the Auto-Post Script

1. **Edit topics.json:**
   ```json
   {
     "topics": [
       {
         "title": "Your Post Title",
         "slug": "url-friendly-slug",
         "status": "draft",  // or "publish"
         "excerpt": "Brief summary...",
         "keywords": ["keyword1", "keyword2"],
         "sections": [
           {
             "heading": "Section Title",
             "content": "Section content..."
           }
         ],
         "faqs": [
           {
             "question": "FAQ question?",
             "answer": "FAQ answer..."
           }
         ]
       }
     ]
   }
   ```

2. **Run the script:**
   ```bash
   python3 scripts/auto_post.py
   ```

3. **Review drafts in WordPress:**
   - Posts are created as drafts by default
   - Review, edit, and publish manually
   - Add featured images if not automated

---

## 🔧 Troubleshooting

### REST API 403 Errors

**Problem:** All API endpoints return 403 "Access denied"

**Solutions:**
1. Check security plugins (Wordfence, iThemes Security)
2. Review .htaccess for blocking rules
3. Verify WordPress REST API is enabled: Settings > Permalinks (save changes)
4. Check server firewall rules
5. Contact hosting provider if issues persist

### Theme Not Displaying Correctly

**Problem:** Theme looks broken or unstyled

**Solutions:**
1. Clear browser cache and WordPress cache
2. Check if Tailwind CSS is loading (view page source)
3. Verify functions.php has no syntax errors
4. Check browser console for JavaScript errors
5. Ensure permalink structure is set to "Post name"

### Newsletter Form Not Working

**Problem:** Newsletter submissions not being saved

**Solutions:**
1. Check that form action URL is correct
2. Verify admin-post.php is accessible
3. Integrate with email service (Mailchimp, ConvertKit, etc.)
4. Install Contact Form 7 plugin for better form handling

---

## 🔒 Security Best Practices

1. **Keep WordPress Updated:**
   - Core, themes, and plugins should always be up-to-date

2. **Use Strong Passwords:**
   - Application passwords for REST API
   - Unique passwords for each service

3. **Limit REST API Access:**
   - Use application passwords (not main password)
   - Revoke unused application passwords

4. **Install Security Plugin:**
   - Wordfence or iThemes Security
   - Enable 2FA for admin users
   - Regular security scans

5. **Backup Regularly:**
   - Use UpdraftPlus or similar
   - Store backups off-site
   - Test restoration process

---

## 📊 Performance Benchmarks

### Target Metrics
- **Lighthouse Performance:** 90+ (mobile/desktop)
- **First Contentful Paint:** < 1.5s
- **Largest Contentful Paint:** < 2.5s
- **Time to Interactive:** < 3.5s
- **Cumulative Layout Shift:** < 0.1

### Testing Tools
- Google PageSpeed Insights: https://pagespeed.web.dev/
- GTmetrix: https://gtmetrix.com/
- WebPageTest: https://www.webpagetest.org/

---

## 📞 Support & Contact

For technical questions or issues:

- **Repository:** This codebase is version controlled
- **Theme Support:** Review WordPress documentation
- **Celeste7 Brand:** See Brand Guidelines.rtf for full specifications

---

## 📄 License

This theme is built for My Yachts Insurance. All rights reserved.

**Celeste7 Brand Guidelines:** © 2024 Celeste7. All brand assets and guidelines are proprietary.

---

## ✅ Deployment Checklist

- [ ] Theme uploaded and activated
- [ ] REST API enabled and tested
- [ ] Application password generated
- [ ] Auto-post script tested
- [ ] 3 sample posts created and reviewed
- [ ] Menus configured (Primary + Footer)
- [ ] Widget areas populated
- [ ] robots.txt uploaded
- [ ] SEO plugin installed and configured
- [ ] Sitemap submitted to search engines
- [ ] Performance tested (Lighthouse 90+)
- [ ] Mobile responsiveness verified
- [ ] Contact forms tested
- [ ] Newsletter signup tested
- [ ] SSL certificate active (HTTPS)
- [ ] Backup system configured
- [ ] Security plugin installed

---

## 🎉 Next Steps

1. **Content Creation:**
   - Create more topics in `topics.json`
   - Run auto_post.py regularly
   - Review and publish drafts

2. **Marketing:**
   - Set up Google Analytics
   - Configure Google Search Console
   - Create social media integration

3. **Advanced Features:**
   - Integrate live chat
   - Add quote calculator
   - Set up email automation

---

**Questions?** Review the brand guidelines in this repository for detailed design specifications and content strategy.
