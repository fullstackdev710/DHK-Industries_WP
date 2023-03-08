<?php
/**
 * Animations Options
 * 
 * @since 1.0.0
 */

namespace Neuron\Modules\Animations;

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Widget_Base;
use Neuron\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		$this->add_actions();
	}

	public function get_name() {
		return 'neuron-animations';
    }
    
	public static function entrance_animations() {
		$animations = [
			'Fading' => [
				'h-neuron-animation--fadeIn' => 'Fade In'
			],
			'Sliding' => [
				'h-neuron-animation--slideUp' => 'Slide Up',
				'h-neuron-animation--slideDown' => 'Slide Down',
				'h-neuron-animation--slideFromLeft' => 'Slide From Left',
				'h-neuron-animation--slideFromRight' => 'Slide From Right',
			],
			'Scale' => [
				'h-neuron-animation--scaleIn' => 'Scale In'
			],
			'Curtain' => [
				'h-neuron-animation--curtainUp' => 'Curtain Up',
				'h-neuron-animation--curtainDown' => 'Curtain Down',
				'h-neuron-animation--curtainFromLeft' => 'Curtain From Left',
				'h-neuron-animation--curtainFromRight' => 'Curtain From Right'
			],
			'Clip' => [
				'h-neuron-animation--clipUp' => 'Clip Up',
				'h-neuron-animation--clipBottom' => 'Clip Bottom',
				'h-neuron-animation--clipFromLeft' => 'Clip From Left',
				'h-neuron-animation--clipFromRight' => 'Clip From Right',
			],
			'Tilt' => [
				'h-neuron-animation--tiltUp' => 'Tilt Up',
				'h-neuron-animation--tiltDown' => 'Tilt Down'
			],
			'Neuron' => [
				'h-neuron-animation--specialOne' => 'Special One'
			],
			'Zooming' => [],
			'Bouncing' => [],
			'Rotating' => [],
			'Light Speed' => [],
			'Specials' => [],
			'Attention Seekers' => [],
		];

		return $animations;
	}

	public static function hover_animations() {
		$animations = [
			'Fading' => [
				'h-neuron-animation--fadeIn' => 'Fade In'
			],
			'Sliding' => [
				'h-neuron-animation--slideUp' => 'Slide Up',
				'h-neuron-animation--slideDown' => 'Slide Down',
				'h-neuron-animation--slideFromLeft' => 'Slide From Left',
				'h-neuron-animation--slideFromRight' => 'Slide From Right',
			],
			'Scale' => [
				'h-neuron-animation--scaleIn' => 'Scale In'
			],
			'Curtain' => [
				'h-neuron-animation--curtainUp' => 'Curtain Up'
			],
			'Clip' => [
				'h-neuron-animation--clipUp' => 'Clip Up',
				'h-neuron-animation--clipBottom' => 'Clip Bottom',
				'h-neuron-animation--clipFromLeft' => 'Clip From Left',
				'h-neuron-animation--clipFromRight' => 'Clip From Right',
			],
			'Tilt' => [
				'h-neuron-animation--tiltUp' => 'Tilt Up',
				'h-neuron-animation--tiltDown' => 'Tilt Down'
			],
			'Neuron' => [
				'h-neuron-animation--specialOne' => 'Special One'
			],
			'Zooming' => [],
			'Bouncing' => [],
			'Rotating' => [],
			'Light Speed' => [],
			'Specials' => [],
			'Attention Seekers' => [],
		];

		return $animations;
	}

	public static function exit_animations() {
		/**
		 * Reverse Animations
		 * 
		 * Reverse names of UP & Down because 
		 * of the animation-direction: reverse
		 */
		
		$animations = [
			'Fading' => [
				'h-neuron-animation--fadeIn' => 'Fade Out'
			],
			'Sliding' => [
				'h-neuron-animation--slideDown' => 'Slide Out Up',
				'h-neuron-animation--slideUp' => 'Slide Out Down',
				'h-neuron-animation--slideFromLeft' => 'Slide Out Left',
				'h-neuron-animation--slideFromRight' => 'Slide Out Right',
			],
			'Scale' => [
				'h-neuron-animation--scaleIn' => 'Scale Out'
			],
			'Curtain' => [
				'h-neuron-animation--curtainDown' => 'Curtain Up',
				'h-neuron-animation--curtainUp' => 'Curtain Down',
				'h-neuron-animation--curtainFromLeft' => 'Curtain Left',
				'h-neuron-animation--curtainFromRight' => 'Curtain Right'
			],
			'Clip' => [
				'h-neuron-animation--clipBottom' => 'Clip Up',
				'h-neuron-animation--clipUp' => 'Clip Bottom',
				'h-neuron-animation--clipFromLeft' => 'Clip Left',
				'h-neuron-animation--clipFromRight' => 'Clip Right',
			],
			'Tilt' => [
				'h-neuron-animation--tiltDown' => 'Tilt Up',
				'h-neuron-animation--tiltUp' => 'Tilt Down'	
			],
			'Neuron' => [
				'h-neuron-animation--specialOne' => 'Special One Out'
			],
			'Zooming' => [],
			'Bouncing' => [],
			'Rotating' => [],
			'Light Speed' => [],
			'Specials' => [],
			'Attention Seekers' => [],
		];

		return $animations;
	}

	public function update_controls( Element_Base $element ) {
		if ( $element instanceof Widget_Base ) {
			$element->update_control(
				'animation_duration',
				[
					'condition' => [
						'_animation!' => [ 
							'none', 
							'', 
							'h-neuron-animation--specialOne', 
							'h-neuron-animation--clipFromLeft', 
							'h-neuron-animation--clipFromRight', 
							'h-neuron-animation--clipUp', 
							'h-neuron-animation--clipBottom', 
						] 
					]
				]
			);
		} else {
			$element->update_control(
				'animation_duration',
				[
					'condition' => [
						'animation!' => [ 
							'none', 
							'', 
							'h-neuron-animation--specialOne', 
							'h-neuron-animation--clipFromLeft', 
							'h-neuron-animation--clipFromRight', 
							'h-neuron-animation--clipUp', 
							'h-neuron-animation--clipBottom', 
						] 
					]
				]
			);
		}
	}


	private function add_actions() {
		add_action( 'elementor/controls/animations/additional_animations', [ $this, 'entrance_animations' ] );
		// add_action( 'elementor/controls/hover_animations/additional_animations', [ $this, 'hover_animations' ] );
		add_action( 'elementor/controls/exit-animations/additional_animations', [ $this, 'exit_animations' ] );
        add_action( 'elementor/element/section/section_effects/after_section_end', [ $this, 'update_controls' ] );
		add_action( 'elementor/element/common/section_effects/after_section_end', [ $this, 'update_controls' ] );
	}
}
