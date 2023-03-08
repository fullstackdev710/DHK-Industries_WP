<?php
/**
 * Archive Posts
 * 
 * Posts query will be modified
 * to match the archive posts.
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\ThemeBuilder\Widgets;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;

use Neuron\Modules\Posts\Widgets\Posts;
use Neuron\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Archive_Posts extends Posts {

	public function get_name() {
		return 'neuron-archive-posts';
	}

	public function get_title() {
		return __( 'Archive Posts', 'neuron-builder' );
	}

	public function get_icon() {
		return 'eicon-archive-posts neuron-badge';
	}

	public function get_categories() {
		return [ 'neuron-elements-archive' ];
    }

	protected function register_controls() {
		parent::register_controls();

		$this->update_control(
			'query_section',
			[
				'type' => 'hidden',
			]
		);
		
		$this->remove_control('posts_per_page');

		$this->update_control(
			'pagination',
			[
				'default' => 'numbers',
			]
		);
	}

	protected function get_query( $filters = false ) {
		$this->query_args = $GLOBALS['wp_query']->query_vars;

		if ( ! $filters ) {
			$this->show_more_pagination();
		}

		// Exclude
		$query = new \WP_Query( $this->query_args );

		return $query;
	}


	protected function render() {
		$settings = $this->get_settings_for_display();

		global $wp_query;

		$query = $this->get_query();

		$this->get_filters( $query );

		// Carousel Class
		$carousel_dots = ( in_array( $settings['navigation'], [ 'dots', 'arrows-dots' ] ) );
		$carousel_arrows = ( in_array( $settings['navigation'], [ 'arrows', 'arrows-dots' ] ) );

		if ( $settings['carousel'] == 'yes' ) {
			$gridClass = 'swiper-wrapper neuron-slides';
			$articleClass = 'swiper-slide';
		} else {
			$gridClass = 'l-neuron-grid';
			$articleClass = 'l-neuron-grid__item';
		}

		if ( !\Elementor\Plugin::$instance->editor->is_edit_mode() && $settings['animation'] == 'yes' && $settings['neuron_animations'] != '' )  {
			$articleClass .= ' h-neuron-animation--wow';
		}
		?>
		<?php if ( $settings['carousel'] == 'yes' ) : ?>
		<div class="neuron-swiper">
			<div class="neuron-slides-wrapper neuron-main-swiper swiper-container" data-animation-id="<?php echo esc_attr( md5( $this->get_id() ) ); ?>">
			<?php endif; // End Carousel ?>

				<div class="<?php echo esc_attr( $gridClass ) ?>" data-masonry-id="<?php echo esc_attr( md5( $this->get_id() ) ); ?>">
					<?php 
					if ( $query->have_posts() ) {
						while ( $query->have_posts() ) {
							$query->the_post();
							$post_class = '';

							if ( $this->repeater_item( $post_class ) ) {
								$post_class = $this->repeater_item( $post_class );
							}
							?>
							<article <?php post_class( $articleClass . ' m-neuron-post' . $post_class ) ?> data-id="<?php the_id() ?>">
								<?php
								// Skin
								if ( $settings['skin'] == 'classic' ) {
									include(  __DIR__ . '/../skins/skin-classic.php' );
								} elseif ( $settings['skin'] == 'cards' ) {
									include(  __DIR__ . '/../skins/skin-cards.php' );
								}
								?>
							</article>
							<?php
						}
					}
					wp_reset_postdata();
					?>
				</div>

			<?php if ( $settings['carousel'] == 'yes' ) : ?>
				</div>

			<?php if ( $settings['dots_style'] == 'scrollbar' ) : ?>
				<div class="swiper-scrollbar"></div>
			<?php endif; ?>

			<?php if ( $carousel_dots ) : ?>
				<div class="swiper-pagination neuron-swiper-dots"></div>
			<?php endif; ?>
			
			<?php if ( $carousel_arrows ) : ?>
				<div class="neuron-swiper-button neuron-swiper-button--prev">
					<?php if ( $settings['arrows_icon'] ) : ?>
						<div class="neuron-icon"><?php Icons_Manager::render_icon( $settings['arrows_icon'], [ 'aria-hidden' => 'true' ] ); ?></div>
					<?php endif; ?>
					<span class="neuron-swiper-button--hidden"><?php _e( 'Previous', 'neuron-builder' ); ?></span>
				</div>
				<div class="neuron-swiper-button neuron-swiper-button--next">
					<?php if ( $settings['arrows_icon'] ) : ?>
						<div class="neuron-icon"><?php Icons_Manager::render_icon( $settings['arrows_icon'], [ 'aria-hidden' => 'true' ] ); ?></div>
					<?php endif; ?>
					<span class="neuron-swiper-button--hidden"><?php _e( 'Next', 'neuron-builder' ); ?></span>
				</div>
			<?php endif; ?>
			</div>
		<?php endif; // End Carousel ?>

		<?php 
		$this->get_pagination( $wp_query );
		$this->load_packery();
	}

	protected function content_template() {}
}
