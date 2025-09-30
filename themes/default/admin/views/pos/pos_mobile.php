<?php
// pos_mobile.php - view (copy toàn bộ file này)
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>POS Mobile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .accordion-button { font-weight: 600; }
    .product-card { min-width: 160px; margin-right: 10px; border-radius: 10px; overflow: hidden; }
    .product-card img { max-height: 110px; object-fit: cover; width:100%; display:block; }
    .qty-box { display:flex; align-items:center; justify-content:center; gap:6px; }
    .qty-box input { width:56px; text-align:center; border-radius:6px; }
    .note-display { font-size:0.85rem; color:#444; min-height:18px; margin-top:6px; }
    .cart-badge { position:absolute; top:0; right:0; transform:translate(50%,-50%); }
    .btn-group-size .btn { font-size: 0.8rem; padding: 4px 8px; }
    .product-card .card-body { padding:10px; }
    .product-title { font-size:0.95rem; font-weight:600; margin-bottom:4px; }
  </style>
</head>
<body>

<!-- Header -->
<nav class="navbar navbar-dark bg-success">
  <div class="container-fluid">
    <a class="navbar-brand">🍹 POS Mobile</a>
    <button class="btn btn-light position-relative" data-bs-toggle="offcanvas" data-bs-target="#cartCanvas" aria-controls="cartCanvas">
      🛒 <span class="badge bg-danger cart-badge" id="cartCount">0</span>
    </button>
  </div>
</nav>

<div class="container my-3">
  <div class="accordion" id="menuAccordion">

    <?php
    // --- helper function để render 1 card ---
    function card($id, $name, $price, $img) {
      // data-price is attached to the radio inputs
      $m_price = (int)$price;
      $l_price = $m_price + 5000;
      $m_label = number_format($m_price,0,',','.') . 'đ';
      $l_label = number_format($l_price,0,',','.') . 'đ';
      $html = '
      <div class="card product-card me-2">
        <img src="'.htmlspecialchars($img).'" alt="'.htmlspecialchars($name).'">
        <div class="card-body text-center p-2">
          <div class="product-title">'.htmlspecialchars($name).'</div>
          <div class="mb-2">
            <div class="btn-group btn-group-size" role="group" aria-label="Size">
              <input type="radio" class="btn-check" name="size-'.$id.'" id="sizeM-'.$id.'" value="M" data-price="'.$m_price.'" checked>
              <label class="btn btn-outline-secondary btn-sm" for="sizeM-'.$id.'">M '.$m_label.'</label>

              <input type="radio" class="btn-check" name="size-'.$id.'" id="sizeL-'.$id.'" value="L" data-price="'.$l_price.'">
              <label class="btn btn-outline-success btn-sm" for="sizeL-'.$id.'">L '.$l_label.'</label>
            </div>
          </div>

          <div class="note-display" id="note-display-'.$id.'"></div>

          <div class="qty-box mb-2">
            <button class="btn btn-sm btn-outline-secondary btn-minus" type="button">-</button>
            <input type="number" class="form-control form-control-sm qty-input" value="0" min="0">
            <button class="btn btn-sm btn-outline-secondary btn-plus" type="button">+</button>
          </div>

          <button class="btn btn-sm btn-info w-100 mb-1 btn-note" data-bs-toggle="modal" data-bs-target="#noteModal"
                  data-id="'.$id.'" data-product="'.htmlspecialchars($name).'">Ghi chú</button>

          <button class="btn btn-sm btn-success w-100 btn-addcart" data-id="'.$id.'" data-name="'.htmlspecialchars($name).'">+ Giỏ</button>
        </div>
      </div>';
      return $html;
    }

    // --- dữ liệu menu (bạn có thể biến thành lấy từ DB sau) ---
    $id = 1;
    // Trà Trái Cây
    $tea = [
      ['Trà Trái Cây',22000,'https://cdn.pixabay.com/photo/2017/05/23/22/36/tea-2345504_1280.jpg'],
      ['Trà Dâu Tằm',22000,'https://cdn.pixabay.com/photo/2017/01/20/15/06/raspberry-tea-1999582_1280.jpg'],
      ['Trà Dứa',22000,'https://cdn.pixabay.com/photo/2017/03/27/14/56/pineapple-2178786_1280.jpg'],
      ['Trà Dứa Hạt Đác',25000,'https://cdn.pixabay.com/photo/2016/03/05/19/02/pineapple-1239426_1280.jpg'],
      ['Trà Dứa Thốt Nốt',25000,'https://cdn.pixabay.com/photo/2017/06/10/07/18/iced-tea-2384772_1280.jpg'],
    ];
    // Cà Phê Phin
    $coffee = [
      ['Cà Phê',20000,'https://cdn.pixabay.com/photo/2016/11/29/03/14/beverage-1869598_1280.jpg'],
      ['Cà Phê Sữa',25000,'https://cdn.pixabay.com/photo/2017/06/30/18/49/coffee-2463278_1280.jpg'],
      ['Cà Phê Hạnh Nhân',30000,'https://cdn.pixabay.com/photo/2015/05/31/12/14/coffee-791045_1280.jpg'],
      ['Cà Phê Sữa Tươi Sương Sáo',28000,'https://cdn.pixabay.com/photo/2017/06/30/20/13/iced-coffee-2464529_1280.jpg'],
    ];
    // Nước ép
    $juices = [
      ['Khóm',20000,'https://cdn.pixabay.com/photo/2015/03/26/09/54/pineapple-690582_1280.jpg'],
      ['Táo',25000,'https://cdn.pixabay.com/photo/2014/02/01/17/28/apple-256262_1280.jpg'],
      ['Sơ Ri',25000,'https://cdn.pixabay.com/photo/2017/04/04/20/56/cherry-2202305_1280.jpg'],
      ['Cà Rốt',22000,'https://cdn.pixabay.com/photo/2016/04/13/20/11/carrots-1327457_1280.jpg'],
      ['Cam',25000,'https://cdn.pixabay.com/photo/2017/01/20/15/06/oranges-1995056_1280.jpg'],
    ];
    // Sinh tố
    $smoothies = [
      ['Sinh Tố Bơ',30000,'https://cdn.pixabay.com/photo/2017/01/31/20/32/avocado-2026009_1280.jpg'],
      ['Sinh Tố Mãng Cầu',30000,'https://cdn.pixabay.com/photo/2019/09/12/12/18/soursop-4471235_1280.jpg'],
      ['Sinh Tố Sa Bô',28000,'https://cdn.pixabay.com/photo/2019/08/15/12/26/sapodilla-4407536_1280.jpg'],
      ['Sinh Tố Dâu',30000,'https://cdn.pixabay.com/photo/2017/01/12/14/34/strawberry-smoothie-1971552_1280.jpg'],
    ];
    ?>

    <!-- Accordion Trà -->
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingTra">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTra" aria-expanded="false" aria-controls="collapseTra">Trà Trái Cây</button>
      </h2>
      <div id="collapseTra" class="accordion-collapse collapse" data-bs-parent="#menuAccordion">
        <div class="accordion-body p-2">
          <div class="d-flex overflow-auto">
            <?php foreach($tea as $d) { echo card($id++, $d[0], $d[1], $d[2]); } ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Accordion Cà phê -->
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingCf">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCf" aria-expanded="false" aria-controls="collapseCf">Cà Phê Phin</button>
      </h2>
      <div id="collapseCf" class="accordion-collapse collapse" data-bs-parent="#menuAccordion">
        <div class="accordion-body p-2">
          <div class="d-flex overflow-auto">
            <?php foreach($coffee as $d) { echo card($id++, $d[0], $d[1], $d[2]); } ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Accordion Nước ép -->
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingN">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseN" aria-expanded="false" aria-controls="collapseN">Nước ép</button>
      </h2>
      <div id="collapseN" class="accordion-collapse collapse" data-bs-parent="#menuAccordion">
        <div class="accordion-body p-2">
          <div class="d-flex overflow-auto">
            <?php foreach($juices as $d) { echo card($id++, $d[0], $d[1], $d[2]); } ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Accordion Sinh tố -->
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingS">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseS" aria-expanded="false" aria-controls="collapseS">Sinh Tố</button>
      </h2>
      <div id="collapseS" class="accordion-collapse collapse" data-bs-parent="#menuAccordion">
        <div class="accordion-body p-2">
          <div class="d-flex overflow-auto">
            <?php foreach($smoothies as $d) { echo card($id++, $d[0], $d[1], $d[2]); } ?>
          </div>
        </div>
      </div>
    </div>

  </div> <!-- end accordion -->
</div> <!-- end container -->

<!-- Modal Ghi chú -->
<div class="modal fade" id="noteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Ghi chú món</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button></div>
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
<div class="offcanvas offcanvas-end" tabindex="-1" id="cartCanvas" aria-labelledby="cartCanvasLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="cartCanvasLabel">🛒 Giỏ hàng</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Đóng"></button>
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
/* pos_mobile.js (embedded) */
let cart = [];

// Tăng/giảm số lượng (delegation)
document.addEventListener('click', function(e){
  if (e.target.matches('.btn-plus')) {
    const input = e.target.closest('.qty-box').querySelector('.qty-input');
    input.value = parseInt(input.value) + 1;
  } else if (e.target.matches('.btn-minus')) {
    const input = e.target.closest('.qty-box').querySelector('.qty-input');
    if (parseInt(input.value) > 0) input.value = parseInt(input.value) - 1;
  }
});

// Modal ghi chú - show
const noteModalEl = document.getElementById('noteModal');
noteModalEl.addEventListener('show.bs.modal', function(event){
  const btn = event.relatedTarget;
  const id = btn.dataset.id;
  document.getElementById('currentProductId').value = id;
  document.getElementById('nameInput').value = '';
  document.getElementById('noteInput').value = '';
  document.querySelectorAll('.note-check').forEach(c => c.checked = false);
});

// checkbox -> update noteInput
document.querySelectorAll('.note-check').forEach(chk => {
  chk.addEventListener('change', () => {
    const arr = [];
    document.querySelectorAll('.note-check:checked').forEach(x => arr.push(x.value));
    document.getElementById('noteInput').value = arr.join(', ');
  });
});

// save note
document.getElementById('saveNoteBtn').addEventListener('click', () => {
  const id = document.getElementById('currentProductId').value;
  const name = document.getElementById('nameInput').value.trim();
  const note = document.getElementById('noteInput').value.trim();
  const text = (name ? 'Người: '+name : '') + (note ? (name ? ' | ' : '') + 'Ghi chú: ' + note : '');
  const el = document.getElementById('note-display-'+id);
  if (el) el.textContent = text;
});

// Add to cart (delegation)
document.addEventListener('click', function(e){
  if (!e.target.matches('.btn-addcart')) return;
  const btn = e.target;
  const cardBody = btn.closest('.card-body');
  const qtyInput = cardBody.querySelector('.qty-input');
  const qty = parseInt(qtyInput.value);
  if (!qty || qty <= 0) { alert('Vui lòng chọn số lượng > 0'); return; }

  const id = btn.dataset.id;
  const name = btn.dataset.name;

  const sizeInput = cardBody.querySelector('input[name="size-'+id+'"]:checked');
  const size = sizeInput ? sizeInput.value : 'M';
  const price = sizeInput ? parseInt(sizeInput.dataset.price) : 0;

  const note = document.getElementById('note-display-'+id).textContent || '';

  cart.push({id, name, size, price, qty, note});
  renderCart();

  // reset card qty + note
  qtyInput.value = 0;
  const noteEl = document.getElementById('note-display-'+id);
  if (noteEl) noteEl.textContent = '';
});

// renderCart
function renderCart(){
  const el = document.getElementById('cartItems');
  if (!cart.length) {
    el.innerHTML = '<p class="text-muted">Chưa có món</p>';
  } else {
    let total = 0;
    const rows = cart.map((it, i) => {
      total += it.price * it.qty;
      return `<div class="border-bottom py-1 d-flex justify-content-between align-items-start">
        <div>
          <strong>${it.name} (${it.size})</strong> x${it.qty} - ${formatCurrency(it.price*it.qty)}<br>
          <small>${escapeHtml(it.note)}</small>
        </div>
        <button class="btn btn-sm btn-outline-danger" onclick="removeItem(${i})">✕</button>
      </div>`;
    }).join('');
    el.innerHTML = rows + `<div class="fw-bold mt-2">Tổng: ${formatCurrency(total)}</div>`;
  }
  document.getElementById('cartCount').textContent = cart.reduce((s,it)=>s+it.qty,0);
}

function removeItem(index) {
  cart.splice(index,1);
  renderCart();
}

function formatCurrency(n){
  return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + 'đ';
}
function escapeHtml(s){ return s ? s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;') : ''; }

// Place order (demo)
document.getElementById('placeOrderBtn').addEventListener('click', function(){
  if (!cart.length) { alert('Giỏ hàng trống!'); return; }
  const customer = document.getElementById('customerName').value.trim();
  const phone = document.getElementById('customerPhone').value.trim();
  const orderNote = document.getElementById('orderNote').value.trim();
  if (!customer || !phone) { alert('Vui lòng nhập Tên và SĐT'); return; }

  // Demo: gửi data lên console (bạn thay bằng AJAX POST vào controller Pos)
  console.log({ customer, phone, orderNote, items: cart });

  alert('Đặt hàng thành công!');

  // reset
  cart = [];
  renderCart();
  document.getElementById('customerName').value = '';
  document.getElementById('customerPhone').value = '';
  document.getElementById('orderNote').value = '';
  // đóng offcanvas nếu muốn
  var off = bootstrap.Offcanvas.getInstance(document.getElementById('cartCanvas'));
  if (off) off.hide();
});
</script>
</body>
</html>
