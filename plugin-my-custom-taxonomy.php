<?php
/**
 * Plugin Name:       My Custom Taxonomy
 * Description:       This Plugin will will be used to add a Custom Taxonomy to existing Post Type
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      5.6
 * Author:            nbm-blue-eye
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       my-custom-taxonomy
 */


if(!defined('ABSPATH')){
    die("You should not to be here!");
}


if(!class_exists("My_Custom_Taxonomy")){

    class My_Custom_Taxonomy
    {
        public function __construct()
        {
            if(!defined("MY_CUSTOM_TAXONOMY_PLUGIN_DIR_PATH")){
                define("MY_CUSTOM_TAXONOMY_PLUGIN_DIR_PATH", plugin_dir_path(__FILE__));
            };
            
            if(!defined("MY_CUSTOM_TAXONOMY_PLUGIN_URL")){
                define("MY_CUSTOM_TAXONOMY_PLUGIN_URL", plugins_url()."/plugin-my-custom-taxonomy/");
            };

            add_action('init', [$this, 'my_custom_taxonomy_include_assets']);

            add_action('admin_menu', [$this, 'set_up_my_custom_taxonomy']);

            register_activation_hook( __FILE__, [$this, 'activate_my_custom_taxonomy_table'] );
            register_deactivation_hook( __FILE__, [$this, 'deactivate_my_custom_taxonomy_table'] );

            call_user_func_array([$this, 'my_custom_taxonomy_initial_custom_func'], ['']);

            add_action('init', [$this, 'add_my_custom_taxonomy']);

        }

        public function my_custom_taxonomy_include_assets(){

            $pages = ["my_custom_taxonomy","my_custom_taxonomy_index"];

            $current_page = isset($_GET['page'])? sanitize_text_field($_GET['page']) :"";

            if(in_array($current_page, $pages)){

                wp_enqueue_style( "my_custom_taxonomy_bootstrap_css", MY_CUSTOM_TAXONOMY_PLUGIN_URL.'assets/css/bootstrap.min.css', array(), '1.0.0', 'all' );

                wp_enqueue_style( "my_custom_taxonomy_fontawsome_css", MY_CUSTOM_TAXONOMY_PLUGIN_URL.'assets/css/all.min.css', array(), '1.0.0', 'all' );

                wp_enqueue_style( "my_custom_taxonomy_main_css", MY_CUSTOM_TAXONOMY_PLUGIN_URL.'assets/css/my_custom_taxonomy.css', array(), '1.0.0', 'all' );

                wp_enqueue_script( "my_custom_taxonomy_bootstrap_js", MY_CUSTOM_TAXONOMY_PLUGIN_URL.'assets/js/bootstrap.min.js', array('jquery'), '1.0.0', true );
            
                wp_enqueue_script( "my_custom_taxonomy_fontawsome_js", MY_CUSTOM_TAXONOMY_PLUGIN_URL.'assets/js/all.min.js', array('jquery'), '1.0.0', true );

                wp_enqueue_script( "my_custom_taxonomy_main_js", MY_CUSTOM_TAXONOMY_PLUGIN_URL.'assets/js/my_custom_taxonomy.js', array('jquery'), '1.0.0', true );
        
                wp_localize_script( 'my_custom_taxonomy_main_js', 'mct_rest_object',
                    array( 
                        'rest_url' => esc_url_raw(rest_url()),
                        'rest_nonce' => wp_create_nonce('wp_rest'),
                    )
                );
                
            }

        }

        public function set_up_my_custom_taxonomy(){

            add_menu_page(
                esc_html__( 'My Custom Taxonomy', 'my-custom-taxonomy' ),
                esc_html__( 'My Custom Taxonomy', 'my-custom-taxonomy' ),
                'manage_options',
                'my_custom_taxonomy',
                [$this, 'my_custom_taxonomy'],
                'dashicons-welcome-learn-more',
                110
            );

            add_submenu_page(
                'my_custom_taxonomy',
                esc_html__( 'MCT_All', 'my-custom-taxonomy' ),
                esc_html__( 'MCT_All', 'my-custom-taxonomy' ),
                'manage_options',
                'my_custom_taxonomy_index',
                [$this, 'my_custom_taxonomy_index'] 
            );

        }
    
        public function my_custom_taxonomy(){
            include_once MY_CUSTOM_TAXONOMY_PLUGIN_DIR_PATH.'views/my-custom-taxonomy-create-new.php';   
        }

        public function my_custom_taxonomy_index(){
            include_once MY_CUSTOM_TAXONOMY_PLUGIN_DIR_PATH.'views/my-custom-taxonomy-index.php';
            
        }

        public function reset_my_custom_taxonomy_table_name(){
            global $wpdb;
            return $wpdb->prefix."my_custom_taxonomy"; // wp_my_custom_taxonomy
        }

        /* <======== activate Database Table =============> */   

        public function activate_my_custom_taxonomy_table(){

            global $wpdb;
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); 
            if($wpdb->get_var('SHOW TABLES LIKE "'.$this->reset_my_custom_taxonomy_table_name().'"') == ""){

                $sql = "CREATE TABLE `".$this->reset_my_custom_taxonomy_table_name()."` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `post_type_slug` text DEFAULT NULL,
                    `taxonomy_name` text DEFAULT NULL,
                    `taxonomy_slug` text DEFAULT NULL,
                    `hierarchical` text DEFAULT NULL,
                    PRIMARY KEY (`id`)
                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

                dbDelta( $sql );

            }

        }

    /* <======== Deactivate Database Table =============> */      

        public function deactivate_my_custom_taxonomy_table(){
            global $wpdb;
            $wpdb->query("DROP TABLE IF EXISTS ".$this->reset_my_custom_taxonomy_table_name());

        }

    /* <======== Add REST ROUTE Function =============> */

        public function my_custom_taxonomy_initial_custom_func($request){

            include_once MY_CUSTOM_TAXONOMY_PLUGIN_DIR_PATH.'library/my-custom-taxonomy-rest-route.php';
        
        }


        public function add_my_custom_taxonomy(){

            global $wpdb;

            $posts = $wpdb->get_results(
               "SELECT * FROM {$this->reset_my_custom_taxonomy_table_name()} ORDER BY `id` desc", 
            );

            if(is_array($posts) && !empty($posts)){

                foreach($posts as $key=>$post){

                        $post_type = !empty($post->post_type_slug) ? $post->post_type_slug:"" ;
                        $taxonomy_name = !empty($post->taxonomy_name) ? $post->taxonomy_name:"" ;
                        $taxonomy_slug = !empty($post->taxonomy_slug) ? $post->taxonomy_slug:"" ;
                        $hierarchical = !empty($post->hierarchical) ? $post->hierarchical:"" ;
                        $hierarchical =  $hierarchical == 'true' ? true:false;

                        $singular = "";
                        if(!empty($taxonomy_name)){
                            $str_len = (int)strlen($taxonomy_name) - 1;
                            if($str_len){
                                if(strpos($taxonomy_name,"s",-$str_len) == $str_len){
                                    $singular = rtrim($taxonomy_name,"s");
                                }else{
                                    $singular = $taxonomy_name;
                                }
                            }
                        }
                
                        $labels = array(
                            'name'              => esc_html__( ucfirst($taxonomy_name), 'my-custom-taxonomy' ),
                            'singular_name'     => esc_html__( ucfirst($singular), 'my-custom-taxonomy' ),
                            'search_items'      => esc_html__( 'Search '.ucfirst($taxonomy_name), 'my-custom-taxonomy' ),
                            'all_items'         => esc_html__( 'All '.ucfirst($taxonomy_name), 'my-custom-taxonomy' ),
                            'parent_item'       => esc_html__( 'Parent '.ucfirst($singular), 'my-custom-taxonomy' ),
                            'parent_item_colon' => esc_html__( 'Parent :'.ucfirst($singular), 'my-custom-taxonomy' ),
                            'edit_item'         => esc_html__( 'Edit '.ucfirst($singular), 'my-custom-taxonomy' ),
                            'update_item'       => esc_html__( 'Update '.ucfirst($singular), 'my-custom-taxonomy' ),
                            'add_new_item'      => esc_html__( 'Add New '.ucfirst($singular), 'my-custom-taxonomy' ),
                            'new_item_name'     => esc_html__( 'New '.ucfirst($singular).' Name', 'my-custom-taxonomy' ),
                            'menu_name'         => esc_html__( ucfirst($taxonomy_name), 'my-custom-taxonomy' ),
                        );

                        $args = array(
                            'labels'            => $labels,
                            'public'            => true,
                            'hierarchical'      => $hierarchical,
                            'show_ui'           => true,
                            'query_var'         => true,
                            'show_admin_column' => true,
                            'show_in_menu'      => true,
                            "show_in_nav_menus" => true,
                            "show_in_rest"      => true,
                            'rewrite'           => array( 'slug' => strtolower($taxonomy_slug)),
                            "capabilities"      => ['manage_terms']
                        );
                        $my_custom_taxonomy = $hierarchical ? strtolower(esc_attr($singular))."_cat":strtolower(esc_attr($singular))."_tag";
                        register_taxonomy($my_custom_taxonomy, esc_attr(strtolower($post_type)), $args);
                }
            }
        }
    }
    new My_Custom_Taxonomy();
}





