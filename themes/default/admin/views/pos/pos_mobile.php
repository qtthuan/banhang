<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>POS Mobile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .qty-box {
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .qty-box input {
      width: 50px;
      text-align: center;
      font-size: 1.2rem;
    }
    .qty-box button {
      width: 40px;
      height: 40px;
      font-size: 1.5rem;
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
    .size-group .btn {
      margin: 2px;
    }
    .size-group .btn-check:checked + .btn {
      background-color: #198754;
      color: #fff;
    }
    .product-card img {
      max-height: 100px;
      object-fit: cover;
    }
  </style>
</head>
<body class="bg-light">

<!-- Header -->
<nav class="navbar navbar-dark bg-success mb-3">
  <div class="container-fluid">
    <span class="navbar-brand mb-0 h1">🍹 POS Mobile</span>
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
      ["id"=>1,"name"=>"Cà phê","price"=>15000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>2,"name"=>"Cà phê hạnh nhân","price"=>25000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>3,"name"=>"Cà phê sữa","price"=>20000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>4,"name"=>"Cà phê sữa tươi hạt đác","price"=>28000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>5,"name"=>"Cà phê sữa tươi sương sáo","price"=>22000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>6,"name"=>"Cà phê sữa tươi thốt nốt","price"=>28000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>7,"name"=>"Milo sữa","price"=>22000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>8,"name"=>"Sữa chua trái cây","price"=>22000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>9,"name"=>"Tàu hũ matcha latte","price"=>25000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>10,"name"=>"Trà sữa gạo","price"=>25000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>11,"name"=>"Trà sữa truyền thống","price"=>25000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>12,"name"=>"Sinh tố bơ","price"=>25000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>13,"name"=>"Sinh tố dâu","price"=>25000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>14,"name"=>"Sinh tố mãng cầu","price"=>22000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>15,"name"=>"Sinh tố sa bô","price"=>22000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>16,"name"=>"Trà cốc","price"=>25000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>17,"name"=>"Trà dâu tằm","price"=>25000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>18,"name"=>"Trà dâu tằm hạt đác","price"=>28000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>19,"name"=>"Trà dứa","price"=>25000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>20,"name"=>"Trà dứa hạt đác","price"=>28000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>21,"name"=>"Trà dứa lưới","price"=>25000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>22,"name"=>"Trà dứa thốt nốt","price"=>28000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>23,"name"=>"Trà mãng cầu","price"=>25000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>24,"name"=>"Trà trái cây","price"=>25000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>25,"name"=>"Trà vải","price"=>22000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>26,"name"=>"Trà đào","price"=>22000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>27,"name"=>"Cà rốt","price"=>15000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>28,"name"=>"Cam","price"=>15000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>29,"name"=>"Cam-cà rốt","price"=>18000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>30,"name"=>"Dưa hấu","price"=>15000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>31,"name"=>"Khóm","price"=>18000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>32,"name"=>"Khóm-cà rốt","price"=>18000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>33,"name"=>"Khóm-cam","price"=>18000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>34,"name"=>"Khóm-dưa hấu","price"=>18000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>35,"name"=>"Khóm-ổi","price"=>18000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>36,"name"=>"Khóm-sơ ri","price"=>18000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>37,"name"=>"Mix 2 vị","price"=>18000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>38,"name"=>"Mix 2 vị (có táo)","price"=>22000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>39,"name"=>"Ổi","price"=>15000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>40,"name"=>"Sơ ri","price"=>15000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>41,"name"=>"Sơ ri-cà chua","price"=>18000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>42,"name"=>"Sơ ri-ổi","price"=>18000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>43,"name"=>"Táo","price"=>22000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>44,"name"=>"Táo-cà rốt","price"=>22000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>45,"name"=>"Táo-dưa hấu","price"=>22000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>46,"name"=>"Táo-khóm","price"=>22000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>47,"name"=>"Táo-ổi","price"=>22000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>48,"name"=>"Táo-sơ ri","price"=>22000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>49,"name"=>"Cà rốt (chai)","price"=>20000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>50,"name"=>"Cam (chai)","price"=>20000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>51,"name"=>"Dưa hấu (chai)","price"=>20000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>52,"name"=>"Khóm (chai)","price"=>23000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>53,"name"=>"Ổi (chai)","price"=>20000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>54,"name"=>"Sơ ri (chai)","price"=>20000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>55,"name"=>"Táo (chai)","price"=>27000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>56,"name"=>"Bạc xỉu","price"=>17000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>57,"name"=>"Trà đá","price"=>3000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>58,"name"=>"Trà đường","price"=>5000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>59,"name"=>"Yaourt đá","price"=>17000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>60,"name"=>"Yaourt đá hạt đác","price"=>25000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>61,"name"=>"Bánh tráng","price"=>13000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>62,"name"=>"Nước đá","price"=>1000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>63,"name"=>"Sữa chua chai","price"=>7000,"img"=>"https://via.placeholder.com/150"],
      ["id"=>64,"name"=>"Sữa chua chai mix trái cây","price"=>8000,"img"=>"https://via.placeholder.com/150"],
    ];

    foreach ($items as $item): ?>
      <div class="col-6">
        <div class="card h-100 product-card">
          <img src="<?= $item['img'] ?>" class="card-img-top" alt="<?= $item['name'] ?>">
          <div class="card-body text-center">
            <h6 class="card-title"><?= $item['name'] ?></h6>
            <p class="text-muted"><?= number_format($item['price'],0,",",".") ?>đ</p>

            <!-- Size chọn -->
            <div class="size-group mb-2">
              <input type="radio" class="btn-check" name="size<?= $item['id'] ?>" id="m<?= $item['id'] ?>" autocomplete="off" checked>
              <label class="btn btn-outline-secondary btn-sm" for="m<?= $item['id'] ?>">M</label>
              <input type="radio" class="btn-check" name="size<?= $item['id'] ?>" id="l<?= $item['id'] ?>" autocomplete="off">
              <label class="btn btn-outline-secondary btn-sm" for="l<?= $item['id'] ?>">L</label>
            </div>

            <!-- Số lượng -->
            <div class="qty-box mb-2">
              <button class="btn btn-sm btn-outline-secondary btn-minus">-</button>
              <input type="number" class="form-control form-control-sm mx-1 qty-input" value="0" min="0">
              <button class="btn btn-sm btn-outline-secondary btn-plus">+</button>
            </div>

            <!-- Nút thêm -->
            <button class="btn btn-sm btn-outline-success w-100 btn-addcart"
                    data-id="<?= $item['id'] ?>"
                    data-name="<?= $item['name'] ?>"
                    data-price="<?= $item['price'] ?>">+ Giỏ</button>
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
    let price = parseInt(btn.dataset.price);
    let size = card.querySelector('input[name="size'+id+'"]:checked').nextElementSibling.textContent;

    cart.items.push({id,name,qty,price,size});
    renderCart();
    card.querySelector('.qty-input').value=0;
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
        <div><strong>${item.name}</strong> (${item.size}) x${item.qty} - ${item.price*item.qty}đ</div>
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
