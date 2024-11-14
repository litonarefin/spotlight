<?php
namespace WPSPOTLIGHT\Inc\Classes\Api;


/**
 * Users
 *
 * @author Jewel Theme <support@jeweltheme.com>
 */

class Users {
    private static $instance = null;

    private function __construct() {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes() {
        register_rest_route('wpspotlight/v1', '/user-manager', [
            'methods' => 'POST',
            'callback' => [$this, 'handle_user_action'],
            // 'permission_callback' => [$this, 'check_permissions'],
            'permission_callback' => '__return_true',
            'args' => [
                'user_id' => [
                    'required' => false,
                    'validate_callback' => function ($param) {
                        return is_numeric($param);
                    },
                ],
                'action' => [
                    'required' => true,
                    'validate_callback' => function ($param) {
                        return in_array($param, ['info', 'create', 'update', 'delete']);
                    },
                ],
                'user_data' => [
                    'required' => false,
                    'validate_callback' => function ($param) {
                        return is_array($param);
                    },
                ],
            ],
        ]);

        register_rest_route('wpspotlight/v1', '/get-user-roles', [
            'methods' => 'GET',
            'callback' => [$this, 'get_user_roles'],
            // 'permission_callback' => [$this, 'check_permissions'],
            'permission_callback' => '__return_true',
            'args' => [],
        ]);

        register_rest_route('wpspotlight/v1', '/get-role-by-users', [
            'methods' => 'GET',
            'callback' => [$this, 'get_role_by_users'],
            // 'permission_callback' => [$this, 'check_permissions'],
            'permission_callback' => '__return_true',
            'args' => [
                'role' => [
                    'required' => true,
                    'validate_callback' => function ($param) {
                        return true;
                    },
                ],
            ],
        ]);

        register_rest_route('wpspotlight/v1', '/get-users-by-email', [
            'methods' => 'GET',
            'callback' => [$this, 'get_users_by_email'],
            // 'permission_callback' => [$this, 'check_permissions'],
            'permission_callback' => '__return_true',
            'args' => [
                'role' => [
                    'required' => false,
                    'validate_callback' => function ($param) {
                        return true;
                    },
                ],
                'name' => [
                    'required' => false,
                    'validate_callback' => function ($param) {
                        return true;
                    },
                ],
                'email' => [
                    'required' => false,
                    'validate_callback' => function ($param) {
                        return true;
                    },
                ],
            ],
        ]);
    }

    public function check_permissions() {
        return current_user_can('manage_options');
    }

    public function handle_user_action($request) {
        $action = sanitize_text_field($request->get_param('action'));
        $user_id = $request->get_param('user_id');
        $user_data = $request->get_param('user_data');

        switch ($action) {
            case 'info':
                return $this->get_user_info($user_id);
            case 'create':
                return $this->create_user($user_data);
            case 'update':
                return $this->update_user($user_id, $user_data);
            case 'delete':
                return $this->delete_user($user_id);
            default:
                return new \WP_Error('invalid_action', 'Invalid action specified', ['status' => 400]);
        }
    }

    public function get_user_roles($request) {
        global $wp_roles;

        if ( ! isset( $wp_roles ) ) $wp_roles = new \WP_Roles();

        return $wp_roles->get_names();
    }

    public function get_role_by_users($request) {
        $role = sanitize_text_field($request->get_param('role'));

        $args = array(
            'role'    => $role,
            'orderby' => 'user_nicename',
            'order'   => 'ASC'
        );

        $users = new \WP_User_Query( $args );

        $result = [];

        foreach ($users->results as $key => $value) {
            $result[$key]['email'] = $value->data->user_email;
            $result[$key]['name'] = $value->data->display_name;
            $result[$key]['edit_url'] = add_query_arg( 'user_id', $value->data->ID, self_admin_url( 'user-edit.php' ) );
        }

        return $result;
    }

    public function get_users_by_email($request) {
        $name = sanitize_text_field($request->get_param('name'));
        $role = sanitize_text_field($request->get_param('role'));
        $email = $request->get_param('email');

        $args = [
            'search' => '*',
            'search_columns' => []
        ];


        if ($name) {
            $args['search'] = '*' . esc_attr($name) . '*';
            $args['search_columns'][] = 'display_name';
            $args['search_columns'][] = 'user_login';
            if( !empty($role) ) {
                $args['role'] = array( $role );
            }
        } elseif ($email) {
            $args['search'] = '*' . esc_attr($email) . '*';
            $args['search_columns'][] = 'user_email';
            if( !empty($role) ) {
                $args['role'] = array( $role );
            }
        }
        $user_query = new \WP_User_Query($args);
        $users = $user_query->get_results();


        $data = [];
        foreach ($users as $user) {
            $data[] = [
                'ID' => $user->ID,
                'name' => $user->display_name,
                'email' => $user->user_email,
                'username' => $user->user_login,
                'roles' => $user->roles,
                'edit_url' => add_query_arg( 'user_id', $user->ID, self_admin_url( 'user-edit.php' ) )
            ];
        }

        return $data;
    }

    private function get_user_info($user_id) {
        $user = get_user_by('ID', $user_id);
        if (!$user) {
            return new \WP_Error('not_found', 'User not found', ['status' => 404]);
        }

        return [
            'ID' => $user->ID,
            'username' => $user->user_login,
            'email' => $user->user_email,
            'roles' => $user->roles,
            'display_name' => $user->display_name,
        ];
    }

    private function create_user($user_data) {
        if (empty($user_data['username']) || empty($user_data['email']) || empty($user_data['password'])) {
            return new \WP_Error('missing_data', 'Username, email, and password are required', ['status' => 400]);
        }

        $user_id = wp_insert_user([
            'user_login' => sanitize_text_field($user_data['username']),
            'user_email' => sanitize_email($user_data['email']),
            'user_pass' => sanitize_text_field($user_data['password']),
            'display_name' => sanitize_text_field($user_data['display_name'] ?? $user_data['username']),
            'role' => sanitize_text_field($user_data['role'] ?? 'subscriber'),
        ]);

        if (is_wp_error($user_id)) {
            return new \WP_Error('creation_failed', 'Failed to create user', ['status' => 500]);
        }

        return ['message' => 'User created successfully', 'user_id' => $user_id];
    }

    private function update_user($user_id, $user_data) {
        if (!$user_id || empty($user_data)) {
            return new \WP_Error('missing_data', 'User ID and data are required for update', ['status' => 400]);
        }

        $update_data = ['ID' => $user_id];
        if (isset($user_data['email'])) {
            $update_data['user_email'] = sanitize_email($user_data['email']);
        }
        if (isset($user_data['display_name'])) {
            $update_data['display_name'] = sanitize_text_field($user_data['display_name']);
        }
        if (isset($user_data['role'])) {
            $update_data['role'] = sanitize_text_field($user_data['role']);
        }
        if (isset($user_data['password'])) {
            $update_data['user_pass'] = sanitize_text_field($user_data['password']);
        }

        $result = wp_update_user($update_data);
        if (is_wp_error($result)) {
            return new \WP_Error('update_failed', 'Failed to update user', ['status' => 500]);
        }

        return ['message' => 'User updated successfully'];
    }

    private function delete_user($user_id) {
        if (!$user_id) {
            return new \WP_Error('missing_user_id', 'User ID is required for deletion', ['status' => 400]);
        }

        $result = wp_delete_user($user_id);
        if (!$result) {
            return new \WP_Error('deletion_failed', 'Failed to delete user', ['status' => 500]);
        }

        return ['message' => 'User deleted successfully'];
    }


    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
