/*
 pos.mobile.js v2.8
 - Delegation for + / - / add
 - Size selection uses variant price (variant_price) if present, otherwise product base
 - Add to cart sets product_option = variant_id
 - After add: reset card (size->first, price->base, qty->0, clear note)
 - Render cart + remove item + mobileLoadItems (build hidden inputs)
*/

var positems = JSON.parse(localStorage.getItem('positems') || '{}');
var lastItemId = localStorage.getItem('lastItemId') ? parseInt(localStorage.getItem('lastItemId')) : 0;

function savePosItems(){
  localStorage.setItem('positems', JSON.stringify(positems));
  localStorage.setItem('lastItemId', lastItemId);
}

function fmtVND(n){
  var num = parseFloat(n||0)||0;
  return num.toLocaleString('vi-VN') + 'đ';
}
function escapeHtml(str){
  if (str === null || str === undefined) return '';
  return String(str).replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/'/g,'&#39;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

/* Add item */
function mobileAddItem(productObj, qty, variantValue, note, noteName){
  lastItemId++;
  var rowKey = String(lastItemId);

  var option_id = '';
  var option_price = '';
  var option_name = '';
  if (variantValue) {
    var parts = variantValue.split('|');
    option_id = parts[0] || '';
    option_price = parts[1] || '';
    option_name = parts[2] || '';
  }

  var base_price = parseFloat(productObj.price || 0) || 0;
  // use variant price directly if present, otherwise use base_price
  var unit_price = (option_price && !isNaN(parseFloat(option_price))) ? parseFloat(option_price) : base_price;
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
      product_option_price: option_price || '',
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
}

/* Remove item */
function mobileRemoveItem(rowKey){
  rowKey = String(rowKey);
  if (positems[rowKey]) {
    delete positems[rowKey];
    savePosItems();
  }
  renderCart();
  updateCartCount();
}

/* Reset cart */
function mobileResetCart(){
  positems = {};
  lastItemId = 0;
  savePosItems();
  renderCart();
  updateCartCount();
}

/* Build hidden inputs for form post */
function mobileLoadItems(){
  var hf = document.getElementById('posTable');
  if (!hf) return;
  // Keep existing children that are not our generated inputs? For simplicity, clear and rebuild.
  hf.innerHTML = '';

  var totalQty = 0;
  Object.keys(positems).forEach(function(k){
    var r = positems[k].row;
    totalQty += parseFloat(r.quantity || 0);

    hf.insertAdjacentHTML('beforeend', '<input type="hidden" name="product_id[]" value="'+escapeHtml(r.product_id)+'">');
    hf.insertAdjacentHTML('beforeend', '<input type="hidden" name="product_type[]" value="'+escapeHtml(r.product_type||'standard')+'">');
    hf.insertAdjacentHTML('beforeend', '<input type="hidden" name="product_code[]" value="'+escapeHtml(r.product_code||'')+'">');
    hf.insertAdjacentHTML('beforeend', '<input type="hidden" name="product_image[]" value="'+escapeHtml(r.product_image||'')+'">');
    hf.insertAdjacentHTML('beforeend', '<input type="hidden" name="product_name[]" value="'+escapeHtml(r.product_name||'')+'">');
    hf.insertAdjacentHTML('beforeend', '<input type="hidden" name="product_name_en[]" value="'+escapeHtml(r.product_name_en||'')+'">');
    hf.insertAdjacentHTML('beforeend', '<input type="hidden" name="product_option[]" value="'+escapeHtml(r.product_option||'')+'">');
    hf.insertAdjacentHTML('beforeend', '<input type="hidden" name="product_comment[]" value="'+escapeHtml(r.product_comment||'')+'">');
    hf.insertAdjacentHTML('beforeend', '<input type="hidden" name="product_comment_name[]" value="'+escapeHtml(r.product_comment_name||'')+'">');
    hf.insertAdjacentHTML('beforeend', '<input type="hidden" name="product_discount[]" value="'+escapeHtml(r.product_discount||0)+'">');
    hf.insertAdjacentHTML('beforeend', '<input type="hidden" name="is_promo[]" value="'+escapeHtml(r.is_promo||0)+'">');
    hf.insertAdjacentHTML('beforeend', '<input type="hidden" name="promo_original_price[]" value="'+escapeHtml(r.promo_original_price||'')+'">');
    hf.insertAdjacentHTML('beforeend', '<input type="hidden" name="promo_original_price_for_suspend[]" value="'+escapeHtml(r.promo_original_price_for_suspend||'')+'">');
    hf.insertAdjacentHTML('beforeend', '<input type="hidden" name="net_price[]" value="'+escapeHtml(r.net_price||0)+'">');
    hf.insertAdjacentHTML('beforeend', '<input type="hidden" name="unit_price[]" value="'+escapeHtml(r.unit_price||0)+'">');
    hf.insertAdjacentHTML('beforeend', '<input type="hidden" name="real_unit_price[]" value="'+escapeHtml(r.real_unit_price||0)+'">');
    hf.insertAdjacentHTML('beforeend', '<input type="hidden" name="no_points[]" value="'+escapeHtml(r.no_points||1)+'">');
    hf.insertAdjacentHTML('beforeend', '<input type="hidden" name="quantity[]" value="'+escapeHtml(r.quantity||1)+'">');
    hf.insertAdjacentHTML('beforeend', '<input type="hidden" name="product_unit[]" value="'+escapeHtml(r.product_unit||'undefined')+'">');
    hf.insertAdjacentHTML('beforeend', '<input type="hidden" name="product_base_quantity[]" value="'+escapeHtml(r.product_base_quantity||r.quantity||1)+'">');
  });

  hf.insertAdjacentHTML('beforeend', '<input type="hidden" name="total_items" value="'+escapeHtml(totalQty)+'">');

  // fixed defaults
  hf.insertAdjacentHTML('beforeend', '<input type="hidden" name="warehouse" value="3">');
  hf.insertAdjacentHTML('beforeend', '<input type="hidden" name="biller" value="7283">');
}

/* Render cart UI */
function renderCart(){
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
    var qty = parseFloat(it.quantity||0);
    var unit = parseFloat(it.unit_price||0);
    var subtotal = qty * unit;
    total += subtotal;
    var opt = it.product_option_name ? (' ('+escapeHtml(it.product_option_name)+')') : '';
    html += '<div class="border-bottom py-2 d-flex justify-content-between align-items-start">';
    html += '<div><strong>'+escapeHtml(it.product_name)+opt+'</strong> x'+qty+' - '+fmtVND(subtotal);
    if (it.product_comment || it.product_comment_name) {
      html += '<br><small>' + (it.product_comment?escapeHtml(it.product_comment):'') + (it.product_comment_name?(' | '+escapeHtml(it.product_comment_name)):'') + '</small>';
    }
    html += '</div>';
    html += '<div><button class="btn btn-sm btn-outline-danger btn-remove-item" data-row="'+escapeHtml(k)+'">X</button></div>';
    html += '</div>';
  });
  html += '<div class="mt-2 fw-bold">Tổng: '+fmtVND(total)+'</div>';
  container.innerHTML = html;

  // attach remove handlers
  Array.prototype.slice.call(container.querySelectorAll('.btn-remove-item')).forEach(function(b){
    b.addEventListener('click', function(){
      var rk = this.getAttribute('data-row');
      if (rk) mobileRemoveItem(rk);
    });
  });
}

/* Update cart count */
function updateCartCount(){
  var badge = document.getElementById('cartCount');
  if (!badge) return;
  var totalQty = 0;
  Object.keys(positems).forEach(function(k){ totalQty += parseFloat(positems[k].row.quantity || 0); });
  badge.textContent = totalQty;
}

/* UI wiring (delegation) */
document.addEventListener('DOMContentLoaded', function(){

  // delegated clicks for plus/minus/add
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
    // add cart
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

      // selected variant
      var selected = card.querySelector('.size-radio:checked');
      var variantValue = selected ? (selected.value||'') : '';

      // notes from localStorage (mobile_current_notes) or visible
      var noteDisplay = document.getElementById('note-display-'+pid);
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

      // add item
      mobileAddItem(productObj, qty, variantValue, note, noteName);

      // reset UI on this card:
      // qty -> 0
      if (qtyInput) qtyInput.value = 0;
      // reset size to first radio
      var firstRadio = card.querySelector('.size-radio');
      if (firstRadio) {
        // uncheck others and check first
        var radios = card.querySelectorAll('.size-radio');
        radios.forEach(function(r, idx){ r.checked = (idx===0); });
        // update displayed price to base
        var baseEl = card.querySelector('.base-price');
        if (baseEl) baseEl.textContent = fmtVND(parseFloat(baseEl.getAttribute('data-base')||0));
        // reset addBtn data-price to base original
        addBtn.setAttribute('data-price', parseFloat(addBtn.getAttribute('data-price')||0));
      }
      // clear note display and stored note
      if (noteDisplay) { noteDisplay.textContent = ''; noteDisplay.dataset.note=''; noteDisplay.dataset.name=''; }
      try {
        var cur2 = JSON.parse(localStorage.getItem('mobile_current_notes') || '{}');
        if (cur2[pid]) { cur2[pid] = {name:'', note:''}; localStorage.setItem('mobile_current_notes', JSON.stringify(cur2)); }
      } catch(e){}

      return;
    }
  });

  // delegated change for size radios -> update card price (use variant price if present, else base)
  document.body.addEventListener('change', function(e){
    if (!e.target) return;
    if (e.target.matches && e.target.matches('.size-radio')) {
      var input = e.target;
      var card = input.closest('.card-body') || input.closest('.card');
      if (!card) return;
      var baseEl = card.querySelector('.base-price');
      var base = baseEl ? (parseFloat(baseEl.getAttribute('data-base')||0)||0) : 0;
      // parse variant
      var parts = (input.value||'').split('|');
      var vprice = parseFloat(parts[1]||'') || 0;
      var newPrice = (vprice && !isNaN(vprice) && vprice>0) ? vprice : base;
      if (baseEl) baseEl.textContent = fmtVND(newPrice);
      // ensure add button data-price updated so add uses correct price
      var addBtn = card.querySelector('.btn-addcart');
      if (addBtn) addBtn.setAttribute('data-price', newPrice);
    }
  });

  // note modal open populate and focus
  var noteModalEl = document.getElementById('noteModal');
  if (noteModalEl) {
    noteModalEl.addEventListener('show.bs.modal', function(e){
      var btn = e.relatedTarget;
      if (!btn) return;
      var pid = btn.getAttribute('data-id');
      document.getElementById('currentProductId').value = pid;
      var cur = {};
      try { cur = JSON.parse(localStorage.getItem('mobile_current_notes') || '{}'); } catch(e){}
      var nameIn = document.getElementById('noteNameInput');
      var textIn = document.getElementById('noteTextInput');
      // reset checkboxes
      document.querySelectorAll('.note-check').forEach(function(c){ c.checked = false; });
      if (cur[pid]) {
        if (nameIn) nameIn.value = cur[pid].name || '';
        if (textIn) textIn.value = cur[pid].note || '';
        var parts = (cur[pid].note||'').split(',').map(function(s){return s.trim();}).filter(Boolean);
        document.querySelectorAll('.note-check').forEach(function(c){ if (parts.indexOf(c.value)!==-1) c.checked = true; });
      } else {
        if (nameIn) nameIn.value = '';
        if (textIn) textIn.value = '';
      }
      setTimeout(function(){ if (nameIn) nameIn.focus(); }, 200);
    });
  }

  // note checkbox sync to text
  document.body.addEventListener('change', function(e){
    if (e.target && e.target.matches && e.target.matches('.note-check')) {
      var txt = document.getElementById('noteTextInput');
      if (!txt) return;
      var arr = txt.value ? txt.value.split(',').map(function(s){return s.trim();}).filter(Boolean) : [];
      if (e.target.checked) { if (arr.indexOf(e.target.value) === -1) arr.push(e.target.value); }
      else arr = arr.filter(function(x){ return x !== e.target.value; });
      txt.value = arr.join(', ');
      txt.focus();
      txt.setSelectionRange(txt.value.length, txt.value.length);
    }
  });

  // Enter in noteText -> click save
  var nt = document.getElementById('noteTextInput');
  if (nt) {
    nt.addEventListener('keydown', function(ev){ if (ev.key === 'Enter'){ ev.preventDefault(); var sb = document.getElementById('saveNoteBtn'); if (sb) sb.click(); }});
  }

  // save note
  var saveBtn = document.getElementById('saveNoteBtn');
  if (saveBtn) {
    saveBtn.addEventListener('click', function(){
      var pid = document.getElementById('currentProductId').value;
      if (!pid) return;
      var n = (document.getElementById('noteNameInput')||{}).value || '';
      var c = (document.getElementById('noteTextInput')||{}).value || '';
      var cur = {};
      try { cur = JSON.parse(localStorage.getItem('mobile_current_notes') || '{}'); } catch(e){}
      cur[pid] = { name: n.trim(), note: c.trim() };
      localStorage.setItem('mobile_current_notes', JSON.stringify(cur));
      // update display
      var disp = document.getElementById('note-display-'+pid);
      if (disp) {
        var out = '';
        if (n) out += 'Người: ' + n;
        if (c) out += (n ? ' | ' : '') + 'Ghi chú: ' + c;
        disp.textContent = out;
        disp.dataset.note = c;
        disp.dataset.name = n;
      }
    });
  }

  // offcanvas show: try to open select2 or focus customer_name
  var cartCanvas = document.getElementById('cartCanvas');
  if (cartCanvas) {
    cartCanvas.addEventListener('show.bs.offcanvas', function(){
      setTimeout(function(){
        try {
          if ($('#customerSelect').data('select2')) {
            $('#customerSelect').select2('open');
            setTimeout(function(){
              var sf = document.querySelector('.select2-container--open .select2-search__field');
              if (sf) sf.focus();
            },100);
            return;
          }
        } catch(e){}
        var cn = document.getElementById('customer_name'); if (cn) cn.focus();
      }, 250);
    });
  }

  // delegated remove button handled in renderCart; ensure events attached initially
  renderCart();
  updateCartCount();
}); // DOMContentLoaded

// expose API
window.mobileAddItem = mobileAddItem;
window.mobileRemoveItem = mobileRemoveItem;
window.mobileLoadItems = mobileLoadItems;
window.mobileResetCart = mobileResetCart;
window.renderCart = renderCart;
window.updateCartCount = updateCartCount;
    