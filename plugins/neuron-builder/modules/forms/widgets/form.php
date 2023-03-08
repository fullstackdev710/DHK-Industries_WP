<?php
namespace Neuron\Modules\Forms\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

use Neuron\Plugin;
use Neuron\Core\Utils;
use Neuron\Modules\Forms\Classes\Ajax_Handler;
use Neuron\Modules\Forms\Classes\Form_Base;
use Neuron\Modules\Forms\Module;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Form extends Form_Base {

	public function get_name() {
		return 'neuron-form';
	}

	public function get_title() {
		return __( 'Form', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-form-horizontal neuron-badge';
	}

	public function get_keywords() {
		return [ 'form', 'forms', 'field', 'button', 'mailchimp', 'recaptcha' ];
	}

	protected function register_controls() {
		$repeater = new Repeater();

		$field_types = [
			'text' => __( 'Text', 'neuron-builder' ),
			'email' => __( 'Email', 'neuron-builder' ),
			'textarea' => __( 'Textarea', 'neuron-builder' ),
			'url' => __( 'URL', 'neuron-builder' ),
			'tel' => __( 'Tel', 'neuron-builder' ),
			'radio' => __( 'Radio', 'neuron-builder' ),
			'select' => __( 'Select', 'neuron-builder' ),
			'checkbox' => __( 'Checkbox', 'neuron-builder' ),
			'acceptance' => __( 'Acceptance', 'neuron-builder' ),
			'number' => __( 'Number', 'neuron-builder' ),
			'date' => __( 'Date', 'neuron-builder' ),
			'time' => __( 'Time', 'neuron-builder' ),
			'upload' => __( 'File Upload', 'neuron-builder' ),
			'password' => __( 'Password', 'neuron-builder' ),
			'html' => __( 'HTML', 'neuron-builder' ),
			'hidden' => __( 'Hidden', 'neuron-builder' ),
		];

		$field_types = apply_filters( 'neuron/forms/field_types', $field_types );

		$repeater->start_controls_tabs( 'form_fields_tabs' );

		$repeater->start_controls_tab( 'form_fields_content_tab', [
			'label' => __( 'Content', 'neuron-builder' ),
		] );

		$repeater->add_control(
			'field_type',
			[
				'label' => __( 'Type', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => $field_types,
				'default' => 'text',
			]
		);

		$repeater->add_control(
			'field_label',
			[
				'label' => __( 'Label', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
			]
		);

		$repeater->add_control(
			'placeholder',
			[
				'label' => __( 'Placeholder', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => 'in',
							'value' => [
								'tel',
								'text',
								'email',
								'textarea',
								'number',
								'url',
								'password',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'required',
			[
				'label' => __( 'Required', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default' => '',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => '!in',
							'value' => [
								'checkbox',
								'recaptcha',
								'recaptcha_v3',
								'hidden',
								'html',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'field_options',
			[
				'label' => __( 'Options', 'neuron-builder' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => '',
				'description' => __( 'Enter each option in a separate line. To differentiate between label and value, separate them with a pipe char ("|"). For example: First Name|f_name', 'neuron-builder' ),
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => 'in',
							'value' => [
								'select',
								'checkbox',
								'radio',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'allow_multiple',
			[
				'label' => __( 'Multiple Selection', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'select',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'select_size',
			[
				'label' => __( 'Rows', 'neuron-builder' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 2,
				'step' => 1,
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'select',
						],
						[
							'name' => 'allow_multiple',
							'value' => 'true',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'inline_list',
			[
				'label' => __( 'Inline List', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'm-neuron-form__subgroup--inline',
				'default' => '',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => 'in',
							'value' => [
								'checkbox',
								'radio',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'field_html',
			[
				'label' => __( 'HTML', 'neuron-builder' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'html',
						],
					],
				],
			]
		);

		$repeater->add_responsive_control(
			'width',
			[
				'label' => __( 'Column Width', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Default', 'neuron-builder' ),
					'100' => '100%',
					'80' => '80%',
					'75' => '75%',
					'66' => '66%',
					'60' => '60%',
					'50' => '50%',
					'40' => '40%',
					'33' => '33%',
					'25' => '25%',
					'20' => '20%',
				],
				'default' => '100',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'max-width: {{VALUE}}%'
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => '!in',
							'value' => [
								'hidden',
								'recaptcha',
								'recaptcha_v3',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'rows',
			[
				'label' => __( 'Rows', 'neuron-builder' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 4,
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'textarea',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'recaptcha_size', [
				'label' => __( 'Size', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'normal' => __( 'Normal', 'neuron-builder' ),
					'compact' => __( 'Compact', 'neuron-builder' ),
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'recaptcha',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'recaptcha_style',
			[
				'label' => __( 'Style', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'light',
				'options' => [
					'light' => __( 'Light', 'neuron-builder' ),
					'dark' => __( 'Dark', 'neuron-builder' ),
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'recaptcha',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'recaptcha_badge', [
				'label' => __( 'Badge', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'bottomright',
				'options' => [
					'bottomright' => __( 'Bottom Right', 'neuron-builder' ),
					'bottomleft' => __( 'Bottom Left', 'neuron-builder' ),
					'inline' => __( 'Inline', 'neuron-builder' ),
				],
				'description' => __( 'To view the validation badge, switch to preview mode', 'neuron-builder' ),
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'recaptcha_v3',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'css_classes',
			[
				'label' => __( 'CSS Classes', 'neuron-builder' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => '',
				'title' => __( 'Add your custom class WITHOUT the dot. e.g: my-class', 'neuron-builder' ),
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'form_fields_advanced_tab',
			[
				'label' => __( 'Advanced', 'neuron-builder' ),
				'condition' => [
					'field_type!' => 'html',
				],
			]
		);

		$repeater->add_control(
			'field_value',
			[
				'label' => __( 'Default Value', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => [
					'active' => true,
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => 'in',
							'value' => [
								'text',
								'email',
								'textarea',
								'url',
								'tel',
								'radio',
								'select',
								'number',
								'date',
								'time',
								'hidden',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'custom_id',
			[
				'label' => __( 'ID', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'description' => __( 'Please make sure the ID is unique and not used elsewhere in this form. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'neuron-builder' ),
				'render_type' => 'none',
			]
		);

		$shortcode_template = '{{ view.container.settings.get( \'custom_id\' ) }}';

		$repeater->add_control(
			'shortcode',
			[
				'label' => __( 'Shortcode', 'neuron-builder' ),
				'type' => Controls_Manager::RAW_HTML,
				'classes' => 'forms-field-shortcode',
				'raw' => '<input class="elementor-form-field-shortcode" value=\'[field id="' . $shortcode_template . '"]\' readonly />',
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->start_controls_section(
			'section_form_fields',
			[
				'label' => __( 'Form Fields', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'form_name',
			[
				'label' => __( 'Form Name', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'New Form', 'neuron-builder' ),
				'placeholder' => __( 'Form Name', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'form_fields',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'custom_id' => 'name',
						'field_type' => 'text',
						'field_label' => __( 'Name', 'neuron-builder' ),
						'placeholder' => __( 'Name', 'neuron-builder' ),
						'width' => '100',
					],
					[
						'custom_id' => 'email',
						'field_type' => 'email',
						'required' => 'true',
						'field_label' => __( 'Email', 'neuron-builder' ),
						'placeholder' => __( 'Email', 'neuron-builder' ),
						'width' => '100',
					],
					[
						'custom_id' => 'message',
						'field_type' => 'textarea',
						'field_label' => __( 'Message', 'neuron-builder' ),
						'placeholder' => __( 'Message', 'neuron-builder' ),
						'width' => '100',
					],
				],
				'title_field' => '{{{ field_label }}}',
			]
		);

		$this->add_control(
			'input_size',
			[
				'label' => __( 'Input Size', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'xs' => __( 'Extra Small', 'neuron-builder' ),
					'sm' => __( 'Small', 'neuron-builder' ),
					'md' => __( 'Medium', 'neuron-builder' ),
					'lg' => __( 'Large', 'neuron-builder' ),
					'xl' => __( 'Extra Large', 'neuron-builder' ),
				],
				'default' => 'sm',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'show_labels',
			[
				'label' => __( 'Label', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'neuron-builder' ),
				'label_off' => __( 'Hide', 'neuron-builder' ),
				'return_value' => 'true',
				'default' => 'true',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'mark_required',
			[
				'label' => __( 'Required Mark', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'neuron-builder' ),
				'label_off' => __( 'Hide', 'neuron-builder' ),
				'default' => '',
				'condition' => [
					'show_labels!' => '',
				],
			]
		);

		$this->add_control(
			'label_position',
			[
				'label' => __( 'Label Position', 'neuron-builder' ),
				'type' => Controls_Manager::HIDDEN,
				'options' => [
					'above' => __( 'Above', 'neuron-builder' ),
					'inline' => __( 'Inline', 'neuron-builder' ),
				],
				'default' => 'above',
				'condition' => [
					'show_labels!' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_submit_button',
			[
				'label' => __( 'Submit Button', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => __( 'Text', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Send', 'neuron-builder' ),
				'placeholder' => __( 'Send', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'button_size',
			[
				'label' => __( 'Size', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'sm',
				'options' => self::get_button_sizes(),
			]
		);

		$this->add_responsive_control(
			'button_width',
			[
				'label' => __( 'Column Width', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Default', 'neuron-builder' ),
					'100' => '100%',
					'80' => '80%',
					'75' => '75%',
					'66' => '66%',
					'60' => '60%',
					'50' => '50%',
					'40' => '40%',
					'33' => '33%',
					'25' => '25%',
					'20' => '20%',
				],
				'condition' => [
					'button_align!' => 'stretch'
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-form__button' => 'width: {{VALUE}}%'
				],
				'default' => '100',
			]
		);

		$this->add_responsive_control(
			'button_container_width',
			[
				'label' => __( 'Button Width', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'rem' ],
				'default' => [
					'size' => 100,
					'unit' => '%'
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-form__button button' => 'max-width: {{SIZE}}{{UNIT}}'
				],
			]
		);

		$this->add_responsive_control(
			'button_align',
			[
				'label' => __( 'Alignment', 'neuron-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => __( 'Left', 'neuron-builder' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'neuron-builder' ),
						'icon' => 'eicon-h-align-center',
					],
					'end' => [
						'title' => __( 'Right', 'neuron-builder' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'selectors_dictionary' => [
					'start' => 'margin-right: auto; margin-left: 0;',
					'center' => 'margin-right: auto; margin-left: auto;',
					'end' => 'margin-left: auto; margin-right: 0;',
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-form__button button' => '{{VALUE}}'
				]
			]
		);

		$this->add_control(
			'selected_button_icon',
			[
				'label' => __( 'Icon', 'neuron-builder' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'button_icon',
				'label_block' => true,
			]
		);

		$this->add_control(
			'button_icon_align',
			[
				'label' => __( 'Icon Position', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => __( 'Before', 'neuron-builder' ),
					'right' => __( 'After', 'neuron-builder' ),
				],
				'condition' => [
					'selected_button_icon[value]!' => '',
				],
			]
		);

		$this->add_control(
			'button_icon_indent',
			[
				'label' => __( 'Icon Spacing', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'condition' => [
					'selected_button_icon[value]!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-form__button .m-neuron-form__button-icon--right > .m-neuron-form__button-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .m-neuron-form__button .m-neuron-form__button-icon--left > .m-neuron-form__button-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_css_id',
			[
				'label' => __( 'Button ID', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'title' => __( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'neuron-builder' ),
				'label_block' => false,
				'description' => __( 'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'neuron-builder' ),
				'separator' => 'before',

			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_integration',
			[
				'label' => __( 'Actions After Submit', 'neuron-builder' ),
			]
		);

		$actions = Module::instance()->get_form_actions();

		$actions_options = [];

		foreach ( $actions as $action ) {
			$actions_options[ $action->get_name() ] = $action->get_label();
		}

		$this->add_control(
			'submit_actions',
			[
				'label' => __( 'Add Action', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $actions_options,
				'render_type' => 'none',
				'label_block' => true,
				'default' => [
					'email',
				],
				'description' => __( 'Add actions that will be performed after a visitor submits the form (e.g. send an email notification). Choosing an action will add its setting below.', 'neuron-builder' ),
			]
		);

		$this->end_controls_section();

		foreach ( $actions as $action ) {
			$action->register_settings_section( $this );
		}

		$this->start_controls_section(
			'section_form_options',
			[
				'label' => __( 'Additional Options', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'form_id',
			[
				'label' => __( 'Form ID', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => 'new_form_id',
				'description' => __( 'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'neuron-builder' ),
				'separator' => 'after',
			]
		);

		$this->add_control(
			'custom_messages',
			[
				'label' => __( 'Custom Messages', 'neuron-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'separator' => 'before',
				'render_type' => 'none',
			]
		);

		$default_messages = Ajax_Handler::get_default_messages();

		$this->add_control(
			'success_message',
			[
				'label' => __( 'Success Message', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'default' => $default_messages[ Ajax_Handler::SUCCESS ],
				'placeholder' => $default_messages[ Ajax_Handler::SUCCESS ],
				'label_block' => true,
				'condition' => [
					'custom_messages!' => '',
				],
				'render_type' => 'none',
			]
		);

		$this->add_control(
			'error_message',
			[
				'label' => __( 'Error Message', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'default' => $default_messages[ Ajax_Handler::ERROR ],
				'placeholder' => $default_messages[ Ajax_Handler::ERROR ],
				'label_block' => true,
				'condition' => [
					'custom_messages!' => '',
				],
				'render_type' => 'none',
			]
		);

		$this->add_control(
			'required_field_message',
			[
				'label' => __( 'Required Message', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'default' => $default_messages[ Ajax_Handler::FIELD_REQUIRED ],
				'placeholder' => $default_messages[ Ajax_Handler::FIELD_REQUIRED ],
				'label_block' => true,
				'condition' => [
					'custom_messages!' => '',
				],
				'render_type' => 'none',
			]
		);

		$this->add_control(
			'invalid_message',
			[
				'label' => __( 'Invalid Message', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'default' => $default_messages[ Ajax_Handler::INVALID_FORM ],
				'placeholder' => $default_messages[ Ajax_Handler::INVALID_FORM ],
				'label_block' => true,
				'condition' => [
					'custom_messages!' => '',
				],
				'render_type' => 'none',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_form_style',
			[
				'label' => __( 'Form', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'column_gap',
			[
				'label' => __( 'Columns Gap', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-form__field-group' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .m-neuron-form' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
				],
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label' => __( 'Rows Gap', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-form__field-group' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .m-neuron-form__field-group.recaptcha_v3-bottomleft, {{WRAPPER}} .m-neuron-form__field-group.recaptcha_v3-bottomright' => 'margin-bottom: 0;',
					'{{WRAPPER}} .m-neuron-form' => 'margin-bottom: -{{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_label',
			[
				'label' => __( 'Label', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'label_spacing',
			[
				'label' => __( 'Spacing', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-form__field-group > label' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'label_color',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-form__field-group > label, {{WRAPPER}} .m-neuron-form__subgroup label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'mark_required_color',
			[
				'label' => __( 'Mark Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .m-neuron-form__field-group--mark-required .m-neuron-form__label:after' => 'color: {{COLOR}};',
				],
				'condition' => [
					'mark_required' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .m-neuron-form__field-group > label',
			]
		);

		$this->add_control(
			'heading_html',
			[
				'label' => __( 'HTML Field', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'html_spacing',
			[
				'label' => __( 'Spacing', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-form__field--html' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'html_color',
			[
				'label' => __( 'Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-form__field--html' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'html_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .m-neuron-form__field--html',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_field_style',
			[
				'label' => __( 'Field', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'field_text_color',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-form__field-group .m-neuron-form__field' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'field_placeholder_color',
			[
				'label' => __( 'Placeholder Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-form__field-group .m-neuron-form__field::placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .m-neuron-form__field-group .m-neuron-form__field:-ms-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .m-neuron-form__field-group .m-neuron-form__field::-ms-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .m-neuron-form__field-group .m-neuron-form__field::-moz-placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'field_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .m-neuron-form__field-group .m-neuron-form__field, {{WRAPPER}} .m-neuron-form__subgroup label',
			]
		);

		$this->add_control(
			'field_background_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-form__field-group:not(.m-neuron-form__field--upload) .m-neuron-form__field:not(.m-neuron-form__subgroup)' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .m-neuron-form__field-group .m-neuron-form__subgroup select' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'field_border_color',
			[
				'label' => __( 'Border Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-form__field-group:not(.m-neuron-form__field--upload) .m-neuron-form__field:not(.m-neuron-form__subgroup)' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .m-neuron-form__field-group .m-neuron-form__subgroup select' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .m-neuron-form__field-group .m-neuron-form__subgroup::before' => 'color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'field_border_width',
			[
				'label' => __( 'Border Width', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'placeholder' => '1',
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-form__field-group:not(.m-neuron-form__field--upload) .m-neuron-form__field:not(.m-neuron-form__subgroup)' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .m-neuron-form__field-group .m-neuron-form__subgroup select' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'field_border_radius',
			[
				'label' => __( 'Border Radius', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-form__field-group:not(.m-neuron-form__field--upload) .m-neuron-form__field:not(.m-neuron-form__subgroup)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; -webkit-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; -moz-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .m-neuron-form__field-group .m-neuron-form__subgroup select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'field_padding',
			[
				'label' => __( 'Padding', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-form__field-group:not(.m-neuron-form__field--upload) .m-neuron-form__field:not(.m-neuron-form__subgroup)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .m-neuron-form__field-group .m-neuron-form__subgroup select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_select',
			[
				'label' => __( 'Select', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'select_text_color',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .m-neuron-form__field select' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'select_typography',
				'selector' => '{{WRAPPER}} .m-neuron-form__field select'
			]
		);

		$this->add_control(
			'heading_radio',
			[
				'label' => __( 'Radio', 'neuron-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'radio_color',
			[
				'label' => __( 'Radio Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .m-neuron-form__option input[type="radio"]:after' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .m-neuron-form__option input[type="radio"]:before' => 'background-color: {{VALUE}}'
				],
			]
		);

		$this->add_responsive_control(
			'radio_spacing',
			[
				'label' => __( 'Spacing', 'neuron-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-form__option input[type="radio"]' => 'margin-right: {{SIZE}}{{UNIT}}'
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_style',
			[
				'label' => __( 'Button', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'button_v_align',
			[
				'label' => __( 'Vertical Alignment', 'neuron-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => __( 'Top', 'neuron-builder' ),
						'icon' => 'eicon-v-align-top',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'neuron-builder' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'condition' => [
					'button_width!' => '100',
					'button_align!' => 'stretch'
				],
				'selectors_dictionary' => [
					'top' => 'margin-bottom: auto !important; margin-top: unset !important;',
					'bottom' => 'margin-top: auto !important;',
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-form__button' => '{{VALUE}}'
				]
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-form__button button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .m-neuron-form__button button' => 'color: {{VALUE}};',
					'{{WRAPPER}} .m-neuron-form__button button svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .m-neuron-form__button button',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .m-neuron-form__button button',
			]
		);

		$this->add_responsive_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-form__button button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_text_padding',
			[
				'label' => __( 'Text Padding', 'neuron-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .m-neuron-form__button button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'neuron-builder' ),
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label' => __( 'Background Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-form__button button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => __( 'Text Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-form__button button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => __( 'Border Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .m-neuron-form__button button:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'button_border_border!' => '',
				],
			]
		);

		$this->add_control(
			'button_hover_animation',
			[
				'label' => __( 'Animation', 'neuron-builder' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_messages_style',
			[
				'label' => __( 'Messages', 'neuron-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'message_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .elementor-message',
			]
		);

		$this->add_control(
			'success_message_color',
			[
				'label' => __( 'Success Message Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-message.elementor-message-success' => 'color: {{COLOR}};',
				],
			]
		);

		$this->add_control(
			'error_message_color',
			[
				'label' => __( 'Error Message Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-message.elementor-message-danger' => 'color: {{COLOR}};',
				],
			]
		);

		$this->add_control(
			'inline_message_color',
			[
				'label' => __( 'Inline Message Color', 'neuron-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-message.elementor-help-inline' => 'color: {{COLOR}};',
				],
			]
		);

		$this->end_controls_section();

	}

	private function render_icon_with_fallback( $settings ) {
		$migrated = isset( $settings['__fa4_migrated']['selected_button_icon'] );
		$is_new = empty( $settings['button_icon'] ) && Icons_Manager::is_migration_allowed();

		if ( $is_new || $migrated ) {
			Icons_Manager::render_icon( $settings['selected_button_icon'], [ 'aria-hidden' => 'true' ] );
		} else {
			?><i class="<?php echo esc_attr( $settings['button_icon'] ); ?>" aria-hidden="true"></i><?php
		}
	}

	protected function render() {
		$instance = $this->get_settings_for_display();

		if ( ! Plugin::elementor()->editor->is_edit_mode() ) {
			/**
			 * Elementor form Pre render.
			 *
			 * Fires before the from is rendered in the frontend
			 *
			 * @since 1.0.0
			 *
			 * @param array $instance current form settings
			 * @param Form $this current form widget instance
			 */
			do_action( 'neuron/forms/pre_render', $instance, $this );
		}

		$this->add_render_attribute(
			[
				'submit-group' => [
					'class' => [
						'm-neuron-form__field-group',
						'm-neuron-form__button',						
					],
				],
				'button' => [
					'class' => [
						'm-neuron-form__submit-button',
						'a-neuron-button',
						'h-neuron-size--' . $instance['button_size']
					],
				],
				'icon-align' => [
					'class' => [
						'm-neuron-form__button-icon',
					],
				],
			]
		);

		if ( $instance['button_hover_animation'] ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $instance['button_hover_animation'] );
		}

		if ( ! empty( $instance['form_id'] ) ) {
			$this->add_render_attribute( 'form', 'id', $instance['form_id'] );
		}

		if ( ! empty( $instance['form_name'] ) ) {
			$this->add_render_attribute( 'form', 'name', $instance['form_name'] );
		}

		if ( ! empty( $instance['button_css_id'] ) ) {
			$this->add_render_attribute( 'button', 'id', $instance['button_css_id'] );
		}

		if ( ! empty( $instance['button_icon_align'] ) ) {
			$this->add_render_attribute( 'icon-holder', 'class', 'm-neuron-form__button-icon--' . $instance['button_icon_align'] );
		}

		?>
		<form class="m-neuron-form" method="post" <?php echo $this->get_render_attribute_string( 'form' ); ?>>
			<input type="hidden" name="post_id" value="<?php echo Utils::get_current_post_id(); ?>"/>
			<input type="hidden" name="form_id" value="<?php echo $this->get_id(); ?>"/>

			<?php
			foreach ( $instance['form_fields'] as $item_index => $item ) :
				$item['input_size'] = $instance['input_size'];
				$this->form_fields_render_attributes( $item_index, $instance, $item );

				$field_type = $item['field_type'];

				/**
				 * Render form field.
				 *
				 * Filters the field rendered by Elementor Forms.
				 *
				 * @since 1.0.0
				 *
				 * @param array $item       The field value.
				 * @param int   $item_index The field index.
				 * @param Form  $this       An instance of the form.
				 */
				$item = apply_filters( 'neuron/forms/render/item', $item, $item_index, $this );

				/**
				 * Render form field.
				 *
				 * Filters the field rendered by Elementor Forms.
				 *
				 * The dynamic portion of the hook name, `$field_type`, refers to the field type.
				 *
				 * @since 1.0.0
				 *
				 * @param array $item       The field value.
				 * @param int   $item_index The field index.
				 * @param Form  $this       An instance of the form.
				 */
				$item = apply_filters( "neuron/forms/render/item/{$field_type}", $item, $item_index, $this );

				if ( 'hidden' === $item['field_type'] ) {
					$item['field_label'] = false;
				}
				?>
			<div <?php echo $this->get_render_attribute_string( 'field-group' . $item_index ); ?>>
				<?php
				if ( $item['field_label'] && 'html' !== $item['field_type'] ) {
					echo '<label ' . $this->get_render_attribute_string( 'label' . $item_index ) . '>' . $item['field_label'] . '</label>';
				}

				switch ( $item['field_type'] ) :
					case 'html':
						echo do_shortcode( $item['field_html'] );
						break;
					case 'textarea':
						echo $this->make_textarea_field( $item, $item_index );
						break;

					case 'select':
						echo $this->make_select_field( $item, $item_index );
						break;

					case 'radio':
					case 'checkbox':
						echo $this->make_radio_checkbox_field( $item, $item_index, $item['field_type'] );
						break;
					case 'text':
					case 'email':
					case 'url':
					case 'password':
					case 'hidden':
					case 'search':
						$this->add_render_attribute( 'input' . $item_index, 'class', 'm-neuron-form__input' );
						echo '<input size="1" ' . $this->get_render_attribute_string( 'input' . $item_index ) . '>';
						break;
					default:
						$field_type = $item['field_type'];

						do_action( "neuron/forms/render_field/{$field_type}", $item, $item_index, $this );
				endswitch;
				?>
			</div>
			<?php endforeach; ?>
			<div <?php echo $this->get_render_attribute_string( 'submit-group' ); ?>>
				<button type="submit" <?php echo $this->get_render_attribute_string( 'button' ); ?>>
					<span <?php echo $this->get_render_attribute_string( 'icon-holder' ); ?>>
						<?php if ( ! empty( $instance['button_icon'] ) || ! empty( $instance['selected_button_icon'] ) ) : ?>
							<span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
								<?php $this->render_icon_with_fallback( $instance ); ?>
								<?php if ( empty( $instance['button_text'] ) ) : ?>
									<span class="elementor-screen-only"><?php _e( 'Submit', 'neuron-builder' ); ?></span>
								<?php endif; ?>
							</span>
						<?php endif; ?>
						<?php if ( ! empty( $instance['button_text'] ) ) : ?>
							<span class="m-neuron-form__button-text"><?php echo $instance['button_text']; ?></span>
						<?php endif; ?>
					</span>
				</button>
			</div>
		</form>
		<?php
	}

	protected function content_template() {
		$submit_text = esc_html__( 'Submit', 'neuron-builder' );
		?>
		<form class="m-neuron-form" id="{{settings.form_id}}" name="{{settings.form_name}}">
			<#
				for ( var i in settings.form_fields ) {
					var item = settings.form_fields[ i ];
					item = elementor.hooks.applyFilters( 'neuron/forms/content_template/item', item, i, settings );

					var options = item.field_options ? item.field_options.split( '\n' ) : [],
						itemClasses = _.escape( item.css_classes ),
						labelVisibility = '',
						placeholder = '',
						required = '',
						inputField = '',
						multiple = '',
						fieldGroupClasses = 'm-neuron-form__field-group m-neuron-form__field-group--' + item.field_type + ' elementor-repeater-item-' + item._id + '';

					if ( ! settings.show_labels ) {
						item.field_label = false;
					}

					if ( item.required ) {
						required = 'required';
						fieldGroupClasses += ' m-neuron-form__field-group--required';

						if ( settings.mark_required ) {
							fieldGroupClasses += ' m-neuron-form__field-group--mark-required';
						}
					}

					if ( item.placeholder ) {
						placeholder = 'placeholder="' + _.escape( item.placeholder ) + '"';
					}

					if ( item.allow_multiple ) {
						multiple = ' multiple';
						fieldGroupClasses += ' m-neuron-form__field-group--' + item.field_type + '-multiple';
					}

					switch ( item.field_type ) {
						case 'html':
							item.field_label = false;
							inputField = item.field_html;
							break;

						case 'textarea':
							inputField = '<textarea class="m-neuron-form__field h-neuron-size--' + settings.input_size + ' ' + itemClasses + '" name="form_field_' + i + '" id="form_field_' + i + '" rows="' + item.rows + '" ' + required + ' ' + placeholder + '>' + item.field_value + '</textarea>';
							break;

						case 'select':
							if ( options ) {
								var size = '';
								if ( item.allow_multiple && item.select_size ) {
									size = ' size="' + item.select_size + '"';
								}
								inputField = '<div class="m-neuron-form__field m-neuron-form__subgroup ' + itemClasses + '">';
								inputField += '<select class="h-neuron-size--' + settings.input_size + '" name="form_field_' + i + '" id="form_field_' + i + '" ' + required + multiple + size + ' >';
								for ( var x in options ) {
									var option_value = options[ x ];
									var option_label = options[ x ];
									var option_id = 'form_field_option' + i + x;

									if ( options[ x ].indexOf( '|' ) > -1 ) {
										var label_value = options[ x ].split( '|' );
										option_label = label_value[0];
										option_value = label_value[1];
									}

									view.addRenderAttribute( option_id, 'value', option_value );
									if ( item.field_value.split( ',' ) .indexOf( option_value ) ) {
										view.addRenderAttribute( option_id, 'selected', 'selected' );
									}
									inputField += '<option ' + view.getRenderAttributeString( option_id ) + '>' + option_label + '</option>';
								}
								inputField += '</select></div>';
							}
							break;

						case 'radio':
						case 'checkbox':
							if ( options ) {
								var multiple = '';

								if ( 'checkbox' === item.field_type && options.length > 1 ) {
									multiple = '[]';
								}

								inputField = '<div class="m-neuron-form__subgroup ' + itemClasses + ' ' + item.inline_list + '">';

								for ( var x in options ) {
									var option_value = options[ x ];
									var option_label = options[ x ];
									var option_id = 'form_field_' + item.field_type + i + x;
									if ( options[x].indexOf( '|' ) > -1 ) {
										var label_value = options[x].split( '|' );
										option_label = label_value[0];
										option_value = label_value[1];
									}

									view.addRenderAttribute( option_id, {
										value: option_value,
										type: item.field_type,
										id: 'form_field_' + i + '-' + x,
										name: 'form_field_' + i + multiple
									} );

									if ( option_value ===  item.field_value ) {
										view.addRenderAttribute( option_id, 'checked', 'checked' );
									}

									inputField += '<span class="m-neuron-form__option"><input ' + view.getRenderAttributeString( option_id ) + ' ' + required + '> ';
									inputField += '<label for="form_field_' + i + '-' + x + '">' + option_label + '</label></span>';

								}

								inputField += '</div>';
							}
							break;

						case 'text':
						case 'email':
						case 'url':
						case 'password':
						case 'number':
						case 'search':
							inputField = '<input size="1" type="' + item.field_type + '" value="' + item.field_value + '" class="m-neuron-form__field h-neuron-size--' + settings.input_size + ' ' + itemClasses + '" name="form_field_' + i + '" id="form_field_' + i + '" ' + required + ' ' + placeholder + ' >';
							break;
						default:
							inputField = elementor.hooks.applyFilters( 'neuron/forms/content_template/field/' + item.field_type, '', item, i, settings );
					}

					if ( inputField ) {
						#>
						<div class="{{ fieldGroupClasses }}">

							<# if ( item.field_label ) { #>
								<label class="m-neuron-form__label" for="form_field_{{ i }}" {{{ labelVisibility }}}>{{{ item.field_label }}}</label>
							<# } #>

							{{{ inputField }}}
						</div>
						<#
					}
				}


				var buttonClasses = 'm-neuron-form__field-group m-neuron-form__button';

				var iconHTML = elementor.helpers.renderIcon( view, settings.selected_button_icon, { 'aria-hidden': true }, 'i' , 'object' ),
					migrated = elementor.helpers.isIconMigrated( settings, 'selected_button_icon' );

				#>

				<div class="{{ buttonClasses }}">
					<button id="{{ settings.button_css_id }}" type="submit" class="m-neuron-form__submit-button elementor-animation-{{ settings.button_hover_animation }} a-neuron-button h-neuron-size--{{ settings.button_size }}">
						<span class="m-neuron-form__button-icon--{{ settings.button_icon_align }}">
							<# if ( settings.button_icon || settings.selected_button_icon ) { #>
								<span class="m-neuron-form__button-icon">
									<# if ( iconHTML && iconHTML.rendered && ( ! settings.button_icon || migrated ) ) { #>
										{{{ iconHTML.value }}}
									<# } else { #>
										<i class="{{ settings.button_icon }}" aria-hidden="true"></i>
									<# } #>
									<span class="elementor-screen-only"><?php echo $submit_text; ?></span>
								</span>
							<# } #>

							<# if ( settings.button_text ) { #>
								<span class="m-neuron-form__button-text">{{{ settings.button_text }}}</span>
							<# } #>
						</span>
					</button>
				</div>
		</form>
		<?php
	}
}
