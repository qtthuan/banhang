<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TIỆM NƯỚC MINI</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .product-card img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      margin-bottom: 5px;
    }
    .qty-box {
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .qty-box input {
      width: 60px;
      text-align: center;
      font-size: 1.1rem;
    }
    .qty-box button {
      width: 36px;
      height: 36px;
      font-size: 1.2rem;
    }
    .size-options .btn {
      margin: 2px;
      min-width: 60px;
      font-size: 0.9rem;
      height: 42px;
    }
    .btn-add {
      margin-top: 5px;
      font-size: 1rem;
      padding: 8px;
      height: 48px;
    }
    .note-display {
      font-size: 0.8rem;
      color: #555;
      min-height: 18px;
      margin-top: 5px;
    }
    .cart-badge {
      position: absolute;
      top: -5px;
      right: -10px;
    }
  </style>
</head>
<body class="bg-light">

<!-- Header -->
<nav class="navbar navbar-dark bg-success mb-3">
  <div class="container-fluid">
    <span class="navbar-brand mb-0 h1">🥤 TIỆM NƯỚC MINI</span>
    <div class="cart-icon" data-bs-toggle="offcanvas" data-bs-target="#cartCanvas">
      <button class="btn btn-light position-relative">
        🛒
        <span class="badge bg-danger cart-badge" id="cartCount">0</span>
      </button>
    </div>
  </div>
</nav>

<div class="container">

  <!-- Search -->
  <div class="mb-3">
    <input type="text" id="searchInput" class="form-control" placeholder="🔍 Tìm sản phẩm...">
  </div>

  <!-- Products -->
  <div class="row g-2" id="productList">

    <?php foreach ($products as $p): 
      $img = !empty($p->image) ? base_url('assets/uploads/thumbs/'.$p->image) : 'https://banicantho.com/assets/uploads/thumbs/no_image.png';
    ?>
    <div class="col-6 product-card" data-name="<?= strtoupper($p->name) ?>">
      <div class="card h-100">
        <div class="card-body text-center">
          <img src="<?= $img ?>" alt="<?= $p->name ?>">
          <h6 class="card-title"><?= strtoupper($p->name) ?></h6>
          <p class="text-muted mb-1"><?= number_format($p->price,0,',','.') ?>đ</p>

          <!-- size nếu có -->
          <?php if (!empty($p->variants)): ?>
          <div class="btn-group size-options" role="group" aria-label="Size">
            <?php foreach ($p->variants as $i => $v): ?>
              <input type="radio" class="btn-check size-radio" name="size-<?= $p->id ?>" id="size-<?= $p->id ?>-<?= $i ?>" 
                     autocomplete="off" value="<?= $v->name ?>|<?= $v->price ?>" <?= $i==0?'checked':'' ?>>
              <label class="btn btn-outline-primary" for="size-<?= $p->id ?>-<?= $i ?>"><?= $v->name ?></label>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>

          <div class="note-display" id="note-display-<?= $p->id ?>"></div>

          <div class="qty-box my-2">
            <button class="btn btn-sm btn-outline-secondary btn-minus">-</button>
            <input type="number" class="form-control form-control-sm mx-1 qty-input" value="0" min="0">
            <button class="btn btn-sm btn-outline-secondary btn-plus">+</button>
          </div>

          <button class="btn btn-sm btn-outline-info w-100 mb-2 btn-note"
                  data-bs-toggle="modal"
                  data-bs-target="#noteModal"
                  data-id="<?= $p->id ?>">
            Ghi chú món
          </button>

          <button class="btn btn-sm btn-success w-100 btn-add btn-addcart"
                  data-id="<?= $p->id ?>"
                  data-name="<?= strtoupper($p->name) ?>"
                  data-price="<?= $p->price ?>">+ Thêm Món</button>
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
<script>
// Cart lưu ở localStorage
let cart = JSON.parse(localStorage.getItem('cart')) || {items: []};
renderCart();

// Tăng giảm qty
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
  setTimeout(() => document.getElementById('nameInput').focus(), 300);
});

// Checkbox auto append note
const noteInput = document.getElementById('noteInput');
document.querySelectorAll('.note-check').forEach(chk => {
  chk.addEventListener('change', () => {
    let selected = [];
    document.querySelectorAll('.note-check:checked').forEach(c => selected.push(c.value));
    noteInput.value = selected.join(', ');
    noteInput.focus();
  });
});

// Lưu ghi chú hiển thị
document.getElementById('saveNoteBtn').addEventListener('click', () => {
  const productId = document.getElementById('currentProductId').value;
  const displayTarget = document.getElementById('note-display-' + productId);
  let name = document.getElementById('nameInput').value.trim();
  let note = noteInput.value.trim();
  let displayText = '';
  if (name) displayText += 'Người: ' + name;
  if (note) displayText += (name ? ' | ' : '') + 'Ghi chú: ' + note;
  displayTarget.textContent = displayText;
});

// Thêm vào giỏ
document.querySelectorAll('.btn-addcart').forEach(btn => {
  btn.addEventListener('click', () => {
    let card = btn.closest('.card-body');
    let qty = parseInt(card.querySelector('.qty-input').value);
    if (qty <= 0) { alert("Vui lòng chọn số lượng > 0"); return; }
    let id = btn.dataset.id;
    let name = btn.dataset.name;
    let basePrice = parseInt(btn.dataset.price);

    // check size
    let sizeOption = card.querySelector('.size-radio:checked');
    let price = basePrice;
    let size = '';
    if (sizeOption) {
      let [sname, sprice] = sizeOption.value.split('|');
      size = sname;
      price = parseInt(sprice);
    }

    let noteText = document.getElementById('note-display-' + id).textContent;
    cart.items.push({id, name, qty, price, size, note: noteText});
    localStorage.setItem('cart', JSON.stringify(cart));
    renderCart();

    // reset
    card.querySelector('.qty-input').value = 0;
    document.getElementById('note-display-' + id).textContent = '';
  });
});

// Render giỏ
function renderCart() {
  const cartItems = document.getElementById('cartItems');
  if (cart.items.length === 0) {
    cartItems.innerHTML = '<p class="text-muted">Chưa có món nào</p>';
  } else {
    let total = 0;
    cartItems.innerHTML = cart.items.map((item,i) => {
      total += item.price * item.qty;
      return `<div class="border-bottom py-2 d-flex justify-content-between align-items-start">
        <div>
          <strong>${item.name}</strong> ${item.size? '('+item.size+')':''} x${item.qty} - ${item.price*item.qty}đ
          <br><small>${item.note}</small>
        </div>
        <button class="btn btn-sm btn-outline-danger btn-remove" data-index="${i}">✕</button>
      </div>`;
    }).join('') + `<div class="mt-2 fw-bold">Tổng: ${total}đ</div>`;
  }
  let totalQty = cart.items.reduce((sum,it)=>sum+it.qty,0);
  document.getElementById('cartCount').textContent = totalQty;

  document.querySelectorAll('.btn-remove').forEach(btn => {
    btn.addEventListener('click', () => {
      let index = btn.dataset.index;
      cart.items.splice(index,1);
      localStorage.setItem('cart', JSON.stringify(cart));
      renderCart();
    });
  });
}

// Đặt hàng
document.getElementById('placeOrderBtn').addEventListener('click', () => {
  if (cart.items.length===0) { alert("Giỏ hàng trống!"); return; }
  let customer = document.getElementById('customerName').value.trim();
  let phone = document.getElementById('customerPhone').value.trim();
  if (!customer || !phone) { alert("Vui lòng nhập Tên khách và Số điện thoại!"); return; }
  console.log({customer,phone,orderNote:document.getElementById('orderNote').value.trim(),items:cart.items});
  alert("Đặt hàng thành công!");
  cart = {items:[]};
  localStorage.setItem('cart', JSON.stringify(cart));
  renderCart();
  document.getElementById('customerName').value='';
  document.getElementById('customerPhone').value='';
  document.getElementById('orderNote').value='';
});

// Search filter
document.getElementById('searchInput').addEventListener('input', function(){
  let q = this.value.toLowerCase();
  document.querySelectorAll('#productList .product-card').forEach(card=>{
    let name = card.getAttribute('data-name').toLowerCase();
    card.style.display = name.includes(q)?'block':'none';
  });
});
</script>
</body>
</html>
