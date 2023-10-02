<?php

global $product;
$categories = get_the_terms( $product->get_id(), 'product_cat' );
$collection = get_the_terms( $product->get_id(), 'product_tag' );
$materials  = get_the_terms( $product->get_id(), 'body-material' );
$sex        = get_the_terms( $product->get_id(), 'pa_dlya-kogo' );
?>
<ul class="product-single__short-description">
	<?php if ( ! empty( $categories ) ) : ?>
		<li>Бренд: <span><?php echo $categories[1]->name ?></span></li>
	<?php endif ?>
	<?php if ( ! empty( $collection ) ) : ?>
		<li>Коллекция: <span><?php echo $collection[0]->name ?></span></li>
	<?php endif ?>
	<?php if ( ! empty( $materials ) ) : ?>
		<li>Материал корпуса: <span><?php echo $materials[0]->name ?></span></li>
	<?php endif ?>
	<?php if ( ! empty( $sex ) ) : ?>
		<li>Пол: <span><?php echo $sex[0]->name ?></span></li>
	<?php endif ?>
</ul>