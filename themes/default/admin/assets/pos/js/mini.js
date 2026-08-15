document.addEventListener('DOMContentLoaded', function () {

    const grid =
        document.getElementById('mini-product-grid');

    if (!grid) return;


    /* =========================================================
     * STATE
     * ========================================================= */

    const cart = [];

    let modal = null;

    let basePrice = 0;

    let discountType = 'amount';

    let editingRowId = null;

    let touchRow = null;

    let touchStartX = 0;

    let touchCurrentX = 0;


    /* =========================================================
     * HELPERS
     * ========================================================= */

    const money = function (value) {

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

    };


    const esc = function (value) {

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

    };


    const createRowId = function () {

        return (
            Date.now().toString(36) +
            '_' +
            Math.random()
                .toString(36)
                .slice(2, 9)
        );

    };


    /* =========================================================
     * PRICE / DISCOUNT
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
     * RENDER CART
     *
     * QUAN TRỌNG:
     * Không hiển thị code sản phẩm.
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


        if (!list) return;


        list
            .querySelectorAll(
                '.mini-cart-row'
            )
            .forEach(function (row) {

                row.remove();

            });


        if (empty) {

            empty.style.display =
                cart.length
                    ? 'none'
                    : 'flex';

        }


        cart.forEach(function (item) {

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
                    class="mini-cart-swipe-delete">
                    XÓA
                </div>


                <div
                    class="mini-cart-row-content">


                    <div
                        class="mini-cart-name">

                        <strong>
                            ${esc(item.name)}
                        </strong>

                        ${
                            noteText
                                ? `
                                    <span
                                        class="mini-cart-note">
                                        ${esc(noteText)}
                                    </span>
                                  `
                                : ''
                        }

                    </div>


                    <div
                        class="mini-cart-price">

                        ${money(
                            getItemUnitNet(item)
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
                            getItemTotal(item)
                        )}

                    </div>

                </div>

            `;


            list.appendChild(row);

        });


        /* =====================================================
         * TOTAL
         * ===================================================== */

        let qty = 0;

        let subtotal = 0;

        let discount = 0;


        cart.forEach(function (item) {

            qty += item.qty;

            subtotal +=
                item.price *
                item.qty;

            discount +=
                getItemDiscount(item) *
                item.qty;

        });


        const deliveryFee =
            0;


        const grandTotal =
            Math.max(
                0,
                subtotal -
                discount +
                deliveryFee
            );


        function setText(
            id,
            value
        ) {

            const el =
                document.getElementById(
                    id
                );


            if (el) {

                el.textContent =
                    value;

            }

        }


        setText(
            'mini-total-qty',
            qty
        );


        setText(
            'mini-subtotal',
            money(subtotal)
        );


        setText(
            'mini-total-discount',
            money(discount)
        );


        setText(
            'mini-delivery-fee',
            money(deliveryFee)
        );


        setText(
            'mini-grand-total',
            money(grandTotal)
        );


        setText(
            'mini-products-count',
            qty + ' món'
        );

    }


    /* =========================================================
     * ADD TO CART
     *
     * MỖI LẦN BẤM MÓN
     * => DÒNG MỚI
     *
     * Không cộng SL vào dòng cũ.
     * ========================================================= */

    function addToCart(
        card,
        qty,
        data
    ) {

        data =
            data || {};


        cart.push({

            rowId:
                createRowId(),

            id:
                String(
                    card.dataset.productId ||
                    ''
                ),

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
                    card.dataset.price ||
                    0
                ) || 0,

            qty:
                Math.max(
                    1,
                    parseInt(
                        qty,
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
                data.comment ||
                '',

            commentName:
                data.commentName ||
                ''

        });


        renderCart();


        card.classList.remove(
            'quick-added'
        );


        void card.offsetWidth;


        card.classList.add(
            'quick-added'
        );

    }


    /* =========================================================
     * CHANGE QTY
     * ========================================================= */

    function changeQty(
        rowId,
        delta
    ) {

        const index =
            cart.findIndex(function (item) {

                return (
                    item.rowId ===
                    rowId
                );

            });


        if (index < 0) return;


        cart[index].qty +=
            delta;


        if (
            cart[index].qty <=
            0
        ) {

            cart.splice(
                index,
                1
            );

        }


        renderCart();

    }


    /* =========================================================
     * DELETE ROW
     * ========================================================= */

    function removeRow(
        rowId
    ) {

        const index =
            cart.findIndex(function (item) {

                return (
                    item.rowId ===
                    rowId
                );

            });


        if (index < 0) return;


        cart.splice(
            index,
            1
        );


        renderCart();

    }


    /* =========================================================
     * QUICK NOTES
     * ========================================================= */

    function renderQuickNotes() {

        const box =
            document.getElementById(
                'mini-quick-notes-list'
            );


        if (!box) return;


        const comments =
            Array.isArray(
                window.miniOrderComments
            )
                ? window.miniOrderComments
                : [];


        box.innerHTML =
            comments
                .map(function (comment) {

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
                            for="mini-comment-${esc(id)}">

                            <input
                                class="mini-note-checkbox chkComment"
                                type="checkbox"
                                id="mini-comment-${esc(id)}"
                                value="${esc(text)}">


                            <span
                                class="mini-note-box">
                                ✓
                            </span>


                            <span
                                class="mini-note-text">
                                ${esc(text)}
                            </span>

                        </label>

                    `;

                })
                .join('');

    }


    /* =========================================================
     * CREATE MODAL
     * ========================================================= */

    function createModal() {

        if (modal) return;


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
                                        min="1">


                                    <button
                                        type="button"
                                        id="mini-qty-plus"
                                        class="mini-qty-btn">
                                        +
                                    </button>

                                </div>

                            </div>


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
                                                inputmode="numeric">

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
                                    placeholder="Chọn ghi chú hoặc nhập thêm...">

                            </div>


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


        modal =
            document.getElementById(
                'mini-product-modal'
            );


        renderQuickNotes();


        modal
            .querySelectorAll(
                '[data-mini-modal-close]'
            )
            .forEach(function (button) {

                button.addEventListener(
                    'click',
                    closeModal
                );

            });


        document
            .getElementById(
                'mini-qty-minus'
            )
            .onclick =
            function () {

                changeModalQty(-1);

            };


        document
            .getElementById(
                'mini-qty-plus'
            )
            .onclick =
            function () {

                changeModalQty(1);

            };


        document
            .getElementById(
                'mini-qty'
            )
            .onchange =
            normalizeQty;


        document
            .getElementById(
                'mini-discount-minus'
            )
            .onclick =
            function () {

                changeDiscount(-1);

            };


        document
            .getElementById(
                'mini-discount-plus'
            )
            .onclick =
            function () {

                changeDiscount(1);

            };


        document
            .getElementById(
                'mini-modal-add'
            )
            .onclick =
            saveModal;


        document
            .getElementById(
                'pdiscount'
            )
            .oninput =
            updateDiscountInput;


        modal
            .querySelectorAll(
                '.mini-discount-type-btn'
            )
            .forEach(function (button) {

                button.onclick =
                    function () {

                        setDiscountType(
                            this.dataset
                                .discountType
                        );

                    };

            });


        modal.addEventListener(
            'change',
            function (event) {

                if (
                    event.target.classList
                        .contains(
                            'chkComment'
                        )
                ) {

                    updateNoteField();

                }

            }
        );

    }


    /* =========================================================
     * OPEN MODAL
     *
     * item = null:
     *      thêm món mới
     *
     * item != null:
     *      sửa dòng đang có
     * ========================================================= */

    function openModal(
        card,
        item
    ) {

        createModal();


        editingRowId =
            item
                ? item.rowId
                : null;


        basePrice =
            parseFloat(
                card.dataset.price ||
                0
            ) || 0;


        modal.dataset.productId =
            card.dataset.productId ||
            '';


        document
            .getElementById(
                'mini-modal-product-name'
            )
            .textContent =
            card.dataset.name ||
            '';


        document
            .getElementById(
                'mini-qty'
            )
            .value =
            item
                ? item.qty
                : 1;


        setDiscountType(
            item?.discountType ||
            'amount'
        );


        document
            .getElementById(
                'pdiscount'
            )
            .value =
            item
                ? item.discount || 0
                : 0;


        document
            .getElementById(
                'icomment'
            )
            .value =
            item?.comment ||
            '';


        document
            .getElementById(
                'icommentname'
            )
            .value =
            item?.commentName ||
            '';


        modal
            .querySelectorAll(
                '.chkComment'
            )
            .forEach(function (box) {

                box.checked =
                    !!(
                        item &&
                        item.comment &&
                        item.comment
                            .split(',')
                            .map(
                                function (x) {
                                    return x.trim();
                                }
                            )
                            .includes(
                                box.value.trim()
                            )
                    );

            });


        document
            .getElementById(
                'mini-modal-add'
            )
            .textContent =
            item
                ? 'CẬP NHẬT MÓN'
                : 'THÊM VÀO ĐƠN';


        updateModalPrice();


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

    }


    /* =========================================================
     * CLOSE MODAL
     * ========================================================= */

    function closeModal() {

        if (!modal) return;


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


        editingRowId =
            null;

    }


    /* =========================================================
     * MODAL QUANTITY
     * ========================================================= */

    function changeModalQty(
        amount
    ) {

        const input =
            document.getElementById(
                'mini-qty'
            );


        if (!input) return;


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


    function normalizeQty() {

        const input =
            document.getElementById(
                'mini-qty'
            );


        if (!input) return;


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
     * MODAL DISCOUNT
     * ========================================================= */

    function getDiscount() {

        let value =
            parseFloat(
                document
                    .getElementById(
                        'pdiscount'
                    )
                    .value
            ) || 0;


        value =
            Math.max(
                0,
                value
            );


        if (
            discountType ===
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


    function updateModalPrice() {

        const value =
            getDiscount();


        const discount =
            discountType ===
            'percent'
                ? basePrice *
                  value /
                  100
                : Math.min(
                    basePrice,
                    value
                );


        document
            .getElementById(
                'mini-modal-product-price'
            )
            .textContent =
            money(
                basePrice -
                discount
            );


        document
            .getElementById(
                'mini-discount-suffix'
            )
            .textContent =
            discountType ===
            'percent'
                ? '%'
                : 'đ';

    }


    function setDiscountType(
        type
    ) {

        discountType =
            type === 'percent'
                ? 'percent'
                : 'amount';


        if (!modal) return;


        modal
            .querySelectorAll(
                '.mini-discount-type-btn'
            )
            .forEach(function (button) {

                button.classList.toggle(
                    'active',
                    button.dataset
                        .discountType ===
                    discountType
                );

            });


        const input =
            document.getElementById(
                'pdiscount'
            );


        if (input) {

            input.max =
                discountType ===
                'percent'
                    ? '100'
                    : '';

            input.step =
                discountType ===
                'percent'
                    ? '1'
                    : '1000';

        }


        updateModalPrice();

    }


    function updateDiscountInput() {

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
            discountType ===
            'percent'
        ) {

            value =
                Math.min(
                    100,
                    Math.round(value)
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


        updateModalPrice();

    }


    function changeDiscount(
        direction
    ) {

        const input =
            document.getElementById(
                'pdiscount'
            );


        if (!input) return;


        const step =
            discountType ===
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
            discountType ===
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


        updateModalPrice();

    }


    /* =========================================================
     * NOTE
     * ========================================================= */

    function updateNoteField() {

        if (!modal) return;


        const input =
            document.getElementById(
                'icomment'
            );


        const quick = [];

        const known = [];


        modal
            .querySelectorAll(
                '.chkComment'
            )
            .forEach(function (box) {

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

            });


        const manual =
            input
                .value
                .split(',')
                .map(function (x) {
                    return x.trim();
                })
                .filter(function (x) {

                    return (
                        x &&
                        known.indexOf(x) ===
                        -1
                    );

                });


        input.value =
            manual
                .concat(quick)
                .join(', ');

    }


    /* =========================================================
     * SAVE MODAL
     * ========================================================= */

    function saveModal() {

        if (!modal) return;


        const productId =
            String(
                modal.dataset.productId ||
                ''
            );


        const card =
            grid.querySelector(
                '.mini-product-card[data-product-id="' +
                CSS.escape(productId) +
                '"]'
            );


        if (!card) return;


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


        const data = {

            discount:
                getDiscount(),

            discountType:
                discountType,

            comment:
                document
                    .getElementById(
                        'icomment'
                    )
                    .value
                    .trim(),

            commentName:
                document
                    .getElementById(
                        'icommentname'
                    )
                    .value
                    .trim()

        };


        /* =====================================================
         * EDIT DÒNG CŨ
         * ===================================================== */

        if (editingRowId) {

            const item =
                cart.find(function (x) {

                    return (
                        x.rowId ===
                        editingRowId
                    );

                });


            if (item) {

                item.qty =
                    qty;

                item.discount =
                    data.discount;

                item.discountType =
                    data.discountType;

                item.comment =
                    data.comment;

                item.commentName =
                    data.commentName;


                renderCart();

            }

        }


        /* =====================================================
         * THÊM DÒNG MỚI
         * ===================================================== */

        else {

            addToCart(
                card,
                qty,
                data
            );

        }


        closeModal();

    }


    /* =========================================================
     * PRODUCT EVENTS
     * ========================================================= */

    grid
        .querySelectorAll(
            '.mini-product-card'
        )
        .forEach(function (card) {


            /*
             * Bấm thân món
             * => thêm ngay 1 dòng
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
                        card,
                        1
                    );

                }
            );


            /*
             * Nút ✎
             * => mở modal thêm món có chi tiết
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


                        openModal(
                            card
                        );

                    }
                );

            }

        });


    /* =========================================================
     * CART CLICK
     *
     * Click +/-:
     *      đổi SL
     *
     * Click phần còn lại:
     *      mở modal EDIT
     * ========================================================= */

    const cartList =
        document.getElementById(
            'mini-cart-list'
        );


    if (cartList) {

        cartList.addEventListener(
            'click',
            function (event) {

                const row =
                    event.target.closest(
                        '.mini-cart-row'
                    );


                if (!row) return;


                const button =
                    event.target.closest(
                        '[data-cart-action]'
                    );


                if (button) {

                    event.preventDefault();

                    event.stopPropagation();


                    changeQty(
                        row.dataset.rowId,

                        button.dataset
                            .cartAction ===
                            'plus'
                            ? 1
                            : -1
                    );


                    return;

                }


                const item =
                    cart.find(function (x) {

                        return (
                            x.rowId ===
                            row.dataset.rowId
                        );

                    });


                const card =
                    grid.querySelector(
                        '.mini-product-card[data-product-id="' +
                        CSS.escape(
                            row.dataset.productId ||
                            ''
                        ) +
                        '"]'
                    );


                if (
                    item &&
                    card
                ) {

                    openModal(
                        card,
                        item
                    );

                }

            }
        );


        /* =====================================================
         * SWIPE START
         * ===================================================== */

        cartList.addEventListener(
            'touchstart',
            function (event) {

                const row =
                    event.target.closest(
                        '.mini-cart-row'
                    );


                if (!row) return;


                touchRow =
                    row;


                touchStartX =
                    event.touches[0]
                        .clientX;


                touchCurrentX =
                    touchStartX;


                row.classList.add(
                    'is-swiping'
                );

            },
            {
                passive: true
            }
        );


        /* =====================================================
         * SWIPE MOVE
         * ===================================================== */

        cartList.addEventListener(
            'touchmove',
            function (event) {

                if (!touchRow) return;


                touchCurrentX =
                    event.touches[0]
                        .clientX;


                const distance =
                    touchCurrentX -
                    touchStartX;


                if (
                    distance < 0
                ) {

                    const content =
                        touchRow.querySelector(
                            '.mini-cart-row-content'
                        );


                    if (content) {

                        content.style.transform =
                            'translateX(' +
                            Math.max(
                                -110,
                                distance
                            ) +
                            'px)';

                    }

                }

            },
            {
                passive: true
            }
        );


        /* =====================================================
         * SWIPE END
         * ===================================================== */

        cartList.addEventListener(
            'touchend',
            function () {

                if (!touchRow) return;


                const row =
                    touchRow;


                const distance =
                    touchCurrentX -
                    touchStartX;


                const content =
                    row.querySelector(
                        '.mini-cart-row-content'
                    );


                row.classList.remove(
                    'is-swiping'
                );


                if (
                    distance <=
                    -80
                ) {

                    removeRow(
                        row.dataset.rowId
                    );

                } else if (content) {

                    content.style.transform =
                        '';

                }


                touchRow =
                    null;

            },
            {
                passive: true
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


    function applySearch() {

        if (!search) return;


        const keyword =
            search.value
                .toLowerCase()
                .trim();


        grid
            .querySelectorAll(
                '.mini-product-card'
            )
            .forEach(function (card) {

                const name =
                    (
                        card.dataset.name ||
                        ''
                    )
                    .toLowerCase();


                const code =
                    (
                        card.dataset.code ||
                        ''
                    )
                    .toLowerCase();


                card.style.display =
                    (
                        !keyword ||
                        name.includes(
                            keyword
                        ) ||
                        code.includes(
                            keyword
                        )
                    )
                        ? ''
                        : 'none';

            });


        if (searchClear) {

            searchClear.style.display =
                search.value
                    ? 'flex'
                    : 'none';

        }


        grid.scrollLeft =
            0;


        updatePages();

    }


    if (search) {

        search.addEventListener(
            'input',
            applySearch
        );

    }


    if (searchClear) {

        searchClear.addEventListener(
            'click',
            function () {

                search.value =
                    '';

                applySearch();

                search.focus();

            }
        );

    }


    /* =========================================================
     * CATEGORY
     * ========================================================= */

    document
        .querySelectorAll(
            '.mini-category'
        )
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    document
                        .querySelectorAll(
                            '.mini-category'
                        )
                        .forEach(function (item) {

                            item.classList
                                .remove(
                                    'active'
                                );

                        });


                    button.classList.add(
                        'active'
                    );


                    const category =
                        button.dataset
                            .category ||
                        'all';


                    grid
                        .querySelectorAll(
                            '.mini-product-card'
                        )
                        .forEach(function (card) {

                            card.style.display =
                                (
                                    category ===
                                    'all' ||
                                    card.dataset
                                        .category ===
                                    category
                                )
                                    ? ''
                                    : 'none';

                        });


                    grid.scrollLeft =
                        0;


                    updatePages();

                }
            );

        });


    /* =========================================================
     * CATEGORY SCROLL
     * ========================================================= */

    document
        .querySelectorAll(
            '[data-category-scroll]'
        )
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    const categories =
                        document.querySelector(
                            '.mini-categories'
                        );


                    if (!categories) return;


                    categories.scrollBy({

                        left:
                            button.dataset
                                .categoryScroll ===
                            'left'
                                ? -230
                                : 230,

                        behavior:
                            'smooth'

                    });

                }
            );

        });


    /* =========================================================
     * PRODUCT PAGING
     * ========================================================= */

    function pageWidth() {

        return Math.max(
            1,
            grid.clientWidth
        );

    }


    function pageCount() {

        return Math.max(
            1,
            Math.ceil(
                grid.scrollWidth /
                pageWidth()
            )
        );

    }


    function updatePages() {

        const label =
            document.getElementById(
                'mini-page-label'
            );


        if (!label) return;


        const current =
            Math.min(

                pageCount(),

                Math.floor(
                    (
                        grid.scrollLeft +
                        pageWidth() *
                        .5
                    ) /
                    pageWidth()
                ) + 1

            );


        label.textContent =
            current +
            ' / ' +
            pageCount();

    }


    const prev =
        document.getElementById(
            'mini-prev-page'
        );


    const next =
        document.getElementById(
            'mini-next-page'
        );


    if (prev) {

        prev.addEventListener(
            'click',
            function () {

                grid.scrollBy({

                    left:
                        -pageWidth(),

                    behavior:
                        'smooth'

                });

            }
        );

    }


    if (next) {

        next.addEventListener(
            'click',
            function () {

                grid.scrollBy({

                    left:
                        pageWidth(),

                    behavior:
                        'smooth'

                });

            }
        );

    }


    grid.addEventListener(
        'scroll',
        function () {

            requestAnimationFrame(
                updatePages
            );

        },
        {
            passive: true
        }
    );


    window.addEventListener(
        'resize',
        updatePages
    );


    /* =========================================================
     * HEADER ORDER MODE
     * ========================================================= */

    document
        .querySelectorAll(
            '.mini-header-mode'
        )
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    document
                        .querySelectorAll(
                            '.mini-header-mode'
                        )
                        .forEach(function (item) {

                            item.classList
                                .remove(
                                    'active'
                                );

                        });


                    button.classList.add(
                        'active'
                    );


                    const mode =
                        button.dataset
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


                    if (mainEl) {

                        mainEl.textContent =
                            mode === 'table'
                                ? 'Chưa chọn bàn'
                                : mode === 'dinein'
                                    ? 'Tại chỗ'
                                    : 'Khách mang đi';

                    }


                    if (subEl) {

                        subEl.textContent =
                            mode === 'takeaway'
                                ? 'Chưa nhập thông tin khách'
                                : 'Khách: Khách lẻ';

                    }

                }
            );

        });


    /* =========================================================
     * MOBILE ORDER PANEL
     * ========================================================= */

    const miniOrder =
        document.querySelector(
            '.mini-order'
        );


    const infoPanel =
        document.querySelector(
            '.mini-order-info-panel'
        );


    if (
        miniOrder &&
        infoPanel
    ) {

        infoPanel.addEventListener(
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


    const headerOrder =
        document.querySelector(
            '.mini-header-order'
        );


    if (headerOrder) {

        headerOrder.addEventListener(
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

                if (!cart.length) return;


                if (
                    window.confirm(
                        'Bạn có chắc muốn hủy đơn hiện tại?'
                    )
                ) {

                    cart.length =
                        0;


                    renderCart();

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
     * KEYBOARD
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


            if (
                event.key ===
                'Escape'
            ) {

                if (
                    modal &&
                    modal.classList.contains(
                        'show'
                    )
                ) {

                    closeModal();

                    return;

                }


                if (
                    search &&
                    document.activeElement ===
                    search &&
                    search.value
                ) {

                    search.value =
                        '';

                    applySearch();

                }

            }

        }
    );


    /* =========================================================
     * INIT
     * ========================================================= */

    renderCart();

    applySearch();

    requestAnimationFrame(
        updatePages
    );

});