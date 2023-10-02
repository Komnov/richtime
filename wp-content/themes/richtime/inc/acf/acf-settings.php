<?php

if ( function_exists( 'acf_add_options_page' ) ) {
	acf_add_options_page(
		[
			'page_title' => 'Richtime Settings',
			'menu_title' => 'Richtime Settings',
			'menu_slug'  => 'theme-general-settings',
			'capability' => 'edit_posts',
			'redirect'   => false,
		]
	);
}