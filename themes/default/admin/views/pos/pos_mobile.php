<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>POS Mobile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet"/>
  <style>
    body { background:#f8f9fa; }
    .navbar-brand { font-weight:bold; }
    .product-img {max-height:100px; width:auto; object-fit:contain;}
    .qty-box {display:flex; align-items:center; justify-content:center;}
    .qty-input {text-align:center; width:50px; height:40px; margin:0 5px;}
    .btn-plus, .btn-minus {font-size:1.3rem; width:40px; height:40px;}
    .btn-addcart {width:60%;}
    .btn-note {width:40%;}
    .note-display {font-size:0.85rem; color:#555;}
    .size-group .btn {margin:2px; min-width:60px; font-size:1rem;}
    .select2-container .select2-selection--single {height:45px !important;}
    .select2-selection__rendered {line-height:45px !important;}
    .select2-selection__arrow {height:45px !important;}
  </style>
</head>
<body>

<!-- Header -->
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

<div class="container py-2">

  <!-- Product Grid -->
  <div class="row" id="productGrid">
    <?php foreach ($products as $p): 
      $displayName = preg_replace('/^[A-Z]_\s*/', '', strtoupper($p->name));
      ?>
      <div class="col-6 mb-3 product-card" data-name="<?= htmlspecialchars($displayName); ?>">
        <div class="card h-100">
          <img src="<?= base_url('assets/uploads/' . $p->image); ?>" class="card-img-top product-img" alt="">
          <div class="card-body d-flex flex-column">
            <h6 class="card-title text-uppercase"><?= $displayName; ?></h6>
            <p class="text-muted mb-1 base-price" data-base="<?= $p->price; ?>"><?= number_format($p->price,0,',','.'); ?>đ</p>

            <!-- Size options -->
            <?php if (!empty($p->variants)): ?>
            <div class="btn-group size-group mb-2" role="group">
              <?php foreach ($p->variants as $i => $v): ?>
                <input type="radio" class="btn-check size-radio" 
                  name="size-<?= $p->id; ?>" 
                  id="size<?= $p->id . '-' . $i; ?>" 
                  value="<?= $v->id . '|' . $v->price . '|' . $v->name; ?>" <?= $i==0?'checked':''; ?>>
                <label class="btn btn-outline-primary" for="size<?= $p->id . '-' . $i; ?>"><?= $v->name; ?></label>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Qty box -->
            <div class="qty-box my-2">
              <button type="button" class="btn btn-outline-secondary btn-minus">-</button>
              <input type="text" class="form-control qty-input" value="0">
              <button type="button" class="btn btn-outline-secondary btn-plus">+</button>
            </div>

            <!-- Note + Add buttons -->
            <div class="d-flex mb-1">
              <button class="btn btn-warning btn-note me-1" data-bs-toggle="modal" data-bs-target="#noteModal" data-id="<?= $p->id; ?>">Ghi chú</button>
              <button class="btn btn-success btn-addcart" 
                data-id="<?= $p->id; ?>"
                data-code="<?= $p->code; ?>"
                data-name="<?= $displayName; ?>"
                data-name-en="<?= $p->name_en; ?>"
                data-price="<?= $p->price; ?>"
                data-image="<?= $p->image; ?>"
                data-unit="<?= $p->unit; ?>"
              >+ Thêm món</button>
            </div>
            <div id="note-display-<?= $p->id; ?>" class="note-display"></div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Cart Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="cartCanvas">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">🛒 Giỏ hàng</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <form id="pos-sale-form" method="post" action="<?= admin_url('pos'); ?>">
      <div id="cartItems"></div>
      <hr>
      <div class="mb-2">
        <label class="form-label">Khách hàng</label>
        <select id="customerSelect" class="form-control" name="customer"></select>
      </div>
      <div class="mb-2">
        <label class="form-label">Tên khách</label>
        <input type="text" id="customer_name" name="customer_name" class="form-control">
      </div>
      <div class="mb-2">
        <textarea id="pos_note" name="pos_note" class="form-control" placeholder="Ghi chú đơn..."></textarea>
      </div>
      <div id="posTable"></div>
      <button type="submit" class="btn btn-success w-100">Đặt hàng</button>
    </form>
  </div>
</div>

<!-- Note Modal -->
<div class="modal fade" id="noteModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Ghi chú món</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="currentProductId">
        <div class="mb-2">
          <label>Tên người ghi chú</label>
          <input type="text" id="noteNameInput" class="form-control">
        </div>
        <div class="mb-2">
          <label>Chi tiết</label>
          <textarea id="noteTextInput" class="form-control"></textarea>
        </div>
        <div class="mb-2">
          <label>Chọn nhanh:</label><br>
          <label><input type="checkbox" class="note-check" value="Ít đá"> Ít đá</label>
          <label><input type="checkbox" class="note-check" value="Nhiều đá"> Nhiều đá</label>
          <label><input type="checkbox" class="note-check" value="Ít đường"> Ít đường</label>
          <label><input type="checkbox" class="note-check" value="Nhiều đường"> Nhiều đường</label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="saveNoteBtn" class="btn btn-primary" data-bs-dismiss="modal">Lưu</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
<script src="<?= $assets ?>pos/js/pos.mobile.js?v=2.7"></script>
<script>
  // realtime filter
  $('#searchInput').on('keyup', function(){
    var val = this.value.toLowerCase();
    $('#productGrid .product-card').each(function(){
      var name = $(this).data('name').toLowerCase();
      $(this).toggle(name.indexOf(val) !== -1);
    });
  });

  // init select2 for customer
  $('#customerSelect').select2({
    placeholder: 'Chọn khách hàng',
    minimumInputLength: 1,
    ajax: {
      url: "<?= admin_url('customers/suggestions'); ?>",
      dataType: 'json',
      delay: 200,
      data: function (params) { return { term: params.term, limit: 10 }; },
      processResults: function (data) { return { results: data.results }; }
    }
  }).on('select2:open', function(){
    document.querySelector('.select2-search__field').focus();
  });

  // update price when size selected
  $(document).on('change', '.size-radio', function(){
    var card = $(this).closest('.card-body');
    var base = parseFloat(card.find('.base-price').data('base'));
    var parts = $(this).val().split('|');
    var extra = parseFloat(parts[1] || 0);
    var price = base + extra;
    card.find('.base-price').text(price.toLocaleString('vi-VN') + 'đ');
    card.find('.btn-addcart').attr('data-price', price);
  });
</script>
</body>
</html>
