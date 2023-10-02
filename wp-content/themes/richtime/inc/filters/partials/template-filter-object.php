<li><label for="filter_<?php echo $item->term_id ?>"><?php echo $item->name ?> <input
				id="filter_<?php echo $item->term_id ?>" name="<?php echo 'filter[' . $key . '][]' ?>"
				value="<?php echo $item->term_id ?>" type="checkbox"></label></li>
<?php if ( ! empty( $item->children ) ) : ?>
	<ul style="margin-left: 20px">
		<?php foreach ( $item->children as $child ) : ?>
			<li><label for="filter_<?php echo $child->term_id ?>"><?php echo $child->name ?> <input
							id="filter_<?php echo $child->term_id ?>"
							name="<?php echo 'filter[' . $key . '][]' ?>"
							value="<?php echo $child->term_id ?>" type="checkbox"></label></li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>
