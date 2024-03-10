<?php

namespace com\cminds\videolesson\controller;

use com\cminds\videolesson\App;
use com\cminds\videolesson\model\Settings;

class AddSettingsTabsController extends Controller {

    protected static $actions = array();
    protected static $filters = array(
        'cmvl_options_config'        => array( 'args' => 1, 'priority' => 10 ),
        'cmvl_settings_pages'        => array( 'args' => 1, 'priority' => 10 ),
        'cmvl_settings_pages_groups' => array( 'args' => 1, 'priority' => 10 ),
        array( 'name' => 'cmvl-settings-category', 'args' => 2, 'priority' => 10, 'method' => 'renderUpgrade' ),
        array( 'name' => 'cmvl-settings-category', 'args' => 2, 'priority' => 10, 'method' => 'renderInstallationGuide' ),
    );
    protected static $ajax    = array();

    static function cmvl_settings_pages( $categories ) {
        $prepend = array(
            'upgrade' => 'Upgrade'
        );
        $append  = array(
            'guide' => 'Installation Tutorial'
        );

        $config = array_merge( $prepend, $categories, $append );

        return $config;
    }

    static function cmvl_settings_pages_groups( $categories ) {
        $prepend = array(
            'upgrade' => array(
                'general' => ''
            ),
        );
        $append  = array(
            'guide' => array(
                'general' => ''
            ),
        );

        $config = array_merge( $prepend, $categories, $append );

        return $config;
    }

    static function cmvl_options_config( $old_config ) {
        $new_config = array(
            App::prefix( '_package_upgrade' ) => array(
                'type'        => Settings::TYPE_CUSTOM,
                'content'     => '',
                'category'    => 'upgrade',
                'subcategory' => 'general',
                'title'       => '',
                'desc'        => '',
            ),
            App::prefix( '_package_guide' ) => array(
                'type'        => Settings::TYPE_CUSTOM,
                'content'     => '',
                'category'    => 'guide',
                'subcategory' => 'general',
                'title'       => '',
                'desc'        => '',
            )
        );

        $config = array_merge( $new_config, $old_config );

        return $config;
    }

    static function renderUpgrade( $result, $category ) {
        if ( $category == 'upgrade' ) {
            $result = do_shortcode( '[cminds_upgrade_box id="'.App::prefix( '' ).'"]' );
        }
        return $result;
    }

    static function renderInstallationGuide( $result, $category ) {
        if ( $category == 'guide' ) {
            $result = do_shortcode( '[cminds_free_guide id="'.App::prefix( '' ).'"]' );
        }
        return $result;
    }

}
