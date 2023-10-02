<?php
defined( 'ABSPATH' ) || exit;
?>
<div class="woocommerce-billing-fields recipient-fields">
	<h3><?php esc_html_e( 'Recipient\'s contact details', 'richtime' ); ?></h3>

	<div class="woocommerce-billing-fields__field-wrapper">
		<?php
		$checkout = WC()->checkout();
		$fields = $checkout->get_checkout_fields( 'recipient' );

		foreach ( $fields as $key => $field ) {
			woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
		}
		?>
	</div>

	<?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>
</div>