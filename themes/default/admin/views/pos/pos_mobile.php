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
      font-size: 1.2rem;
    }
    .qty-box button {
      width: 45px;
      height: 45px;
      font-size: 1.5rem;
    }
    .cart-badge {
      position: absolute;
      top: -5px;
      right: -10px;
    }
    .size-group .btn {
      margin: 2px;
      padding: 6px 16px;
      font-size: 1rem;
    }
    .size-group .btn-check:checked + .btn {
      background-color: #198754;
      color: #fff;
    }
    .product-card img {
      max-height: 100px;
      object-fit: cover;
    }
    .note-display {
      font-size: 0.85rem;
      color: #555;
      min-height: 20px;
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

  <div class="row g-2">
    <?php
    $items = [
      ["id"=>1,"name"=>"CÀ PHÊ","price"=>15000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>2,"name"=>"CÀ PHÊ HẠNH NHÂN","price"=>25000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>3,"name"=>"CÀ PHÊ SỮA","price"=>20000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>4,"name"=>"CÀ PHÊ SỮA TƯƠI HẠT ĐÁC","price"=>28000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>5,"name"=>"CÀ PHÊ SỮA TƯƠI SƯƠNG SÁO","price"=>22000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>6,"name"=>"CÀ PHÊ SỮA TƯƠI THỐT NỐT","price"=>28000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>7,"name"=>"MILO SỮA","price"=>22000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>8,"name"=>"SỮA CHUA TRÁI CÂY","price"=>22000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>9,"name"=>"TÀU HŨ MATCHA LATTE","price"=>25000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>10,"name"=>"TRÀ SỮA GẠO","price"=>25000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>11,"name"=>"TRÀ SỮA TRUYỀN THỐNG","price"=>25000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>12,"name"=>"SINH TỐ BƠ","price"=>25000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>13,"name"=>"SINH TỐ DÂU","price"=>25000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>14,"name"=>"SINH TỐ MÃNG CẦU","price"=>22000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>15,"name"=>"SINH TỐ SA BÔ","price"=>22000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>16,"name"=>"TRÀ CỐC","price"=>25000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>17,"name"=>"TRÀ DÂU TẰM","price"=>25000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>18,"name"=>"TRÀ DÂU TẰM HẠT ĐÁC","price"=>28000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>19,"name"=>"TRÀ DỨA","price"=>25000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>20,"name"=>"TRÀ DỨA HẠT ĐÁC","price"=>28000,"img"=>"https://via.placeholder.com/150"],
    ];

    foreach ($items as $item): ?>
      <div class="col-6">
        <div class="card h-100 product-card">
          <img src="<?= $item['img'] ?>" class="card-img-top" alt="<?= $item['name'] ?>">
          <div class="card-body text-center">
            <h6 class="card-title"><?= $item['name'] ?></h6>
            <p class="text-muted">Size M: <?= number_format($item['price'],0,",",".") ?>đ<br>Size L: <?= number_format($item['price']+5000,0,",",".") ?>đ</p>

            <!-- Size chọn -->
            <div class="size-group mb-2">
              <input type="radio" class="btn-check" name="size<?= $item['id'] ?>" id="m<?= $item['id'] ?>" data-price="<?= $item['price'] ?>" autocomplete="off" checked>
              <label class="btn btn-outline-secondary btn-sm" for="m<?= $item['id'] ?>">M</label>
              <input type="radio" class="btn-check" name="size<?= $item['id'] ?>" id="l<?= $item['id'] ?>" data-price="<?= $item['price']+5000 ?>" autocomplete="off">
              <label class="btn btn-outline-secondary btn-sm" for="l<?= $item['id'] ?>">L</label>
            </div>

            <!-- Số lượng -->
            <div class="qty-box mb-2">
              <button class="btn btn-sm btn-outline-secondary btn-minus">-</button>
              <input type="number" class="form-control form-control-sm mx-1 qty-input" value="0" min="0">
              <button class="btn btn-sm btn-outline-secondary btn-plus">+</button>
            </div>

            <!-- Ghi chú -->
            <input type="text" class="form-control form-control-sm mb-1 note-input" placeholder="Ghi chú món...">
            <div class="d-flex flex-wrap justify-content-center mb-2">
              <div class="form-check me-2">
                <input class="form-check-input quick-note" type="checkbox" value="Ít ngọt">
                <label class="form-check-label">Ít ngọt</label>
              </div>
              <div class="form-check me-2">
                <input class="form-check-input quick-note" type="checkbox" value="Không đá">
                <label class="form-check-label">Không đá</label>
              </div>
              <div class="form-check">
                <input class="form-check-input quick-note" type="checkbox" value="Nhiều cafe">
                <label class="form-check-label">Nhiều cafe</label>
              </div>
            </div>

            <!-- Nút thêm -->
            <button class="btn btn-sm btn-outline-success w-100 btn-addcart"
                    data-id="<?= $item['id'] ?>"
                    data-name="<?= $item['name'] ?>">+ Thêm Món</button>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
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
let cart = {items: []};

// Tăng giảm
document.querySelectorAll('.btn-plus').forEach(btn=>{
  btn.addEventListener('click', ()=>{
    let input = btn.closest('.qty-box').querySelector('.qty-input');
    input.value = parseInt(input.value)+1;
  });
});
document.querySelectorAll('.btn-minus').forEach(btn=>{
  btn.addEventListener('click', ()=>{
    let input = btn.closest('.qty-box').querySelector('.qty-input');
    if(parseInt(input.value)>0) input.value = parseInt(input.value)-1;
  });
});

// Thêm giỏ
document.querySelectorAll('.btn-addcart').forEach(btn=>{
  btn.addEventListener('click', ()=>{
    let card = btn.closest('.card-body');
    let qty = parseInt(card.querySelector('.qty-input').value);
    if(qty<=0){alert("Chọn số lượng > 0");return;}
    let id = btn.dataset.id;
    let name = btn.dataset.name;
    let sizeRadio = card.querySelector('input[name="size'+id+'"]:checked');
    let size = sizeRadio.nextElementSibling.textContent;
    let price = parseInt(sizeRadio.dataset.price);
    let note = card.querySelector('.note-input').value;
    let quickNotes = [];
    card.querySelectorAll('.quick-note:checked').forEach(c=>quickNotes.push(c.value));
    if(quickNotes.length>0) note += (note? ', ':'')+quickNotes.join(', ');

    cart.items.push({id,name,qty,price,size,note});
    renderCart();
    card.querySelector('.qty-input').value=0;
    card.querySelector('.note-input').value='';
    card.querySelectorAll('.quick-note').forEach(c=>c.checked=false);
  });
});

// Render giỏ
function renderCart(){
  const cartItems=document.getElementById('cartItems');
  if(cart.items.length===0){cartItems.innerHTML='<p class="text-muted">Chưa có món nào</p>';}
  else{
    let total=0;
    cartItems.innerHTML=cart.items.map((item,i)=>{
      total+=item.price*item.qty;
      return `<div class="border-bottom py-2 d-flex justify-content-between align-items-start">
        <div>
          <strong>${item.name}</strong> (${item.size}) x${item.qty} - ${item.price*item.qty}đ
          <br><small>${item.note}</small>
        </div>
        <button class="btn btn-sm btn-outline-danger" onclick="removeItem(${i})">✕</button>
      </div>`;
    }).join('')+`<div class="mt-2 fw-bold">Tổng: ${total}đ</div>`;
  }
  document.getElementById('cartCount').textContent=cart.items.reduce((s,it)=>s+it.qty,0);
}
function removeItem(i){
  cart.items.splice(i,1);
  renderCart();
}

// Đặt hàng
document.getElementById('placeOrderBtn').addEventListener('click',()=>{
  if(cart.items.length===0){alert("Giỏ hàng trống!");return;}
  let customer=document.getElementById('customerName').value.trim();
  let phone=document.getElementById('customerPhone').value.trim();
  if(!customer||!phone){alert("Nhập tên và số điện thoại!");return;}
  console.log({customer,phone,note:document.getElementById('orderNote').value.trim(),items:cart.items});
  alert("Đặt hàng thành công!");
  cart={items:[]};renderCart();
});
</script>
</body>
</html>
