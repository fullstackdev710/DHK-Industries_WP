<div class="neuron-admin">
    <?php $this->neuron_admin_header( 'getting-started' ) ?>

    <div class="neuron-admin__content">
        <div class="neuron-admin__card neuron-admin__card--getting-started">
            <h2>Getting Started with Neuron Builder</h2>
            <p>We recommend you watch this getting started video, and then try the <br /> editor yourself by dragging and dropping elements to create your first page.</p>
            
           <iframe width="620" height="350" src="https://www.youtube-nocookie.com/embed/videoseries?list=PLQ6ar32WAK86L3VBrHyMf8u9hBqAelKQo&amp;controls=1&amp;modestbranding=1" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

            <div class="neuron-admin__card--inline-buttons">
                <a href="<?php echo esc_url( \Elementor\Plugin::$instance->documents->get_create_new_post_url( 'page' ) ); ?>" class="button">Create your first page</a>
                <a href="https://docs.neuronthemes.com" class="button invert">Get the full guide</a>
            </div>
        </div> 
    </div>
</div>