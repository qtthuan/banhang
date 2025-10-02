<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TIỆM NƯỚC MINI</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f8f9fa; }
    .navbar-brand { font-weight:bold; }
    .product-card img { max-height:100px; width:auto; margin-bottom:10px; }
    .product-card h6 { font-size:0.95rem; font-weight:bold; min-height:40px; }
    .note-display { font-size:0.75rem; color:#555; margin-bottom:4px; min-height:18px; }

    .size-options .btn { font-size:0.9rem; padding:12px 20px; }
    .btn-plus, .btn-minus { font-size:1.5rem; padding:14px 20px; }
    .qty-box input { width:65px; text-align:center; height:100%; font-size:1.1rem; }
    .qty-box { display:flex; justify-content:center; align-items:center; gap:5px; }

    .btn-note { width:40%; font-size:1rem; padding:14px; }
    .btn-addcart { width:60%; font-size:1rem; padding:14px; }
    .cart-badge { position: absolute; top: -5px; right: -10px; }
  </style>
</head>
<body>

<!-- NAVBAR -->
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

<!-- FORM POST -->
<form id="pos-sale-form" method="post" action="<?= admin_url('pos/add'); ?>">
  <div id="posTable"></div>
</form>

<!-- DANH SÁCH SẢN PHẨM -->
<div class="container py-3">
  <div class="row g-2" id="productList">
    <?php foreach ($products as $p): 
      $img = !empty($p->image) ? base_url('assets/uploads/thumbs/'.$p->image) : 'https://banicantho.com/assets/uploads/thumbs/no_image.png';
      $cleanName = preg_replace('/^[A-Z]_\s*/', '', strtoupper($p->name));
    ?>
    <div class="col-6 product-card" data-name="<?= $cleanName ?>">
      <div class="card h-100">
        <div class="card-body text-center">
          <img src="<?= $img ?>" alt="<?= $p->name ?>">
          <h6 class="card-title"><?= $cleanName ?></h6>
          <p class="text-muted mb-1"><?= number_format($p->price,0,',','.') ?>đ</p>

          <?php if (!empty($p->variants)): ?>
          <div class="btn-group size-options mb-2" role="group">
            <?php foreach ($p->variants as $i => $v): ?>
              <input type="radio" class="btn-check size-radio"
                     name="size-<?= $p->id ?>"
                     id="size-<?= $p->id ?>-<?= $i ?>"
                     value="<?= $v->name ?>|<?= $v->price ?>"
                     <?= $i==0?'checked':'' ?>>
              <label class="btn btn-outline-primary" for="size-<?= $p->id ?>-<?= $i ?>"><?= $v->name ?></label>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>

          <div class="note-display" id="note-display-<?= $p->id ?>"></div>

          <div class="qty-box my-2">
            <button class="btn btn-outline-secondary btn-minus">-</button>
            <input type="number" class="form-control qty-input" value="0" min="0">
            <button class="btn btn-outline-secondary btn-plus">+</button>
          </div>

          <div class="d-flex gap-2">
            <button class="btn btn-info btn-note"
                    data-bs-toggle="modal"
                    data-bs-target="#noteModal"
                    data-id="<?= $p->id ?>">
              📝 Ghi chú
            </button>
            <button class="btn btn-success btn-addcart"
                    data-id="<?= $p->id ?>"
                    data-name="<?= $cleanName ?>"
                    data-price="<?= $p->price ?>">
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
      <div class="modal-header">
        <h5 class="modal-title">Ghi chú món</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="currentProductId">
        <input type="text" class="form-control mb-2" id="nameInput" placeholder="Tên (ví dụ: A, B...)">
        <textarea class="form-control mb-2" id="noteInput" placeholder="Nhập ghi chú..."></textarea>

        <div class="form-check">
          <input class="form-check-input note-check" type="checkbox" value="Ít ngọt" id="note1">
          <label class="form-check-label" for="note1">Ít ngọt</label>
        </div>
        <div class="form-check">
          <input class="form-check-input note-check" type="checkbox" value="Không đá" id="note2">
          <label class="form-check-label" for="note2">Không đá</label>
        </div>
        <div class="form-check">
          <input class="form-check-input note-check" type="checkbox" value="Nhiều cafe" id="note3">
          <label class="form-check-label" for="note3">Nhiều cafe</label>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
        <button type="button" class="btn btn-success" id="saveNoteBtn" data-bs-dismiss="modal">Lưu</button>
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
    <div id="cartItems" class="mb-3">
      <p class="text-muted">Chưa có món nào</p>
    </div>

    <div class="mb-3">
      <input type="text" class="form-control mb-2" id="customerName" placeholder="Tên khách">
      <input type="tel" class="form-control mb-2" id="customerPhone" placeholder="Số điện thoại">
      <textarea class="form-control" id="orderNote" rows="2" placeholder="Ghi chú đơn..."></textarea>
    </div>

    <button class="btn btn-success mt-auto" id="placeOrderBtn">Đặt hàng</button>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= $assets ?>pos/js/pos.mobile.js"></script>
<script>
let currentNotes = {}; // lưu note tạm theo productId
let cartCount = 0;

// Tăng giảm số lượng
document.querySelectorAll('.btn-plus').forEach(btn => {
  btn.addEventListener('click', () => {
    let input = btn.closest('.qty-box').querySelector('.qty-input');
    input.value = parseInt(input.value) + 1;
  });
});
document.querySelectorAll('.btn-minus').forEach(btn => {
  btn.addEventListener('click', () => {
    let input = btn.closest('.qty-box').querySelector('.qty-input');
    if (parseInt(input.value) > 0) input.value = parseInt(input.value) - 1;
  });
});

// Modal ghi chú
const noteModal = document.getElementById('noteModal');
noteModal.addEventListener('show.bs.modal', function (event) {
  const button = event.relatedTarget;
  const productId = button.getAttribute('data-id');
  document.getElementById('currentProductId').value = productId;
  document.getElementById('nameInput').value = '';
  document.getElementById('noteInput').value = '';
  document.querySelectorAll('.note-check').forEach(c => c.checked = false);
  setTimeout(()=>document.getElementById('nameInput').focus(),300);
});
document.querySelectorAll('.note-check').forEach(chk => {
  chk.addEventListener('change', () => {
    let selected = [];
    document.querySelectorAll('.note-check:checked').forEach(c => selected.push(c.value));
    let noteInput = document.getElementById('noteInput');
    noteInput.value = selected.join(', ');
    noteInput.focus();
  });
});
document.getElementById('saveNoteBtn').addEventListener('click', () => {
  const productId = document.getElementById('currentProductId').value;
  const displayTarget = document.getElementById('note-display-' + productId);
  let name = document.getElementById('nameInput').value.trim();
  let note = document.getElementById('noteInput').value.trim();
  let displayText = '';
  if (name) displayText += 'Người: ' + name;
  if (note) displayText += (name ? ' | ' : '') + 'Ghi chú: ' + note;
  displayTarget.textContent = displayText;
  currentNotes[productId] = displayText;
});

// Thêm vào giỏ
document.querySelectorAll('.btn-addcart').forEach(btn => {
  btn.addEventListener('click', () => {
    let card = btn.closest('.card-body');
    let qty = parseInt(card.querySelector('.qty-input').value);
    if (qty <= 0) {
      alert("Vui lòng chọn số lượng > 0");
      return;
    }
    let id = btn.dataset.id;
    let name = btn.dataset.name;
    let price = parseInt(btn.dataset.price);
    let sizeEl = card.querySelector('.size-radio:checked');
    let option = sizeEl ? sizeEl.value : '';
    let noteText = currentNotes[id] || '';

    mobileAddItem(id, name, price, qty, option, noteText);

    card.querySelector('.qty-input').value = 0;
    document.getElementById('note-display-' + id).textContent = '';
    currentNotes[id] = '';

    cartCount++;
    document.getElementById('cartCount').textContent = cartCount;
    renderCart();
  });
});

// Render giỏ hàng (đơn giản từ positems)
function renderCart() {
  const cartItems = document.getElementById('cartItems');
  let items = JSON.parse(localStorage.getItem('positems')) || {};
  if (Object.keys(items).length === 0) {
    cartItems.innerHTML = '<p class="text-muted">Chưa có món nào</p>';
    return;
  }
  let total = 0;
  let html = '';
  Object.values(items).forEach((item, i) => {
    total += item.row.price * item.row.quantity;
    html += `<div class="border-bottom py-2 d-flex justify-content-between align-items-start">
        <div><strong>${item.row.name}</strong> ${item.row.option?`(${item.row.option})`:''} 
        x${item.row.quantity} - ${item.row.price * item.row.quantity}đ<br>
        <small>${item.row.comment||''}</small></div>
        <button class="btn btn-sm btn-outline-danger" onclick="mobileRemoveItem(${item.id});renderCart();">✕</button>
      </div>`;
  });
  html += `<div class="mt-2 fw-bold">Tổng: ${total}đ</div>`;
  cartItems.innerHTML = html;
}

// Submit đơn
document.getElementById('placeOrderBtn').addEventListener('click', () => {
  let customer = document.getElementById('customerName').value.trim();
  let phone = document.getElementById('customerPhone').value.trim();
  let orderNote = document.getElementById('orderNote').value.trim();
  if (!customer || !phone) {
    alert("Vui lòng nhập Tên khách và Số điện thoại!");
    return;
  }
  // thêm info vào form
  let hf = document.getElementById('posTable');
  hf.innerHTML += `<input type="hidden" name="customer_name" value="${customer}">`;
  hf.innerHTML += `<input type="hidden" name="customer_phone" value="${phone}">`;
  hf.innerHTML += `<input type="hidden" name="order_note" value="${orderNote}">`;

  mobileLoadItems();
  document.getElementById('pos-sale-form').submit();
});

// Search filter
document.getElementById('searchInput').addEventListener('keyup', () => {
  let val = document.getElementById('searchInput').value.toLowerCase();
  document.querySelectorAll('#productList .product-card').forEach(card => {
    let name = card.getAttribute('data-name').toLowerCase();
    card.style.display = name.includes(val) ? 'block' : 'none';
  });
});

// Load lại giỏ khi refresh
renderCart();
</script>
</body>
</html>
