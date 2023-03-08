<?php
/**
 * Presets Control.
 *
 * @since 1.0.0
 */

namespace Neuron\Controls;

use Elementor\Base_Data_Control;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Neuron_Presets extends Base_Data_Control {

	const CONTROL_TYPE = 'neuron_presets';

	public function get_type() {
		return self::CONTROL_TYPE;
	}

	public function content_template() {
		?>
		<div class="elementor-control-field neuron-control-presets">
			<div class="neuron-element-presets-wrapper">
				<# if (data.controlValue && data.controlValue.presets.length > 0 ) data.controlValue = window.neuronPresets && window.neuronPresets[data.controlValue.presets[0]['type']] && window.neuronPresets[data.controlValue.presets[0]['type']].length === data.controlValue.presets.length ? data.controlValue : {selectedId: null, presets: []}; #>

				<div class="neuron-element-presets">
					<# if (!data.controlValue || data.controlValue && data.controlValue.presets.length === 0) { #>
						<div class="neuron-element-presets-404">
							No presets found
						</div>
					<# } #>

					<div class="neuron-element-presets__loading">
						Loading presets
						<span style="display:inline-flex" class="elementor-control-spinner"><span class="fa fa-spinner fa-spin"></span>&nbsp;</span>
					</div>

					<# if (data.controlValue) { #>
						<# _.each( data.controlValue.presets, function( preset ) { #>
							<div class="neuron-element-presets-item {{{preset.id === data.controlValue.selectedId ? ' active' : ''}}}" data-preset-id='{{{preset.id}}}'>
								<# if (preset.thumbnail) { #>
									<img src="{{{preset.thumbnail}}}" alt="{{{preset.title}}}">
								<# } else { #>
									<span class="neuron-element-presets-item-title">{{{preset.title}}}</span>
								<# } #>
							</div>
						<# } ); #>
					<# } #>
				</div>
			</div>
		</div>
		<?php
	}
}
