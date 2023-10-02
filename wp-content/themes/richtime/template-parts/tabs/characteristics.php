<?php

global $product;
$chars = get_field('additional_params');
//$terms = get_terms(
//	[
//		'taxonomy' => [
//			'product_cat',
//			'product_tag',
//			'body-material',
//			'complex-functions'
//		],
//	]
//);
if (!empty($chars)) : ?>
<div class="charsdiv">
        <?php foreach ($chars as $char) : ?>
<p style="margin-bottom:0px;"><span><?php echo $char['title'] ?>: </span><?php echo $char['description'] ?><span></span></p>
        <?php endforeach; ?>
        </div>
<?php endif;