<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>TIỆM NƯỚC MINI</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f8f9fa; }
    .navbar { background:#28a745; }
    .navbar-brand { color:#fff !important; font-weight:bold; }
    .cart-btn { position:relative; }
    .cart-btn .badge { position:absolute; top:-5px; right:-10px; }

    .product-card { border:1px solid #ddd; border-radius:8px; padding:10px; text-align:center; background:#fff; }
    .product-card img { width:100px; height:100px; object-fit:cover; margin-bottom:8px; }
    .product-card h6 { font-size:14px; font-weight:bold; }
    .note-display { font-size:12px; color:#666; min-height:16px; margin-top:4px; }

    .size-btn input { display:none; }
    .size-btn label {
      margin:2px; padding:8px 12px;
      border:1px solid #28a745; border-radius:4px;
      cursor:pointer; font-size:13px;
    }
    .size-btn input:checked + label { background:#28a745; color:#fff; }

    .qty-control button {
      font-size:24px; width:44px; height:44px;
    }
    .qty-control span { font-size:18px; min-width:35px; display:inline-block; }

    .btn-add { height:50px; font-size:15px; }
  </style>
</head>
<body>

<!-- Header -->
<nav class="navbar">
  <div class="container-fluid">
    <span class="navbar-brand">🥤 TIỆM NƯỚC MINI</span>
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
    <div class="mb-2">
      <input type="text" id="customerName" class="form-control mb-2" placeholder="Tên khách hàng">
      <input type="text" id="customerPhone" class="form-control mb-2" placeholder="Số điện thoại">
      <textarea id="orderNote" class="form-control" placeholder="Ghi chú đơn hàng"></textarea>
    </div>
    <ul class="list-group mb-2" id="cartItems"></ul>
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
        <input type="hidden" id="noteIndex">
        <input type="text" id="noteName" class="form-control mb-2" placeholder="Tên khách (A, B...)">
        <input type="text" id="noteText" class="form-control mb-2" placeholder="Nhập ghi chú...">
        <div class="form-check"><input class="form-check-input note-check" type="checkbox" value="Ít ngọt"><label class="form-check-label">Ít ngọt</label></div>
        <div class="form-check"><input class="form-check-input note-check" type="checkbox" value="Không đá"><label class="form-check-label">Không đá</label></div>
        <div class="form-check"><input class="form-check-input note-check" type="checkbox" value="Nhiều cafe"><label class="form-check-label">Nhiều cafe</label></div>
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
const imgDefault = "https://banicantho.com/assets/uploads/thumbs/no_image.png";
const products = [
  {name:"CÀ PHÊ", price:15000}, {name:"CÀ PHÊ SỮA", price:20000}, {name:"TRÀ ĐÀO", price:22000}
  // 👉 thêm danh sách đầy đủ ở đây
];
let cart = [];
let notes = {};
let currentIndex = null;

// Render sản phẩm
function renderProducts(list) {
  const container = document.getElementById('productList');
  container.innerHTML = '';
  list.forEach((p, index) => {
    let noteText = notes[index] || '';
    container.innerHTML += `
      <div class="col-6">
        <div class="product-card">
          <img src="${imgDefault}" alt="${p.name}">
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
          <div class="d-flex mt-2">
            <button class="btn btn-info btn-sm flex-fill me-1" onclick="openNote(${index})">Ghi chú</button>
            <button class="btn btn-success btn-sm flex-fill btn-add" onclick="addToCart(${index})">+ Thêm Món</button>
          </div>
          <div class="note-display" id="note-display-${index}">${noteText}</div>
        </div>
      </div>
    `;
  });
}
renderProducts(products);

function changeQty(i, delta) {
  let el=document.getElementById('qty'+i);
  let qty=parseInt(el.innerText)+delta;
  if(qty<1) qty=1;
  el.innerText=qty;
}

// Modal ghi chú
function openNote(i){
  currentIndex=i;
  document.getElementById('noteIndex').value=i;
  document.getElementById('noteName').value='';
  document.getElementById('noteText').value='';
  document.querySelectorAll('.note-check').forEach(c=>c.checked=false);
  let modal=new bootstrap.Modal(document.getElementById('noteModal'));
  modal.show();
  setTimeout(()=>document.getElementById('noteName').focus(),300);
}

// Checkbox append vào textbox
document.querySelectorAll('.note-check').forEach(chk=>{
  chk.addEventListener('change',()=>{
    let txt=document.getElementById('noteText');
    let values = txt.value ? txt.value.split(',').map(v=>v.trim()) : [];
    if(chk.checked){
      if(!values.includes(chk.value)) values.push(chk.value);
    } else {
      values=values.filter(v=>v!==chk.value);
    }
    txt.value = values.join(', ');
    txt.focus();
    txt.setSelectionRange(txt.value.length, txt.value.length);
  });
});

// Enter để lưu
document.getElementById('noteText').addEventListener('keydown',e=>{
  if(e.key==="Enter"){
    e.preventDefault();
    document.getElementById('saveNoteBtn').click();
  }
});

// Lưu ghi chú
document.getElementById('saveNoteBtn').addEventListener('click',()=>{
  let i=document.getElementById('noteIndex').value;
  let name=document.getElementById('noteName').value.trim();
  let note=document.getElementById('noteText').value.trim();
  let display=(name?("Người: "+name+" | "):"")+(note?("Ghi chú: "+note):"");
  notes[i]=display;
  document.getElementById('note-display-'+i).innerText=display;
  bootstrap.Modal.getInstance(document.getElementById('noteModal')).hide();
});

// Giỏ hàng
function addToCart(i){
  let qty=parseInt(document.getElementById('qty'+i).innerText);
  let size=document.querySelector('input[name="size'+i+'"]:checked').value;
  let price=products[i].price+(size=="L"?5000:0);
  let note=notes[i]||'';
  cart.push({name:products[i].name, qty, size, price, note});
  updateCart();

  // reset sản phẩm
  document.getElementById('qty'+i).innerText=1;
  document.getElementById('m'+i).checked=true;
  notes[i]='';
  document.getElementById('note-display-'+i).innerText='';
}
function updateCart(){
  document.getElementById('cartCount').innerText=cart.length;
  let ul=document.getElementById('cartItems'); ul.innerHTML='';
  let total=0;
  cart.forEach((c,idx)=>{
    let sub=c.price*c.qty; total+=sub;
    ul.innerHTML+=`<li class="list-group-item d-flex justify-content-between">
      <div><strong>${c.name}</strong> (${c.size}) x${c.qty}<br><small>${c.note}</small></div>
      <div><span>${sub.toLocaleString()}đ</span>
      <br><button class="btn btn-sm btn-danger" onclick="removeItem(${idx})">X</button></div>
    </li>`;
  });
  document.getElementById('cartTotal').innerText=total.toLocaleString();
}
function removeItem(i){cart.splice(i,1);updateCart();}

// Search
document.getElementById('searchInput').addEventListener('input',e=>{
  let v=e.target.value.toLowerCase();
  renderProducts(products.filter(p=>p.name.toLowerCase().includes(v)));
});
</script>
</body>
</html>
