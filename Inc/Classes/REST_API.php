<?php

namespace WPSPOTLIGHT\Inc\Classes;

use WPSPOTLIGHT\Inc\Classes\Api\Users;

// No, Direct access Sir !!!
if (! defined('ABSPATH')) {
    exit;
}

/**
 * REST_API
 *
 * @author Jewel Theme <support@jeweltheme.com>
 */
class REST_API {

    private static $_instance = null;

    public function __construct()
    {
        $this->include_apis();
    }

    public function include_apis(){
        Users::get_instance();
    }

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
