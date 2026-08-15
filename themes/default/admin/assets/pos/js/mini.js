document.addEventListener('DOMContentLoaded', function () {
    const grid = document.getElementById('mini-product-grid');

    if (!grid) return;

    const cart = [];

    let miniModal = null;
    let miniBasePrice = 0;
    let miniDiscountType = 'amount';
    let miniEditingRowId = null;

    let touchRow = null;
    let touchStartX = 0;
    let touchCurrentX = 0;

    let searchClear = null;


    /* =========================================================
     * HELPERS
     * ========================================================= */

    const money = value =>
        Math.max(0, Math.round(Number(value) || 0))
            .toLocaleString('vi-VN') + 'đ';


    const escapeHtml = value =>
        String(value == null ? '' : value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');


    const createRowId = () =>
        Date.now().toString(36) +
        '_' +
        Math.random().toString(36).slice(2, 9);


    function getLineDiscount(item) {

        const value =
            Math.max(0, Number(item.discount) || 0);


        if (item.discountType === 'percent') {

            return Math.min(
                item.price,
                item.price *
                Math.min(100, value) /
                100
            );

        }


        return Math.min(
            item.price,
            value
        );

    }


    function getLineUnitNet(item) {

        return Math.max(
            0,
            item.price -
            getLineDiscount(item)
        );

    }


    function getLineTotal(item) {

        return (
            getLineUnitNet(item) *
            item.qty
        );

    }


    /* =========================================================
     * MOBILE CART BAR
     * ========================================================= */

    function updateMobileCartBar(qty, total) {

        let bar =
            document.getElementById(
                'mini-mobile-cart-bar'
            );


        if (!bar) {

            bar =
                document.createElement('button');

            bar.type = 'button';

            bar.id =
                'mini-mobile-cart-bar';

            bar.className =
                'mini-mobile-cart-bar';


            bar.innerHTML = `
                <span class="mini-mobile-cart-icon">
                    🛒
                </span>

                <span class="mini-mobile-cart-info">
                    <strong>Đơn hàng</strong>
                    <small>
                        <b id="mini-mobile-cart-qty">0</b>
                        món
                    </small>
                </span>

                <strong id="mini-mobile-cart-total">
                    0đ
                </strong>

                <span class="mini-mobile-cart-arrow">
                    ⌃
                </span>
            `;


            document.body.appendChild(bar);


            bar.addEventListener(
                'click',
                function () {

                    document.body.classList.toggle(
                        'mini-mobile-cart-open'
                    );


                    bar.classList.toggle(
                        'open'
                    );


                    const order =
                        document.querySelector(
                            '.mini-order'
                        );


                    if (
                        order &&
                        document.body.classList.contains(
                            'mini-mobile-cart-open'
                        )
                    ) {

                        order.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });

                    }

                }
            );

        }


        const qtyEl =
            document.getElementById(
                'mini-mobile-cart-qty'
            );


        const totalEl =
            document.getElementById(
                'mini-mobile-cart-total'
            );


        if (qtyEl) {
            qtyEl.textContent = qty;
        }


        if (totalEl) {
            totalEl.textContent =
                money(total);
        }


        bar.classList.toggle(
            'has-items',
            qty > 0
        );

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
                document.createElement('div');


            row.className =
                'mini-cart-row';


            row.dataset.rowId =
                item.rowId;


            row.dataset.productId =
                item.id;


            const noteText = [

                item.comment || '',

                item.commentName
                    ? 'Ly: ' +
                      item.commentName
                    : ''

            ]
                .filter(Boolean)
                .join(' • ');


            row.innerHTML = `

                <div class="mini-cart-swipe-delete">
                    XÓA
                </div>


                <div class="mini-cart-row-content">

                    <div class="mini-cart-name">

                        <strong>
                            ${escapeHtml(item.name)}
                        </strong>

                        ${
                            noteText
                                ? `
                                    <span class="mini-cart-note">
                                        ${escapeHtml(noteText)}
                                    </span>
                                  `
                                : ''
                        }

                    </div>


                    <div class="mini-cart-price">

                        ${money(
                            getLineUnitNet(item)
                        )}

                    </div>


                    <div class="mini-cart-qty">

                        <button
                            type="button"
                            data-cart-action="minus"
                            aria-label="Giảm số lượng">
                            −
                        </button>


                        <span>
                            ${item.qty}
                        </span>


                        <button
                            type="button"
                            data-cart-action="plus"
                            aria-label="Tăng số lượng">
                            +
                        </button>

                    </div>


                    <div class="mini-cart-total">

                        ${money(
                            getLineTotal(item)
                        )}

                    </div>

                </div>
            `;


            list.appendChild(row);

        });


        let qty = 0;
        let subtotal = 0;
        let discount = 0;


        cart.forEach(function (item) {

            qty += item.qty;

            subtotal +=
                item.price *
                item.qty;

            discount +=
                getLineDiscount(item) *
                item.qty;

        });


        const deliveryFee = 0;


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

            const element =
                document.getElementById(id);


            if (element) {
                element.textContent =
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


        updateMobileCartBar(
            qty,
            grandTotal
        );

    }


    /* =========================================================
     * ADD TO CART
     *
     * LUÔN TẠO DÒNG MỚI
     * ========================================================= */

    function addToCart(
        card,
        quantity,
        extra
    ) {

        if (!card) return;


        const data =
            extra || {};


        cart.push({

            rowId:
                createRowId(),

            id:
                String(
                    card.dataset.productId ||
                    ''
                ),

            code:
                card.dataset.code ||
                '',

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
                data.comment ||
                '',

            commentName:
                data.commentName ||
                '',

            option:
                data.option ||
                '',

            serial:
                data.serial ||
                '',

            isPromo:
                Number(
                    card.dataset.promo ||
                    0
                )
                    ? 1
                    : 0

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
     * CHANGE QUANTITY
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


        if (index === -1) return;


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
     * REMOVE ROW
     * ========================================================= */

    function removeCartRow(
        rowId
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


        if (index === -1) return;


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

        const container =
            document.getElementById(
                'mini-quick-notes-list'
            );


        if (!container) return;


        const comments =
            Array.isArray(
                window.miniOrderComments
            )
                ? window.miniOrderComments
                : [];


        container.innerHTML =
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
                        ).trim();


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


                            <span class="mini-note-box">
                                ✓
                            </span>


                            <span class="mini-note-text">
                                ${escapeHtml(text)}
                            </span>

                        </label>

                    `;

                })
                .join('');

    }


    /* =========================================================
     * CREATE MODAL
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


                    <div class="mini-modal-header">

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


                    <div class="mini-modal-body">


                        <div class="mini-modal-two-col-top">


                            <div class="mini-modal-field">

                                <label>
                                    Số lượng
                                </label>


                                <div class="mini-modal-quantity">

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


                            <div class="mini-modal-field">

                                <label>
                                    Giảm giá
                                </label>


                                <div class="mini-discount-control">


                                    <div class="mini-discount-stepper">

                                        <button
                                            type="button"
                                            id="mini-discount-minus"
                                            class="mini-discount-step-btn">
                                            −
                                        </button>


                                        <div class="mini-input-money">

                                            <input
                                                type="text"
                                                id="pdiscount"
                                                class="mini-modal-input"
                                                value="0"
                                                autocomplete="off"
                                                inputmode="numeric">


                                            <span id="mini-discount-suffix">
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


                                    <div class="mini-discount-type">

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


                        <div class="mini-modal-two-col-bottom">


                            <div class="mini-modal-field">

                                <label for="icomment">
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


                            <div class="mini-modal-field">

                                <label for="icommentname">
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


                        <div class="mini-modal-section">

                            <div class="mini-modal-label">
                                Ghi chú nhanh
                            </div>


                            <div
                                id="mini-quick-notes-list"
                                class="mini-note-grid">
                            </div>

                        </div>


                    </div>


                    <div class="mini-modal-footer">


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


        miniModal
            .querySelectorAll(
                '[data-mini-modal-close]'
            )
            .forEach(function (button) {

                button.addEventListener(
                    'click',
                    closeProductModal
                );

            });


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


        document
            .getElementById(
                'mini-modal-add'
            )
            .addEventListener(
                'click',
                handleModalSave
            );


        document.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key ===
                    'Escape' &&
                    miniModal &&
                    miniModal.classList.contains(
                        'show'
                    )
                ) {

                    closeProductModal();

                }

            }
        );

    }


    /* =========================================================
     * DISCOUNT
     * ========================================================= */

    function getDiscountValue() {

        let value =
            parseFloat(
                document.getElementById(
                    'pdiscount'
                )?.value
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
                    value
                );

        }


        return value;

    }


    function updateMiniModalPrice() {

        const value =
            getDiscountValue();


        const discount =
            miniDiscountType ===
            'percent'

                ? miniBasePrice *
                  value /
                  100

                : Math.min(
                    miniBasePrice,
                    value
                );


        const priceEl =
            document.getElementById(
                'mini-modal-product-price'
            );


        if (priceEl) {

            priceEl.textContent =
                money(
                    Math.max(
                        0,
                        miniBasePrice -
                        discount
                    )
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


    function setMiniDiscountType(
        type,
        reset
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

            if (reset !== false) {
                input.value = 0;
            }


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
                .forEach(function (button) {

                    button.classList.toggle(
                        'active',
                        button.dataset.discountType ===
                        miniDiscountType
                    );

                });

        }


        updateMiniModalPrice();

    }


    function changeMiniDiscount(
        direction
    ) {

        const input =
            document.getElementById(
                'pdiscount'
            );


        if (!input) return;


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
     * NOTES
     * ========================================================= */

    function updateMiniNoteField() {

        const input =
            document.getElementById(
                'icomment'
            );


        if (
            !input ||
            !miniModal
        ) return;


        const quick = [];
        const known = [];


        miniModal
            .querySelectorAll(
                '.chkComment'
            )
            .forEach(function (box) {

                const value =
                    box.value.trim();


                if (value) {
                    known.push(value);
                }


                if (
                    box.checked &&
                    value
                ) {
                    quick.push(value);
                }

            });


        const manual =
            input.value
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
     * OPEN MODAL
     *
     * existingItem = edit dòng hiện tại
     * ========================================================= */

    function openProductModal(
        card,
        existingItem
    ) {

        createProductModal();


        miniEditingRowId =
            existingItem
                ? existingItem.rowId
                : null;


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
            '';


        document
            .getElementById(
                'mini-qty'
            )
            .value =
            existingItem?.qty ||
            1;


        document
            .getElementById(
                'pdiscount'
            )
            .value =
            existingItem?.discount ||
            0;


        document
            .getElementById(
                'icomment'
            )
            .value =
            existingItem?.comment ||
            '';


        document
            .getElementById(
                'icommentname'
            )
            .value =
            existingItem?.commentName ||
            '';


        miniModal
            .querySelectorAll(
                '.chkComment'
            )
            .forEach(function (box) {

                box.checked =
                    !!existingItem?.comment &&
                    existingItem.comment
                        .split(',')
                        .map(x =>
                            x.trim()
                        )
                        .includes(
                            box.value.trim()
                        );

            });


        setMiniDiscountType(
            existingItem?.discountType ||
            'amount',
            false
        );


        const discountInput =
            document.getElementById(
                'pdiscount'
            );


        discountInput.oninput =
            function () {

                let value =
                    Math.max(
                        0,
                        parseFloat(
                            this.value
                        ) || 0
                    );


                if (
                    miniDiscountType ===
                    'amount'
                ) {

                    value =
                        Math.round(
                            value /
                            1000
                        ) *
                        1000;

                } else {

                    value =
                        Math.min(
                            100,
                            Math.round(
                                value
                            )
                        );

                }


                this.value =
                    value;


                updateMiniModalPrice();

            };


        miniModal
            .querySelectorAll(
                '.mini-discount-type-btn'
            )
            .forEach(function (button) {

                button.onclick =
                    function () {

                        setMiniDiscountType(
                            this.dataset
                                .discountType,
                            false
                        );

                    };

            });


        miniModal
            .querySelectorAll(
                '.chkComment'
            )
            .forEach(function (box) {

                box.onchange =
                    updateMiniNoteField;

            });


        const save =
            document.getElementById(
                'mini-modal-add'
            );


        if (save) {

            save.textContent =
                miniEditingRowId
                    ? 'CẬP NHẬT MÓN'
                    : 'THÊM VÀO ĐƠN';

        }


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

        if (!miniModal) return;


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


        miniEditingRowId =
            null;

    }


    function changeModalQuantity(
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


    function normalizeModalQuantity() {

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
     * SAVE MODAL
     * ========================================================= */

    function getModalData() {

        return {

            discount:
                getDiscountValue(),

            discountType:
                miniDiscountType,

            comment:
                document
                    .getElementById(
                        'icomment'
                    )
                    ?.value
                    .trim() ||
                '',

            commentName:
                document
                    .getElementById(
                        'icommentname'
                    )
                    ?.value
                    .trim() ||
                '',

            qty:
                Math.max(
                    1,
                    parseInt(
                        document
                            .getElementById(
                                'mini-qty'
                            )
                            ?.value,
                        10
                    ) || 1
                )

        };

    }


    function handleModalSave() {

        if (!miniModal) return;


        const id =
            String(
                miniModal.dataset.productId ||
                ''
            );


        const card =
            grid.querySelector(
                '.mini-product-card[data-product-id="' +
                CSS.escape(id) +
                '"]'
            );


        if (!card) return;


        const data =
            getModalData();


        /* EDIT DÒNG CŨ */

        if (miniEditingRowId) {

            const item =
                cart.find(function (row) {

                    return (
                        row.rowId ===
                        miniEditingRowId
                    );

                });


            if (item) {

                item.qty =
                    data.qty;

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

        /* THÊM DÒNG MỚI */

        else {

            addToCart(
                card,
                data.qty,
                data
            );

        }


        closeProductModal();

    }


    /* =========================================================
     * PRODUCT EVENTS
     * ========================================================= */

    function bindProductEvents() {

        grid
            .querySelectorAll(
                '.mini-product-card'
            )
            .forEach(function (card) {

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

            });

    }


    /* =========================================================
     * CART EVENTS
     * ========================================================= */

    function bindCartEvents() {

        const cartList =
            document.getElementById(
                'mini-cart-list'
            );


        if (!cartList) return;


        /* CLICK */

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


                if (!row) return;


                if (button) {

                    event.preventDefault();

                    event.stopPropagation();


                    changeCartQty(
                        row.dataset.rowId,
                        button.dataset
                            .cartAction ===
                            'plus'
                            ? 1
                            : -1
                    );


                    return;

                }


                if (
                    !event.target.closest(
                        '.mini-cart-swipe-delete'
                    )
                ) {

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

                        openProductModal(
                            card,
                            item
                        );

                    }

                }

            }
        );


        /* TOUCH START */

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
                    'swiping'
                );

            },
            {
                passive: true
            }
        );


        /* TOUCH MOVE */

        cartList.addEventListener(
            'touchmove',
            function (event) {

                if (!touchRow) return;


                touchCurrentX =
                    event.touches[0]
                        .clientX;


                const delta =
                    touchCurrentX -
                    touchStartX;


                if (
                    Math.abs(delta) >
                    8
                ) {

                    const move =
                        Math.max(
                            -110,
                            Math.min(
                                110,
                                delta
                            )
                        );


                    touchRow.style.transform =
                        `translateX(${move}px)`;

                }

            },
            {
                passive: true
            }
        );


        /* TOUCH END */

        cartList.addEventListener(
            'touchend',
            function () {

                if (!touchRow) return;


                const row =
                    touchRow;


                const delta =
                    touchCurrentX -
                    touchStartX;


                row.classList.remove(
                    'swiping'
                );


                row.style.transform =
                    '';


                if (
                    Math.abs(delta) >=
                    80
                ) {

                    removeCartRow(
                        row.dataset.rowId
                    );

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
     * HEADER
     * ========================================================= */

    function bindHeaderModes() {

        /* BỎ TẠI CHỖ */

        document
            .querySelectorAll(
                '.mini-header-mode[data-order-mode="dinein"]'
            )
            .forEach(function (button) {

                button.remove();

            });


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

                                item.classList.remove(
                                    'active'
                                );

                            });


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
                                    : 'MANG ĐI';

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

            });

    }


    /* =========================================================
     * CATEGORY
     * ========================================================= */

    function bindCategories() {

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

                                item.classList.remove(
                                    'active'
                                );

                            });


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
                            .forEach(function (card) {

                                card.style.display =
                                    category ===
                                    'all' ||
                                    category ===
                                    card.dataset
                                        .category
                                        ? ''
                                        : 'none';

                            });


                        grid.scrollLeft =
                            0;


                        updateSwiperPages();

                    }
                );

            });


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
                                this.dataset
                                    .categoryScroll ===
                                'left'
                                    ? -240
                                    : 240,

                            behavior:
                                'smooth'

                        });

                    }
                );

            });

    }


    /* =========================================================
     * SEARCH + BUTTON X
     * ========================================================= */

    function bindSearch() {

        const search =
            document.getElementById(
                'mini-product-search'
            );


        if (!search) return;


        const wrap =
            search.closest(
                '.mini-search-wrap'
            );


        if (wrap) {

            const oldShortcut =
                wrap.querySelector(
                    '.mini-search-shortcut'
                );


            if (oldShortcut) {
                oldShortcut.remove();
            }


            searchClear =
                wrap.querySelector(
                    '.mini-search-clear'
                );


            if (!searchClear) {

                searchClear =
                    document.createElement(
                        'button'
                    );


                searchClear.type =
                    'button';


                searchClear.className =
                    'mini-search-clear';


                searchClear.setAttribute(
                    'aria-label',
                    'Xóa tìm kiếm'
                );


                searchClear.textContent =
                    '×';


                wrap.appendChild(
                    searchClear
                );

            }


            searchClear.addEventListener(
                'click',
                function () {

                    search.value =
                        '';


                    search.dispatchEvent(
                        new Event(
                            'input',
                            {
                                bubbles:
                                    true
                            }
                        )
                    );


                    search.focus();

                }
            );

        }


        function applySearch() {

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
                            card.dataset
                                .name ||
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
                        !keyword ||
                        name.includes(
                            keyword
                        ) ||
                        code.includes(
                            keyword
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


            updateSwiperPages();

        }


        search.addEventListener(
            'input',
            applySearch
        );


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

                    search.focus();

                }


                if (
                    event.key ===
                    'Escape' &&
                    document.activeElement ===
                    search &&
                    search.value
                ) {

                    search.value =
                        '';

                    applySearch();

                }

            }
        );


        applySearch();

    }


    /* =========================================================
     * SWIPER DESKTOP / TABLET NGANG
     *
     * MOBILE KHÔNG PAGING
     * ========================================================= */

    function getSwiperPageWidth() {

        return Math.max(
            1,
            grid.clientWidth
        );

    }


    function getSwiperPageCount() {

        if (
            window.matchMedia(
                '(max-width: 767px)'
            ).matches
        ) {

            return 1;

        }


        return Math.max(
            1,
            Math.ceil(
                grid.scrollWidth /
                getSwiperPageWidth()
            )
        );

    }


    function getCurrentSwiperPage() {

        const count =
            getSwiperPageCount();


        if (
            count <= 1
        ) {

            return 1;

        }


        return Math.min(

            count,

            Math.floor(

                (
                    grid.scrollLeft +
                    getSwiperPageWidth() *
                    0.5
                ) /
                getSwiperPageWidth()

            ) + 1

        );

    }


    function updateSwiperPages() {

        const label =
            document.getElementById(
                'mini-page-label'
            );


        if (label) {

            label.textContent =
                getCurrentSwiperPage() +
                ' / ' +
                getSwiperPageCount();

        }


        const footer =
            document.querySelector(
                '.mini-products-footer'
            );


        if (footer) {

            footer.style.display =
                getSwiperPageCount() <=
                1 &&
                window.matchMedia(
                    '(max-width: 767px)'
                ).matches
                    ? 'none'
                    : '';

        }

    }


    function scrollProducts(
        direction
    ) {

        if (
            window.matchMedia(
                '(max-width: 767px)'
            ).matches
        ) {

            return;

        }


        grid.scrollBy({

            left:
                getSwiperPageWidth() *
                (
                    direction ===
                    'next'
                        ? 1
                        : -1
                ),

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
        updateSwiperPages
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

                if (
                    !cart.length
                ) return;


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
     * INIT
     * ========================================================= */

    bindProductEvents();

    bindCartEvents();

    bindHeaderModes();

    bindCategories();

    bindSearch();

    renderCart();


    window.requestAnimationFrame(
        updateSwiperPages
    );

});