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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


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
    
    .note-check {
        transform: scale(1.5); /* tăng 20% ~ 7% theo yêu cầu */
        margin-right: 6px;
    }
    /* Ẩn nút x mặc định trong input search (iOS & Safari) */
    input[type="search"]::-webkit-search-cancel-button {
      -webkit-appearance: none;
      appearance: none;
    }


   

    /* Giúp ô tìm món, select KH, input KH đồng bộ chiều cao */
    .navbar .form-control,
    .navbar .select2-container .select2-selection--single {
      height: 40px !important;              /* cùng chiều cao */
      border-radius: 6px;                   /* bo góc mềm */
      font-size: 16px;
      line-height: 40px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 38px !important;         /* canh chữ giữa */
    }

    .select2-container--default .select2-selection--single {
      border: 1px solid #ccc !important;
      padding: 0 8px;
    }

    .btn-info-order {
      background-color: #ffc107; /* vàng giống nút Ghi chú */
      color: #000;
      font-weight: 600;
      border: none;
      border-radius: 0.5rem;
      padding: 8px 12px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.15);
      transition: all 0.2s ease;
    }

    .btn-info-order:hover {
      background-color: #ffb300;
      transform: translateY(-1px);
    }

    .modal-header {
      background-color: #198754; /* cùng màu bg-success */
      color: #fff;
      font-weight: 600;
    }

    .modal-header .btn-close {
      filter: invert(1) grayscale(100%) brightness(200%);
    }
    .offcanvas-header {
      background-color: #198754;
      color: #fff;
      font-weight: 600;
    }
    .select2-container {
      z-index: 9999 !important;
    }

    .select2-dropdown {
      z-index: 10000 !important;
    }


    #btnOrderInfo {
      background-color: #007bff !important; /* xanh sáng như ảnh */
      border: none;
      border-radius: 6px;
      font-weight: 600;
      font-size: 16px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      height: 42px;
    }
    #btnOrderInfo i {
      font-size: 22px;
    }



  .btn[data-bs-target="#cartCanvas"] {
    font-size: 26px !important;
    line-height: 1;
    padding: 6px 10px;
  }

  #cartCount {
    font-size: 12px;
  }







    @media (max-width:576px) {
      .col-6 { flex: 0 0 50%; max-width:50%; }
    }
  </style>
</head>
<body>

<!-- Header -->
<nav class="navbar navbar-light bg-white sticky-top">

  <div class="container-fluid align-items-center">
    <a class="navbar-brand fw-bold" href="#"><img src="<?= base_url('assets/uploads/logos/logo3.png') ?>" alt="" class=""> TIỆM NƯỚC MINI</a>
    <div class="d-flex align-items-center flex-grow-1">
      <div class="position-relative flex-grow-1 me-2">
        <input class="form-control pe-5" id="searchInput" type="search" placeholder="Tìm món..." aria-label="Search">
        <button type="button" id="clearSearchBtn" class="btn position-absolute end-0 top-0 bottom-0 me-1 px-2 text-muted" style="border:none;background:transparent;">✕</button>
      </div>
      </button>

      <button id="btnOrderInfo" class="btn text-white d-flex align-items-center justify-content-center px-3 py-2" style="border:none; border-radius:8px; font-weight: 600;" data-bs-toggle="modal" data-bs-target="#orderInfoModal">
        <i class="fa-solid fa-circle-info me-2"></i> KH
      </button>

      <button class="btn btn-outline-light position-relative p-2" type="button"
        data-bs-toggle="offcanvas" data-bs-target="#cartCanvas"
        style="font-size: 26px; line-height: 1;">
        🛒
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" 
              id="cartCount" style="font-size: 12px;">0</span>
      </button>

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
      $img = !empty($p->image) ? base_url('assets/uploads/thumbs/'.$p->image) : base_url('assets/uploads/thumbs/no_image.png');
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

 <!-- Modal Thông Tin Đơn Hàng -->
<div class="modal fade" id="orderInfoModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">🧾 Thông Tin Đơn Hàng</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <!-- Select khách hàng -->
        <div class="mb-2">
          <label class="form-label">Khách hàng</label>
          <select id="customerSelect" class="form-control" style="width:100%;"></select>
        </div>
        <!-- Nhập tên khách -->
        <div class="mb-2">
          <label class="form-label">Tên khách</label>
          <input type="text" id="customer_name" class="form-control" placeholder="Nhập tên khách...">
        </div>
        <!-- Nhập SĐT -->
        <div class="mb-2">
          <label class="form-label">Số điện thoại</label>
          <input type="tel" id="customer_phone" class="form-control" placeholder="Nhập số điện thoại...">
        </div>
        <!-- Ghi chú -->
        <div class="mb-2">
          <label class="form-label">Ghi chú đơn</label>
          <textarea id="order_note" class="form-control" rows="2" placeholder="Nhập ghi chú..."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
        <button class="btn btn-success" id="saveOrderInfo">Lưu</button>
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
        dropdownParent: $('#orderInfoModal'),
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
      }).on('select2:open', function () {
        // Focus vào ô nhập khi mở dropdown
        setTimeout(() => {
          const input = document.querySelector('.select2-search__field');
          if (input) {
            input.focus();
            // Thêm sự kiện ảo để kích hoạt bàn phím iOS
            input.dispatchEvent(new Event('touchstart', { bubbles: true }));
            input.dispatchEvent(new Event('mousedown', { bubbles: true }));
          }
        }, 300);
      });

      // Toggle giữa chọn KH và nhập KH
      document.getElementById('toggleCustomerMode').addEventListener('click', function() {
        const btn = this;
        const selectWrap = document.getElementById('selectCustomerWrap');
        const inputWrap = document.getElementById('inputCustomerWrap');
        
        const isSelectVisible = !selectWrap.classList.contains('d-none');

        if (isSelectVisible) {
          // Đang là chọn khách → chuyển sang nhập tên
          selectWrap.classList.add('d-none');
          inputWrap.classList.remove('d-none');
          btn.innerHTML = '<i class="fa fa-sync-alt"></i> Chọn';
          document.getElementById('customer_name').focus();
        } else {
          // Đang là nhập tên → chuyển sang chọn khách
          inputWrap.classList.add('d-none');
          selectWrap.classList.remove('d-none');
          btn.innerHTML = '<i class="fa fa-sync-alt"></i> Nhập';
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

  // ---- Lưu & Load thông tin đơn hàng ----
  const orderInfoKey = 'pos_order_info';

  // Khi mở modal, load lại thông tin đã lưu
  document.getElementById('orderInfoModal').addEventListener('shown.bs.modal', function() {
    const data = JSON.parse(localStorage.getItem(orderInfoKey) || '{}');
    if (data.customer_id) $('#customerSelect').val(data.customer_id).trigger('change');
    if (data.customer_name) document.getElementById('customer_name').value = data.customer_name;
    if (data.customer_phone) document.getElementById('customer_phone').value = data.customer_phone;
    if (data.order_note) document.getElementById('order_note').value = data.order_note;
  });

  // Khi nhấn “Lưu”
  document.getElementById('saveOrderInfo').addEventListener('click', function() {
    const data = {
      customer_id: $('#customerSelect').val(),
      customer_name: document.getElementById('customer_name').value.trim(),
      customer_phone: document.getElementById('customer_phone').value.trim(),
      order_note: document.getElementById('order_note').value.trim()
    };
    localStorage.setItem(orderInfoKey, JSON.stringify(data));
    alert('Đã lưu thông tin đơn hàng!');
    const modal = bootstrap.Modal.getInstance(document.getElementById('orderInfoModal'));
    modal.hide();
  });

  // Khi mở giỏ hàng, hiển thị thông tin khách hàng trong phần tóm tắt
  document.getElementById('cartCanvas').addEventListener('show.bs.offcanvas', function() {
    const data = JSON.parse(localStorage.getItem(orderInfoKey) || '{}');
    const container = document.getElementById('cartItems');
    if (data.customer_name || data.customer_phone) {
      const info = `
        <div class="mb-2 p-2 border rounded bg-light">
          <strong>${data.customer_name || 'Không tên'}</strong><br>
          📞 ${data.customer_phone || ''}<br>
          🗒️ ${data.order_note || ''}
        </div>`;
      container.insertAdjacentHTML('afterbegin', info);
    }
  });

</script>
</body>
</html>
