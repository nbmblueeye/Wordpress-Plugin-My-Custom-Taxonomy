<?php

add_action( 'rest_api_init', 'my_custom_taxonomy_add_rest_route');

function my_custom_taxonomy_add_rest_route(){

    register_rest_route( 'add_my_custom_taxonomy/v1', '/add', array(
        'methods'       => WP_REST_Server::CREATABLE,
        'callback'      => 'add_my_custom_taxonomy_data',
        'permission_callback' => '__return_true',
    ));

    register_rest_route( 'add_my_custom_taxonomy/v1', '/update', array(
        'methods'       => WP_REST_Server::EDITABLE,
        'callback'      => 'edit_my_custom_taxonomy_data',
        'permission_callback' => '__return_true',
    ));


    register_rest_route( 'add_my_custom_taxonomy/v1', '/delete', array(
        'methods'       => WP_REST_Server::DELETABLE,
        'callback'      => 'delete_existing_my_custom_taxonomy',
        'permission_callback' => '__return_true',
    ));

}

/* < ========== Add My Custom Post Type Data ================> */

function add_my_custom_taxonomy_data($request){

    global $wpdb;

    $headers = $request->get_headers();
    $nonce   = sanitize_key($headers['x_wp_nonce'][0]);

    if(!$nonce && !wp_verify_nonce($nonce, 'wp_rest')){
        return new WP_REST_Response( "There're something Wrong!" );
    }

    $params = $request->get_params();
    
    if(is_array($params) && !empty($params)){
      
            $post_type_slug     =   sanitize_text_field($params['mct_post_type_slug']);
            $taxonomy_name      =   sanitize_text_field($params['mct_taxonomy_name']);
            $taxonomy_slug      =   sanitize_text_field($params['mct_taxonomy_slug']);
            $hierarchical       =   sanitize_key($params['mct_hierarchical']);
            
            $taxonomy = new My_Custom_Taxonomy();
            $table_name = $taxonomy->reset_my_custom_taxonomy_table_name();

            $wpdb->query( 
                $wpdb->prepare( 
                    "INSERT INTO $table_name (`post_type_slug`, `taxonomy_name`, `taxonomy_slug`, `hierarchical` ) VALUES (%s, %s, %s, %s)",  $post_type_slug,  $taxonomy_name, $taxonomy_slug, $hierarchical 
                )
            );

    }

    return new WP_REST_Response( "New Taxonomy was added to ".ucfirst($post_type_slug)." successfully!");

}

function edit_my_custom_taxonomy_data($request){

    global $wpdb;

    $headers = $request->get_headers();
    $nonce   = sanitize_key($headers['x_wp_nonce'][0]);

    if(!$nonce && !wp_verify_nonce($nonce, 'wp_rest')){
        return new WP_REST_Response( "There're something Wrong!" );
    }

    $params = $request->get_params();
   
    if(is_array($params) && !empty($params)){

        $post_type_slug =   sanitize_text_field($params['mct_post_type_slug']);
        $taxonomy_name  =   sanitize_text_field($params['mct_taxonomy_name']);
        $taxonomy_slug  =   sanitize_text_field($params['mct_taxonomy_slug']);
        $hierarchical   =   sanitize_key($params['mct_hierarchical']);
        $id             =   absint($params['mct_taxonomy_id']);

    }

    $taxonomy = new My_Custom_Taxonomy();
    $table_name = $taxonomy->reset_my_custom_taxonomy_table_name();
    
    $wpdb->query( 
        $wpdb->prepare( 
            "UPDATE $table_name SET `post_type_slug`=%s, `taxonomy_name`=%s, `taxonomy_slug`=%s, `hierarchical`=%s WHERE id=%d",  $post_type_slug, $taxonomy_name, $taxonomy_slug , $hierarchical ,$id
        )
    );

    return new WP_REST_Response( "Update Taxonomy ".$taxonomy_name." successfully!" );

}


function delete_existing_my_custom_taxonomy($request){
    global $wpdb;

    $headers = $request->get_headers();
    $nonce   = sanitize_key($headers['x_wp_nonce'][0]);

    if(!$nonce && !wp_verify_nonce($nonce, 'wp_rest')){
        return new WP_REST_Response( "There're something Wrong!" );
    }

    $param = $request->get_params();
   
    if(is_array($param) && !empty($param)){
        $id = absint($param['id']);
    }

    $taxonomy = new My_Custom_Taxonomy();
    $table_name = $taxonomy->reset_my_custom_taxonomy_table_name();

    $wpdb->query( $wpdb->prepare( "DELETE FROM `$table_name` WHERE `id` = %d", $id ) );

    return new WP_REST_Response( "Selected Taxonomy was deleted successfully!" );

}

