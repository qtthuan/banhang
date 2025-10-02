<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TIỆM NƯỚC MINI</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f8f9fa; }
    .navbar-brand { font-weight:bold; }
    .product-card img { max-height:100px; width:auto; margin-bottom:10px; }
    .product-card h6 { font-size:0.95rem; font-weight:bold; min-height:40px; }
    .qty-box { display:flex; justify-content:center; align-items:center; }
    .qty-box input { width:50px; text-align:center; }
    .btn-addcart, .btn-note {
      font-size:1rem;
      padding:10px;
      height:52px;
    }
    .btn-plus, .btn-minus { font-size:1.2rem; padding:6px 12px; }
    .size-options .btn { font-size:0.8rem; padding:4px 8px; }
    .note-display { font-size:0.75rem; color:#555; margin-bottom:4px; min-height:18px; }
  </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-success sticky-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">🥤 TIỆM NƯỚC MINI</a>
    <form class="d-flex">
      <input class="form-control me-2" id="searchInput" type="search" placeholder="Tìm món..." aria-label="Search">
    </form>
    <button class="btn btn-outline-light position-relative ms-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#cartCanvas">
      🛒
      <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cartCount">0</span>
    </button>
  </div>
</nav>

<!-- DANH SÁCH SẢN PHẨM -->
<div class="container py-3">
  <div class="row g-2" id="productList">
    <?php foreach ($products as $p): 
      $img = !empty($p->image) ? base_url('assets/uploads/thumbs/'.$p->image) : 'https://banicantho.com/assets/uploads/thumbs/no_image.png';
      $cleanName = preg_replace('/^[A-Z]_\s*/', '', strtoupper($p->name));
    ?>
    <div class="col-6 product-card" data-name="<?= $cleanName ?>">
      <div class="card h-100">
        <div class="card-body text-center">
          <img src="<?= $img ?>" alt="<?= $p->name ?>">
          <h6 class="card-title"><?= $cleanName ?></h6>
          <p class="text-muted mb-1"><?= number_format($p->price,0,',','.') ?>đ</p>

          <!-- SIZE -->
          <?php if (!empty($p->variants)): ?>
          <div class="btn-group size-options mb-2" role="group">
            <?php foreach ($p->variants as $i => $v): ?>
              <input type="radio" class="btn-check size-radio"
                     name="size-<?= $p->id ?>"
                     id="size-<?= $p->id ?>-<?= $i ?>"
                     value="<?= $v->name ?>|<?= $v->price ?>"
                     <?= $i==0?'checked':'' ?>>
              <label class="btn btn-outline-primary" for="size-<?= $p->id ?>-<?= $i ?>"><?= $v->name ?></label>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>

          <!-- GHI CHÚ -->
          <div class="note-display" id="note-display-<?= $p->id ?>"></div>

          <!-- QTY -->
          <div class="qty-box my-2">
            <button class="btn btn-sm btn-outline-secondary btn-minus">-</button>
            <input type="number" class="form-control form-control-sm mx-1 qty-input" value="0" min="0">
            <button class="btn btn-sm btn-outline-secondary btn-plus">+</button>
          </div>

          <!-- NÚT -->
          <div class="d-flex gap-2">
            <button class="btn btn-info flex-fill btn-note"
                    data-bs-toggle="modal"
                    data-bs-target="#noteModal"
                    data-id="<?= $p->id ?>">
              📝 Ghi chú
            </button>
            <button class="btn btn-success flex-fill btn-addcart"
                    data-id="<?= $p->id ?>"
                    data-name="<?= $cleanName ?>"
                    data-price="<?= $p->price ?>">
              + Thêm Món
            </button>
          </div>

        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- MODAL GHI CHÚ -->
<div class="modal fade" id="noteModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Ghi chú món</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="noteProductId">
        <div class="mb-2">
          <label class="form-label">Tên khách</label>
          <input type="text" class="form-control" id="noteCustomerName">
        </div>
        <div class="mb-2">
          <label class="form-label">Ghi chú</label>
          <textarea id="noteText" class="form-control" rows="2"></textarea>
        </div>
        <div class="d-flex flex-wrap gap-2">
          <div><input type="checkbox" class="form-check-input note-check" value="Ít ngọt"> Ít ngọt</div>
          <div><input type="checkbox" class="form-check-input note-check" value="Không đá"> Không đá</div>
          <div><input type="checkbox" class="form-check-input note-check" value="Nhiều cafe"> Nhiều cafe</div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
        <button class="btn btn-primary" id="saveNote">Lưu</button>
      </div>
    </div>
  </div>
</div>

<!-- OFFCANVAS GIỎ HÀNG -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="cartCanvas">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">Giỏ hàng</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <div id="cartItems"></div>
    <hr>
    <div class="mb-2">
      <label>Tên KH</label>
      <input type="text" class="form-control" id="orderName">
    </div>
    <div class="mb-2">
      <label>Điện thoại</label>
      <input type="text" class="form-control" id="orderPhone">
    </div>
    <div class="mb-2">
      <label>Ghi chú đơn</label>
      <textarea class="form-control" id="orderNote"></textarea>
    </div>
    <button class="btn btn-success w-100" id="placeOrder">Đặt hàng</button>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
let cart = JSON.parse(localStorage.getItem("cart")||"[]");

// search filter
document.getElementById("searchInput").addEventListener("keyup", function(){
  let val = this.value.toLowerCase();
  document.querySelectorAll("#productList .product-card").forEach(el=>{
    el.style.display = el.dataset.name.toLowerCase().includes(val) ? "block" : "none";
  });
});

// qty buttons
document.querySelectorAll(".btn-plus").forEach(btn=>{
  btn.addEventListener("click",function(){
    let input=this.parentElement.querySelector(".qty-input");
    input.value=parseInt(input.value)+1;
  });
});
document.querySelectorAll(".btn-minus").forEach(btn=>{
  btn.addEventListener("click",function(){
    let input=this.parentElement.querySelector(".qty-input");
    input.value=Math.max(0,parseInt(input.value)-1);
  });
});

// note modal
let currentNoteId=null;
document.querySelectorAll(".btn-note").forEach(btn=>{
  btn.addEventListener("click",function(){
    currentNoteId=this.dataset.id;
    document.getElementById("noteProductId").value=currentNoteId;
    document.getElementById("noteCustomerName").focus();
  });
});
document.querySelectorAll(".note-check").forEach(ch=>{
  ch.addEventListener("change",function(){
    let txt=document.getElementById("noteText");
    if(this.checked){
      txt.value += (txt.value?", ":"")+this.value;
    }
    txt.focus();
  });
});
document.getElementById("noteText").addEventListener("keypress",function(e){
  if(e.key==="Enter"){
    e.preventDefault();
    document.getElementById("saveNote").click();
  }
});
document.getElementById("saveNote").addEventListener("click",function(){
  let id=document.getElementById("noteProductId").value;
  let name=document.getElementById("noteCustomerName").value;
  let note=document.getElementById("noteText").value;
  document.getElementById("note-display-"+id).innerText = name?name+": "+note:note;
  bootstrap.Modal.getInstance(document.getElementById("noteModal")).hide();
});

// add cart
document.querySelectorAll(".btn-addcart").forEach(btn=>{
  btn.addEventListener("click",function(){
    let card=this.closest(".card-body");
    let qty=parseInt(card.querySelector(".qty-input").value);
    if(qty<=0) return;
    let id=this.dataset.id;
    let name=this.dataset.name;
    let basePrice=parseInt(this.dataset.price);
    let sizeOpt=card.querySelector(".size-radio:checked");
    let size=sizeOpt?sizeOpt.value.split("|")[0]:"";
    let price=sizeOpt?parseInt(sizeOpt.value.split("|")[1]):basePrice;
    let note=card.querySelector(".note-display").innerText;

    cart.push({id,name,qty,size,price,note});
    localStorage.setItem("cart",JSON.stringify(cart));
    updateCart();
    card.querySelector(".qty-input").value=0;
    card.querySelector(".note-display").innerText="";
  });
});

function updateCart(){
  document.getElementById("cartCount").innerText=cart.length;
  let html="";
  cart.forEach((item,i)=>{
    html+=`<div class="border p-2 mb-2">
      <div><b>${item.name}</b> x ${item.qty} (${item.size}) - ${item.price*item.qty}đ</div>
      <div><small>${item.note||""}</small></div>
      <button class="btn btn-sm btn-danger mt-1" onclick="removeItem(${i})">Xóa</button>
    </div>`;
  });
  document.getElementById("cartItems").innerHTML=html;
}
function removeItem(i){
  cart.splice(i,1);
  localStorage.setItem("cart",JSON.stringify(cart));
  updateCart();
}
updateCart();

document.getElementById("placeOrder").addEventListener("click",function(){
  alert("Đặt hàng thành công!");
  cart=[];
  localStorage.removeItem("cart");
  updateCart();
});
</script>
</body>
</html>
