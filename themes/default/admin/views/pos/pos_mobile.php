<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TIỆM NƯỚC MINI</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .product-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      padding: 10px;
      text-align: center;
      margin-bottom: 15px;
    }
    .product-card img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      margin-bottom: 8px;
    }
    .size-btn input { display:none; }
    .size-btn label {
      border:1px solid #ccc;
      padding:5px 10px;
      border-radius:6px;
      margin:0 3px;
      cursor:pointer;
    }
    .size-btn input:checked + label {
      background:#198754;
      color:#fff;
      border-color:#198754;
    }
    .qty-control button {
      width:40px;
      height:40px;
      font-size:20px;
    }
    .qty-control span {
      min-width:30px;
      display:inline-block;
      font-size:18px;
    }
    .note-display {
      font-size:0.85rem;
      color:#555;
      margin-top:5px;
      min-height:18px;
    }
    .cart-badge {
      position:absolute;
      top:-5px;
      right:-10px;
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
    <input type="text" id="searchInput" class="form-control" placeholder="Tìm sản phẩm...">
  </div>

  <!-- Danh sách sản phẩm -->
  <div class="row g-2" id="productList">
    <?php if (!empty($products)): ?>
      <?php foreach ($products as $i => $p): ?>
        <div class="col-6 product-col">
          <div class="product-card">
            <img src="<?= base_url('assets/uploads/thumbs/no_image.png') ?>" alt="<?= $p->name ?>">
            <h6><?= strtoupper($p->name) ?></h6>
            <p><?= number_format($p->price) ?>đ</p>

            <!-- Size -->
            <div class="size-btn mb-2">
              <input type="radio" name="size<?= $i ?>" id="m<?= $i ?>" value="M" checked>
              <label for="m<?= $i ?>">Size M</label>
              <input type="radio" name="size<?= $i ?>" id="l<?= $i ?>" value="L">
              <label for="l<?= $i ?>">Size L (+5k)</label>
            </div>

            <!-- Qty -->
            <div class="qty-control d-flex justify-content-center align-items-center mb-2">
              <button class="btn btn-outline-secondary" onclick="changeQty(<?= $i ?>,-1)">-</button>
              <span id="qty<?= $i ?>" class="mx-2">1</span>
              <button class="btn btn-outline-secondary" onclick="changeQty(<?= $i ?>,1)">+</button>
            </div>

            <!-- Buttons -->
            <div class="d-flex">
              <button class="btn btn-info btn-sm flex-fill me-1" onclick="openNote(<?= $i ?>,'<?= htmlspecialchars($p->name) ?>')">Ghi chú</button>
              <button class="btn btn-success btn-sm flex-fill" onclick="addToCart(<?= $i ?>,'<?= htmlspecialchars($p->name) ?>',<?= $p->price ?>)">+ Thêm Món</button>
            </div>
            <div class="note-display" id="note-display-<?= $i ?>"></div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
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
        <button type="button" class="btn btn-success" id="saveNoteBtn" data-bs-dismiss="modal">Lưu ghi chú</button>
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

    <button class="btn btn-success mt-auto" id="placeOrderBtn">Đặt hàng</button>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
let cart = JSON.parse(localStorage.getItem('cart')) || {items: []};
let notes = {};
renderCart();

// Thay đổi số lượng
function changeQty(i, delta){
  let el = document.getElementById('qty'+i);
  let val = parseInt(el.innerText) + delta;
  if(val<1) val=1;
  el.innerText = val;
}

// Ghi chú modal
let currentIndex=null;
function openNote(i, productName){
  currentIndex=i;
  document.getElementById('currentProductId').value=i;
  document.getElementById('nameInput').value='';
  document.getElementById('noteInput').value='';
  document.querySelectorAll('.note-check').forEach(c=>c.checked=false);
  new bootstrap.Modal(document.getElementById('noteModal')).show();
  setTimeout(()=>{ document.getElementById('nameInput').focus(); },500);
}

// Checkbox ghi chú -> append vào textbox
document.querySelectorAll('.note-check').forEach(chk=>{
  chk.addEventListener('change',()=>{
    let noteInput=document.getElementById('noteInput');
    let cursorPos = noteInput.value.length;
    let val = chk.value;
    if(chk.checked){
      noteInput.value += (noteInput.value ? ', ' : '') + val;
    } else {
      let arr = noteInput.value.split(',').map(s=>s.trim()).filter(s=>s && s!=val);
      noteInput.value=arr.join(', ');
    }
    noteInput.focus();
    noteInput.setSelectionRange(cursorPos+val.length+2,cursorPos+val.length+2);
  });
});

// Enter trong noteInput = lưu
document.getElementById('noteInput').addEventListener('keydown',(e)=>{
  if(e.key==='Enter'){
    e.preventDefault();
    document.getElementById('saveNoteBtn').click();
  }
});

// Lưu ghi chú
document.getElementById('saveNoteBtn').addEventListener('click',()=>{
  let i=currentIndex;
  let name=document.getElementById('nameInput').value.trim();
  let note=document.getElementById('noteInput').value.trim();
  let txt='';
  if(name) txt+='Người: '+name;
  if(note) txt+=(name?' | ':'')+'Ghi chú: '+note;
  document.getElementById('note-display-'+i).innerText=txt;
  notes[i]=txt;
});

// Thêm vào giỏ
function addToCart(i,name,basePrice){
  let qty=parseInt(document.getElementById('qty'+i).innerText);
  let size=document.querySelector('input[name="size'+i+'"]:checked').value;
  let price=basePrice+(size=="L"?5000:0);
  let note=notes[i]||'';
  cart.items.push({id:i,name,qty,price,size,note});
  localStorage.setItem('cart',JSON.stringify(cart));
  renderCart();

  // reset
  document.getElementById('qty'+i).innerText=1;
  document.getElementById('m'+i).checked=true;
  notes[i]='';
  document.getElementById('note-display-'+i).innerText='';
}

// Render giỏ
function renderCart(){
  const cartItems=document.getElementById('cartItems');
  if(cart.items.length===0){
    cartItems.innerHTML='<p class="text-muted">Chưa có món nào</p>';
  } else {
    let total=0;
    cartItems.innerHTML=cart.items.map((item,i)=>{
      total+=item.price*item.qty;
      return `<div class="border-bottom py-2 d-flex justify-content-between align-items-start">
        <div>
          <strong>${item.name}</strong> (${item.size}) x${item.qty} - ${item.price*item.qty}đ
          <br><small>${item.note}</small>
        </div>
        <button class="btn btn-sm btn-outline-danger btn-remove" data-index="${i}">✕</button>
      </div>`;
    }).join('')+`<div class="mt-2 fw-bold">Tổng: ${total}đ</div>`;
  }
  let totalQty=cart.items.reduce((sum,it)=>sum+it.qty,0);
  document.getElementById('cartCount').textContent=totalQty;

  document.querySelectorAll('.btn-remove').forEach(btn=>{
    btn.addEventListener('click',()=>{
      let idx=btn.dataset.index;
      cart.items.splice(idx,1);
      localStorage.setItem('cart',JSON.stringify(cart));
      renderCart();
    });
  });
}

// Đặt hàng
document.getElementById('placeOrderBtn').addEventListener('click',()=>{
  if(cart.items.length===0){alert("Giỏ hàng trống!");return;}
  let customer=document.getElementById('customerName').value.trim();
  let phone=document.getElementById('customerPhone').value.trim();
  let orderNote=document.getElementById('orderNote').value.trim();
  if(!customer||!phone){alert("Vui lòng nhập Tên khách và SĐT");return;}
  console.log({customer,phone,orderNote,items:cart.items});
  alert("Đặt hàng thành công! Cảm ơn "+customer);
  cart={items:[]};
  localStorage.setItem('cart',JSON.stringify(cart));
  renderCart();
  document.getElementById('customerName').value='';
  document.getElementById('customerPhone').value='';
  document.getElementById('orderNote').value='';
});

// Search filter
document.getElementById('searchInput').addEventListener('input',function(){
  let val=this.value.toLowerCase();
  document.querySelectorAll('#productList .product-col').forEach(col=>{
    let name=col.querySelector('h6').innerText.toLowerCase();
    col.style.display=name.includes(val)?'block':'none';
  });
});
</script>
</body>
</html>
