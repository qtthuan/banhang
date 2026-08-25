document.addEventListener('DOMContentLoaded', function () {

    const grid =
        document.getElementById(
            'mini-product-grid'
        );

    if (!grid) {
        return;
    }


    /* =========================================================
     * STATE
     * ========================================================= */

    let miniModal = null;

    let miniBasePrice = 0;

    let miniVariantPrice = 0;

    let miniSelectedVariantId = '';

    let miniDiscountType = 'amount';

    /*
    * Row đang được edit.
    * null = đang thêm món mới.
    */
    let miniEditingRowId = null;

    let miniOrderDeliveryFee = 0;

    let miniOrderDiscount = 0;

    let miniOrderNote = '';

    let miniOrderToolType = '';


    /*
     * Array thay cho Map.
     *
     * Mỗi lần thêm sản phẩm:
     * => tạo một rowId mới
     * => không cộng dồn quantity.
     */

    const cart = [];


    /* =========================================================
     * MONEY
     * ========================================================= */

    function money(value) {

        return (
            Math.max(
                0,
                Math.round(
                    Number(value) || 0
                )
            )
            .toLocaleString('vi-VN') +
            'đ'
        );

    }


    /* =========================================================
     * ESCAPE
     * ========================================================= */

    function escapeHtml(value) {

        return String(
            value == null
                ? ''
                : value
        )
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');

    }


    /* =========================================================
     * UNIQUE ROW ID
     * ========================================================= */

    function createRowId() {

        return (
            Date.now().toString(36) +
            '_' +
            Math.random()
                .toString(36)
                .slice(2, 9)
        );

    }


    /* =========================================================
     * DISCOUNT
     * ========================================================= */

    function getItemDiscount(item) {

        const value =
            Math.max(
                0,
                Number(
                    item.discount
                ) || 0
            );


        if (
            item.discountType ===
            'percent'
        ) {

            return Math.min(
                item.price,
                item.price *
                Math.min(
                    100,
                    value
                ) /
                100
            );

        }


        return Math.min(
            item.price,
            value
        );

    }


    function getItemUnitNet(item) {

        return Math.max(
            0,
            item.price -
            getItemDiscount(item)
        );

    }


    function getItemTotal(item) {

        return (
            getItemUnitNet(item) *
            item.qty
        );

    }


    /* =========================================================
     * MOBILE CART BAR
     * ========================================================= */

    function updateMobileCartBar(
        qty,
        total
    ) {

        const panel =
            document.querySelector(
                '.mini-order-info-panel'
            );


        if (!panel) {
            return;
        }


        let meta =
            panel.querySelector(
                '.mini-mobile-cart-meta'
            );


        if (!meta) {

            meta =
                document.createElement(
                    'div'
                );

            meta.className =
                'mini-mobile-cart-meta';


            meta.innerHTML = `

                <span
                    class="mini-mobile-cart-count">
                    0 món
                </span>

                <strong
                    class="mini-mobile-cart-total">
                    0đ
                </strong>

            `;


            panel.appendChild(
                meta
            );

        }


        const count =
            meta.querySelector(
                '.mini-mobile-cart-count'
            );


        const totalEl =
            meta.querySelector(
                '.mini-mobile-cart-total'
            );


        if (count) {

            count.textContent =
                qty + ' món';

        }


        if (totalEl) {

            totalEl.textContent =
                money(total);

        }

    }


    /* =========================================================
     * RENDER CART
     * ========================================================= */

    function renderCart() {

        const list =
            document.getElementById(
                'mini-cart-list'
            );


        const empty =
            document.getElementById(
                'mini-cart-empty'
            );


        if (!list) {
            return;
        }


        /*
         * Xóa row cũ.
         */

        list
            .querySelectorAll(
                '.mini-cart-row'
            )
            .forEach(
                function (row) {
                    row.remove();
                }
            );


        /*
         * Empty.
         */

        if (!cart.length) {

            if (empty) {

                empty.style.display =
                    'flex';

            }

        } else {

            if (empty) {

                empty.style.display =
                    'none';

            }


            /*
             * Render từng row.
             */

            cart.forEach(
                function (item) {

                    const row =
                        document.createElement(
                            'div'
                        );


                    row.className =
                        'mini-cart-row';


                    row.dataset.rowId =
                        item.rowId;


                    row.dataset.productId =
                        item.id;


                    const noteText = [

                        item.comment ||
                            '',

                        item.commentName
                            ? '' +
                            item.commentName
                            : ''

                    ]
                    .filter(Boolean)
                    .join(' • ');


                    row.innerHTML = `
                        <div class="mini-cart-row-content">

                            <div class="mini-cart-name">
                                <strong>
                                    ${escapeHtml(
                                        miniDisplayProductName(
                                            item.name
                                        )
                                    )}${
                                        item.optionName
                                            ? ' (' +
                                            escapeHtml(
                                                item.optionName
                                            ) +
                                            ')'
                                            : ''
                                    }
                                </strong>

                                ${
                                    noteText
                                        ? `
                                            <span
                                                class="mini-cart-note">
                                                ${escapeHtml(
                                                    noteText
                                                )}
                                            </span>
                                        `
                                        : ''
                                }

                            </div>


                            <div class="mini-cart-price">

                                ${money(
                                    getItemUnitNet(
                                        item
                                    )
                                )}

                            </div>


                            <div class="mini-cart-qty">

                                <button
                                    type="button"
                                    data-cart-action="minus"
                                    aria-label="Giảm">
                                    −
                                </button>

                                <span>
                                    ${item.qty}
                                </span>

                                <button
                                    type="button"
                                    data-cart-action="plus"
                                    aria-label="Tăng">
                                    +
                                </button>

                            </div>


                            <div class="mini-cart-total">

                                ${money(
                                    getItemTotal(
                                        item
                                    )
                                )}

                            </div>

                        </div>


                        <button
                            type="button"
                            class="mini-cart-delete"
                            data-cart-action="delete"
                            aria-label="Xóa món">
                            XÓA
                        </button>
                    `;


                    list.appendChild(
                        row
                    );

                }
            );

        }


        /* =====================================================
         * TOTAL
         * ===================================================== */

        let qty = 0;

        let subtotal = 0;

        let discount = 0;


        cart.forEach(
            function (item) {

                qty +=
                    item.qty;


                subtotal +=
                    item.price *
                    item.qty;


                discount +=
                    getItemDiscount(
                        item
                    ) *
                    item.qty;

            }
        );


        const deliveryFee =
            0;


        const grandTotal =
            Math.max(
                0,
                subtotal -
                discount +
                deliveryFee
            );


        /* =====================================================
         * UPDATE DOM
         * ===================================================== */

        const count =
            document.getElementById(
                'mini-products-count'
            );


        const qtyEl =
            document.getElementById(
                'mini-total-qty'
            );


        const subEl =
            document.getElementById(
                'mini-subtotal'
            );


        const discountEl =
            document.getElementById(
                'mini-total-discount'
            );


        const deliveryEl =
            document.getElementById(
                'mini-delivery-fee'
            );


        const grandEl =
            document.getElementById(
                'mini-grand-total'
            );


        if (count) {

            count.textContent =
                qty + ' món';

        }


        if (qtyEl) {

            qtyEl.textContent =
                qty;

        }


        if (subEl) {

            subEl.textContent =
                money(subtotal);

        }


        if (discountEl) {

            discountEl.textContent =
                money(discount);

        }


        if (deliveryEl) {

            deliveryEl.textContent =
                money(deliveryFee);

        }


        if (grandEl) {

            grandEl.textContent =
                money(grandTotal);

        }


        /*
         * Mobile bar.
         */

        updateMobileCartBar(
            qty,
            grandTotal
        );

    }


    /* =========================================================
     * ADD TO CART
     * ========================================================= */

    function addToCart(
        card,
        quantity,
        extra
    ) {

        if (!card) {
            return;
        }


        const data =
            extra || {};


        const item = {

            rowId:
                createRowId(),


            id:
                String(
                    card.dataset
                        .productId || ''
                ),


            code:
                card.dataset.code || '',


            name:
                card.dataset.name ||
                card
                    .querySelector(
                        '.mini-product-name'
                    )
                    ?.textContent
                    .trim() ||
                '',


            price:
                (
                    parseFloat(
                        card.dataset.price || 0
                    ) || 0
                ) +
                (
                    Number(
                        data.variantPrice
                    ) || 0
                ),


            qty:
                Math.max(
                    1,
                    parseInt(
                        quantity,
                        10
                    ) || 1
                ),


            discount:
                Math.max(
                    0,
                    Number(
                        data.discount
                    ) || 0
                ),


            discountType:
                data.discountType ===
                'percent'
                    ? 'percent'
                    : 'amount',


            comment:
                data.comment || '',


            commentName:
                data.commentName || '',


            option:
                data.option || '',

            optionName:
                data.optionName || '',


            serial:
                data.serial || '',


            isPromo:
                Number(
                    card.dataset
                        .promo || 0
                )
                    ? 1
                    : 0

        };


        /*
         * QUAN TRỌNG:
         *
         * Không tìm sản phẩm cũ.
         * Không cộng quantity.
         *
         * Mỗi click = dòng mới.
         */

        cart.push(item);


        renderCart();

        /* =====================================================
        * DESKTOP: CUỘN TỚI MÓN VỪA THÊM
        * ===================================================== */

        if (
            window.matchMedia('(min-width: 801px)').matches
        ) {

            const cartList =
                document.getElementById(
                    'mini-cart-list'
                );

            if (cartList) {

                requestAnimationFrame(function () {

                    cartList.scrollTo({
                        top: cartList.scrollHeight,
                        behavior: 'smooth'
                    });

                });

            }

        }


        /*
         * Animation.
         */

        card.classList.remove(
            'quick-added'
        );


        void card.offsetWidth;


        card.classList.add(
            'quick-added'
        );

    }


    /* =========================================================
     * CHANGE CART QTY
     * ========================================================= */

    function changeCartQty(
        rowId,
        delta
    ) {

        const index =
            cart.findIndex(
                function (item) {

                    return (
                        item.rowId ===
                        rowId
                    );

                }
            );


        if (index === -1) {
            return;
        }


        cart[index].qty +=
            delta;


        if (
            cart[index].qty <= 0
        ) {

            cart.splice(
                index,
                1
            );

        }


        renderCart();

    }


    /* =========================================================
     * QUICK NOTES
     * ========================================================= */

    function renderQuickNotes() {

        const container =
            document.getElementById(
                'mini-quick-notes-list'
            );


        if (!container) {
            return;
        }


        const comments =
            Array.isArray(
                window.miniOrderComments
            )
                ? window.miniOrderComments
                : [];


        container.innerHTML =
            comments
                .map(
                    function (comment) {

                        const id =
                            String(
                                comment.id ||
                                ''
                            );


                        const text =
                            String(
                                comment.comment ||
                                ''
                            )
                            .trim();


                        if (
                            !id ||
                            !text
                        ) {

                            return '';

                        }


                        return `

                            <div class="mini-note-item">

                                <input
                                    type="checkbox"
                                    class="btn-check mini-note-checkbox chkComment"
                                    id="mini-comment-${escapeHtml(id)}"
                                    value="${escapeHtml(text)}"
                                    autocomplete="off">

                                <label
                                    class="btn btn-outline-secondary mini-note-btn"
                                    for="mini-comment-${escapeHtml(id)}">

                                    ${escapeHtml(text)}

                                </label>

                            </div>

                        `;

                    }
                )
                .join('');

    }

    let miniAlertTimer = null;


    function showMiniAlert(
        message,
        type = 'danger',
        duration = 2500
    ) {

        const alertBox =
            document.getElementById(
                'mini-global-alert'
            );


        if (!alertBox) {
            return;
        }


        /*
        * Hủy timer cũ nếu alert đang hiện.
        */
        if (miniAlertTimer) {

            clearTimeout(
                miniAlertTimer
            );

            miniAlertTimer = null;

        }


        /*
        * Xóa toàn bộ màu Bootstrap cũ.
        */
        alertBox.classList.remove(
            'alert-danger',
            'alert-warning',
            'alert-success',
            'alert-info'
        );


        /*
        * Thêm màu Bootstrap mới.
        */
        alertBox.classList.add(
            'alert-' + type
        );


        /*
        * Nội dung.
        */
        alertBox.textContent =
            message;


        /*
        * Bootstrap dùng d-none để ẩn.
        * Xóa d-none => hiện alert.
        */
        alertBox.classList.remove(
            'd-none'
        );


        /*
        * Tự ẩn sau duration.
        */
        miniAlertTimer =
            setTimeout(
                function () {

                    alertBox.classList.add(
                        'd-none'
                    );

                },
                duration
            );

    }

    /* =========================================================
    * QUICK ADD PRODUCT MODAL
    * ========================================================= */

    function createQuickAddModal() {

        if (
            document.getElementById(
                'mini-quick-add-modal'
            )
        ) {
            return;
        }


        const html = `

            <div
                id="mini-quick-add-modal"
                class="mini-modal"
                aria-hidden="true">

                <div
                    class="mini-modal-overlay"
                    data-quick-add-close>
                </div>


                <div
                    class="mini-modal-dialog"
                    role="dialog"
                    aria-modal="true">

                    <div class="mini-modal-header">

                        <div>

                            <div class="mini-modal-header-name">
                                Thêm món nhanh
                            </div>

                            <div class="mini-modal-header-price">
                                Món ngoài menu
                            </div>

                        </div>


                        <button
                            type="button"
                            class="mini-modal-close"
                            data-quick-add-close>
                            ×
                        </button>

                    </div>


                    <div class="mini-modal-body">


                        <!-- TÊN MÓN -->

                        <div class="mini-modal-field">

                            <label for="mini-quick-name">
                                Tên món
                            </label>

                            <input
                                type="text"
                                id="mini-quick-name"
                                class="mini-modal-input"
                                placeholder="Nhập tên món..."
                                autocomplete="off">

                        </div>


                        <!-- GIÁ + GIẢM GIÁ -->

                        <div
                            class="mini-modal-two-col">

                            <div class="mini-modal-field">

                                <label for="mini-quick-price">
                                    Đơn giá
                                </label>

                                <input
                                    type="number"
                                    id="mini-quick-price"
                                    class="mini-modal-input"
                                    min="0"
                                    step="1000"
                                    inputmode="numeric"
                                    placeholder="0">

                            </div>


                            <div class="mini-modal-field">

                                <label for="mini-quick-discount">
                                    Giảm giá
                                </label>

                                <input
                                    type="number"
                                    id="mini-quick-discount"
                                    class="mini-modal-input"
                                    min="0"
                                    step="1000"
                                    inputmode="numeric"
                                    value="0">

                            </div>

                        </div>


                        <!-- SỐ LƯỢNG -->

                        <div class="mini-modal-field">

                            <label for="mini-quick-qty">
                                Số lượng
                            </label>

                            <div class="mini-qty-control">

                                <button
                                    type="button"
                                    id="mini-quick-qty-minus">
                                    −
                                </button>

                                <input
                                    type="number"
                                    id="mini-quick-qty"
                                    value="1"
                                    min="1"
                                    inputmode="numeric">

                                <button
                                    type="button"
                                    id="mini-quick-qty-plus">
                                    +
                                </button>

                            </div>

                        </div>

                    </div>


                    <div class="mini-modal-footer">

                        <button
                            type="button"
                            class="mini-modal-btn mini-modal-btn-cancel"
                            data-quick-add-close>
                            HỦY
                        </button>

                        <button
                            type="button"
                            id="mini-quick-add-submit"
                            class="mini-modal-btn mini-modal-btn-primary">
                            THÊM VÀO ĐƠN
                        </button>

                    </div>

                </div>

            </div>
        `;


        document.body.insertAdjacentHTML(
            'beforeend',
            html
        );


        const modal =
            document.getElementById(
                'mini-quick-add-modal'
            );


        /*
        * Đóng modal.
        */

        modal
            .querySelectorAll(
                '[data-quick-add-close]'
            )
            .forEach(
                function (button) {

                    button.addEventListener(
                        'click',
                        closeQuickAddModal
                    );

                }
            );


        /*
        * Quantity.
        */

        document
            .getElementById(
                'mini-quick-qty-minus'
            )
            .addEventListener(
                'click',
                function () {

                    const input =
                        document.getElementById(
                            'mini-quick-qty'
                        );

                    input.value =
                        Math.max(
                            1,
                            (
                                parseInt(
                                    input.value,
                                    10
                                ) || 1
                            ) - 1
                        );

                }
            );


        document
            .getElementById(
                'mini-quick-qty-plus'
            )
            .addEventListener(
                'click',
                function () {

                    const input =
                        document.getElementById(
                            'mini-quick-qty'
                        );

                    input.value =
                        Math.max(
                            1,
                            (
                                parseInt(
                                    input.value,
                                    10
                                ) || 1
                            ) + 1
                        );

                }
            );


        /*
        * Thêm vào đơn.
        */

        document
            .getElementById(
                'mini-quick-add-submit'
            )
            .addEventListener(
                'click',
                handleQuickAddSubmit
            );

        /* =========================================================
        * QUICK ADD - SELECT INPUT CONTENT
        * ========================================================= */

        [
            'mini-quick-name',
            'mini-quick-price',
            'mini-quick-discount',
            'mini-quick-qty'
        ].forEach(
            function (id) {

                const input =
                    document.getElementById(id);


                if (!input) {
                    return;
                }


                input.addEventListener(
                    'focus',
                    function () {

                        /*
                        * Select toàn bộ nội dung
                        * khi chạm/click vào ô.
                        */
                        this.select();

                    }
                );

            }
        );

    }

    function openQuickAddModal() {

        createQuickAddModal();


        const modal =
            document.getElementById(
                'mini-quick-add-modal'
            );


        document
            .getElementById(
                'mini-quick-name'
            )
            .value = '';


        document
            .getElementById(
                'mini-quick-price'
            )
            .value = '';


        document
            .getElementById(
                'mini-quick-discount'
            )
            .value = '0';


        document
            .getElementById(
                'mini-quick-qty'
            )
            .value = '1';


        modal.classList.add(
            'show'
        );


        modal.setAttribute(
            'aria-hidden',
            'false'
        );


        document.body.classList.add(
            'mini-modal-open'
        );


        setTimeout(
            function () {

                document
                    .getElementById(
                        'mini-quick-name'
                    )
                    ?.focus();

            },
            50
        );

    }


    function closeQuickAddModal() {

        const modal =
            document.getElementById(
                'mini-quick-add-modal'
            );


        if (!modal) {
            return;
        }


        modal.classList.remove(
            'show'
        );


        modal.setAttribute(
            'aria-hidden',
            'true'
        );


        document.body.classList.remove(
            'mini-modal-open'
        );

    }

    function handleQuickAddSubmit() {

        const name =
            document
                .getElementById(
                    'mini-quick-name'
                )
                ?.value
                .trim() || '';


        const price =
            Math.max(
                0,
                parseFloat(
                    document
                        .getElementById(
                            'mini-quick-price'
                        )
                        ?.value
                ) || 0
            );


        const discount =
            Math.min(
                price,
                Math.max(
                    0,
                    parseFloat(
                        document
                            .getElementById(
                                'mini-quick-discount'
                            )
                            ?.value
                    ) || 0
                )
            );


        const qty =
            Math.max(
                1,
                parseInt(
                    document
                        .getElementById(
                            'mini-quick-qty'
                        )
                        ?.value,
                    10
                ) || 1
            );


        /*
        * Validate.
        */

        if (!name) {

            showMiniAlert(
                'Vui lòng nhập tên món.'
            );

            document
                .getElementById(
                    'mini-quick-name'
                )
                ?.focus();

            return;

        }

        if (price <= 0) {

            showMiniAlert(
                'Vui lòng nhập đơn giá.'
            );

            document
                .getElementById(
                    'mini-quick-price'
                )
                ?.focus();

            return;
        }


        /*
        * Tạo dòng cart trực tiếp.
        *
        * Không cần product card.
        * Không lưu vào database.
        */

        cart.push({

            rowId:
                createRowId(),

            id:
                'quick-' +
                Date.now(),

            code:
                '',

            name:
                name,

            price:
                price,

            qty:
                qty,

            discount:
                discount,

            discountType:
                'amount',

            comment:
                '',

            commentName:
                '',

            option:
                '',

            serial:
                '',

            isPromo:
                0

        });


        /*
        * Render lại cart.
        */

        renderCart();


        /*
        * Đóng modal.
        */

        closeQuickAddModal();

    }

    function createOrderToolModal() {

        if (
            document.getElementById(
                'mini-order-tool-modal'
            )
        ) {
            return;
        }


        const html = `

            <div
                id="mini-order-tool-modal"
                class="modal fade"
                tabindex="-1"
                aria-hidden="true">

                <div
                    class="modal-dialog modal-dialog-centered modal-sm">

                    <div
                        class="modal-content">

                        <div
                            class="modal-header">

                            <h5
                                id="mini-order-tool-modal-title"
                                class="modal-title">
                                Cài đặt đơn hàng
                            </h5>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Đóng">
                            </button>

                        </div>


                        <div
                            class="modal-body">

                            <div
                                id="mini-order-tool-money-field">

                                <label
                                    id="mini-order-tool-money-label"
                                    class="form-label">
                                    Số tiền
                                </label>

                                <div
                                    class="input-group">

                                    <input
                                        type="text"
                                        id="mini-order-tool-money"
                                        class="form-control"
                                        inputmode="numeric"
                                        autocomplete="off"
                                        value="0">

                                    <span
                                        class="input-group-text">
                                        đ
                                    </span>

                                </div>

                            </div>


                            <div
                                id="mini-order-tool-note-field"
                                style="display:none;">

                                <label
                                    for="mini-order-tool-note"
                                    class="form-label">
                                    Ghi chú đơn hàng
                                </label>

                                <input
                                    type="text"
                                    id="mini-order-tool-note"
                                    class="form-control"
                                    maxlength="500"
                                    autocomplete="off"
                                    placeholder="Nhập ghi chú...">

                            </div>

                        </div>


                        <div
                            class="modal-footer">

                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">
                                HỦY
                            </button>

                            <button
                                type="button"
                                id="mini-order-tool-save"
                                class="btn btn-success">
                                LƯU
                            </button>

                        </div>

                    </div>

                </div>

            </div>

        `;


        document.body.insertAdjacentHTML(
            'beforeend',
            html
        );

    }

    function openOrderToolModal(type) {

        createOrderToolModal();


        const modalElement =
            document.getElementById(
                'mini-order-tool-modal'
            );


        const title =
            document.getElementById(
                'mini-order-tool-modal-title'
            );


        const moneyField =
            document.getElementById(
                'mini-order-tool-money-field'
            );


        const moneyLabel =
            document.getElementById(
                'mini-order-tool-money-label'
            );


        const moneyInput =
            document.getElementById(
                'mini-order-tool-money'
            );


        const noteField =
            document.getElementById(
                'mini-order-tool-note-field'
            );


        const noteInput =
            document.getElementById(
                'mini-order-tool-note'
            );


        if (
            !modalElement ||
            !title ||
            !moneyInput ||
            !noteInput
        ) {
            return;
        }


        miniOrderToolType =
            type;


        /*
        * SHIP
        */
        if (
            type === 'shipping'
        ) {

            title.textContent =
                'Phí giao hàng';

            moneyLabel.textContent =
                'Phí ship';

            moneyField.style.display =
                '';

            noteField.style.display =
                'none';

            moneyInput.value =
                miniOrderDeliveryFee ||
                0;

        }


        /*
        * GIẢM
        */
        else if (
            type === 'discount'
        ) {

            title.textContent =
                'Giảm giá đơn hàng';

            moneyLabel.textContent =
                'Giảm giá';

            moneyField.style.display =
                '';

            noteField.style.display =
                'none';

            moneyInput.value =
                miniOrderDiscount ||
                0;

        }


        /*
        * GHI CHÚ
        */
        else if (
            type === 'note'
        ) {

            title.textContent =
                'Ghi chú đơn hàng';

            moneyField.style.display =
                'none';

            noteField.style.display =
                '';

            noteInput.value =
                miniOrderNote ||
                '';

        }


        const modal =
            bootstrap.Modal.getOrCreateInstance(
                modalElement
            );


        modal.show();

    }


    /* =========================================================
     * CREATE PRODUCT MODAL
     * ========================================================= */

    function createProductModal() {

        const existing =
            document.getElementById(
                'mini-product-modal'
            );


        if (existing) {

            miniModal =
                existing;

            return;

        }


        const html = `

            <div
                id="mini-product-modal"
                class="mini-modal"
                aria-hidden="true">


                <div
                    class="mini-modal-overlay"
                    data-mini-modal-close>
                </div>


                <div
                    class="mini-modal-dialog"
                    role="dialog"
                    aria-modal="true">


                    <div
                        class="mini-modal-header">

                        <div>

                            <div
                                id="mini-modal-product-name"
                                class="mini-modal-header-name">
                                Chi tiết món
                            </div>

                            <div
                                id="mini-modal-product-price"
                                class="mini-modal-header-price">
                                0đ
                            </div>

                        </div>


                        <button
                            type="button"
                            class="mini-modal-close"
                            data-mini-modal-close>
                            ×
                        </button>

                    </div>


                    <div
                        class="mini-modal-body">


                        <div
                            id="mini-modal-options"
                            class="mini-modal-field mini-modal-options-center"
                            style="display:none;">

                            <div
                                id="mini-option-list"
                                class="btn-group"
                                role="group"
                                aria-label="Chọn size">
                            </div>

                        </div>


                        <div
                            class="mini-modal-two-col-top">


                            <!-- SỐ LƯỢNG -->

                            <div
                                class="mini-modal-field">

                                <label>
                                    Số lượng
                                </label>


                                <div
                                    class="mini-modal-quantity">

                                    <button
                                        type="button"
                                        id="mini-qty-minus"
                                        class="mini-qty-btn">
                                        −
                                    </button>


                                    <input
                                        type="number"
                                        id="mini-qty"
                                        class="mini-qty-input"
                                        value="1"
                                        min="1"
                                        inputmode="numeric">


                                    <button
                                        type="button"
                                        id="mini-qty-plus"
                                        class="mini-qty-btn">
                                        +
                                    </button>

                                </div>

                            </div>


                            <!-- GIẢM -->

                            <div
                                class="mini-modal-field">

                                <label>
                                    Giảm giá
                                </label>


                                <div
                                    class="mini-discount-control">


                                    <div class="mini-discount-stepper">

                                        <div class="mini-input-money">

                                            <input
                                                type="text"
                                                id="pdiscount"
                                                class="mini-modal-input"
                                                value="0"
                                                inputmode="numeric"
                                                autocomplete="off">

                                            <span
                                                id="mini-discount-suffix">
                                                đ
                                            </span>

                                        </div>

                                    </div>


                                    <div
                                        class="mini-discount-type">

                                        <button
                                            type="button"
                                            class="mini-discount-type-btn active"
                                            data-discount-type="amount">
                                            đ
                                        </button>


                                        <button
                                            type="button"
                                            class="mini-discount-type-btn"
                                            data-discount-type="percent">
                                            %
                                        </button>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <div
                            class="mini-modal-two-col-bottom">


                            <!-- GHI CHÚ -->

                            <div
                                class="mini-modal-field">

                                <label
                                    for="icomment">
                                    Ghi chú món
                                </label>


                                <input
                                    type="text"
                                    id="icomment"
                                    class="mini-modal-input form-control text-uppercase"
                                    maxlength="500"
                                    autocomplete="off"
                                    placeholder="Ghi chú...">

                            </div>


                            <!-- TÊN LY -->

                            <div
                                class="mini-modal-field">

                                <label
                                    for="icommentname">
                                    Tên dán ly
                                </label>


                                <input
                                    type="text"
                                    id="icommentname"
                                    class="mini-modal-input form-control text-uppercase"
                                    maxlength="100"
                                    autocomplete="off"
                                    placeholder="Tên khách...">

                            </div>

                        </div>


                        <div
                            class="mini-modal-section">

                            <div
                                class="mini-modal-label">
                                Ghi chú nhanh
                            </div>


                            <div
                                id="mini-quick-notes-list"
                                class="mini-note-grid">
                            </div>

                        </div>


                    </div>


                    <div
                        class="mini-modal-footer">

                        <button
                            type="button"
                            class="mini-modal-btn mini-modal-btn-cancel"
                            data-mini-modal-close>
                            HỦY
                        </button>


                        <button
                            type="button"
                            id="mini-modal-add"
                            class="mini-modal-btn mini-modal-btn-primary">
                            THÊM VÀO ĐƠN
                        </button>

                    </div>

                </div>

            </div>

        `;


        document.body.insertAdjacentHTML(
            'beforeend',
            html
        );

        const commentInput =
            document.getElementById(
                'icomment'
            );

        const commentNameInput =
            document.getElementById(
                'icommentname'
            );


        if (commentInput) {
            commentInput.addEventListener(
                'input',
                function () {
                    this.value =
                        this.value.toUpperCase();
                }
            );
        }


        if (commentNameInput) {
            commentNameInput.addEventListener(
                'input',
                function () {
                    this.value =
                        this.value.toUpperCase();
                }
            );
        }


        miniModal =
            document.getElementById(
                'mini-product-modal'
            );


        renderQuickNotes();


        /*
         * Close.
         */

        miniModal
            .querySelectorAll(
                '[data-mini-modal-close]'
            )
            .forEach(
                function (button) {

                    button.addEventListener(
                        'click',
                        closeProductModal
                    );

                }
            );


        /*
         * Quantity.
         */

        document
            .getElementById(
                'mini-qty-minus'
            )
            .addEventListener(
                'click',
                function () {

                    changeModalQuantity(
                        -1
                    );

                }
            );


        document
            .getElementById(
                'mini-qty-plus'
            )
            .addEventListener(
                'click',
                function () {

                    changeModalQuantity(
                        1
                    );

                }
            );


        document
            .getElementById(
                'mini-qty'
            )
            .addEventListener(
                'change',
                normalizeModalQuantity
            );

        /*
        * Số lượng:
        * Khi focus vào ô,
        * tự động chọn toàn bộ nội dung.
        */

        document
            .getElementById(
                'mini-qty'
            )
            .addEventListener(
                'focus',
                function () {

                    const input = this;

                    setTimeout(
                        function () {

                            input.select();

                        },
                        0
                    );

                }
            );

        /*
        * Giảm giá:
        * Khi focus vào ô,
        * tự động chọn toàn bộ nội dung.
        */

        document
            .getElementById(
                'pdiscount'
            )
            .addEventListener(
                'focus',
                function () {

                    const input = this;

                    setTimeout(
                        function () {

                            input.select();

                        },
                        0
                    );

                }
            );


        /*
         * Add.
         */

        document
            .getElementById(
                'mini-modal-add'
            )
            .addEventListener(
                'click',
                handleModalAdd
            );


        /*
         * ESC.
         */

        document.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key ===
                        'Escape' &&
                    miniModal &&
                    miniModal.classList
                        .contains('show')
                ) {

                    closeProductModal();

                }

            }
        );

    }


    /* =========================================================
     * DISCOUNT VALUE
     * ========================================================= */

    function getDiscountValue() {

        const input =
            document.getElementById(
                'pdiscount'
            );


        let value =
            input
                ? parseFloat(
                    input.value
                ) || 0
                : 0;


        value =
            Math.max(
                0,
                value
            );


        if (
            miniDiscountType ===
            'percent'
        ) {

            value =
                Math.min(
                    100,
                    value
                );

        }


        return value;

    }


    /* =========================================================
     * MODAL PRICE
     * ========================================================= */

    function updateMiniModalPrice() {

        const value =
            getDiscountValue();

        const currentPrice = miniBasePrice + miniVariantPrice;

        let discount;


        if (
            miniDiscountType ===
            'percent'
        ) {

            discount = currentPrice * value / 100;

        } else {

            discount =
                value;

        }


        discount = Math.min(currentPrice, discount);


        const priceEl =
            document.getElementById(
                'mini-modal-product-price'
            );


        if (priceEl) {

            priceEl.textContent = money(currentPrice - discount);

        }


        const suffix =
            document.getElementById(
                'mini-discount-suffix'
            );


        if (suffix) {

            suffix.textContent =
                miniDiscountType ===
                'percent'
                    ? '%'
                    : 'đ';

        }

    }


    /* =========================================================
     * DISCOUNT TYPE
     * ========================================================= */

    function setMiniDiscountType(
        type
    ) {

        miniDiscountType =
            type === 'percent'
                ? 'percent'
                : 'amount';


        const input =
            document.getElementById(
                'pdiscount'
            );


        if (input) {

            input.value = 0;

            input.max =
                miniDiscountType ===
                'percent'
                    ? '100'
                    : '';

            input.step =
                miniDiscountType ===
                'percent'
                    ? '1'
                    : '1000';

        }


        if (miniModal) {

            miniModal
                .querySelectorAll(
                    '.mini-discount-type-btn'
                )
                .forEach(
                    function (button) {

                        button.classList.toggle(
                            'active',

                            button.dataset
                                .discountType ===
                            miniDiscountType
                        );

                    }
                );

        }


        updateMiniModalPrice();

    }


    /* =========================================================
     * DISCOUNT + / -
     * ========================================================= */

    function changeMiniDiscount(
        direction
    ) {

        const input =
            document.getElementById(
                'pdiscount'
            );


        if (!input) {
            return;
        }


        const step =
            miniDiscountType ===
            'percent'
                ? 1
                : 1000;


        let value =
            (
                parseFloat(
                    input.value
                ) || 0
            ) +
            direction *
            step;


        value =
            Math.max(
                0,
                value
            );


        if (
            miniDiscountType ===
            'percent'
        ) {

            value =
                Math.min(
                    100,
                    value
                );

        }


        input.value =
            value;


        updateMiniModalPrice();

    }


    /* =========================================================
     * COMMENT
     * ========================================================= */

    function updateMiniNoteField() {

        const input =
            document.getElementById(
                'icomment'
            );


        if (
            !input ||
            !miniModal
        ) {

            return;

        }


        const quick = [];

        const known = [];


        miniModal
            .querySelectorAll(
                '.chkComment'
            )
            .forEach(
                function (box) {

                    const value =
                        box.value.trim();


                    if (value) {

                        known.push(
                            value
                        );

                    }


                    if (
                        box.checked &&
                        value
                    ) {

                        quick.push(
                            value
                        );

                    }

                }
            );


        const manual =
            input.value
                .split(',')
                .map(
                    function (value) {
                        return value.trim();
                    }
                )
                .filter(
                    function (value) {

                        return (
                            value &&
                            known.indexOf(
                                value
                            ) === -1
                        );

                    }
                );


        input.value =
            manual
                .concat(
                    quick
                )
                .join(', ');

    }

    function renderMiniOptions(
        card,
        selectedOption = ''
    ) {

        const wrapper =
            document.getElementById(
                'mini-modal-options'
            );

        const list =
            document.getElementById(
                'mini-option-list'
            );


        if (!wrapper || !list) {
            return;
        }


        let variants = [];


        /*
        * Lấy variants từ product card.
        */
        try {

            variants =
                JSON.parse(
                    card.dataset.variants ||
                    '[]'
                );

        } catch (error) {

            variants = [];

        }


        /*
        * Không có size
        * => ẩn cụm Size.
        */
        if (
            !Array.isArray(variants) ||
            !variants.length
        ) {

            wrapper.style.display =
                'none';

            list.innerHTML =
                '';

            return;

        }

        /*
        * Nếu chưa có Size được chọn:
        * mặc định lấy variant đầu tiên.
        */
        if (!selectedOption) {

            selectedOption =
                String(
                    variants[0].id || ''
                );

        }


        /*
        * Hiện cụm Size.
        */
        wrapper.style.display =
            'block';


        const selectedVariant =
            variants.find(
                function (variant) {

                    return (
                        String(
                            variant.id
                        ) ===
                        String(
                            selectedOption
                        )
                    );

                }
            );


        miniSelectedVariantId =
            selectedVariant
                ? String(
                    selectedVariant.id
                )
                : '';


        miniVariantPrice =
            selectedVariant
                ? (
                    Number(
                        selectedVariant.price
                    ) || 0
                )
                : 0;

        /*
        * Tạo button Bootstrap.
        */
        list.innerHTML =
            variants
                .map(
                    function (variant, index) {

                        const id =
                            String(
                                variant.id ||
                                ''
                            );


                        const name =
                            String(
                                variant.name ||
                                ''
                            ).trim();


                        if (!name) {
                            return '';
                        }


                        const active =
                            selectedOption
                                ? id === String(selectedOption)
                                : index === 0;


                        return `

                            <button
                                type="button"
                                class="btn btn-outline-primary ${
                                    active
                                        ? 'active'
                                        : ''
                                }"
                                data-mini-option-id="${escapeHtml(id)}"
                                data-mini-option-price="${escapeHtml(
                                    variant.price || 0
                                )}">

                                ${escapeHtml(name)}

                            </button>

                        `;

                    }
                )
                .join('');


        /*
        * Click chọn Size.
        */
        list
            .querySelectorAll(
                '[data-mini-option-id]'
            )
            .forEach(
                function (button) {

                    button.addEventListener(
                        'click',
                        function (event) {

                            event.preventDefault();
                            event.stopPropagation();


                            /*
                            * Bỏ active ở tất cả Size.
                            */
                            list
                                .querySelectorAll(
                                    '[data-mini-option-id]'
                                )
                                .forEach(
                                    function (item) {

                                        item.classList.remove(
                                            'active'
                                        );

                                    }
                                );


                            /*
                            * Active đúng Size vừa chọn.
                            */
                            this.classList.add(
                                'active'
                            );


                            /*
                            * Lưu variant đang chọn.
                            */
                            miniSelectedVariantId =
                                this.dataset
                                    .miniOptionId || '';


                            miniVariantPrice =
                                Number(
                                    this.dataset
                                        .miniOptionPrice
                                ) || 0;


                            /*
                            * Cập nhật giá header modal ngay.
                            */
                            updateMiniModalPrice();

                        }
                    );

                }
            );

    }

    function miniDisplayProductName(name) {
        name =
            String(
                name || ''
            ).trim();


        return name.replace(
            /^[A-Za-z]_/,
            ''
        );

    }

    /* =========================================================
     * OPEN MODAL
     * ========================================================= */

   function openProductModal(
        card,
        editItem = null
    ) {

        createProductModal();


        miniBasePrice = parseFloat(card.dataset.price || 0) || 0;

        miniVariantPrice = 0;

        miniSelectedVariantId = '';

        miniModal.dataset.productId =
            card.dataset.productId ||
            '';


        document
            .getElementById(
                'mini-modal-product-name'
            )
            .textContent =
            miniDisplayProductName(
                card.dataset.name ||
                card
                    .querySelector(
                        '.mini-product-name'
                    )
                    ?.textContent
                    .trim() ||
                ''
            );


        /*
        * Xác định đang:
        * - thêm món mới
        * - hay chỉnh sửa row cũ
        */
        miniEditingRowId =
            editItem
                ? editItem.rowId
                : null;

        renderMiniOptions(
            card,
            editItem
                ? editItem.option
                : ''
        );


        miniModal.dataset.editRowId =
            editItem
                ? editItem.rowId
                : '';


        /*
        * Mặc định là form thêm món mới.
        */
        document
            .getElementById(
                'mini-qty'
            )
            .value =
            editItem
                ? editItem.qty
                : 1;


        setMiniDiscountType(
            editItem &&
            editItem.discountType
                ? editItem.discountType
                : 'amount'
        );


        /*
        * Sau setMiniDiscountType()
        * mới set lại giá trị discount,
        * vì hàm trên reset input về 0.
        */
        document
            .getElementById(
                'pdiscount'
            )
            .value =
            editItem
                ? (
                    Number(
                        editItem.discount
                    ) || 0
                )
                : 0;


        /*
        * Ghi chú món.
        */
        document
            .getElementById(
                'icomment'
            )
            .value =
            editItem
                ? (
                    editItem.comment ||
                    ''
                )
                : '';


        /*
        * Tên dán ly.
        */
        document
            .getElementById(
                'icommentname'
            )
            .value =
            editItem
                ? (
                    editItem.commentName ||
                    ''
                )
                : '';


        /*
        * Reset checkbox.
        */
        miniModal
            .querySelectorAll(
                '.chkComment'
            )
            .forEach(
                function (box) {

                    box.checked =
                        false;

                }
            );


        /*
        * Nếu đang edit:
        * tick lại những ghi chú nhanh
        * đã có trong món.
        */
        if (editItem) {

            const currentNotes =
                String(
                    editItem.comment ||
                    ''
                )
                .split(',')
                .map(
                    function (value) {
                        return value
                            .trim()
                            .toLowerCase();
                    }
                )
                .filter(Boolean);


            miniModal
                .querySelectorAll(
                    '.chkComment'
                )
                .forEach(
                    function (box) {

                        const value =
                            box.value
                                .trim()
                                .toLowerCase();


                        box.checked =
                            currentNotes.indexOf(
                                value
                            ) !== -1;

                    }
                );

        }


        /*
        * Đổi chữ nút theo trạng thái.
        */
        const modalAddButton =
            document.getElementById(
                'mini-modal-add'
            );


        if (modalAddButton) {

            modalAddButton.textContent =
                editItem
                    ? 'CẬP NHẬT'
                    : 'THÊM VÀO ĐƠN';

        }


        /*
         * Discount input.
         */

        const discountInput =
            document.getElementById(
                'pdiscount'
            );


        discountInput.oninput =
            function () {

                /*
                * Khi đang gõ:
                * Không tự sửa giá trị input.
                *
                * Nếu vừa gõ "2" mà lập tức
                * làm tròn thành 0 thì iPhone
                * sẽ không thể nhập "2000".
                */

                let value =
                    parseFloat(
                        this.value
                    );


                if (
                    Number.isNaN(value)
                ) {

                    value = 0;

                }


                value =
                    Math.max(
                        0,
                        value
                    );


                if (
                    miniDiscountType ===
                    'percent'
                ) {

                    value =
                        Math.min(
                            100,
                            value
                        );

                }


                /*
                * Chỉ cập nhật giá tạm tính.
                * Không ghi đè this.value.
                */

                updateMiniModalPrice();

            };

        discountInput.addEventListener(
            'blur',
            function () {

                let value =
                    parseFloat(
                        this.value
                    ) || 0;


                value =
                    Math.max(
                        0,
                        value
                    );


                if (
                    miniDiscountType ===
                    'percent'
                ) {

                    value =
                        Math.min(
                            100,
                            Math.round(
                                value
                            )
                        );

                } else {

                    value =
                        Math.round(
                            value /
                            1000
                        ) *
                        1000;

                }


                this.value =
                    value;


                updateMiniModalPrice();

            }
        );


        /*
         * Discount type.
         */

        miniModal
            .querySelectorAll(
                '.mini-discount-type-btn'
            )
            .forEach(
                function (button) {

                    button.onclick =
                        function () {

                            setMiniDiscountType(
                                this.dataset
                                    .discountType
                            );

                        };

                }
            );


        /*
         * Quick notes.
         */

        miniModal
            .querySelectorAll(
                '.chkComment'
            )
            .forEach(
                function (box) {

                    box.onchange =
                        updateMiniNoteField;

                }
            );


        updateMiniModalPrice();


        miniModal.classList.add(
            'show'
        );


        miniModal.setAttribute(
            'aria-hidden',
            'false'
        );


        document.body.classList.add(
            'mini-modal-open'
        );

    }


    /* =========================================================
     * CLOSE MODAL
     * ========================================================= */

    function closeProductModal() {

        if (!miniModal) {
            return;
        }


        miniModal.classList.remove(
            'show'
        );


        miniModal.setAttribute(
            'aria-hidden',
            'true'
        );


        document.body.classList.remove(
            'mini-modal-open'
        );

    }

    /* =========================================================
    * CLOSE CART WHEN TOUCH / CLICK OUTSIDE
    *
    * Khi cart đang mở:
    * - Chạm ngoài cart -> đóng cart
    * - Chặn luôn click tiếp theo
    * - Không cho product bên dưới nhận click
    * ========================================================= */

    let miniSuppressNextClick = false;


    document.addEventListener(
        'pointerdown',
        function (event) {

            if (
                window.innerWidth > 800
            ) {
                return;
            }


            if (!miniOrder) {
                return;
            }


            /*
            * Cart chưa mở.
            */
            if (
                !miniOrder.classList.contains(
                    'mini-order-open'
                )
            ) {
                return;
            }


            /*
            * Chạm bên trong cart
            * => giữ nguyên.
            */
            if (
                miniOrder.contains(
                    event.target
                )
            ) {
                return;
            }


            /*
            * ĐÁNH DẤU:
            * click phát sinh sau pointerdown này
            * phải bị bỏ qua.
            */
            miniSuppressNextClick = true;


            /*
            * Đóng cart.
            */
            miniOrder.classList.remove(
                'mini-order-open'
            );


            /*
            * Chặn pointer event hiện tại.
            */
            event.preventDefault();
            event.stopPropagation();

        },
        true
    );


    /*
    * Chặn click được sinh ra sau khi
    * người dùng chạm ngoài cart.
    */
    document.addEventListener(
        'click',
        function (event) {

            if (
                !miniSuppressNextClick
            ) {
                return;
            }


            miniSuppressNextClick = false;


            /*
            * Không cho click xuyên xuống
            * product card.
            */
            event.preventDefault();
            event.stopPropagation();

        },
        true
    );


    /* =========================================================
     * MODAL QUANTITY
     * ========================================================= */

    function changeModalQuantity(
        amount
    ) {

        const input =
            document.getElementById(
                'mini-qty'
            );


        if (!input) {
            return;
        }


        input.value =
            Math.max(
                1,

                (
                    parseInt(
                        input.value,
                        10
                    ) || 1
                ) +
                amount
            );

    }


    function normalizeModalQuantity() {

        const input =
            document.getElementById(
                'mini-qty'
            );


        if (!input) {
            return;
        }


        input.value =
            Math.max(
                1,

                parseInt(
                    input.value,
                    10
                ) || 1
            );

    }


    /* =========================================================
     * MODAL ADD
     * ========================================================= */

    function handleModalAdd() {

        if (!miniModal) {
            return;
        }


        const productId =
            String(
                miniModal.dataset
                    .productId ||
                ''
            );


        const card =
            grid.querySelector(
                '.mini-product-card[data-product-id="' +
                CSS.escape(productId) +
                '"]'
            );


        if (!card) {
            return;
        }


        const qty =
            Math.max(
                1,

                parseInt(
                    document
                        .getElementById(
                            'mini-qty'
                        )
                        .value,
                    10
                ) || 1
            );


        const discount =
            getDiscountValue();


        const comment =
            document
                .getElementById(
                    'icomment'
                )
                ?.value
                .trim() ||
            '';


        const commentName =
            document
                .getElementById(
                    'icommentname'
                )
                ?.value
                .trim() ||
            '';

        /*
        * =====================================================
        * LẤY SIZE ĐANG CHỌN
        * =====================================================
        */

        const selectedOptionButton =
            miniModal.querySelector(
                '[data-mini-option-id].active'
            );


        const selectedOption =
            selectedOptionButton
                ?.dataset
                .miniOptionId ||
            '';


        const selectedOptionName =
            selectedOptionButton
                ?.textContent
                .trim() ||
            '';


        const selectedVariantPrice =
            Number(
                selectedOptionButton
                    ?.dataset
                    .miniOptionPrice
            ) || 0;


        /*
        * =====================================================
        * EDIT ROW CŨ
        * =====================================================
        */

        if (miniEditingRowId) {

            const index =
                cart.findIndex(
                    function (item) {

                        return (
                            item.rowId ===
                            miniEditingRowId
                        );

                    }
                );


            if (index !== -1) {

                cart[index].qty =
                    qty;


                cart[index].discount =
                    discount;


                cart[index].discountType =
                    miniDiscountType;


                cart[index].comment =
                    comment;


                cart[index].commentName =
                    commentName;


                /*
                * Cập nhật Size.
                */
                cart[index].option =
                    selectedOption;


                cart[index].optionName =
                    selectedOptionName;


                /*
                * Cập nhật lại giá món + Size.
                */
                cart[index].price =
                    (
                        parseFloat(
                            card.dataset.price || 0
                        ) || 0
                    ) +
                    selectedVariantPrice;


                renderCart();

            }


        } else {

            /*
            * =================================================
            * THÊM MÓN MỚI
            * =================================================
            */

            addToCart(
                card,
                qty,
                {
                    discount:
                        discount,

                    discountType:
                        miniDiscountType,

                    comment:
                        comment,

                    commentName:
                        commentName,

                    option:
                        selectedOption,

                    optionName:
                        selectedOptionName,

                    variantPrice:
                        selectedVariantPrice
                }
            );

        }


        closeProductModal();

    }


    /* =========================================================
     * PRODUCT EVENTS
     * ========================================================= */

    grid
        .querySelectorAll(
            '.mini-product-card'
        )
        .forEach(
            function (card) {


                /*
                 * Click thân card:
                 * thêm ngay 1 dòng.
                 */

                card.addEventListener(
                    'click',
                    function (event) {

                        if (
                            event.target.closest(
                                '.mini-product-edit'
                            )
                        ) {

                            return;

                        }


                        const card =
                            this;


                        /*
                        * Mặc định:
                        * không có option.
                        */
                        let option = '';

                        let optionName = '';

                        let variantPrice = 0;


                        /*
                        * Nếu món có variants:
                        * lấy variant ĐẦU TIÊN.
                        */
                        try {

                            const variants =
                                JSON.parse(
                                    card.dataset.variants ||
                                    '[]'
                                );


                            if (
                                Array.isArray(variants) &&
                                variants.length
                            ) {

                                const firstVariant =
                                    variants[0];


                                option =
                                    String(
                                        firstVariant.id ||
                                        ''
                                    );


                                optionName =
                                    String(
                                        firstVariant.name ||
                                        ''
                                    ).trim();


                                variantPrice =
                                    Number(
                                        firstVariant.price
                                    ) || 0;

                            }

                        } catch (error) {

                            option = '';

                            optionName = '';

                            variantPrice = 0;

                        }


                        /*
                        * Thêm món trực tiếp.
                        * Nếu có option:
                        * tự lấy Size đầu tiên.
                        */
                        addToCart(
                            card,
                            1,
                            {
                                option:
                                    option,

                                optionName:
                                    optionName,

                                variantPrice:
                                    variantPrice
                            }
                        );

                    }
                );


                /*
                 * Click edit:
                 * mở modal.
                 */

                const edit =
                    card.querySelector(
                        '.mini-product-edit'
                    );


                if (edit) {

                    edit.addEventListener(
                        'click',
                        function (event) {

                            event.preventDefault();

                            event.stopPropagation();


                            openProductModal(
                                card
                            );

                        }
                    );

                }

            }
        );


    /* =========================================================
    * CART EVENTS
    * ========================================================= */

    const cartList =
        document.getElementById(
            'mini-cart-list'
        );


    if (cartList) {

        cartList.addEventListener(
            'click',
            function (event) {

                /*
                * Tìm row được click.
                */
                const row =
                    event.target.closest(
                        '.mini-cart-row'
                    );


                /*
                * Không nằm trong row thì bỏ qua.
                */
                if (!row) {
                    return;
                }


                /*
                * Tìm button nếu click vào button.
                */
                const button =
                    event.target.closest(
                        '[data-cart-action]'
                    );


                /* =================================================
                * CÓ BUTTON -> XỬ LÝ BUTTON
                * ================================================= */

                if (button) {

                    const action =
                        button.dataset.cartAction;


                    /*
                    * XÓA MÓN
                    */
                    if (
                        action === 'delete'
                    ) {

                        const index =
                            cart.findIndex(
                                function (item) {

                                    return (
                                        String(
                                            item.rowId
                                        ) ===
                                        String(
                                            row.dataset.rowId
                                        )
                                    );

                                }
                            );


                        if (index !== -1) {

                            cart.splice(
                                index,
                                1
                            );


                            renderCart();

                        }


                        return;
                    }


                    /*
                    * TĂNG / GIẢM SỐ LƯỢNG
                    *
                    * Chỉ xử lý quantity,
                    * không mở modal.
                    */
                    if (
                        action === 'plus' ||
                        action === 'minus'
                    ) {

                        changeCartQty(
                            row.dataset.rowId,

                            action === 'plus'
                                ? 1
                                : -1
                        );


                        return;
                    }


                    /*
                    * Button khác nếu có:
                    * không mở modal.
                    */
                    return;

                }


                /* =================================================
                * CLICK BẤT KỲ VÙNG NÀO CỦA ROW -> EDIT
                * ================================================= */

                /*
                * Nếu row đang swipe mở nút XÓA
                * thì click lần này chỉ đóng trạng thái swipe.
                */
                if (
                    row.classList.contains(
                        'swiped'
                    )
                ) {

                    row.classList.remove(
                        'swiped'
                    );

                    return;

                }


                /*
                * Lấy rowId.
                */
                const rowId =
                    row.dataset.rowId;


                /*
                * Tìm item tương ứng trong cart.
                */
                const item =
                    cart.find(
                        function (cartItem) {

                            return (
                                String(
                                    cartItem.rowId
                                ) ===
                                String(
                                    rowId
                                )
                            );

                        }
                    );


                if (!item) {
                    return;
                }


                /*
                * Tìm product card tương ứng.
                */
                const card =
                    grid.querySelector(
                        '.mini-product-card[data-product-id="' +
                        CSS.escape(
                            String(
                                item.id
                            )
                        ) +
                        '"]'
                    );


                if (!card) {
                    return;
                }


                /*
                * Mở modal EDIT.
                */
                openProductModal(
                    card,
                    item
                );

            }
        );

    }
    
    /* =========================================================
    * CART SWIPE / DRAG TO DELETE
    *
    * Dùng Pointer Events:
    * - Touch mobile
    * - Touch laptop
    * - Mouse desktop
    * ========================================================= */

    let cartPointerRow = null;
    let cartPointerStartX = 0;
    let cartPointerStartY = 0;
    let cartPointerActive = false;


    /* ---------------------------------------------------------
    * Đóng các row đang mở
    * --------------------------------------------------------- */

    function closeSwipedCartRows(exceptRow) {

        if (!cartList) {
            return;
        }

        cartList
            .querySelectorAll(
                '.mini-cart-row.swiped'
            )
            .forEach(
                function (row) {

                    if (row !== exceptRow) {

                        row.classList.remove(
                            'swiped'
                        );

                    }

                }
            );
    }


    /* =========================================================
    * POINTER DOWN
    * Touch + Mouse đều vào đây
    * ========================================================= */

    if (cartList) {

        cartList.addEventListener(
            'pointerdown',
            function (event) {

                /*
                * Chỉ xử lý:
                * - touch
                * - pen
                * - chuột trái
                */

                if (
                    event.pointerType === 'mouse' &&
                    event.button !== 0
                ) {

                    return;

                }


                const row =
                    event.target.closest(
                        '.mini-cart-row'
                    );


                if (!row) {
                    return;
                }


                /*
                * Nếu đang bấm vào button
                * thì không bắt đầu gesture.
                */

                if (
                    event.target.closest(
                        'button'
                    )
                ) {

                    return;

                }


                cartPointerRow = row;

                cartPointerStartX =
                    event.clientX;

                cartPointerStartY =
                    event.clientY;

                cartPointerActive = true;


                /*
                * Pointer capture giúp laptop
                * cảm ứng không bị mất pointer
                * khi ngón tay kéo ra khỏi row.
                */

                if (
                    row.setPointerCapture
                ) {

                    try {

                        row.setPointerCapture(
                            event.pointerId
                        );

                    } catch (e) {}

                }

            }
        );


        /* =====================================================
        * POINTER MOVE
        * ===================================================== */

        cartList.addEventListener(
            'pointermove',
            function (event) {

                if (
                    !cartPointerActive ||
                    !cartPointerRow
                ) {

                    return;

                }


                const deltaX =
                    event.clientX -
                    cartPointerStartX;

                const deltaY =
                    event.clientY -
                    cartPointerStartY;


                /*
                * Chưa đủ khoảng cách
                */

                if (
                    Math.abs(deltaX) < 10 &&
                    Math.abs(deltaY) < 10
                ) {

                    return;

                }

                /*
                * Nếu đang cuộn dọc thì bỏ qua.
                */

                if (
                    Math.abs(deltaY) >
                    Math.abs(deltaX)
                ) {

                    return;

                }


                /*
                * Vuốt ngang.
                */

                if (
                    event.cancelable
                ) {

                    event.preventDefault();

                }

            }
        );


        /* =====================================================
        * POINTER UP
        * ===================================================== */

        cartList.addEventListener(
            'pointerup',
            function (event) {

                if (
                    !cartPointerActive ||
                    !cartPointerRow
                ) {

                    return;

                }


                const row =
                    cartPointerRow;


                const deltaX =
                    event.clientX -
                    cartPointerStartX;

                const deltaY =
                    event.clientY -
                    cartPointerStartY;


                cartPointerActive = false;


                /*
                * Chỉ xử lý swipe ngang.
                */

                if (
                    Math.abs(deltaX) <=
                    Math.abs(deltaY)
                ) {

                    cartPointerRow = null;

                    return;

                }


                /*
                * Vuốt sang trái
                * => mở XÓA
                */

                if (
                    deltaX < -50
                ) {

                    closeSwipedCartRows(
                        row
                    );

                    row.classList.add(
                        'swiped'
                    );

                }


                /*
                * Vuốt sang phải
                * => đóng XÓA
                */

                else if (
                    deltaX > 30
                ) {

                    row.classList.remove(
                        'swiped'
                    );

                }


                cartPointerRow = null;

            }
        );


        /* =====================================================
        * POINTER CANCEL
        * ===================================================== */

        cartList.addEventListener(
            'pointercancel',
            function () {

                cartPointerActive = false;

                if (cartPointerRow) {

                    cartPointerRow.classList.remove(
                        'dragging'
                    );

                }

                cartPointerRow = null;

            }
        );

    }


    /* =========================================================
    * CLICK RA NGOÀI CART
    * ========================================================= */

    document.addEventListener(
        'click',
        function (event) {

            if (
                event.target.closest(
                    '.mini-cart-row'
                )
            ) {

                return;

            }


            closeSwipedCartRows(
                null
            );

        }
    );


    /* =========================================================
     * MOBILE CART TOGGLE
     * ========================================================= */

    const miniOrder =
        document.querySelector(
            '.mini-order'
        );


    const miniOrderInfo =
        document.querySelector(
            '.mini-order-info-panel'
        );


    if (
        miniOrder &&
        miniOrderInfo
    ) {

        miniOrderInfo.addEventListener(
            'click',
            function () {

                if (
                    window.innerWidth <=
                    800
                ) {

                    miniOrder.classList.toggle(
                        'mini-order-open'
                    );

                }

            }
        );

    }


    /* =========================================================
     * HEADER MODE
     * ========================================================= */

    document
        .querySelectorAll(
            '.mini-header-mode'
        )
        .forEach(
            function (button) {

                button.addEventListener(
                    'click',
                    function () {


                        document
                            .querySelectorAll(
                                '.mini-header-mode'
                            )
                            .forEach(
                                function (item) {

                                    item.classList
                                        .remove(
                                            'active'
                                        );

                                }
                            );


                        this.classList.add(
                            'active'
                        );


                        const mode =
                            this.dataset
                                .orderMode ||
                            'table';


                        const modeEl =
                            document.getElementById(
                                'mini-order-info-mode'
                            );


                        const mainEl =
                            document.getElementById(
                                'mini-order-info-main'
                            );


                        const subEl =
                            document.getElementById(
                                'mini-order-info-sub'
                            );


                        if (modeEl) {

                            modeEl.textContent =
                                mode === 'table'
                                    ? 'BÀN'
                                    : mode === 'dinein'
                                        ? 'TẠI CHỖ'
                                        : 'MANG ĐI';


                            modeEl.dataset.mode =
                                mode;

                        }


                        if (
                            mode ===
                            'table'
                        ) {

                            if (mainEl) {

                                mainEl.textContent =
                                    'Chưa chọn bàn';

                            }


                            if (subEl) {

                                subEl.textContent =
                                    'Khách: Khách lẻ';

                            }

                        }


                        else if (
                            mode ===
                            'dinein'
                        ) {

                            if (mainEl) {

                                mainEl.textContent =
                                    'Tại chỗ';

                            }


                            if (subEl) {

                                subEl.textContent =
                                    'Khách: Khách lẻ';

                            }

                        }


                        else {

                            if (mainEl) {

                                mainEl.textContent =
                                    'Khách mang đi';

                            }


                            if (subEl) {

                                subEl.textContent =
                                    'Chưa nhập thông tin khách';

                            }

                        }

                    }
                );

            }
        );


    /* =========================================================
     * HEADER ORDER BUTTON
     *
     * Desktop:
     * không làm gì.
     *
     * Mobile:
     * mở cart.
     * ========================================================= */

    const orderButton =
        document.querySelector(
            '.mini-header-order'
        );


    if (orderButton) {

        orderButton.addEventListener(
            'click',
            function () {

                if (
                    window.innerWidth <=
                    800 &&
                    miniOrder
                ) {

                    miniOrder.classList.add(
                        'mini-order-open'
                    );

                }

            }
        );

    }


    /* =========================================================
    * SEARCH
    * ========================================================= */

    const search =
        document.getElementById(
            'mini-product-search'
        );

    const searchClear =
        document.getElementById(
            'mini-search-clear'
        );


    function updateSearchClear() {

        if (!searchClear) {
            return;
        }


        const hasText =
            !!(
                search &&
                search.value.trim()
            );


        searchClear.classList.toggle(
            'is-visible',
            hasText
        );

    }


    function applyProductSearch() {

        if (!search) {
            return;
        }


        const keyword =
            search.value
                .toLowerCase()
                .trim();


        grid
            .querySelectorAll(
                '.mini-product-card'
            )
            .forEach(
                function (card) {

                    const name =
                        (
                            card.dataset
                                .name ||
                            card
                                .querySelector(
                                    '.mini-product-name'
                                )
                                ?.textContent ||
                            ''
                        )
                        .toLowerCase();


                    const code =
                        (
                            card.dataset
                                .code ||
                            ''
                        )
                        .toLowerCase();


                    card.style.display =
                        (
                            !keyword ||
                            name.indexOf(
                                keyword
                            ) !== -1 ||
                            code.indexOf(
                                keyword
                            ) !== -1
                        )
                            ? ''
                            : 'none';

                }
            );


        grid.scrollLeft = 0;


        updateSwiperPages();

        updateSearchClear();

    }


    /*
    * Gõ tìm kiếm
    */

    if (search) {

        search.addEventListener(
            'input',
            function () {

                applyProductSearch();

            }
        );

    }


    /*
    * Bấm X
    */

    if (searchClear) {

        searchClear.addEventListener(
            'click',
            function (event) {

                event.preventDefault();

                event.stopPropagation();


                if (!search) {
                    return;
                }


                search.value = '';


                applyProductSearch();


                search.focus();

            }
        );

    }


    /*
    * Trạng thái ban đầu
    */

    updateSearchClear();


    /* =========================================================
    * CATEGORY FILTER
    * Lọc product theo subcategory ID
    *
    * Button:
    *   data-category = ID của subcategory
    *
    * Product:
    *   data-category = category_id
    *
    * "all":
    *   hiển thị toàn bộ sản phẩm
    * ========================================================= */

    const miniCategoryButtons =
        document.querySelectorAll(
            '.mini-category'
        );


    function applyMiniCategoryFilter(
        selectedCategory
    ) {

        const category =
            String(
                selectedCategory || 'all'
            );


        grid
            .querySelectorAll(
                '.mini-product-card'
            )
            .forEach(
                function (card) {

                    const productCategory =
                        String(
                            card.dataset
                                .category || ''
                        );


                    const show =
                        category === 'all' ||
                        productCategory === category;


                    card.style.display =
                        show
                            ? ''
                            : 'none';

                }
            );


        /*
        * Sau khi đổi category:
        * đưa product grid về đầu.
        */
        grid.scrollLeft = 0;

        grid.classList.remove(
            'mini-category-changing'
        );

        void grid.offsetWidth;

        grid.classList.add(
            'mini-category-changing'
        );

        /*
        * Cập nhật lại pagination
        * theo danh sách product đang hiển thị.
        */
        updateSwiperPages();

    }


    miniCategoryButtons.forEach(
        function (button) {

            button.addEventListener(
                'click',
                function () {

                    /*
                    * Bỏ active ở tất cả category.
                    */
                    miniCategoryButtons
                        .forEach(
                            function (item) {

                                item.classList
                                    .remove(
                                        'active'
                                    );

                            }
                        );


                    /*
                    * Active category vừa chọn.
                    */
                    this.classList.add(
                        'active'
                    );


                    /*
                    * Lấy ID subcategory
                    * từ data-category.
                    */
                    applyMiniCategoryFilter(
                        this.dataset.category
                    );

                }
            );

        }
    );


    /* =========================================================
     * CATEGORY SCROLL
     * ========================================================= */

    document
        .querySelectorAll(
            '[data-category-scroll]'
        )
        .forEach(
            function (button) {

                button.addEventListener(
                    'click',
                    function () {

                        const categories =
                            document.querySelector(
                                '.mini-categories'
                            );


                        if (!categories) {
                            return;
                        }


                        categories.scrollBy({

                            left:
                                this.dataset
                                    .categoryScroll ===
                                'left'
                                    ? -230
                                    : 230,

                            behavior:
                                'smooth'

                        });

                    }
                );

            }
        );


    /* =========================================================
     * SWIPER
     * ========================================================= */

    function getPageWidth() {

        return Math.max(
            1,
            grid.clientWidth
        );

    }


    function getPageCount() {

        const width =
            getPageWidth();


        return Math.max(
            1,

            Math.ceil(
                grid.scrollWidth /
                width
            )
        );

    }


    function getCurrentPage() {

        const width =
            getPageWidth();


        return Math.min(
            getPageCount(),

            Math.floor(
                (
                    grid.scrollLeft +
                    width * .5
                ) /
                width
            ) + 1
        );

    }


    function updateSwiperPages() {

        if (window.innerWidth <= 800) {
            return;
        }

        const label =
            document.getElementById(
                'mini-page-label'
            );


        if (!label) {
            return;
        }


        label.textContent =
            getCurrentPage() +
            ' / ' +
            getPageCount();

    }


    function scrollProducts(
        direction
    ) {

        const amount =
            getPageWidth() *
            (
                direction === 'next'
                    ? 1
                    : -1
            );


        grid.scrollBy({

            left:
                amount,

            behavior:
                'smooth'

        });

    }


    const prevPage =
        document.getElementById(
            'mini-prev-page'
        );


    const nextPage =
        document.getElementById(
            'mini-next-page'
        );


    if (prevPage) {

        prevPage.addEventListener(
            'click',
            function () {

                scrollProducts(
                    'prev'
                );

            }
        );

    }


    if (nextPage) {

        nextPage.addEventListener(
            'click',
            function () {

                scrollProducts(
                    'next'
                );

            }
        );

    }


    grid.addEventListener(
        'scroll',
        function () {

            window.requestAnimationFrame(
                updateSwiperPages
            );

        },
        {
            passive: true
        }
    );


    window.addEventListener(
        'resize',
        function () {

            updateSwiperPages();

        }
    );


    /* =========================================================
     * CANCEL
     * ========================================================= */

    const cancel =
        document.getElementById(
            'mini-cancel-order'
        );


    if (cancel) {

        cancel.addEventListener(
            'click',
            function () {

                if (!cart.length) {
                    return;
                }


                if (
                    window.confirm(
                        'Bạn có chắc muốn hủy đơn hiện tại?'
                    )
                ) {

                    cart.length = 0;

                    renderCart();

                    if (miniOrder) {

                        miniOrder.classList
                            .remove(
                                'mini-order-open'
                            );

                    }

                }

            }
        );

    }


    /* =========================================================
    * PAYMENT
    * ========================================================= */

    const payment =
        document.getElementById(
            'mini-payment'
        );


    if (payment) {

        payment.addEventListener(
            'click',
            function () {

                /*
                * Không có món
                */
                if (!cart.length) {

                    showMiniAlert(
                        'Vui lòng thêm món vào đơn.',
                        'warning'
                    );

                    return;

                }


                /*
                * Có món
                */
                const total =
                    document
                        .getElementById(
                            'mini-grand-total'
                        )
                        ?.textContent ||
                    '0đ';


                showMiniAlert(
                    'Tổng thanh toán: ' + total,
                    'success'
                );

            }
        );

    }


    /* =========================================================
     * HOTKEY SEARCH
     * ========================================================= */

    document.addEventListener(
        'keydown',
        function (event) {

            if (
                (
                    event.ctrlKey ||
                    event.metaKey
                ) &&
                event.key.toLowerCase() ===
                    'k'
            ) {

                event.preventDefault();


                if (search) {

                    search.focus();

                }

            }

        }
    );

    /* =========================================================
    * ORDER TOOLS
    * ========================================================= */

    const shippingButton =
        document.getElementById(
            'mini-order-shipping'
        );


    if (shippingButton) {

        shippingButton.addEventListener(
            'click',
            function () {

                openOrderToolModal(
                    'shipping'
                );

            }
        );

    }


    const discountButton =
        document.getElementById(
            'mini-order-discount'
        );


    if (discountButton) {

        discountButton.addEventListener(
            'click',
            function () {

                openOrderToolModal(
                    'discount'
                );

            }
        );

    }


    const noteButton =
        document.getElementById(
            'mini-order-note'
        );


    if (noteButton) {

        noteButton.addEventListener(
            'click',
            function () {

                openOrderToolModal(
                    'note'
                );

            }
        );

    }

    /* =========================================================
     * QUICK ADD BUTTON
     * ========================================================= */

    const quickAddButton =
        document.getElementById(
            'mini-quick-add'
        );


    if (quickAddButton) {

        quickAddButton.addEventListener(
            'click',
            function (event) {

                event.preventDefault();

                event.stopPropagation();

                openQuickAddModal();

            }
        );

    }


    /* =========================================================
     * INIT
     * ========================================================= */

    renderCart();


    window.requestAnimationFrame(
        function () {

            updateSwiperPages();

        }
    );

});