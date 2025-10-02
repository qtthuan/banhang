/*
 * pos.mobile.js
 * POS Mobile helper: positems (localStorage) + build hidden inputs for form submit
 * - mobileAddItem(productObj, qty, variantValue, note, noteName)
 *   variantValue format: "variant_id|variant_price|variant_name"
 */

var positems = JSON.parse(localStorage.getItem('positems')) || {};
var lastItemId = localStorage.getItem('lastItemId') ? parseInt(localStorage.getItem('lastItemId')) : 0;

function savePosItems() {
  localStorage.setItem('positems', JSON.stringify(positems));
  localStorage.setItem('lastItemId', lastItemId);
}

// productObj: {id, code, name, name_en, image, unit, price}
function mobileAddItem(productObj, qty, variantValue, note, noteName) {
  lastItemId++;
  var rowKey = lastItemId.toString();

  // parse variantValue
  var option_id = '';
  var option_price = '';
  var option_name = '';
  if (variantValue) {
    var parts = variantValue.split('|');
    option_id = parts[0] || '';
    option_price = parts[1] || '';
    option_name = parts[2] || '';
  }

  // determine prices
  var unit_price = option_price ? parseFloat(option_price) : parseFloat(productObj.price || 0);
  var real_unit_price = parseFloat(productObj.price || unit_price); // original product price
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

// remove item by rowKey
function mobileRemoveItem(rowKey) {
  rowKey = rowKey.toString();
  if (positems[rowKey]) {
    delete positems[rowKey];
    savePosItems();
  }
}

// reset cart
function mobileResetCart() {
  positems = {};
  lastItemId = 0;
  savePosItems();
}

// Build hidden inputs for form post (names match POS controller expectation)
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
    // option must be variant_id
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
}

// small helper
function escapeHtml(str) {
  if (str === null || str === undefined) return '';
  return String(str).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&#39;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}
