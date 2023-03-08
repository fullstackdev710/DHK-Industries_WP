<?php
namespace Neuron\Modules\Forms;

use Elementor\Core\Common\Modules\Ajax\Module as Ajax;
use Neuron\Base\Module_Base;
use Neuron\Modules\Forms\Actions;
use Neuron\Modules\Forms\Classes;
use Neuron\Modules\Forms\Fields;
use Neuron\Modules\Forms\Controls\Fields_Map;
use Neuron\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Module extends Module_Base {
	
	private $form_actions = [];
	
	public $field_types = [];

	public function get_name() {
		return 'forms';
	}

	public function get_widgets() {
		return [
			'Form',
			'Login',
		];
	}

	public function localize_settings( $settings ) {
		$settings = array_replace_recursive( $settings, [
			'i18n' => [
				'x_field' => __( '%s Field', 'neuron-builder' ),
			],
		] );

		return $settings;
	}

	public static function find_element_recursive( $elements, $form_id ) {
		foreach ( $elements as $element ) {
			if ( $form_id === $element['id'] ) {
				return $element;
			}

			if ( ! empty( $element['elements'] ) ) {
				$element = self::find_element_recursive( $element['elements'], $form_id );

				if ( $element ) {
					return $element;
				}
			}
		}

		return false;
	}

	public function register_controls() {
		$controls_manager = Plugin::elementor()->controls_manager;

		$controls_manager->register_control( Fields_Map::CONTROL_TYPE, new Fields_Map() );
	}

	public function forms_panel_action_data( array $data ) {
		if ( empty( $data['service'] ) ) {
			throw new \Exception( 'service_required' );
		}

		$integration = $this->get_form_actions( $data['service'] );

		if ( ! $integration ) {
			throw new \Exception( 'action_not_found' );
		}

		return $integration->handle_panel_request( $data );
	}

	public function add_form_field_type( $type, $instance ) {
		$this->field_types[ $type ] = $instance;
	}

	public function add_form_action( $id, $instance ) {
		$this->form_actions[ $id ] = $instance;
	}

	public function get_form_actions( $id = null ) {
		if ( $id ) {
			if ( ! isset( $this->form_actions[ $id ] ) ) {
				return null;
			}

			return $this->form_actions[ $id ];
		}

		return $this->form_actions;
	}

	public function register_ajax_actions( Ajax $ajax ) {
		$ajax->register_ajax_action( 'neuron_forms_panel_action_data', [ $this, 'forms_panel_action_data' ] );
	}

	public function __construct() {
		parent::__construct();

		add_filter( 'neuron/editor/localize_settings', [ $this, 'localize_settings' ] );
		add_action( 'elementor/controls/controls_registered', [ $this, 'register_controls' ] );
		add_action( 'elementor/ajax/register_actions', [ $this, 'register_ajax_actions' ] );

		//fields
		$this->add_form_field_type( 'time', new Fields\Time() );
		$this->add_form_field_type( 'date', new Fields\Date() );
		$this->add_form_field_type( 'tel', new Fields\Tel() );
		$this->add_form_field_type( 'number', new Fields\Number() );
		$this->add_form_field_type( 'acceptance', new Fields\Acceptance() );
		$this->add_form_field_type( 'upload', new Fields\Upload() );

		$this->add_component( 'recaptcha', new Classes\Recaptcha_Handler() );
		$this->add_component( 'recaptcha_v3', new Classes\Recaptcha_V3_Handler() );

		// Actions Handlers
		$this->add_form_action( 'email', new Actions\Email() );
		$this->add_form_action( 'email2', new Actions\Email2() );
		$this->add_form_action( 'redirect', new Actions\Redirect() );
		$this->add_form_action( 'mailchimp', new Actions\Mailchimp() );

		// Ajax Handler
		if ( Classes\Ajax_Handler::is_form_submitted() ) {
			$this->add_component( 'ajax_handler', new Classes\Ajax_Handler() );

			/**
			 * Elementor form submitted.
			 *
			 * Fires when the form is submitted.
			 *
			 * @since 1.0.0
			 *
			 * @param Module $this An instance of the form module.
			 */
			do_action( 'neuron/forms/form_submitted', $this );
		}
	}
}
