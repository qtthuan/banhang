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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">



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
      background-color: #35b9bb !important; /* xanh sáng như ảnh */
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

  /* Đảm bảo select2 cùng chiều cao với input */
.select2-container--bootstrap5 .select2-selection--single,
.select2-container .select2-selection--single {
  height: calc(2.25rem + 2px) !important; /* ~ chiều cao .form-control */
  padding: 0.375rem 0.75rem !important;
  display: flex;
  align-items: center;
  border: 1px solid #ced4da;
  border-radius: 0.375rem;
}

/* Căn giữa text và mũi tên */
.select2-selection__rendered {
  line-height: normal !important;
  padding-left: 0 !important;
}

.select2-selection__arrow {
  height: 100% !important;
}

/* Giúp select2 và input nằm thẳng hàng hoàn hảo */
.select2-container {
  width: 100% !important;
}


.modal-footer .btn {
  font-size: 1.15rem;
  padding: 10px 20px;
}

#phone_suggestions {
    position: absolute;
    background: #fff;
    width: 100%;
    z-index: 9999;
    border: 1px solid #ddd;
}

/*.status-box {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #6c757d;
    color: #fff;
    padding: 12px 16px;
    border-radius: 6px;
    font-size: 14px;
    box-shadow: 0 4px 12px rgba(0,0,0,.15);
    z-index: 9999;
    opacity: 0;
    transition: all .3s ease;
}

.status-box.show {
    opacity: 1;
}

.status-box.hidden {
    display: none;
}*/



/* Header gốc */
.modal-header {
  background: #198754; /* xanh */
  overflow: hidden;
}

/* Status box */
.modal-status-box {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 5;

  display: flex;
  align-items: center;
  justify-content: center;

  font-weight: 600;
  font-size: 15px;
  color: #fff;
}

/* Màu */
.modal-status-success {
  background: #198754; /* xanh */
}

.modal-status-error {
  background: #dc3545; /* đỏ */
}


@keyframes shake {
  0% { transform: translateX(0); }
  20% { transform: translateX(-4px); }
  40% { transform: translateX(4px); }
  60% { transform: translateX(-4px); }
  80% { transform: translateX(4px); }
  100% { transform: translateX(0); }
}

.input-shake {
  animation: shake 0.35s;
  border-color: #dc3545 !important; /* đỏ */
}








    @media (max-width:576px) {
      .col-6 { flex: 0 0 50%; max-width:50%; }
    }
  </style>
</head>
<body>
  <script>
    ///var currentCustomer = JSON.parse(localStorage.getItem('customer_info') || '{}');
    var base_url = "<?= base_url(); ?>"; // thêm nếu chưa có
    var admin_url = "<?= admin_url(); ?>";
    var mini_url = "<?= mini_url(); ?>";

    if (typeof group_code !== 'undefined') {
      let info = JSON.parse(localStorage.getItem('customer_info') || '{}');
      info.group_code = group_code;
      localStorage.setItem('customer_info', JSON.stringify(info));
    }

  </script>

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

      <?php //if (!empty($group_items)): ?>
      <div class="alert alert-info">
          <strong>Đơn nhóm: <?= $group->code ?></strong><br>
          <?php foreach ($group_items as $item): ?>
              <div>
                  <b><?= $item->customer_name ?>:</b>
                  <?= $item->product_name ?> x <?= $item->qty ?>
              </div>
          <?php endforeach; ?>
      </div>
      <?php //endif; ?>

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
    <?php //$this->sma->print_arrays($products); ?>
    <?php if (!empty($group)): ?>
      <script>
        console.log('Has group');
        console.log('code: ' + "<?= $group->code ?>" + ' order_id: ' + "<?= $group->id ?>");
          var group_code = "<?= $group->code ?>";
          var group_order_id = "<?= $group->id ?>";
          localStorage.setItem('group_code', group_code);
          localStorage.setItem('group_order_id', group_order_id);
      </script>
    <?php endif; ?>

    <?php foreach ($products as $p):
      $img = !empty($p->image) ? base_url('assets/uploads/thumbs/'.$p->image) : base_url('assets/uploads/thumbs/no_image.png');
      $cleanName = preg_replace('/^[A-Z]_\s*/', '', strtoupper($p->name));
      $code = isset($p->code) ? $p->code : '';
      $name_en = isset($p->name_en) ? $p->name_en : '';
      $unit = isset($p->unit) ? $p->unit : '';
    ?>
    
<div class="col-6 col-md-4 mb-3 product-card"
     data-product-id="<?= $p->id ?>"
     data-name="<?= $p->name ?>"
     data-base-price="<?= (float)$p->price ?>">    
    
      <div class="card h-100">
        <div class="card-body text-center">
          <img src="<?= $img ?>" alt="<?= htmlspecialchars($p->name) ?>" class="product-img">
          <h6 class="card-title text-uppercase"><?= htmlspecialchars($cleanName) ?></h6>

          <!-- Base price element stores base in data-base -->


          <p class="text-muted mb-1 product-price" 
            id="price-<?= $p->id ?>" 
            data-product-id="<?= $p->id ?>" 
            data-base-price="<?= (float)$p->price ?>" 
            data-price-m="<?= (float)$p->price ?>"
            data-price-l="<?= (float)$p->price ?>">
            <?php if (!empty($p->is_promo) && $p->is_promo): ?>
              <span class="text-danger fw-bold"><?= number_format($p->price, 0, ',', '.') ?>đ</span>
              <small class="text-muted text-decoration-line-through"><?= number_format($p->original_price, 0, ',', '.') ?>đ</small>
            <?php else: ?>
              <?= number_format($p->price, 0, ',', '.') ?>đ
            <?php endif; ?>
          </p>

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
      <div class="modal-header position-relative p-0">

        <!-- STATUS BOX -->
        <div id="modalStatusBox" class="modal-status-box d-none"></div>

        <!-- HEADER GỐC -->
        <div class="modal-header-content d-flex align-items-center w-100 px-3 py-2">
          <h5 class="modal-title text-white mb-0" id="customerModalTitle">
            🧾 Thông Tin Khách Hàng
          </h5>
          <button type="button" class="btn-close btn-close-white ms-auto"
                  data-bs-dismiss="modal"></button>
        </div>

      </div>


      <!-- <div class="modal-header">
        <h5 class="modal-title" id="customerModalTitle">🧾 Thông Tin Khách Hàng</h5>
       <div id="status-box" class="status-box"></div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div> -->

      
      <div class="modal-body">
        <!-- Nhập SĐT -->
        <div class="mb-3">
          <label class="form-label"><i class="fa fa-phone text-warning me-1"></i> Số điện thoại</label>
          <input type="tel" id="customer_phone" class="form-control" placeholder="Nhập số điện thoại...">

          <div id="phone_suggestions" 
              style="background:#fff; border:1px solid #ddd; display:none; position:absolute; z-index:9999; width: 92%; max-height:150px; overflow-y:auto;">
          </div>

        </div>
        <!-- Nhập tên khách -->
        <div class="mb-3">
          <label class="form-label"><i class="fa fa-id-badge text-success me-1"></i> Tên khách</label>
          <input type="text" id="customer_name" class="form-control" placeholder="Nhập tên...">
        </div>
        <div class="mb-3">
          <label class="form-label"><i class="fa fa-address-card text-info me-1"></i> Địa chỉ giao</label>
          <input type="text" id="customer_address" class="form-control" placeholder="Nhập địa chỉ...">
        </div>
        <div class="form-group d-flex align-items-center" style="gap:13px; margin-bottom:10px;">
        <!-- Chữ Đơn nhóm -->
        <label class="mb-0" style="font-weight:600; font-size:15px; cursor:pointer;" 
              for="group_order_toggle">
            <i class="fa fa-users"></i> Đơn nhóm
        </label>
        <!-- Toggle -->
        <label class="switch mb-0">
            <input type="checkbox" id="group_order_toggle">
            <span class="slider round"></span>
        </label>

        <input 
          type="text" 
          id="copyInput"
          style="position:fixed; top:-1000px; opacity:0;"
          readonly
        >
        

        <style>
          .switch {
            position: relative; display: inline-block;
            width: 48px; height: 24px;
          }
          .switch input { display:none; }
          .slider {
            position: absolute; cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
          }
          .slider:before {
            position: absolute; content: "";
            height: 18px; width: 18px;
            left: 3px; bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
          }
          input:checked + .slider { background-color: #28a745; }
          input:checked + .slider:before {
            transform: translateX(24px);
          }
        </style>

    </div>

        <!-- Ghi chú -->
        <div class="mb-3">
          <label class="form-label"><i class="fa fa-sticky-note text-danger me-1"></i> Ghi chú đơn</label>
          <textarea id="order_note" class="form-control" rows="2" placeholder="Nhập ghi chú..."></textarea>
        </div>
      </div>
      

      <!-- <div id="saveStatus"
          style="display:none; position:absolute; top:50%; left:50%; transform:translate(-50%,-50%);
                  background:rgba(0,0,0,0.75); color:#fff; padding:10px 20px;
                  border-radius:8px; font-weight:500; z-index:2000;">
        Đang lưu...
      </div> -->
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="resetOrderInfoBtn">Reset</button>
        <button class="btn btn-danger" data-bs-dismiss="modal">Đóng</button>
        <button class="btn btn-success" id="saveCustomerInfoBtn">Lưu</button>
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

    <div id="cartCustomerInfo" class="mb-3 border-bottom pb-2"></div>

    <div id="cartItems" class="mb-3"><p class="text-muted">Chưa có món nào</p></div>
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
 
<script src="<?= $mini_assets ?>/js/mini.mobile.js"></script>

<!-- Inline initialization (safe, small) -->
<script>
  (function(){



    $('#customer_phone').on('keyup', function () {
      let phone = $(this).val().trim();

      if (phone.length < 7) {
          $('#phone_suggestions').hide();
          return;
      }
      //console.log('111: ' + base_url);

      $.ajax({
        url: base_url + "order/findCustomer",
        type: "GET",
        dataType: "json",
        data: { term: phone },
        success: function (res) {
          //console.log('222: ' + base_url);

            let list = res.results || [];

            if (!Array.isArray(list) || list.length === 0) {
                $('#phone_suggestions').hide();
                return;
            }

            let html = '';

            list.slice(0, 7).forEach(c => {
                html += `
                    <div class="suggest-item" 
                        data-name="${c.name}" 
                        data-address="${c.address}"
                        data-phone="${c.phone}"
                        data-id="${c.id}"
                        style="padding:8px; cursor:pointer;background:#dbebeb">
                        <strong>${c.name}</strong><br>
                        <small>${c.phone} - ${c.address}</small>
                    </div>`;
            });

            $('#phone_suggestions').html(html).show();
        }
    });

  });

  



$(document).on('click', '.suggest-item', function () {



  // const saved = JSON.parse(localStorage.getItem('customer_info') || '{}');

  // saved.customer_id   = $(this).data('id');      // 👈 QUAN TRỌNG
  // saved.customer_name = $(this).data('name');
  // saved.customer_phone = $(this).data('phone');
  // saved.customer_address = $(this).data('address');

  // localStorage.setItem('customer_info', JSON.stringify(saved));




    let name = $(this).data('name');
    let phone = $(this).data('phone');
    let address = $(this).data('address');
    let customer_id = $(this).data('id');

    $('#customer_phone').val(phone);
    $('#customer_name').val(name);
    $('#customer_address').val(address);

    let info = JSON.parse(localStorage.getItem('customer_info') || '{}');
    info.customer_id = customer_id;
    info.customer_name = name;
    info.customer_phone = phone;
    info.customer_address = address;
    localStorage.setItem('customer_info', JSON.stringify(info));

    $('#phone_suggestions').hide();
});



  //   $('#customer_phone').on('keyup', function () {
  //     let phone = $(this).val().trim();

  //     if (phone.length < 4) return; // gõ ít quá thì không tìm
  //     console.log('1111: ' + phone);

  //     $.ajax({
  //         url: admin_url + "customers/findCustomer",
  //         type: "GET",
  //         dataType: "json",
  //         data: { term: phone },    // POS đang dùng 'term'
  //         success: function (res) {
  //           console.log('222: ' + JSON.stringify(res));
  //             if (res.length > 0) {
  //                 let cus = res[0];  // lấy khách đầu tiên khớp nhất

  //                 // Gán thông tin xuống form
  //                 $('#customer_name').val(cus.name);
  //                 $('#customer_address').val(cus.address);

  //                 // Nếu bạn có lưu customer_id thì cho vào hidden
  //                 $('#customer_id').val(cus.id);
  //             }
  //         }
  //     });
  // });

     
    // === Nút Reset form Thông tin đơn hàng ===
    document.getElementById('resetOrderInfoBtn').addEventListener('click', function() {
      try {
        // Xóa chọn trong select2
        if ($('#customerSelect').data('select2')) {
          $('#customerSelect').val(1).trigger('change');
        } else {
          const sel = document.getElementById('customerSelect');
          if (sel) sel.selectedIndex = -1;
        }

        // Reset input text
        document.getElementById('customer_name').value = '';
        document.getElementById('customer_phone').value = '';
        document.getElementById('customer_address').value = '';
        document.getElementById('order_note').value = '';

         // ✅ Reset toggle Đơn nhóm
        const toggle = document.getElementById('group_order_toggle');
        if (toggle) {
          toggle.checked = false;
        }

        // Gọi lại cập nhật giá mặc định (giá gốc)
        updateProductPrices();

        // Xóa dữ liệu localStorage
        localStorage.removeItem('customer_info');
        updateCustomerModalTitle();
        
      } catch (err) {
        console.error('Reset error', err);
      }
    });



    // place order: validate, build hidden inputs and submit form
    document.getElementById('placeOrderBtn').addEventListener('click', function(){
      
      // build hidden inputs
      if (typeof mobileLoadItems === 'function') mobileLoadItems();

      // add customer_name and customer_phone into the existing form (posTable already created by mobileLoadItems)
      var posTable = document.getElementById('posTable');
      // ensure we don't duplicate
      const info = JSON.parse(localStorage.getItem('customer_info') || '{}');
      if (info.customer_id) posTable.innerHTML += '<input type="hidden" name="customer" value="'+escapeHtml(info.customer_id)+'">';
      posTable.innerHTML += '<input type="hidden" name="customer_name" value="'+escapeHtml(info.customer_name || info.customer_text || '')+'">';
      posTable.innerHTML += '<input type="hidden" name="customer_phone" value="'+escapeHtml(info.customer_phone || '')+'">';
      posTable.innerHTML += '<input type="hidden" name="pos_note" value="'+escapeHtml(info.order_note || '')+'">';
      posTable.innerHTML += '<input type="hidden" name="warehouse" value="3">';
      posTable.innerHTML += '<input type="hidden" name="biller" value="7283">';



      // submit the form (the form element is at top)
      var form = document.getElementById('pos-sale-form');
      if (form) {
        // set action to admin/pos (already set) then submit
        //form.submit();
      } else {
        alert('Không tìm thấy form để submit.');
      }
    });
  })();

  // ---- Lưu & Load thông tin đơn hàng ----
  const orderInfoKey = 'pos_order_info';

  // Khi mở modal, load lại thông tin đã lưu
  document.getElementById('orderInfoModal').addEventListener('shown.bs.modal', function() {
    // load lại thông tin từ localStorage
    const savedInfo = JSON.parse(localStorage.getItem('customer_info') || '{}');
    if (savedInfo.customer_name) document.getElementById('customer_name').value = savedInfo.customer_name;
    if (savedInfo.customer_phone) document.getElementById('customer_phone').value = savedInfo.customer_phone;
    if (savedInfo.order_note) document.getElementById('order_note').value = savedInfo.order_note;

    // load lại khách hàng đã chọn
    if (savedInfo.customer_id && savedInfo.customer_text) {
      const option = new Option(savedInfo.customer_text, savedInfo.customer_id, true, true);
      $('#customerSelect').append(option).trigger('change');
    }

    updateCustomerModalTitle();

  });

  // Khi nhấn “Lưu”
  document.getElementById('saveCustomerInfoBtn').addEventListener('click', function() {
    const statusBox = document.getElementById('saveStatus');
    const modal = document.getElementById('orderInfoModal');

    const customerId = $('#customerSelect').val();  
    const customerText = $('#customerSelect').find('option:selected').text();
    const isGroup = document.getElementById('group_order_toggle') && document.getElementById('group_order_toggle').checked;


    const saved = JSON.parse(localStorage.getItem('customer_info') || '{}');
    console.log(JSON.stringify(saved));
    const updated = {
      ...saved,
      //customer_name: document.getElementById('customer_name').value || '',
      //customer_phone: document.getElementById('customer_phone').value || '',
      //address: document.getElementById('customer_address').value || '',
      order_note: document.getElementById('order_note').value || ''
      
    };

    const phoneInput = document.getElementById('customer_phone');
    const phone = phoneInput.value.trim();

    if (!phone) {
      showStatus('⚠️ Vui lòng nhập số điện thoại', 'error', 2000);

      // rung input
      phoneInput.classList.remove('input-shake'); // reset
      void phoneInput.offsetWidth;               // force reflow
      phoneInput.classList.add('input-shake');

      phoneInput.focus();
      return; // ❌ không cho chạy tiếp
    }



    localStorage.setItem('customer_info', JSON.stringify(updated));

    //console.log(JSON.stringify(updated));
    if (!isGroup) {

      // Giả lập xử lý lưu ajax (có thể thay bằng thật)
      setTimeout(() => {
        // hiển thị "Đã lưu"
        //statusBox.textContent = '✅ Đã lưu';
        showStatus('✅ Đã lưu', 'success', 1800);
        
        // ẩn sau 800ms và đóng modal
        setTimeout(() => {
          //statusBox.style.display = 'none';
          const modalInstance = bootstrap.Modal.getInstance(modal);
          if (modalInstance) modalInstance.hide();
        }, 400);

      }, 400); // giả lập thời gian ajax
      return;
    }

    
    const csrfName = $('#csrf_token_input').attr('name');
    const csrfHash = $('#csrf_token_input').val();

    fetch(base_url + 'order/create_group', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: new URLSearchParams({
            customer_name: saved.customer_name,
            customer_phone: saved.customer_phone,
            customer_address: saved.address,
            customer_id: saved.customer_id,
            note: updated.note,
            [csrfName]: csrfHash       // 🚀 Gửi CSRF token
        })
    })
    .then(r => r.json())
    .then(json => {
        if (json && json.success && json.code) {
        updated.group_code = json.code;
        const link = json.link || (location.origin + '/order/' + json.code);
        updated.group_link = link;
        localStorage.setItem('customer_info', JSON.stringify(updated));
        updateCustomerModalTitle();
      } else {
        alert('Tạo mã nhóm thất bại.');
      }
    })
    .catch(err => console.error(err));
    
  });


function showStatus(message, type = 'success', duration = 2000, redirectUrl = null) {
  const box = document.getElementById('modalStatusBox');
  if (!box) return;

  box.textContent = message;
  box.className = 'modal-status-box';

  if (type === 'error') {
    box.classList.add('modal-status-error');
  } else {
    box.classList.add('modal-status-success');
  }

  box.classList.remove('d-none');

  setTimeout(() => {
    box.classList.add('d-none');
    if (redirectUrl) {
      window.location.href = redirectUrl;
    }
  }, duration);
}


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
