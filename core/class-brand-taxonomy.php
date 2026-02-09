<?php
namespace RakmyatCore\Core;

if ( ! defined( 'ABSPATH' ) ) exit;

class Brand_Taxonomy {
    private static $instance = null;

    public static function instance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'product_brand_add_form_fields', [ $this, 'add_country_field' ] );
        add_action( 'product_brand_edit_form_fields', [ $this, 'edit_country_field' ], 10 );
        add_action( 'created_product_brand', [ $this, 'save_country_field' ] );
        add_action( 'edited_product_brand', [ $this, 'save_country_field' ] );
        add_filter( 'manage_edit-product_brand_columns', [ $this, 'add_country_column' ] );
        add_filter( 'manage_product_brand_custom_column', [ $this, 'country_column_content' ], 10, 3 );
    }

    /**
     * Add Country field to "Add New Brand" form
     */
    public function add_country_field() {
        ?>
        <div class="form-field">
            <label for="brand_country"><?php esc_html_e( 'Country', 'rakmyat-core' ); ?></label>
            <input type="text" name="brand_country" id="brand_country" value="">
            <p class="description"><?php esc_html_e( 'The country or origin of this brand (e.g., Germany, Japan, USA).', 'rakmyat-core' ); ?></p>
        </div>
        <?php
    }

    /**
     * Add Country field to "Edit Brand" form
     */
    public function edit_country_field( $term ) {
        $country = get_term_meta( $term->term_id, 'brand_country', true );
        ?>
        <tr class="form-field">
            <th scope="row"><label for="brand_country"><?php esc_html_e( 'Country', 'rakmyat-core' ); ?></label></th>
            <td>
                <input type="text" name="brand_country" id="brand_country" value="<?php echo esc_attr( $country ); ?>">
                <p class="description"><?php esc_html_e( 'The country or origin of this brand (e.g., Germany, Japan, USA).', 'rakmyat-core' ); ?></p>
            </td>
        </tr>
        <?php
    }

    /**
     * Save Country field value
     */
    public function save_country_field( $term_id ) {
        if ( isset( $_POST['brand_country'] ) ) {
            update_term_meta( $term_id, 'brand_country', sanitize_text_field( wp_unslash( $_POST['brand_country'] ) ) );
        }
    }

    /**
     * Add Country column to brands list table
     */
    public function add_country_column( $columns ) {
        $new_columns = [];
        foreach ( $columns as $key => $value ) {
            $new_columns[ $key ] = $value;
            if ( $key === 'name' ) {
                $new_columns['brand_country'] = __( 'Country', 'rakmyat-core' );
            }
        }
        return $new_columns;
    }

    /**
     * Display Country column content
     */
    public function country_column_content( $content, $column_name, $term_id ) {
        if ( $column_name === 'brand_country' ) {
            $country = get_term_meta( $term_id, 'brand_country', true );
            return $country ? esc_html( $country ) : '&mdash;';
        }
        return $content;
    }
}
