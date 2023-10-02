<?php

global $product;
$categories = get_the_terms($product->get_id(), 'product_cat');
//$materials = get_the_terms($product->get_id(), 'body-material');
//$sex = get_the_terms($product->get_id(), 'pa_dlya-kogo');
?>
<div class="richtime-product__chars">
    <p><strong><?php echo isset($categories[1]) ? $categories[1]->name : '' ?></strong>
<!--        --><?php //echo isset($materials[0]) ? $materials[0]->name : '' ?><!--<br/>-->
<!--        --><?php //echo isset($sex[0]) ? $sex[0]->name : '' ?>
    </p>
</div>
