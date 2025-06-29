<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Repeater;

/**
 * Elementor News Ticker Widget.
 *
 * @since 1.0.0
 */
class Elementor_News_Ticker_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'news-ticker'; }
    public function get_title() { return __( 'News Ticker', 'elementor-news-ticker' ); }
    public function get_icon() { return 'eicon-posts-ticker'; }
    public function get_categories() { return [ 'general' ]; }
    public function get_script_depends() { return [ 'news-ticker-frontend' ]; }
    public function get_style_depends() { return [ 'news-ticker-style' ]; }
    
    private function get_post_types() {
        $post_types = get_post_types( [ 'public' => true ], 'objects' );
        $options = [ 'custom' => __( 'Custom', 'elementor-news-ticker' ) ];
        foreach ( $post_types as $post_type ) { $options[ $post_type->name ] = $post_type->label; }
        return $options;
    }

    protected function _register_controls() {

        //============================================
        //            CONTENT TAB
        //============================================
        $this->start_controls_section( 'section_content', [ 'label' => __( 'Query', 'elementor-news-ticker' ) ] );
        
        $this->add_control( 'post_type', [ 'label' => __( 'Source', 'elementor-news-ticker' ), 'type' => Controls_Manager::SELECT, 'options' => $this->get_post_types(), 'default' => 'post']);
        
        $repeater = new Repeater();
        $repeater->add_control('item_title', [ 'label' => __( 'Title', 'elementor-news-ticker' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Custom Ticker Title' , 'elementor-news-ticker' ), 'label_block' => true ]);
        $repeater->add_control('item_link', [ 'label' => __( 'Link', 'elementor-news-ticker' ), 'type' => Controls_Manager::URL, 'default' => [ 'url' => '#' ] ]);
        $this->add_control('custom_items', [ 'label' => __( 'Custom Items', 'elementor-news-ticker' ), 'type' => Controls_Manager::REPEATER, 'fields' => $repeater->get_controls(), 'default' => [ [ 'item_title' => __( 'News Item #1', 'elementor-news-ticker' ) ], [ 'item_title' => __( 'News Item #2', 'elementor-news-ticker' ) ] ], 'title_field' => '{{{ item_title }}}', 'condition' => [ 'post_type' => 'custom' ] ]);
        $this->add_control('posts_per_page', [ 'label' => __( 'Number of Posts', 'elementor-news-ticker' ), 'type' => Controls_Manager::NUMBER, 'default' => 5, 'condition' => [ 'post_type!' => 'custom' ] ]);
        $this->add_control('orderby', [ 'label' => __( 'Order By', 'elementor-news-ticker' ), 'type' => Controls_Manager::SELECT, 'default' => 'date', 'options' => [ 'date' => __( 'Date', 'elementor-news-ticker' ), 'title' => __( 'Title', 'elementor-news-ticker' ), 'rand' => __( 'Random', 'elementor-news-ticker' ) ], 'condition' => [ 'post_type!' => 'custom' ] ]);
        $this->add_control('order', [ 'label' => __( 'Order', 'elementor-news-ticker' ), 'type' => Controls_Manager::SELECT, 'default' => 'DESC', 'options' => [ 'ASC' => 'ASC', 'DESC' => 'DESC' ], 'condition' => [ 'post_type!' => 'custom' ] ]);
        $this->add_control('show_post_date', [ 'label' => __( 'Show Post Date', 'elementor-news-ticker' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'condition' => [ 'post_type!' => 'custom' ] ]);

        $this->end_controls_section();

        $this->start_controls_section('section_ticker_settings', [ 'label' => __( 'Ticker Settings', 'elementor-news-ticker' ) ]);
        $this->add_control('show_label', [ 'label' => __( 'Show Label', 'elementor-news-ticker' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ]);
        $this->add_control('ticker_label', [ 'label' => __( 'Label Text', 'elementor-news-ticker' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'LATEST', 'elementor-news-ticker' ), 'condition' => [ 'show_label' => 'yes' ] ]);
        $this->add_control('animation_effect', [ 'label' => esc_html__( 'Animation Effect', 'elementor-news-ticker' ), 'type' => Controls_Manager::SELECT, 'default' => 'scroll', 'options' => [ 'scroll'  => esc_html__( 'Scroll', 'elementor-news-ticker' ), 'fade' => esc_html__( 'Fade', 'elementor-news-ticker' ), 'slide-up' => esc_html__( 'Slide Up', 'elementor-news-ticker' ) ] ]);
        $this->add_control('animation_speed', [ 'label' => esc_html__( 'Animation Speed (ms)', 'elementor-news-ticker' ), 'type' => Controls_Manager::NUMBER, 'default' => 5000 ]);
        $this->add_control('infinite_loop', [ 'label' => __( 'Infinite Loop', 'elementor-news-ticker' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'condition' => [ 'animation_effect!' => 'scroll' ] ]);
        $this->add_control('navigation_arrows', [ 'label' => __( 'Navigation Arrows', 'elementor-news-ticker' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'condition' => [ 'animation_effect!' => 'scroll' ] ]);
        $this->end_controls_section();

        //============================================
        //              STYLE TAB
        //============================================
        $this->start_controls_section('section_design_style', [ 'label' => __( 'General Design', 'elementor-news-ticker' ), 'tab' => Controls_Manager::TAB_STYLE ]);
        $this->add_control('predefined_design', [
            'label' => __( 'Predefined Design', 'elementor-news-ticker' ), 'type' => Controls_Manager::SELECT, 'default' => 'design-default',
            'options' => [
                'design-default' => __( 'Default', 'elementor-news-ticker' ),
                'design-modern' => __( 'Modern Dark', 'elementor-news-ticker' ),
                'design-minimal' => __( 'Minimal', 'elementor-news-ticker' ),
                'design-glass' => __( 'Glassmorphism', 'elementor-news-ticker' ),
                'design-bordered' => __( 'Heavy Border', 'elementor-news-ticker' ),
                'design-gradient' => __( 'Vibrant Gradient', 'elementor-news-ticker' ),
            ],
        ]);
        $this->add_control('wrapper_background_color', [ 'label' => __( 'Wrapper Background', 'elementor-news-ticker' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .news-ticker-wrapper' => 'background-color: {{VALUE}}' ] ]);
        $this->add_group_control(Group_Control_Border::get_type(), [ 'name' => 'wrapper_border', 'selector' => '{{WRAPPER}} .news-ticker-wrapper' ]);
        $this->add_responsive_control('wrapper_padding', [ 'label' => __( 'Padding', 'elementor-news-ticker' ), 'type' => Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .news-ticker-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ]);
        $this->end_controls_section();

        $this->start_controls_section('section_label_style', [ 'label' => __( 'Label', 'elementor-news-ticker' ), 'tab' => Controls_Manager::TAB_STYLE, 'condition' => [ 'show_label' => 'yes' ] ]);
        $this->add_control('label_design', [
            'label' => __( 'Design', 'elementor-news-ticker' ), 'type' => Controls_Manager::SELECT, 'default' => 'design-1',
            'options' => [
                'design-1' => __( 'Rectangle', 'elementor-news-ticker' ),
                'design-2' => __( 'Arrow Right', 'elementor-news-ticker' ),
                'design-3' => __( 'Rounded', 'elementor-news-ticker' ),
                'design-4' => __( 'Ribbon', 'elementor-news-ticker' ),
                'design-5' => __( 'Tag', 'elementor-news-ticker' ),
                'design-6' => __( 'Corner Cut', 'elementor-news-ticker' ),
                'design-7' => __( 'Circle', 'elementor-news-ticker' ),
            ],
        ]);
        $this->add_control('label_text_color', [ 'label' => __( 'Text Color', 'elementor-news-ticker' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .ticker-label' => 'color: {{VALUE}}' ] ]);
        $this->add_control('label_background_color', [ 'label' => __( 'Background Color', 'elementor-news-ticker' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .ticker-label' => 'background-color: {{VALUE}}', '{{WRAPPER}} .ticker-label.design-2:after' => 'border-left-color: {{VALUE}}', '{{WRAPPER}} .ticker-label.design-5:after' => 'border-left-color: {{VALUE}}' ] ]);
        $this->add_group_control(Group_Control_Typography::get_type(), [ 'name' => 'label_typography', 'selector' => '{{WRAPPER}} .ticker-label' ]);
        $this->add_responsive_control('label_padding', [ 'label' => __( 'Padding', 'elementor-news-ticker' ), 'type' => Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .ticker-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ]);
        $this->add_responsive_control('label_margin', [ 'label' => __( 'Margin', 'elementor-news-ticker' ), 'type' => Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .ticker-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ]);
        $this->end_controls_section();

        $this->start_controls_section('section_content_style', [ 'label' => __( 'Content', 'elementor-news-ticker' ), 'tab' => Controls_Manager::TAB_STYLE ]);
        $this->add_group_control(Group_Control_Typography::get_type(), [ 'name' => 'content_typography', 'selector' => '{{WRAPPER}} .ticker-content a' ]);
        $this->add_control('content_text_color', [ 'label' => __( 'Text Color', 'elementor-news-ticker' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .ticker-content a' => 'color: {{VALUE}}' ] ]);
        $this->add_control('content_link_hover_color', [ 'label' => __( 'Link Hover Color', 'elementor-news-ticker' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .ticker-content a:hover' => 'color: {{VALUE}}' ] ]);
        $this->add_control('date_heading', [ 'label' => __( 'Date', 'elementor-news-ticker' ), 'type' => Controls_Manager::HEADING, 'separator' => 'before' ]);
        $this->add_control('date_color', [ 'label' => __( 'Date Color', 'elementor-news-ticker' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .ticker-item-date' => 'color: {{VALUE}}' ] ]);
        $this->add_group_control(Group_Control_Typography::get_type(), [ 'name' => 'date_typography', 'selector' => '{{WRAPPER}} .ticker-item-date' ]);
        $this->end_controls_section();

        $this->start_controls_section('section_navigation_style', [ 'label' => __( 'Navigation Arrows', 'elementor-news-ticker' ), 'tab' => Controls_Manager::TAB_STYLE, 'condition' => [ 'navigation_arrows' => 'yes', 'animation_effect!' => 'scroll' ] ]);
        $this->add_control('arrow_color', [ 'label' => __( 'Arrow Color', 'elementor-news-ticker' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .ticker-nav-arrow svg' => 'fill: {{VALUE}}' ] ]);
        $this->add_control('arrow_background', [ 'label' => __( 'Background Color', 'elementor-news-ticker' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .ticker-nav-arrow' => 'background-color: {{VALUE}}' ] ]);
        $this->add_control('arrow_hover_color', [ 'label' => __( 'Arrow Hover Color', 'elementor-news-ticker' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .ticker-nav-arrow:hover svg' => 'fill: {{VALUE}}' ] ]);
        $this->add_control('arrow_hover_background', [ 'label' => __( 'Hover Background', 'elementor-news-ticker' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .ticker-nav-arrow:hover' => 'background-color: {{VALUE}}' ] ]);
        $this->add_responsive_control('arrow_size', [ 'label' => __( 'Arrow Size', 'elementor-news-ticker' ), 'type' => Controls_Manager::SLIDER, 'range' => ['px' => ['min' => 10, 'max' => 50]], 'selectors' => [ '{{WRAPPER}} .ticker-nav-arrow svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ] ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $this->add_render_attribute( 'ticker_wrapper', 'class', ['news-ticker-wrapper', $settings['predefined_design']] );
        $this->add_render_attribute( 'ticker_wrapper', 'data-effect', $settings['animation_effect'] );
        $this->add_render_attribute( 'ticker_wrapper', 'data-speed', $settings['animation_speed'] );
        $this->add_render_attribute( 'ticker_wrapper', 'data-infinite', $settings['infinite_loop'] );
        
        $this->add_render_attribute( 'ticker_label', 'class', ['ticker-label', $settings['label_design']] );
        ?>
        <div <?php echo $this->get_render_attribute_string( 'ticker_wrapper' ); ?>>
            
            <?php if ( 'yes' === $settings['show_label'] && !empty($settings['ticker_label']) ) : ?>
                <div <?php echo $this->get_render_attribute_string( 'ticker_label' ); ?>>
                    <?php echo esc_html( $settings['ticker_label'] ); ?>
                </div>
            <?php endif; ?>

            <div class="ticker-content">
                <ul class="ticker-list">
                    <?php
                    if ($settings['post_type'] === 'custom') {
                        foreach (  $settings['custom_items'] as $item ) {
                            $link_props = 'href="' . esc_url($item['item_link']['url']) . '"';
                            if($item['item_link']['is_external']) { $link_props .= ' target="_blank"'; }
                            if($item['item_link']['nofollow']) { $link_props .= ' rel="nofollow"'; }
                            echo '<li><a ' . $link_props . '>' . esc_html($item['item_title']) . '</a></li>';
                        }
                    } else {
                        $args = [ 'post_type' => $settings['post_type'], 'posts_per_page' => $settings['posts_per_page'], 'orderby' => $settings['orderby'], 'order' => $settings['order'], 'post_status' => 'publish' ];
                        $query = new \WP_Query( $args );
                        if ($query->have_posts()) {
                             while ( $query->have_posts() ) {
                                $query->the_post();
                                $date_html = ('yes' === $settings['show_post_date']) ? '<span class="ticker-item-date">' . get_the_date() . '</span>' : '';
                                echo '<li><a href="' . get_the_permalink() . '">' . get_the_title() . '</a>' . $date_html . '</li>';
                            }
                        }
                        wp_reset_postdata();
                    }
                    ?>
                </ul>
            </div>
            
            <?php if ( 'yes' === $settings['navigation_arrows'] && 'scroll' !== $settings['animation_effect'] ) : ?>
                <div class="ticker-navigation">
                    <div class="ticker-nav-arrow arrow-prev">
                        <svg viewBox="0 0 256 512"><path d="M192 448c-8.188 0-16.38-3.125-22.62-9.375l-160-160c-12.5-12.5-12.5-32.75 0-45.25l160-160c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25L77.25 256l137.4 137.4c12.5 12.5 12.5 32.75 0 45.25C208.4 444.9 200.2 448 192 448z"/></svg>
                    </div>
                    <div class="ticker-nav-arrow arrow-next">
                        <svg viewBox="0 0 256 512"><path d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"/></svg>
                    </div>
                </div>
            <?php endif; ?>

        </div>
        <?php
    }
}
