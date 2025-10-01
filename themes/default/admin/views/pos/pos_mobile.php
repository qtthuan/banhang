<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>POS Mobile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .product-card { border-radius:10px; overflow:hidden; margin-bottom:15px; }
    .product-card img { max-height:120px; object-fit:cover; width:100%; }
    .qty-box { display:flex; align-items:center; justify-content:center; gap:8px; }
    .qty-box input { width:64px; text-align:center; font-size:1.1rem; }
    .qty-box button { width:40px; height:40px; font-size:1.2rem; }
    .note-display { font-size:0.85rem; color:#444; min-height:18px; margin-top:6px; }
    .cart-badge { position:absolute; top:0; right:0; transform:translate(50%,-50%); }
    .btn-group-size .btn { font-size:0.9rem; padding:8px 14px; } /* size button lớn hơn */
    .product-card .card-body { padding:10px; }
    .product-title { font-size:1rem; font-weight:600; margin-bottom:4px; }
  </style>
</head>
<body>

<!-- Header -->
<nav class="navbar navbar-dark bg-success">
  <div class="container-fluid">
    <a class="navbar-brand">🍹 POS Mobile</a>
    <button class="btn btn-light position-relative" data-bs-toggle="offcanvas" data-bs-target="#cartCanvas">
      🛒 <span class="badge bg-danger cart-badge" id="cartCount">0</span>
    </button>
  </div>
</nav>

<div class="container my-3">
  <div class="row">
  <?php
  function card($id, $name, $price, $img) {
    $m_price = (int)$price;
    $l_price = $m_price + 5000;
    return '
    <div class="col-6">
      <div class="card product-card">
        <img src="'.$img.'" alt="'.$name.'">
        <div class="card-body text-center p-2">
          <div class="product-title">'.$name.'</div>
          <div class="btn-group btn-group-size mb-2" role="group">
            <input type="radio" class="btn-check" name="size-'.$id.'" id="sizeM-'.$id.'" value="M" data-price="'.$m_price.'" checked>
            <label class="btn btn-outline-secondary" for="sizeM-'.$id.'">M '.number_format($m_price,0,',','.').'đ</label>
            <input type="radio" class="btn-check" name="size-'.$id.'" id="sizeL-'.$id.'" value="L" data-price="'.$l_price.'">
            <label class="btn btn-outline-success" for="sizeL-'.$id.'">L '.number_format($l_price,0,',','.').'đ</label>
          </div>
          <div class="note-display" id="note-display-'.$id.'"></div>
          <div class="qty-box mb-2">
            <button class="btn btn-outline-secondary btn-minus">-</button>
            <input type="number" class="form-control qty-input" value="0" min="0">
            <button class="btn btn-outline-secondary btn-plus">+</button>
          </div>
          <button class="btn btn-sm btn-info w-100 mb-1 btn-note" data-bs-toggle="modal" data-bs-target="#noteModal"
                  data-id="'.$id.'" data-product="'.$name.'">Ghi chú</button>
          <button class="btn btn-sm btn-success w-100 btn-addcart" data-id="'.$id.'" data-name="'.$name.'">+ Giỏ</button>
        </div>
      </div>
    </div>';
  }

  $id = 1;
  $items = [
    ['Trà Trái Cây',22000,'https://cdn.pixabay.com/photo/2017/05/23/22/36/tea-2345504_1280.jpg'],
    ['Trà Dâu Tằm',22000,'https://cdn.pixabay.com/photo/2017/01/20/15/06/raspberry-tea-1999582_1280.jpg'],
    ['Trà Dứa',22000,'https://cdn.pixabay.com/photo/2017/03/27/14/56/pineapple-2178786_1280.jpg'],
    ['Trà Dứa Hạt Đác',25000,'https://cdn.pixabay.com/photo/2016/03/05/19/02/pineapple-1239426_1280.jpg'],
    ['Trà Dứa Thốt Nốt',25000,'https://cdn.pixabay.com/photo/2017/06/10/07/18/iced-tea-2384772_1280.jpg'],
    ['Cà Phê',20000,'https://cdn.pixabay.com/photo/2016/11/29/03/14/beverage-1869598_1280.jpg'],
    ['Cà Phê Sữa',25000,'https://cdn.pixabay.com/photo/2017/06/30/18/49/coffee-2463278_1280.jpg'],
    ['Cà Phê Hạnh Nhân',30000,'https://cdn.pixabay.com/photo/2015/05/31/12/14/coffee-791045_1280.jpg'],
    ['Cà Phê Sữa Tươi Sương Sáo',28000,'https://cdn.pixabay.com/photo/2017/06/30/20/13/iced-coffee-2464529_1280.jpg'],
    ['Khóm',20000,'https://cdn.pixabay.com/photo/2015/03/26/09/54/pineapple-690582_1280.jpg'],
    ['Táo',25000,'https://cdn.pixabay.com/photo/2014/02/01/17/28/apple-256262_1280.jpg'],
    ['Sơ Ri',25000,'https://cdn.pixabay.com/photo/2017/04/04/20/56/cherry-2202305_1280.jpg'],
    ['Cà Rốt',22000,'https://cdn.pixabay.com/photo/2016/04/13/20/11/carrots-1327457_1280.jpg'],
    ['Cam',25000,'https://cdn.pixabay.com/photo/2017/01/20/15/06/oranges-1995056_1280.jpg'],
    ['Sinh Tố Bơ',30000,'https://cdn.pixabay.com/photo/2017/01/31/20/32/avocado-2026009_1280.jpg'],
    ['Sinh Tố Mãng Cầu',30000,'https://cdn.pixabay.com/photo/2019/09/12/12/18/soursop-4471235_1280.jpg'],
    ['Sinh Tố Sa Bô',28000,'https://cdn.pixabay.com/photo/2019/08/15/12/26/sapodilla-4407536_1280.jpg'],
    ['Sinh Tố Dâu',30000,'https://cdn.pixabay.com/photo/2017/01/12/14/34/strawberry-smoothie-1971552_1280.jpg'],
  ];
  foreach ($items as $it) { echo card($id++, $it[0], $it[1], $it[2]); }
  ?>
  </div>
</div>

<!-- Modal Ghi chú -->
<div class="modal fade" id="noteModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Ghi chú món</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <input type="hidden" id="currentProductId">
        <input type="text" class="form-control mb-2" id="nameInput" placeholder="Tên (A, B...)">
        <input type="text" class="form-control mb-2" id="noteInput" placeholder="Nhập ghi chú...">
        <div class="form-check"><input class="form-check-input note-check" type="checkbox" value="Ít ngọt" id="n1"><label class="form-check-label" for="n1"> Ít ngọt</label></div>
        <div class="form-check"><input class="form-check-input note-check" type="checkbox" value="Không đá" id="n2"><label class="form-check-label" for="n2"> Không đá</label></div>
        <div class="form-check"><input class="form-check-input note-check" type="checkbox" value="Nhiều cafe" id="n3"><label class="form-check-label" for="n3"> Nhiều cafe</label></div>
      </div>
      <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button><button class="btn btn-success" id="saveNoteBtn" data-bs-dismiss="modal">Lưu</button></div>
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
let cart = [];

// Tăng giảm số lượng
document.addEventListener('click', e=>{
  if(e.target.classList.contains('btn-plus')){
    let input=e.target.closest('.qty-box').querySelector('.qty-input');
    input.value=parseInt(input.value)+1;
  }
  if(e.target.classList.contains('btn-minus')){
    let input=e.target.closest('.qty-box').querySelector('.qty-input');
    if(parseInt(input.value)>0) input.value=parseInt(input.value)-1;
  }
});

// Modal ghi chú
document.getElementById('noteModal').addEventListener('show.bs.modal', ev=>{
  document.getElementById('currentProductId').value = ev.relatedTarget.dataset.id;
  document.getElementById('nameInput').value='';
  document.getElementById('noteInput').value='';
  document.querySelectorAll('.note-check').forEach(c=>c.checked=false);
});
document.querySelectorAll('.note-check').forEach(chk=>{
  chk.addEventListener('change', ()=>{
    let arr=[];document.querySelectorAll('.note-check:checked').forEach(c=>arr.push(c.value));
    document.getElementById('noteInput').value=arr.join(', ');
  });
});
document.getElementById('saveNoteBtn').addEventListener('click', ()=>{
  let id=document.getElementById('currentProductId').value;
  let name=document.getElementById('nameInput').value.trim();
  let note=document.getElementById('noteInput').value.trim();
  let txt=(name?'Người: '+name:'')+(note?(name?' | ':'')+'Ghi chú: '+note:'');
  document.getElementById('note-display-'+id).textContent=txt;
});

// Add to cart
document.addEventListener('click', e=>{
  if(!e.target.classList.contains('btn-addcart')) return;
  let btn=e.target;
  let card=btn.closest('.card-body');
  let qty=parseInt(card.querySelector('.qty-input').value);
  if(qty<=0){alert('Chọn số lượng >0');return;}
  let id=btn.dataset.id, name=btn.dataset.name;
  let sizeEl=card.querySelector('input[name="size-'+id+'"]:checked');
  let size=sizeEl.value, price=parseInt(sizeEl.dataset.price);
  let note=document.getElementById('note-display-'+id).textContent;
  cart.push({id,name,qty,price,size,note});
  renderCart();
  card.querySelector('.qty-input').value=0;
  document.getElementById('note-display-'+id).textContent='';
});

// Render cart
function renderCart(){
  let el=document.getElementById('cartItems');
  if(!cart.length){el.innerHTML='<p class="text-muted">Chưa có món</p>';}
  else{
    let total=0;
    el.innerHTML=cart.map((it,i)=>{
      total+=it.price*it.qty;
      return `<div class="border-bottom py-1 d-flex justify-content-between">
        <div><strong>${it.name} (${it.size})</strong> x${it.qty} - ${format(it.price*it.qty)}<br><small>${it.note}</small></div>
        <button class="btn btn-sm btn-outline-danger" onclick="removeItem(${i})">✕</button>
      </div>`;
    }).join('')+`<div class="fw-bold mt-2">Tổng: ${format(total)}</div>`;
  }
  document.getElementById('cartCount').textContent=cart.reduce((s,it)=>s+it.qty,0);
}
function removeItem(i){cart.splice(i,1);renderCart();}
function format(n){return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g,".")+'đ';}

// Place order
document.getElementById('placeOrderBtn').addEventListener('click',()=>{
  if(!cart.length){alert('Giỏ hàng trống!');return;}
  let name=document.getElementById('customerName').value.trim();
  let phone=document.getElementById('customerPhone').value.trim();
  if(!name||!phone){alert('Nhập tên và SĐT');return;}
  console.log({name,phone,note:document.getElementById('orderNote').value.trim(),items:cart});
  alert('Đặt hàng thành công!');
  cart=[];renderCart();
  document.getElementById('customerName').value='';
  document.getElementById('customerPhone').value='';
  document.getElementById('orderNote').value='';
  bootstrap.Offcanvas.getInstance(document.getElementById('cartCanvas')).hide();
});
</script>
</body>
</html>
