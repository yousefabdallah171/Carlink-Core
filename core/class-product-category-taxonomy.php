<?php
namespace RakmyatCore\Core;

if ( ! defined( 'ABSPATH' ) ) exit;

class Product_Category_Taxonomy {
    private static $instance = null;

    public static function instance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'product_cat_add_form_fields', [ $this, 'add_icon_field' ] );
        add_action( 'product_cat_edit_form_fields', [ $this, 'edit_icon_field' ], 10 );
        add_action( 'created_product_cat', [ $this, 'save_icon_field' ] );
        add_action( 'edited_product_cat', [ $this, 'save_icon_field' ] );
        add_filter( 'manage_edit-product_cat_columns', [ $this, 'add_icon_column' ] );
        add_filter( 'manage_product_cat_custom_column', [ $this, 'icon_column_content' ], 10, 3 );
        add_action( 'admin_footer-edit-tags.php', [ $this, 'enqueue_media_uploader' ] );
        add_action( 'wp_ajax_rmt_upload_category_icon', [ $this, 'handle_icon_upload' ] );
    }

    /**
     * Add Icon field to "Add New Category" form
     */
    public function add_icon_field() {
        wp_nonce_field( 'rmt_category_icon', 'rmt_category_icon_nonce' );
        ?>
        <div class="form-field">
            <label for="product_cat_icon"><?php esc_html_e( 'Category Icon', 'rakmyat-core' ); ?></label>
            <div id="category-icon-preview" style="margin-bottom: 10px;">
                <img id="icon-preview-img" src="" alt="Icon preview" style="max-width: 80px; max-height: 80px; display: none;">
            </div>
            <input type="hidden" name="product_cat_icon_id" id="product_cat_icon_id" value="">
            <button type="button" class="button rmt-upload-icon-btn"><?php esc_html_e( 'Upload Icon', 'rakmyat-core' ); ?></button>
            <button type="button" class="button rmt-remove-icon-btn" style="margin-left: 5px; display: none;"><?php esc_html_e( 'Remove Icon', 'rakmyat-core' ); ?></button>
            <p class="description"><?php esc_html_e( 'Upload an icon image for this category (recommended: square image, min 200x200px).', 'rakmyat-core' ); ?></p>
        </div>
        <?php
        $this->enqueue_media_uploader();
    }

    /**
     * Add Icon field to "Edit Category" form
     */
    public function edit_icon_field( $term ) {
        $icon_id = get_term_meta( $term->term_id, 'product_cat_icon_id', true );
        $icon_url = '';
        if ( $icon_id ) {
            $icon_url = wp_get_attachment_image_url( $icon_id, 'thumbnail' );
        }
        wp_nonce_field( 'rmt_category_icon', 'rmt_category_icon_nonce' );
        ?>
        <tr class="form-field">
            <th scope="row"><label for="product_cat_icon"><?php esc_html_e( 'Category Icon', 'rakmyat-core' ); ?></label></th>
            <td>
                <div id="category-icon-preview" style="margin-bottom: 10px;">
                    <img id="icon-preview-img" src="<?php echo esc_url( $icon_url ); ?>" alt="Icon preview" style="max-width: 80px; max-height: 80px; <?php echo $icon_url ? '' : 'display: none;'; ?>">
                </div>
                <input type="hidden" name="product_cat_icon_id" id="product_cat_icon_id" value="<?php echo esc_attr( $icon_id ); ?>">
                <button type="button" class="button rmt-upload-icon-btn"><?php esc_html_e( 'Upload Icon', 'rakmyat-core' ); ?></button>
                <button type="button" class="button rmt-remove-icon-btn" style="margin-left: 5px; <?php echo $icon_id ? '' : 'display: none;'; ?>"><?php esc_html_e( 'Remove Icon', 'rakmyat-core' ); ?></button>
                <p class="description"><?php esc_html_e( 'Upload an icon image for this category (recommended: square image, min 200x200px).', 'rakmyat-core' ); ?></p>
            </td>
        </tr>
        <?php
        $this->enqueue_media_uploader();
    }

    /**
     * Save Icon field value
     */
    public function save_icon_field( $term_id ) {
        if ( ! isset( $_POST['rmt_category_icon_nonce'] ) || ! wp_verify_nonce( $_POST['rmt_category_icon_nonce'], 'rmt_category_icon' ) ) {
            return;
        }
        if ( isset( $_POST['product_cat_icon_id'] ) ) {
            $icon_id = sanitize_text_field( wp_unslash( $_POST['product_cat_icon_id'] ) );
            if ( $icon_id ) {
                update_term_meta( $term_id, 'product_cat_icon_id', $icon_id );
            } else {
                delete_term_meta( $term_id, 'product_cat_icon_id' );
            }
        }
    }

    /**
     * Add Icon column to categories list table
     */
    public function add_icon_column( $columns ) {
        $new_columns = [];
        foreach ( $columns as $key => $value ) {
            if ( $key === 'name' ) {
                $new_columns['cat_icon'] = __( 'Icon', 'rakmyat-core' );
            }
            $new_columns[ $key ] = $value;
        }
        return $new_columns;
    }

    /**
     * Display Icon column content
     */
    public function icon_column_content( $content, $column_name, $term_id ) {
        if ( $column_name === 'cat_icon' ) {
            $icon_id = get_term_meta( $term_id, 'product_cat_icon_id', true );
            if ( $icon_id ) {
                $icon_url = wp_get_attachment_image_url( $icon_id, 'thumbnail' );
                return $icon_url ? '<img src="' . esc_url( $icon_url ) . '" style="max-width: 50px; max-height: 50px;">' : '&mdash;';
            }
            return '&mdash;';
        }
        return $content;
    }

    /**
     * Enqueue media uploader scripts
     */
    public function enqueue_media_uploader() {
        if ( ! did_action( 'wp_enqueue_media' ) ) {
            wp_enqueue_media();
        }
        wp_add_inline_script( 'media-upload', $this->get_media_uploader_script() );
    }

    /**
     * JavaScript for media uploader
     */
    private function get_media_uploader_script() {
        return "
        jQuery(document).ready(function($) {
            let mediaFrame;

            $(document).on('click', '.rmt-upload-icon-btn', function(e) {
                e.preventDefault();

                if (mediaFrame) {
                    mediaFrame.open();
                    return;
                }

                mediaFrame = wp.media({
                    title: '" . esc_js( __( 'Select Category Icon', 'rakmyat-core' ) ) . "',
                    button: { text: '" . esc_js( __( 'Select Icon', 'rakmyat-core' ) ) . "' },
                    multiple: false,
                    library: { type: 'image' }
                });

                mediaFrame.on('select', function() {
                    let attachment = mediaFrame.state().get('selection').first().toJSON();
                    $('#product_cat_icon_id').val(attachment.id);
                    $('#icon-preview-img').attr('src', attachment.url).show();
                    $('.rmt-remove-icon-btn').show();
                });

                mediaFrame.open();
            });

            $(document).on('click', '.rmt-remove-icon-btn', function(e) {
                e.preventDefault();
                $('#product_cat_icon_id').val('');
                $('#icon-preview-img').hide();
                $(this).hide();
            });
        });
        ";
    }

    /**
     * Handle AJAX icon upload (optional fallback)
     */
    public function handle_icon_upload() {
        if ( ! current_user_can( 'manage_product_terms' ) ) {
            wp_die( 'Unauthorized' );
        }

        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'rmt_category_icon' ) ) {
            wp_die( 'Nonce verification failed' );
        }

        if ( empty( $_FILES['icon'] ) ) {
            wp_send_json_error( 'No file uploaded' );
        }

        $attachment_id = media_handle_upload( 'icon', 0 );

        if ( is_wp_error( $attachment_id ) ) {
            wp_send_json_error( $attachment_id->get_error_message() );
        }

        wp_send_json_success( [
            'attachment_id' => $attachment_id,
            'url' => wp_get_attachment_image_url( $attachment_id, 'thumbnail' ),
        ] );
    }
}
