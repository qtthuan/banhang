/* ==========================================================
   Customer Screen JS
   Phase 1
========================================================== */

"use strict";

/* ----------------------------------------------------------
    GLOBAL
---------------------------------------------------------- */

var CS = {

    lastUpdated : "",
    lastMode    : "",
    lastData    : null,
    lastRowNo : 0,
    currentTotal : 0,

    adsIndex : 0,

    adsTimer : null,

    adsCurrent : 1,

    adsPreloaded : [],

    adsStarted : false,

    adsTransitioning : false,

    pollTime    : 500,

    loading     : false

};


/* ----------------------------------------------------------
    INIT
---------------------------------------------------------- */

$(function(){

    console.log('init screen: ');

    /* Preload toàn bộ ảnh quảng cáo */
    preloadAds();

    updateClock();

    setInterval(updateClock,1000);

    loadCustomerScreen();

    setInterval(loadCustomerScreen,CS.pollTime);
    console.log('end cuss screen: ');

});


/*----------------------------------------------------------
    TOTAL ANIMATION
----------------------------------------------------------*/
function animateTotal(newTotal)
{
    newTotal = Number(newTotal) || 0;

    let start = CS.currentTotal;
    let end   = newTotal;

    if(start === end){

        $("#grand-total").text(formatMoney(end));

        return;
    }

    let $total = $("#grand-total");

    $total.addClass("total-changing");

    let duration = 350;
    let startTime = null;

    function step(timestamp)
    {
        if(!startTime){
            startTime = timestamp;
        }

        let progress = (timestamp - startTime) / duration;

        if(progress > 1){
            progress = 1;
        }

        let value = Math.round(
            start + (end - start) * progress
        );

        $total.text(
            formatMoney(value)
        );

        if(progress < 1){

            requestAnimationFrame(step);

        }else{

            CS.currentTotal = end;

            $total.removeClass("total-changing");

            // Reset animation để có thể chạy nhiều lần
            $total.removeClass("total-bounce");

            void $total[0].offsetWidth;

            $total.addClass("total-bounce");

        }
    }

    requestAnimationFrame(step);
}

/* ----------------------------------------------------------
    CLOCK
---------------------------------------------------------- */

function updateClock()
{
    let now = new Date();

    let hh = pad(now.getHours());
    let mm = pad(now.getMinutes());
    let ss = pad(now.getSeconds());

    let dd = pad(now.getDate());
    let MM = pad(now.getMonth()+1);
    let yyyy = now.getFullYear();

    $("#clock-time").text(
        hh+":"+mm+":"+ss
    );

    $("#clock-date").text(
        dd+"/"+MM+"/"+yyyy
    );
}

function pad(n)
{
    return n<10 ? "0"+n : n;
}


/* ----------------------------------------------------------
    LOAD SERVER
---------------------------------------------------------- */

function loadCustomerScreen()
{

    if(CS.loading){
        return;
    }

    CS.loading=true;

    $.getJSON(customerScreen.getUrl,function(res){

        CS.loading=false;

        if(!res){
            showWaiting();
            return;
        }

        processScreenData(res);

    }).fail(function(){

        CS.loading=false;

        showWaiting();

    });

}


/* ----------------------------------------------------------
    PROCESS
---------------------------------------------------------- */

function processScreenData(res)
{

    if(!res.updated_at){

        showWaiting();

        return;

    }

    if(
        CS.lastUpdated===res.updated_at
    ){
        return;
    }

    CS.lastUpdated=res.updated_at;

    CS.lastMode=res.mode;

    if(res.mode==="ads"){

        showWaiting();

        return;

    }

    if(!res.data){

        showWaiting();

        return;

    }

    if(
        res.mode=="payment_wait" ||
        res.mode=="payment_cash" ||
        res.mode=="payment_bank" ||
        res.mode=="payment_success"
    ) {

        let pay;

        try{

            pay=JSON.parse(res.data);
            console.log("PAY:", pay);

        }catch(e){ 

            return;

        }

        showPayment();

        switch(res.mode){

            case "payment_wait":

                renderPaymentWait(pay);

                break;

            case "payment_cash":

                renderPaymentCash(pay);

                break;

            case "payment_bank":

                renderPaymentBank(pay);

                break;
            case "payment_success":

                renderPaymentSuccess();

                break;

        }

        return;

    }


    let bill;

    try{

        bill=JSON.parse(res.data);

    }catch(e){

        console.log(e);

        showWaiting();

        return;

    }

    CS.lastData=bill;

    showBill();

    renderBill(bill);

}

/* ----------------------------------------------------------
    ADS PRELOAD
---------------------------------------------------------- */

function preloadAds()
{
    if(!customerScreen.ads || customerScreen.ads.length === 0){
        return;
    }

    $.each(customerScreen.ads, function(i, file){

        var url = customerScreen.adsUrl + file;

        var img = new Image();

        img.src = url;

        CS.adsPreloaded[i] = img;

    });

}

/* ----------------------------------------------------------
    ADS
---------------------------------------------------------- */


/* Khởi động quảng cáo */

function startAds()
{
    if(CS.adsStarted){
        return;
    }

    if(!customerScreen.ads || customerScreen.ads.length === 0){
        return;
    }

    CS.adsStarted = true;

    CS.adsIndex = 0;

    CS.adsCurrent = 1;

    showFirstAd();

    if(customerScreen.ads.length > 1){

        CS.adsTimer = setInterval(function(){

            nextAds();

        }, 5000);

    }

}


/* Dừng timer quảng cáo */

function stopAds()
{
    if(CS.adsTimer){

        clearInterval(CS.adsTimer);

        CS.adsTimer = null;

    }

    CS.adsStarted = false;

    CS.adsTransitioning = false;

}


/* Hiện ảnh đầu tiên */

function showFirstAd()
{
    var file = customerScreen.ads[CS.adsIndex];

    var url = customerScreen.adsUrl + file;

    var $img1 = $("#ads-image-1");

    var $img2 = $("#ads-image-2");

    var $bg1 = $("#ads-bg-1");

    var $bg2 = $("#ads-bg-2");


    /* Xác định ảnh dọc hay ngang */

    var preloaded = CS.adsPreloaded[CS.adsIndex];

    var portrait = false;

    if(preloaded && preloaded.naturalHeight > preloaded.naturalWidth){

        portrait = true;

    }


    /* Reset ảnh */

    $img1
        .removeClass("portrait landscape")
        .addClass(portrait ? "portrait" : "landscape")
        .attr("src", url)
        .css({
            opacity : 1,
            transform : "translate(-50%,-50%) scale(1)"
        })
        .addClass("active");


    $img2
        .removeClass("active")
        .css({
            opacity : 0,
            transform : "translate(-50%,-50%) scale(1)"
        });


    /* Background */

    $bg1
        .css("background-image", "url('" + url + "')")
        .addClass("active");


    $bg2
        .removeClass("active");


    CS.adsCurrent = 1;


    /* Zoom nhẹ */

    requestAnimationFrame(function(){

        requestAnimationFrame(function(){

            $img1.css(
                "transform",
                "translate(-50%,-50%) scale(1.03)"
            );

        });

    });

}


/* Chuyển sang ảnh tiếp theo */

function nextAds()
{
    if(CS.adsTransitioning){
        return;
    }

    if(customerScreen.ads.length <= 1){
        return;
    }


    CS.adsTransitioning = true;


    /* Ảnh tiếp theo */

    var nextIndex = CS.adsIndex + 1;

    if(nextIndex >= customerScreen.ads.length){

        nextIndex = 0;

    }


    var file = customerScreen.ads[nextIndex];

    var url = customerScreen.adsUrl + file;


    /* Xác định ảnh dọc / ngang */

    var preloaded = CS.adsPreloaded[nextIndex];

    var portrait = false;

    if(preloaded && preloaded.naturalHeight > preloaded.naturalWidth){

        portrait = true;

    }


    /* Xác định ảnh đang hiện và ảnh kế tiếp */

    var currentNumber = CS.adsCurrent;

    var nextNumber = currentNumber === 1 ? 2 : 1;


    var $currentImg = $("#ads-image-" + currentNumber);

    var $nextImg = $("#ads-image-" + nextNumber);

    var $currentBg = $("#ads-bg-" + currentNumber);

    var $nextBg = $("#ads-bg-" + nextNumber);


    /* Chuẩn bị ảnh mới ở phía sau */

    $nextImg
        .removeClass("portrait landscape")
        .addClass(portrait ? "portrait" : "landscape")
        .attr("src", url)
        .removeClass("active")
        .css({
            opacity : 0,
            transform : "translate(-50%,-50%) scale(1)"
        });


    /* Chuẩn bị background mới */

    $nextBg
        .css("background-image", "url('" + url + "')")
        .removeClass("active");


    /*
        Ép browser render trạng thái mới trước
        rồi mới bắt đầu crossfade
    */

    requestAnimationFrame(function(){

        requestAnimationFrame(function(){


            /* Ảnh cũ fade out */

            $currentImg
                .removeClass("active")
                .css({
                    opacity : 0,
                    transform : "translate(-50%,-50%) scale(1.03)"
                });


            /* Ảnh mới fade in */

            $nextImg
                .addClass("active")
                .css({
                    opacity : 1,
                    transform : "translate(-50%,-50%) scale(1)"
                });


            /* Background chuyển cùng lúc */

            $currentBg.removeClass("active");

            $nextBg.addClass("active");


            /* Bắt đầu zoom nhẹ ảnh mới */

            setTimeout(function(){

                $nextImg.css(
                    "transform",
                    "translate(-50%,-50%) scale(1.03)"
                );

            }, 50);


        });

    });


    /* Lưu ảnh hiện tại */

    CS.adsIndex = nextIndex;

    CS.adsCurrent = nextNumber;


    /* Mở khóa sau khi animation xong */

    setTimeout(function(){

        CS.adsTransitioning = false;

    }, 900);

}


/* ----------------------------------------------------------
    WAITING
---------------------------------------------------------- */

function showWaiting()
{
    $("#payment-screen").removeClass("active");
    $("#bill-screen").removeClass("active");

    startAds();

    $("#waiting-screen").addClass("active");

    clearBill();

    $("#cs-footer").addClass("hide");
}


/* ----------------------------------------------------------
    BILL
---------------------------------------------------------- */

function showBill()
{
    stopAds();

    $("#waiting-screen").removeClass("active");

    $("#payment-screen").removeClass("active");

    $("#bill-screen").addClass("active");

    $("#cs-footer").removeClass("hide");
}

function showPayment()
{
    stopAds();

    $("#waiting-screen").removeClass("active");

    $("#bill-screen").removeClass("active");

    $("#payment-screen").addClass("active");

    $("#cs-footer").addClass("hide");
}

function renderPaymentWait(data)
{
    $("#payment-content").html(

        '<div class="payment-title">TỔNG THANH TOÁN</div>' +

        '<div class="payment-divider"></div>' +

        '<div class="payment-total">' +
            formatMoney(data.total) +
        '</div>' +

        '<div class="payment-divider small"></div>' +

        '<div class="payment-wait">' +
            'Vui lòng chọn phương thức thanh toán' +
        '</div>' +

        '<div class="payment-method-preview">' +

            '<div class="payment-preview cash">' +

                '<div class="preview-icon">💵</div>' +

                '<div class="preview-title">TIỀN MẶT</div>' +

            '</div>' +

            '<div class="payment-preview bank">' +

                '<div class="preview-icon">🏦</div>' +

                '<div class="preview-title">CHUYỂN KHOẢN</div>' +

            '</div>' +

        '</div>'

    );
}

function renderPaymentCash(data)
{
    $("#payment-content").html(

        '<div class="payment-method cash">' +
            'TIỀN MẶT' +
        '</div>' +

        '<div class="payment-total">' +
            formatMoney(data.total) +
        '</div>' +

        '<div class="payment-divider"></div>' +

        '<div class="payment-info">' +
            '<span class="payment-label">Khách đưa</span>' +
            '<span class="payment-value">' +
                formatMoney(data.paid || 0) +
            '</span>' +
        '</div>' +

        '<div class="payment-info">' +
            '<span class="payment-label">Tiền thối</span>' +
            '<span class="payment-change">' +
                formatMoney(data.change || 0) +
            '</span>' +
        '</div>'

    );
}

function renderPaymentBank(data)
{
    var amount = Number(data.bank_amount || data.total || 0);

    var qr = customerScreen.qrBase
        + "?amount=" + amount
        + "&accountName=" + customerScreen.accountName
        + "&addInfo=" + customerScreen.bankInfo;

    $("#payment-content").html(

        '<div class="payment-method bank">' +
            'CHUYỂN KHOẢN' +
        '</div>' +

        '<div class="payment-bank-amount">' +
            formatMoney(amount) +
        '</div>' +

        '<div class="payment-divider"></div>' +

        '<div class="payment-qr-wrap">' +
            '<img class="payment-qr" src="' + qr + '">' +
        '</div>' +

        '<div class="payment-bank-info">' +
            customerScreen.bankInfoText +
        '</div>'

    );
}

function renderPaymentSuccess()
{
    showPayment();

    $("#payment-content").html(

        '<div class="payment-success-icon">✓</div>' +

        '<div class="payment-success-title">' +

            'THANH TOÁN THÀNH CÔNG' +

        '</div>' +

        '<div class="payment-success-message">' +

            'Cảm ơn Quý khách!<br>Hẹn gặp lại.' +

        '</div>'

    );

}

function clearBill()
{
    $("#bill-list").empty();

    CS.lastRowNo = 0;
    CS.currentTotal = 0;

    $("#grand-total").text(
        formatMoney(0)
    );

    $("#total-items-number").text("0 MÓN");
}


/* ----------------------------------------------------------
    PLACE HOLDER
    (Phase 2)
---------------------------------------------------------- */

/* ----------------------------------------------------------
    RENDER BILL
---------------------------------------------------------- */

function renderBill(data)
{
    if (!data || !data.items || data.items.length === 0) {

        showWaiting();
        return;

    }

    // Tổng số món (tính theo quantity)
    var totalItems = 0;

    $.each(data.items, function(i, item){
        totalItems += Number(item.qty || 0);
    });

    $("#total-items-number").text(totalItems + " MÓN");

    // Sắp xếp món mới lên đầu
    data.items.sort(function(a, b) {

        return Number(b.row_no) - Number(a.row_no);

    });

    var newestRowNo = Number(data.items[0].row_no);

    let $list = $("#bill-list");

    $list.empty();

    $.each(data.items, function(i, item){

        let card = renderItem(item);

        // Chỉ món mới nhất mới có hiệu ứng
        if (Number(item.row_no) === newestRowNo &&
            newestRowNo !== CS.lastRowNo) {

            card
                .addClass("highlight-new item-enter");

            $list.append(card);

            // ép browser render trước
            requestAnimationFrame(function () {

                card.addClass("item-enter-active");

            });

            // hết hiệu ứng vàng
            setTimeout(function () {

                card.removeClass("highlight-new");

            }, 1200);

        } else {

            $list.append(card);

        }

    });

    // Lưu row mới nhất
    CS.lastRowNo = newestRowNo;

    // Hiệu ứng tổng tiền
    animateTotal(data.total);
}
/* ----------------------------------------------------------
    ITEM
---------------------------------------------------------- */

function renderItem(item)
{

    let note = item.note || "";
    let size  = item.size || "";
    let qty   = Number(item.qty || 1);
    let price = Number(item.price || 0);

    let originalPrice = Number(item.original_price || price);

    let hasDiscount = originalPrice > price;

    let html = $("<div>")
        .addClass("bill-item new")
        .attr("data-row", item.row_no);;

    let icon = $("<div>")
        .addClass("bill-icon");

    if (item.image && item.image !== "") {

        $("<img>")
            .attr("src", customerScreen.imageUrl + item.image)
            .attr("alt", item.name)
            .appendTo(icon);

    } else {

        icon.html("🥤");

    }

    let info = $("<div>")
        .addClass("bill-info");

    let title = $("<div>")
        .addClass("bill-title");

    $("<span>")
        .addClass("bill-name")
        .text(item.name)
        .appendTo(title);


    if (size != "") {

        let badgeClass = "bill-option";
        let sizeText = size.toUpperCase().replace("SIZE ", "");

        switch(sizeText){

            case "M":
                badgeClass += " size-m";
                break;

            case "L":
                badgeClass += " size-l";
                break;

            case "XL":
                badgeClass += " size-xl";
                break;

            default:
                badgeClass += " size-default";
                break;
        }

        $("<span>")
            .addClass(badgeClass)
            .html('<span class="dot"></span>' + sizeText)
            .appendTo(title);
    }

    title.appendTo(info);

    // $("<div>")
    //     .addClass("bill-name")
    //     .text(item.name)
    //     .appendTo(info);


    // Dòng Size + Ghi chú
    if (size !== "" || note !== "") {

        let extra = $("<div>")
            .addClass("bill-extra");

        // if (size !== "") {
        //     $("<span>")
        //         .addClass("bill-option")
        //         .text(size.toUpperCase())
        //         .appendTo(extra);
        // }

        if (note !== "") {
            $("<span>")
                .addClass("bill-note")
                .text(note)
                .appendTo(extra);
        }

        extra.appendTo(info);
    }


    let right = $("<div>")
        .addClass("bill-right");

    $("<div>")
        .addClass("bill-qty")
        .text("x" + qty)
        .appendTo(right);

    let priceBox = $("<div>")
        .addClass("bill-price-box");    

    if (hasDiscount) {

        $("<div>")
            .addClass("bill-original-price")
            .text(formatMoney(originalPrice))
            .appendTo(priceBox);
    }

    $("<div>")
        .addClass("bill-price")
        .text(formatMoney(price))
        .appendTo(priceBox);

    priceBox.appendTo(right);

    html
        .append(icon)
        .append(info)
        .append(right);

    return html;
}

/* ----------------------------------------------------------
    MONEY
---------------------------------------------------------- */

function formatMoney(number)
{

    number=parseFloat(number);

    if(isNaN(number))
        number=0;

    return number.toLocaleString(
        "vi-VN"
    )+"đ";

}