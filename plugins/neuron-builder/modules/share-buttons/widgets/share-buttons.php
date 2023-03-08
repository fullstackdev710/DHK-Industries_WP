<?php
/**
 * Share Buttons
 * 
 * Add share buttons to your page
 * where you can share content through
 * a modal which is displayed on click.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\ShareButtons\Widgets;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;

use Neuron\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Share_Buttons extends Base_Widget {
	
	public function get_name() {
		return 'neuron-share-buttons';
	}

	public function get_title() {
		return __( 'Share Buttons', 'neuron-builder');
	}

	public function get_icon() {
		return 'eicon-share neuron-badge';
	}

	public function get_script_depends() {
		return [ 'neuron-social-share' ];
	}

	public function get_keywords() {
		return [ 'share', 'button', 'share button', 'social share' ];
	}

	public function get_style_depends() {
		if ( Icons_Manager::is_migration_allowed() ) {
			return [
				'elementor-icons-fa-solid',
				'elementor-icons-fa-brands',
			];
		}
		return [];
	}

	protected function register_controls() {
		// Share Buttons
		$this->start_controls_section(
			'share_buttons_section', [
				'label' => __( 'Share Buttons', 'neuron-builder' )
			]
		);

		// Slides Repeater
		$repeater = new Repeater();
		
		$repeater->add_control(
			'network', [
				'label' => __( 'Network', 'neuron-builder' ),
                'type' => Controls_Manager::SELECT,
                'options' => $this->get_networks(),
				'default' => 'facebook'
			]
		);

		$repeater->add_control(
			'custom_label',
			[
				'label' => __( 'Custom Label', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
			]
		);
		
		$this->add_control(
			'share_buttons',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'network' => 'facebook',
					],
					[
						'network' => 'twitter',
					],
					[
						'network' => 'reddit',
					],
				],
				'title_field' => '<i class="{{ neuron.modules.shareButtons.getNetworkClass( network ) }}" aria-hidden="true"></i> {{{ neuron.modules.shareButtons.getNetworkTitle( obj ) }}}',
			]
		);

		$this->add_control(
			'style',
			[
				'label' => __( 'Style', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'icon-text' => __( 'Icon & Text', 'neuron-builder' ),
					'icon' => __( 'Icon', 'neuron-builder' ),
					'text' => __( 'Text', 'neuron-builder' ),
				],
				'default' => 'icon-text',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'label',
			[
				'label' => __( 'Label', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Show', 'neuron-builder' ),
				'label_off' => __( 'Hide', 'neuron-builder' ),
				'condition' => [
					'style!' => 'icon'
				]
			]
		);

		$this->add_control(
			'skin',
			[
				'label' => __( 'Skin', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'gradient' => __( 'Gradient', 'neuron-builder' ),
					'minimal' => __( 'Minimal', 'neuron-builder' ),
					'framed' => __( 'Framed', 'neuron-builder' ),
					'boxed-icon' => __( 'Boxed Icon', 'neuron-builder' ),
					'flat' => __( 'Flat', 'neuron-builder' ),
				],
				'prefix_class' => 'a-neuron-share-buttons-skin--',
				'default' => 'gradient',
			]
		);

		$this->add_control(
			'shape',
			[
				'label' => __( 'Shape', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'square' => __( 'Square', 'neuron-builder' ),
					'rounded' => __( 'Rounded', 'neuron-builder' ),
					'circle' => __( 'Circle', 'neuron-builder' ),
				],
				'prefix_class' => 'a-neuron-share-buttons-shape--',
				'default' => 'square',
			]
		);
		
		$this->add_responsive_control(
			'columns',
			[
				'label' => __( 'Columns', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'default' => '0',
				'options' => [
					'0' => __( 'Auto', 'neuron-builder' ),
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'prefix_class' => 'l-neuron-grid-wrapper%s--columns__',
			]
		);

		$this->add_responsive_control(
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
					'justify' => [
						'title' => __( 'justify', 'neuron-builder' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'left',
				'prefix_class' => 'a-neuron-share-buttons-wrapper%s--align-',
				'condition' => [
					'columns' => '0',
				],
			]
		);

		$this->add_control(
			'target_url',
			[
				'label' => __( 'Target URL', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'current-page' => __( 'Current Page', 'neuron-builder' ),
					'custom' => __( 'Custom', 'neuron-builder' ),
				],
				'default' => 'current-page',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'custom_url',
			[
				'label' => __( 'Link', 'neuron-builder' ),
				'type' => Controls_Manager::URL,
				'show_label' => false,
				'placeholder' => __( 'https://neuronthemes.com', 'neuron-builder' ),
				'show_external' => false,
				'condition' => [
					'target_url' => 'custom'
				],
				'frontend_available' => true,
			]
		);

		$this->end_controls_section(); // Share Buttons Section End

		// Share Buttons Style
		$this->start_controls_section(
			'share_buttons_style_section', [
				'label' => __( 'Share Buttons', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_responsive_control(
			'columns_gap',
			[
				'label' => __( 'Columns Gap', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .l-neuron-grid' => 'margin-right: calc(-{{SIZE}}{{UNIT}} / 2); margin-left: calc(-{{SIZE}}{{UNIT}} / 2)',
                	'{{WRAPPER}} .l-neuron-grid__item' => 'padding-right: calc({{SIZE}}{{UNIT}} / 2); padding-left: calc({{SIZE}}{{UNIT}} / 2)',
				],
			]
		);

		$this->add_responsive_control(
			'rows_gap',
			[
				'label' => __( 'Rows Gap', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .l-neuron-grid__item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_height',
			[
				'label' => __( 'Button Height', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .a-neuron-share-buttons__item' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
            'icon_heading',
            [
                'label' => __( 'Icon', 'neuron-builder' ),
                'type' => Controls_Manager::HEADING,
                'condition' => [
					'style' => [ 'icon', 'icon-text' ],
				],
				'separator' => 'before',
            ]
        );

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Size', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'condition' => [
					'style' => [ 'icon', 'icon-text' ],
				],
				'selectors' => [
					'{{WRAPPER}} .a-neuron-share-button__icon' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'icon_width',
			[
				'label' => __( 'Width', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'condition' => [
					'style' => [ 'icon', 'icon-text' ],
				],
				'selectors' => [
					'{{WRAPPER}} .a-neuron-share-button__icon' => 'width: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'icon_height',
			[
				'label' => __( 'Height', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'condition' => [
					'style' => [ 'icon', 'icon-text' ],
				],
				'selectors' => [
					'{{WRAPPER}} .a-neuron-share-button__icon' => 'height: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_control(
			'color', [
				'label' => __( 'Color', 'neuron-builder' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
					'' => __( 'Official Color', 'neuron-builder' ),
					'custom' => __( 'Custom Color', 'neuron-builder' ),
				],
				'default' => '',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'icon_border',
				'label' => __( 'Border', 'neuron-builder' ),
				'selector' => '{{WRAPPER}} .a-neuron-share-button',
				'separator' => 'after',
			]
		);

		// Tabs
		$this->start_controls_tabs(
			'share_buttons_tabs', [
				'condition' => [
					'color' => 'custom'
				]
			]
 		);

		$this->start_controls_tab(
			'share_buttons_normal_tab',
			[
				'label' => __( 'Normal', 'neuron-builder' )
			]
		);

		$this->add_control(
			'primary_color',
			[
				'label' => __( 'Primary Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.a-neuron-share-buttons-skin--flat .a-neuron-share-button,
					 {{WRAPPER}}.a-neuron-share-buttons-skin--gradient .a-neuron-share-button,
					 {{WRAPPER}}.a-neuron-share-buttons-skin--boxed-icon .a-neuron-share-button .a-neuron-share-button__icon,
					 {{WRAPPER}}.a-neuron-share-buttons-skin--minimal .a-neuron-share-button .a-neuron-share-button__icon' => 'background-color: {{VALUE}} !important',
					'{{WRAPPER}}.a-neuron-share-buttons-skin--framed .a-neuron-share-button,
					 {{WRAPPER}}.a-neuron-share-buttons-skin--minimal .a-neuron-share-button,
					 {{WRAPPER}}.a-neuron-share-buttons-skin--boxed-icon .a-neuron-share-button' => 'color: {{VALUE}} !important; border-color: {{VALUE}} !important',
				]
			]
		);

		$this->add_control(
			'secondary_color',
			[
				'label' => __( 'Secondary Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.a-neuron-share-buttons-skin--flat .a-neuron-share-button__icon, 
					 {{WRAPPER}}.a-neuron-share-buttons-skin--flat .a-neuron-share-button__text, 
					 {{WRAPPER}}.a-neuron-share-buttons-skin--gradient .a-neuron-share-button__icon,
					 {{WRAPPER}}.a-neuron-share-buttons-skin--gradient .a-neuron-share-button__text,
					 {{WRAPPER}}.a-neuron-share-buttons-skin--boxed-icon .a-neuron-share-button__icon,
					 {{WRAPPER}}.a-neuron-share-buttons-skin--minimal .a-neuron-share-button__icon' => 'color: {{VALUE}} !important',
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'share_buttons_hover_tab',
			[
				'label' => __( 'Hover', 'neuron-builder' )
			]
		);

		$this->add_control(
			'primary_color_hover',
			[
				'label' => __( 'Primary Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.a-neuron-share-buttons-skin--flat .a-neuron-share-button:hover,
					 {{WRAPPER}}.a-neuron-share-buttons-skin--gradient .a-neuron-share-button:hover' => 'background-color: {{VALUE}} !important',
					'{{WRAPPER}}.a-neuron-share-buttons-skin--framed .a-neuron-share-button:hover,
					 {{WRAPPER}}.a-neuron-share-buttons-skin--minimal .a-neuron-share-button:hover,
					 {{WRAPPER}}.a-neuron-share-buttons-skin--boxed-icon .a-neuron-share-button:hover' => 'color: {{VALUE}} !important; border-color: {{VALUE}} !important',
					'{{WRAPPER}}.a-neuron-share-buttons-skin--boxed-icon .a-neuron-share-button:hover .a-neuron-share-button__icon, 
					 {{WRAPPER}}.a-neuron-share-buttons-skin--minimal .a-neuron-share-button:hover .a-neuron-share-button__icon' => 'background-color: {{VALUE}} !important',
				]
			]
		);

		$this->add_control(
			'secondary_color_hover',
			[
				'label' => __( 'Secondary Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.a-neuron-share-buttons-skin--flat .a-neuron-share-button:hover .a-neuron-share-button__icon, 
					 {{WRAPPER}}.a-neuron-share-buttons-skin--flat .a-neuron-share-button:hover .a-neuron-share-button__text, 
					 {{WRAPPER}}.a-neuron-share-buttons-skin--gradient .a-neuron-share-button:hover .a-neuron-share-button__icon,
					 {{WRAPPER}}.a-neuron-share-buttons-skin--gradient .a-neuron-share-button:hover .a-neuron-share-button__text,
					 {{WRAPPER}}.a-neuron-share-buttons-skin--boxed-icon .a-neuron-share-button:hover .a-neuron-share-button__icon,
					 {{WRAPPER}}.a-neuron-share-buttons-skin--minimal .a-neuron-share-button:hover .a-neuron-share-button__icon' => 'color: {{VALUE}} !important',
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'label' => __( 'Typography', 'neuron-builder' ),
				'selector' => '{{WRAPPER}} .a-neuron-share-button__text',
			]
		);

		$this->end_controls_section(); // Share Buttons Style Section End
	}

	/**
	 * Networks
	 */
	public function get_networks( $network = '' ) {
		$networks = [
			'facebook' => 'Facebook',
			'twitter' => 'Twitter',
			'linkedin' => 'Linkedin',
			'pinterest' => 'Pinterest',
			'reddit' => 'Reddit',
			'vk' => 'VK',
			'odnoklassniki' => 'OK',
			'tumblr' => 'Tumblr',
			'delicious' => 'Delicious',
			'digg' => 'Digg',
			'skype' => 'Skype',
			'stumbleupon' => 'Stumble Upon',
			'telegram' => 'Telegram',
			'pocket' => 'Pocket',
			'xing' => 'XING',
			'whatsapp' => 'WhatsApp',
			'email' => 'Email',
			'print' => 'Print',
		];

		if ( !empty( $network ) ) {
			return $networks[$network];
		}

		return $networks;
	}

	public function different_social_network_classes() {
		$output = [
			'pocket',
			'email',
			'print',
			'facebook',
		];

		return $output;
	}

	public function replace_social_network_class( $network ) {
		$output = [
			'pocket' => 'fab fa-get-pocket',
			'email' => 'fas fa-envelope',
			'print' => 'fas fa-print',
			'facebook' => 'fab fa-facebook-f',
		];

		return $output[$network];
	}

	public function get_social_network_icon( $network ) {
		return '<i class="fab fa-'. $network .'"></i>';
	}

	public function get_social_network( $network ) {
		$icon = '';
		
		if ( in_array( $network, $this->different_social_network_classes() ) ) {
			return '<i class="'. $this->replace_social_network_class( $network ) .'"></i>';
		}

		$icon = $this->get_social_network_icon( $network );

		return $icon;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Post URL
		global $post;
		$post_URL = $post->guid;
		$post_title = $post->post_title;
	?>
		<div class="l-neuron-grid a-neuron-share-buttons">
			<?php foreach ( $settings['share_buttons'] as $button ) : ?>
				<div class="l-neuron-grid__item a-neuron-share-buttons__item">
					<div class="a-neuron-share-button a-neuron-share-button--<?php echo array_search( $this->get_networks( $button['network']), $this->get_networks() ) ?>">
						<?php if ( 'icon' === $settings['style'] || 'icon-text' === $settings['style'] ) : ?>
							<div class="a-neuron-share-button__icon"><?php echo $this->get_social_network( $button['network'] ) ?></div>
						<?php endif; ?>
						<?php if ( ( $settings['style'] === 'text' || $settings['style'] === 'icon-text' ) && $settings['label'] == 'yes' ) : ?>
							<div class="a-neuron-share-button__text"><?php echo $button['custom_label'] ? $button['custom_label'] : $this->get_networks( $button['network'] ) ?></div>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function content_template() {
		?>
		<div class="l-neuron-grid a-neuron-share-buttons">
			<# jQuery.each( settings.share_buttons, function( index, button ) { #>
				<div class="l-neuron-grid__item a-neuron-share-buttons__item">
					<div class="a-neuron-share-button a-neuron-share-button--{{{button.network}}}">
						<# if ( 'icon' == settings.style || 'icon-text' == settings.style ) { #>
							<div class="a-neuron-share-button__icon">
								<i class="{{{ neuron.modules.shareButtons.getNetworkClass(button.network) }}}"></i>
							</div>
						<# } #>
						<# if ( ( settings.style === 'text' || settings.style === 'icon-text' ) && settings.label == 'yes' ) { #>
							<div class="a-neuron-share-button__text">
								<# if ( button.custom_label ) { #>
									{{{ button.custom_label }}}
								<# } else { #>
									{{{ neuron.modules.shareButtons.getNetworkTitle(button) }}}
								<# } #>
							</div>
						<# } #>
					</div>
				</div>
			<# } ); #>
		</div>
		<?php
	}
}
