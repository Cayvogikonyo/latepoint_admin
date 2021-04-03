<?php

//Add the following lines to the end of your functions.php theme file

/**
 * The function utilises admin_init do-actions. 
 * Visit https://developer.wordpress.org/reference/hooks/admin_init/ for reference on the hook 
 */

add_action('init', 'restrict_admin_with_redirect');  //add our custom function

/**
 * Our custom function redirect action
 */
function restrict_admin_with_redirect() { 
    $user = wp_get_current_user();
    $admin_array = [ //Whitelisted admins can access the dashboard
        'admin1@example.com',
        'admin2@example.com' 
    ];


    if ( current_user_can('administrator') && isset($user->user_email) && ! in_array($user->user_email, $admin_array) && ( ! wp_doing_ajax() ) ) {
        $url = OsRouterHelper::build_link(['dashboard', 'index']); //Create url from latepoint builders. Requires latepoint plugin activated.

        $page = get_request_parameter('page'); //Check if we have already redirected, if so, no redirections

        $logOut = get_request_parameter('action'); //Check if the request is for logout action. If logout, dont redirect

        if($page !== 'latepoint' && $logOut !== 'logout'){
            wp_redirect( $url ); 
            exit;
        }

    }
}


/**
 * Gets the request parameter.
 *
 * @param      string  $key      The query parameter
 * @param      string  $default  The default value to return if not found
 *
 * @return     string  The request parameter.
 * credit: https://www.intechgrity.com/correct-way-get-url-parameter-values-wordpress/
 */
function get_request_parameter( $key, $default = '' ) {
    // If not request set
    if ( ! isset( $_REQUEST[ $key ] ) || empty( $_REQUEST[ $key ] ) ) {
        return $default;
    }
 
    // Set so process it
    return strip_tags( (string) wp_unslash( $_REQUEST[ $key ] ) );
}

