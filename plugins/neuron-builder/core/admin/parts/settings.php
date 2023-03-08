<div class="neuron-admin">

    <?php $this->neuron_admin_header() ?>

    <div class="neuron-admin__content">
        
        <form id="neuron-settings-form" method="POST" action="options.php">
            <?php settings_fields( 'neuron-integrations' ); ?>
            
            <div class="neuron-admin__card">
                <?php do_settings_sections( 'neuron_integrations_recaptcha_section' ) ?>
            </div>
            <div class="neuron-admin__card">
                <?php do_settings_sections( 'neuron_integrations_recaptcha_v3_section' ) ?>
            </div>
            <div class="neuron-admin__card">
                <?php do_settings_sections( 'neuron_google_map_section' ) ?>
            </div>
            <div class="neuron-admin__card">
                <?php do_settings_sections( 'neuron_instagram_section' ) ?>
            </div>
            <div class="neuron-admin__card">
                <?php do_settings_sections( 'neuron_mailchimp_section' ) ?>
            </div>
            <div class="neuron-admin__card">
                <?php do_settings_sections( 'neuron_typekit_section' ) ?>
            </div>
            <?php submit_button(); ?> 
        </form>
    </div>
</div>