<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TIỆM NƯỚC MINI</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f8f9fa; }
    .navbar-brand { font-weight:bold; }
    .product-card img { max-height:100px; width:auto; max-width:100%; margin-bottom:8px; object-fit:contain; }
    .product-card h6 { font-size:0.95rem; font-weight:bold; min-height:40px; }
    .note-display { font-size:0.8rem; color:#555; min-height:18px; margin-bottom:6px; }

    .size-options .btn { font-size:0.95rem; padding:10px 14px; }
    .btn-plus, .btn-minus { font-size:1.35rem; padding:10px 14px; }
    .qty-box input { width:65px; text-align:center; height:100%; font-size:1.05rem; }
    .qty-box { display:flex; justify-content:center; align-items:center; gap:6px; }

    .btn-note { width:40%; font-size:1rem; padding:12px; }
    .btn-addcart { width:60%; font-size:1rem; padding:12px; }

    @media (max-width:576px) {
      .col-6 { flex: 0 0 100%; max-width:100%; }
    }
  </style>
</head>
<body>

<nav class="navbar navbar-dark bg-success sticky-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">🥤 TIỆM NƯỚC MINI</a>
    <form class="d-flex me-2">
      <input class="form-control" id="searchInput" type="search" placeholder="Tìm món..." aria-label="Search">
    </form>
    <button class="btn btn-outline-light position-relative" type="button" data-bs-toggle="offcanvas" data-bs-target="#cartCanvas">
      🛒
      <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cartCount">0</span>
    </button>
  </div>
</nav>

<!-- FORM POST: posTable will be filled by mobileLoadItems() -->
<form id="pos-sale-form" method="post" action="<?= admin_url('pos'); ?>">
  <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" 
         value="<?= $this->security->get_csrf_hash(); ?>">
  <div id="posTable"></div>
</form>


<div class="container py-3">
  <div class="row g-2" id="productList">
    <?php foreach ($products as $p):
      // assume $p has id, code, name, name_en, price, image, unit, variants (array of objects with id, price, name)
      $img = !empty($p->image) ? base_url('assets/uploads/thumbs/'.$p->image) : 'https://banicantho.com/assets/uploads/thumbs/no_image.png';
      $cleanName = preg_replace('/^[A-Z]_\s*/', '', strtoupper($p->name));
      $code = isset($p->code) ? $p->code : '';
      $name_en = isset($p->name_en) ? $p->name_en : '';
      $unit = isset($p->unit) ? $p->unit : '';
    ?>
    <div class="col-6 product-card" data-name="<?= htmlspecialchars($cleanName) ?>">
      <div class="card h-100">
        <div class="card-body text-center">
          <img src="<?= $img ?>" alt="<?= htmlspecialchars($p->name) ?>">
          <h6 class="card-title"><?= htmlspecialchars($cleanName) ?></h6>
          <p class="text-muted mb-1"><?= number_format($p->price,0,',','.') ?>đ</p>

          <?php if (!empty($p->variants)): ?>
            <div class="btn-group size-options mb-2" role="group">
              <?php foreach ($p->variants as $i => $v): ?>
                <input type="radio" class="btn-check size-radio"
                       name="size-<?= $p->id ?>"
                       id="size-<?= $p->id ?>-<?= $i ?>"
                       value="<?= $v->id ?>|<?= $v->price ?>|<?= htmlspecialchars($v->name) ?>"
                       <?= $i==0 ? 'checked' : '' ?>>
                <label class="btn btn-outline-primary" for="size-<?= $p->id ?>-<?= $i ?>"><?= htmlspecialchars($v->name) ?></label>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <div class="note-display" id="note-display-<?= $p->id ?>"></div>

          <div class="qty-box my-2">
            <button class="btn btn-outline-secondary btn-minus" type="button">-</button>
            <input type="number" class="form-control qty-input" value="0" min="0">
            <button class="btn btn-outline-secondary btn-plus" type="button">+</button>
          </div>

          <div class="d-flex gap-2">
            <button class="btn btn-info btn-note"
                    data-bs-toggle="modal"
                    data-bs-target="#noteModal"
                    data-id="<?= $p->id ?>">
              📝 Ghi chú
            </button>
            <button class="btn btn-success btn-addcart"
                    type="button"
                    data-id="<?= $p->id ?>"
                    data-code="<?= htmlspecialchars($code) ?>"
                    data-name="<?= htmlspecialchars($cleanName) ?>"
                    data-name-en="<?= htmlspecialchars($name_en) ?>"
                    data-price="<?= $p->price ?>"
                    data-image="<?= htmlspecialchars($p->image ?: 'no_image.png') ?>"
                    data-unit="<?= htmlspecialchars($unit) ?>">
              + Thêm Món
            </button>
          </div>

        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Modal Ghi chú -->
<div class="modal fade" id="noteModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Ghi chú món</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="currentProductId">
        <div class="mb-2">
          <label class="form-label">Tên (dán lên ly)</label>
          <input type="text" id="noteNameInput" class="form-control" placeholder="Tên (ví dụ: A, B...)">
        </div>
        <div class="mb-2">
          <label class="form-label">Ghi chú</label>
          <input type="text" id="noteTextInput" class="form-control" placeholder="Nhập ghi chú...">
        </div>
        <div class="d-flex flex-wrap gap-2">
          <div><input type="checkbox" class="form-check-input note-check" value="Ít ngọt"> <small>Ít ngọt</small></div>
          <div><input type="checkbox" class="form-check-input note-check" value="Không đá"> <small>Không đá</small></div>
          <div><input type="checkbox" class="form-check-input note-check" value="Nhiều cafe"> <small>Nhiều cafe</small></div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
        <button class="btn btn-success" id="saveNoteBtn">Lưu</button>
      </div>
    </div>
  </div>
</div>

<!-- Offcanvas giỏ hàng -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="cartCanvas">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">🛒 Giỏ hàng</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body d-flex flex-column">

    <div id="cartItems" class="mb-3"><p class="text-muted">Chưa có món nào</p></div>

    <!-- Hidden fixed fields -->
    <input type="hidden" name="warehouse" value="3">
    <input type="hidden" name="biller" value="7283">

    <!-- Customer select2 -->
    <label class="form-label">Khách hàng</label>
    <select id="poscustomer" name="customer" class="form-control"></select>

    <!-- Customer info -->
    <input type="text" class="form-control my-2" id="customer_name" name="customer_name" placeholder="Tên khách">
    <input type="tel" class="form-control mb-2" id="customerPhone" name="customer_phone" placeholder="Số điện thoại">
    <textarea class="form-control" id="sale_note" name="sale_note" rows="2" placeholder="Ghi chú đơn..."></textarea>

    <button class="btn btn-success mt-auto" id="placeOrderBtn">Đặt hàng</button>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= $assets ?>pos/js/pos.mobile.js"></script>
<script>
/* UI glue (keeps layout behavior you requested) */

// store note per productId
var currentNotes = JSON.parse(localStorage.getItem('mobile_current_notes') || '{}');

function saveCurrentNotes() {
  localStorage.setItem('mobile_current_notes', JSON.stringify(currentNotes));
}

function escapeHtml(s){ if(s===null||s===undefined) return ''; return String(s).replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/'/g,'&#39;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

/* qty handlers */
document.querySelectorAll('.btn-plus').forEach(btn=>{
  btn.addEventListener('click', function(){
    var input = this.closest('.qty-box').querySelector('.qty-input');
    input.value = (parseInt(input.value||0)+1);
  });
});
document.querySelectorAll('.btn-minus').forEach(btn=>{
  btn.addEventListener('click', function(){
    var input = this.closest('.qty-box').querySelector('.qty-input');
    input.value = Math.max(0, (parseInt(input.value||0)-1));
  });
});

/* modal open */
var noteModalEl = document.getElementById('noteModal');
noteModalEl.addEventListener('show.bs.modal', function(e){
  var btn = e.relatedTarget;
  var pid = btn.getAttribute('data-id');
  document.getElementById('currentProductId').value = pid;
  if(currentNotes[pid]){
    document.getElementById('noteNameInput').value = currentNotes[pid].name || '';
    document.getElementById('noteTextInput').value = currentNotes[pid].note || '';
    document.querySelectorAll('.note-check').forEach(c=> c.checked=false);
    var parts = (currentNotes[pid].note||'').split(',').map(s=>s.trim()).filter(Boolean);
    document.querySelectorAll('.note-check').forEach(c=> { if(parts.indexOf(c.value)!==-1) c.checked=true; });
  } else {
    document.getElementById('noteNameInput').value = '';
    document.getElementById('noteTextInput').value = '';
    document.querySelectorAll('.note-check').forEach(c=> c.checked=false);
  }
  setTimeout(()=>document.getElementById('noteNameInput').focus(),250);
});

/* checkbox sync */
document.querySelectorAll('.note-check').forEach(chk=>{
  chk.addEventListener('change', function(){
    var txt = document.getElementById('noteTextInput');
    var arr = txt.value ? txt.value.split(',').map(s=>s.trim()).filter(Boolean) : [];
    if(this.checked){
      if(arr.indexOf(this.value)===-1) arr.push(this.value);
    } else {
      arr = arr.filter(i=> i!==this.value);
    }
    txt.value = arr.join(', ');
    txt.focus();
    txt.setSelectionRange(txt.value.length, txt.value.length);
  });
});

/* save note */
document.getElementById('saveNoteBtn').addEventListener('click', function(){
  var pid = document.getElementById('currentProductId').value;
  var name = document.getElementById('noteNameInput').value.trim();
  var note = document.getElementById('noteTextInput').value.trim();
  currentNotes[pid] = {name: name, note: note};
  saveCurrentNotes();
  var disp = document.getElementById('note-display-'+pid);
  var displayText = '';
  if(name) displayText += 'Người: '+name;
  if(note) displayText += (name ? ' | ' : '') + 'Ghi chú: ' + note;
  disp.textContent = displayText;
});

/* add to cart (use mobileAddItem from pos.mobile.js) */
document.querySelectorAll('.btn-addcart').forEach(btn=>{
  btn.addEventListener('click', function(){
    var card = this.closest('.card-body');
    var qty = parseInt(card.querySelector('.qty-input').value || 0);
    if(qty <= 0){ alert('Vui lòng chọn số lượng > 0'); return; }

    var pid = this.dataset.id;
    var code = this.dataset.code || '';
    var name = this.dataset.name || '';
    var name_en = this.dataset['nameEn'] || this.getAttribute('data-name-en') || '';
    var price = parseFloat(this.dataset.price || 0);
    var image = this.dataset.image || 'no_image.png';
    var unit = this.getAttribute('data-unit') || '';

    // parse selected size variant (variant_id|price|name)
    var option_id = '';
    var option_price = '';
    var option_name = '';
    var sizeEl = card.querySelector('.size-radio:checked');
    if(sizeEl){
      var parts = sizeEl.value.split('|');
      option_id = parts[0] || '';
      option_price = parts[1] || '';
      option_name = parts[2] || '';
    }

    var finalPrice = option_price ? parseFloat(option_price) : price;
    var noteObj = currentNotes[pid] || {name:'', note:''};
    var note = noteObj.note || '';
    var noteName = noteObj.name || '';

    var productObj = {
      id: pid,
      code: code,
      name: name,
      name_en: name_en,
      image: image,
      unit: unit,
      price: price,
      type: 'standard'
    };

    // call mobileAddItem(productObj, qty, variantValue, note, noteName)
    var variantValue = option_id ? (option_id + '|' + (option_price||finalPrice) + '|' + (option_name||'')) : '';
    if (typeof mobileAddItem === 'function') {
      mobileAddItem(productObj, qty, variantValue, note, noteName);
    } else {
      console.error('mobileAddItem not found');
    }

    // reset UI for that product
    card.querySelector('.qty-input').value = 0;
    document.getElementById('note-display-'+pid).textContent = '';
    currentNotes[pid] = {name:'', note:''};
    saveCurrentNotes();

    renderCart();
    updateCartCount();
  });
});

/* renderCart */
function renderCart() {
  var container = document.getElementById('cartItems');
  var items = JSON.parse(localStorage.getItem('positems') || '{}');
  var keys = Object.keys(items);
  if(keys.length === 0){
    container.innerHTML = '<p class="text-muted">Chưa có món nào</p>';
    updateCartCount();
    return;
  }
  var html = '';
  var total = 0;
  keys.forEach(function(k){
    var it = items[k].row;
    var subtotal = (parseFloat(it.unit_price) || 0) * (parseFloat(it.quantity) || 0);
    total += subtotal;
    html += '<div class="border-bottom py-2 d-flex justify-content-between align-items-start">';
    html += '<div><strong>'+escapeHtml(it.product_name)+'</strong> '+(it.product_option_name?('('+escapeHtml(it.product_option_name)+')'):'')+' x'+it.quantity+' - '+subtotal+'đ';
    html += '<br><small>' + (it.product_comment?escapeHtml(it.product_comment):'') + (it.product_comment_name?(' | '+escapeHtml(it.product_comment_name)):'') + '</small></div>';
    html += '<div><button class="btn btn-sm btn-outline-danger" onclick="mobileRemoveItem(\''+k+'\'); renderCart(); updateCartCount();">X</button></div>';
    html += '</div>';
  });
  html += '<div class="mt-2 fw-bold">Tổng: '+ total +'đ</div>';
  container.innerHTML = html;
}

/* update cart count */
function updateCartCount() {
  var items = JSON.parse(localStorage.getItem('positems') || '{}');
  var totalQty = 0;
  Object.values(items).forEach(function(i){ totalQty += parseFloat(i.row.quantity || 0); });
  document.getElementById('cartCount').textContent = totalQty;
}

/* place order -> build hidden inputs + submit */
document.getElementById('placeOrderBtn').addEventListener('click', function(){
  var customer = document.getElementById('customerName').value.trim();
  var phone = document.getElementById('customerPhone').value.trim();
  if(!customer || !phone){ alert('Vui lòng nhập Tên khách và Số điện thoại!'); return; }

  // clear posTable then add customer fields
  var hf = document.getElementById('posTable');
  hf.innerHTML = '';
  hf.innerHTML += '<input type="hidden" name="customer_name" value="'+escapeHtml(customer)+'">';
  hf.innerHTML += '<input type="hidden" name="customer_phone" value="'+escapeHtml(phone)+'">';
  hf.innerHTML += '<input type="hidden" name="pos_note" value="'+escapeHtml(document.getElementById('orderNote').value.trim())+'">';

  // build product inputs
  mobileLoadItems();

  // submit
  document.getElementById('pos-sale-form').submit();
});

/* search filter */
document.getElementById('searchInput').addEventListener('input', function(){
  var q = (this.value || '').toLowerCase();
  document.querySelectorAll('#productList .product-card').forEach(function(card){
    var nm = (card.getAttribute('data-name') || '').toLowerCase();
    card.style.display = nm.indexOf(q) !== -1 ? 'block' : 'none';
  });
});

/* init */
renderCart();
updateCartCount();
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet"/>

<script>
$(document).ready(function(){
  $('#poscustomer').select2({
    minimumInputLength: 1,
    ajax: {
      url: "<?= admin_url('customers/suggestions'); ?>",
      dataType: 'json',
      delay: 250,
      data: function (params) {
        return { term: params.term, limit: 10 };
      },
      processResults: function (data) {
        if (data.results) {
          return { results: data.results };
        } else {
          return { results: [] };
        }
      }
    }
  });
});
</script>

</body>
</html>
