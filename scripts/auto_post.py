#!/usr/bin/env python3
"""
WordPress Auto-Post Script for My Yachts Insurance
Creates high-quality blog posts with SEO optimization, internal linking, and featured images

Usage:
    export WP_SITE_URL="https://myyachtsinsurance.com"
    export WP_USERNAME="your-email@example.com"
    export WP_APP_PASSWORD="your-app-password"
    python3 auto_post.py
"""

import os
import json
import requests
from requests.auth import HTTPBasicAuth
from datetime import datetime
import sys
import time


class WordPressAutoPoster:
    """Automated WordPress post creation with REST API"""

    def __init__(self):
        self.site_url = os.getenv("WP_SITE_URL")
        self.username = os.getenv("WP_USERNAME")
        self.app_password = os.getenv("WP_APP_PASSWORD")

        if not all([self.site_url, self.username, self.app_password]):
            print("❌ Error: Missing environment variables")
            print("Required: WP_SITE_URL, WP_USERNAME, WP_APP_PASSWORD")
            sys.exit(1)

        self.auth = HTTPBasicAuth(self.username, self.app_password)
        self.api_base = f"{self.site_url}/wp-json/wp/v2"
        self.log_file = f"posts_log_{datetime.now().strftime('%Y%m%d_%H%M%S')}.json"
        self.posts_created = []

    def test_connection(self):
        """Test WordPress REST API connection"""
        print("🔌 Testing WordPress connection...")
        try:
            response = requests.get(f"{self.api_base}/users/me", auth=self.auth, timeout=30)
            if response.status_code == 200:
                user = response.json()
                print(f"✅ Connected as: {user.get('name', 'Unknown')}")
                return True
            else:
                print(f"❌ Connection failed: {response.status_code}")
                print(f"   Response: {response.text[:200]}")
                return False
        except Exception as e:
            print(f"❌ Connection error: {str(e)}")
            return False

    def upload_featured_image(self, image_url, post_title):
        """Upload featured image from URL"""
        print(f"   📸 Uploading featured image...")
        try:
            # Download image
            img_response = requests.get(image_url, timeout=30)
            if img_response.status_code != 200:
                print(f"   ⚠️  Failed to download image from {image_url}")
                return None

            # Prepare file upload
            files = {
                'file': ('featured-image.jpg', img_response.content, 'image/jpeg')
            }
            data = {
                'title': f"Featured image for {post_title}",
                'alt_text': post_title,
                'caption': post_title
            }

            # Upload to WordPress
            upload_response = requests.post(
                f"{self.api_base}/media",
                auth=self.auth,
                files=files,
                data=data,
                timeout=60
            )

            if upload_response.status_code == 201:
                media = upload_response.json()
                print(f"   ✅ Image uploaded (ID: {media['id']})")
                return media['id']
            else:
                print(f"   ⚠️  Image upload failed: {upload_response.status_code}")
                return None

        except Exception as e:
            print(f"   ⚠️  Image upload error: {str(e)}")
            return None

    def generate_post_content(self, topic):
        """Generate structured HTML content for post"""
        title = topic['title']
        keywords = topic.get('keywords', [])
        sections = topic.get('sections', [])

        content = f"""
        <div class="entry-content">
            <p class="lead text-xl mb-6">{topic.get('intro', f'Comprehensive guide to {title.lower()}.')}</p>

            {"".join([f'''
            <h2 class="text-3xl font-semibold mt-8 mb-4">{section['heading']}</h2>
            <p class="mb-4">{section['content']}</p>
            ''' for section in sections])}

            <div class="bg-signal-blue p-6 rounded-lg my-8">
                <h3 class="text-2xl font-semibold mb-3">Need Expert Advice?</h3>
                <p class="mb-4">Our team of yacht insurance specialists is here to help you navigate the complexities of maritime insurance.</p>
                <a href="{self.site_url}/contact" class="btn btn-primary inline-block">Get a Free Quote →</a>
            </div>

            <h2 class="text-3xl font-semibold mt-8 mb-4">Frequently Asked Questions</h2>

            {"".join([f'''
            <div class="faq-item mb-6">
                <h3 class="text-xl font-semibold mb-2">{faq['question']}</h3>
                <p class="text-gray-700">{faq['answer']}</p>
            </div>
            ''' for faq in topic.get('faqs', [])])}

            <div class="mt-8 pt-8 border-t border-gray-200">
                <p class="text-sm text-gray-600">
                    <strong>Keywords:</strong> {', '.join(keywords[:5])}
                </p>
            </div>
        </div>
        """

        return content.strip()

    def create_post(self, topic):
        """Create a WordPress post from topic data"""
        print(f"\n📝 Creating post: {topic['title']}")

        # Generate content
        content = self.generate_post_content(topic)

        # Prepare post data
        post_data = {
            'title': topic['title'],
            'content': content,
            'slug': topic['slug'],
            'status': topic.get('status', 'draft'),
            'excerpt': topic.get('excerpt', ''),
            'meta': {
                'keywords': ', '.join(topic.get('keywords', [])),
                'description': topic.get('meta_description', '')
            }
        }

        # Add categories if specified
        if 'categories' in topic:
            post_data['categories'] = topic['categories']

        # Add tags if specified
        if 'tags' in topic:
            post_data['tags'] = topic['tags']

        try:
            # Create post
            response = requests.post(
                f"{self.api_base}/posts",
                auth=self.auth,
                json=post_data,
                timeout=30
            )

            if response.status_code == 201:
                post = response.json()
                post_id = post['id']
                post_url = post['link']

                print(f"✅ Post created successfully!")
                print(f"   ID: {post_id}")
                print(f"   URL: {post_url}")
                print(f"   Status: {post['status']}")

                # Upload featured image if provided
                if 'featured_image_url' in topic:
                    media_id = self.upload_featured_image(topic['featured_image_url'], topic['title'])
                    if media_id:
                        # Update post with featured image
                        update_response = requests.post(
                            f"{self.api_base}/posts/{post_id}",
                            auth=self.auth,
                            json={'featured_media': media_id},
                            timeout=30
                        )
                        if update_response.status_code == 200:
                            print(f"   ✅ Featured image set")

                # Log successful creation
                self.posts_created.append({
                    'id': post_id,
                    'title': topic['title'],
                    'url': post_url,
                    'slug': topic['slug'],
                    'status': post['status'],
                    'created_at': datetime.now().isoformat()
                })

                return True

            else:
                print(f"❌ Failed to create post: {response.status_code}")
                print(f"   Response: {response.text[:300]}")
                return False

        except Exception as e:
            print(f"❌ Error creating post: {str(e)}")
            return False

    def process_topics_file(self, filepath='topics.json'):
        """Process all topics from JSON file"""
        print(f"\n{'=' * 60}")
        print(f"WordPress Auto-Poster - My Yachts Insurance")
        print(f"{'=' * 60}\n")

        # Load topics
        try:
            with open(filepath, 'r') as f:
                data = json.load(f)
                topics = data.get('topics', [])
        except FileNotFoundError:
            print(f"❌ Error: {filepath} not found")
            sys.exit(1)
        except json.JSONDecodeError:
            print(f"❌ Error: Invalid JSON in {filepath}")
            sys.exit(1)

        print(f"📚 Loaded {len(topics)} topics from {filepath}\n")

        # Test connection
        if not self.test_connection():
            print("\n❌ Aborting: Could not connect to WordPress")
            sys.exit(1)

        # Process each topic
        print(f"\n{'=' * 60}")
        print("Starting post creation...")
        print(f"{'=' * 60}")

        success_count = 0
        for i, topic in enumerate(topics, 1):
            print(f"\n[{i}/{len(topics)}]", end=" ")
            if self.create_post(topic):
                success_count += 1
                time.sleep(2)  # Rate limiting

        # Save log
        self.save_log()

        # Summary
        print(f"\n{'=' * 60}")
        print(f"✅ Completed!")
        print(f"   Successfully created: {success_count}/{len(topics)} posts")
        print(f"   Log saved to: {self.log_file}")
        print(f"{'=' * 60}\n")

    def save_log(self):
        """Save creation log to file"""
        log_data = {
            'run_date': datetime.now().isoformat(),
            'site_url': self.site_url,
            'total_posts': len(self.posts_created),
            'posts': self.posts_created
        }

        with open(self.log_file, 'w') as f:
            json.dump(log_data, f, indent=2)


def main():
    """Main execution"""
    poster = WordPressAutoPoster()
    poster.process_topics_file('topics.json')


if __name__ == "__main__":
    main()
