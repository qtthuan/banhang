/*
 pos.mobile.js v2.9
 - Variant price = base + extra
 - Cart badge update after add
 - Notes save and show correctly
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
  var option_extra = 0;
  var option_name = '';
  if (variantValue) {
    var parts = variantValue.split('|');
    option_id = parts[0] || '';
    option_extra = parseFloat(parts[1]||0);
    option_name = parts[2] || '';
  }

  var base_price = parseFloat(productObj.price || 0) || 0;
  var unit_price = base_price + (option_extra || 0);
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
      product_option_price: option_extra || 0,
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
    hf.insertAdjacentHTML('beforeend', '<input type="hidden" name="unit_price[]" value="'+escapeHtml(r.unit_price||0)+'">');
    hf.insertAdjacentHTML('beforeend', '<input type="hidden" name="quantity[]" value="'+escapeHtml(r.quantity||1)+'">');
  });

  hf.insertAdjacentHTML('beforeend', '<input type="hidden" name="total_items" value="'+escapeHtml(totalQty)+'">');
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

  container.querySelectorAll('.btn-remove-item').forEach(function(b){
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

/* Notes */
document.addEventListener('DOMContentLoaded', function(){
  // Save note
  var saveBtn = document.getElementById('saveNoteBtn');
  if (saveBtn) {
    saveBtn.addEventListener('click', function(){
      var pid = document.getElementById('currentProductId').value;
      var n = document.getElementById('noteNameInput').value.trim();
      var c = document.getElementById('noteTextInput').value.trim();
      var cur = JSON.parse(localStorage.getItem('mobile_current_notes') || '{}');
      cur[pid] = { name: n, note: c };
      localStorage.setItem('mobile_current_notes', JSON.stringify(cur));

      var disp = document.getElementById('note-display-'+pid);
      if (disp) {
        var out = '';
        if (n) out += 'Người: '+n;
        if (c) out += (n? ' | ' : '')+'Ghi chú: '+c;
        disp.textContent = out;
        disp.dataset.note = c;
        disp.dataset.name = n;
      }
    });
  }

  // Checkbox sync
  document.querySelectorAll('.note-check').forEach(function(chk){
    chk.addEventListener('change', function(){
      var txt = document.getElementById('noteTextInput');
      var arr = txt.value ? txt.value.split(',').map(s=>s.trim()).filter(Boolean) : [];
      if (this.checked) { if (!arr.includes(this.value)) arr.push(this.value); }
      else arr = arr.filter(x=>x!==this.value);
      txt.value = arr.join(', ');
    });
  });
});

/* Init */
document.addEventListener('DOMContentLoaded', function(){
  renderCart();
  updateCartCount();
});

window.mobileAddItem = mobileAddItem;
window.mobileRemoveItem = mobileRemoveItem;
window.mobileLoadItems = mobileLoadItems;
window.mobileResetCart = mobileResetCart;
window.renderCart = renderCart;
window.updateCartCount = updateCartCount;
