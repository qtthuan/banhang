<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TIỆM NƯỚC MINI</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .product-img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      margin: 0 auto 8px auto;
      display: block;
    }
    .qty-box {
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .qty-box input {
      width: 50px;
      text-align: center;
      font-size: 1.1rem;
    }
    .qty-box button {
      width: 40px;
      height: 40px;
      font-size: 1.2rem;
      line-height: 1;
    }
    .cart-badge {
      position: absolute;
      top: -5px;
      right: -10px;
    }
    .btn-group .btn {
      min-width: 50px;
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

<div class="container mb-3">
  <!-- Ô tìm kiếm -->
  <input type="text" id="searchBox" class="form-control" placeholder="🔍 Tìm sản phẩm...">
</div>

<div class="container">
  <div class="row g-2" id="productList">

    <!-- Sản phẩm sẽ render qua JS -->

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
        <input type="text" class="form-control mb-2" id="noteInput" placeholder="Nhập ghi chú...">
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
        <button type="button" class="btn btn-success" id="saveNoteBtn" data-bs-dismiss="modal">Lưu ghi chú</button>
      </div>
    </div>
  </div>
</div>

<!-- Offcanvas Giỏ hàng -->
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
// Danh sách sản phẩm
const products = [
  {id:1, name:"CÀ PHÊ", price:15000},
  {id:2, name:"CÀ PHÊ HẠNH NHÂN", price:25000},
  {id:3, name:"CÀ PHÊ SỮA", price:20000},
  {id:4, name:"CÀ PHÊ SỮA TƯƠI HẠT ĐÁC", price:28000},
  {id:5, name:"CÀ PHÊ SỮA TƯƠI SƯƠNG SÁO", price:22000},
  {id:6, name:"CÀ PHÊ SỮA TƯƠI THỐT NỐT", price:28000},
  {id:7, name:"MILO SỮA", price:22000},
  {id:8, name:"SỮA CHUA TRÁI CÂY", price:22000},
  {id:9, name:"TÀU HŨ MATCHA LATTE", price:25000},
  {id:10, name:"TRÀ SỮA GẠO", price:25000},
  {id:11, name:"TRÀ SỮA TRUYỀN THỐNG", price:25000},
  {id:12, name:"SINH TỐ BƠ", price:25000},
  {id:13, name:"SINH TỐ DÂU", price:25000},
  {id:14, name:"SINH TỐ MÃNG CẦU", price:22000},
  {id:15, name:"SINH TỐ SA BÔ", price:22000},
  {id:16, name:"TRÀ CỐC", price:25000},
  {id:17, name:"TRÀ DÂU TẰM", price:25000},
  {id:18, name:"TRÀ DÂU TẰM HẠT ĐÁC", price:28000},
  {id:19, name:"TRÀ DỨA", price:25000},
  {id:20, name:"TRÀ DỨA HẠT ĐÁC", price:28000},
  {id:21, name:"TRÀ DỨA LƯỚI", price:25000},
  {id:22, name:"TRÀ DỨA THỐT NỐT", price:28000},
  {id:23, name:"TRÀ MÃNG CẦU", price:25000},
  {id:24, name:"TRÀ TRÁI CÂY", price:25000},
  {id:25, name:"TRÀ VẢI", price:22000},
  {id:26, name:"TRÀ ĐÀO", price:22000},
  {id:27, name:"NƯỚC ÉP CÀ RỐT", price:15000},
  {id:28, name:"NƯỚC ÉP CAM", price:15000},
  {id:29, name:"NƯỚC ÉP CAM-CÀ RỐT", price:18000},
  {id:30, name:"NƯỚC ÉP DƯA HẤU", price:15000},
  {id:31, name:"NƯỚC ÉP KHÓM", price:18000},
  {id:32, name:"NƯỚC ÉP KHÓM-CÀ RỐT", price:18000},
  {id:33, name:"NƯỚC ÉP KHÓM-CAM", price:18000},
  {id:34, name:"NƯỚC ÉP KHÓM-DƯA HẤU", price:18000},
  {id:35, name:"NƯỚC ÉP KHÓM-ỔI", price:18000},
  {id:36, name:"NƯỚC ÉP KHÓM-SƠ RI", price:18000},
  {id:37, name:"NƯỚC ÉP MIX 2 VỊ", price:18000},
  {id:38, name:"NƯỚC ÉP MIX 2 VỊ (CÓ TÁO)", price:22000},
  {id:39, name:"NƯỚC ÉP ỔI", price:15000},
  {id:40, name:"NƯỚC ÉP SƠ RI", price:15000},
  {id:41, name:"NƯỚC ÉP SƠ RI-CÀ CHUA", price:18000},
  {id:42, name:"NƯỚC ÉP SƠ RI-ỔI", price:18000},
  {id:43, name:"NƯỚC ÉP TÁO", price:22000},
  {id:44, name:"NƯỚC ÉP TÁO-CÀ RỐT", price:22000},
  {id:45, name:"NƯỚC ÉP TÁO-DƯA HẤU", price:22000},
  {id:46, name:"NƯỚC ÉP TÁO-KHÓM", price:22000},
  {id:47, name:"NƯỚC ÉP TÁO-ỔI", price:22000},
  {id:48, name:"NƯỚC ÉP TÁO-SƠ RI", price:22000},
  {id:49, name:"CÀ RỐT (CHAI)", price:20000},
  {id:50, name:"CAM (CHAI)", price:20000},
  {id:51, name:"DƯA HẤU (CHAI)", price:20000},
  {id:52, name:"KHÓM (CHAI)", price:23000},
  {id:53, name:"ỔI (CHAI)", price:20000},
  {id:54, name:"SƠ RI (CHAI)", price:20000},
  {id:55, name:"TÁO (CHAI)", price:27000},
  {id:56, name:"BẠC XỈU", price:17000},
  {id:57, name:"TRÀ ĐÁ", price:3000},
  {id:58, name:"TRÀ ĐƯỜNG", price:5000},
  {id:59, name:"YAOURT ĐÁ", price:17000},
  {id:60, name:"YAOURT ĐÁ HẠT ĐÁC", price:25000},
  {id:61, name:"BÁNH TRÁNG", price:13000},
  {id:62, name:"NƯỚC ĐÁ", price:1000},
  {id:63, name:"SỮA CHUA CHAI", price:7000},
  {id:64, name:"SỮA CHUA CHAI MIX TRÁI CÂY", price:8000},
];

// Render sản phẩm
const productList = document.getElementById('productList');
const imgUrl = "https://banicantho.com/assets/uploads/thumbs/no_image.png";

function renderProducts(list){
  productList.innerHTML = list.map(p => `
    <div class="col-6">
      <div class="card h-100 text-center">
        <img src="${imgUrl}" class="product-img" alt="${p.name}">
        <div class="card-body">
          <h6 class="card-title">${p.name}</h6>
          <p class="text-muted">M: ${p.price.toLocaleString()}đ<br>L: ${(p.price+5000).toLocaleString()}đ</p>
          <div class="btn-group mb-2" role="group">
            <input type="radio" class="btn-check" name="size${p.id}" id="m${p.id}" data-price="${p.price}" checked>
            <label class="btn btn-outline-secondary btn-sm" for="m${p.id}">M</label>
            <input type="radio" class="btn-check" name="size${p.id}" id="l${p.id}" data-price="${p.price+5000}">
            <label class="btn btn-outline-secondary btn-sm" for="l${p.id}">L</label>
          </div>
          <div class="qty-box mb-2">
            <button class="btn btn-sm btn-outline-secondary btn-minus">-</button>
            <input type="number" class="form-control form-control-sm mx-1 qty-input" value="0" min="0">
            <button class="btn btn-sm btn-outline-secondary btn-plus">+</button>
          </div>
          <button class="btn btn-sm btn-outline-info w-100 mb-2 btn-note"
            data-bs-toggle="modal"
            data-bs-target="#noteModal"
            data-id="${p.id}"
            data-product="${p.name}">
            Ghi chú món
          </button>
          <button class="btn btn-sm btn-outline-success w-100 btn-addcart"
            data-id="${p.id}"
            data-name="${p.name}">+ Thêm Món</button>
        </div>
      </div>
    </div>
  `).join('');
}
renderProducts(products);

// Search filter
document.getElementById('searchBox').addEventListener('input', function(){
  const keyword = this.value.toLowerCase();
  const filtered = products.filter(p => p.name.toLowerCase().includes(keyword));
  renderProducts(filtered);
  attachEvents();
});

// Giỏ hàng
let cart = JSON.parse(localStorage.getItem('cart')) || {items: []};
function renderCart(){
  const cartItems = document.getElementById('cartItems');
  if(cart.items.length===0){
    cartItems.innerHTML = '<p class="text-muted">Chưa có món nào</p>';
  } else {
    let total = 0;
    cartItems.innerHTML = cart.items.map((it,i)=>{
      total += it.price*it.qty;
      return `<div class="border-bottom py-2 d-flex justify-content-between align-items-start">
        <div><strong>${it.name}</strong> x${it.qty} - ${(it.price*it.qty).toLocaleString()}đ<br><small>${it.note||''}</small></div>
        <button class="btn btn-sm btn-outline-danger btn-remove" data-index="${i}">✕</button>
      </div>`;
    }).join('') + `<div class="mt-2 fw-bold">Tổng: ${total.toLocaleString()}đ</div>`;
  }
  let totalQty = cart.items.reduce((s,it)=>s+it.qty,0);
  document.getElementById('cartCount').textContent = totalQty;
  document.querySelectorAll('.btn-remove').forEach(btn=>{
    btn.onclick = ()=>{ cart.items.splice(btn.dataset.index,1); localStorage.setItem('cart',JSON.stringify(cart)); renderCart(); };
  });
}
renderCart();

// Events
function attachEvents(){
  document.querySelectorAll('.btn-plus').forEach(btn=>{
    btn.onclick = ()=>{ let input=btn.closest('.qty-box').querySelector('.qty-input'); input.value=parseInt(input.value)+1; };
  });
  document.querySelectorAll('.btn-minus').forEach(btn=>{
    btn.onclick = ()=>{ let input=btn.closest('.qty-box').querySelector('.qty-input'); if(parseInt(input.value)>0) input.value=parseInt(input.value)-1; };
  });
  document.querySelectorAll('.btn-addcart').forEach(btn=>{
    btn.onclick = ()=>{
      let card=btn.closest('.card-body');
      let qty=parseInt(card.querySelector('.qty-input').value);
      if(qty<=0){alert("Chọn số lượng > 0");return;}
      let id=btn.dataset.id;
      let name=btn.dataset.name;
      let sizePrice=card.querySelector('input[name="size'+id+'"]:checked').dataset.price;
      let note=document.getElementById('note-display-'+id)?.textContent||'';
      cart.items.push({id,name,qty,price:parseInt(sizePrice),note});
      localStorage.setItem('cart',JSON.stringify(cart));
      renderCart();
      card.querySelector('.qty-input').value=0;
    };
  });
}
attachEvents();

// Modal ghi chú
const noteModal=document.getElementById('noteModal');
noteModal.addEventListener('show.bs.modal',function(ev){
  document.getElementById('noteInput').value='';
  document.querySelectorAll('.note-check').forEach(c=>c.checked=false);
});
document.getElementById('saveNoteBtn').onclick=function(){
  let note=document.getElementById('noteInput').value.trim();
  document.querySelectorAll('.note-check:checked').forEach(c=>note+=(note?', ':'')+c.value);
  // lưu note vào biến tạm? ở đây gắn thẳng luôn vào cart sau khi thêm món
  alert("Ghi chú: "+note+"\n(Sẽ thêm vào món khi bạn bấm + Thêm Món)");
};
</script>
</body>
</html>
