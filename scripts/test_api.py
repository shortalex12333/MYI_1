#!/usr/bin/env python3
"""Test WordPress REST API connectivity"""

import requests
import json
import os
from requests.auth import HTTPBasicAuth

site_url = "https://myyachtsinsurance.com"

print("Testing WordPress REST API...")
print(f"Site: {site_url}\n")

# Test 1: Check if REST API is accessible
print("1. Testing basic REST API endpoint (no auth)...")
try:
    response = requests.get(f"{site_url}/wp-json", timeout=30)
    print(f"   Status: {response.status_code}")
    if response.status_code == 200:
        data = response.json()
        print(f"   ✓ Site Name: {data.get('name', 'Unknown')}")
        print(f"   ✓ API URL: {data.get('url', 'Unknown')}")
        print(f"   ✓ Namespaces: {', '.join(data.get('namespaces', [])[:5])}...")
    else:
        print(f"   Response: {response.text[:200]}")
except Exception as e:
    print(f"   ✗ Error: {str(e)}")

print()

# Test 2: Try authenticated request
print("2. Testing authenticated endpoint...")
username = os.getenv("WP_USERNAME", "SHORTALEX@HOTMAIL.CO.UK")
# Try both with and without spaces
passwords = [
    "m9tO PHxU B6WO W4cL Qjob s5m6",
    "m9tOPHxUB6WOW4cLQjobs5m6"
]

for i, password in enumerate(passwords, 1):
    print(f"\n   Attempt {i}: Password {'with' if ' ' in password else 'without'} spaces")
    try:
        auth = HTTPBasicAuth(username, password)
        response = requests.get(f"{site_url}/wp-json/wp/v2/users/me", auth=auth, timeout=30)
        print(f"   Status: {response.status_code}")

        if response.status_code == 200:
            user = response.json()
            print(f"   ✓ SUCCESS! Connected as: {user.get('name', 'Unknown')}")
            print(f"   ✓ Roles: {', '.join(user.get('roles', []))}")
            break
        else:
            print(f"   ✗ Error: {response.status_code}")
            print(f"   Response: {response.text[:300]}")
    except Exception as e:
        print(f"   ✗ Exception: {str(e)}")

print()

# Test 3: Check themes endpoint (may not require auth)
print("3. Testing themes endpoint (public)...")
try:
    response = requests.get(f"{site_url}/wp-json/wp/v2/themes", timeout=30)
    print(f"   Status: {response.status_code}")
    if response.status_code == 200:
        print(f"   ✓ Themes endpoint accessible")
    else:
        print(f"   Note: {response.text[:200]}")
except Exception as e:
    print(f"   Error: {str(e)}")

print()

# Test 4: Check posts endpoint (should be public)
print("4. Testing posts endpoint (public)...")
try:
    response = requests.get(f"{site_url}/wp-json/wp/v2/posts?per_page=1", timeout=30)
    print(f"   Status: {response.status_code}")
    if response.status_code == 200:
        posts = response.json()
        if posts:
            print(f"   ✓ Found {len(posts)} post(s)")
            print(f"   Latest: {posts[0].get('title', {}).get('rendered', 'Unknown')}")
    else:
        print(f"   Response: {response.text[:200]}")
except Exception as e:
    print(f"   Error: {str(e)}")
