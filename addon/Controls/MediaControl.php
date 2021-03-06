<?php

namespace WPMVC\Addons\Metaboxer\Controls;

use WPMVC\MVC\Models\Common\Attachment;
use WPMVC\Addons\Metaboxer\Abstracts\Control;
/**
 * jQuery media control.
 *
 * @author 10 Quality <info@10quality.com>
 * @package wpmvc-addon-metaboxer
 * @license MIT
 * @version 1.0.0
 */
class MediaControl extends Control
{
    /**
     * Control type.
     * @since 1.0.0
     * @var string
     */
    const TYPE = 'media';
    /**
     * The control type, acts like ID identifier.
     * @since 1.0.0
     * @var string
     */
    protected $type = self::TYPE;
    /**
     * View key to use if render method is not present.
     * View will be render as fallback.
     * @since 1.0.0
     * @var string
     */
    protected $view = 'metaboxer.controls.media';
    /**
     * Enqueues styles and scripts especific to the control.
     * @since 1.0.0
     */
    public function enqueue()
    {
        wpmvc_enqueue_addon_resource( 'wp-media-uploader' );
        wpmvc_enqueue_addon_resource( 'wpmvc-media' );
    }
    /**
     * Renders output.
     * @since 1.0.0
     * @param array $args
     */
    public function render( $args = [] )
    {
        if ( is_numeric( $args['value'] ) ) {
            $args['attachment'] = Attachment::find( $args['value'] );
        }
        parent::render( $args );
    }
}