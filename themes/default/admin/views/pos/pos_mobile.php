<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TIỆM NƯỚC MINI</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f8f9fa; }
    .navbar-brand { font-weight:bold; }
    .product-card img { max-height:100px; width:auto; max-width:100%; margin-bottom:8px; object-fit:contain; }
    .product-card h6 { font-size:0.95rem; font-weight:bold; min-height:40px; }
    .note-display { font-size:0.8rem; color:#555; min-height:18px; margin-bottom:6px; }

    .size-options .btn { font-size:0.95rem; padding:10px 14px; }
    .btn-plus, .btn-minus { font-size:1.35rem; padding:10px 14px; }
    .qty-box input { width:65px; text-align:center; height:100%; font-size:1.05rem; }
    .qty-box { display:flex; justify-content:center; align-items:center; gap:6px; }

    .btn-note { width:40%; font-size:1rem; padding:12px; }
    .btn-addcart { width:60%; font-size:1rem; padding:12px; }

    @media (max-width:576px) {
      .col-6 { flex: 0 0 50%; max-width:50%; }
    }
  </style>
</head>
<body>

<nav class="navbar navbar-dark bg-success sticky-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">🥤 TIỆM NƯỚC MINI</a>
    <form class="d-flex me-2">
      <input class="form-control" id="searchInput" type="search" placeholder="Tìm món..." aria-label="Search">
    </form>
    <button class="btn btn-outline-light position-relative" type="button" data-bs-toggle="offcanvas" data-bs-target="#cartCanvas">
      🛒
      <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cartCount">0</span>
    </button>
  </div>
</nav>

<!-- FORM POST -->
<form id="pos-sale-form" method="post" action="<?= admin_url('pos'); ?>">
  <div id="posTable"></div>
</form>

<div class="container py-3">
  <div class="row g-2" id="productList">
    <?php foreach ($products as $p):
      $img = !empty($p->image) ? base_url('assets/uploads/thumbs/'.$p->image) : 'https://banicantho.com/assets/uploads/thumbs/no_image.png';
      $cleanName = preg_replace('/^[A-Z]_\s*/', '', strtoupper($p->name));
      $code = $p->code ?? '';
      $name_en = $p->name_en ?? '';
      $unit = $p->unit ?? '';
    ?>
    <div class="col-6 product-card" data-name="<?= htmlspecialchars($cleanName) ?>">
      <div class="card h-100">
        <div class="card-body text-center">
          <img src="<?= $img ?>" alt="<?= htmlspecialchars($p->name) ?>">
          <h6 class="card-title"><?= htmlspecialchars($cleanName) ?></h6>
          <p class="text-muted mb-1 base-price" data-base="<?= $p->price ?>"><?= number_format($p->price,0,',','.') ?>đ</p>

          <?php if (!empty($p->variants)): ?>
            <div class="btn-group size-options mb-2" role="group">
              <?php foreach ($p->variants as $i => $v): 
                // $v->price là phần extra (+5k) hoặc full price? Giả sử là extra
              ?>
                <input type="radio" class="btn-check size-radio"
                       name="size-<?= $p->id ?>"
                       id="size-<?= $p->id ?>-<?= $i ?>"
                       value="<?= $v->id ?>|<?= $v->price ?>|<?= htmlspecialchars($v->name) ?>"
                       <?= $i==0 ? 'checked' : '' ?>>
                <label class="btn btn-outline-primary" for="size-<?= $p->id ?>-<?= $i ?>"><?= htmlspecialchars($v->name) ?></label>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <div class="note-display" id="note-display-<?= $p->id ?>"></div>

          <div class="qty-box my-2">
            <button class="btn btn-outline-secondary btn-minus" type="button">-</button>
            <input type="number" class="form-control qty-input" value="0" min="0">
            <button class="btn btn-outline-secondary btn-plus" type="button">+</button>
          </div>

          <div class="d-flex gap-2">
            <button class="btn btn-info btn-note"
                    data-bs-toggle="modal"
                    data-bs-target="#noteModal"
                    data-id="<?= $p->id ?>">
              📝 Ghi chú
            </button>
            <button class="btn btn-success btn-addcart"
                    type="button"
                    data-id="<?= $p->id ?>"
                    data-code="<?= htmlspecialchars($code) ?>"
                    data-name="<?= htmlspecialchars($cleanName) ?>"
                    data-name-en="<?= htmlspecialchars($name_en) ?>"
                    data-price="<?= $p->price ?>"
                    data-image="<?= htmlspecialchars($p->image ?: 'no_image.png') ?>"
                    data-unit="<?= htmlspecialchars($unit) ?>">
              + Thêm Món
            </button>
          </div>

        </div>
      </div>
    </div>
    <?php endforeach; ?>
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
        <input type="hidden" id="currentProductId">
        <div class="mb-2">
          <label class="form-label">Tên (dán lên ly)</label>
          <input type="text" id="noteNameInput" class="form-control" placeholder="Tên (ví dụ: A, B...)">
        </div>
        <div class="mb-2">
          <label class="form-label">Ghi chú</label>
          <input type="text" id="noteTextInput" class="form-control" placeholder="Nhập ghi chú...">
        </div>
        <div class="d-flex flex-wrap gap-2">
          <div><input type="checkbox" class="form-check-input note-check" value="Ít ngọt"> <small>Ít ngọt</small></div>
          <div><input type="checkbox" class="form-check-input note-check" value="Không đá"> <small>Không đá</small></div>
          <div><input type="checkbox" class="form-check-input note-check" value="Nhiều cafe"> <small>Nhiều cafe</small></div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
        <button class="btn btn-success" id="saveNoteBtn">Lưu</button>
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
    <!-- Customer select -->
    <div class="mb-2">
      <label class="form-label">Khách hàng</label>
      <select id="customerSelect" class="form-select">
        <option value="">-- Chọn khách hàng --</option>
        <?php foreach ($customers as $c): ?>
          <option value="<?= $c->id ?>"><?= htmlspecialchars($c->name) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div id="cartItems" class="mb-3"><p class="text-muted">Chưa có món nào</p></div>

    <div class="mb-2">
      <input type="text" class="form-control mb-2" id="customer_name" placeholder="Tên khách">
      <input type="tel" class="form-control mb-2" id="customerPhone" placeholder="Số điện thoại">
      <textarea class="form-control" id="orderNote" rows="2" placeholder="Ghi chú đơn..."></textarea>
    </div>

    <button class="btn btn-success mt-auto" id="placeOrderBtn">Đặt hàng</button>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= $assets ?>pos/js/pos.mobile.js"></script>
<script>
// search filter realtime
document.getElementById('searchInput').addEventListener('input', function(){
  var q = (this.value || '').toLowerCase();
  document.querySelectorAll('#productList .product-card').forEach(function(card){
    var nm = (card.getAttribute('data-name') || '').toLowerCase();
    card.style.display = nm.indexOf(q) !== -1 ? 'block' : 'none';
  });
});

// place order
document.getElementById('placeOrderBtn').addEventListener('click', function(){
  var cname = document.getElementById('customer_name').value.trim();
  var phone = document.getElementById('customerPhone').value.trim();
  if (!cname || !phone) {
    alert('Vui lòng nhập Tên khách và Số điện thoại!');
    return;
  }
  mobileLoadItems();
  document.getElementById('pos-sale-form').submit();
});
</script>
</body>
</html>
