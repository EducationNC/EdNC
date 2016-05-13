<?php
/**
 * WordPress Settings Framework
 *
 * @author Gilbert Pellegrom
 * @link https://github.com/gilbitron/WordPress-Settings-Framework
 * @license MIT
 */

/**
 * Define the settings
 */
add_filter( 'qse_register_settings', 'qse_settings' );
function qse_settings( $qse_settings ) {

    $qse_settings[] = array(
        'section_id' => 'default',
        'section_title' => 'Default Settings',
        'section_description' => 'You can change the default settings for the Qualtrics survey embed below.',
        'section_order' => 5,
        'fields' => array(
            array(
                'id' => 'pxorpercent',
                'title' => 'Pixel or Percent',
                'type' => 'select',
                'std' => '%',
                'link' => 'true',
                'choices' => array(
                    '%' => '%',
                    'px' => 'px'
                )
            ),            
            array(
                'id' => 'width',
                'title' => 'Width',
                'desc' => 'The default width of the embed.',
                'type' => 'number',
                'std' => '100'
            ),
            array(
                'id' => 'height',
                'title' => 'Height',
                'after' => ' px',
                'desc' => 'The default height of the embed.',
                'type' => 'number',
                'std' => '800'                
            )
        )
    );
    return $qse_settings;
}
