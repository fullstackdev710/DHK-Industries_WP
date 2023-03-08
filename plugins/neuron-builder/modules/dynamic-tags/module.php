<?php
/**
 * Dynamic Tags
 * 
 * Show different dynamic tags
 * through different elements 
 * that supports it.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\DynamicTags;

use Neuron\Modules\DynamicTags\ACF;
use Neuron\Modules\DynamicTags\Pods;

use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends TagsModule {

	const AUTHOR_GROUP = 'author';

	const POST_GROUP = 'post';

	const COMMENTS_GROUP = 'comments';

	const SITE_GROUP = 'site';

	const ARCHIVE_GROUP = 'archive';

	const ACTION_GROUP = 'action';

	public function __construct() {
		parent::__construct();

		// ACF 5 and up
		if ( class_exists( '\acf' ) && function_exists( 'acf_get_field_groups' ) ) {
			$this->add_component( 'acf', new ACF\Module() );
		}

		if ( function_exists( 'pods' ) ) {
			$this->add_component( 'pods', new Pods\Module() );
		}
	}

	public function get_name() {
		return 'tags';
	}

	public static function is_active() {
		return ! class_exists( 'ElementorPro\Plugin' );
	}

	public function get_tag_classes_names() {
		return [
			'Archive_Description',
			'Archive_Meta',
			'Archive_Title',
			'Archive_URL',

			'Author_Info',
			'Author_Meta',
			'Author_Name',
			'Author_Profile_Picture',
			'Author_URL',

			'Comments_Number',
			'Comments_URL',

			'Page_Title',
			'Post_Custom_Field',
			'Post_Date',
			'Post_Excerpt',
			'Post_Featured_Image',
			'Post_Hero_Image',
			'Post_ID',
			'Post_Terms',
			'Post_Time',
			'Post_Title',
			'Post_URL',

			'Site_Logo',
			'Site_Tagline',
			'Site_Title',
			'Site_URL',

			'Current_Date_Time',
			'Request_Parameter',
			'Lightbox',
			'Shortcode',
			'Contact_URL',
			
			'User_Info',
			'User_Profile_Picture',
		];
	}

	public function get_groups() {
		return [
			self::POST_GROUP => [
				'title' => __( 'Post', 'neuron-builder' ),
			],
			self::ARCHIVE_GROUP => [
				'title' => __( 'Archive', 'neuron-builder' ),
			],
			self::SITE_GROUP => [
				'title' => __( 'Site', 'neuron-builder' ),
			],
			self::ACTION_GROUP => [
				'title' => __( 'Actions', 'neuron-builder' ),
			],
			self::AUTHOR_GROUP => [
				'title' => __( 'Author', 'neuron-builder' ),
			],
			self::COMMENTS_GROUP => [
				'title' => __( 'Comments', 'neuron-builder' ),
			],
		];
	}
}
