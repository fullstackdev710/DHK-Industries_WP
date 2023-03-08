<?php
/**
 * Blockquote
 * 
 * Create quotes via different styling
 * which can be share directly to twitter.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\Blockquote\Widgets;

use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Controls_Manager;

use Neuron\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Blockquote extends Base_Widget {

	public function get_name() {
		return 'neuron-blockquote';
	}

	public function get_title() {
		return __( 'Blockquote', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-blockquote neuron-badge';
	}

	public function get_keywords() {
		return [ 'blockquote', 'quote', 'text', 'cite', 'citation' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'blockquote_section', [
				'label' => __( 'Blockquote', 'neuron-builder' )
			]
        );
        
        $this->add_control(
			'skin',
			[
				'label' => __( 'Skin', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'border',
				'options' => [
					'border'  => __( 'Border', 'neuron-builder' ),
					'quotation' => __( 'Quotation', 'neuron-builder' ),
					'boxed' => __( 'Boxed', 'neuron-builder' ),
					'clean' => __( 'Clean', 'neuron-builder' ),
                ],
                'prefix_class' => 'm-neuron-blockquote--skin-',
			]
        );
        
        $this->add_control(
			'alignment',
			[
				'label' => __( 'Alignment', 'neuron-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'neuron-builder' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'neuron-builder' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'neuron-builder' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'toggle' => true,
				'prefix_class' => 'm-neuron-blockquote--alignment-',
                'condition' => [
                    'skin!' => 'border'
                ],
                'separator' => 'after',
			]
        );
        
        $this->add_control(
			'blockquote_content',
			[
				'label' => __( 'Content', 'neuron-builder' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ut efficitur nunc. Etiam scelerisque venenatis tortor, in tincidunt elit imperdiet eget. Curabitur at ante id urna sagittis dignissim. Donec eget luctus est. Pellentesque laoreet eros ac sagittis pharetra. Proin euismod ornare nibh. Pellentesque sed enim tempus, fermentum purus ac, ullamcorper arcu. Etiam sed lacinia lacus, sit amet efficitur velit.',
                'placeholder' => __( 'Type your content here', 'neuron-builder' ),
                'rows' => 10,
			]
        );
        
        $this->add_control(
			'blockquote_author',
			[
				'label' => __( 'Author', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
                'default' => __( 'John Doe', 'neuron-builder' ),
                'separator' => 'after',
			]
        );
        
        $this->add_control(
			'tweet_button',
			[
				'label' => __( 'Tweet Button', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
        );
        
        $this->add_control(
			'tweet_button_view',
			[
				'label' => __( 'View', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'icon-text',
				'options' => [
					'icon-text'  => __( 'Icon & Text', 'neuron-builder' ),
					'icon' => __( 'Icon', 'neuron-builder' ),
					'text' => __( 'Text', 'neuron-builder' ),
                ],
                'condition' => [
                    'tweet_button' => 'yes'
                ]
			]
        );
        
        $this->add_control(
			'tweet_button_skin',
			[
				'label' => __( 'Skin', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'classic',
				'options' => [
					'classic'  => __( 'Classic', 'neuron-builder' ),
					'bubble' => __( 'Bubble', 'neuron-builder' ),
					'link' => __( 'Link', 'neuron-builder' ),
				],
				'prefix_class' => 'm-neuron-blockquote--button-skin-',
                'condition' => [
                    'tweet_button' => 'yes'
                ]
			]
        );
        
        $this->add_control(
			'tweet_button_label',
			[
				'label' => __( 'Label', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
                'default' => __( 'Tweet', 'neuron-builder' ),
                'condition' => [
                    'tweet_button' => 'yes',
                    'tweet_button_view!' => 'icon'
                ]
			]
        );
        
        $this->add_control(
			'username',
			[
				'label' => __( 'Username', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
                'placeholder' => __( '@neuronthemes', 'neuron-builder' ),
                'condition' => [
                    'tweet_button' => 'yes'
                ]
			]
		);
		
		$this->add_control(
			'url_type',
			[
				'label' => __( 'Target URL', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'current_page' => __( 'Current Page', 'neuron-builder' ),
					'none' => __( 'None', 'neuron-builder' ),
					'custom' => __( 'Custom', 'neuron-builder' ),
				],
				'default' => 'current_page',
				'condition' => [
					'tweet_button' => 'yes',
				],
			]
		);

		$this->add_control(
			'url',
			[
				'label' => __( 'Link', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'url',
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder' => __( 'https://neuronthemes.com', 'neuron-builder' ),
				'label_block' => true,
				'condition' => [
					'url_type' => 'custom',
				],
			]
		);

        $this->end_controls_section(); // End Blockquote Section
        
        $this->start_controls_section(
			'content_style_section', [
                'label' => __( 'Content', 'neuron-builder' ),
                'tab' => Controls_Manager::TAB_STYLE
			]
        );

        $this->add_control(
			'content_text_color',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-blockquote__content' => 'color: {{VALUE}}',
				],
			]
        );
        
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'label' => __( 'Typography', 'neuron-builder' ),
				'selector' => '{{WRAPPER}} .m-neuron-blockquote__content',
			]
        );
        
        $this->add_responsive_control(
			'content_gap',
			[
				'label' => __( 'Gap', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-blockquote__content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
        );
        
        $this->add_control(
			'author_heading',
			[
				'label' => __( 'Author', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
        );
        
        $this->add_control(
			'author_text_color',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-blockquote__author' => 'color: {{VALUE}}',
				],
			]
        );

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'author_typography',
				'label' => __( 'Typography', 'neuron-builder' ),
				'selector' => '{{WRAPPER}} .m-neuron-blockquote__author',
			]
        );
        
        $this->end_controls_section(); // End Content Section

        $this->start_controls_section(
			'button_style_section', [
                'label' => __( 'Button', 'neuron-builder' ),
                'tab' => Controls_Manager::TAB_STYLE
			]
        );

        $this->add_responsive_control(
			'button_size',
			[
				'label' => __( 'Size', 'neuron-builder' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-blockquote__button' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
        );

        $this->add_responsive_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'neuron-builder' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-blockquote__button' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
        );

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'label' => __( 'Typography', 'neuron-builder' ),
				'selector' => '{{WRAPPER}} .m-neuron-blockquote__button',
			]
        );

        $this->add_control(
			'button_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'official',
				'options' => [
					'official' => __( 'Official', 'neuron-builder' ),
					'custom' => __( 'Custom', 'neuron-builder' ),
                ],
			]
        );

        $this->start_controls_tabs(
            'button_style_tabs', [
                'condition' => [
                    'button_color' => 'custom'
                ]
            ]
        );

        $this->start_controls_tab(
			'button_style_normal_tab',
			[
                'label' => __( 'Normal', 'neuron-builder' ),
			]
        );
        
        $this->add_control(
			'button_normal_text_color',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-blockquote__button' => 'color: {{VALUE}}',
				],
			]
        );

        $this->add_control(
			'button_normal_bg_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-blockquote__button' => 'background-color: {{VALUE}}',
				],
			]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
			'button_style_hover_tab',
			[
                'label' => __( 'Hover', 'neuron-builder' ),
			]
        );

        $this->add_control(
			'button_hover_text_color',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-blockquote__button:hover' => 'color: {{VALUE}}',
				],
			]
        );

        $this->add_control(
			'button_hover_bg_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-blockquote__button:hover' => 'background-color: {{VALUE}}',
				],
			]
        );
        
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section(); // End Button Style Section

        $this->start_controls_section(
			'border_style_section', [
                'label' => __( 'Border', 'neuron-builder' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'skin' => 'border'
                ]
			]
        );

        $this->start_controls_tabs(
            'border_style_tabs'
        );

        $this->start_controls_tab(
			'border_style_normal_tab',
			[
                'label' => __( 'Normal', 'neuron-builder' ),
			]
        );
        
        $this->add_control(
			'border_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-blockquote' => 'border-left-color: {{VALUE}}',
				],
			]
        );

        $this->add_responsive_control(
			'border_width',
			[
				'label' => __( 'Width', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-blockquote' => 'border-left-width: {{SIZE}}{{UNIT}}; border-left-style: solid;',
				],
			]
        );

        $this->add_responsive_control(
			'border_gap',
			[
				'label' => __( 'Gap', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-blockquote' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
			'border_style_hover_tab',
			[
                'label' => __( 'Hover', 'neuron-builder' ),
			]
        );

        $this->add_control(
			'border_hover_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-blockquote:hover' => 'border-left-color: {{VALUE}}',
				],
			]
        );

        $this->add_responsive_control(
			'border_hover_width',
			[
				'label' => __( 'Width', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-blockquote:hover' => 'border-left-width: {{SIZE}}{{UNIT}};',
				],
			]
        );

        $this->add_responsive_control(
			'border_hover_gap',
			[
				'label' => __( 'Gap', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-blockquote:hover' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
			'border_vertical_padding',
			[
				'label' => __( 'Vertical Padding', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-blockquote' => 'padding-top: calc({{SIZE}}{{UNIT}} / 2); padding-bottom: calc({{SIZE}}{{UNIT}} / 2)',
				],
			]
        );

        $this->end_controls_section(); // End Border Style Section

        $this->start_controls_section(
			'quote_style_section', [
                'label' => __( 'Quote', 'neuron-builder' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'skin' => 'quotation'
                ]
			]
        );

        $this->add_control(
			'quote_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-blockquote:before' => 'color: {{VALUE}}',
				],
			]
        );

        $this->add_responsive_control(
			'quote_size',
			[
				'label' => __( 'Size', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-blockquote:before' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
        );

        $this->add_responsive_control(
			'quote_gap',
			[
				'label' => __( 'Gap', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-blockquote__content' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
        );

        $this->end_controls_section(); // End Quote Style Section

        $this->start_controls_section(
			'box_style_section', [
                'label' => __( 'Box', 'neuron-builder' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'skin' => 'boxed'
                ]
			]
        );

        $this->add_responsive_control(
			'box_padding',
			[
				'label' => __( 'Padding', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-blockquote' => 'padding: {{SIZE}}{{UNIT}};',
				],
			]
        );

        $this->start_controls_tabs(
            'box_style_tabs'
        );

        $this->start_controls_tab(
			'box_style_normal_tab',
			[
                'label' => __( 'Normal', 'neuron-builder' ),
			]
        );
        
        $this->add_control(
			'box_bg_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-blockquote' => 'background-color: {{VALUE}}',
				],
			]
        );

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'box_border',
				'label' => __( 'Border', 'neuron-builder' ),
				'selector' => '{{WRAPPER}} .m-neuron-blockquote',
			]
        );
        
        $this->add_responsive_control(
			'box_border_radius',
			[
				'label' => __( 'Border Radius', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-blockquote' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
        );

        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'label' => __( 'Box Shadow', 'neuron-builder' ),
				'selector' => '{{WRAPPER}} .m-neuron-blockquote',
			]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
			'box_style_hover_tab',
			[
                'label' => __( 'Hover', 'neuron-builder' ),
			]
        );

        $this->add_control(
			'box_bg_hover_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-blockquote:hover' => 'background-color: {{VALUE}}',
				],
			]
        );

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'box_hover_border',
				'label' => __( 'Border', 'neuron-builder' ),
				'selector' => '{{WRAPPER}} .m-neuron-blockquote:hover',
			]
        );
        
        $this->add_responsive_control(
			'box_hover_border_radius',
			[
				'label' => __( 'Border Radius', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-blockquote:hover' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
        );

        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_hover_shadow',
				'label' => __( 'Box Shadow', 'neuron-builder' ),
				'selector' => '{{WRAPPER}} .m-neuron-blockquote:hover',
			]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section(); // End Box Style Section
	}

	public function get_style_depends() {
		if ( Icons_Manager::is_migration_allowed() ) {
			return [
				'elementor-icons-fa-solid',
				'elementor-icons-fa-regular',
			];
		}
		return [];
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		
		// Share Link
		$share_link = 'https://twitter.com/intent/tweet';

		$text = rawurlencode( $settings['blockquote_content'] );

		if ( ! empty( $settings['blockquote_author'] ) ) {
			$text .= ' — ' . $settings['blockquote_author'];
		}

		$share_link = add_query_arg( 'text', $text, $share_link );

		if ( 'current_page' === $settings['url_type'] ) {
			$share_link = add_query_arg( 'url', rawurlencode( home_url() . add_query_arg( false, false ) ), $share_link );
		} elseif ( 'custom' === $settings['url_type'] ) {
			$share_link = add_query_arg( 'url', rawurlencode( $settings['url'] ), $share_link );
		}

		if ( ! empty( $settings['username'] ) ) {
			$user_name = $settings['username'];
			if ( '@' === substr( $user_name, 0, 1 ) ) {
				$user_name = substr( $user_name, 1 );
			}
			$share_link = add_query_arg( 'via', rawurlencode( $user_name ), $share_link );
		}

        ?>
        <blockquote class="m-neuron-blockquote">
            <?php if ( $settings['blockquote_content'] ) : ?>
                <p class="m-neuron-blockquote__content"><?php echo $settings['blockquote_content'] ?></p>
            <?php endif; ?>

			<?php if ( $settings['blockquote_author'] || $settings['tweet_button'] == 'yes' ) : ?>
				<div class="m-neuron-blockquote__footer">
					<?php if ( $settings['blockquote_author'] ) : ?>
						<cite class="m-neuron-blockquote__author"><?php echo $settings['blockquote_author'] ?></cite>
					<?php endif; ?>

					<?php if ( $settings['tweet_button'] == 'yes' ) : ?>
						<a href="<?php echo esc_attr( $share_link ); ?>" class="m-neuron-blockquote__button" target="_blank">
							<?php if ( $settings['tweet_button_view'] != 'text' ) : ?>
								<i class="fab fa-twitter"></i>
							<?php endif; ?>

							<?php if ( $settings['tweet_button_view'] != 'icon' ) : ?>
								<?php echo $settings['tweet_button_label'] ? $settings['tweet_button_label'] : esc_html__( 'Tweet', 'neuron-builder' ) ?>
							<?php endif; ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
        </blockquote>
        <?php
     }

    protected function content_template() {
		?>
		<# var tweetButtonView = settings.tweet_button_view; #>
		<blockquote class="m-neuron-blockquote">
			<# if ( settings.blockquote_content ) { #>
				<p class="m-neuron-blockquote__content elementor-inline-editing" data-elementor-setting-key="blockquote_content">{{{ settings.blockquote_content }}}</p>
			<# } #>

			<# if ( settings.tweet_button == 'yes' || settings.blockquote_author ) { #>
				<div class="m-neuron-blockquote__footer">
					<# if ( settings.blockquote_author ) { #>
						<cite class="m-neuron-blockquote__author elementor-inline-editing" data-elementor-setting-key="blockquote_author" data-elementor-inline-editing-toolbar="none">{{{ settings.blockquote_author }}}</cite>
					<# } #>

					<# if ( settings.tweet_button == 'yes' ) { #>
						<a href="#" class="m-neuron-blockquote__button">
							<# if ( tweetButtonView !== 'text' ) { #>
								<i class="fab fa-twitter" aria-hidden="true"></i><span class="elementor-screen-only"><?php esc_html_e( 'Tweet', 'neuron-builder' ); ?></span>
							<# } #>
							<# if ( tweetButtonView == 'icon-text' || tweetButtonView == 'text' ) { #>
								<span class="elementor-inline-editing elementor-blockquote__tweet-label" data-elementor-setting-key="tweet_button_label" data-elementor-inline-editing-toolbar="none">{{{ settings.tweet_button_label }}}</span>
							<# } #>
						</a>
					<# } #>
				</div>
			<# } #>
		</blockquote>
		<?php
	}
}
