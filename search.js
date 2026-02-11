// Quickdraw Pressing Co. - Search Module
// Site-wide product search with dropdown results

(function() {
  'use strict';

  const API_URL = 'http://localhost:8000/api';
  let searchDebounce;
  const DEBOUNCE_DELAY = 350;

  // Security: escape HTML to prevent XSS
  function escHtml(str) {
    var div = document.createElement('div');
    div.appendChild(document.createTextNode(String(str)));
    return div.innerHTML;
  }

  // Inject search bar into header
  function injectSearchBar() {
    var header = document.querySelector('header');

    if (!header || document.getElementById('search-bar-injected')) return;

    var nav = header.querySelector('nav');

    var searchContainer = document.createElement('div');
    searchContainer.id = 'search-bar-injected';
    searchContainer.style.cssText = 'max-width:500px;margin:16px auto 0;padding:0 16px;position:relative;';

    searchContainer.innerHTML = '' +
      '<div style="position:relative;">' +
        '<input ' +
          'type="text" ' +
          'id="search-input" ' +
          'placeholder="Search denim, jackets, accessories..." ' +
          'aria-label="Search products" ' +
          'autocomplete="off" ' +
          'style="width:100%;padding:10px 40px 10px 12px;border:1px solid #e5e7eb;font-size:13px;font-family:Georgia,serif;letter-spacing:0.05em;transition:border-color 0.2s;border-radius:2px;"' +
        '/>' +
        '<button id="search-icon-btn" style="position:absolute;right:8px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;padding:4px;color:#666;" aria-label="Search">' +
          '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="pointer-events:none;">' +
            '<circle cx="11" cy="11" r="8"/>' +
            '<path d="M21 21l-4.35-4.35"/>' +
          '</svg>' +
        '</button>' +
      '</div>' +
      '<div id="search-dropdown" style="position:absolute;top:100%;left:16px;right:16px;max-height:400px;overflow-y:auto;background:white;border:1px solid #e5e7eb;box-shadow:0 4px 12px rgba(0,0,0,0.15);border-radius:2px;margin-top:4px;display:none;z-index:1000;"></div>';

    // Insert before nav if it exists, otherwise append to header
    if (nav) {
      header.insertBefore(searchContainer, nav);
    } else {
      header.appendChild(searchContainer);
    }

    attachSearchListeners();
  }

  // Attach event listeners
  function attachSearchListeners() {
    var searchInput = document.getElementById('search-input');
    var dropdown = document.getElementById('search-dropdown');

    if (!searchInput || !dropdown) return;

    // Input event with debouncing
    searchInput.addEventListener('input', function(e) {
      var query = e.target.value.trim();

      clearTimeout(searchDebounce);

      if (query.length < 2) {
        closeDropdown();
        return;
      }

      showLoadingState();
      dropdown.style.display = 'block';

      searchDebounce = setTimeout(function() {
        performSearch(query);
      }, DEBOUNCE_DELAY);
    });

    // Enter key triggers immediate search
    searchInput.addEventListener('keydown', function(e) {
      if (e.key === 'Enter') {
        clearTimeout(searchDebounce);
        var query = e.target.value.trim();
        if (query.length >= 2) {
          performSearch(query);
        }
      } else if (e.key === 'Escape') {
        closeDropdown();
        searchInput.blur();
      }
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
      var searchBar = document.getElementById('search-bar-injected');
      if (searchBar && !searchBar.contains(e.target)) {
        closeDropdown();
      }
    });

    // Prevent dropdown close when clicking inside it
    dropdown.addEventListener('click', function(e) {
      e.stopPropagation();
    });
  }

  // Perform search API call
  async function performSearch(query) {
    try {
      var response = await fetch(`${API_URL}/products/search?q=${encodeURIComponent(query)}&limit=10`);

      if (!response.ok) {
        throw new Error('Search failed');
      }

      var data = await response.json();

      if (data.success && data.data && data.data.length > 0) {
        renderResults(data);
      } else {
        showEmptyState(query);
      }
    } catch (error) {
      console.error('Search error:', error);
      showErrorState();
    }
  }

  // Render search results
  function renderResults(data) {
    var dropdown = document.getElementById('search-dropdown');
    if (!dropdown) return;

    var html = '<div style="padding:12px 16px;border-bottom:1px solid #f3f4f6;">' +
      '<p style="font-size:11px;color:#9ca3af;margin:0;">Found ' + data.results + ' ' + (data.results === 1 ? 'product' : 'products') + ' for "' + escHtml(data.query) + '"</p>' +
      '</div>';

    data.data.forEach(function(product) {
      var mainImage = product.images && product.images.length > 0
        ? product.images.find(function(img) { return img.type === 'main'; }) ||
          product.images.find(function(img) { return img.type === 'gallery'; }) ||
          product.images[0]
        : null;
      var imageUrl = mainImage ? mainImage.url : '';

      html += '<a href="/products/product.html?slug=' + product.slug + '" style="display:flex;gap:12px;padding:12px 16px;text-decoration:none;color:inherit;transition:background-color 0.2s;" onmouseover="this.style.backgroundColor=\'#f9fafb\'" onmouseout="this.style.backgroundColor=\'transparent\'">' +
        '<div style="width:60px;height:80px;background:#f5f5f0;flex-shrink:0;overflow:hidden;">' +
          (imageUrl ? '<img src="' + imageUrl + '" alt="' + escHtml(product.name) + '" style="width:100%;height:100%;object-fit:contain;" />' : '') +
        '</div>' +
        '<div style="flex:1;min-width:0;">' +
          '<p style="font-size:13px;font-weight:500;margin:0 0 4px 0;color:#111;">' + escHtml(product.name) + '</p>' +
          '<p style="font-size:11px;color:#6b7280;margin:0 0 4px 0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' + escHtml(product.desc || '') + '</p>' +
          '<div style="display:flex;align-items:center;gap:8px;">' +
            '<p style="font-size:13px;color:#111;margin:0;">' + escHtml(product.price) + '</p>' +
            (product.tag ? '<span style="font-size:9px;background:#111;color:white;padding:2px 6px;letter-spacing:0.05em;">' + escHtml(product.tag) + '</span>' : '') +
          '</div>' +
        '</div>' +
      '</a>';
    });

    dropdown.innerHTML = html;
  }

  // Show loading state
  function showLoadingState() {
    var dropdown = document.getElementById('search-dropdown');
    if (!dropdown) return;

    dropdown.innerHTML = '<div style="text-align:center;padding:24px 16px;">' +
      '<div style="width:24px;height:24px;border:3px solid #e5e7eb;border-top-color:#111;border-radius:50%;margin:0 auto;animation:spin 1s linear infinite;"></div>' +
      '<p style="font-size:11px;color:#9ca3af;margin-top:8px;">Searching...</p>' +
      '</div>';

    dropdown.style.display = 'block';
  }

  // Show empty state
  function showEmptyState(query) {
    var dropdown = document.getElementById('search-dropdown');
    if (!dropdown) return;

    dropdown.innerHTML = '<div style="text-align:center;padding:32px 16px;">' +
      '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" style="margin:0 auto 12px;">' +
        '<circle cx="11" cy="11" r="8"/>' +
        '<path d="M21 21l-4.35-4.35"/>' +
      '</svg>' +
      '<p style="font-size:13px;color:#6b7280;margin:0 0 6px;">No products found</p>' +
      '<p style="font-size:11px;color:#9ca3af;margin:0;">Try searching for "denim", "jacket", or "selvedge"</p>' +
      '</div>';
  }

  // Show error state
  function showErrorState() {
    var dropdown = document.getElementById('search-dropdown');
    if (!dropdown) return;

    dropdown.innerHTML = '<div style="text-align:center;padding:32px 16px;">' +
      '<p style="font-size:13px;color:#ef4444;margin:0 0 6px;">Search failed</p>' +
      '<p style="font-size:11px;color:#9ca3af;margin:0;">Please try again</p>' +
      '</div>';
  }

  // Close dropdown
  function closeDropdown() {
    var dropdown = document.getElementById('search-dropdown');
    if (dropdown) {
      dropdown.style.display = 'none';
    }
  }

  // Initialize search on page load
  function init() {
    // Add CSS animation for spinner
    var style = document.createElement('style');
    style.textContent = '@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }';
    document.head.appendChild(style);

    injectSearchBar();
  }

  // Auto-initialize
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  // Global API (if needed)
  window.QuickdrawSearch = {
    close: closeDropdown
  };

})();
