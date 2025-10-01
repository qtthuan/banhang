<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>TIỆM NƯỚC MINI</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f8f9fa; }
    .product-card { border:1px solid #ddd; border-radius:8px; padding:10px; text-align:center; background:#fff; }
    .product-card img { width:100px; height:100px; object-fit:cover; margin-bottom:8px; }
    .size-btn input { display:none; }
    .size-btn label {
      margin:2px;
      padding:5px 10px;
      border:1px solid #28a745;
      border-radius:4px;
      cursor:pointer;
    }
    .size-btn input:checked + label {
      background:#28a745;
      color:#fff;
    }
    .qty-control button {
      font-size:20px;
      width:36px;
      height:36px;
    }
    .cart-btn {
      position:fixed; top:10px; right:10px;
    }
  </style>
</head>
<body>

<!-- Header -->
<nav class="navbar navbar-success bg-success">
  <div class="container-fluid">
    <span class="navbar-brand mb-0 h1 text-white">🥤 TIỆM NƯỚC MINI</span>
    <button class="btn btn-light cart-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#cartCanvas">
      🛒 <span class="badge bg-danger" id="cartCount">0</span>
    </button>
  </div>
</nav>

<!-- Search -->
<div class="container mt-2">
  <input type="text" id="searchInput" class="form-control" placeholder="🔍 Tìm sản phẩm...">
</div>

<!-- Products -->
<div class="container mt-3">
  <div class="row g-2" id="productList"></div>
</div>

<!-- Offcanvas Cart -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="cartCanvas">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">🛒 Giỏ hàng</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <ul class="list-group" id="cartItems"></ul>
    <hr>
    <h5>Tổng: <span id="cartTotal">0</span>đ</h5>
    <button class="btn btn-success w-100 mt-2">Đặt hàng</button>
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
        <input type="text" id="noteTitle" class="form-control mb-2" placeholder="Tên (A, B...)">
        <textarea id="noteText" class="form-control mb-2" placeholder="Nhập ghi chú..."></textarea>
        <div class="form-check"><input class="form-check-input" type="checkbox" id="noteLessSugar"><label class="form-check-label">Ít ngọt</label></div>
        <div class="form-check"><input class="form-check-input" type="checkbox" id="noteNoIce"><label class="form-check-label">Không đá</label></div>
        <div class="form-check"><input class="form-check-input" type="checkbox" id="noteMoreCoffee"><label class="form-check-label">Nhiều cafe</label></div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
        <button class="btn btn-success" id="saveNoteBtn">Lưu</button>
      </div>
    </div>
  </div>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const products = [
  {name:"CÀ PHÊ", price:15000},
  {name:"CÀ PHÊ HẠNH NHÂN", price:25000},
  {name:"CÀ PHÊ SỮA", price:20000},
  {name:"CÀ PHÊ SỮA TƯƠI HẠT ĐÁC", price:28000},
  {name:"CÀ PHÊ SỮA TƯƠI SƯƠNG SÁO", price:22000},
  {name:"CÀ PHÊ SỮA TƯƠI THỐT NỐT", price:28000},
  {name:"MILO SỮA", price:22000},
  {name:"SỮA CHUA TRÁI CÂY", price:22000},
  {name:"TÀU HŨ MATCHA LATTE", price:25000},
  {name:"TRÀ SỮA GẠO", price:25000},
  {name:"TRÀ SỮA TRUYỀN THỐNG", price:25000},
  {name:"SINH TỐ BƠ", price:25000},
  {name:"SINH TỐ DÂU", price:25000},
  {name:"SINH TỐ MÃNG CẦU", price:22000},
  {name:"SINH TỐ SA BÔ", price:22000},
  {name:"TRÀ CỐC", price:25000},
  {name:"TRÀ DÂU TẰM", price:25000},
  {name:"TRÀ DÂU TẰM HẠT ĐÁC", price:28000},
  {name:"TRÀ DỨA", price:25000},
  {name:"TRÀ DỨA HẠT ĐÁC", price:28000},
  {name:"TRÀ DỨA LƯỚI", price:25000},
  {name:"TRÀ DỨA THỐT NỐT", price:28000},
  {name:"TRÀ MÃNG CẦU", price:25000},
  {name:"TRÀ TRÁI CÂY", price:25000},
  {name:"TRÀ VẢI", price:22000},
  {name:"TRÀ ĐÀO", price:22000},
  {name:"CÀ RỐT", price:15000},
  {name:"CAM", price:15000},
  {name:"CAM-CÀ RỐT", price:18000},
  {name:"DƯA HẤU", price:15000},
  {name:"KHÓM", price:18000},
  {name:"KHÓM-CÀ RỐT", price:18000},
  {name:"KHÓM-CAM", price:18000},
  {name:"KHÓM-DƯA HẤU", price:18000},
  {name:"KHÓM-ỔI", price:18000},
  {name:"KHÓM-SƠ RI", price:18000},
  {name:"MIX 2 VỊ", price:18000},
  {name:"MIX 2 VỊ (CÓ TÁO)", price:22000},
  {name:"ỔI", price:15000},
  {name:"SƠ RI", price:15000},
  {name:"SƠ RI-CÀ CHUA", price:18000},
  {name:"SƠ RI-ỔI", price:18000},
  {name:"TÁO", price:22000},
  {name:"TÁO-CÀ RỐT", price:22000},
  {name:"TÁO-DƯA HẤU", price:22000},
  {name:"TÁO-KHÓM", price:22000},
  {name:"TÁO-ỔI", price:22000},
  {name:"TÁO-SƠ RI", price:22000},
  {name:"CÀ RỐT (CHAI)", price:20000},
  {name:"CAM (CHAI)", price:20000},
  {name:"DƯA HẤU (CHAI)", price:20000},
  {name:"KHÓM (CHAI)", price:23000},
  {name:"ỔI (CHAI)", price:20000},
  {name:"SƠ RI (CHAI)", price:20000},
  {name:"TÁO (CHAI)", price:27000},
  {name:"BẠC XỈU", price:17000},
  {name:"TRÀ ĐÁ", price:3000},
  {name:"TRÀ ĐƯỜNG", price:5000},
  {name:"YAOURT ĐÁ", price:17000},
  {name:"YAOURT ĐÁ HẠT ĐÁC", price:25000},
  {name:"BÁNH TRÁNG", price:13000},
  {name:"NƯỚC ĐÁ", price:1000},
  {name:"SỮA CHUA CHAI", price:7000},
  {name:"SỮA CHUA CHAI MIX TRÁI CÂY", price:8000}
];

let cart = [];
let currentNoteIndex = null;

// Render products
function renderProducts(list) {
  const container = document.getElementById('productList');
  container.innerHTML = '';
  list.forEach((p, index) => {
    container.innerHTML += `
      <div class="col-6 col-md-3">
        <div class="product-card">
          <img src="/assets/uploads/thumbs/no_image.png" alt="${p.name}">
          <h6>${p.name}</h6>
          <p>${p.price.toLocaleString()}đ</p>
          <div class="size-btn">
            <input type="radio" name="size${index}" id="m${index}" value="M" checked>
            <label for="m${index}">Size M</label>
            <input type="radio" name="size${index}" id="l${index}" value="L">
            <label for="l${index}">Size L (+5k)</label>
          </div>
          <div class="qty-control d-flex justify-content-center align-items-center mt-2">
            <button class="btn btn-outline-secondary" onclick="changeQty(${index}, -1)">-</button>
            <span id="qty${index}" class="mx-2">1</span>
            <button class="btn btn-outline-secondary" onclick="changeQty(${index}, 1)">+</button>
          </div>
          <button class="btn btn-success btn-sm w-100 mt-2" onclick="addToCart(${index})">+ Thêm Món</button>
        </div>
      </div>
    `;
  });
}
renderProducts(products);

// Quantity change
function changeQty(i, delta) {
  let qtyEl = document.getElementById('qty'+i);
  let qty = parseInt(qtyEl.innerText) + delta;
  if(qty < 1) qty = 1;
  qtyEl.innerText = qty;
}

// Add to cart
function addToCart(i) {
  let qty = parseInt(document.getElementById('qty'+i).innerText);
  let size = document.querySelector('input[name="size'+i+'"]:checked').value;
  let note = "";
  let price = products[i].price + (size=="L"?5000:0);
  cart.push({name:products[i].name, size:size, qty:qty, price:price, note:note});
  updateCart();
}

// Update cart
function updateCart() {
  document.getElementById('cartCount').innerText = cart.length;
  let cartList = document.getElementById('cartItems');
  cartList.innerHTML = '';
  let total = 0;
  cart.forEach((c, idx) => {
    let sub = c.price * c.qty;
    total += sub;
    cartList.innerHTML += `<li class="list-group-item d-flex justify-content-between">
      <div>
        ${c.name} (${c.size}) x${c.qty}<br><small>${c.note||''}</small>
      </div>
      <span>${sub.toLocaleString()}đ</span>
    </li>`;
  });
  document.getElementById('cartTotal').innerText = total.toLocaleString();
}

// Search filter
document.getElementById('searchInput').addEventListener('input', e => {
  let val = e.target.value.toLowerCase();
  let filtered = products.filter(p => p.name.toLowerCase().includes(val));
  renderProducts(filtered);
});
</script>

</body>
</html>
