#!/usr/bin/env python3
"""
WordPress REST API Audit Script for myyachtsinsurance.com
Audits themes, plugins, and generates performance report
"""

import os
import json
import requests
from requests.auth import HTTPBasicAuth
from datetime import datetime
import sys

def get_wp_auth():
    """Get WordPress authentication from environment variables"""
    site_url = os.getenv("WP_SITE_URL")
    username = os.getenv("WP_USERNAME")
    app_password = os.getenv("WP_APP_PASSWORD")

    if not all([site_url, username, app_password]):
        print("ERROR: Missing environment variables")
        print("Required: WP_SITE_URL, WP_USERNAME, WP_APP_PASSWORD")
        sys.exit(1)

    return site_url, HTTPBasicAuth(username, app_password)

def audit_themes(base_url, auth):
    """Audit WordPress themes"""
    try:
        response = requests.get(f"{base_url}/wp-json/wp/v2/themes", auth=auth, timeout=30)
        if response.status_code == 200:
            themes = response.json()

            # Try to find active theme
            active_theme_slug = None
            for theme in themes:
                # Check various indicators of active theme
                if theme.get('status') == 'active':
                    active_theme_slug = theme.get("stylesheet", "")
                    break

            return {
                "total_themes": len(themes),
                "themes": [
                    {
                        "name": theme.get("name", {}).get("rendered", "Unknown") if isinstance(theme.get("name"), dict) else theme.get("name", "Unknown"),
                        "slug": theme.get("stylesheet", ""),
                        "version": theme.get("version", "Unknown"),
                        "author": theme.get("author", {}).get("rendered", "Unknown") if isinstance(theme.get("author"), dict) else theme.get("author", "Unknown"),
                        "status": "active" if theme.get("stylesheet") == active_theme_slug else theme.get("status", "inactive")
                    }
                    for theme in themes
                ],
                "status": "success"
            }
        else:
            return {
                "status": "error",
                "code": response.status_code,
                "message": f"Failed to fetch themes: {response.text}"
            }
    except Exception as e:
        return {
            "status": "error",
            "message": str(e)
        }

def audit_plugins(base_url, auth):
    """Audit WordPress plugins"""
    try:
        response = requests.get(f"{base_url}/wp-json/wp/v2/plugins", auth=auth, timeout=30)
        if response.status_code == 200:
            plugins = response.json()
            return {
                "total_plugins": len(plugins),
                "active_plugins": len([p for p in plugins if p.get("status") == "active"]),
                "plugins": [
                    {
                        "name": plugin.get("name", "Unknown"),
                        "slug": plugin.get("plugin", ""),
                        "version": plugin.get("version", "Unknown"),
                        "status": plugin.get("status", "unknown"),
                        "author": plugin.get("author", "Unknown")
                    }
                    for plugin in plugins
                ],
                "status": "success"
            }
        else:
            return {
                "status": "error",
                "code": response.status_code,
                "message": f"Failed to fetch plugins: {response.text}"
            }
    except Exception as e:
        return {
            "status": "error",
            "message": str(e)
        }

def test_connection(base_url, auth):
    """Test WordPress REST API connection"""
    try:
        response = requests.get(f"{base_url}/wp-json/wp/v2/users/me", auth=auth, timeout=30)
        if response.status_code == 200:
            user = response.json()
            return {
                "status": "success",
                "user": user.get("name", "Unknown"),
                "roles": user.get("roles", [])
            }
        else:
            return {
                "status": "error",
                "code": response.status_code,
                "message": response.text
            }
    except Exception as e:
        return {
            "status": "error",
            "message": str(e)
        }

def get_site_info(base_url):
    """Get basic site information"""
    try:
        response = requests.get(f"{base_url}/wp-json", timeout=30)
        if response.status_code == 200:
            data = response.json()
            return {
                "name": data.get("name", "Unknown"),
                "description": data.get("description", ""),
                "url": data.get("url", ""),
                "wordpress_version": data.get("_links", {}).get("help", [{}])[0].get("href", "Unknown"),
                "status": "success"
            }
        else:
            return {
                "status": "error",
                "code": response.status_code
            }
    except Exception as e:
        return {
            "status": "error",
            "message": str(e)
        }

def main():
    print("=" * 60)
    print("WordPress REST API Audit - My Yachts Insurance")
    print("=" * 60)
    print()

    # Get credentials
    site_url, auth = get_wp_auth()
    print(f"Site URL: {site_url}")
    print()

    # Test connection
    print("Testing API connection...")
    connection = test_connection(site_url, auth)
    if connection["status"] == "success":
        print(f"✓ Connected as: {connection['user']}")
        print(f"✓ Roles: {', '.join(connection['roles'])}")
    else:
        print(f"✗ Connection failed: {connection.get('message', 'Unknown error')}")
        sys.exit(1)
    print()

    # Get site info
    print("Fetching site information...")
    site_info = get_site_info(site_url)
    if site_info["status"] == "success":
        print(f"✓ Site Name: {site_info['name']}")
        print(f"✓ Description: {site_info['description']}")
    print()

    # Audit themes
    print("Auditing themes...")
    themes = audit_themes(site_url, auth)
    if themes["status"] == "success":
        print(f"✓ Found {themes['total_themes']} themes")
        for theme in themes["themes"]:
            status_icon = "●" if theme["status"] == "active" else "○"
            print(f"  {status_icon} {theme['name']} v{theme['version']} ({theme['status']})")
    else:
        print(f"✗ Theme audit failed: {themes.get('message', 'Unknown error')}")
    print()

    # Audit plugins
    print("Auditing plugins...")
    plugins = audit_plugins(site_url, auth)
    if plugins["status"] == "success":
        print(f"✓ Found {plugins['total_plugins']} plugins ({plugins['active_plugins']} active)")
        for plugin in plugins["plugins"][:10]:  # Show first 10
            status_icon = "●" if plugin["status"] == "active" else "○"
            print(f"  {status_icon} {plugin['name']} v{plugin['version']} ({plugin['status']})")
        if plugins['total_plugins'] > 10:
            print(f"  ... and {plugins['total_plugins'] - 10} more")
    else:
        print(f"✗ Plugin audit failed: {plugins.get('message', 'Unknown error')}")
    print()

    # Generate report
    report = {
        "audit_date": datetime.now().isoformat(),
        "site_url": site_url,
        "site_info": site_info,
        "connection": connection,
        "themes": themes,
        "plugins": plugins,
        "recommendations": [
            "Review active plugins for performance impact",
            "Consider implementing lazy loading for images",
            "Add critical CSS inlining",
            "Implement CDN for static assets",
            "Enable browser caching",
            "Minify CSS and JavaScript"
        ]
    }

    # Save report
    report_path = "reports/audit.json"
    with open(report_path, "w") as f:
        json.dump(report, f, indent=2)

    print(f"✓ Audit report saved to: {report_path}")
    print()
    print("=" * 60)
    print("Audit Complete!")
    print("=" * 60)

if __name__ == "__main__":
    main()
