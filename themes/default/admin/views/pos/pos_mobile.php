<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>POS Mobile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .accordion-button { font-weight: bold; }
    .product-card { min-width: 150px; margin-right: 10px; }
    .product-card img { max-height: 100px; object-fit: cover; }
    .qty-box { display:flex; align-items:center; justify-content:center; }
    .qty-box input { width:50px; text-align:center; }
    .note-display { font-size:0.8rem; color:#555; min-height:18px; margin-top:5px; }
    .cart-badge { position:absolute; top:0; right:0; transform:translate(50%,-50%); }
    .size-box { font-size: 0.8rem; margin-bottom: 5px; }
  </style>
</head>
<body>

<!-- Header -->
<nav class="navbar navbar-dark bg-success">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">🍹 POS Mobile</a>
    <button class="btn btn-light position-relative" data-bs-toggle="offcanvas" data-bs-target="#cartCanvas">
      🛒 <span class="badge bg-danger cart-badge" id="cartCount">0</span>
    </button>
  </div>
</nav>

<div class="container my-3">
  <div class="accordion" id="menuAccordion">

    <!-- Trà Trái Cây -->
    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tra">Trà Trái Cây</button>
      </h2>
      <div id="tra" class="accordion-collapse collapse" data-bs-parent="#menuAccordion">
        <div class="accordion-body p-2">
          <div class="d-flex overflow-auto">
            <?php
              $drinks = [
                ['Trà Trái Cây',22000],
                ['Trà Dâu Tằm',22000],
                ['Trà Dứa',22000],
                ['Trà Dứa Hạt Đác',25000],
                ['Trà Dứa Thốt Nốt',25000],
              ];
              $id=1;
              foreach($drinks as $d){ echo card($id++,$d[0],$d[1]); }
            ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Cà Phê Phin -->
    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cf">Cà Phê Phin</button>
      </h2>
      <div id="cf" class="accordion-collapse collapse" data-bs-parent="#menuAccordion">
        <div class="accordion-body p-2">
          <div class="d-flex overflow-auto">
            <?php
              $drinks = [
                ['Cà Phê',20000],
                ['Cà Phê Sữa',25000],
                ['Cà Phê Hạnh Nhân',30000],
                ['Cà Phê Sữa Tươi Sương Sáo',28000],
              ];
              foreach($drinks as $d){ echo card($id++,$d[0],$d[1]); }
            ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Nước ép -->
    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#nuocep">Nước ép</button>
      </h2>
      <div id="nuocep" class="accordion-collapse collapse" data-bs-parent="#menuAccordion">
        <div class="accordion-body p-2">
          <div class="d-flex overflow-auto">
            <?php
              $drinks = [
                ['Khóm',20000],
                ['Táo',25000],
                ['Sơ Ri',25000],
                ['Cà Rốt',22000],
                ['Cam',25000],
              ];
              foreach($drinks as $d){ echo card($id++,$d[0],$d[1]); }
            ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Sinh Tố -->
    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sinhto">Sinh Tố</button>
      </h2>
      <div id="sinhto" class="accordion-collapse collapse" data-bs-parent="#menuAccordion">
        <div class="accordion-body p-2">
          <div class="d-flex overflow-auto">
            <?php
              $drinks = [
                ['Sinh Tố Bơ',30000],
                ['Sinh Tố Mãng Cầu',30000],
                ['Sinh Tố Sa Bô',28000],
                ['Sinh Tố Dâu',30000],
              ];
              foreach($drinks as $d){ echo card($id++,$d[0],$d[1]); }
            ?>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<?php
// function helper in view
function card($id,$name,$price){
  $img = "https://via.placeholder.com/140x100?text=".urlencode($name);
  return '
  <div class="card product-card">
    <img src="'.$img.'" class="card-img-top">
    <div class="card-body text-center p-2">
      <h6 class="small">'.$name.'</h6>
      <p class="text-muted mb-1">'.number_format($price,0,',','.').'đ (M)</p>
      <p class="text-muted mb-1">'.number_format($price+5000,0,',','.').'đ (L)</p>
      <div class="size-box">
        <label class="me-2"><input type="radio" name="size-'.$id.'" value="M" data-price="'.$price.'" checked> M</label>
        <label><input type="radio" name="size-'.$id.'" value="L" data-price="'.($price+5000).'"> L</label>
      </div>
      <div class="note-display" id="note-display-'.$id.'"></div>
      <div class="qty-box mb-1">
        <button class="btn btn-sm btn-outline-secondary btn-minus">-</button>
        <input type="number" class="form-control form-control-sm mx-1 qty-input" value="0" min="0">
        <button class="btn btn-sm btn-outline-secondary btn-plus">+</button>
      </div>
      <button class="btn btn-sm btn-info w-100 mb-1 btn-note"
              data-bs-toggle="modal" data-bs-target="#noteModal"
              data-id="'.$id.'" data-product="'.$name.'">Ghi chú</button>
      <button class="btn btn-sm btn-success w-100 btn-addcart"
              data-id="'.$id.'" data-name="'.$name.'">+ Giỏ</button>
    </div>
  </div>';
}
?>

<!-- Modal Ghi chú -->
<div class="modal fade" id="noteModal">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Ghi chú món</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
      <input type="hidden" id="currentProductId">
      <input type="text" class="form-control mb-2" id="nameInput" placeholder="Tên (A, B...)">
      <input type="text" class="form-control mb-2" id="noteInput" placeholder="Nhập ghi chú...">
      <div class="form-check"><input class="form-check-input note-check" type="checkbox" value="Ít ngọt" id="n1"><label class="form-check-label" for="n1">Ít ngọt</label></div>
      <div class="form-check"><input class="form-check-input note-check" type="checkbox" value="Không đá" id="n2"><label class="form-check-label" for="n2">Không đá</label></div>
      <div class="form-check"><input class="form-check-input note-check" type="checkbox" value="Nhiều cafe" id="n3"><label class="form-check-label" for="n3">Nhiều cafe</label></div>
    </div>
    <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button><button class="btn btn-success" id="saveNoteBtn" data-bs-dismiss="modal">Lưu</button></div>
  </div></div>
</div>

<!-- Offcanvas Giỏ hàng -->
<div class="offcanvas offcanvas-end" id="cartCanvas">
  <div class="offcanvas-header"><h5>🛒 Giỏ hàng</h5><button class="btn-close" data-bs-dismiss="offcanvas"></button></div>
  <div class="offcanvas-body d-flex flex-column">
    <div id="cartItems" class="mb-3"><p class="text-muted">Chưa có món</p></div>
    <div class="mb-3">
      <input class="form-control mb-2" id="customerName" placeholder="Tên khách">
      <input class="form-control mb-2" id="customerPhone" placeholder="SĐT">
      <textarea class="form-control" id="orderNote" rows="2" placeholder="Ghi chú đơn..."></textarea>
    </div>
    <button class="btn btn-success mt-auto" id="placeOrderBtn">Đặt hàng</button>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
let cart=[];

// qty +/- 
document.addEventListener('click',e=>{
  if(e.target.classList.contains('btn-plus')){
    let i=e.target.closest('.qty-box').querySelector('.qty-input');i.value=parseInt(i.value)+1;
  }
  if(e.target.classList.contains('btn-minus')){
    let i=e.target.closest('.qty-box').querySelector('.qty-input');if(i.value>0)i.value--;
  }
});

// modal note
const noteModal=document.getElementById('noteModal');
noteModal.addEventListener('show.bs.modal',e=>{
  const btn=e.relatedTarget;
  document.getElementById('currentProductId').value=btn.dataset.id;
  document.getElementById('nameInput').value='';
  document.getElementById('noteInput').value='';
  document.querySelectorAll('.note-check').forEach(c=>c.checked=false);
});
document.querySelectorAll('.note-check').forEach(c=>c.onchange=()=>{
  let arr=[];document.querySelectorAll('.note-check:checked').forEach(x=>arr.push(x.value));
  document.getElementById('noteInput').value=arr.join(', ');
});
document.getElementById('saveNoteBtn').onclick=()=>{
  let id=document.getElementById('currentProductId').value;
  let n=document.getElementById('nameInput').value.trim();
  let nt=document.getElementById('noteInput').value.trim();
  let txt=(n?'Người: '+n:'')+(nt?(n?' | ':'')+'Ghi chú: '+nt:'');
  document.getElementById('note-display-'+id).textContent=txt;
};

// add cart
document.addEventListener('click',e=>{
  if(e.target.classList.contains('btn-addcart')){
    let card=e.target.closest('.card-body');
    let qty=parseInt(card.querySelector('.qty-input').value);
    if(qty<=0){alert('Chọn số lượng >0');return;}
    let id=e.target.dataset.id,name=e.target.dataset.name;
    let sizeInput=card.querySelector('input[name="size-'+id+'"]:checked');
    let size=sizeInput.value;
    let price=parseInt(sizeInput.dataset.price);
    let note=document.getElementById('note-display-'+id).textContent;
    cart.push({id,name,qty,price,note,size});
    renderCart();
    card.querySelector('.qty-input').value=0;
    document.getElementById('note-display-'+id).textContent='';
  }
});

// render cart
function renderCart(){
  let el=document.getElementById('cartItems');
  if(cart.length==0){el.innerHTML='<p class="text-muted">Chưa có món</p>';}
  else{
    let total=0;
    el.innerHTML=cart.map((it,i)=>{
      total+=it.price*it.qty;
      return `<div class="border-bottom py-1 d-flex justify-content-between">
        <div><strong>${it.name} (${it.size})</strong> x${it.qty} - ${it.price*it.qty}đ<br><small>${it.note}</small></div>
        <button class="btn btn-sm btn-outline-danger" onclick="removeItem(${i})">✕</button>
      </div>`;
    }).join('')+`<div class="fw-bold mt-2">Tổng: ${total}đ</div>`;
  }
  document.getElementById('cartCount').textContent=cart.reduce((s,it)=>s+it.qty,0);
}
function removeItem(i){cart.splice(i,1);renderCart();}

// place order
document.getElementById('placeOrderBtn').onclick=()=>{
  if(cart.length==0){alert('Giỏ trống');return;}
  let c=document.getElementById('customerName').value.trim();
  let p=document.getElementById('customerPhone').value.trim();
  if(!c||!p){alert('Nhập Tên & SĐT');return;}
  let note=document.getElementById('orderNote').value.trim();
  console.log({customer:c,phone:p,orderNote:note,items:cart});
  alert('Đặt hàng thành công!');
  cart=[];renderCart();
  document.getElementById('customerName').value='';
  document.getElementById('customerPhone').value='';
  document.getElementById('orderNote').value='';
};
</script>
</body>
</html>
