<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WPXtension_Setting_Fields_Basic' ) ) {
    class WPXtension_Setting_Fields_Basic {

        public static $_plugin = "";

        protected static $_instance = null;

        public static function instance($_plugin) {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self($_plugin);
            }

            return self::$_instance;
        }

        /*
         * Construct of the Class.
         *
         */

        public function __construct($_plugin){

            self::$_plugin = $_plugin;

        }

    

        // Select Field 

        public static function select($options = []){


            ?>
                <tr class="<?php echo esc_attr($options['tr_class']); ?>" valign="top" data-new-tag="<?php echo ( isset( $options['tag'] ) ) ? esc_attr($options['tag']) : ''; ?>">

                    <td class="row-title" scope="row">
                        <?php
                            echo sprintf(
                                '<label>%s</label>',
                                esc_attr($options['label'])
                            );
                        ?>
                    </td>
                    <td>

                        <select class="regular-ele-width<?php echo ( isset( $options['ele_class'] ) ) ? esc_attr($options['ele_class']) : ''; ?>" name='<?php echo esc_attr($options['name']); ?>'>
                            <?php 
                                foreach( $options['option'] as $select_option ){
                            ?>
                            <option value="<?php echo esc_attr($select_option['value']); ?>"
                                <?php echo $options['value'] == $select_option['value'] ? "selected" : ''; ?>><?php echo esc_attr($select_option['name']); ?></option>
                            <?php 
                                } 
                            ?>
                        </select>

                        <?php
                            if( isset($options['note']) && $options['note'] !== '' ):
                        ?>
                            <p style="font-style: italic; color: red;"><?php echo esc_html( $options['note'] ); ?></p>
                        <?php

                            endif;
                        ?>
                    </td>

                </tr>
            <?php
        }

        public static function checkbox($options = []){
            ?>
                <tr class="<?php echo esc_attr($options['tr_class']); ?>" valign="top" data-new-tag="<?php echo ( isset( $options['tag'] ) ) ? esc_attr($options['tag']) : ''; ?>">

                    <td class="row-title" scope="row">

                        <label for="tablecell">
                            <?php
                                echo esc_html($options['label']);
                            ?>
                        </label>
                    </td>
                    <td>
                        <label>
                            <input class="<?php echo ( isset( $options['ele_class'] ) ) ? esc_attr($options['ele_class']) : ''; ?>" type='checkbox' name='<?php echo esc_attr($options['name']); ?>' value='<?php echo esc_attr( $options['default_value'] ); ?>' <?php checked( esc_attr($options['value'] ), esc_attr( $options['default_value'] ), true ); ?> />
                            <?php echo esc_html( $options['checkbox_label'] ); ?>
                        </label>
                        <?php if( isset( $options['note'] ) && $options['note'] !== ''  ): ?>
                            <p style="font-style: italic; color: red;"><?php echo esc_html( $options['note'] ); ?></p>
                        <?php endif; ?>

                        <?php if( isset( $options['note_info'] ) && $options['note_info'] !== ''  ): ?>
                            <p style="font-style: italic; color: #222;"><?php echo esc_html( $options['note_info'] ); ?></p>
                        <?php endif; ?>
                    </td>

                </tr>
            <?php
        }

        public static function color($options = []){
            ?>
                <tr class="<?php echo esc_attr($options['tr_class']); ?>" valign="top" data-new-tag="<?php echo ( isset( $options['tag'] ) ) ? esc_attr($options['tag']) : ''; ?>">

                    <td class="row-title" scope="row">
                        <label for="tablecell">
                            <?php
                                echo esc_html($options['label']);
                            ?>
                        </label>
                    </td>
                    <td>
                        <label>
                            <input class="color-field<?php echo ( isset( $options['ele_class'] ) ) ? esc_attr($options['ele_class']) : ''; ?>" type='text' name='<?php echo esc_attr($options['name']); ?>' value='<?php echo esc_attr( $options['value'] ); ?>'/>
                        </label>
                        <p style="font-style: italic; color: red;"><?php echo esc_html( $options['note'] ); ?></p>
                    </td>

                </tr>
            <?php
        }

        public static function number($options = []){
            ?>
                <tr class="<?php echo esc_attr($options['tr_class']); ?>" valign="top" data-new-tag="<?php echo ( isset( $options['tag'] ) ) ? esc_attr($options['tag']) : ''; ?>">

                    <td class="row-title" scope="row">
                        <?php
                            echo esc_html($options['label']);
                        ?>
                    </td>
                    <td>
                        <label class="wpx-number-group">
                            <input class="wpx-number<?php echo ( isset( $options['ele_class'] ) ) ? esc_attr($options['ele_class']) : ''; ?>" type='number' min="0" name='<?php echo esc_attr($options['name']); ?>' value='<?php echo esc_attr( $options['value'] ); ?>'/>
                            <span>PX</span>
                        </label>
                        <p style="font-style: italic; color: red;"><?php echo esc_html( $options['note'] ); ?></p>
                    </td>

                </tr>
            <?php
        }


        public static function text($options = []){
            ?>
                <tr class="<?php echo esc_attr($options['tr_class']); ?>" valign="top" data-new-tag="<?php echo ( isset( $options['tag'] ) ) ? esc_attr($options['tag']) : ''; ?>">

                    <td class="row-title" scope="row">
                        <label for="tablecell">
                            <?php
                                echo esc_html($options['label']);
                            ?>
                        </label>
                    </td>
                    <td>
                        <label>
                            <input class='regular-text<?php echo ( isset( $options['ele_class'] ) ) ? esc_attr($options['ele_class']) : ''; ?>' type='text' name='<?php echo esc_attr($options['name']); ?>' value='<?php echo esc_attr( $options['value'] ); ?>' placeholder='<?php echo esc_attr($options['placeholder']); ?>' />
                        </label>

                        <?php if( isset( $options['note'] ) && $options['note'] !== ''  ): ?>
                            <p style="font-style: italic; color: red;"><?php echo esc_html( $options['note'] ); ?></p>
                        <?php endif; ?>

                        <?php if( isset( $options['note_info'] ) && $options['note_info'] !== ''  ): ?>
                            <p style="font-style: italic; color: #222;"><?php echo esc_html( $options['note_info'] ); ?></p>
                        <?php endif; ?>
                    </td>

                </tr>
            <?php
        }


        // Select Field 

        public static function multiselect($options = []){


            ?>
                <tr class="<?php echo esc_attr($options['tr_class']); ?>" valign="top" data-new-tag="<?php echo ( isset( $options['tag'] ) ) ? esc_attr($options['tag']) : ''; ?>">

                    <td class="row-title" scope="row">
                        <?php
                            echo sprintf(
                                '<label>%s</label>',
                                esc_attr($options['label'])
                            );
                        ?>
                    </td>
                    <td>

                        <select class="regular-ele-width<?php echo ( isset( $options['ele_class'] ) ) ? esc_attr($options['ele_class']) : ''; ?>" name="<?php echo esc_attr($options['name']); ?>" multiple="multiple">
                            <?php 
                                foreach( $options['option'] as $select_option ){
                            ?>
                            <option value="<?php echo esc_attr($select_option['value']); ?>"
                                <?php echo ( in_array( $select_option['value'], $options['value'] ) ) ? "selected" : ''; ?>><?php echo esc_attr($select_option['name']); ?></option>
                            <?php 
                                } 
                            ?>
                        </select>

                        <?php
                            if( isset($options['note']) && $options['note'] !== '' ):
                        ?>
                            <p style="font-style: italic; color: red;"><?php echo esc_html( $options['note'] ); ?></p>
                        <?php

                            endif;
                        ?>
                    </td>

                </tr>
            <?php
        }

    }

}
