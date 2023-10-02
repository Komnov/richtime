<?php

/**
 * Actions
 *
 * @see richtime_product_open_wrapper()
 * @see richtime_product_close_wrapper()
 * @see add_favorite_button()
 * @see get_user_favorites_list()
 * @see richtime_ajax_update_favorite()
 * @see richtime_remove_favorite()
 * @see richtime_filter_products()
 * @see richtime_woocommerce_init()
 * @see richtime_update_custom_order_fields()
 * @see richtime_checkout_notices()
 */
add_action('richtime_before_product_link', 'richtime_product_open_wrapper');
add_action('richtime_after_product_link', 'richtime_product_close_wrapper');
add_action('richtime_product_item', 'add_favorite_button');
add_action('woocommerce_shop_loop_item_title', 'richtime_before_product_title', 1);
add_action('woocommerce_after_shop_loop_item_title', 'richtime_after_product_title', 999);
//add_action('woocommerce_shop_loop_item_title', 'get_product_tags', 20);
add_action('woocommerce_before_shop_loop_item', 'get_product_chars', 30);
add_action('init', 'get_user_favorites_list');
add_action('wp_ajax_richtime_update_favorite', 'richtime_ajax_update_favorite');
add_action('wp_ajax_richtime_clear_favorites', 'richtime_clear_favorites');
add_action('pre_get_posts', 'richtime_filter_products');
add_action('woocommerce_init', 'richtime_woocommerce_init');
add_action('pre_get_posts', 'richtime_news_filter');
add_action('woocommerce_before_add_to_cart_button', 'richtime_add_to_cart_before_wrapper');
add_action('woocommerce_after_add_to_cart_button', 'richtime_add_favorite_single', 5);
add_action('woocommerce_after_add_to_cart_button', 'richtime_add_to_cart_after_wrapper', 100);
add_action('woocommerce_single_product_summary', 'richtime_product_article', 1);
add_action('woocommerce_single_product_summary', 'richtime_product_short_description', 6);
add_action('richtime_woocommerce_after_content', 'richtime_woocommerce_actions');
add_action('wp_ajax_ajax_search', 'ajax_search');
add_action('wp_ajax_nopriv_ajax_search', 'ajax_search');
add_action('woocommerce_checkout_before_order_review_heading', 'richtime_recipient_fields');
add_action('woocommerce_checkout_update_order_meta', 'richtime_update_custom_order_fields', 10, 2);
add_action('woocommerce_after_checkout_validation', 'richtime_checkout_notices', 10, 2);
add_action('template_redirect', 'richtime_after_user_login', 10);
add_action('template_redirect', 'richtime_woocommerce_account_redirect', 20);

/**
 * Remove actions and filters
 */
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price');

/**
 * Filters
 *
 * @see set_attribute_hierarchy()
 * @see richtime_product_tabs()
 * @see richtime_woocommerce_billing_fields()
 */
add_filter('woocommerce_taxonomy_args_pa_individualnyj-podbor', 'set_attribute_hierarchy', 10, 1);
add_filter('woocommerce_product_tabs', 'richtime_product_tabs');
add_filter('woocommerce_product_loop_start', 'richtime_category_loop_display', 10, 1);
add_filter('excerpt_length', 'richtime_excerpt_length');
add_filter('comment_form_fields', 'move_comment_field');
add_filter('woocommerce_process_registration_errors', 'richtime_user_registration', 10, 1);
add_filter('woocommerce_single_product_carousel_options', 'richtime_gallery_settings');
add_filter('woocommerce_checkout_fields', 'custom_checkout_fields');
add_filter('woocommerce_gateway_icon', 'mastercard_change_icon', 10, 2);
add_filter('woocommerce_billing_fields', 'richtime_woocommerce_billing_fields');

function add_favorite_button()
{
    global $product, $favorites;
    $favorites = (array)$favorites;
    $is_favorite = in_array($product->get_id(), $favorites) ? 'bi-star-fill' : 'bi-star';
    $account_link = !is_user_logged_in() ? get_permalink(get_option('woocommerce_myaccount_page_id')) : '';

    echo sprintf(
        '<span class="add-favorite" data-account="%s" data-product_id="%d"><i class="bi %s"></i></span>',
        $account_link,
        $product->get_id(),
        $is_favorite
    );

}

function set_attribute_hierarchy($data)
{
    $data['hierarchical'] = true;

    return $data;
}

/**
 * Get user favorites products list
 */
function get_user_favorites_list()
{
    global $favorites;
    if (!is_user_logged_in()) {
        $favorites = [];

        return;
    }

    $favorites = get_user_meta(get_current_user_id(), 'favorites_products', true);
}

/**
 * Add product to user favorites list
 */
function richtime_ajax_update_favorite()
{
    if (is_user_logged_in() && !empty($_POST['pid'])) {
        $user_id = get_current_user_id();
        $favorites_list = get_user_meta($user_id, 'favorites_products', true);
        $pid = (int)$_POST['pid'];
        $key = array_search($pid, $favorites_list, true);
        if (false !== $key) {
            unset($favorites_list[$key]);
            $updated = update_user_meta($user_id, 'favorites_products', $favorites_list);
            $message = __('removed', 'richtime');
        } else {
            array_push($favorites_list, $pid);
            $updated = update_user_meta(get_current_user_id(), 'favorites_products', $favorites_list);
            $message = __('added', 'richtime');
        }
        $updated ? wp_send_json_success(['message' => $message]) : wp_send_json_error();
    }
}

function richtime_filter_products($query)
{
    if (!$query->is_admin && is_product_taxonomy()) {

        $args = [];

        if (!empty($_GET['filter'])) {
            foreach ($_GET['filter'] as $key => $items) {
                if ('brands' === $key) {
                    $item = [
                        'taxonomy' => 'product_cat',
                        'field' => 'id',
                        'terms' => $items,
                        'operator' => 'IN',
                    ];
                    $args[] = $item;
                } else {
                    $item = [
                        'taxonomy' => $key,
                        'field' => 'id',
                        'terms' => $items,
                        'operator' => 'IN',
                    ];
                    $args[] = $item;
                }
            }
        }

        if (!empty($args)) {
            $query->set(
                'tax_query',
                $args
            );
        }
    }
}

function richtime_woocommerce_init()
{
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
}

function richtime_product_tabs($tabs)
{

}

function richtime_chars_callback()
{
    get_template_part('template-parts/tabs/characteristics');
}

function richtime_product_news_callback()
{
    get_template_part('template-parts/tabs/news');
}

function richtime_delivery_callback()
{
    get_template_part('template-parts/tabs/delivery');
}

function richtime_product_open_wrapper()
{
    echo '<div class="richtime-loop__product">';
}

function richtime_product_close_wrapper()
{
    echo '</div>';
}

function get_product_tags()
{
    global $product;
    $product_tags = get_the_terms($product->get_id(), 'product_tag');
    $result = [];
    if (!empty($product_tags)) {
        foreach ($product_tags as $product_tag) {
            $result[] = $product_tag->name;
        }
    }

    echo '<p class="richtime-product__tags">' . implode(', ', $result) . '</p>';
}

function richtime_before_product_title()
{
    echo '<div class="richtime-loop__product-title-wrapper">';
}

function richtime_after_product_title()
{
    echo "</div>";
}

function get_product_chars()
{
    get_template_part('template-parts/product/preview-short-description');
//    ob_start();
//    echo ob_get_contents();
//    ob_get_clean();

}

function richtime_category_loop_display($html)
{
    return '';
}

function richtime_excerpt_length()
{
    return 20;
}

function richtime_news_filter($query)
{
    if (!is_admin() && $query->is_home && $query->is_main_query()) {
        $query->set('offset', 1);
    }
}

function move_comment_field($fields)
{
    $comment_field = $fields['comment'];
    unset($fields['comment']);
    $fields['comment'] = $comment_field;

    return $fields;
}

function richtime_user_registration($errors)
{
    if (isset($_POST['register'])) {

        if ($_POST['password'] !== $_POST['password2']) {
            $errors->add('error_password_match', __('Password mismatch', 'richtime'));
        }
    }

    return $errors;
}

function richtime_gallery_settings($options)
{

    $options['directionNav'] = true;
    $options['prevText'] = '<svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M0.869981 8.82833L7.47331 15.42C7.58177 15.5294 7.71081 15.6161 7.85297 15.6754C7.99514 15.7346 8.14763 15.7651 8.30165 15.7651C8.45566 15.7651 8.60815 15.7346 8.75032 15.6754C8.89249 15.6161 9.02152 15.5294 9.12998 15.42C9.34727 15.2014 9.46924 14.9057 9.46924 14.5975C9.46924 14.2893 9.34727 13.9936 9.12998 13.775L3.35498 7.94167L9.12998 2.16667C9.34727 1.94808 9.46924 1.65238 9.46924 1.34417C9.46924 1.03595 9.34727 0.740256 9.12998 0.521666C9.02193 0.41143 8.89308 0.323731 8.75089 0.263653C8.6087 0.203573 8.45601 0.172308 8.30165 0.171668C8.14729 0.172308 7.99459 0.203573 7.85241 0.263653C7.71022 0.323731 7.58137 0.41143 7.47332 0.521666L0.869982 7.11333C0.751559 7.22258 0.657049 7.35518 0.592407 7.50276C0.527765 7.65034 0.494393 7.80971 0.494393 7.97083C0.494393 8.13195 0.527765 8.29132 0.592407 8.43891C0.657049 8.58649 0.751559 8.71908 0.869981 8.82833Z" fill="black"/>
								</svg>
								';
    $options['nextText'] = '<svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M9.13002 7.17167L2.52669 0.579999C2.41823 0.47065 2.28919 0.383857 2.14703 0.324626C2.00486 0.265396 1.85237 0.234901 1.69835 0.234901C1.54434 0.234901 1.39185 0.265396 1.24968 0.324626C1.10751 0.383857 0.978476 0.47065 0.87002 0.579999C0.652727 0.798589 0.530762 1.09428 0.530762 1.4025C0.530762 1.71072 0.652727 2.00641 0.87002 2.225L6.64502 8.05833L0.87002 13.8333C0.652727 14.0519 0.530762 14.3476 0.530762 14.6558C0.530762 14.9641 0.652727 15.2597 0.87002 15.4783C0.97807 15.5886 1.10692 15.6763 1.24911 15.7363C1.3913 15.7964 1.54399 15.8277 1.69835 15.8283C1.85271 15.8277 2.00541 15.7964 2.1476 15.7363C2.28978 15.6763 2.41864 15.5886 2.52669 15.4783L9.13002 8.88667C9.24844 8.77742 9.34295 8.64482 9.40759 8.49724C9.47224 8.34966 9.50561 8.19029 9.50561 8.02917C9.50561 7.86805 9.47224 7.70868 9.40759 7.56109C9.34295 7.41351 9.24844 7.28092 9.13002 7.17167Z" fill="black"/>
								</svg>
								';

    return $options;
}

function richtime_add_favorite_single()
{
    echo '<div class="product-favorite">';
    add_favorite_button();
    echo '</div>';
}

function richtime_add_to_cart_before_wrapper()
{
    echo "<div data-html2canvas-ignore='true' class='add-to-cart-wrapper'>";
}

function richtime_add_to_cart_after_wrapper()
{
    echo "</div>";
}

function richtime_product_article()
{
    get_template_part('template-parts/product/single-article');
}

function richtime_product_short_description()
{
    get_template_part('template-parts/product/single-short-description');
}

function richtime_woocommerce_billing_fields($fields)
{
    $fields['billing_middle_name'] = [
        'class' => [
            'form-row-wide',
        ],
        'id' => 'middle_name',
        'required' => true,
        'label' => __('Middle name', 'richtime'),
        'placeholder' => '',
        'priority' => 30,
    ];

    return $fields;
}

function custom_checkout_fields($fields)
{
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_address_1']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_city']);

    $fields['recipient']['custom_recipient'] = [
        'class' => ['form-row-wide'],
        'type' => 'checkbox',
        'label' => __('Third party recipient', 'richtime'),
        'required' => false,
    ];
    $fields['recipient']['custom_recipient_first_name'] = [
        'class' => ['form-row-wide recipient-field recipient-required'],
        'type' => 'text',
        'label' => __('First name', 'woocommerce'),
        'required' => true,
    ];
    $fields['recipient']['custom_recipient_last_name'] = [
        'class' => ['form-row-first recipient-field recipient-required'],
        'type' => 'text',
        'label' => __('Last name', 'woocommerce'),
        'required' => true,
    ];
    $fields['recipient']['custom_recipient_third_name'] = [
        'class' => ['form-row-last recipient-field recipient-required'],
        'type' => 'text',
        'label' => __('Third party name', 'richtime'),
        'required' => true,
    ];
    $fields['recipient']['custom_recipient_email'] = [
        'class' => ['form-row-first recipient-field'],
        'type' => 'text',
        'label' => __('Email', 'richtime'),
        'required' => true,
    ];
    $fields['recipient']['custom_recipient_phone'] = [
        'class' => ['form-row-last recipient-field recipient-required'],
        'type' => 'text',
        'label' => __('Phone', 'richtime'),
        'required' => true,
    ];
    $fields['recipient']['custom_recipient_date'] = [
        'class' => ['form-row-first recipient-field recipient-required'],
        'label' => __('Date of receiving', 'richtime'),
        'required' => false,
    ];

    $fields['recipient']['custom_recipient_time'] = [
        'type' => 'select',
        'class' => ['form-row-last recipient-field recipient-required'],
        'options' => [
            '' => __('Choose interval', 'richtime'),
            '10:00-11:00' => '10:00-11:00',
            '11:00-12:00' => '11:00-12:00',
            '12:00-13:00' => '12:00-13:00',
            '13:00-14:00' => '13:00-14:00',
            '14:00-15:00' => '14:00-15:00',
            '15:00-16:00' => '15:00-16:00',
            '16:00-17:00' => '16:00-17:00',
            '17:00-18:00' => '17:00-18:00',
            '18:00-19:00' => '18:00-19:00',
            '19:00-20:00' => '19:00-20:00',
            '20:00-21:00' => '20:00-21:00',
            '21:00-22:00' => '21:00-22:00',
        ],
        'required' => false,
        'label' => __('Time of receiving', 'richtime'),
        'placeholder' => __('Choose interval', 'richtime'),
    ];

    return $fields;
}

function mastercard_change_icon($icon, $id)
{
    if ('fondy' === $id) {
        $icon = "<img src='" . get_template_directory_uri() . '/assets/images/pay1.png' . "'>";
    } elseif ('paypal' === $id) {
        $icon = "<img src='" . get_template_directory_uri() . '/assets/images/paypal.png' . "'>";
    }

    return $icon;
}

function richtime_woocommerce_actions()
{
    if (is_product()) {
        woocommerce_output_related_products();
    }
}

function ajax_search()
{
    ob_start();
    get_template_part('template-parts/search/ajax');
    $content = ob_get_contents();
    ob_end_clean();
    wp_send_json_success($content);
}

function richtime_clear_favorites()
{
    wp_send_json_success(update_user_meta(get_current_user_id(), 'favorites_products', []));
}

function richtime_recipient_fields()
{
    get_template_part('template-parts/checkout/recipient');
}

function richtime_update_custom_order_fields($order_id, $data)
{
    $order = wc_get_order($order_id);
    update_field('custom_recipient', $data['custom_recipient'], $order_id);
    update_field('custom_recipient_first_name', $data['custom_recipient_first_name'], $order_id);
    update_field('custom_recipient_last_name', $data['custom_recipient_last_name'], $order_id);
    update_field('custom_recipient_third_name', $data['custom_recipient_third_name'], $order_id);
    update_field('custom_recipient_email', $data['custom_recipient_email'], $order_id);
    update_field('custom_recipient_phone', $data['custom_recipient_phone'], $order_id);
    update_field('custom_recipient_date', $data['custom_recipient_date'], $order_id);
    update_field('custom_recipient_time', $data['custom_recipient_time'], $order_id);
    $order->save();
}

function richtime_checkout_notices($data, $errors)
{
    $fields = [
        'custom_recipient_first_name_required',
        'custom_recipient_last_name_required',
        'custom_recipient_third_name_required',
        'custom_recipient_email_required',
        'custom_recipient_phone_required',
    ];

    if (empty($data['custom_recipient'])) {
        foreach ($fields as $recipient) {
            $errors->remove($recipient);
        }
    }
}

function richtime_after_user_login()
{
    global $wp;

    if (isset($wp->query_vars['pagename']) && 'my-account' === $wp->query_vars['pagename'] && is_user_logged_in()) {
        $user = wp_get_current_user();

        $favorite_product = isset($_GET['fp']) ? (int)$_GET['fp'] : 0;

        if (0 !== $favorite_product) {
            $favorite_list = get_user_meta($user->ID, 'favorites_products', true);
            $key = array_search($favorite_product, $favorite_list, true);
            if (false !== $key) {
                unset($favorite_list[$key]);
                wc_add_notice('Товар удалён', 'success');
            } else {
                array_push($favorite_list, $favorite_product);
                wc_add_notice('Товар добавлен', 'success');
            }

            update_user_meta($user->ID, 'favorites_products', $favorite_list);
        }
    }

}

function richtime_woocommerce_account_redirect()
{

    $current_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    $dashboard_url = get_permalink(get_option('woocommerce_myaccount_page_id'));

    if (is_user_logged_in() && $dashboard_url == $current_url) {
        $url = get_home_url() . '/my-account/orders';
        wp_redirect($url);
        exit;
    }
}

add_filter('woocommerce_short_description', 'richtime_woocommerce_short_description');

function richtime_woocommerce_short_description()
{
    return false;
}

add_action( 'woocommerce_after_add_to_cart_button', 'richtime_chars_callback2', 100 );

function richtime_chars_callback2()
{
    get_template_part('template-parts/tabs/characteristics');
}


add_action( 'woocommerce_after_add_to_cart_button', 'truemisha_short_description', 150 );
 
function truemisha_short_description() {
    echo '<p></p><span style="font-weight:500;font-size:20px;">Описание:</span>';
	the_content();
}

add_action( 'woocommerce_after_add_to_cart_button', 'truemisha_pdf', 20 );
 
function truemisha_pdf() {
    echo '<a style="margin-left:30px;" href="#" onclick="generatePDF()"><i class="fa fa-file-pdf fa-3x" style="color: #ff4545;"></i></a>';
}


add_action( 'woocommerce_after_add_to_cart_button', 'truemisha_but', 10 );
 
function truemisha_but() {
    echo '<button class="popmake-2589 single_add_to_cart_button clickBuyButton button21 button alt ld-ext-left">
                    <span> Назначить встречу</span>
                    <div style="font-size:14px" class="ld ld-ring ld-cycle"></div>
                </button>';
}