<?php
/**
 * Arabic String Overrides
 *
 * Uses the WordPress `gettext` filter to supply Arabic translations for
 * strings that originate in third-party plugins (WCFM, Martfury theme,
 * WooCommerce) and are NOT covered by their own translation files.
 *
 * The override only fires when:
 *   1. The site is in RTL mode (i.e. Arabic is active).
 *   2. The string has NOT already been translated by its own domain
 *      (i.e. $translated === $text  — still in English).
 *
 * This ensures we never overwrite a correct translation that already exists;
 * we only fill the gaps.
 */

namespace RakmyatCore\Multilang;

if ( ! defined( 'ABSPATH' ) ) exit;

class String_Overrides {

    private static $instance = null;

    /** @var array<string,string>|null  Lazy-loaded translation map */
    private $map = null;

    public static function instance(): self {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_filter( 'gettext', [ $this, 'apply' ], 20, 3 );
    }

    /**
     * Intercept a translated string and replace it with our Arabic translation
     * if the string is still untranslated (in English) and RTL is active.
     *
     * @param string $translated  Result from the domain's own translation (or original if none).
     * @param string $text        Original English string.
     * @param string $domain      Text domain that was used.
     * @return string
     */
    public function apply( string $translated, string $text, string $domain ): string {
        // Skip if already translated by its own domain
        if ( $translated !== $text ) {
            return $translated;
        }

        // Only apply when Arabic / RTL is active
        if ( ! is_rtl() ) {
            return $translated;
        }

        $map = $this->get_map();

        return $map[ $text ] ?? $translated;
    }

    // ─────────────────────────────────────────────────────────────
    // Translation map
    // Covers: WCFM, Martfury theme, WooCommerce gaps
    // ─────────────────────────────────────────────────────────────
    private function get_map(): array {
        if ( null !== $this->map ) {
            return $this->map;
        }

        $this->map = [

            /* ── WooCommerce My Account greetings ── */
            'Hello!'                           => 'مرحباً!',
            'Hello'                            => 'مرحبا',

            /* ── Martfury / theme My Account dropdown ── */
            'Account Settings'                 => 'إعدادات الحساب',
            'Orders History'                   => 'سجل الطلبات',
            'Logout'                           => 'تسجيل الخروج',

            /* ── WCFM — sidebar menu items ── */
            'Followings'                       => 'المتابَعون',
            'Support Tickets'                  => 'تذاكر الدعم',
            'Inquiries'                        => 'الاستفسارات',

            /* ── WCFM — Support Tickets table ── */
            'Ticket(s)'                        => 'التذاكر',
            'Priority'                         => 'الأولوية',
            'Actions'                          => 'الإجراءات',
            'You do not have any support ticket yet!' => 'ليس لديك أي تذاكر دعم حتى الآن!',

            /* ── WCFM — Inquiries table ── */
            'Query'                            => 'الاستفسار',
            'Store'                            => 'المتجر',
            'Additional Info'                  => 'معلومات إضافية',
            'You do not have any inquiry yet!' => 'ليس لديك أي استفسارات حتى الآن!',

            /* ── WooCommerce Addresses (fallback if WC Arabic missing) ── */
            'Billing address'                  => 'عنوان الفواتير',
            'Shipping address'                 => 'عنوان الشحن',
            'Edit'                             => 'تعديل',
            'Add'                              => 'إضافة',
            'Edit Billing address'             => 'تعديل عنوان الفواتير',
            'Edit Shipping address'            => 'تعديل عنوان الشحن',
            'Add Billing address'              => 'إضافة عنوان الفواتير',
            'Add Shipping address'             => 'إضافة عنوان الشحن',

        ];

        return $this->map;
    }
}
