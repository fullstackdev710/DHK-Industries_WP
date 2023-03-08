<?php
/**
 * Add WPML Compatibility Module.
 *
 * @since 1.0.0
 */

namespace Neuron\Core\Compatibility\Wpml;

defined( 'ABSPATH' ) || die();

class Module {

	const TYPE = 'widgetType';

	public function __construct() {
		add_filter( 'wpml_elementor_widgets_to_translate', [ $this, 'wpml_widgets_to_translate_filter' ] );
	}

	public function wpml_widgets_to_translate_filter( $widgets ) {

		$widgets['neuron-search-form'] = [
			'conditions' => [ self::TYPE => 'neuron-search-form' ],
			'fields'     => [
				[
					'field'       => 'placeholder',
					'type'        => esc_html__( 'Neuron Search Form: Placeholder', 'neuron-builder' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$widgets['neuron-slides'] = [
			'conditions' => [ self::TYPE => 'neuron-slides' ],
			'fields' => array(),
			'integration-class' => __NAMESPACE__ . '\Modules\Slides',
		];

		$widgets['neuron-flip-box'] = [
			'conditions' => [ self::TYPE => 'neuron-flip-box' ],
			'fields'     => [
				[
					'field'       => 'title_front',
					'type'        => __( 'Flip Box: Title text side A', 'neuron-builder' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'description_front',
					'type'        => __( 'Flip Box: Description text side A', 'neuron-builder' ),
					'editor_type' => 'AREA'
				],
				[
					'field'       => 'title_back',
					'type'        => __( 'Flip Box: Title text side B', 'neuron-builder' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'description_back',
					'type'        => __( 'Flip Box: Description text side B', 'neuron-builder' ),
					'editor_type' => 'AREA'
				],
				[
					'field'       => 'button_text',
					'type'        => __( 'Flip Box: Button text', 'neuron-builder' ),
					'editor_type' => 'LINE'
				],
				'link' => [
					'field'       => 'link',
					'type'        => __( 'Flip Box: Button link', 'neuron-builder' ),
					'editor_type' => 'LINK'
				]
			]
		];

		$widgets['neuron-animated-heading'] = [
			'conditions' => [ self::TYPE => 'neuron-animated-heading' ],
			'fields'     => [
				[
					'field'       => 'before_text',
					'type'        => __( 'Animated Heading: Before text', 'neuron-builder' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'highlighted_text',
					'type'        => __( 'Animated Heading: Highlighted text', 'neuron-builder' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'rotating_text',
					'type'        => __( 'Animated Heading: Rotating text', 'neuron-builder' ),
					'editor_type' => 'AREA'
				],
				[
					'field'       => 'after_text',
					'type'        => __( 'Animated Heading: After text', 'neuron-builder' ),
					'editor_type' => 'LINE'
				],
				'link' => [
					'field'       => 'link',
					'type'        => __( 'Animated Heading: Link URL', 'neuron-builder' ),
					'editor_type' => 'LINK'
				],
			]
		];

		$widgets['neuron-testimonial-carousel'] = [
			'conditions' => [ self::TYPE => 'neuron-testimonial-carousel' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\Modules\Carousel',
		];

		$widgets['neuron-countdown'] = [
			'conditions' => [ self::TYPE => 'neuron-countdown' ],
			'fields'     => [
				[
					'field'       => 'label_days',
					'type'        => __( 'Countdown: Label days', 'neuron-builder' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'label_hours',
					'type'        => __( 'Countdown: Label hours', 'neuron-builder' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'label_minutes',
					'type'        => __( 'Countdown: Label minutes', 'neuron-builder' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'label_seconds',
					'type'        => __( 'Countdown: Label seconds', 'neuron-builder' ),
					'editor_type' => 'LINE'
				],
			],
		];

		$widgets['neuron-blockquote'] = [
			'conditions' => [ self::TYPE => 'neuron-blockquote' ],
			'fields'     => [
				[
					'field'       => 'blockquote_content',
					'type'        => __( 'Blockquote: Content', 'neuron-builder' ),
					'editor_type' => 'VISUAL'
				],
				[
					'field'       => 'tweet_button_label',
					'type'        => __( 'Blockquote: Tweet button label', 'neuron-builder' ),
					'editor_type' => 'LINE'
				],
			],
		];

		$widgets['neuron-price-list'] = [
			'conditions' => [ self::TYPE => 'neuron-price-list' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\Modules\PriceList',
		];

		$widgets['neuron-price-table'] = [
			'conditions' => [ self::TYPE => 'neuron-price-table' ],
			'fields'     => [
				[
					'field'       => 'title',
					'type'        => __( 'Price Table: Title', 'neuron-builder' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'description',
					'type'        => __( 'Price Table: Description', 'neuron-builder' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'period',
					'type'        => __( 'Price Table: Period', 'neuron-builder' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'button_text',
					'type'        => __( 'Price Table: Button text', 'neuron-builder' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'additional_info',
					'type'        => __( 'Price Table: Footer additional info', 'neuron-builder' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'ribbon_title',
					'type'        => __( 'Price Table: Ribbon title', 'neuron-builder' ),
					'editor_type' => 'LINE'
				],
				'link' => [
					'field'       => 'button_link',
					'type'        => __( 'Price Table: Button link', 'neuron-builder' ),
					'editor_type' => 'LINK'
				],
			],
			'integration-class' => __NAMESPACE__ . '\Modules\PriceTable',
		];

		$widgets['neuron-login'] = [
			'conditions' => [ self::TYPE => 'neuron-login' ],
			'fields'     => [
				[
					'field'       => 'button_text',
					'type'        => __( 'Login: Button text', 'neuron-builder' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'user_label',
					'type'        => __( 'Login: User label', 'neuron-builder' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'user_placeholder',
					'type'        => __( 'Login: User placeholder', 'neuron-builder' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'password_label',
					'type'        => __( 'Login: Password label', 'neuron-builder' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'password_placeholder',
					'type'        => __( 'Login: Password placeholder', 'neuron-builder' ),
					'editor_type' => 'LINE'
				],	
			],
		];

		$widgets['neuron-posts'] = [
			'conditions' => [ self::TYPE => 'neuron-posts' ],
			'fields'     => [
				[
					'field'       => 'prev_label',
					'type'        => __( 'Posts: Previous Label', 'neuron-builder' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'next_label',
					'type'        => __( 'Posts: Next Label', 'neuron-builder' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'read_more_text',
					'type'        => __( 'Posts: Cards Read more text', 'neuron-builder' ),
					'editor_type' => 'LINE'
				],
			],
		];

		$widgets['neuron-post-navigation'] = [
			'conditions' => [ self::TYPE => 'neuron-post-navigation' ],
			'fields'     => [
				[
					'field'       => 'previous_label',
					'type'        => __( 'Previous Label', 'neuron-builder' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'next_label',
					'type'        => __( 'Next Label', 'neuron-builder' ),
					'editor_type' => 'LINE',
				],
			],
		];
		
		$widgets['neuron-table-of-contents'] = [
			'conditions' => [ self::TYPE => 'neuron-table-of-contents' ],
			'fields'     => [
				[
					'field'       => 'title',
					'type'        => __( 'Table of Contents - Title', 'neuron-builder' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$widgets['neuron-archive-posts'] = [
			'conditions' => [ self::TYPE => 'neuron-archive-posts' ],
			'fields'     => [
				[
					'field'       => 'separator_between',
					'type'        => __( 'Separator Between', 'neuron-builder' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'read_more_text',
					'type'        => __( 'Read More Text', 'neuron-builder' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'prev_label',
					'type'        => __( 'Previous Label', 'neuron-builder' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'next_label',
					'type'        => __( 'Next Label', 'neuron-builder' ),
					'editor_type' => 'LINE'
				],
			],
		];

		// $fields['neuron-form'] = [ // TODO
		// 	'conditions' => [ self::TYPE => 'neuron-form' ],
		// 	'fields'     => [
		// 		[
		// 			'field'       => 'form_name',
		// 			'type'        => __( 'Form: name', 'neuron-builder' ),
		// 			'editor_type' => 'LINE'
		// 		],
		// 		[
		// 			'field'       => 'button_text',
		// 			'type'        => __( 'Form: Button text', 'neuron-builder' ),
		// 			'editor_type' => 'LINE'
		// 		],
				// [
				// 	'field'       => 'email_subject',
				// 	'type'        => __( 'Form: Email subject', 'neuron-builder' ),
				// 	'editor_type' => 'LINE'
				// ],
				// [
				// 	'field'       => 'email_from_name',
				// 	'type'        => __( 'Form: Email from name', 'neuron-builder' ),
				// 	'editor_type' => 'LINE'
				// ],
				// [
				// 	'field'       => 'email_content',
				// 	'type'        => __( 'Form: Email Content', 'neuron-builder' ),
				// 	'editor_type' => 'AREA'
				// ],
				// [
				// 	'field'       => 'email_subject_2',
				// 	'type'        => __( 'Form: Email subject 2', 'neuron-builder' ),
				// 	'editor_type' => 'LINE'
				// ],
				// [
				// 	'field'       => 'email_content_2',
				// 	'type'        => __( 'Form: Email Content', 'neuron-builder' ),
				// 	'editor_type' => 'AREA'
				// ],
				// [
				// 	'field'       => 'success_message',
				// 	'type'        => __( 'Form: Success message', 'neuron-builder' ),
				// 	'editor_type' => 'LINE'
				// ],
				// [
				// 	'field'       => 'error_message',
				// 	'type'        => __( 'Form: Error message', 'neuron-builder' ),
				// 	'editor_type' => 'LINE'
				// ],
				// [
				// 	'field'       => 'required_message',
				// 	'type'        => __( 'Form: Required message', 'neuron-builder' ),
				// 	'editor_type' => 'LINE'
				// ],
				// [
				// 	'field'       => 'invalid_message',
				// 	'type'        => __( 'Form: Invalid message', 'neuron-builder' ),
				// 	'editor_type' => 'LINE'
				// ],
				// [
				// 	'field'       => 'required_field_message',
				// 	'type'        => __( 'Form: Required message', 'neuron-builder' ),
				// 	'editor_type' => 'LINE'
				// ],
				// [
				// 	'field'       => 'redirect_to',
				// 	'type'        => __( 'Form: Redirect to URL', 'neuron-builder' ),
				// 	'editor_type' => 'LINE'
				// ],
			// ],
			// 'integration-class' => __NAMESPACE__ . '\Modules\Form',
		// ];

		

		return $widgets;
	}
}
