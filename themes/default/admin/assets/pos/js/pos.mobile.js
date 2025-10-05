/*
 * pos.mobile.js  — version 2.6 (full)
 * POS Mobile helper: positems (localStorage) + build hidden inputs for form submit
 * - mobileAddItem(productObj, qty, variantValue, note, noteName)
 *   variantValue format: "variant_id|variant_extra_price|variant_name"
 *
 * Features:
 * - Delegated + / - buttons
 * - Size radio change updates shown price (new price = base + variant_extra)
 * - Add to cart uses chosen size price (variant extra added to base)
 * - Note modal: checkbox sync to text, Enter = save, Save shows note under product
 * - Cart render + update count, remove item
 * - mobileLoadItems builds hidden inputs for POST (includes customer, customer_name, customer_phone, pos_note, warehouse, biller)
 * - Works without jQuery; if select2 present it will try to open it on offcanvas show
 */

var positems = JSON.parse(localStorage.getItem('positems') || '{}') || {};
var lastItemId = localStorage.getItem('lastItemId') ? parseInt(localStorage.getItem('lastItemId')) : 0;

function savePosItems() {
  localStorage.setItem('positems', JSON.stringify(positems));
  localStorage.setItem('lastItemId', lastItemId);
}

function fmtVND(n) {
  var num = parseFloat(n || 0) || 0;
  return num.toLocaleString('vi-VN') + 'đ';
}

function escapeHtml(str) {
  if (str === null || str === undefined) return '';
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;');
}

/* PUBLIC: add item */
function mobileAddItem(productObj, qty, variantValue, note, noteName) {
  lastItemId++;
  var rowKey = String(lastItemId);

  var option_id = '';
  var option_extra = 0;
  var option_name = '';
  if (variantValue) {
    var parts = variantValue.split('|');
    option_id = parts[0] || '';
    option_extra = parseFloat(parts[1] || 0) || 0;
    option_name = parts[2] || '';
  }

  var base_price = parseFloat(productObj.price || 0) || 0;
  var unit_price = base_price + option_extra;
  var real_unit_price = base_price;

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
      product_option_price: option_extra || '',
      product_comment: note || '',
      product_comment_name: noteName || '',
      product_discount: 0,
      is_promo: 0,
      promo_original_price: '',
      promo_original_price_for_suspend: '',
      net_price: unit_price,
      unit_price: unit_price,
      real_unit_price: real_unit_price,
      no_points: 1,
      quantity: qty,
      product_unit: productObj.unit || 'undefined',
      product_base_quantity: qty
    }
  };

  savePosItems();
  renderCart();
  updateCartCount();
}

/* PUBLIC: remove item */
function mobileRemoveItem(rowKey) {
  rowKey = String(rowKey);
  if (positems[rowKey]) {
    delete positems[rowKey];
    savePosItems();
  }
  renderCart();
  updateCartCount();
}

/* PUBLIC: reset cart */
function mobileResetCart() {
  positems = {};
  lastItemId = 0;
  savePosItems();
  renderCart();
  updateCartCount();
}

/* Build hidden inputs for POST */
function mobileLoadItems() {
  var hf = document.getElementById('posTable');
  if (!hf) return;
  hf.innerHTML = '';

  var totalQty = 0;
  Object.keys(positems).forEach(function(k){
    var r = positems[k].row;
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

  hf.innerHTML += '<input type="hidden" name="total_items" value="' + escapeHtml(totalQty) + '">';

  // fixed fields (as requested)
  hf.innerHTML += '<input type="hidden" name="warehouse" value="3">';
  hf.innerHTML += '<input type="hidden" name="biller" value="7283">';

  // add customer id if present (select2 or plain select)
  var cust = document.getElementById('customerSelect');
  if (cust) {
    var custVal = '';
    try { custVal = (typeof $ !== 'undefined' && $(cust).val) ? $(cust).val() : cust.value; } catch(e) { custVal = cust.value; }
    if (custVal) hf.innerHTML += '<input type="hidden" name="customer" value="' + escapeHtml(custVal) + '">';
  }

  // include customer_name, phone, pos_note if present
  var cname = document.getElementById('customer_name');
  if (cname && cname.value) hf.innerHTML += '<input type="hidden" name="customer_name" value="' + escapeHtml(cname.value) + '">';
  var cphone = document.getElementById('customerPhone') || document.getElementById('customer_phone');
  if (cphone && cphone.value) hf.innerHTML += '<input type="hidden" name="customer_phone" value="' + escapeHtml(cphone.value) + '">';
  var pnote = document.getElementById('pos_note') || document.getElementById('orderNote');
  if (pnote && pnote.value) hf.innerHTML += '<input type="hidden" name="pos_note" value="' + escapeHtml(pnote.value) + '">';
}

/* Render cart UI */
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
    var opt = it.product_option_name ? (' (' + escapeHtml(it.product_option_name) + ')') : '';
    html += '<div class="border-bottom py-2 d-flex justify-content-between align-items-start">';
    html += '<div><strong>' + escapeHtml(it.product_name) + opt + '</strong> x' + qty + ' - ' + fmtVND(subtotal);
    if (it.product_comment || it.product_comment_name) {
      html += '<br><small>' + (it.product_comment ? escapeHtml(it.product_comment) : '') + (it.product_comment_name ? (' | ' + escapeHtml(it.product_comment_name)) : '') + '</small>';
    }
    html += '</div>';
    html += '<div><button class="btn btn-sm btn-outline-danger btn-remove-item" data-row="'+escapeHtml(k)+'">X</button></div>';
    html += '</div>';
  });
  html += '<div class="mt-2 fw-bold">Tổng: ' + fmtVND(total) + '</div>';
  container.innerHTML = html;

  // attach remove handlers (delegation also handles but ensure dynamic buttons)
  container.querySelectorAll('.btn-remove-item').forEach(function(b){
    b.addEventListener('click', function(){
      var rk = this.getAttribute('data-row');
      if (rk) mobileRemoveItem(rk);
    });
  });
}

/* Update cart badge count */
function updateCartCount() {
  var badge = document.getElementById('cartCount') || document.getElementById('cartCountTop') || document.getElementById('cartCountBadge');
  if (!badge) return;
  var totalQty = 0;
  Object.keys(positems).forEach(function(k){ totalQty += parseFloat(positems[k].row.quantity || 0); });
  badge.textContent = totalQty;
}

/* UI wiring: delegated event handlers */
document.addEventListener('DOMContentLoaded', function(){

  // Delegated click for + / - / addcart
  document.body.addEventListener('click', function(e){
    // plus
    var plus = e.target.closest && e.target.closest('.btn-plus');
    if (plus) {
      var input = plus.closest('.qty-box') ? plus.closest('.qty-box').querySelector('.qty-input') : null;
      if (input) input.value = (parseInt(input.value || 0) + 1);
      return;
    }
    // minus
    var minus = e.target.closest && e.target.closest('.btn-minus');
    if (minus) {
      var input = minus.closest('.qty-box') ? minus.closest('.qty-box').querySelector('.qty-input') : null;
      if (input) input.value = Math.max(0, (parseInt(input.value || 0) - 1));
      return;
    }
    // add to cart
    var addBtn = e.target.closest && e.target.closest('.btn-addcart');
    if (addBtn) {
      e.preventDefault();
      var card = addBtn.closest('.card-body') || addBtn.closest('.card');
      if (!card) return;
      var qtyInput = card.querySelector('.qty-input');
      var qty = parseInt(qtyInput && qtyInput.value ? qtyInput.value : 0) || 0;
      if (qty <= 0) { alert('Vui lòng chọn số lượng > 0'); return; }

      var pid = addBtn.getAttribute('data-id');
      var pcode = addBtn.getAttribute('data-code') || '';
      var pname = addBtn.getAttribute('data-name') || '';
      var pname_en = addBtn.getAttribute('data-name-en') || addBtn.getAttribute('data-name_en') || '';
      var basePrice = parseFloat(addBtn.getAttribute('data-price') || 0) || 0;
      var image = addBtn.getAttribute('data-image') || 'no_image.png';
      var unit = addBtn.getAttribute('data-unit') || '';

      // get selected size radio in this card
      var selected = card.querySelector('.size-radio:checked');
      var variantValue = selected ? (selected.value || '') : '';

      // get note stored in localStorage or displayed area
      var noteDisplay = document.getElementById('note-display-' + pid);
      var note = '';
      var noteName = '';
      if (noteDisplay) {
        note = noteDisplay.dataset.note || noteDisplay.textContent || '';
        noteName = noteDisplay.dataset.name || '';
      }
      try {
        var cur = JSON.parse(localStorage.getItem('mobile_current_notes') || '{}');
        if (cur[pid]) { note = cur[pid].note || note; noteName = cur[pid].name || noteName; }
      } catch(e){}

      var productObj = {
        id: pid,
        code: pcode,
        name: pname,
        name_en: pname_en,
        image: image,
        unit: unit,
        price: basePrice,
        type: 'standard'
      };

      mobileAddItem(productObj, qty, variantValue, note, noteName);

      // reset UI for that product: qty -> 0, clear note display and stored note
      if (qtyInput) qtyInput.value = 0;
      if (noteDisplay) { noteDisplay.textContent = ''; noteDisplay.dataset.note = ''; noteDisplay.dataset.name = ''; }
      try {
        var cur2 = JSON.parse(localStorage.getItem('mobile_current_notes') || '{}');
        if (cur2[pid]) { cur2[pid] = {name:'', note:''}; localStorage.setItem('mobile_current_notes', JSON.stringify(cur2)); }
      } catch(e){}
      return;
    }
  }); // end body click delegation

  // delegated change for size radios -> update price on card
  document.body.addEventListener('change', function(e){
    if (!e.target) return;
    if (e.target.matches && e.target.matches('.size-radio')) {
      var input = e.target;
      var card = input.closest('.card-body') || input.closest('.card');
      if (!card) return;
      // try to find base price element: .base-price[data-base] or .text-muted
      var baseEl = card.querySelector('.base-price');
      var base = baseEl ? (parseFloat(baseEl.getAttribute('data-base') || 0) || 0) : (parseFloat((card.querySelector('.text-muted') || {}).getAttribute && card.querySelector('.text-muted').textContent.replace(/[^\d]/g,'') || 0) || 0);
      // safe fallback: read data-price from add button (original)
      var addBtn = card.querySelector('.btn-addcart');
      var originalBase = addBtn ? (parseFloat(addBtn.getAttribute('data-price') || 0) || 0) : base;
      if (!base) base = originalBase;

      var parts = (input.value || '').split('|');
      var extra = parseFloat(parts[1] || 0) || 0;
      var newPrice = base + extra;
      // update display: either .base-price or .text-muted
      if (baseEl) {
        baseEl.setAttribute('data-base', base); // keep base stored
        baseEl.textContent = fmtVND(newPrice);
      } else {
        var txt = card.querySelector('.text-muted');
        if (txt) txt.textContent = fmtVND(newPrice);
      }
      // update add button data-price so add uses computed price
      if (addBtn) addBtn.setAttribute('data-price', newPrice);
    }
  });

  // Note modal open: populate fields from localStorage if exists, focus name input
  var noteModal = document.getElementById('noteModal');
  if (noteModal) {
    noteModal.addEventListener('shown.bs.modal', function(ev){
      var btn = ev.relatedTarget;
      if (!btn) return;
      var pid = btn.getAttribute('data-id');
      var cur = {};
      try { cur = JSON.parse(localStorage.getItem('mobile_current_notes') || '{}'); } catch(e){}
      var nameIn = document.getElementById('noteNameInput');
      var txtIn = document.getElementById('noteTextInput');
      // clear checkboxes
      var checks = document.querySelectorAll('.note-check');
      checks.forEach(function(c){ c.checked = false; });
      if (cur[pid]) {
        if (nameIn) nameIn.value = cur[pid].name || '';
        if (txtIn) txtIn.value = cur[pid].note || '';
        var parts = (cur[pid].note||'').split(',').map(function(s){return s.trim();}).filter(Boolean);
        checks.forEach(function(c){ if (parts.indexOf(c.value)!==-1) c.checked = true; });
      } else {
        if (nameIn) nameIn.value = '';
        if (txtIn) txtIn.value = '';
      }
      document.getElementById('currentProductId').value = pid;
      setTimeout(function() { if (nameIn) { nameIn.focus();nameIn.select();} }, 200);

    });
  }

  // checkbox note sync
  document.body.addEventListener('change', function(e){
    if (e.target && e.target.matches && e.target.matches('.note-check')) {
      var txt = document.getElementById('noteTextInput');
      if (!txt) return;
      var arr = txt.value ? txt.value.split(',').map(function(s){ return s.trim(); }).filter(Boolean) : [];
      var val = e.target.value;
      if (e.target.checked) {
        if (arr.indexOf(val) === -1) arr.push(val);
      } else {
        arr = arr.filter(function(x){ return x !== val; });
      }
      txt.value = arr.join(', ');
      txt.focus();
      txt.setSelectionRange(txt.value.length, txt.value.length);
    }
  });

  // enter in noteTextInput => click save
  var noteText = document.getElementById('noteTextInput');
  if (noteText) {
    noteText.addEventListener('keydown', function(ev){
      if (ev.key === 'Enter') {
        ev.preventDefault();
        var sb = document.getElementById('saveNoteBtn');
        if (sb) sb.click();
      }
    });
  }

  // save note click
  var saveBtn = document.getElementById('saveNoteBtn');
  if (saveBtn) {
    saveBtn.addEventListener('click', function(){
      var pid = document.getElementById('currentProductId').value;
      if (!pid) return;
      var nameVal = (document.getElementById('noteNameInput') || {}).value || '';
      var noteVal = (document.getElementById('noteTextInput') || {}).value || '';
      var cur = {};
      try { cur = JSON.parse(localStorage.getItem('mobile_current_notes') || '{}'); } catch(e){}
      cur[pid] = { name: nameVal.trim(), note: noteVal.trim() };
      localStorage.setItem('mobile_current_notes', JSON.stringify(cur));
      // update note display under product card
      var disp = document.getElementById('note-display-' + pid);
      if (disp) {
        var out = '';
        if (nameVal) out += 'Người: ' + nameVal;
        if (noteVal) out += (nameVal ? ' | ' : '') + 'Ghi chú: ' + noteVal;
        disp.textContent = out;
        disp.dataset.note = noteVal;
        disp.dataset.name = nameVal;
      }
      var modalEl = document.getElementById('noteModal');
      var modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();

        // reset fields
        (document.getElementById('noteNameInput')||{}).value = '';
        (document.getElementById('noteTextInput')||{}).value = '';
        document.querySelectorAll('.note-check').forEach(c=>c.checked=false);
    });
  }

  // bind form submit to build hidden inputs
  var posForm = document.getElementById('pos-sale-form') || document.querySelector('form#pos-sale-form');
  if (posForm) {
    posForm.addEventListener('submit', function(ev){
      // before submit, build hidden inputs
      mobileLoadItems();
      // let default submit proceed
    });
  }

  // initial render
  renderCart();
  updateCartCount();
}); // DOMContentLoaded

/* Expose API */
window.mobileAddItem = mobileAddItem;
window.mobileRemoveItem = mobileRemoveItem;
window.mobileLoadItems = mobileLoadItems;
window.mobileResetCart = mobileResetCart;
window.renderCart = renderCart;
window.updateCartCount = updateCartCount;
