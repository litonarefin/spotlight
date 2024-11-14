<?php

namespace WPSPOTLIGHT\Inc;

// no direct access allowed
if (!defined('ABSPATH')) {
    exit;
}

// no direct access allowed
if (!defined('ABSPATH')) {
	exit;
}

class Utils
{

    function is_backend()
    {
        return is_admin();
    }

    function is_frontend()
    {
        return !is_admin();
    }

    /**
     * Check Site wide plugin settings
     */
    public static function is_site_wide($plugin)
    {
        if (!is_multisite()) {
            return false;
        }

        $plugins = get_site_option('active_sitewide_plugins');
        if (isset($plugins[$plugin])) {
            return true;
        }

        return false;
    }


    // Get Post Types
    public static function get_post_types()
    {
        $args       = [
            'public'  => true,
            'show_ui' => true,
        ];
        $output     = 'objects';
        $post_types = get_post_types($args, $output);
        $post_types = $post_types;
        return $post_types;
    }

    // Get Post Meta
    public static function post_meta($meta_key)
    {
        $meta_key = get_post_meta(get_the_ID(), $meta_key, true);
        return $meta_key;
    }

    // verfiy current page id
    public static function get_currentpage_id($id)
    {
        if (!function_exists('get_current_screen')) {
            return true;
        }

        $screen = get_current_screen();
        return is_object($screen) && $screen->id == $id;
    }

    // Get Taxonomies
    public static function get_taxonomies()
    {
        $args       = [
            'public'  => true,
            'show_ui' => true,
        ];
        $output     = 'objects';
        $taxonomies = get_taxonomies($args, $output);
        $taxonomies = $taxonomies;
        return $taxonomies;
    }

    /**
     * Get All Taxonomies
     *
     * @return void
     */
    public static function get_all_taxonomies()
    {
        $taxonomies     = get_taxonomies(
            [
                'show_ui' => true,
            ],
            'objects'
        );
        $taxonomy_names = [];
        foreach ($taxonomies as $taxonomy) {
            if ($taxonomy->name == 'post_format') {
                continue;
            }
            $taxonomy_names[$taxonomy->name] = $taxonomy->label;
        }
        return $taxonomy_names;
    }


    /**
     * Restricts for role / user
     */

    public static function restrict_for($disabled_for)
    {
        if (!is_array($disabled_for)) {
            return false;
        }

        require_once ABSPATH . '/wp-includes/pluggable.php';

        if (!function_exists('wp_get_current_user')) {
            return false;
        }
        $current_user  = wp_get_current_user();
        $current_name  = $current_user->display_name;
        $current_roles = $current_user->roles;

        $formattedroles = [];

        foreach ($disabled_for as $item) {
            $item = strtolower($item);
            $item = str_replace(' ', '_', $item);
            array_push($formattedroles, $item);
        }

        if (in_array($current_name, $disabled_for)) {
            return true;
        }

        foreach ($current_roles as $role) {
            if (in_array($role, $formattedroles)) {
                return true;
            }
        }
    }

    public static function class_cleanup($string)
    {
        // Lower case everything
        $string = strtolower($string);
        // Make alphanumeric (removes all other characters)
        $string = preg_replace('/[^a-z0-9_\s-]/', '', $string);
        // Clean up multiple dashes or whitespaces
        $string = preg_replace('/[\s-]+/', ' ', $string);
        // Convert whitespaces and underscore to dash
        $string = preg_replace('/[\s_]/', '-', $string);
        return $string;
    }
    public static function sanitize_id($url)
    {
        $url = preg_replace('/^customize.php\?return=.*$/', 'customize', $url);
        $url = preg_replace('/(&|&amp;|&#038;)?_wpnonce=([^&]+)/', '', $url);
        return str_replace(['.php', '.', '/', '?', '='], ['', '_', '_', '_', '_'], $url);
    }


    /**
     * String to ID
     * Remove Space replace with underscore and lowercase
     *
     * @param void
     *
     * @return string
     */
    public static function string_to_id($string)
    {
        $string_replace   = str_replace(' ', '_', $string);
        $formatted_string = strtolower($string_replace);
        return $formatted_string;
    }


    /**
     * ID to String
     * Remove Space replace with underscore and lowercase
     *
     * @param void
     *
     * @return string
     */
    public static function id_to_string($string)
    {
        $string_replace   = str_replace('_', ' ', $string);
        $formatted_string = ucwords($string_replace);
        return $formatted_string;
    }


    /**
     * Check is Plugin Active
     *
     * @param [type] $plugin_path
     *
     * @return boolean
     */
    public static function is_plugin_active($plugin_path)
    {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
        return is_plugin_active($plugin_path);
    }

    /**
     * Check if Block Editor Page
     *
     * @return boolean
     */
    public static function is_block_editor_page()
    {
        if (function_exists('get_current_screen')) {
            $current_screen = get_current_screen();
            if (!empty($current_screen->is_block_editor)) {
                return true;
            }
        }
        return false;
    }


    /**
     * Check is Plugin Active
     *
     * @param [type] $plugin_path
     *
     * @return boolean
     */
    public static function is_plugin_installed_and_active($plugin_path)
    {
        // Check if the plugin is active
        if (is_plugin_active($plugin_path)) {
            return true; // Plugin is both installed and active
        } else {
            return false; // Plugin is either not installed or not active
        }
    }

    public static function is_plugin_installed($plugin_slug, $plugin_file)
    {
        $installed_plugins = get_plugins();
        return isset($installed_plugins[$plugin_file]);
    }


    public static function assets_ext($ext)
    {
        if (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) {
            return $ext;
        }
        return '.min' . $ext;
    }


    public static function wp_kses_atts_map(array $attrs)
    {
        return array_fill_keys(array_values($attrs), true);
    }


    public static function wp_kses_custom($content)
    {
        $allowed_tags = wp_kses_allowed_html('post');

        $custom_tags = [
            'select'         => self::wp_kses_atts_map(['class', 'id', 'style', 'width', 'height', 'title', 'data', 'name', 'autofocus', 'disabled', 'multiple', 'required', 'size']),
            'input'          => self::wp_kses_atts_map(['class', 'id', 'style', 'width', 'height', 'title', 'data', 'name', 'autofocus', 'disabled', 'required', 'size', 'type', 'checked', 'readonly', 'placeholder', 'value', 'maxlength', 'min', 'max', 'multiple', 'pattern', 'step', 'autocomplete']),
            'textarea'       => self::wp_kses_atts_map(['class', 'id', 'style', 'width', 'height', 'title', 'data', 'name', 'autofocus', 'disabled', 'required', 'rows', 'cols', 'wrap', 'maxlength']),
            'option'         => self::wp_kses_atts_map(['class', 'id', 'label', 'disabled', 'label', 'selected', 'value']),
            'optgroup'       => self::wp_kses_atts_map(['disabled', 'label', 'class', 'id']),
            'form'           => self::wp_kses_atts_map(['class', 'id', 'data', 'style', 'width', 'height', 'accept-charset', 'action', 'autocomplete', 'enctype', 'method', 'name', 'novalidate', 'rel', 'target']),
            'svg'            => self::wp_kses_atts_map(['class', 'xmlns', 'viewbox', 'width', 'height', 'fill', 'aria-hidden', 'aria-labelledby', 'role']),
            'rect'           => self::wp_kses_atts_map(['rx', 'width', 'height', 'fill']),
            'path'           => self::wp_kses_atts_map(['d', 'fill']),
            'g'              => self::wp_kses_atts_map(['fill']),
            'defs'           => self::wp_kses_atts_map(['fill']),
            'linearGradient' => self::wp_kses_atts_map(['id', 'x1', 'x2', 'y1', 'y2', 'gradientUnits']),
            'stop'           => self::wp_kses_atts_map(['stop-color', 'offset', 'stop-opacity']),
            'style'          => self::wp_kses_atts_map(['type']),
            'div'            => self::wp_kses_atts_map(['class', 'id', 'style']),
            'ul'             => self::wp_kses_atts_map(['class', 'id', 'style']),
            'li'             => self::wp_kses_atts_map(['class', 'id', 'style']),
            'label'          => self::wp_kses_atts_map(['class', 'for']),
            'span'           => self::wp_kses_atts_map(['class', 'id', 'style']),
            'h1'             => self::wp_kses_atts_map(['class', 'id', 'style']),
            'h2'             => self::wp_kses_atts_map(['class', 'id', 'style']),
            'h3'             => self::wp_kses_atts_map(['class', 'id', 'style']),
            'h4'             => self::wp_kses_atts_map(['class', 'id', 'style']),
            'h5'             => self::wp_kses_atts_map(['class', 'id', 'style']),
            'h6'             => self::wp_kses_atts_map(['class', 'id', 'style']),
            'a'              => self::wp_kses_atts_map(['class', 'href', 'target', 'rel']),
            'p'              => self::wp_kses_atts_map(['class', 'id', 'style', 'data']),
            'table'          => self::wp_kses_atts_map(['class', 'id', 'style']),
            'thead'          => self::wp_kses_atts_map(['class', 'id', 'style']),
            'tbody'          => self::wp_kses_atts_map(['class', 'id', 'style']),
            'tr'             => self::wp_kses_atts_map(['class', 'id', 'style']),
            'th'             => self::wp_kses_atts_map(['class', 'id', 'style']),
            'td'             => self::wp_kses_atts_map(['class', 'id', 'style']),
            'i'              => self::wp_kses_atts_map(['class', 'id', 'style']),
            'button'         => self::wp_kses_atts_map(['class', 'id']),
            'nav'            => self::wp_kses_atts_map(['class', 'id', 'style']),
            'time'           => self::wp_kses_atts_map(['datetime']),
            'br'             => [],
            'strong'         => [],
            'style'          => [],
            'img'            => self::wp_kses_atts_map(['class', 'src', 'alt', 'height', 'width', 'srcset', 'id', 'loading']),
        ];

        $allowed_tags = array_merge_recursive($allowed_tags, $custom_tags);

        return wp_kses(stripslashes_deep($content), $allowed_tags);
    }

    /*
	* Compares the version of WordPress running to the $version specified.
	* Usage: Utils::check_wp_version('>=', '4.0')
	version_compare( $wp_version, '4.3', '>=' )
	* @param string $operator
	* @param string $version
	* @returns boolean
	*/
    public static function check_wp_version($operator = '>', $version = '6.6')
    {
        global $wp_version;
        return version_compare($wp_version, $version, $operator);
    }


}
