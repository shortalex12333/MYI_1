/**
 * Celeste7 Modern Theme JavaScript
 * Handles navigation, search modal, and interactive elements
 *
 * @package Celeste7_Modern
 * @version 1.0.0
 */

(function () {
  'use strict';

  /**
   * Mobile Menu Toggle
   */
  function initMobileMenu() {
    const toggleButton = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuIcon = document.getElementById('menu-icon');
    const closeIcon = document.getElementById('close-icon');

    if (toggleButton && mobileMenu) {
      toggleButton.addEventListener('click', function () {
        const isHidden = mobileMenu.classList.contains('hidden');

        if (isHidden) {
          mobileMenu.classList.remove('hidden');
          menuIcon?.classList.add('hidden');
          closeIcon?.classList.remove('hidden');
        } else {
          mobileMenu.classList.add('hidden');
          menuIcon?.classList.remove('hidden');
          closeIcon?.classList.add('hidden');
        }
      });

      // Close menu on escape key
      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !mobileMenu.classList.contains('hidden')) {
          mobileMenu.classList.add('hidden');
          menuIcon?.classList.remove('hidden');
          closeIcon?.classList.add('hidden');
        }
      });

      // Close menu when clicking menu links
      const menuLinks = mobileMenu.querySelectorAll('a');
      menuLinks.forEach(function (link) {
        link.addEventListener('click', function () {
          mobileMenu.classList.add('hidden');
          menuIcon?.classList.remove('hidden');
          closeIcon?.classList.add('hidden');
        });
      });
    }
  }

  /**
   * Search Modal Toggle
   */
  function initSearchModal() {
    const searchToggle = document.getElementById('search-toggle');
    const searchModal = document.getElementById('search-modal');
    const searchClose = document.getElementById('search-close');

    if (searchToggle && searchModal) {
      // Open modal
      searchToggle.addEventListener('click', function () {
        searchModal.classList.remove('hidden');
        // Focus on search input
        const searchInput = searchModal.querySelector('input[type="search"]');
        if (searchInput) {
          setTimeout(() => searchInput.focus(), 100);
        }
      });

      // Close modal
      if (searchClose) {
        searchClose.addEventListener('click', function () {
          searchModal.classList.add('hidden');
        });
      }

      // Close on escape
      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !searchModal.classList.contains('hidden')) {
          searchModal.classList.add('hidden');
        }
      });

      // Close on backdrop click
      searchModal.addEventListener('click', function (e) {
        if (e.target === searchModal) {
          searchModal.classList.add('hidden');
        }
      });
    }
  }

  /**
   * Back to Top Button
   */
  function initBackToTop() {
    const backToTopButton = document.getElementById('back-to-top');

    if (backToTopButton) {
      // Show/hide button based on scroll position
      window.addEventListener('scroll', function () {
        if (window.pageYOffset > 300) {
          backToTopButton.classList.remove('opacity-0', 'pointer-events-none');
          backToTopButton.classList.add('opacity-100');
        } else {
          backToTopButton.classList.add('opacity-0', 'pointer-events-none');
          backToTopButton.classList.remove('opacity-100');
        }
      });

      // Smooth scroll to top
      backToTopButton.addEventListener('click', function () {
        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        });
      });
    }
  }

  /**
   * Smooth Scroll for Anchor Links
   */
  function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
      anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (href === '#') return;

        const target = document.querySelector(href);
        if (target) {
          e.preventDefault();
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });
  }

  /**
   * Add Active Class to Current Menu Item
   */
  function initActiveMenuItems() {
    const currentPath = window.location.pathname;
    const menuLinks = document.querySelectorAll('nav a');

    menuLinks.forEach(function (link) {
      const linkPath = new URL(link.href).pathname;
      if (linkPath === currentPath) {
        link.classList.add('active');
        // Add custom styling for active links
        link.style.color = 'var(--color-electric-blue)';
      }
    });
  }

  /**
   * Lazy Load Images (Fallback for browsers without native support)
   */
  function initLazyLoad() {
    if ('loading' in HTMLImageElement.prototype) {
      // Native lazy loading is supported
      return;
    }

    // Fallback for older browsers
    const images = document.querySelectorAll('img[loading="lazy"]');

    if ('IntersectionObserver' in window) {
      const imageObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            const img = entry.target;
            if (img.dataset.src) {
              img.src = img.dataset.src;
            }
            imageObserver.unobserve(img);
          }
        });
      });

      images.forEach(function (img) {
        imageObserver.observe(img);
      });
    } else {
      // Fallback for very old browsers - load all images immediately
      images.forEach(function (img) {
        if (img.dataset.src) {
          img.src = img.dataset.src;
        }
      });
    }
  }

  /**
   * Newsletter Form Enhancement
   */
  function initNewsletterForm() {
    const forms = document.querySelectorAll('form[action*="admin-post.php"]');

    forms.forEach(function (form) {
      form.addEventListener('submit', function (e) {
        const emailInput = form.querySelector('input[type="email"]');

        if (emailInput && emailInput.value) {
          // Add loading state
          const submitButton = form.querySelector('button[type="submit"]');
          if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'Subscribing...';
          }
        }
      });
    });
  }

  /**
   * Sticky Header on Scroll
   */
  function initStickyHeader() {
    const header = document.querySelector('.site-header');
    if (!header) return;

    let lastScroll = 0;

    window.addEventListener('scroll', function () {
      const currentScroll = window.pageYOffset;

      // Add shadow when scrolled
      if (currentScroll > 10) {
        header.classList.add('shadow-md');
      } else {
        header.classList.remove('shadow-md');
      }

      lastScroll = currentScroll;
    });
  }

  /**
   * External Links - Open in New Tab
   */
  function initExternalLinks() {
    const links = document.querySelectorAll('a[href^="http"]');
    const hostname = window.location.hostname;

    links.forEach(function (link) {
      try {
        const linkHostname = new URL(link.href).hostname;
        if (linkHostname !== hostname) {
          link.setAttribute('target', '_blank');
          link.setAttribute('rel', 'noopener noreferrer');
        }
      } catch (e) {
        // Invalid URL, skip
      }
    });
  }

  /**
   * Initialize All Functions
   */
  function init() {
    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', function () {
        initAll();
      });
    } else {
      initAll();
    }
  }

  function initAll() {
    initMobileMenu();
    initSearchModal();
    initBackToTop();
    initSmoothScroll();
    initActiveMenuItems();
    initLazyLoad();
    initNewsletterForm();
    initStickyHeader();
    initExternalLinks();

    // Log theme initialization (remove in production)
    console.log('Celeste7 Modern Theme initialized ✓');
  }

  // Start initialization
  init();
})();
