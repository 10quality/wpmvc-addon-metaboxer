<?php

namespace WPMVC\Addons\Metaboxer\Helpers;

use WPMVC\Addons\Metaboxer\MetaboxerAddon;
/**
 * Class that helps rendering process.
 *
 * @author 10 Quality <info@10quality.com>
 * @package wpmvc-addon-metaboxer
 * @license MIT
 * @version 1.0.4
 */
class RenderHelper
{
    /**
     * Current repeater ID.
     * @since 1.0.0
     * 
     * @var string
     */
    public $repeater_id = null;
    /**
     * List of current repeater fields.
     * @since 1.0.0
     * 
     * @var array
     */
    public $repeater_fields = [];
    /**
     * Flag that indicates if there is a section opened.
     * @since 1.0.0
     * 
     * @var bool
     */
    public $is_section_opened = false;
    /**
     * Flag that indicates if there is a repeater opened.
     * @since 1.0.0
     * 
     * @var bool
     */
    public $is_repeater_opened = false;
    /**
     * Flag that if repeater item is even or odd.
     * @since 1.0.0
     * 
     * @var bool
     */
    public $is_repeater_odd = true;
    /**
     * Flag that indicates if is a repeater field or not.
     * @since 1.0.0
     * 
     * @var bool
     */
    public $is_repeater_field = false;
    /**
     * Opens a section logically.
     * @since 1.0.0
     */
    public function open_section()
    {
        $this->is_section_opened = true;
    }
    /**
     * Opens a repeater logically and clears field list.
     * @since 1.0.0
     */
    public function open_repeater( $id )
    {
        $this->repeater_id = $id;
        $this->is_repeater_opened = true;
        $this->is_repeater_odd = true;
        $this->repeater_fields = [];
    }
    /**
     * Closes a section logically.
     * @since 1.0.0
     */
    public function close_section()
    {
        $this->is_section_opened = false;
        $this->is_repeater_opened = false;
    }
    /**
     * Closes a repeater logically.
     * @since 1.0.0
     */
    public function close_repeater()
    {
        $this->is_repeater_opened = false;
    }
    /**
     * Adds a field to the repeater fields.
     * @since 1.0.0
     * 
     * @param string $field_id
     * @param array  $field
     */
    public function add_repeater_field( $field_id, $field )
    {
        if ( $this->is_repeater_opened ) {
            $this->repeater_fields[$field_id] = $field;
        }
    }
    /**
     * Renders repeater fields.
     * @since 1.0.0
     * 
     * @param \WPMVC\Addons\Metaboxer\Abstracts\PostModel &$model
     * @param array                                       &$controls
     */
    public function render_repeater( &$model, &$controls )
    {
        if ( ! is_array( $this->repeater_fields ) &&
            count( $this->repeater_fields ) === 0
        ) return;
        // Get item keys
        $keys = [];
        foreach ( $this->repeater_fields as $field ) {
            if ( array_key_exists( 'value' , $field ) && is_array( $field['value'] ) ) {
                foreach ( array_keys( $field['value'] ) as $key ) {
                    if ( !in_array( $key, $keys, true ) )
                        $keys[] = $key;
                }
            }
        }
        $this->is_repeater_field = true;
        foreach ( $keys as $key ) {
            foreach ( $this->repeater_fields as $field_id => $field ) {
                $field['field_id'] = $field_id;
                $field['value'] = is_array( $field['value'] ) && array_key_exists( $key, $field['value'] ) ? $field['value'][$key] : null;
                $field['id'] = ( array_key_exists( 'name', $field ) ? $field['name'] : $field_id ) . '['. $key .']';
                $field['name'] = ( array_key_exists( 'name', $field ) ? $field['name'] : $field_id ) . '['. $key .']';
                $field['repeater_key'] = $key;
                MetaboxerAddon::view( 'metaboxer.repeater-field', [
                    'repeater_id' => &$this->repeater_id,
                    'model' => &$model,
                    'controls' => &$controls,
                    'field_id' => &$field_id,
                    'field' => &$field,
                    'key' => &$key,
                    'helper' => &$this,
                ] );
            }
            $this->is_repeater_odd = ! $this->is_repeater_odd;
        }
        $this->is_repeater_field = false;
    }
}