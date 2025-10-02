/*
 * pos.mobile.js
 * Xử lý giỏ hàng POS Mobile
 * Author: ChatGPT
 */

let positems = JSON.parse(localStorage.getItem('positems')) || {};
let lastItemId = localStorage.getItem('lastItemId') ? parseInt(localStorage.getItem('lastItemId')) : 0;

// Lưu giỏ hàng vào localStorage
function savePosItems() {
    localStorage.setItem('positems', JSON.stringify(positems));
    localStorage.setItem('lastItemId', lastItemId);
}

// Thêm sản phẩm vào giỏ
function mobileAddItem(id, name, price, qty, option, note) {
    lastItemId++;
    const rowId = lastItemId;

    positems[rowId] = {
        id: rowId,
        row: {
            product_id: id,
            name: name,
            price: price,
            quantity: qty,
            option: option,
            comment: note
        }
    };

    savePosItems();
}

// Xóa sản phẩm
function mobileRemoveItem(rowId) {
    if (positems[rowId]) {
        delete positems[rowId];
        savePosItems();
    }
}

// Reset toàn bộ giỏ
function mobileResetCart() {
    positems = {};
    lastItemId = 0;
    savePosItems();
}

// Load giỏ hàng để submit form
function mobileLoadItems() {
    const hf = document.getElementById('posTable');
    hf.innerHTML = '';

    Object.values(positems).forEach((item, i) => {
        hf.innerHTML += `<input type="hidden" name="product_id[]" value="${item.row.product_id}">`;
        hf.innerHTML += `<input type="hidden" name="product_name[]" value="${item.row.name}">`;
        hf.innerHTML += `<input type="hidden" name="product_price[]" value="${item.row.price}">`;
        hf.innerHTML += `<input type="hidden" name="product_qty[]" value="${item.row.quantity}">`;
        hf.innerHTML += `<input type="hidden" name="product_option[]" value="${item.row.option || ''}">`;
        hf.innerHTML += `<input type="hidden" name="product_note[]" value="${item.row.comment || ''}">`;
    });
}
