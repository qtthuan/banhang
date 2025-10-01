<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TIỆM NƯỚC MINI</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
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
      width: 40px;
      height: 40px;
      font-size: 1.2rem;
      line-height: 1;
    }
    .note-display {
      font-size: 0.8rem;
      color: #555;
      margin-top: 5px;
      min-height: 18px;
    }
    .cart-badge {
      position: absolute;
      top: -5px;
      right: -10px;
    }
    .product-img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      margin: 0 auto 8px auto;
      display: block;
    }
    .size-options .btn {
      min-width: 60px;
    }
  </style>
</head>
<body class="bg-light">

<!-- Header -->
<nav class="navbar navbar-dark bg-success mb-3">
  <div class="container-fluid">
    <span class="navbar-brand mb-0 h1">🍹 TIỆM NƯỚC MINI</span>
    <div class="cart-icon" data-bs-toggle="offcanvas" data-bs-target="#cartCanvas">
      <button class="btn btn-light position-relative">
        🛒
        <span class="badge bg-danger cart-badge" id="cartCount">0</span>
      </button>
    </div>
  </div>
</nav>

<div class="container">
  <div class="row g-2" id="productList">
    <!-- Danh sách sản phẩm render ở đây -->
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
        <input type="text" class="form-control mb-2" id="nameInput" placeholder="Tên (A, B...)">
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
    <!-- Thông tin khách -->
    <div class="mb-3">
      <input type="text" class="form-control mb-2" id="customerName" placeholder="Tên khách">
      <input type="tel" class="form-control mb-2" id="customerPhone" placeholder="Số điện thoại">
      <textarea class="form-control" id="orderNote" rows="2" placeholder="Ghi chú đơn..."></textarea>
    </div>
    <!-- Nút đặt hàng -->
    <button class="btn btn-success mt-auto" id="placeOrderBtn">Đặt hàng</button>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const products = [];
for (let i = 1; i <= 60; i++) {
  products.push({
    id: i,
    name: "SẢN PHẨM " + i,
    priceM: 20000,
    priceL: 25000,
    img: "https://via.placeholder.com/100x100.png?text=Fruit+"+i
  });
}

let cart = JSON.parse(localStorage.getItem('cart')) || {items: []};
renderCart();

// render sản phẩm
function renderProducts() {
  const list = document.getElementById('productList');
  list.innerHTML = products.map(p => `
    <div class="col-6">
      <div class="card h-100 text-center p-2">
        <img src="${p.img}" class="product-img" alt="${p.name}">
        <h6 class="card-title">${p.name}</h6>
        <div class="size-options btn-group mb-2" role="group" data-id="${p.id}">
          <input type="radio" class="btn-check" name="size-${p.id}" id="sizeM-${p.id}" value="M" checked>
          <label class="btn btn-outline-primary btn-sm" for="sizeM-${p.id}">Size M</label>
          <input type="radio" class="btn-check" name="size-${p.id}" id="sizeL-${p.id}" value="L">
          <label class="btn btn-outline-primary btn-sm" for="sizeL-${p.id}">Size L</label>
        </div>
        <p class="text-muted">M: ${p.priceM}đ - L: ${p.priceL}đ</p>
        <div class="note-display" id="note-display-${p.id}"></div>
        <div class="qty-box mb-2">
          <button class="btn btn-sm btn-outline-secondary btn-minus">-</button>
          <input type="number" class="form-control form-control-sm mx-1 qty-input" value="0" min="0">
          <button class="btn btn-sm btn-outline-secondary btn-plus">+</button>
        </div>
        <button class="btn btn-sm btn-outline-info w-100 mb-2 btn-note"
                data-bs-toggle="modal" data-bs-target="#noteModal"
                data-id="${p.id}">Ghi chú món</button>
        <button class="btn btn-sm btn-outline-success w-100 btn-addcart"
                data-id="${p.id}" data-name="${p.name}"
                data-priceM="${p.priceM}" data-priceL="${p.priceL}">+ Thêm Món</button>
      </div>
    </div>
  `).join('');
}
renderProducts();

// Tăng giảm số lượng
document.addEventListener('click', function(e){
  if(e.target.classList.contains('btn-plus')){
    let input = e.target.closest('.qty-box').querySelector('.qty-input');
    input.value = parseInt(input.value) + 1;
  }
  if(e.target.classList.contains('btn-minus')){
    let input = e.target.closest('.qty-box').querySelector('.qty-input');
    if(parseInt(input.value) > 0) input.value = parseInt(input.value) - 1;
  }
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
});

// Checkbox update text
const noteInput = document.getElementById('noteInput');
document.querySelectorAll('.note-check').forEach(chk => {
  chk.addEventListener('change', () => {
    let selected = [];
    document.querySelectorAll('.note-check:checked').forEach(c => selected.push(c.value));
    noteInput.value = selected.join(', ');
  });
});

// Lưu ghi chú
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
document.addEventListener('click', function(e){
  if(e.target.classList.contains('btn-addcart')){
    let card = e.target.closest('.card');
    let qty = parseInt(card.querySelector('.qty-input').value);
    if (qty <= 0) { alert("Vui lòng chọn số lượng > 0"); return; }
    let id = e.target.dataset.id;
    let name = e.target.dataset.name;
    let priceM = parseInt(e.target.dataset.pricem);
    let priceL = parseInt(e.target.dataset.pricel);
    let size = card.querySelector('input[name="size-'+id+'"]:checked').value;
    let price = (size === 'M') ? priceM : priceL;
    let noteText = document.getElementById('note-display-' + id).textContent;
    cart.items.push({id, name, qty, size, price, note: noteText});
    localStorage.setItem('cart', JSON.stringify(cart));
    renderCart();
    card.querySelector('.qty-input').value = 0;
    document.getElementById('note-display-' + id).textContent = '';
  }
});

// Render giỏ
function renderCart() {
  const cartItems = document.getElementById('cartItems');
  if (cart.items.length === 0) {
    cartItems.innerHTML = '<p class="text-muted">Chưa có món nào</p>';
  } else {
    let total = 0;
    cartItems.innerHTML = cart.items.map((item, i) => {
      total += item.price * item.qty;
      return `<div class="border-bottom py-2 d-flex justify-content-between align-items-start">
         <div>
           <strong>${item.name}</strong> (${item.size}) x${item.qty} - ${item.price * item.qty}đ
           <br><small>${item.note}</small>
         </div>
         <button class="btn btn-sm btn-outline-danger btn-remove" data-index="${i}">✕</button>
       </div>`;
    }).join('') + `<div class="mt-2 fw-bold">Tổng: ${total}đ</div>`;
  }
  let totalQty = cart.items.reduce((sum, it) => sum + it.qty, 0);
  document.getElementById('cartCount').textContent = totalQty;
  document.querySelectorAll('.btn-remove').forEach(btn => {
    btn.addEventListener('click', () => {
      let index = btn.dataset.index;
      cart.items.splice(index, 1);
      localStorage.setItem('cart', JSON.stringify(cart));
      renderCart();
    });
  });
}

// Đặt hàng
document.getElementById('placeOrderBtn').addEventListener('click', () => {
  if (cart.items.length === 0) {
    alert("Giỏ hàng trống!"); return;
  }
  let customer = document.getElementById('customerName').value.trim();
  let phone = document.getElementById('customerPhone').value.trim();
  let orderNote = document.getElementById('orderNote').value.trim();
  if (!customer || !phone) {
    alert("Vui lòng nhập Tên khách và Số điện thoại!"); return;
  }
  console.log({customer, phone, orderNote, items: cart.items});
  alert("Đặt hàng thành công!\nCảm ơn " + customer);
  cart = {items: []};
  localStorage.setItem('cart', JSON.stringify(cart));
  renderCart();
  document.getElementById('customerName').value = '';
  document.getElementById('customerPhone').value = '';
  document.getElementById('orderNote').value = '';
});
</script>
</body>
</html>
