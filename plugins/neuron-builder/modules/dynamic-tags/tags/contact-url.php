<?php
/**
 * Contact Url
 * 
 * Add action linking to 
 * external contact messengers.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\DynamicTags\Tags;

use Neuron\Modules\DynamicTags\Tags\Base\Tag;
use Neuron\Modules\DynamicTags\Module;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Contact_URL extends Tag {

	public function get_name() {
		return 'contact-url';
	}

	public function get_title() {
		return __( 'Contact URL', 'neuron-builder' );
	}

	public function get_group() {
		return Module::ACTION_GROUP;
	}

	public function get_categories() {
		return [ Module::URL_CATEGORY ];
	}

	protected function register_controls() {
		$this->add_control(
			'link_type',
			[
				'label' => __( 'Type', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => '— ' . __( 'Select', 'neuron-builder' ) . ' —',
					'email' => __( 'Email', 'neuron-builder' ),
					'tel' => __( 'Tel', 'neuron-builder' ),
					'sms' => __( 'SMS', 'neuron-builder' ),
					'whatsapp' => __( 'WhatsApp', 'neuron-builder' ),
					'skype' => __( 'Skype', 'neuron-builder' ),
					'messenger' => __( 'Messenger', 'neuron-builder' ),
					'viber' => __( 'Viber', 'neuron-builder' ),
					'waze' => __( 'Waze', 'neuron-builder' ),
				],
			]
		);

		$this->add_control(
			'mail_to',
			[
				'label' => __( 'Email', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'link_type' => 'email',
				],
			]
		);

		$this->add_control(
			'mail_subject',
			[
				'label' => __( 'Subject', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => 'true',
				'condition' => [
					'link_type' => 'email',
				],
			]
		);

		$this->add_control(
			'mail_body',
			[
				'label' => __( 'Message', 'neuron-builder' ),
				'type' => Controls_Manager::TEXTAREA,
				'condition' => [
					'link_type' => 'email',
				],
			]
		);

		$this->add_control(
			'tel_number',
			[
				'label' => __( 'Number', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'link_type' => [
						'tel',
						'sms',
						'whatsapp',
						'viber',
					],
				],
			]
		);

		$this->add_control(
			'username',
			[
				'label' => __( 'Username', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'link_type' => [ 'skype', 'messenger' ],
				],
			]
		);

		$this->add_control(
			'viber_action',
			[
				'label' => __( 'Action', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'contact' => __( 'Contact', 'neuron-builder' ),
					'add' => __( 'Add', 'neuron-builder' ),
				],
				'default' => 'contact',
				'condition' => [
					'link_type' => 'viber',
				],
			]
		);

		$this->add_control(
			'skype_action',
			[
				'label' => __( 'Action', 'neuron-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'call' => __( 'Call', 'neuron-builder' ),
					'chat' => __( 'Chat', 'neuron-builder' ),
					'userinfo' => __( 'Show Profile', 'neuron-builder' ),
					'add' => __( 'Add to Contacts', 'neuron-builder' ),
					'voicemail' => __( 'Send Voice Mail', 'neuron-builder' ),
				],
				'default' => 'call',
				'condition' => [
					'link_type' => 'skype',
				],
			]
		);

		$this->add_control(
			'waze_address',
			[
				'label' => __( 'Location', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => 'true',
				'condition' => [
					'link_type' => 'waze',
				],
			]
		);

		$this->add_control(
			'event_title',
			[
				'label' => __( 'Title', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => 'true',
				'condition' => [
					'link_type' => [
						'google_calendar',
						'outlook_calendar',
						'yahoo_calendar',
					],
				],
			]
		);

		$this->add_control(
			'event_description',
			[
				'label' => __( 'Description', 'neuron-builder' ),
				'type' => Controls_Manager::TEXTAREA,
				'condition' => [
					'link_type' => [
						'google_calendar',
						'outlook_calendar',
						'yahoo_calendar',
					],
				],
			]
		);

		$this->add_control(
			'event_location',
			[
				'label' => __( 'Location', 'neuron-builder' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => 'true',
				'condition' => [
					'link_type' => [
						'google_calendar',
						'outlook_calendar',
						'yahoo_calendar',
					],
				],
			]
		);

		$this->add_control(
			'event_start_date',
			[
				'label' => __( 'Start', 'neuron-builder' ),
				'type' => Controls_Manager::DATE_TIME,
				'condition' => [
					'link_type' => [
						'google_calendar',
						'outlook_calendar',
						'yahoo_calendar',
					],
				],
			]
		);

		$this->add_control(
			'event_end_date',
			[
				'label' => __( 'End', 'neuron-builder' ),
				'type' => Controls_Manager::DATE_TIME,
				'condition' => [
					'link_type' => [
						'google_calendar',
						'outlook_calendar',
						'yahoo_calendar',
					],
				],
			]
		);
	}

	protected function register_advanced_section() {}

	private function build_mail_to_link( $settings ) {
		if ( empty( $settings['mail_to'] ) ) {
			return '';
		}

		$link = 'mailto:' . $settings['mail_to'] . '?';

		$build_parts = [];

		if ( ! empty( $settings['mail_subject'] ) ) {
			$build_parts['subject'] = $this->escape_space_in_url( $settings['mail_subject'] );
		}

		if ( ! empty( $settings['mail_body'] ) ) {
			$build_parts['body'] = $this->escape_space_in_url( $settings['mail_body'] );
		}

		return add_query_arg( $build_parts, $link );
	}

	private function build_sms_link( $settings ) {
		if ( empty( $settings['tel_number'] ) ) {
			return '';
		}

		$value = 'sms:' . $settings['tel_number'];
		return $value;
	}

	private function build_whatsapp_link( $settings ) {
		if ( empty( $settings['tel_number'] ) ) {
			return '';
		}

		return 'https://api.whatsapp.com/send?phone=' . $settings['tel_number'];
	}

	private function build_skype_link( $settings ) {
		if ( empty( $settings['username'] ) ) {
			return '';
		}

		$action = 'call';
		if ( ! empty( $settings['skype_action'] ) ) {
			$action = $settings['skype_action'];
		}
		$link = 'skype:' . $settings['username'] . '?' . $action;
		return $link;
	}

	private function build_waze_link( $settings ) {
		$link = 'https://waze.com/ul?';

		$build_parts = [
			'q' => $settings['waze_address'],
			'z' => 10,
			'navigate' => 'yes',
		];

		return add_query_arg( $build_parts, $link );
	}

	private function escape_space_in_url( $url ) {
		return str_replace( ' ', '%20', $url );
	}

	private function build_messenger_link( $settings ) {
		if ( empty( $settings['username'] ) ) {
			return '';
		}
		return 'https://m.me/' . $settings['username'];
	}

	public function build_viber_link( $settings ) {
		if ( empty( $settings['tel_number'] ) ) {
			return '';
		}
		$action = 'contact';
		if ( ! empty( $settings['viber_action'] ) ) {
			$action = $settings['viber_action'];
		}
		return add_query_arg( [
			'number' => urlencode( $settings['tel_number'] ),
		], 'viber://' . $action );
	}

	public function render() {
		$settings = $this->get_settings();
		$value = '';

		if ( empty( $settings['link_type'] ) ) {
			return '';
		}
		
		switch ( $settings['link_type'] ) {
			case 'email':
				$value = $this->build_mail_to_link( $settings );
				break;
			case 'tel':
				$value = ( empty( $settings['tel_number'] ) ? '' : 'tel:' . $settings['tel_number'] );
				break;
			case 'sms':
				$value = $this->build_sms_link( $settings );
				break;
			case 'messenger':
				$value = $this->build_messenger_link( $settings );
				break;
			case 'whatsapp':
				$value = $this->build_whatsapp_link( $settings );
				break;
			case 'skype':
				$value = $this->build_skype_link( $settings );
				break;
			case 'waze':
				$value = $this->build_waze_link( $settings );
				break;
			case 'viber':
				$value = $this->build_viber_link( $settings );
				break;
		}

		echo $value;
	}
}

