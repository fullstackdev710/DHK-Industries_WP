<div class="neuron-admin">
    <?php $this->neuron_admin_header( 'system-info' ) ?>

    <div class="neuron-admin__content neuron-admin__system-info">

        <table class="widefat" cellspacing="0">
            <thead>
                <tr>
                    <th colspan="3">Theme Info</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Theme Version:</td>
                    <td>
                        <div class="tooltip">[?]<span class="tooltip__text">Shows the actual version of theme.</span></div>
                    </td>
                    <td><?php echo esc_attr( wp_get_theme()->version ) ?></td>
                </tr>
                <tr>
                    <td>Plugin Version:</td>
                    <td>
                        <div class="tooltip">[?]<span class="tooltip__text">Shows the actual version of Neuron Builder.</span></div>
                    </td>
                    <td><?php echo esc_attr( NEURON_BUILDER_VERSION ) ?></td>
                </tr>
            </tbody>
        </table>

        <table class="widefat" cellspacing="0">
            
            <!-- WordPress Info -->
            <thead>
                <tr>
                    <th colspan="3">WordPress Info</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Home URL:</td>
                    <td>
                        <div class="tooltip">[?]<span class="tooltip__text">Shows the home URL of current site.</span></div>
                    </td>
                    <td><?php echo home_url() ?></td>
                </tr>
                <tr>
                    <td>Site URL:</td>
                    <td>
                        <div class="tooltip">[?]<span class="tooltip__text">Shows the site URL of current site.</span></div>
                    </td>
                    <td><?php echo site_url() ?></td>
                </tr>
                <tr>
                    <td>WordPress Version:</td>
                    <td>
                        <div class="tooltip">[?]<span class="tooltip__text">Shows the actual version of WordPress.</span></div>
                    </td>
                    <td><?php bloginfo('version'); ?></td>
                </tr>
                <tr>
                    <td>WordPress Multisite:</td>
                    <td>
                        <div class="tooltip">[?]<span class="tooltip__text">Determine whether Multisite support is enabled.</span></div>
                    </td>
                    <td><?php echo (is_multisite()) ? '&#10004;' : '&ndash;'; ?></td>
                </tr>
                <tr>
                    <td>PHP Memory Limit:</td>
                    <td>
                        <div class="tooltip">[?]<span class="tooltip__text">Shows the memory limit from the server.</span></div>
                    </td>
                    <?php
                        // Get memory limit from the server
                        $memory_limit = ini_get('memory_limit');
                        $memory_limit_output = '';

                        // If memory limit fails get WP_MEMORY_LIMIT
                        if (!$memory_limit || -1 === $memory_limit) {
                            $memory_limit = wp_convert_hr_to_bytes(WP_MEMORY_LIMIT);
                        }

                        // Format the number if it's not formatted correctly in bytes
                        if (!is_numeric($memory_limit)) {
                            $memory_limit = wp_convert_hr_to_bytes($memory_limit);
                        }

                        if ($memory_limit < 128000000) {
                            $memory_limit_output = size_format($memory_limit) . " - <mark>We suggest to increase the memory limit to 128mb or higher.</mark>";
                        } else {
                            $memory_limit_output = size_format($memory_limit);
                        }
                    ?>
                    <td><b><?php echo $memory_limit_output ?></b></td>
                </tr>
                <tr>
                    <td>WP Debug Mode:</td>
                    <td>
                        <div class="tooltip">[?]<span class="tooltip__text">WP_DEBUG can be used to trigger the "debug" mode throughout WordPress.</span></div>
                    </td>
                    <?php
                        // Debug Mode
                        if (defined('WP_DEBUG') && WP_DEBUG) {
                            $debug_mode = '&#10004;';
                        } else {
                            $debug_mode = '&ndash;';
                        }
                    ?>
                    <td><?php echo $debug_mode; ?></td>
                </tr>
                <tr>
                    <td>Language:</td>
                    <td>
                        <div class="tooltip">[?]<span class="tooltip__text">Shows the language of current site.</span></div>
                    </td>
                    <td><?php bloginfo('language') ?></td>
                </tr>
            </tbody>
        </table>

        <table class="widefat" cellspacing="0">
            <thead>
                <tr>
                    <th colspan="3">Server Info</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>PHP Version:</td>
                    <td>
                        <div class="tooltip">[?]<span class="tooltip__text">Shows the actual version of PHP.</span></div>
                    </td>
                    <?php
                        // PHP Version
                        $php_version = null;
                        $php_version_output = '';

                        if (defined('PHP_VERSION')) {
                            $php_version = PHP_VERSION;
                        } elseif (function_exists('phpversion')) {
                            $php_version = phpversion();
                        }

                        if (null === $php_version) {
                            $php_version_output = "<mark>PHP Version could not be detected.</mark>";
                        } else {
                            if (version_compare($php_version, '7.0.0') >= 0) {
                                $php_version_output = $php_version;
                            } else {
                                $php_version_output = $php_version . " - <mark>We suggest to use version 7 of PHP or higher.</mark>";
                            }
                        }
                    ?>
                    <td><?php echo $php_version_output; ?></td>
                </tr>
                <tr>
                    <td>PHP Post Max Size:</td>
                    <td>
                        <div class="tooltip">[?]<span class="tooltip__text">Shows the maximum upload file size.</span></div>
                    </td>
                    <?php
                        // Post Max Size
                        $post_max_size = ini_get('post_max_size');
                        $post_max_number = preg_replace('/[^0-9]+/', '', $post_max_size);
                        $post_max_output = '';

                        if ($post_max_number <= 8) {
                            $post_max_output = size_format(wp_convert_hr_to_bytes($post_max_size)) . ' - <mark>We recommend increasing the maximum upload file size to 32 or higher.</mark>';
                        } else {
                            $post_max_output = size_format(wp_convert_hr_to_bytes($post_max_size));
                        }
                    ?>
                    <td><?php echo $post_max_output ?></td>
                </tr>
                <tr>
                    <td>PHP Time Limit:</td>
                    <td>
                        <div class="tooltip">[?]<span class="tooltip__text">Shows the maximum execution time of server.</span></div>
                    </td>
                    <?php
                        // Maximum Execution Time
                        $maximum_execution = ini_get('max_execution_time');
                        $maximum_execution_output = '';

                        if ($maximum_execution < 60) {
                            $maximum_execution_output = $maximum_execution . ' - <mark>We recommend increasing the maximum execution time to 400 or higher.</mark>';
                        } else {
                            $maximum_execution_output = $maximum_execution;
                        }
                    ?>
                    <td><?php echo $maximum_execution_output ?></td>
                </tr>
                <tr>
                    <td>MySQL Version:</td>
                    <td>
                        <div class="tooltip">[?]<span class="tooltip__text">Shows the actual version of MySQL.</span></div>
                    </td>
                    <?php
                        global $wpdb;
                        $mysql_version = $wpdb->db_version();
                    ?>
                    <td><?php echo $mysql_version ?></td>
                </tr>
            </tbody>
        </table>

    </div>
</div>