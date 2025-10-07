<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>TIỆM NƯỚC MINI</title>

  <!-- CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <style>
    body { background:#f8f9fa; }
    .navbar-brand { font-weight:bold; }
    .product-img { max-height:100px; width:auto; max-width:100%; object-fit:contain; margin:auto; display:block; }
    .card-body { padding: .9rem; }
    .card-title { font-size:0.95rem; font-weight:600; min-height:40px; margin-top: 5px; }
    .note-display { font-size:0.85rem; color:#444; min-height:18px; margin-bottom:6px; }

    .size-options .btn { font-size:0.95rem; padding:8px 10px; }
    .btn-plus, .btn-minus { font-size:1.25rem; padding:8px 10px; width:40px; height:40px; }
    .qty-box input.qty-input { width:66px; text-align:center; height:40px; }
    .qty-box { display:flex; justify-content:center; align-items:center; gap:6px; }

    .btn-note { width:40%; font-size:1rem; padding:10px; }
    .btn-addcart { width:60%; font-size:1rem; padding:10px; }

    /* 2-column layout */
    .col-6 { flex: 0 0 50%; max-width:50%; }

    /* select2 sizing */
    .select2-container--default .select2-selection--single { height:44px; }
    .select2-selection__rendered { line-height:40px; }
    .select2-search__field { min-height:40px !important; }
    .note-check {
        transform: scale(1.5); /* tăng 20% ~ 7% theo yêu cầu */
        margin-right: 6px;
    }
    /* Ẩn nút x mặc định trong input search (iOS & Safari) */
    input[type="search"]::-webkit-search-cancel-button {
      -webkit-appearance: none;
      appearance: none;
    }


    


    .customer-select-wrapper {
      display: flex;
      gap: 6px;
      align-items: center;
    }
    .customer-select-wrapper .form-control {
      height: 38px;
      font-size: 15px;
    }

    .select2-container {
      width: 100% !important;
      z-index: 2000 !important;
    }
    .select2-dropdown {
      z-index: 2100 !important;
    }
    

    #customerRow {
      gap: 8px;
    }
    @media (min-width: 768px) { /* iPad trở lên */
      #customerRow {
        justify-content: space-between;
      }
      #customerRow select,
      #customerRow input {
        max-width: 48%;
      }
    }




    @media (max-width:576px) {
      .col-6 { flex: 0 0 50%; max-width:50%; }
    }
  </style>
</head>
<body>

<!-- Header -->
<nav class="navbar navbar-dark bg-success sticky-top">
   

  <div class="container-fluid">
    <a class="navbar-brand" href="#">🥤 TIỆM NƯỚC MINI</a>
    <form class="d-flex me-2" onsubmit="return false;">
      <div class="position-relative w-100 me-2">
        <input class="form-control pe-5" id="searchInput" type="search" placeholder="Tìm món..." aria-label="Search">
        <button type="button" id="clearSearchBtn" class="btn position-absolute end-0 top-0 bottom-0 me-1 px-2 text-muted" style="border:none; background:transparent;">✕</button>
    </div>

    </form>
    <button class="btn btn-outline-light position-relative" type="button" data-bs-toggle="offcanvas" data-bs-target="#cartCanvas">
      🛒
      <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cartCount">0</span>
    </button>
  </div>  

  <div class="customer-select-wrapper mb-2 d-flex align-items-center">
    <div class="flex-fill me-2 position-relative">
      <!-- Input giả chỉ để kích bàn phím iOS -->
      <input id="iosTriggerInput" 
            type="text" 
            placeholder="-- Chọn khách hàng --" 
            class="form-control" 
            autocomplete="off"
            style="position:absolute;opacity:0;pointer-events:none;">
      <!-- Select thật dùng cho Select2 -->
      <select id="customerSelect" style="width:100%;"></select>
    </div>

    <div style="width:45%;">
      <input id="customer_name" 
            type="text" 
            placeholder="Tên khách" 
            class="form-control">
    </div>
  </div>



 
</nav>

<div class="container py-3">
  <!-- The form: CSRF token placed here (so mobileLoadItems won't erase it) -->
  <form id="pos-sale-form" method="post" action="<?= admin_url('pos'); ?>">
    <!-- CSRF token (CodeIgniter) -->
    <?php if (isset($this->security)): ?>
      <input type="hidden" id="csrf_token_input" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
    <?php endif; ?>

    <!-- posTable: JS will append hidden inputs here before submit -->
    <div id="posTable"></div>
  </form>

  <!-- Product grid -->
  <div class="row g-2" id="productList">
    <?php foreach ($products as $p):
      $img = !empty($p->image) ? base_url('assets/uploads/thumbs/'.$p->image) : 'https://banicantho.com/assets/uploads/thumbs/no_image.png';
      $cleanName = preg_replace('/^[A-Z]_\s*/', '', strtoupper($p->name));
      $code = isset($p->code) ? $p->code : '';
      $name_en = isset($p->name_en) ? $p->name_en : '';
      $unit = isset($p->unit) ? $p->unit : '';
    ?>
    <div class="col-6 product-card" data-name="<?= htmlspecialchars($cleanName) ?>">
      <div class="card h-100">
        <div class="card-body text-center">
          <img src="<?= $img ?>" alt="<?= htmlspecialchars($p->name) ?>" class="product-img">
          <h6 class="card-title text-uppercase"><?= htmlspecialchars($cleanName) ?></h6>

          <!-- Base price element stores base in data-base -->
          <p class="text-muted mb-1 base-price" data-base="<?= (float)$p->price ?>"><?= number_format($p->price,0,',','.') ?>đ</p>

          <!-- Size radios: value = variant_id|variant_price|variant_name -->
          <?php if (!empty($p->variants)): ?>
            <div class="btn-group size-options mb-2" role="group">
              <?php foreach ($p->variants as $i => $v): ?>
                <input type="radio" class="btn-check size-radio" name="size-<?= $p->id ?>" id="size-<?= $p->id.'-'.$i ?>" value="<?= $v->id.'|'.(float)$v->price.'|'.htmlspecialchars($v->name) ?>" <?= $i==0 ? 'checked' : '' ?>>
                <label class="btn btn-outline-primary" for="size-<?= $p->id.'-'.$i ?>"><?= htmlspecialchars($v->name) ?></label>
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
            <button class="btn btn-warning btn-note" data-bs-toggle="modal" data-bs-target="#noteModal" data-id="<?= $p->id ?>">Ghi chú</button>
            <button class="btn btn-success btn-addcart"
              type="button"
              data-id="<?= $p->id ?>"
              data-code="<?= htmlspecialchars($code) ?>"
              data-name="<?= htmlspecialchars($cleanName) ?>"
              data-name-en="<?= htmlspecialchars($name_en) ?>"
              data-price="<?= (float)$p->price ?>"
              data-image="<?= htmlspecialchars($p->image ?: 'no_image.png') ?>"
              data-unit="<?= htmlspecialchars($unit) ?>"
            >+ Thêm Món</button>
          </div>

        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Note modal -->
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
          <label class="form-label">Tên (dán lên ly)</label>
          <input type="text" id="noteNameInput" class="form-control" placeholder="Tên (ví dụ: A, B...)">
        </div>
        <div class="mb-2">
          <label class="form-label">Ghi chú</label>
          <input type="text" id="noteTextInput" class="form-control" placeholder="Nhập ghi chú...">
        </div>
        <div class="d-flex flex-wrap gap-2">
            <div class="form-check form-check-inline">
                <input class="form-check-input note-check" type="checkbox" id="chk-1" value="Ít ngọt">
                <label class="form-check-label" for="chk-1">Ít ngọt</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input note-check" type="checkbox" id="chk-2" value="Không đá">
                <label class="form-check-label" for="chk-2">Không đá</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input note-check" type="checkbox" id="chk-3" value="Nhiều cafe">
                <label class="form-check-label" for="chk-3">Nhiều cafe</label>
            </div>

        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
        <button class="btn btn-success" id="saveNoteBtn">Lưu</button>
      </div>
    </div>
  </div>
</div>

<!-- Cart offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="cartCanvas">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">🛒 Giỏ hàng</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body d-flex flex-column">

    <div id="cartItems" class="mb-3"><p class="text-muted">Chưa có món nào</p></div>
    <div class="mb-2">
      <input type="tel" id="customerPhone" name="customer_phone" class="form-control mb-2" placeholder="Số điện thoại">
      <textarea id="orderNote" name="pos_note" class="form-control" rows="2" placeholder="Ghi chú đơn..."></textarea>
    </div>

    <div id="submitArea">
      <!-- Submit button inside the form: we submit the form after mobileLoadItems -->
      <button id="placeOrderBtn" class="btn btn-success w-100">Đặt hàng</button>
    </div>
  </div>
</div>

<!-- scripts: jQuery -> Select2 -> Bootstrap -> pos.mobile.js -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- pos.mobile.js external (we also include the version below) -->
<script src="<?= $assets ?>pos/js/pos.mobile.js?v=2.8"></script>

<!-- Inline initialization (safe, small) -->
<script>
  (function(){
    // initialize select2 (dropdownParent so it's inside offcanvas)
    try {
      const iosInput = document.getElementById('iosTriggerInput');
      const $custSel = $('#customerSelect');

      $custSel.select2({
        dropdownParent: $('body'),
        placeholder: 'Chọn khách hàng',
        minimumInputLength: 1,
        allowClear: true,
        language: { inputTooShort: () => "" },
        ajax: {
          url: "<?= admin_url('customers/suggestions'); ?>",
          dataType: 'json',
          delay: 250,
          data: params => ({ term: params.term, limit: 10 }),
          processResults: data => ({ results: data.results })
        }
      });

      // iOS trigger input: tap -> mở select2
      iosInput.addEventListener('focus', function() {
        setTimeout(() => {
          $custSel.select2('open');
          const sf = document.querySelector('.select2-search__field');
          if (sf) sf.focus();
        }, 200);
      });

      // Khi chọn khách xong -> cập nhật lại input
      $custSel.on('select2:select', function(e) {
        iosInput.value = e.params.data.text || '';
      });

    } catch (e) {
      console.warn('select2 init failed', e);
    }


    


    // place order: validate, build hidden inputs and submit form
    document.getElementById('placeOrderBtn').addEventListener('click', function(){
      var cname = document.getElementById('customer_name').value.trim();
      var phone = document.getElementById('customerPhone').value.trim();
      if (!cname || !phone) {
        alert('Vui lòng nhập Tên khách và Số điện thoại!');
        return;
      }
      // build hidden inputs
      if (typeof mobileLoadItems === 'function') mobileLoadItems();

      // add customer_name and customer_phone into the existing form (posTable already created by mobileLoadItems)
      var posTable = document.getElementById('posTable');
      // ensure we don't duplicate
      if (posTable) {
        // add customer_name/customer_phone inputs (they will be posted)
        posTable.insertAdjacentHTML('beforeend', '<input type="hidden" name="customer_name" value="'+(cname.replace(/"/g,'&quot;'))+'">');
        posTable.insertAdjacentHTML('beforeend', '<input type="hidden" name="customer_phone" value="'+(phone.replace(/"/g,'&quot;'))+'">');
        // add customer id if selected
        try {
          var custVal = $('#customerSelect').val();
          if (custVal) posTable.insertAdjacentHTML('beforeend', '<input type="hidden" name="customer" value="'+custVal+'">');
        } catch(e){}
      }



      // submit the form (the form element is at top)
      var form = document.getElementById('pos-sale-form');
      if (form) {
        // set action to admin/pos (already set) then submit
        form.submit();
      } else {
        alert('Không tìm thấy form để submit.');
      }
    });
  })();
</script>
</body>
</html>
