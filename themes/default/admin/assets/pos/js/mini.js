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

    let miniDiscountType = 'amount';


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
                            ? 'Ly: ' +
                              item.commentName
                            : ''

                    ]
                    .filter(Boolean)
                    .join(' • ');


                    row.innerHTML = `

                        <div
                            class="mini-cart-name">

                            <strong>
                                ${escapeHtml(
                                    item.name
                                )}
                            </strong>

                            <small>
                                ${escapeHtml(
                                    item.code || ''
                                )}
                            </small>

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


                        <div
                            class="mini-cart-price">

                            ${money(
                                getItemUnitNet(
                                    item
                                )
                            )}

                        </div>


                        <div
                            class="mini-cart-qty">

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


                        <div
                            class="mini-cart-total">

                            ${money(
                                getItemTotal(
                                    item
                                )
                            )}

                        </div>

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
                parseFloat(
                    card.dataset.price || 0
                ) || 0,


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

                            <label
                                class="mini-note-item"
                                for="mini-comment-${escapeHtml(id)}">

                                <input
                                    class="mini-note-checkbox chkComment"
                                    type="checkbox"
                                    id="mini-comment-${escapeHtml(id)}"
                                    value="${escapeHtml(text)}">

                                <span
                                    class="mini-note-box">
                                    ✓
                                </span>

                                <span
                                    class="mini-note-text">
                                    ${escapeHtml(text)}
                                </span>

                            </label>

                        `;

                    }
                )
                .join('');

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


                                    <div
                                        class="mini-discount-stepper">

                                        <button
                                            type="button"
                                            id="mini-discount-minus"
                                            class="mini-discount-step-btn">
                                            −
                                        </button>


                                        <div
                                            class="mini-input-money">

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


                                        <button
                                            type="button"
                                            id="mini-discount-plus"
                                            class="mini-discount-step-btn">
                                            +
                                        </button>

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
                                    class="mini-modal-input"
                                    maxlength="500"
                                    autocomplete="off"
                                    placeholder="Chọn ghi chú hoặc nhập thêm...">

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
                                    class="mini-modal-input"
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
         * Discount.
         */

        document
            .getElementById(
                'mini-discount-minus'
            )
            .addEventListener(
                'click',
                function () {

                    changeMiniDiscount(
                        -1
                    );

                }
            );


        document
            .getElementById(
                'mini-discount-plus'
            )
            .addEventListener(
                'click',
                function () {

                    changeMiniDiscount(
                        1
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


        let discount;


        if (
            miniDiscountType ===
            'percent'
        ) {

            discount =
                miniBasePrice *
                value /
                100;

        } else {

            discount =
                value;

        }


        discount =
            Math.min(
                miniBasePrice,
                discount
            );


        const priceEl =
            document.getElementById(
                'mini-modal-product-price'
            );


        if (priceEl) {

            priceEl.textContent =
                money(
                    miniBasePrice -
                    discount
                );

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


    /* =========================================================
     * OPEN MODAL
     * ========================================================= */

    function openProductModal(
        card
    ) {

        createProductModal();


        miniBasePrice =
            parseFloat(
                card.dataset.price ||
                0
            ) || 0;


        miniModal.dataset.productId =
            card.dataset.productId ||
            '';


        document
            .getElementById(
                'mini-modal-product-name'
            )
            .textContent =
            card.dataset.name ||
            card
                .querySelector(
                    '.mini-product-name'
                )
                ?.textContent
                .trim() ||
            '';


        document
            .getElementById(
                'mini-qty'
            )
            .value = 1;


        document
            .getElementById(
                'pdiscount'
            )
            .value = 0;


        document
            .getElementById(
                'icomment'
            )
            .value = '';


        document
            .getElementById(
                'icommentname'
            )
            .value = '';


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


        setMiniDiscountType(
            'amount'
        );


        /*
         * Discount input.
         */

        const discountInput =
            document.getElementById(
                'pdiscount'
            );


        discountInput.oninput =
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

            };


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
                    commentName
            }
        );


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


                        addToCart(
                            this,
                            1
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

                const button =
                    event.target.closest(
                        '[data-cart-action]'
                    );


                const row =
                    event.target.closest(
                        '.mini-cart-row'
                    );


                if (
                    !button ||
                    !row
                ) {

                    return;

                }


                changeCartQty(
                    row.dataset.rowId,

                    button.dataset
                        .cartAction ===
                        'plus'
                        ? 1
                        : -1
                );

            }
        );

    }


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


    if (search) {

        search.addEventListener(
            'input',
            function () {

                const keyword =
                    this.value
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


                grid.scrollLeft =
                    0;


                updateSwiperPages();

            }
        );

    }


    /* =========================================================
     * CATEGORY FILTER
     * ========================================================= */

    document
        .querySelectorAll(
            '.mini-category'
        )
        .forEach(
            function (button) {

                button.addEventListener(
                    'click',
                    function () {


                        document
                            .querySelectorAll(
                                '.mini-category'
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


                        const category =
                            this.dataset
                                .category ||
                            'all';


                        grid
                            .querySelectorAll(
                                '.mini-product-card'
                            )
                            .forEach(
                                function (card) {

                                    card.style.display =
                                        (
                                            category ===
                                                'all' ||
                                            category ===
                                                card.dataset
                                                    .category
                                        )
                                            ? ''
                                            : 'none';

                                }
                            );


                        grid.scrollLeft =
                            0;


                        updateSwiperPages();

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

                const total =
                    document
                        .getElementById(
                            'mini-grand-total'
                        )
                        ?.textContent ||
                    '0đ';


                window.alert(
                    'Tổng thanh toán: ' +
                    total
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
     * INIT
     * ========================================================= */

    renderCart();


    window.requestAnimationFrame(
        function () {

            updateSwiperPages();

        }
    );

});