<?php
/**
 * Attachments
 * 
 * Extra Options to attachments
 * 
 * @since 1.0.0
 */

namespace Neuron\Core\Helpers;

class Attachments {
    
    public function __construct() {
        add_filter( 'attachment_fields_to_edit', [ $this, 'attachment_fields_edit' ], 10, 2 );
        add_filter( 'attachment_fields_to_save', [ $this, 'attachment_fields_save' ], 10, 2 );
    }

    public function get_fields() {
        $fields = array(
            'metro_column' => array(
                'label'       => __( 'Metro Column', 'neuron-builder' ),
                'input'       => 'select',
                'options'     => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                    '11' => '11',
                    '12' => '12',
                ],
                'default_value' => 3
            ),
            'external_url' => array(
                'label'       => __( 'External URL', 'neuron-builder' ),
                'input'       => 'text',
            ),
        );

        $fields = apply_filters( 'neuron/attachment/options', $fields );

        return $fields;
    }

    public function attachment_fields_edit( $form_fields, $post ) {
        if ( ! empty( $this->get_fields() ) ) {
            // We browse our set of options
            foreach ( $this->get_fields() as $field => $values ) {
                $meta = get_post_meta( $post->ID, '_' . $field, true );

                switch ( $values['input'] ) {
                    default:
                    case 'text':
                        $values['input'] = 'text';
                        break;
                
                    case 'textarea':
                        $values['input'] = 'textarea';
                        break;
                
                    case 'select':
                
                        // Select type doesn't exist, so we will create the html manually
                        // For this, we have to set the input type to 'html'
                        $values['input'] = 'html';
                
                        // Create the select element with the right name (matches the one that wordpress creates for custom fields)
                        $html = '<select style="width: 100%;" name="attachments[' . $post->ID . '][' . $field . ']">';
                
                        // If options array is passed
                        if ( isset( $values['options'] ) ) {

                            // Browse and add the options
                            foreach ( $values['options'] as $k => $v ) {

                                // Set the option selected or not
                                if ( $meta == $k || $values['default_value'] == $k ) {
                                    $selected = ' selected="selected"';
                                } else {
                                    $selected = '';
                                }

                                $html .= '<option' . $selected . ' value="' . $k . '">' . $v . '</option>';
                            }
                        }
                
                        $html .= '</select>';
                
                        // Set the html content
                        $values['html'] = $html;
                
                        break;
                }

                $values['value'] = $meta;

                $form_fields[$field] = $values;
            }
        }

        // We return the completed $form_fields array
        return $form_fields;
    }

    public function attachment_fields_save( $post, $attachment ) {
        if ( ! empty( $this->get_fields() ) ) {
            foreach ( $this->get_fields() as $field => $values ) {
                if ( isset( $attachment[$field] ) ) {
                    if ( strlen( trim( $attachment[$field] ) ) == 0 ) {
                        $post['errors'][$field]['errors'][] = __( $values['error_text'] );
                    } else {
                        update_post_meta( $post['ID'], '_' . $field, $attachment[$field] );
                    }
                } else {
                    delete_post_meta( $post['ID'], $field );
                }
            }
        }
    
        return $post;
    }
}