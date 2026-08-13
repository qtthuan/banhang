document.addEventListener('DOMContentLoaded', function () {

    const grid = document.getElementById('mini-product-grid');

    if (!grid) return;


    /* =========================================================
     * MINI POS - DARK SUZLON
     * ========================================================= */

    let miniModal = null;

    let miniBasePrice = 0;

    let miniDiscountType = 'amount';

    const cart = new Map();


    /* =========================================================
     * FORMAT TIỀN
     * ========================================================= */

    function money(value) {

        return (
            Math.max(
                0,
                Math.round(
                    Number(value) || 0
                )
            ).toLocaleString('vi-VN') + 'đ'
        );

    }


    /* =========================================================
     * ESCAPE HTML
     * ========================================================= */

    function escapeHtml(value) {

        return String(value || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

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
            .forEach(function (el) {

                el.remove();

            });


        if (!cart.size) {

            if (empty) {

                empty.style.display =
                    'flex';

            }

        } else {

            if (empty) {

                empty.style.display =
                    'none';

            }


            cart.forEach(function (item) {

                const row =
                    document.createElement(
                        'div'
                    );


                row.className =
                    'mini-cart-row';


                row.dataset.productId =
                    item.id;


                row.innerHTML = `

                    <div class="mini-cart-name">

                        <strong>
                            ${escapeHtml(item.name)}
                        </strong>

                        <small>
                            ${escapeHtml(item.code || '')}
                        </small>

                    </div>


                    <div class="mini-cart-price">

                        ${money(item.price)}

                    </div>


                    <div class="mini-cart-qty">

                        <button
                            type="button"
                            data-cart-action="minus"
                        >
                            −
                        </button>


                        <span>
                            ${item.qty}
                        </span>


                        <button
                            type="button"
                            data-cart-action="plus"
                        >
                            +
                        </button>

                    </div>


                    <div class="mini-cart-total">

                        ${money(
                            item.price *
                            item.qty
                        )}

                    </div>

                `;


                list.appendChild(
                    row
                );

            });

        }


        /* =====================================================
         * TÍNH TỔNG
         * ===================================================== */

        let qty = 0;

        let subtotal = 0;


        cart.forEach(function (item) {

            qty += item.qty;

            subtotal +=
                item.price *
                item.qty;

        });


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
                '0đ';

        }


        if (deliveryEl) {

            deliveryEl.textContent =
                '0đ';

        }


        if (grandEl) {

            grandEl.textContent =
                money(subtotal);

        }

    }


    /* =========================================================
     * ADD TO CART
     * ========================================================= */

    function addToCart(
        card,
        quantity
    ) {

        const id =
            String(
                card.dataset.productId
            );


        const item =
            cart.get(id);


        const data = {

            id: id,

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
                quantity || 1

        };


        if (item) {

            item.qty +=
                data.qty;

        } else {

            cart.set(
                id,
                data
            );

        }


        renderCart();


        /* Animation */

        card.classList.remove(
            'quick-added'
        );


        void card.offsetWidth;


        card.classList.add(
            'quick-added'
        );

    }


    /* =========================================================
     * CHANGE CART QUANTITY
     * ========================================================= */

    function changeCartQty(
        id,
        delta
    ) {

        const item =
            cart.get(id);


        if (!item) return;


        item.qty +=
            delta;


        if (item.qty <= 0) {

            cart.delete(id);

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
                            comment.id || ''
                        );


                    const text =
                        String(
                            comment.comment || ''
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
                            for="mini-comment-${escapeHtml(id)}"
                        >

                            <input
                                class="
                                    mini-note-checkbox
                                    chkComment
                                "
                                type="checkbox"
                                id="mini-comment-${escapeHtml(id)}"
                                value="${escapeHtml(text)}"
                            >


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

        if (
            document.getElementById(
                'mini-product-modal'
            )
        ) {

            miniModal =
                document.getElementById(
                    'mini-product-modal'
                );

            return;

        }


        const html = `

            <div
                id="mini-product-modal"
                class="mini-modal"
                aria-hidden="true"
            >

                <div
                    class="mini-modal-overlay"
                    data-mini-modal-close
                ></div>


                <div
                    class="mini-modal-dialog"
                    role="dialog"
                    aria-modal="true"
                >


                    <!-- HEADER -->

                    <div class="mini-modal-header">

                        <div>

                            <div
                                id="mini-modal-product-name"
                                class="mini-modal-header-name"
                            >
                                Chi tiết món
                            </div>


                            <div
                                id="mini-modal-product-price"
                                class="mini-modal-header-price"
                            >
                                0đ
                            </div>

                        </div>


                        <button
                            type="button"
                            class="mini-modal-close"
                            data-mini-modal-close
                        >
                            ×
                        </button>

                    </div>


                    <!-- BODY -->

                    <div class="mini-modal-body">


                        <!-- SỐ LƯỢNG + GIẢM GIÁ -->

                        <div
                            class="
                                mini-modal-two-col-top
                            "
                        >


                            <!-- SỐ LƯỢNG -->

                            <div class="mini-modal-field">

                                <label>
                                    Số lượng
                                </label>


                                <div
                                    class="
                                        mini-modal-quantity
                                    "
                                >

                                    <button
                                        type="button"
                                        id="mini-qty-minus"
                                        class="mini-qty-btn"
                                    >
                                        −
                                    </button>


                                    <input
                                        type="number"
                                        id="mini-qty"
                                        class="mini-qty-input"
                                        value="1"
                                        min="1"
                                        inputmode="numeric"
                                    >


                                    <button
                                        type="button"
                                        id="mini-qty-plus"
                                        class="mini-qty-btn"
                                    >
                                        +
                                    </button>

                                </div>

                            </div>


                            <!-- GIẢM GIÁ -->

                            <div class="mini-modal-field">

                                <label>
                                    Giảm giá
                                </label>


                                <div
                                    class="
                                        mini-discount-control
                                    "
                                >


                                    <div
                                        class="
                                            mini-discount-stepper
                                        "
                                    >

                                        <button
                                            type="button"
                                            id="mini-discount-minus"
                                            class="
                                                mini-discount-step-btn
                                            "
                                        >
                                            −
                                        </button>


                                        <div
                                            class="
                                                mini-input-money
                                            "
                                        >

                                            <input
                                                type="text"
                                                id="pdiscount"
                                                class="
                                                    mini-modal-input
                                                "
                                                value="0"
                                                autocomplete="off"
                                                inputmode="numeric"
                                            >


                                            <span
                                                id="mini-discount-suffix"
                                            >
                                                đ
                                            </span>

                                        </div>


                                        <button
                                            type="button"
                                            id="mini-discount-plus"
                                            class="
                                                mini-discount-step-btn
                                            "
                                        >
                                            +
                                        </button>

                                    </div>


                                    <div
                                        class="
                                            mini-discount-type
                                        "
                                    >

                                        <button
                                            type="button"
                                            class="
                                                mini-discount-type-btn
                                                active
                                            "
                                            data-discount-type="amount"
                                        >
                                            đ
                                        </button>


                                        <button
                                            type="button"
                                            class="
                                                mini-discount-type-btn
                                            "
                                            data-discount-type="percent"
                                        >
                                            %
                                        </button>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <!-- GHI CHÚ + TÊN LY -->

                        <div
                            class="
                                mini-modal-two-col-bottom
                            "
                        >


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
                                    placeholder="
                                        Chọn ghi chú hoặc nhập thêm...
                                    "
                                >

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
                                    placeholder="Tên khách..."
                                >

                            </div>

                        </div>


                        <!-- GHI CHÚ NHANH -->

                        <div
                            class="
                                mini-modal-section
                            "
                        >

                            <div
                                class="
                                    mini-modal-label
                                "
                            >

                                Ghi chú nhanh

                            </div>


                            <div
                                id="mini-quick-notes-list"
                                class="mini-note-grid"
                            ></div>

                        </div>

                    </div>


                    <!-- FOOTER -->

                    <div
                        class="
                            mini-modal-footer
                        "
                    >

                        <button
                            type="button"
                            class="
                                mini-modal-btn
                                mini-modal-btn-cancel
                            "
                            data-mini-modal-close
                        >
                            HỦY
                        </button>


                        <button
                            type="button"
                            id="mini-modal-add"
                            class="
                                mini-modal-btn
                                mini-modal-btn-primary
                            "
                        >
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


        /* Đóng */

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


        /* Quantity */

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


        /* Discount */

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


        /* Add */

        document
            .getElementById(
                'mini-modal-add'
            )
            .addEventListener(
                'click',
                handleModalAdd
            );


        /* ESC */

        document.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key === 'Escape' &&
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
     * GET DISCOUNT
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
     * UPDATE MODAL PRICE
     * ========================================================= */

    function updateMiniModalPrice() {

        const value =
            getDiscountValue();


        let discount =
            miniDiscountType ===
            'percent'

                ? miniBasePrice *
                  value /
                  100

                : value;


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

            input.value =
                0;


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
                            button.dataset.discountType ===
                            miniDiscountType
                        );

                    }
                );

        }


        updateMiniModalPrice();

    }


    /* =========================================================
     * DISCOUNT − / +
     * ========================================================= */

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
     * UPDATE COMMENT
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
                    function (x) {

                        return x.trim();

                    }
                )
                .filter(
                    function (x) {

                        return (
                            x &&
                            known.indexOf(
                                x
                            ) === -1
                        );

                    }
                );


        input.value =
            manual
                .concat(
                    quick
                )
                .join(
                    ', '
                );

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
            card.dataset.productId;


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
            1;


        document
            .getElementById(
                'pdiscount'
            )
            .value =
            0;


        document
            .getElementById(
                'icomment'
            )
            .value =
            '';


        document
            .getElementById(
                'icommentname'
            )
            .value =
            '';


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
            .forEach(
                function (button) {

                    button.onclick =
                        function () {

                            setMiniDiscountType(
                                this.dataset.discountType
                            );

                        };

                }
            );


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
     * ADD FROM MODAL
     * ========================================================= */

    function handleModalAdd() {

        if (!miniModal) return;


        const id =
            String(
                miniModal.dataset.productId ||
                ''
            );


        const card =
            grid.querySelector(
                '.mini-product-card[data-product-id="' +
                id +
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


        addToCart(
            card,
            qty
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


                /* Click món */

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


                /* Edit */

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
                    String(
                        row.dataset.productId
                    ),
                    button.dataset.cartAction ===
                        'plus'
                        ? 1
                        : -1
                );

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
                                    card.dataset.name ||
                                    ''
                                )
                                    .toLowerCase();


                            card.style.display =
                                (
                                    !keyword ||
                                    name.indexOf(
                                        keyword
                                    ) !== -1
                                )
                                    ? ''
                                    : 'none';

                        }
                    );

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

                                    item.classList.remove(
                                        'active'
                                    );

                                }
                            );


                        this.classList.add(
                            'active'
                        );


                        const category =
                            this.dataset.category;


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
                                                card.dataset.category
                                        )
                                            ? ''
                                            : 'none';

                                }
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


                        if (!categories) return;


                        categories.scrollBy(
                            {
                                left:
                                    this.dataset.categoryScroll ===
                                        'left'
                                        ? -220
                                        : 220,

                                behavior:
                                    'smooth'
                            }
                        );

                    }
                );

            }
        );


    /* =========================================================
     * CANCEL ORDER
     * ========================================================= */

    const cancel =
        document.getElementById(
            'mini-cancel-order'
        );


    if (cancel) {

        cancel.addEventListener(
            'click',
            function () {

                if (!cart.size) return;


                if (
                    window.confirm(
                        'Bạn có chắc muốn hủy đơn hiện tại?'
                    )
                ) {

                    cart.clear();

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

});