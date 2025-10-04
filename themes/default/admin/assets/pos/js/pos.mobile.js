/*
 * pos.mobile.js v2.6
 * POS Mobile helper: positems (localStorage) + build hidden inputs for form submit
 * - mobileAddItem(productObj, qty, variantValue, note, noteName)
 *   variantValue format: "variant_id|variant_price|variant_name"
 *
 * Requires minimal DOM: product cards contain:
 *  - .btn-addcart[data-price="BASE_PRICE"]
 *  - .size-radio (value "variant_id|variant_price|variant_name")
 *  - #noteModal with #noteNameInput, #noteTextInput, .note-check checkboxes
 *  - #posTable container (hidden inputs for submit)
 *  - #customerSelect (optional select for customer id)
 */

var positems = JSON.parse(localStorage.getItem('positems')) || {};
var lastItemId = localStorage.getItem('lastItemId') ? parseInt(localStorage.getItem('lastItemId')) : 0;

function savePosItems() {
  localStorage.setItem('positems', JSON.stringify(positems));
  localStorage.setItem('lastItemId', lastItemId);
}

/* helper: format VND */
function fmtVND(n) {
  n = parseFloat(n || 0) || 0;
  return n.toLocaleString('vi-VN') + 'đ';
}

/* add item (public API) */
function mobileAddItem(productObj, qty, variantValue, note, noteName) {
  lastItemId++;
  var rowKey = lastItemId.toString();

  var option_id = '';
  var option_price = '';
  var option_name = '';
  if (variantValue) {
    var parts = variantValue.split('|');
    option_id = parts[0] || '';
    option_price = parts[1] || '';
    option_name = parts[2] || '';
  }

  var unit_price = option_price ? parseFloat(option_price) : parseFloat(productObj.price || 0);
  var real_unit_price = parseFloat(productObj.price || unit_price);
  var product_discount = 0;
  var is_promo = 0;
  var promo_original_price = '';
  var promo_original_price_for_suspend = '';

  var net_price = unit_price - (product_discount || 0);

  positems[rowKey] = {
    id: rowKey,
    row: {
      product_id: productObj.id,
      product_type: productObj.type || 'standard',
      product_code: productObj.code || '',
      product_image: productObj.image || 'no_image.png',
      product_name: productObj.name || '',
      product_name_en: productObj.name_en || '',
      product_option: option_id || '',
      product_option_name: option_name || '',
      product_option_price: option_price || '',
      product_comment: note || '',
      product_comment_name: noteName || '',
      product_discount: product_discount || 0,
      is_promo: is_promo || 0,
      promo_original_price: promo_original_price,
      promo_original_price_for_suspend: promo_original_price_for_suspend,
      net_price: net_price,
      unit_price: unit_price,
      real_unit_price: real_unit_price,
      no_points: 1,
      quantity: qty,
      product_unit: productObj.unit || 'undefined',
      product_base_quantity: qty
    }
  };

  savePosItems();
}

/* remove item */
function mobileRemoveItem(rowKey) {
  rowKey = String(rowKey);
  if (positems[rowKey]) {
    delete positems[rowKey];
    savePosItems();
  }
  renderCart();
  updateCartCount();
}

/* reset cart */
function mobileResetCart() {
  positems = {};
  lastItemId = 0;
  savePosItems();
  renderCart();
  updateCartCount();
}

/* build hidden inputs for submit */
function mobileLoadItems() {
  var hf = document.getElementById('posTable');
  if (!hf) return;
  hf.innerHTML = '';

  var totalQty = 0;
  Object.values(positems).forEach(function(it) {
    var r = it.row;
    totalQty += parseFloat(r.quantity || 0);

    hf.innerHTML += '<input type="hidden" name="product_id[]" value="' + escapeHtml(r.product_id) + '">';
    hf.innerHTML += '<input type="hidden" name="product_type[]" value="' + escapeHtml(r.product_type || 'standard') + '">';
    hf.innerHTML += '<input type="hidden" name="product_code[]" value="' + escapeHtml(r.product_code || '') + '">';
    hf.innerHTML += '<input type="hidden" name="product_image[]" value="' + escapeHtml(r.product_image || '') + '">';
    hf.innerHTML += '<input type="hidden" name="product_name[]" value="' + escapeHtml(r.product_name || '') + '">';
    hf.innerHTML += '<input type="hidden" name="product_name_en[]" value="' + escapeHtml(r.product_name_en || '') + '">';
    hf.innerHTML += '<input type="hidden" name="product_option[]" value="' + escapeHtml(r.product_option || '') + '">';
    hf.innerHTML += '<input type="hidden" name="product_comment[]" value="' + escapeHtml(r.product_comment || '') + '">';
    hf.innerHTML += '<input type="hidden" name="product_comment_name[]" value="' + escapeHtml(r.product_comment_name || '') + '">';
    hf.innerHTML += '<input type="hidden" name="product_discount[]" value="' + escapeHtml(r.product_discount || 0) + '">';
    hf.innerHTML += '<input type="hidden" name="is_promo[]" value="' + escapeHtml(r.is_promo || 0) + '">';
    hf.innerHTML += '<input type="hidden" name="promo_original_price[]" value="' + escapeHtml(r.promo_original_price || '') + '">';
    hf.innerHTML += '<input type="hidden" name="promo_original_price_for_suspend[]" value="' + escapeHtml(r.promo_original_price_for_suspend || '') + '">';
    hf.innerHTML += '<input type="hidden" name="net_price[]" value="' + escapeHtml(r.net_price || 0) + '">';
    hf.innerHTML += '<input type="hidden" name="unit_price[]" value="' + escapeHtml(r.unit_price || 0) + '">';
    hf.innerHTML += '<input type="hidden" name="real_unit_price[]" value="' + escapeHtml(r.real_unit_price || 0) + '">';
    hf.innerHTML += '<input type="hidden" name="no_points[]" value="' + escapeHtml(r.no_points || 1) + '">';
    hf.innerHTML += '<input type="hidden" name="quantity[]" value="' + escapeHtml(r.quantity || 1) + '">';
    hf.innerHTML += '<input type="hidden" name="product_unit[]" value="' + escapeHtml(r.product_unit || 'undefined') + '">';
    hf.innerHTML += '<input type="hidden" name="product_base_quantity[]" value="' + escapeHtml(r.product_base_quantity || r.quantity || 1) + '">';
  });

  hf.innerHTML += '<input type="hidden" name="total_items" value="' + totalQty + '">';
  // defaults for mobile
  hf.innerHTML += '<input type="hidden" name="warehouse" value="3">';
  hf.innerHTML += '<input type="hidden" name="biller" value="7283">';

  // customer id (if select exists)
  var custSel = document.getElementById('customerSelect');
  if (custSel) {
    var custVal = (typeof $ !== 'undefined' && $(custSel).val) ? $(custSel).val() : custSel.value;
    if (custVal) hf.innerHTML += '<input type="hidden" name="customer" value="' + escapeHtml(custVal) + '">';
  }
  // customer_name + phone + pos_note should be added by caller (placeOrder) as hidden fields
}

/* format escape helper */
function escapeHtml(str) {
  if (str === null || str === undefined) return '';
  return String(str).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&#39;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

/* render cart UI */
function renderCart() {
  var container = document.getElementById('cartItems');
  if (!container) return;
  var keys = Object.keys(positems);
  if (keys.length === 0) {
    container.innerHTML = '<p class="text-muted">Chưa có món nào</p>';
    updateCartCount();
    return;
  }
  var html = '', total = 0;
  keys.forEach(function(k){
    var it = positems[k].row;
    var qty = parseFloat(it.quantity || 0);
    var unit = parseFloat(it.unit_price || 0);
    var subtotal = qty * unit;
    total += subtotal;
    var optName = it.product_option_name ? ' (size ' + escapeHtml(it.product_option_name) + ')' : '';
    html += '<div class="border-bottom py-2 d-flex justify-content-between align-items-start">';
    html += '<div><strong>' + escapeHtml(it.product_name) + optName + '</strong> x' + qty + ' - ' + fmtVND(subtotal) + '<br><small>' + (it.product_comment?escapeHtml(it.product_comment):'') + (it.product_comment_name?(' | '+escapeHtml(it.product_comment_name)):'') + '</small></div>';
    html += '<div><button class="btn btn-sm btn-outline-danger" onclick="mobileRemoveItem(\''+k+'\')">X</button></div>';
    html += '</div>';
  });
  html += '<div class="mt-2 fw-bold">Tổng: ' + fmtVND(total) + '</div>';
  container.innerHTML = html;
}

/* update cart count badge */
function updateCartCount() {
  var badge = document.getElementById('cartCount');
  if (!badge) return;
  var totalQty = 0;
  Object.values(positems).forEach(function(it){ totalQty += parseFloat(it.row.quantity || 0); });
  badge.textContent = totalQty;
}

/* --- UI wiring (delegation) --- */
document.addEventListener('DOMContentLoaded', function(){

  // plus / minus (delegated)
  document.body.addEventListener('click', function(e){
    if (e.target.closest && e.target.closest('.btn-plus')) {
      var input = e.target.closest('.qty-box').querySelector('.qty-input');
      input.value = (parseInt(input.value || 0) + 1);
      return;
    }
    if (e.target.closest && e.target.closest('.btn-minus')) {
      var input = e.target.closest('.qty-box').querySelector('.qty-input');
      input.value = Math.max(0, (parseInt(input.value || 0) - 1));
      return;
    }
    // add to cart
    if (e.target.closest && e.target.closest('.btn-addcart')) {
      e.preventDefault();
      var btn = e.target.closest('.btn-addcart');
      var card = btn.closest('.card-body') || btn.closest('.card');
      var qtyInput = card.querySelector('.qty-input');
      var qty = parseInt(qtyInput.value || 0);
      if (qty <= 0) { alert('Vui lòng chọn số lượng > 0'); return; }

      var pid = btn.getAttribute('data-id');
      var code = btn.getAttribute('data-code') || '';
      var name = btn.getAttribute('data-name') || '';
      var name_en = btn.getAttribute('data-name-en') || btn.getAttribute('data-name_en') || '';
      var basePrice = parseFloat(btn.getAttribute('data-price') || 0);
      var image = btn.getAttribute('data-image') || 'no_image.png';
      var unit = btn.getAttribute('data-unit') || '';

      var selectedVariant = card.querySelector('.size-radio:checked');
      var variantValue = '';
      if (selectedVariant) {
        variantValue = selectedVariant.value || '';
      }

      // note for product
      var noteDisplay = document.getElementById('note-display-' + pid);
      var noteText = '';
      var noteName = '';
      if (noteDisplay && noteDisplay.dataset) {
        noteText = noteDisplay.dataset.note || noteDisplay.textContent || '';
        // if you stored name separately in dataset:
        noteName = noteDisplay.dataset.name || '';
      }
      // also check localStorage currentNotes fallback:
      try {
        var currentNotes = JSON.parse(localStorage.getItem('mobile_current_notes') || '{}');
        if (currentNotes[pid]) {
          noteText = currentNotes[pid].note || noteText;
          noteName = currentNotes[pid].name || noteName;
        }
      } catch (err) {}

      var productObj = {
        id: pid,
        code: code,
        name: name,
        name_en: name_en,
        image: image,
        unit: unit,
        price: basePrice,
        type: 'standard'
      };

      mobileAddItem(productObj, qty, variantValue, noteText, noteName);

      // reset UI for that product
      qtyInput.value = 0;
      if (noteDisplay) { noteDisplay.textContent = ''; noteDisplay.dataset = {}; }
      try {
        var curr = JSON.parse(localStorage.getItem('mobile_current_notes') || '{}');
        if (curr[pid]) { curr[pid] = {name:'', note:''}; localStorage.setItem('mobile_current_notes', JSON.stringify(curr)); }
      } catch(e){}

      renderCart();
      updateCartCount();
      return;
    }
  }); // end click delegation

  // size change -> update price display
  document.body.addEventListener('change', function(e){
    if (e.target && e.target.matches('.size-radio')) {
      var input = e.target;
      var card = input.closest('.card-body') || input.closest('.card');
      var addBtn = card.querySelector('.btn-addcart');
      var basePrice = parseFloat(addBtn ? (addBtn.getAttribute('data-price') || 0) : 0);
      var priceEl = card.querySelector('.text-muted');
      var parts = (input.value || '').split('|');
      var vprice = parseFloat(parts[1] || 0);
      if (!isNaN(vprice) && vprice > 0) {
        priceEl.textContent = fmtVND(vprice);
      } else {
        priceEl.textContent = fmtVND(basePrice);
      }
    }
  });

  // modal open (note)
  var noteModalEl = document.getElementById('noteModal');
  if (noteModalEl) {
    noteModalEl.addEventListener('show.bs.modal', function(e){
      var btn = e.relatedTarget;
      if (!btn) return;
      var pid = btn.getAttribute('data-id');
      document.getElementById('currentProductId').value = pid;
      var currentNotes = {};
      try { currentNotes = JSON.parse(localStorage.getItem('mobile_current_notes') || '{}'); } catch(e) {}
      var nameIn = document.getElementById('noteNameInput');
      var textIn = document.getElementById('noteTextInput');
      // reset
      document.querySelectorAll('.note-check').forEach(function(c){ c.checked = false; });
      if (currentNotes[pid]) {
        if (nameIn) nameIn.value = currentNotes[pid].name || '';
        if (textIn) textIn.value = currentNotes[pid].note || '';
        var parts = (currentNotes[pid].note || '').split(',').map(s => s.trim()).filter(Boolean);
        document.querySelectorAll('.note-check').forEach(function(c){
          if (parts.indexOf(c.value) !== -1) c.checked = true;
        });
      } else {
        if (nameIn) nameIn.value = '';
        if (textIn) textIn.value = '';
      }
      // focus name input after modal shown
      setTimeout(function(){ if (nameIn) nameIn.focus(); }, 200);
    });
  }

  // checkbox delegation for notes (update text box)
  document.body.addEventListener('change', function(e){
    if (e.target && e.target.matches('.note-check')) {
      var textIn = document.getElementById('noteTextInput');
      if (!textIn) return;
      var arr = textIn.value ? textIn.value.split(',').map(s => s.trim()).filter(Boolean) : [];
      if (e.target.checked) {
        if (arr.indexOf(e.target.value) === -1) arr.push(e.target.value);
      } else {
        arr = arr.filter(i => i !== e.target.value);
      }
      textIn.value = arr.join(', ');
      textIn.focus();
      textIn.setSelectionRange(textIn.value.length, textIn.value.length);
    }
  });

  // pressing Enter inside note textbox -> save
  var noteTextInput = document.getElementById('noteTextInput');
  if (noteTextInput) {
    noteTextInput.addEventListener('keydown', function(evt){
      if (evt.key === 'Enter') {
        evt.preventDefault();
        var saveBtn = document.getElementById('saveNoteBtn');
        if (saveBtn) saveBtn.click();
      }
    });
  }

  // save note button
  var saveNoteBtn = document.getElementById('saveNoteBtn');
  if (saveNoteBtn) {
    saveNoteBtn.addEventListener('click', function(){
      var pid = document.getElementById('currentProductId').value;
      var name = (document.getElementById('noteNameInput') || {}).value || '';
      var note = (document.getElementById('noteTextInput') || {}).value || '';
      var currentNotes = {};
      try { currentNotes = JSON.parse(localStorage.getItem('mobile_current_notes') || '{}'); } catch(e){}
      currentNotes[pid] = { name: name.trim(), note: note.trim() };
      localStorage.setItem('mobile_current_notes', JSON.stringify(currentNotes));
      // update display on card
      var disp = document.getElementById('note-display-' + pid);
      if (disp) {
        var displayText = '';
        if (name) displayText += 'Người: ' + name;
        if (note) displayText += (name ? ' | ' : '') + 'Ghi chú: ' + note;
        disp.textContent = displayText;
        // also store small dataset for later
        disp.dataset.note = note;
        disp.dataset.name = name;
      }
    });
  }

  // when cart offcanvas opens, focus customer select or input
  var cartCanvas = document.getElementById('cartCanvas');
  if (cartCanvas) {
    cartCanvas.addEventListener('show.bs.offcanvas', function(){
      setTimeout(function(){
        // try select2 open first
        var custSel = document.getElementById('customerSelect');
        if (custSel && typeof $ !== 'undefined' && $(custSel).select2) {
          try { $(custSel).select2('open'); } catch(e) {
            // fallback to search field
            var searchField = document.querySelector('.select2-search__field');
            if (searchField) searchField.focus();
            else {
              var cn = document.getElementById('customer_name');
              if (cn) cn.focus();
            }
          }
          return;
        }
        // fallback: focus customer_name input if present
        var cn = document.getElementById('customer_name');
        if (cn) cn.focus();
      }, 250);
    });
  }

  // init UI render
  renderCart();
  updateCartCount();
}); // DOMContentLoaded end

// expose helper functions to global (if needed)
window.mobileAddItem = mobileAddItem;
window.mobileRemoveItem = mobileRemoveItem;
window.mobileLoadItems = mobileLoadItems;
window.mobileResetCart = mobileResetCart;
window.renderCart = renderCart;
window.updateCartCount = updateCartCount;
