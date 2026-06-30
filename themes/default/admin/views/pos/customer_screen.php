<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
    
    <script type="text/javascript">if(parent.frames.length !== 0){top.location = '<?=admin_url('pos')?>';}</script>
    <base href="<?=base_url()?>"/>
    <meta http-equiv="cache-control" content="max-age=0"/>
    <meta http-equiv="cache-control" content="no-cache"/>
    <meta http-equiv="expires" content="0"/>
    <meta http-equiv="pragma" content="no-cache"/>
    <link rel="shortcut icon" href="<?=$assets?>images/icon.png"/>
    <link rel="stylesheet" href="<?=$assets?>styles/theme.css" type="text/css"/>
    <link rel="stylesheet" href="<?=$assets?>styles/style.css" type="text/css"/>
    <link rel="stylesheet" href="<?=$assets?>pos/css/posajax.css" type="text/css"/>
    <link rel="stylesheet" href="<?=$assets?>pos/css/print.css" type="text/css" media="print"/>
    <script type="text/javascript" src="<?=$assets?>js/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="<?=$assets?>js/jquery-migrate-1.2.1.min.js"></script>

<title>Customer Screen</title>

<style>

html,body{
    margin:0;
    padding:0;
    width:100%;
    height:100%;
    overflow:hidden;
    font-family:Arial,Helvetica,sans-serif;
}

#ads-screen,
#sale-screen{
    width:100%;
    height:100%;
}

#ads-screen{
    display:flex;
    align-items:center;
    justify-content:center;
    background:#000;
}

#ads-screen img{
    width:100%;
    height:100%;
    object-fit:cover;
}

#sale-screen{
    display:none;
    background:#fff;
    padding:20px;
    box-sizing:border-box;
}

.header{
    font-size:40px;
    font-weight:bold;
    margin-bottom:20px;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#f5f5f5;
}

th,td{
    padding:15px;
    border:1px solid #ddd;
    font-size:26px;
}

#total{
    margin-top:20px;
    text-align:right;
    font-size:60px;
    color:#e53935;
    font-weight:bold;
}

</style>
</head>

<body>

<div id="ads-screen">

    <img src="<?= base_url('assets/uploads/customer-screen-banner.jpg'); ?>">

</div>

<div id="sale-screen">

    <div class="header">
        ĐƠN HÀNG CỦA BẠN
    </div>

    <table>

        <thead>
            <tr>
                <th>Món</th>
                <th>SL</th>
            </tr>
        </thead>

        <tbody id="cart-body"></tbody>

    </table>

    <div id="total">0đ</div>

</div>

<script>

let lastActivity = 0;

function renderSale(data)
{
    if(!data){
        return;
    }

    lastActivity = Date.now();

    $('#ads-screen').hide();
    $('#sale-screen').show();

    let html = '';

    if(data.items){

        data.items.forEach(function(item){

            html += `
                <tr>
                    <td>${item.name}</td>
                    <td style="text-align:center">${item.qty}</td>
                </tr>
            `;
        });

    }

    $('#cart-body').html(html);

    $('#total').html(data.total || '0đ');
}

function loadCustomerScreen()
{
    //console.log('1111');
    $.getJSON(
        '<?= admin_url("pos/get_customer_screen"); ?>',
        function(res){
            //console.log('222');
            //console.log(res);

            if(res && res.data){
                //console.log('333');
                renderSale(
                    JSON.parse(res.data)
                );

            }

        }
    );
}

setInterval(loadCustomerScreen,1000);

setInterval(function(){

    let diff = (Date.now() - lastActivity) / 1000;

    if(diff > 30){

        $('#sale-screen').hide();
        $('#ads-screen').show();

    }

},1000);

</script>

</body>
</html>