<?php
/**
 * Metaboxer tab.
 * WordPress MVC view.
 * 
 * @author 10 Quality <info@10quality.com>
 * @package wpmvc-addon-metaboxer
 * @license MIT
 * @version 1.0.0
 */
?><section id="<?php echo esc_attr( $metabox_id ) ?>-<?php echo esc_attr( $tab_id ) ?>"
    class="tab-content<?php if ( $tab_id === $default_tab ) : ?> active<?php endif ?>"
    role="tab"
>
    <?php if ( array_key_exists( 'description', $tab ) ) : ?>
        <p class="description"><?php echo esc_attr( $tab['description'] ) ?></p><hr>
    <?php endif ?>
    <?php foreach ( $fields as $field_id => $field ) : ?>
        <?php if ( array_key_exists( 'type', $field )
            && ( $field['type'] === 'section_open' || $field['type'] === 'repeater_open' )
        ) : ?>
            <?php if ( $helper->is_section_opened ) : ?></table><?php endif ?>
            <?php $helper->open_section() ?>
            <div id="<?php echo esc_attr( $field_id ) ?>"
                class="tab-section fieldset"
                <?php if ( $field['type'] === 'repeater_open' ) : ?>
                    role="repeater"
                    data-remove-message="<?php echo esc_attr( array_key_exists( 'remove_message', $field ) ? $field['remove_message'] : __( 'Remove item?', 'wpmvc-addon-administrator' ) ) ?>"
                <?php endif ?>
                <?php echo apply_filters( 'metaboxer_control_section', [], $field, $model, $helper ) ?>
            >
                <?php if ( array_key_exists( 'title', $field ) ) : ?>
                    <h3><?php echo $field['title'] ?><?php if ( $field['type'] === 'repeater_open' ) : ?>
                        <button type="button" class="repeater-add button" role="repeater-add">
                            <?php if ( ! array_key_exists( 'repeate_icon', $field ) || $field['repeate_icon'] !== false ) : ?>
                                <i class="fa <?php echo esc_attr( array_key_exists( 'repeate_icon', $field ) ? $field['repeate_icon'] : 'fa-plus-circle' ) ?>"
                                    aria-hidden="true"
                                    ></i>
                            <?php endif ?>
                            <?php echo array_key_exists( 'repeate_label', $field ) ? $field['repeate_label'] : __( 'Add' ) ?>
                        </button>
                    <?php endif ?></h3>
                <?php elseif ( $field['type'] === 'repeater_open' ) : ?>
                    <h3><!--Repeater:<?php echo esc_attr( $field_id ) ?>-->
                        <button type="button" class="repeater-add button" role="action-repeate">
                            <?php if ( ! array_key_exists( 'repeate_icon', $field ) || $field['repeate_icon'] !== false ) : ?>
                                <i class="fa <?php echo esc_attr( array_key_exists( 'repeate_icon', $field ) ? $field['repeate_icon'] : 'fa-plus-circle' ) ?>"
                                    aria-hidden="true"
                                    ></i>
                            <?php endif ?>
                            <?php echo array_key_exists( 'repeate_label', $field ) ? $field['repeate_label'] : __( 'Add' ) ?>
                        </button>
                    </h3>
                <?php endif ?>
                <?php if ( array_key_exists( 'description', $field ) && !empty( $field['description'] ) ) : ?>
                    <p class="description"><?php echo $field['description'] ?></p>
                <?php endif ?>
                <table class="form-table"<?php if ($field['type'] === 'repeater_open' ) : ?> role="repeater-items"<?php endif ?>>
                    <?php if ( $field['type'] === 'repeater_open' ) : ?>
                        <?php $helper->open_repeater( $field_id ) ?><script id="template-<?php echo esc_attr( $field_id ) ?>" type="text/template">
                    <?php endif ?>
        <?php elseif ( array_key_exists( 'type', $field ) && $helper->is_section_opened
            && ( $field['type'] === 'section_close' || $field['type'] === 'repeater_close' )
        ) : ?>
                    <?php if ( $helper->is_repeater_opened ) : ?></script><?php endif ?>
                    <?php $helper->render_repeater( $model, $controls ) ?>
                </table>
            </div><!--.tab-section-->
            <?php $helper->close_section() ?>
            <?php $helper->close_repeater() ?>
        <?php elseif ( array_key_exists( 'type', $field ) && $field['type'] === 'section_separator' ) : ?>
            <?php if ( $helper->is_repeater_opened ) : ?></script><?php endif ?>
            <?php $helper->render_repeater( $model, $controls ) ?>
            <?php if ( $helper->is_section_opened ) : ?></table><?php endif ?>
            <?php $helper->close_section() ?>
            <hr id="<?php echo esc_attr( $field_id ) ?>" <?php echo apply_filters( 'metaboxer_control_section', [], $field, $model, $helper ) ?>/>
            <?php if ( $helper->is_section_opened ) : ?><table class="form-table"><?php endif ?>
        <?php elseif ( array_key_exists( 'type', $field )
            && $field['type'] === 'callback'
            && array_key_exists( 'callback', $field )
            && is_callable( $field['callback'] )
        ) : ?>
            <?php call_user_func_array( $field['callback'], [$model, $field_id] ) ?>
        <?php else : ?>
            <?php $helper->add_repeater_field( $field_id, $field ) ?>
            <?php if ( !$helper->is_section_opened ) : ?><table class="form-table"><?php endif ?>
            <tr id="tr-<?php echo esc_attr( $field_id ) ?>" <?php echo apply_filters( 'metaboxer_control_tr', [], $field, $model, $helper ) ?>>
                <?php if ( !array_key_exists( 'show_title', $field ) || $field['show_title'] === true ) : ?>
                    <th><?php echo array_key_exists( 'title', $field ) ? $field['title'] : $field_id ?></th>
                <?php endif ?>
                <td class="type-<?php echo esc_attr( array_key_exists( 'type', $field ) ? $field['type'] : 'input' ) ?>">
                    <?php if ( array_key_exists( $field['_control'], $controls ) ) : ?>
                        <?php if ( $helper->is_repeater_opened ) : $field['value'] = ''; endif ?>
                        <?php $controls[$field['_control']]->render( $field ) ?>
                    <?php endif ?>
                    <?php if ( array_key_exists( 'description', $field ) && !empty( $field['description'] ) ) : ?>
                        <br><p class="description"><?php echo $field['description'] ?></p>
                    <?php endif ?>
                </td><?php if ( $helper->is_repeater_opened ) : ?><td role="repeater-actions"></td><?php endif ?>
            </tr>
            <?php if ( !$helper->is_section_opened ) : ?></table><?php endif ?>
        <?php endif ?>
    <?php endforeach ?>
    <?php if ( $helper->is_section_opened ) : ?></table><?php endif ?>
</section>