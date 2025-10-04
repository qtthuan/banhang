/*
 * pos.mobile.js v2.7
 * Quản lý giỏ hàng cho POS Mobile
 */

var positems = JSON.parse(localStorage.getItem('positems')) || {};
var lastItemId = localStorage.getItem('lastItemId') ? parseInt(localStorage.getItem('lastItemId')) : 0;

function savePosItems() {
  localStorage.setItem('positems', JSON.stringify(positems));
  localStorage.setItem('lastItemId', lastItemId);
}

// Thêm item vào giỏ
function mobileAddItem(productObj, qty, variantValue, note, noteName) {
  if (qty <= 0) return;

  lastItemId++;
  var rowKey = lastItemId.toString();

  // parse variant
  var option_id = '', option_extra = 0, option_name = '';
  if (variantValue) {
    var parts = variantValue.split('|');
    option_id = parts[0] || '';
    option_extra = parseFloat(parts[1] || 0);
    option_name = parts[2] || '';
  }

  var base_price = parseFloat(productObj.price || 0);
  var unit_price = base_price + option_extra;

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
      product_comment: note || '',
      product_comment_name: noteName || '',
      product_discount: 0,
      is_promo: 0,
      promo_original_price: '',
      promo_original_price_for_suspend: '',
      net_price: unit_price,
      unit_price: unit_price,
      real_unit_price: base_price,
      no_points: 1,
      quantity: qty,
      product_unit: productObj.unit || 'undefined',
      product_base_quantity: qty
    }
  };

  savePosItems();
}

// Xóa item
function mobileRemoveItem(rowKey) {
  rowKey = rowKey.toString();
  if (positems[rowKey]) {
    delete positems[rowKey];
    savePosItems();
  }
}

// Reset giỏ
function mobileResetCart() {
  positems = {};
  lastItemId = 0;
  savePosItems();
}

// Build hidden inputs để submit form
function mobileLoadItems() {
  var hf = document.getElementById('posTable');
  if (!hf) return;
  hf.innerHTML = '';

  var totalQty = 0;
  Object.values(positems).forEach(function(it) {
    var r = it.row;
    totalQty += parseFloat(r.quantity || 0);

    hf.innerHTML += hidden('product_id[]', r.product_id);
    hf.innerHTML += hidden('product_type[]', r.product_type || 'standard');
    hf.innerHTML += hidden('product_code[]', r.product_code || '');
    hf.innerHTML += hidden('product_image[]', r.product_image || '');
    hf.innerHTML += hidden('product_name[]', r.product_name || '');
    hf.innerHTML += hidden('product_name_en[]', r.product_name_en || '');
    hf.innerHTML += hidden('product_option[]', r.product_option || '');
    hf.innerHTML += hidden('product_comment[]', r.product_comment || '');
    hf.innerHTML += hidden('product_comment_name[]', r.product_comment_name || '');
    hf.innerHTML += hidden('product_discount[]', r.product_discount || 0);
    hf.innerHTML += hidden('is_promo[]', r.is_promo || 0);
    hf.innerHTML += hidden('promo_original_price[]', r.promo_original_price || '');
    hf.innerHTML += hidden('promo_original_price_for_suspend[]', r.promo_original_price_for_suspend || '');
    hf.innerHTML += hidden('net_price[]', r.net_price || 0);
    hf.innerHTML += hidden('unit_price[]', r.unit_price || 0);
    hf.innerHTML += hidden('real_unit_price[]', r.real_unit_price || 0);
    hf.innerHTML += hidden('no_points[]', r.no_points || 1);
    hf.innerHTML += hidden('quantity[]', r.quantity || 1);
    hf.innerHTML += hidden('product_unit[]', r.product_unit || 'undefined');
    hf.innerHTML += hidden('product_base_quantity[]', r.product_base_quantity || r.quantity || 1);
  });

  hf.innerHTML += hidden('total_items', totalQty);
}

// helper
function hidden(name, val) {
  return '<input type="hidden" name="'+escapeHtml(name)+'" value="'+escapeHtml(val)+'">';
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
