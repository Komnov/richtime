# Changelog
======
1.8.8
======
- FIX: Backend layout images missing
- FIX:	Removed ":" from meta keys (you need to add them yourself now)

======
1.8.7
======
- NEW:	Added support for DIVI Shortcodes
- NEW:	Added 3 custom blocks you can use with filter:
		woocommerce_print_products_custom_block_1
		woocommerce_print_products_custom_block_2
		woocommerce_print_products_custom_block_3
- NEW: 	Append custom PDFs
		https://imgur.com/a/DNr5qoy
- NEW:	Link product name option
- NEW:	Custom PDF File name
		https://imgur.com/a/wnUSF3N
- NEW:	Set variation image size & move price to last column
		https://imgur.com/a/ukVjjfZ
- FIX:	Modified CURL User Agent to comply with siteground firewall
		'curlUserAgent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:110.0) Gecko/20100101 Firefox/110.0',
- FIX:	PHP NOtice
- FIX:	Replaced $_SERVER with get_site_url()

======
1.8.6
======
- NEW:	Add extra texts before or after a product
		https://imgur.com/a/fq0WXKf
- FIX:	Date in Export information not Translated
- FIX:	Empty ACF fields show in PDF
- FIX:	Fatal error when using categores in header / footer

======
1.8.5
======
- NEW:	Sort & Order custom Meta fields:
		https://imgur.com/a/JZBpRRE
- NEW:	Show attribute value description
		https://imgur.com/a/0GKj5ub
- NEW:	Added 4 cols header & footer options
		https://imgur.com/a/5gIN48h	
- NEW:	Hide specific attributes in PDF
		https://imgur.com/a/N05fA6Q
- NEW:	Removed inline styling on header & footer elements
- NEW:	2 Options to disable CURL SSL validation & follow 302 Redirects
- NEW:	FIlter for 
		woocommerce_print_products_notes_html
		woocommerce_print_products_header_html
		woocommerce_print_products_footer_html
- FIX:	PHP Notice icon variable
- FIX:	Using FULL Image URL now for main image

======
1.8.4
======
- NEW:	Added Martel Sans font
- NEW:	Added explicit option to enable support for our group attributes plugin
		& added 2 options to hide more & name
		https://imgur.com/a/QLpuA1F
- NEW:	Description field has an own input field in admin
- FIX:	Texts before footer & after header not working
- FIX:	Updates not possible
- FIX:	Hide empty ACF Fields
- FIX:	Meta keys container move from Span to DIV
- FIX:	Group attributs plugin closing div missing

======
1.8.3
======
- NEW:	Set custom featured image background color for layout 2
		https://imgur.com/a/iMsKMj5
- NEW:	Use SKU as Filename
		https://imgur.com/a/GPxdOoW
- NEW:	Added poppins font
- FIX:	Space missing in meta keys 3rd layout
- FIX:	{{current_date}} wrong data

======
1.8.2
======
- NEW:	Support for our Attribute Images / Variation Swatches plugin
		https://www.welaunch.io/en/product/woocommerce-attribute-images/
		https://imgur.com/a/XsVoAaN
- NEW:	Variation export only shows selected attributes
- NEW:	Use {{current_date}} in header / footer
- NEW:	Added Mulish font
- FIX:	Updated WPML Keys

======
1.8.1
======
- NEW:	New Template 8th (customized)
		https://imgur.com/a/4EMzWsm
- NEW:	Show Barcode (e.g. EAN13)
		https://imgur.com/a/Nbqj9wE
- NEW:	Added Gallery Images Headline option
		https://imgur.com/a/yguCzkh
- NEW:	Filter for MPDF Settings
		woocommerce_print_products_mpdf_config
- FIX:	Removed Table border spacing
- FIX:	General CSS fixes

======
1.8.0
======
- NEW:	Added short description to order blocks
		https://imgur.com/a/WpWJ2E4
- NEW:	Added Variation Stock Status & Quantity options:
		https://imgur.com/a/mPdVaPi
- NEW:	Optimized shortcode and added show_icon parameter:
		https://imgur.com/a/Ify66hj
- NEW:	Added DE, NL, IT, FR Translations
- NEW:	Added support for ACF repeater fields

======
1.7.17
======
- NEW:	Option to hide the attribute links 
- NEW:	Upgraded to font awesome 5 included package
- NEW:	Meta keys take parent product ID not variation ID
- FIX:	QR code size not working
- FIX:	Removed woo 2.X support
- FIX:	Layout 2 showed | seperator even when SKU was hidden
- FIX:	Updated MPDF library to support PHP 8.0
- FIX:	PHP Notices

======
1.7.16
======
- NEW:	filter for custom header / footer data: woocommerce_print_products_header_footer_data
- FIX:	filename contained a space before .pdf
- FIX:	WPML keys missing for buttons

======
1.7.15
======
- NEW:	Option to set the export button text in backend:
		https://imgur.com/a/8jIJZmW
- FIX:	Attribute title not showing in data to show options

======
1.7.14
======
- NEW:	Added support for tierd pricing plugin from WooCommerce
		https://imgur.com/a/K5CRP0v
- NEW:	Added 4 more hooks

======
1.7.13
======
- NEW:	Dropped Redux Framework support and added our own framework 
		Read more here: https://www.welaunch.io/en/2021/01/switching-from-redux-to-our-own-framework
		This ensure auto updates & removes all gutenberg stuff
		You can delete Redux (if not used somewhere else) afterwards
		https://www.welaunch.io/updates/welaunch-framework.zip
		https://imgur.com/a/BIBz6kz
- NEW:	When variation image is empty it fallsback to variable image
- NEW:	When variation short description is empty it fallsback to variable short description

======
1.7.12
======
- FIX:	When empty attributes a 1 was returned / displayed in PDF

======
1.7.11
======
- FIX:	undefined ß contstant php warning

======
1.7.10
======
- NEW:	Watermark option HAPPY CHRIStMAS
		https://imgur.com/a/dbkbW5O
- NEW:	Custom post data section and ACF Support
		https://imgur.com/a/wdjK0Mg

======
1.7.9
======
- NEW:	Support for our own Gallery Images plugin 
- NEW:	Filter for final HTML contains all data now as 3rd object
- NEW:	Variation image fallback to main image
- FIX:	Filter contains 3rd data
- FIX:	Variations title padding

======
1.7.8
======
- FIX:	PHP notices
- FIX:	Updated POT / Language files
- FIX:	Variation Attributes displayed as numeric values

======
1.7.7
======
- FIX: 	Added roboto font
- FIX:	Group attributes support not working

======
1.7.6
======
- NEW:	Big Performance Release 
		!! MAKE SURE YOU ARE ON LATEST VERSION OF REDUX FRAMEWORK !!
- FIX:	Updated Docs

======
1.7.5
======
- NEW:	Single Variation PDF Export can be disabled
		https://imgur.com/a/NZ4Phcf

======
1.7.4
======
- NEW:	Layout 6 & layout 7
- NEW:	Added support for Additional Variation Images Gallery for WooCommerce Plugin
- NEW:	Notes content item
- NEW:	use {{product_name}} variable in header / footer
- NEW:	Better custom header / footer styling
- FIX:	PHP issue with table mode
		Access level to WooCommerce_Print_Products_Public::get_option() must be protected

======
1.7.3
======
- NEW:	Variations are now supported to be exported as PDF directly (not just the variable)
- FIX:	QRCode Class missing

======
1.7.2
======
- NEW:	Added PHP 7.4 support
- NEW:	Updated the MPDF Rendering Engine from Version 7 to 8

======
1.7.1
======
- NEW:	Performance boost through making exlusions optional. get_posts for exlusions 
		was a performance killer. 
		Demo: https://imgur.com/a/8uswUtq

======
1.7.0
======
- NEW:	Print Product Templates
		Read more here: https://welaunch.io/plugins/woocommerce-print-products/faq/create-use-print-pdf-templates/

======
1.6.4
======
- FIX:	Group attributes integration not working

======
1.6.3
======
- NEW:	Created a new template (vesion 5)
		Example: https://imgur.com/a/wAVQlG4 (from on HazTec)
- NEW:	Show / Hide Description or Attribute title
- NEW:	Set max gallery images: https://imgur.com/a/gOpkYYT

======
1.6.2
======
- NEW:	Added an option to set custom gallery image size types
- NEW:	Added an option to get images locally in advanced settings
		You can see images protected by htpasswd then
- FIX:	PHP Notice fix

======
1.6.1
======
- FIX:	Upgraded to Font Awesome 5.12.1
- FIX:	Icons not visible in backend

======
1.6.0
======
- NEW:	Generate PDF, Word or Print directly from the backend
		When you edit a product or in product overview: https://imgur.com/a/XUukg2N
- NEW:	Readded the exclusion functionality
- NEW:	Added transient caching
- NEW:	Enable backend or fronted exporting separately
- NEW:	Enable backend product list / single product page export separately
- FIX:	Code improvements

======
1.5.8
======
- NEW:	Added visual composer support

======
1.5.7
======
- NEW:	Added transient caching for meta keys in admin panel
- FIX:	Removed exclusions for products for performance

======
1.5.6
======
- NEW:	Added "the_content" and more default Woo Hooks to the icon position
- NEW:	Added priority option for the icons
- NEW:	Support for Yoast Primary Category for Header > Category Description

======
1.5.5
======
- NEW:	Created a new template (4)

======
1.5.4
======
- NEW:	Option to move SKU under the product title
- FIX:	Readded support for format (A4, landscape etc)

======
1.5.3
======
- FIX:	Get attributes function updated to latest Woo Standard (inches issue)
- FIX:	Custom meta keys stored as array will be output as string delimited by comma

======
1.5.2
======
- NEW:	Option to hide the Gallery Images Intro Title
- FIX:	Switched table header to DIV
- FIX:	Header & Footer widht / height can be set to 0

======
1.5.1
======
- NEW:	Option to set a custom Meta Key Separator in Data to Show
- NEW:	Check for empty meta values

======
1.5.0
======
- NEW:	Added multiple new filters:

		// Data Filters
		apply_filters('woocommerce_print_products_title', $this->post->post_title);
		apply_filters('woocommerce_print_products_short_description', do_shortcode($this->post->post_excerpt));
		apply_filters('woocommerce_print_products_price', $price);
		apply_filters('woocommerce_print_products_description', $this->post->post_content);
		apply_filters('woocommerce_print_products_meta_keys', $temp);

		// Modify the whole layout
		apply_filters('woocommerce_print_products_product_html', $this->get_first_layout(), $this->data->ID); 

		// Custom HTML 
		apply_filters('woocommerce_print_products_before_product_info_html', '', $this->data->ID); 
		apply_filters('woocommerce_print_products_after_product_info_html', '', $this->data->ID); 

		// HTML Strings
		apply_filters('woocommerce_print_products_product_description_html', ob_get_clean(), $this->data->ID);
		apply_filters('woocommerce_print_products_product_attributes_html', ob_get_clean(), $this->data->ID);
		apply_filters('woocommerce_print_products_product_attributes_html', ob_end_clean(), $this->data->ID);
		apply_filters('woocommerce_print_products_product_reviews_html', ob_get_clean(), $this->data->ID);
		apply_filters('woocommerce_print_products_product_upsells_html', ob_get_clean(), $this->data->ID);
		apply_filters('woocommerce_print_products_product_gallery_images_html', ob_get_clean(), $this->data->ID);
		apply_filters('woocommerce_print_products_product_variations_html', ob_get_clean(), $this->data->ID);

- FIX:	Dimensions displayed
- FIX:	Performance Increase
- FIX:	Updated Documentation

======
1.4.11
======
- FIX:	hr was replaced with a div and class hr
		with makes it easier to hide it via background-color: #fff for example

======
1.4.10
======
- FIX:	Google Fonts missing

======
1.4.9
======
- FIX:	Performance (admin panel only loads for admins now)
- FIX:	Removed TGM

======
1.4.8
======
- NEW:	Added support for our custom Tab plugin:
		https://codecanyon.net/item/woocommerce-ultimate-tabs/14667506

======
1.4.7
======
- NEW:	Moved all 3 Layouts from "Table" to "DIV"
		This gives you more styling possibilites using custom CSS
		If you want to revert back read next line:
- NEW:	Added option to go back to Table View (see advanced Settings)
- FIX:	Added compatibility to ATUM plugin
- FIX:	Cannot find TTF TrueType font file DejaVuSans-Bold.ttf

======
1.4.6
======
- NEW:	Filter for Meta Keys, so you can reorder / change value / add own to them
		woocommerce_print_products_meta_keys
- NEW:	Show Stock Status

======
1.4.5
======
- NEW:	Option to show the export icons as buttons
		See Settings > General > Icon Type

======
1.4.4
======
- NEW:	Option to Show first product category description in Header or Footer

======
1.4.3
======
- NEW:	Show product categories in Header or Footer

======
1.4.2
======
- NEW:	Variation Attributes will now be better displayed
- FIX:	Variation Description not found

======
1.4.1
======
- NEW:	Added an option to not convert description to table
		See Data to Show > Do not convert Description to Table
- FIX:	Strip images not working

======
1.4.0
======
- NEW:  Added Support for your Group Attributes Plugin
		https://codecanyon.net/item/woocommerce-group-attributes/15467980

======
1.3.9
======
- FIX: 	Removed WooCommerce translations and changed to plugin ones
		Make sure you go to Loco Translate and translate 
		the woocommerce-print-products plugin there

======
1.3.8
======
- NEW:  Layout 4

======
1.3.7
======
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!! MPDF 7 requires at least PHP 5.6 				!!!!
!!!! Do NOT update if you are on a lower Version 	!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
- NEW:  PHP 7.2. Support
- NEW:  Moved MPDF to vendor folder for composer support
- NEW:  Option to enable MPDF Debugging (images, fonts)
- FIX:  Upgraded MPDF Rendering Engine to Version 7.0.3

======
1.3.6
======
- FIX:  Issue with strange meta keys

======
1.3.5
======
- NEW:  4 x new Filters:
		woocommerce_print_products_title
		woocommerce_print_products_short_description
		woocommerce_print_products_price
		woocommerce_print_products_description
- FIX:  Added BR tag after custom meta key
- FIX:  Added CSS classes to custom meta keys

======
1.3.4
======
- NEW: Get paramter product_id will override the shortcodes id attribute

======
1.3.3
======
- NEW: WPML Support 
- NEW: New option "Data to show" > "Try executing shortcodes in description"
	   Disable this if you have issues with shortcodes in your post_content

=====
1.3.2
======
- NEW:  Shortcode support – Example:
		[print_product id="76" mode="pdf" text="Print Product (ID 76)"]
- NEW:  Shortcode rendering in header / footer

=====
1.3.1
======
- FIX:  Plugin initial code updated in order to use hooks

=====
1.3.0
======
- NEW:  Support for Custom Post Fields (see data to show)
	    All custom meta keys for products will be shown there
- NEW:	Added New Font Families: 
		Droid Sans, Droid Serif, Lato, Lora, Merriweather, 
		Montserrat, Open sans, Open Sans Condensed, Oswald, 
		PT Sans, Source Sans Pro, Slabo, Raleway
- NEW: 	Limit access to specific user roles
- FIX:  Small Tweaks

=====
1.2.6
======
- FIX: Print functionality

=====
1.2.5
======
- FIX: WooCommerce 3.0 variable products compatibility

=====
1.2.4
======
- FIX: Plugin activation check
- FIX: WooCommerce 3.0 compatibility
- FIX: Gallery Images overwritten by custom filter

=====
1.2.3
======
- NEW: Shortcode support in short description

=====
1.2.2
======
- FIX: Removed comments from PDF file when viewed in Chrome

=====
1.2.1
======
- FIX: For old PHP Version

=====
1.2.0
======
- NEW: You can now add a custom Text after the Header 
- NEW: You can now add a custom Text before the Footer 
- NEW: Template Nr. 3 has arrived -> see Layouts
- NEW: You can now include / exclude products
- NEW: You can now include / exclude product categories
- NEW: Custom Meta Free Text can be added. This will be placed after the short description
- NEW: Debug Mode (this will prevent PDF from render and display the plain HTML)
- NEW: Set a custom Feature Image size
- NEW: Added many CSS classes to better use the Custom CSS
- FIX: Font-size and Line Height issue (switched to PX)

=====
1.1.8
======
- NEW: Updated MPDF Library to Version 6.1 (this also removes PHP 7 errors)
- NEW: decreased plugin size by 10MB (removed 2 fonts)

=====
1.1.7
======
- NEW: Better plugin activation
- FIX: Better advanced settings page (ACE Editor for CSS and JS )
- FIX: array key exists

=====
1.1.6
======
- FIX: Redux Error

=====
1.1.5
======
- NEW: Removed the embedded Redux Framework for update consistency
//* PLEASE MAKE SURE YOU INSTALL THE REDUX FRAMEWORK PLUGIN *//

======
1.1.4
======
- FIX: Remove shortcodes from description
- FIX: Print Function fixes for certain browsers

======
1.1.3
======
- FIX: Print Function fixes 

======
1.1.2
======
- NEW: Do not display the next pagebreak if an element is empty (e.g. there are no gallery images)
- NEW: Show Title, Caption, Alt Text or Decsription of your Product gallery images
- FIX: Print Function fixes for Safari and Firefox
- FIX: Updated translation files

======
1.1.1
======
- NEW: removed unnecessary files to reduce plugin file size

======
1.1.0
======
- NEW: show product variations
- NEW: show / hide variation image
- NEW: show / hide variation sku
- NEW: show / hide variation description
- NEW: show / hide variation attributes
- NEW: extra class in each title element (e.g. description-title)
- NEW: pagebreak now also in print

======
1.0.9.1
======
- FIX: product upsells title
- FIX: gallery images quality
- FIX: reviews heading text 
- FIX: Russian ruble symbol
- NEW: display a QR-Code blow product short description to your product page
- NEW: display a QR-Code in header / footer to your product page

======
1.0.9
======
- FIX: print windows now closes after print / abort 
- FIX: Word document special characters
- FIX: Paragraph tags are now splitted in table rows
- NEW: set header height 
- NEW: set header top margin
- NEW: set header vertical alignment
- NEW: set foooter height 
- NEW: set foooter top margin
- NEW: set foooter vertical alignment

======
1.0.8
======
- FIX: reviews will now always be displayed text aligned left
- FIX: reviews in print have now valign top
- FIX: image in layout 2 will always be centered
- FIX: text not aligned in layout 1
- NEW: print windows now closes after print / abort

======
1.0.7
======
- FIX: removed unused admin CSS / JS

======
1.0.6
======
- NEW: product gallery images now possible to add
- NEW: custom CSS now will be executed in PDF / Word / Print exports instead of the website
- NEW: product title will now be used for PDF / Word filename

======
1.0.5
======
- FIX: font fix for arabic, chineses and any other special languages

======
1.0.4
======
- NEW: now you have the ability to add pagebreaks yourself

======
1.0.3
======
- FIX: header and footer text alignment

======
1.0.2
======
- FIX: layout images will now be shown in admin UI
- FIX: SKU will now be shown
- NEW: translation of tag / page / categories (please use Loco Translate - Translation comes from WooCommerce itself)
- NEW: line height option for text and heading
- NEW: 3 different header types: 1/1 OR 1/2 + 1/2 OR 1/3 + 1/3 + 1/3
- NEW: 3 different footer types: 1/1 OR 1/2 + 1/2 OR 1/3 + 1/3 + 1/3
- NEW: reorder the product information like you want 
- NEW: ability to choose the text alignment (left, center, right)

======
1.0.1
======
- fixed end of file bug

======
1.0
======
- Inital release

# Future features
- NONE