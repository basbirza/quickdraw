// Quickdraw Pressing Co. - Shopping Cart
// Shared cart module using localStorage for persistence

(function() {
  'use strict';

  // Security: escape HTML to prevent XSS from localStorage data
  function escHtml(str) {
    var div = document.createElement('div');
    div.appendChild(document.createTextNode(String(str)));
    return div.innerHTML;
  }

  // Cart data
  function getCart() {
    try {
      return JSON.parse(localStorage.getItem('quickdraw_cart')) || [];
    } catch (e) {
      return [];
    }
  }

  function saveCart(cart) {
    localStorage.setItem('quickdraw_cart', JSON.stringify(cart));
    updateCartUI();
  }

  function addToCart(product) {
    if (!product || typeof product.name !== 'string' || typeof product.price !== 'string') return;

    var name = product.name.substring(0, 200);
    var desc = (product.desc || '').substring(0, 200);
    var price = product.price.substring(0, 20);
    var priceNum = parseFloat(price.replace(/[^0-9.]/g, ''));
    var size = (product.size || 'One Size').substring(0, 50);
    var image = (product.image || '').substring(0, 300);

    if (isNaN(priceNum) || priceNum <= 0 || priceNum > 99999) return;

    var cart = getCart();
    if (cart.length > 50) return;

    var existing = cart.find(function(item) {
      return item.name === name && item.size === size;
    });
    if (existing) {
      if (existing.qty >= 20) return;
      existing.qty += 1;
    } else {
      cart.push({
        name: name,
        desc: desc,
        price: price,
        priceNum: priceNum,
        size: size,
        image: image,
        qty: 1
      });
    }
    saveCart(cart);
    openCartDrawer();
  }

  function removeFromCart(index) {
    var cart = getCart();
    if (index < 0 || index >= cart.length) return;
    cart.splice(index, 1);
    saveCart(cart);
  }

  function updateQty(index, newQty) {
    var cart = getCart();
    if (index < 0 || index >= cart.length) return;
    if (newQty <= 0) {
      cart.splice(index, 1);
    } else if (newQty <= 20) {
      cart[index].qty = newQty;
    }
    saveCart(cart);
  }

  function getCartCount() {
    return getCart().reduce(function(sum, item) { return sum + item.qty; }, 0);
  }

  function getCartTotal() {
    return getCart().reduce(function(sum, item) { return sum + (item.priceNum * item.qty); }, 0);
  }

  // UI: Inject account links into header
  function injectAccountLinks() {
    var header = document.querySelector('header');
    if (!header || document.getElementById('account-links-injected')) return;

    var accountDiv = document.createElement('div');
    accountDiv.id = 'account-links-injected';
    accountDiv.style.cssText = 'position:fixed;top:16px;right:60px;z-index:1000;display:flex;gap:16px;font-size:11px;letter-spacing:0.1em;font-family:Georgia,serif;';

    var authToken = localStorage.getItem('auth_token');
    var userData = localStorage.getItem('user_data');

    if (authToken && userData) {
      try {
        var user = JSON.parse(userData);
        accountDiv.innerHTML = '<a href="/account.html" style="color:#666;text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color=\'#000\'" onmouseout="this.style.color=\'#666\'">' + (user.name ? user.name.toUpperCase() : 'MY ACCOUNT') + '</a>';
      } catch (e) {
        accountDiv.innerHTML = '<a href="/login.html" style="color:#666;text-decoration:none;">LOGIN</a>';
      }
    } else {
      accountDiv.innerHTML = '' +
        '<a href="/login.html" style="color:#666;text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color=\'#000\'" onmouseout="this.style.color=\'#666\'">LOGIN</a>' +
        '<a href="/register.html" style="color:#666;text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color=\'#000\'" onmouseout="this.style.color=\'#666\'">REGISTER</a>';
    }

    document.body.appendChild(accountDiv);
  }

  // UI: Inject cart icon into header
  function injectCartIcon() {
    var header = document.querySelector('header');
    if (!header) return;

    // Add cart button to top-right of header
    var cartBtn = document.createElement('button');
    cartBtn.id = 'cart-toggle';
    cartBtn.setAttribute('aria-label', 'Open cart');
    cartBtn.style.cssText = 'position:fixed;top:80px;right:16px;z-index:1000;background:none;border:none;cursor:pointer;padding:8px;';
    cartBtn.innerHTML = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>' +
      '<span id="cart-count" style="position:absolute;top:2px;right:2px;background:#c45a3a;color:white;font-size:10px;font-family:Georgia,serif;width:18px;height:18px;border-radius:50%;display:none;align-items:center;justify-content:center;line-height:1;">0</span>';
    cartBtn.addEventListener('click', function() { openCartDrawer(); });
    document.body.appendChild(cartBtn);
  }

  // UI: Inject cart drawer
  function injectCartDrawer() {
    var overlay = document.createElement('div');
    overlay.id = 'cart-overlay';
    overlay.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.4);z-index:9998;display:none;opacity:0;transition:opacity 0.3s ease;';
    overlay.addEventListener('click', function() { closeCartDrawer(); });

    var drawer = document.createElement('div');
    drawer.id = 'cart-drawer';
    drawer.style.cssText = 'position:fixed;top:0;right:0;bottom:0;width:100%;max-width:400px;background:white;z-index:9999;transform:translateX(100%);transition:transform 0.3s ease;display:flex;flex-direction:column;font-family:Georgia,\"Times New Roman\",serif;';
    drawer.innerHTML = '' +
      '<div style="display:flex;justify-content:space-between;align-items:center;padding:20px 24px;border-bottom:1px solid #e5e7eb;">' +
        '<div>' +
          '<span style="font-size:11px;letter-spacing:0.15em;color:#9ca3af;">YOUR</span>' +
          '<h2 style="font-size:18px;letter-spacing:0.1em;margin:0;">Shopping Bag</h2>' +
        '</div>' +
        '<button id="cart-close" style="background:none;border:none;cursor:pointer;font-size:24px;color:#666;padding:4px;" aria-label="Close cart">&times;</button>' +
      '</div>' +
      '<div id="cart-items" style="flex:1;overflow-y:auto;padding:16px 24px;"></div>' +
      '<div id="cart-footer" style="padding:20px 24px;border-top:1px solid #e5e7eb;">' +
        '<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">' +
          '<span style="font-size:12px;letter-spacing:0.15em;">SUBTOTAL</span>' +
          '<span id="cart-total" style="font-size:16px;">€0</span>' +
        '</div>' +
        '<p style="font-size:11px;color:#9ca3af;margin:0 0 12px 0;">Shipping calculated at checkout</p>' +
        '<button id="cart-checkout" style="width:100%;background:black;color:white;border:none;padding:14px;font-size:11px;letter-spacing:0.15em;cursor:pointer;font-family:Georgia,serif;">CHECKOUT</button>' +
        '<button id="cart-continue" style="width:100%;background:none;border:1px solid #d1d5db;color:#111;padding:14px;font-size:11px;letter-spacing:0.15em;cursor:pointer;font-family:Georgia,serif;margin-top:8px;">CONTINUE SHOPPING</button>' +
      '</div>';

    document.body.appendChild(overlay);
    document.body.appendChild(drawer);

    document.getElementById('cart-close').addEventListener('click', function() { closeCartDrawer(); });
    document.getElementById('cart-continue').addEventListener('click', function() { closeCartDrawer(); });
    document.getElementById('cart-checkout').addEventListener('click', function() {
      window.location.href = 'checkout.html';
    });
  }

  function openCartDrawer() {
    var overlay = document.getElementById('cart-overlay');
    var drawer = document.getElementById('cart-drawer');
    if (!overlay || !drawer) return;
    overlay.style.display = 'block';
    requestAnimationFrame(function() {
      overlay.style.opacity = '1';
      drawer.style.transform = 'translateX(0)';
    });
    document.body.style.overflow = 'hidden';
    renderCartItems();
  }

  function closeCartDrawer() {
    var overlay = document.getElementById('cart-overlay');
    var drawer = document.getElementById('cart-drawer');
    if (!overlay || !drawer) return;
    overlay.style.opacity = '0';
    drawer.style.transform = 'translateX(100%)';
    setTimeout(function() {
      overlay.style.display = 'none';
      document.body.style.overflow = '';
    }, 300);
  }

  function renderCartItems() {
    var container = document.getElementById('cart-items');
    var totalEl = document.getElementById('cart-total');
    var footerEl = document.getElementById('cart-footer');
    if (!container) return;

    var cart = getCart();

    if (cart.length === 0) {
      container.innerHTML = '<div style="text-align:center;padding:60px 20px;">' +
        '<p style="font-size:14px;color:#6b7280;margin-bottom:8px;">Your bag is empty</p>' +
        '<p style="font-size:12px;color:#9ca3af;">Add some items to get started</p>' +
        '</div>';
      if (footerEl) footerEl.style.display = 'none';
      return;
    }

    if (footerEl) footerEl.style.display = 'block';

    var html = '';
    cart.forEach(function(item, i) {
      html += '<div style="display:flex;gap:16px;padding:16px 0;' + (i > 0 ? 'border-top:1px solid #f3f4f6;' : '') + '">';
      // Image
      if (item.image) {
        html += '<div style="width:80px;height:100px;background:#f5f5f0;flex-shrink:0;overflow:hidden;">' +
          '<img src="' + escHtml(item.image) + '" alt="' + escHtml(item.name) + '" style="width:100%;height:100%;object-fit:contain;" />' +
          '</div>';
      } else {
        html += '<div style="width:80px;height:100px;background:#f5f5f0;flex-shrink:0;"></div>';
      }
      // Details
      html += '<div style="flex:1;min-width:0;">' +
        '<p style="font-size:13px;font-weight:500;margin:0 0 2px 0;">' + escHtml(item.name) + '</p>' +
        '<p style="font-size:11px;color:#6b7280;margin:0 0 4px 0;">' + escHtml(item.desc) + '</p>' +
        '<p style="font-size:11px;color:#9ca3af;margin:0 0 10px 0;">Size: ' + escHtml(item.size) + '</p>' +
        '<div style="display:flex;justify-content:space-between;align-items:center;">' +
          '<div style="display:flex;align-items:center;border:1px solid #e5e7eb;">' +
            '<button data-action="dec" data-index="' + i + '" style="background:none;border:none;width:30px;height:30px;cursor:pointer;font-size:14px;color:#666;">-</button>' +
            '<span style="font-size:12px;width:24px;text-align:center;">' + item.qty + '</span>' +
            '<button data-action="inc" data-index="' + i + '" style="background:none;border:none;width:30px;height:30px;cursor:pointer;font-size:14px;color:#666;">+</button>' +
          '</div>' +
          '<span style="font-size:13px;">\u20AC' + (item.priceNum * item.qty).toFixed(0) + '</span>' +
        '</div>' +
      '</div>' +
      '<button data-action="remove" data-index="' + i + '" style="background:none;border:none;cursor:pointer;color:#9ca3af;font-size:16px;padding:0;align-self:flex-start;" aria-label="Remove item">&times;</button>' +
      '</div>';
    });

    container.innerHTML = html;
    if (totalEl) totalEl.textContent = '\u20AC' + getCartTotal().toFixed(0);

    // Attach event listeners
    container.querySelectorAll('button[data-action]').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var idx = parseInt(btn.getAttribute('data-index'));
        var action = btn.getAttribute('data-action');
        var cart = getCart();
        if (action === 'inc') {
          updateQty(idx, cart[idx].qty + 1);
        } else if (action === 'dec') {
          updateQty(idx, cart[idx].qty - 1);
        } else if (action === 'remove') {
          removeFromCart(idx);
        }
        renderCartItems();
      });
    });
  }

  function updateCartUI() {
    var countEl = document.getElementById('cart-count');
    if (!countEl) return;
    var count = getCartCount();
    if (count > 0) {
      countEl.style.display = 'flex';
      countEl.textContent = count;
    } else {
      countEl.style.display = 'none';
    }
  }

  // Expose global API for add-to-cart buttons
  window.QuickdrawCart = {
    add: addToCart,
    open: openCartDrawer,
    close: closeCartDrawer,
    getCount: getCartCount
  };

  // Initialize on DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  function init() {
    injectAccountLinks();
    injectCartIcon();
    injectCartDrawer();
    updateCartUI();
  }

})();
