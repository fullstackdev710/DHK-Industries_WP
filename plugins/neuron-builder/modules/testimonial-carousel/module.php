<?php
/**
 * Testimonial Carousel Module
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\TestimonialCarousel;

defined( 'ABSPATH' ) || die();

use Neuron\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'testimonial-carousel';
	}

	public function get_widgets() {
		return [ 'Testimonial_Carousel' ];
	}

}
