<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) exit;

class RMT_Product_Reviews_Widget extends Widget_Base {

    public function get_name() { return 'product-reviews'; }
    public function get_title() { return __( 'Product Reviews', 'rakmyat-core' ); }
    public function get_icon() { return 'eicon-review'; }
    public function get_categories() { return [ 'rakmyat-elements' ]; }

    public function get_style_depends() { return [ 'rmt-product-reviews-css' ]; }
    public function get_script_depends() { return [ 'rmt-product-reviews' ]; }

    protected function register_controls() {

        // --- CONTENT TAB ---
        $this->start_controls_section('section_content', [
            'label' => __( 'Content', 'rakmyat-core' ),
        ]);

        $this->add_control('reviews_title', [
            'label' => __( 'Reviews Title', 'rakmyat-core' ),
            'type' => Controls_Manager::TEXT,
            'default' => __( 'Customer reviews', 'rakmyat-core' ),
        ]);

        $this->add_control('top_reviews_title', [
            'label' => __( 'Top Reviews Title', 'rakmyat-core' ),
            'type' => Controls_Manager::TEXT,
            'default' => __( 'Top reviews', 'rakmyat-core' ),
        ]);

        $this->add_control('reviews_per_page', [
            'label' => __( 'Reviews Per Page', 'rakmyat-core' ),
            'type' => Controls_Manager::NUMBER,
            'default' => 4,
            'min' => 1,
            'max' => 20,
        ]);

        $this->add_control('show_like_dislike', [
            'label' => __( 'Show Like/Dislike Buttons', 'rakmyat-core' ),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('show_view_more', [
            'label' => __( 'Show View More Link', 'rakmyat-core' ),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('view_more_text', [
            'label' => __( 'View More Text', 'rakmyat-core' ),
            'type' => Controls_Manager::TEXT,
            'default' => __( 'View more', 'rakmyat-core' ),
            'condition' => [ 'show_view_more' => 'yes' ],
        ]);

        $this->end_controls_section();

        // --- STYLE TAB: SUMMARY ---
        $this->start_controls_section('section_style_summary', [
            'label' => __( 'Reviews Summary', 'rakmyat-core' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'summary_title_typo',
            'label' => __( 'Title Typography', 'rakmyat-core' ),
            'selector' => '{{WRAPPER}} .rmt-reviews-summary-title',
        ]);

        $this->add_control('summary_title_color', [
            'label' => __( 'Title Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#0A0A0A',
            'selectors' => [ '{{WRAPPER}} .rmt-reviews-summary-title' => 'color: {{VALUE}};' ],
        ]);

        $this->add_control('star_color', [
            'label' => __( 'Star Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#F59E0B',
            'selectors' => [ '{{WRAPPER}} .rmt-star-filled' => 'color: {{VALUE}};' ],
        ]);

        $this->add_control('star_empty_color', [
            'label' => __( 'Empty Star Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#E5E7EB',
            'selectors' => [ '{{WRAPPER}} .rmt-star-empty' => 'color: {{VALUE}};' ],
        ]);

        $this->add_control('progress_bar_color', [
            'label' => __( 'Progress Bar Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#F59E0B',
            'selectors' => [ '{{WRAPPER}} .rmt-rating-bar-fill' => 'background-color: {{VALUE}};' ],
        ]);

        $this->add_control('progress_bar_bg_color', [
            'label' => __( 'Progress Bar Background', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#E5E7EB',
            'selectors' => [ '{{WRAPPER}} .rmt-rating-bar' => 'background-color: {{VALUE}};' ],
        ]);

        $this->end_controls_section();

        // --- STYLE TAB: REVIEWS LIST ---
        $this->start_controls_section('section_style_reviews', [
            'label' => __( 'Reviews List', 'rakmyat-core' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('avatar_bg_color', [
            'label' => __( 'Avatar Background', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#3A5F79',
            'selectors' => [ '{{WRAPPER}} .rmt-review-avatar' => 'background-color: {{VALUE}};' ],
        ]);

        $this->add_control('avatar_text_color', [
            'label' => __( 'Avatar Text Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [ '{{WRAPPER}} .rmt-review-avatar' => 'color: {{VALUE}};' ],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'reviewer_name_typo',
            'label' => __( 'Reviewer Name Typography', 'rakmyat-core' ),
            'selector' => '{{WRAPPER}} .rmt-reviewer-name',
        ]);

        $this->add_control('reviewer_name_color', [
            'label' => __( 'Reviewer Name Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#0A0A0A',
            'selectors' => [ '{{WRAPPER}} .rmt-reviewer-name' => 'color: {{VALUE}};' ],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'review_text_typo',
            'label' => __( 'Review Text Typography', 'rakmyat-core' ),
            'selector' => '{{WRAPPER}} .rmt-review-content',
        ]);

        $this->add_control('review_text_color', [
            'label' => __( 'Review Text Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#374151',
            'selectors' => [ '{{WRAPPER}} .rmt-review-content' => 'color: {{VALUE}};' ],
        ]);

        $this->end_controls_section();

        // --- STYLE TAB: RATING BREAKDOWN ---
        $this->start_controls_section('section_style_rating_breakdown', [
            'label' => __( 'Rating Breakdown', 'rakmyat-core' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('rating_label_color', [
            'label' => __( 'Rating Label Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#3B82F6',
            'selectors' => [ '{{WRAPPER}} .rmt-rating-label' => 'color: {{VALUE}};' ],
        ]);

        $this->add_control('rating_bar_bg_color', [
            'label' => __( 'Rating Bar Background', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#E5E7EB',
            'selectors' => [ '{{WRAPPER}} .rmt-rating-bar' => 'background-color: {{VALUE}};' ],
        ]);

        $this->add_control('rating_bar_fill_color', [
            'label' => __( 'Rating Bar Fill', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#F59E0B',
            'selectors' => [ '{{WRAPPER}} .rmt-rating-bar-fill' => 'background-color: {{VALUE}};' ],
        ]);

        $this->end_controls_section();

        // --- STYLE TAB: REVIEW ITEMS ---
        $this->start_controls_section('section_style_review_items', [
            'label' => __( 'Review Items', 'rakmyat-core' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('review_border_color', [
            'label' => __( 'Item Border Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#E5E7EB',
            'selectors' => [ '{{WRAPPER}} .rmt-review-item' => 'border-bottom-color: {{VALUE}};' ],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'reviewer_title_typo',
            'label' => __( 'Reviewer Title Typography', 'rakmyat-core' ),
            'selector' => '{{WRAPPER}} .rmt-reviewer-title',
        ]);

        $this->add_control('reviewer_title_color', [
            'label' => __( 'Reviewer Title Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#6B7280',
            'selectors' => [ '{{WRAPPER}} .rmt-reviewer-title' => 'color: {{VALUE}};' ],
        ]);

        $this->end_controls_section();

        // --- STYLE TAB: ACTION BUTTONS ---
        $this->start_controls_section('section_style_buttons', [
            'label' => __( 'Like/Dislike Buttons', 'rakmyat-core' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('btn_border_color', [
            'label' => __( 'Button Border Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#D1D5DB',
            'selectors' => [ '{{WRAPPER}} .rmt-review-btn' => 'border-color: {{VALUE}};' ],
        ]);

        $this->add_control('btn_text_color', [
            'label' => __( 'Button Text Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#6B7280',
            'selectors' => [ '{{WRAPPER}} .rmt-review-btn' => 'color: {{VALUE}};' ],
        ]);

        $this->add_control('btn_hover_border_color', [
            'label' => __( 'Button Hover Border', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#9CA3AF',
            'selectors' => [ '{{WRAPPER}} .rmt-review-btn:hover' => 'border-color: {{VALUE}};' ],
        ]);

        $this->add_control('btn_active_border_color', [
            'label' => __( 'Button Active Border', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#3A5F79',
            'selectors' => [ '{{WRAPPER}} .rmt-review-btn.active' => 'border-color: {{VALUE}};' ],
        ]);

        $this->add_control('btn_active_text_color', [
            'label' => __( 'Button Active Text Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#3A5F79',
            'selectors' => [ '{{WRAPPER}} .rmt-review-btn.active' => 'color: {{VALUE}};' ],
        ]);

        $this->add_control('btn_active_bg_color', [
            'label' => __( 'Button Active Background', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => 'rgba(58, 95, 121, 0.05)',
            'selectors' => [ '{{WRAPPER}} .rmt-review-btn.active' => 'background-color: {{VALUE}};' ],
        ]);

        $this->end_controls_section();

        // --- STYLE TAB: LAYOUT ---
        $this->start_controls_section('section_style_layout', [
            'label' => __( 'Layout', 'rakmyat-core' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('layout_gap', [
            'label' => __( 'Gap Between Columns', 'rakmyat-core' ),
            'type' => Controls_Manager::SLIDER,
            'default' => [ 'size' => 60 ],
            'range' => [ 'px' => [ 'min' => 20, 'max' => 100 ] ],
            'selectors' => [ '{{WRAPPER}} .rmt-reviews-container' => 'gap: {{SIZE}}px;' ],
        ]);

        $this->add_control('summary_width', [
            'label' => __( 'Summary Width (Left Column)', 'rakmyat-core' ),
            'type' => Controls_Manager::SLIDER,
            'default' => [ 'size' => 280 ],
            'range' => [ 'px' => [ 'min' => 200, 'max' => 400 ] ],
            'selectors' => [ '{{WRAPPER}} .rmt-reviews-container' => 'grid-template-columns: {{SIZE}}px 1fr;' ],
        ]);

        $this->end_controls_section();

        // --- STYLE TAB: VIEW MORE LINK ---
        $this->start_controls_section('section_style_view_more', [
            'label' => __( 'View More Link', 'rakmyat-core' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'view_more_typo',
            'label' => __( 'Typography', 'rakmyat-core' ),
            'selector' => '{{WRAPPER}} .rmt-view-more-link',
        ]);

        $this->add_control('view_more_color', [
            'label' => __( 'Link Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#3A5F79',
            'selectors' => [ '{{WRAPPER}} .rmt-view-more-link' => 'color: {{VALUE}};' ],
        ]);

        $this->add_control('view_more_hover_color', [
            'label' => __( 'Hover Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#3A5F79',
            'selectors' => [ '{{WRAPPER}} .rmt-view-more-link:hover' => 'color: {{VALUE}};' ],
        ]);

        $this->end_controls_section();

        // --- STYLE TAB: NO REVIEWS MESSAGE ---
        $this->start_controls_section('section_style_no_reviews', [
            'label' => __( 'No Reviews Message', 'rakmyat-core' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'no_reviews_typo',
            'label' => __( 'Typography', 'rakmyat-core' ),
            'selector' => '{{WRAPPER}} .rmt-no-reviews',
        ]);

        $this->add_control('no_reviews_color', [
            'label' => __( 'Text Color', 'rakmyat-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#6B7280',
            'selectors' => [ '{{WRAPPER}} .rmt-no-reviews' => 'color: {{VALUE}};' ],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // Get current product
        global $product;

        if ( ! $product ) {
            $product = wc_get_product( get_the_ID() );
        }

        if ( ! $product ) {
            echo '<p>' . __( 'No product found. This widget works on single product pages.', 'rakmyat-core' ) . '</p>';
            return;
        }

        $product_id = $product->get_id();
        $average_rating = $product->get_average_rating();
        $review_count = $product->get_review_count();
        $rating_counts = $this->get_rating_counts( $product_id );

        // Get reviews
        $reviews = get_comments([
            'post_id' => $product_id,
            'status' => 'approve',
            'type' => 'review',
            'number' => $settings['reviews_per_page'],
            'orderby' => 'comment_date_gmt',
            'order' => 'DESC',
        ]);

        ?>
        <div class="rmt-product-reviews">
            <div class="rmt-reviews-container">
                <!-- Left: Summary -->
                <div class="rmt-reviews-summary">
                    <h3 class="rmt-reviews-summary-title"><?php echo esc_html( $settings['reviews_title'] ); ?></h3>

                    <div class="rmt-rating-overview">
                        <div class="rmt-rating-stars-large">
                            <?php echo $this->render_stars( $average_rating ); ?>
                            <span class="rmt-rating-number"><?php echo esc_html( number_format( $average_rating, 1 ) ); ?> out of 5</span>
                        </div>
                        <p class="rmt-total-ratings"><?php echo esc_html( number_format( $review_count ) ); ?> global ratings</p>
                    </div>

                    <div class="rmt-rating-breakdown">
                        <?php for ( $i = 5; $i >= 1; $i-- ) :
                            $count = isset( $rating_counts[ $i ] ) ? $rating_counts[ $i ] : 0;
                            $percentage = $review_count > 0 ? ( $count / $review_count ) * 100 : 0;
                        ?>
                            <div class="rmt-rating-row">
                                <span class="rmt-rating-label"><?php echo $i; ?> star</span>
                                <div class="rmt-rating-bar">
                                    <div class="rmt-rating-bar-fill" style="width: <?php echo esc_attr( $percentage ); ?>%;"></div>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>

                <!-- Right: Reviews List -->
                <div class="rmt-reviews-list">
                    <h3 class="rmt-reviews-list-title"><?php echo esc_html( $settings['top_reviews_title'] ); ?></h3>

                    <?php if ( ! empty( $reviews ) ) : ?>
                        <?php foreach ( $reviews as $review ) :
                            $rating = get_comment_meta( $review->comment_ID, 'rating', true );
                            $author_name = $review->comment_author;
                            $initial = strtoupper( substr( $author_name, 0, 1 ) );
                            $name_parts = explode( ' ', $author_name );
                            $display_name = $name_parts[0];
                            if ( isset( $name_parts[1] ) ) {
                                $display_name .= ' ' . strtoupper( substr( $name_parts[1], 0, 1 ) ) . '.';
                            }
                        ?>
                            <div class="rmt-review-item" data-review-id="<?php echo esc_attr( $review->comment_ID ); ?>">
                                <div class="rmt-review-header">
                                    <div class="rmt-review-avatar">
                                        <?php echo esc_html( $initial ); ?>
                                    </div>
                                    <div class="rmt-review-meta">
                                        <span class="rmt-reviewer-name"><?php echo esc_html( $display_name ); ?></span>
                                        <div class="rmt-review-rating">
                                            <?php echo $this->render_stars( $rating ); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="rmt-review-content">
                                    <?php echo wp_kses_post( $review->comment_content ); ?>
                                </div>
                                <?php if ( $settings['show_like_dislike'] === 'yes' ) : ?>
                                    <div class="rmt-review-actions">
                                        <button class="rmt-review-btn rmt-like-btn" data-action="like">
                                            <span class="rmt-btn-text">Like</span>
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/>
                                            </svg>
                                        </button>
                                        <button class="rmt-review-btn rmt-dislike-btn" data-action="dislike">
                                            <span class="rmt-btn-text">Dislike</span>
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zm7-13h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17"/>
                                            </svg>
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>

                        <?php if ( $settings['show_view_more'] === 'yes' && $review_count > $settings['reviews_per_page'] ) : ?>
                            <a href="<?php echo esc_url( get_permalink( $product_id ) . '#reviews' ); ?>" class="rmt-view-more-link">
                                <?php echo esc_html( $settings['view_more_text'] ); ?>
                            </a>
                        <?php endif; ?>

                    <?php else : ?>
                        <p class="rmt-no-reviews"><?php esc_html_e( 'No reviews yet. Be the first to review this product!', 'rakmyat-core' ); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Render star rating HTML
     */
    private function render_stars( $rating ) {
        $output = '<span class="rmt-stars">';
        $rating = floatval( $rating );

        for ( $i = 1; $i <= 5; $i++ ) {
            if ( $i <= $rating ) {
                $output .= '<span class="rmt-star rmt-star-filled">★</span>';
            } elseif ( $i - 0.5 <= $rating ) {
                $output .= '<span class="rmt-star rmt-star-half">★</span>';
            } else {
                $output .= '<span class="rmt-star rmt-star-empty">★</span>';
            }
        }

        $output .= '</span>';
        return $output;
    }

    /**
     * Get rating counts for each star level
     */
    private function get_rating_counts( $product_id ) {
        global $wpdb;

        $counts = $wpdb->get_results( $wpdb->prepare("
            SELECT meta_value as rating, COUNT(*) as count
            FROM {$wpdb->comments} c
            INNER JOIN {$wpdb->commentmeta} cm ON c.comment_ID = cm.comment_id
            WHERE c.comment_post_ID = %d
            AND c.comment_approved = '1'
            AND c.comment_type = 'review'
            AND cm.meta_key = 'rating'
            GROUP BY meta_value
        ", $product_id ), OBJECT_K );

        $rating_counts = [];
        foreach ( $counts as $rating => $data ) {
            $rating_counts[ intval( $rating ) ] = intval( $data->count );
        }

        return $rating_counts;
    }
}
