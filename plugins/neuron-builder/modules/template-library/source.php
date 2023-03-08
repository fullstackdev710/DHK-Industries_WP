<?php
/**
 * Custom Source
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\TemplateLibrary;

use Elementor\Plugin;

class Source_Custom extends \Elementor\TemplateLibrary\Source_Base {

	const LIBRARY_OPTION_KEY = 'neuron_remote_info_library';

	const FEED_OPTION_KEY = 'neuron_remote_info_feed_data';

	const TIMESTAMP_CACHE_KEY = 'neuron_remote_update_timestamp';

	public static $api_info_url = 'https://neuronthemes.com/library/wp-json/neuron/v1/info';

	private static $api_get_template_content_url = 'https://neuronthemes.com/library/wp-json/neuron/v1/info/%d';

	public function get_id() {
		return 'remote';
	}

	public function get_title() {
		return __( 'Remote', 'neuron-builder' );
	}

	public function register_data() {}

	// public static function get_promotion_widgets() {
	// 	return false;
	// }

	public function get_items( $args = [] ) {
		$library_data = self::get_library_data();

		$templates = [];

		if ( ! empty( $library_data['templates'] ) ) {
			foreach ( $library_data['templates'] as $template_data ) {
				$templates[] = $this->prepare_template( $template_data );
			}
		}

		return $templates;
	}

	public static function get_library_data( $force_update = false ) {
		self::get_info_data( $force_update );

        $library_data = get_option( self::LIBRARY_OPTION_KEY );
        
		if ( empty( $library_data ) ) {
			return [];
		}

		return $library_data;
	}

	public static function get_feed_data( $force_update = false ) {
		self::get_info_data( $force_update );

		$feed = get_option( self::FEED_OPTION_KEY );

		if ( empty( $feed ) ) {
			return [];
		}

		return $feed;
	}

	private static function get_info_data( $force_update = false ) {
        $cache_key = 'neuron_remote_info_api_data_' . NEURON_BUILDER_VERSION;

		$info_data = get_transient( $cache_key );

		if ( $force_update || false === $info_data ) {
			$timeout = ( $force_update ) ? 25 : 8;

			$response = wp_remote_get( self::$api_info_url, [
				'timeout' => $timeout,
				'body' => [
					// Which API version is used.
					'api_version' => NEURON_BUILDER_VERSION,
					// Which language to return.
					'site_lang' => get_bloginfo( 'language' ),
				],
			] );

			if ( is_wp_error( $response ) || 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
				set_transient( $cache_key, [], 2 * HOUR_IN_SECONDS );

				return false;
			}

			$info_data = json_decode( wp_remote_retrieve_body( $response ), true );

			if ( empty( $info_data ) || ! is_array( $info_data ) ) {
				set_transient( $cache_key, [], 2 * HOUR_IN_SECONDS );

				return false;
			}

			if ( isset( $info_data['library'] ) ) {
				update_option( self::LIBRARY_OPTION_KEY, $info_data['library'], 'no' );

				unset( $info_data['library'] );
			}

			if ( isset( $info_data['feed'] ) ) {
				update_option( self::FEED_OPTION_KEY, $info_data['feed'], 'no' );

				unset( $info_data['feed'] );
			}

			set_transient( $cache_key, $info_data, 12 * HOUR_IN_SECONDS );
		}

		return $info_data;
	}

	public function get_item( $template_id ) {
		$templates = $this->get_items();

		return $templates[ $template_id ];
	}

	public function save_item( $template_data ) {
		return new \WP_Error( 'invalid_request', 'Cannot save template to a remote source' );
	}

	public function update_item( $new_data ) {
		return new \WP_Error( 'invalid_request', 'Cannot update template to a remote source' );
	}

	public function delete_template( $template_id ) {
		return new \WP_Error( 'invalid_request', 'Cannot delete template from a remote source' );
	}

	public function export_template( $template_id ) {
		return new \WP_Error( 'invalid_request', 'Cannot export template from a remote source' );
	}

	public function get_data( array $args, $context = 'display' ) {
		$data = self::get_template_content( $args['template_id'] );

		if ( is_wp_error( $data ) ) {
			return $data;
        }
        
		$data = (array) $data;

		$data['content'] = $this->replace_elements_ids( $data['content'] );
		$data['content'] = $this->process_export_import_content( $data['content'], 'on_import' );

		$post_id = $args['editor_post_id'];
		$document = Plugin::$instance->documents->get( $post_id );
		if ( $document ) {
			$data['content'] = $document->get_elements_raw_data( $data['content'], true );
		}

		return $data;
	}

	
	public static function get_template_content( $template_id ) {
		$url = sprintf( self::$api_get_template_content_url, $template_id );

		$body_args = [
			// Which API version is used.
			'api_version' => NEURON_BUILDER_VERSION,
			// Which language to return.
			'site_lang' => get_bloginfo( 'language' ),
		];

		/**
		 * API: Template body args.
		 *
		 * Filters the body arguments send with the GET request when fetching the content.
		 *
		 * @param array $body_args Body arguments.
		 */
		$body_args = apply_filters( 'elementor/api/get_templates/body_args', $body_args );

		$response = wp_remote_get( $url, [
			'timeout' => 40,
			'body' => $body_args,
		] );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$response_code = (int) wp_remote_retrieve_response_code( $response );

		if ( 200 !== $response_code ) {
			return new \WP_Error( 'response_code_error', sprintf( 'The request returned with a status code of %s.', $response_code ) );
		}

		$template_content = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( isset( $template_content['error'] ) ) {
			return new \WP_Error( 'response_error', $template_content['error'] );
		}

		if ( empty( $template_content['data'] ) && empty( $template_content['content'] ) ) {
			return new \WP_Error( 'template_data_error', 'An invalid data was returned.' );
		}

		return $template_content;
	}

	private function prepare_template( array $template_data ) {
		$favorite_templates = $this->get_user_meta( 'favorites' );

		return [
			'template_id' => $template_data['id'],
			'source' => $this->get_id(),
			'type' => $template_data['type'],
			'subtype' => $template_data['subtype'],
			'title' => $template_data['title'],
			'thumbnail' => $template_data['thumbnail'],
			'date' => $template_data['tmpl_created'],
			'author' => $template_data['author'],
			'tags' => ! empty( $template_data['tags']) ?  json_decode( $template_data['tags'] ) : '',
			'isPro' => ( '1' === $template_data['is_pro'] ),
			'accessLevel' => $template_data['access_level'],
			'popularityIndex' => (int) $template_data['popularity_index'],
			'trendIndex' => (int) $template_data['trend_index'],
			'hasPageSettings' => ( '1' === $template_data['has_page_settings'] ),
			'url' => $template_data['url'],
			'favorite' => ! empty( $favorite_templates[ $template_data['id'] ] ),
		];
	}
}

