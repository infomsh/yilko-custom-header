<?php
/**
 * Plugin Name: Yilko Custom Header
 * Plugin URI: https://yilkoshop.com
 * Description: هدر سفارشی ریسپانسیو با شورت کد برای Elementor
 * Version: 1.0.1
 * Author: Yilko Shop
 * Text Domain: yilko-header
 */

if (!defined('ABSPATH')) {
    exit;
}

class Yilko_Custom_Header {
    
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_shortcode('yilko_header', array($this, 'render_header'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('after_setup_theme', array($this, 'register_menus'));
    }
    
    public function register_menus() {
        register_nav_menus(array(
            'header-category-menu' => __('منوی دسته بندی هدر', 'yilko-header')
        ));
    }
    
    public function enqueue_assets() {
        wp_enqueue_style('yilko-header-style', plugin_dir_url(__FILE__) . 'assets/style.css', array(), '1.0.1');
        wp_enqueue_script('yilko-header-script', plugin_dir_url(__FILE__) . 'assets/script.js', array('jquery'), '1.0.1', true);
    }
    
    public function add_admin_menu() {
        add_menu_page(
            'تنظیمات هدر یلکو',
            'هدر یلکو',
            'manage_options',
            'yilko-header',
            array($this, 'admin_page'),
            'dashicons-menu',
            30
        );
    }
    
    public function register_settings() {
        register_setting('yilko_header_settings', 'yilko_contact_link');
        register_setting('yilko_header_settings', 'yilko_about_link');
        register_setting('yilko_header_settings', 'yilko_tracking_link');
    }
    
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1>تنظیمات هدر یلکو</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('yilko_header_settings');
                do_settings_sections('yilko_header_settings');
                ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">لینک تماس با ما</th>
                        <td>
                            <input type="text" name="yilko_contact_link" value="<?php echo esc_attr(get_option('yilko_contact_link', '#')); ?>" class="regular-text" dir="ltr" placeholder="https://yilkoshop.com/contact">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">لینک درباره ما</th>
                        <td>
                            <input type="text" name="yilko_about_link" value="<?php echo esc_attr(get_option('yilko_about_link', '#')); ?>" class="regular-text" dir="ltr" placeholder="https://yilkoshop.com/about">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">لینک پیگیری سفارش</th>
                        <td>
                            <input type="text" name="yilko_tracking_link" value="<?php echo esc_attr(get_option('yilko_tracking_link', '#')); ?>" class="regular-text" dir="ltr" placeholder="https://yilkoshop.com/tracking">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">منوی دسته بندی</th>
                        <td>
                            <p class="description">
                                از منوی <a href="<?php echo admin_url('nav-menus.php'); ?>" target="_blank"><strong>ظاهر > منوها</strong></a>، منوی خود را در موقعیت "منوی دسته بندی هدر" قرار دهید
                            </p>
                            <?php
                            $locations = get_nav_menu_locations();
                            if (isset($locations['header-category-menu']) && $locations['header-category-menu'] != 0) {
                                $menu = wp_get_nav_menu_object($locations['header-category-menu']);
                                echo '<p style="color: green;">✅ منو فعال: <strong>' . $menu->name . '</strong></p>';
                            } else {
                                echo '<p style="color: red;">❌ هیچ منویی اختصاص داده نشده است</p>';
                            }
                            ?>
                        </td>
                    </tr>
                </table>
                <?php submit_button('ذخیره تنظیمات'); ?>
            </form>
            
            <hr>
            <h2>نحوه استفاده</h2>
            <p>شورت کد زیر را در Elementor قرار دهید:</p>
            <code style="background: #f5f5f5; padding: 10px; display: block; margin: 10px 0; font-size: 16px;">[yilko_header]</code>
            
            <hr>
            <h2>راهنمای تنظیم منو</h2>
            <ol style="line-height: 2;">
                <li>به <a href="<?php echo admin_url('nav-menus.php'); ?>" target="_blank">ظاهر > منوها</a> بروید</li>
                <li>یک منوی جدید ایجاد کنید یا منوی موجود را انتخاب کنید</li>
                <li>دسته‌های محصولات یا لینک‌های دلخواه را به منو اضافه کنید</li>
                <li>در قسمت "تنظیمات نمایش"، گزینه <strong>"منوی دسته بندی هدر"</strong> را تیک بزنید</li>
                <li>منو را ذخیره کنید</li>
            </ol>
        </div>
        <?php
    }
    
    public function render_header($atts) {
        $contact_link = get_option('yilko_contact_link', '#');
        $about_link = get_option('yilko_about_link', '#');
        $tracking_link = get_option('yilko_tracking_link', '#');
        
        // Check if WooCommerce is active
        $cart_count = 0;
        $cart_url = '#';
        if (class_exists('WooCommerce')) {
            $cart_count = WC()->cart->get_cart_contents_count();
            $cart_url = wc_get_cart_url();
        }
        
        ob_start();
        ?>
        
        <header class="yilko-header">
            <!-- Top Header -->
            <div class="yilko-header-top">
                <div class="yilko-container">
                    <div class="yilko-header-row">
                        <!-- Logo -->
                        <div class="yilko-logo">
                            <a href="<?php echo home_url(); ?>">
                                <img src="https://yilkoshop.com/wp-content/uploads/elementor/thumbs/yilkologo44-r55qhu5xxasjiq6ujp532502z3cmebzmi12hw6snws.png" alt="Yilko Shop">
                            </a>
                        </div>
                        
                        <!-- Navigation Menu -->
                        <nav class="yilko-nav">
                            <ul class="yilko-nav-menu">
                                <li><a href="<?php echo esc_url($contact_link); ?>">تماس با ما</a></li>
                                <li><a href="<?php echo esc_url($about_link); ?>">درباره ما</a></li>
                                <li><a href="<?php echo esc_url($tracking_link); ?>">پیگیری سفارش</a></li>
                            </ul>
                        </nav>
                        
                        <!-- User Actions -->
                        <div class="yilko-user-actions">
                            <!-- Login Button -->
                            <div class="yilko-login">
                                <?php echo do_shortcode('[digits_smart_button]'); ?>
                            </div>
                            
                            <!-- Cart Icon -->
                            <div class="yilko-cart">
                                <a href="<?php echo esc_url($cart_url); ?>" class="yilko-cart-link">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="9" cy="21" r="1"></circle>
                                        <circle cx="20" cy="21" r="1"></circle>
                                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                    </svg>
                                    <?php if ($cart_count > 0) : ?>
                                        <span class="yilko-cart-count"><?php echo $cart_count; ?></span>
                                    <?php endif; ?>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Mobile Menu Toggle -->
                        <button class="yilko-mobile-toggle" aria-label="Menu">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Bottom Header -->
            <div class="yilko-header-bottom">
                <div class="yilko-container">
                    <div class="yilko-bottom-row">
                        <!-- Category Menu -->
                        <div class="yilko-category-menu">
                            <button class="yilko-category-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="3" y1="12" x2="21" y2="12"></line>
                                    <line x1="3" y1="6" x2="21" y2="6"></line>
                                    <line x1="3" y1="18" x2="21" y2="18"></line>
                                </svg>
                                دسته بندی محصولات
                            </button>
                            <div class="yilko-category-dropdown">
                                <?php
                                $menu_args = array(
                                    'theme_location' => 'header-category-menu',
                                    'menu_class' => 'yilko-category-list',
                                    'container' => false,
                                    'fallback_cb' => '__return_false'
                                );
                                
                                if (has_nav_menu('header-category-menu')) {
                                    wp_nav_menu($menu_args);
                                } else {
                                    echo '<ul class="yilko-category-list">
                                        <li style="padding: 15px; color: #999; text-align: center;">
                                            منویی یافت نشد<br>
                                            <small>لطفاً از پنل مدیریت منو را تنظیم کنید</small>
                                        </li>
                                    </ul>';
                                }
                                ?>
                            </div>
                        </div>
                        
                        <!-- Search -->
                        <div class="yilko-search">
                            <?php echo do_shortcode('[fibosearch]'); ?>
                        </div>
                        
                        <!-- Consultation Button -->
                        <div class="yilko-consultation">
                            <a href="tel:09139102009" class="yilko-consultation-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                </svg>
                                مشاوره خرید
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Menu -->
            <div class="yilko-mobile-menu">
                <div class="yilko-mobile-menu-header">
                    <img src="https://yilkoshop.com/wp-content/uploads/elementor/thumbs/yilkologo44-r55qhu5xxasjiq6ujp532502z3cmebzmi12hw6snws.png" alt="Yilko">
                    <button class="yilko-mobile-close">&times;</button>
                </div>
                
                <!-- Mobile Category Menu -->
                <div class="yilko-mobile-categories">
                    <h3 class="yilko-mobile-section-title">دسته‌بندی محصولات</h3>
                    <?php
                    if (has_nav_menu('header-category-menu')) {
                        wp_nav_menu(array(
                            'theme_location' => 'header-category-menu',
                            'menu_class' => 'yilko-mobile-category-list',
                            'container' => false,
                        ));
                    } else {
                        echo '<p style="padding: 10px 20px; color: #999;">منویی تنظیم نشده است</p>';
                    }
                    ?>
                </div>
                
                <nav class="yilko-mobile-nav">
                    <ul>
                        <li><a href="<?php echo esc_url($contact_link); ?>">تماس با ما</a></li>
                        <li><a href="<?php echo esc_url($about_link); ?>">درباره ما</a></li>
                        <li><a href="<?php echo esc_url($tracking_link); ?>">پیگیری سفارش</a></li>
                    </ul>
                </nav>
                <div class="yilko-mobile-search">
                    <?php echo do_shortcode('[fibosearch]'); ?>
                </div>
                <div class="yilko-mobile-consultation">
                    <a href="tel:09139102009" class="yilko-consultation-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                        </svg>
                        مشاوره خرید
                    </a>
                </div>
            </div>
            <div class="yilko-mobile-overlay"></div>
        </header>
        
        <?php
        return ob_get_clean();
    }
}

// Initialize Plugin
new Yilko_Custom_Header();
