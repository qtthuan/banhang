<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="box">
    <div class="box-header no-print">
        <h2 class="blue">
            <i class="fa fa-tags"></i> IN TEM TẠM TỪ POS
        </h2>
    </div>

    <div class="box-content">
        <div id="barcode-con">

            <button type="button"
                    class="btn btn-primary btn-block no-print"
                    onclick="window.print();">
                <i class="fa fa-print"></i> IN TEM
            </button>

            <div class="barcode_mini">

                <?php
                $style = '10_1';

                foreach ($items as $item):

                    // Các sản phẩm không in tem theo quy định hiện tại
                    $excluded_ids = array(
                        21121, 21122, 20354, 20357, 20358,
                        22133, 22153, 22154, 22178, 22179,
                        22180, 22997, 22181, 22185, 22288
                    );

                    if (in_array((int)$item['product_id'], $excluded_ids)) {
                        continue;
                    }

                    $qty = max(0, (int)$item['qty']);

                    // Tách tên khách theo từng ly
                    $names = array();

                    if (!empty($item['comment_name'])) {
                        $names = array_map(
                            'trim',
                            explode(',', $item['comment_name'])
                        );
                    }

                    for ($i = 0; $i < $qty; $i++):

                        $comment = isset($item['comment'])
                            ? trim($item['comment'])
                            : '';

                        $name = isset($names[$i])
                            ? $names[$i]
                            : '';

                        $product_name = htmlspecialchars(
                            $item['name'],
                            ENT_QUOTES,
                            'UTF-8'
                        );

                        $comment_html = htmlspecialchars(
                            $comment,
                            ENT_QUOTES,
                            'UTF-8'
                        );

                        $customer_html = htmlspecialchars(
                            $name,
                            ENT_QUOTES,
                            'UTF-8'
                        );

                        $option = isset($item['option'])
                            ? strtolower(trim($item['option']))
                            : '';

                        $increase_size = 'increase_size';

                        if ($comment !== '') {
                            $increase_size .= '_1';
                        }
                ?>

                    <div class="item_1 style<?= $style; ?>">

                        <span
                            class="barcode_name <?= $increase_size; ?>"
                            style="position:absolute; top:0;">

                            <?= $product_name; ?>

                            <?php if ($comment !== ''): ?>
                                <br>
                                <span class="comment"
                                      style="font-size:11px;">
                                    <strong><?= $comment_html; ?></strong>
                                </span>
                            <?php endif; ?>

                        </span>

                        <?php if ($customer_html !== ''): ?>
                            <span class="circle_text1"
                                  style="position:absolute;
                                         bottom:32px;
                                         left:3px;
                                         font-size:16px;
                                         font-weight:bold;">
                                <strong><?= $customer_html; ?></strong>
                            </span>
                        <?php endif; ?>

                        <?php if ($option === 'size l' || $option === 'size m'): ?>
                            <span class="circle_text"
                                  style="position:absolute;
                                         bottom:22px;
                                         right:3px;
                                         font-size:16px;
                                         font-weight:bold;">
                                <?= strtoupper(substr($option, -1)); ?>
                            </span>
                        <?php endif; ?>

                        <h4 style="margin:1px;
                                   position:absolute;
                                   bottom:0;
                                   font-size:18px;">

                            <span class="reference_no"></span>

                            <span class="text_price">
                                <?= $this->sma->formatK($item['price']); ?>
                            </span>

                        </h4>

                    </div>

                <?php
                    endfor;
                endforeach;
                ?>

            </div>

            <button type="button"
                    class="btn btn-primary btn-block no-print"
                    onclick="window.print();">
                <i class="fa fa-print"></i> IN TEM
            </button>

        </div>
    </div>
</div>