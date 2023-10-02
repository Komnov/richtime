<?php
global $product;
$categories = get_the_terms($product->get_id(), 'product_cat'); ?>
<div class="sku-wrapper">
    <span><?php echo sprintf( __( 'Бренд <strong>%s</strong>', 'richtime'), $categories[1]->name )?></span><span><?php echo 'Art. ' . $product->get_sku() ?></span>
</div>