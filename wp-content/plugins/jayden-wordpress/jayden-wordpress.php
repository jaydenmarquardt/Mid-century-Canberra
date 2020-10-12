<?php
/*
  Plugin Name: Jayden's Wordpress Util Plugin
  Plugin URI: https://promote.net.au
  description: This Wordpress plugin was developed to make wordpress more professional
  Version: 1.0a1
  Author: Jayden Marquardt - Bachelor of Software Engineering
  Author URI: https://promote.net.au
  License: GPL2
  */

class JM_API {

    private static $instance;

    function __construct()
    {
        define("JM_PATH", __DIR__);
        define("JM_PATH_INCLUDES", JM_PATH."/includes/");
        define("JM_PATH_ASSETS", JM_PATH."/assets/");
        define("JM_PATH_VENDOR", JM_PATH."/vendor/");
        define("JM_SITE_URL", get_bloginfo("url"));
        define("JM_URL", JM_SITE_URL."/wp-content/plugins/jayden-wordpress/");
        define("JM_URL_ASSETS", JM_URL."assets/");
        define("JM_URL_VENDOR", JM_URL."vendor/");
        define("JM_URL_includes", JM_URL."includes/");

        add_action("init", [$this, "init"]);

        include_once JM_PATH_INCLUDES."jm-dev.php";
        include_once JM_PATH_INCLUDES."jm-custom-post-types.php";
        include_once JM_PATH_INCLUDES."jm-utilities.php";
        include_once JM_PATH_INCLUDES."jm-acf-blocks.php";
        include_once JM_PATH_INCLUDES."jm-block.php";

    }

    public static function get_instance()
    {
        if (isset(self::$instance)) return self::$instance;
        return self::$instance = new JM_API();
    }

    public function init()
    {
        add_filter("jm_add_asset_styles", function ($styles){
            $styles[] = JM_PATH_ASSETS."theme_styles.scss";
            return $styles;
        }, 10, 1);
        add_filter("jm_add_asset_script", function ($scripts){

            return $scripts;
        }, 10, 1);
        add_action("wp_enqueue_scripts", [$this, "enqueue_assets"], 100);
        add_action("enqueue_block_editor_assets", [$this, "enqueue_assets"], 100);
        acf_update_setting('google_api_key', 'AIzaSyDCeQQG78ExASthfRztYOnZ_OMPLLl9haA');

        JM_CPT::add_custom_post_type("House",  ["menu_icon" => "dashicons-admin-home", "description" => "These are your houses", "taxonomies" => ["house_tax"], "menu_position" => 4]);
        JM_CPT::add_custom_post_type("Architect",  ["menu_icon" => "dashicons-businessman", "description" => "These are your Architects", "taxonomies" => ["architect_tax"], "menu_position" => 5]);

        JM_CPT::add_custom_taxonomy("House",  "house");
        JM_CPT::add_custom_taxonomy("Architect",  "architect");
        JM_Blocks::init();

        add_image_size( 'post-thumb', 500, 300 );
//        add_image_size( 'custom-size', 220, 180 );

        if(is_admin() && array_key_exists("import_house", $_GET))
        {
            $this->import();
        }
    }



    public static function enqueue_assets()
    {
        JM_DEV::enqueue_assets();

        wp_enqueue_style( 'uikit', JM_URL_VENDOR."/uikit/css/uikit.css");
        wp_enqueue_style( 'fontawesome',  JM_URL_VENDOR."/fontawesome/css/fontawesome.css");
        wp_enqueue_style( 'fontawesome-solid',  JM_URL_VENDOR."/fontawesome/css/solid.css");
        wp_enqueue_style( 'fontawesome-brand',  JM_URL_VENDOR."/fontawesome/css/brands.css");
        wp_enqueue_style( 'jm_master_css', JM_URL_ASSETS."/jm_master_css.css" );
        if(is_admin())return;

//        wp_enqueue_script( 'jquery-jm', JM_URL_VENDOR."/google/jquery.min.js", array(), '3.0.0', true );
        wp_enqueue_script( 'uikit', JM_URL_VENDOR."/uikit/js/uikit.min.js", array("jquery"), '1.0.0', true );
        wp_enqueue_script( 'uikit-icons', JM_URL_VENDOR."/uikit/js/uikit-icons.min.js", array("jquery"), '1.0.0', true );
        wp_enqueue_script( 'jm_master_js', JM_URL_ASSETS."/jm_master_js.js", array("jquery"), '1.0.0', true );

    }

    private function import()
    {
        include_once JM_PATH_VENDOR."/excelSimple/simpleexcel.php";
        if ( ! function_exists( 'post_exists' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/post.php' );
        }if ( ! function_exists( 'wp_crop_image' ) ) {
            include( ABSPATH . 'wp-admin/includes/image.php' );
        }

        $file = JM_PATH_ASSETS."houses_import.xls";

        $xls = SimpleXLS::parse($file);
        $rows = $xls->rows();
        $headers = [];
        $data = [];
        foreach ($rows[0] as $header)
        {
            if($header){
                $headers[] = $header;
                $data[$header] = [];
            }
        }
       unset($rows[0]);

        foreach ($rows as $row)
        {
            $column_index = 0;
            foreach ($row as $column)
            {
                $header = $headers[$column_index];

                if($header){
                    $data[$header][] = $column;
                }
                $column_index++;

            }
        }

        for($index = 0; $index < count($data["Title"]); $index++)
        {
            $post = [];
            foreach ($headers as $header)
            {
                $post[$header] = $data[$header][$index];
            }

            echo "<pre>"; var_dump("-----POST----- $post[Title]");echo "</pre>";
//            echo "<pre>"; var_dump($post);echo "</pre>";

            if(!$post["Title"]){
                echo "<pre>"; var_dump("___NO Title");echo "</pre>";

                continue;
            }
            $post_id = -1;
            if(post_exists($post["Title"] ,'','', 'house'))
            {
                $post_id = get_page_by_title($post["Title"], OBJECT, 'house')->ID;
                echo "<pre>"; var_dump("___Post exists [$post_id]");echo "</pre>";

            }else{
                $my_post = array(
                    'post_title'    => $post["Title"],
                    'post_content'  => $post["Content"],
                    'post_status'   => 'publish',
                    'post_author'   => 1,
                    'post_type'   => "house",
                );

                $post_id = wp_insert_post( $my_post );

                echo "<pre>"; var_dump("___New Post [$post_id]");echo "</pre>";

            }
            update_field("time_frame", $post["Time Frame"]." - ".$post["Time Frame"], $post_id);
            update_field("value", $post["Value"], $post_id);
            update_field("bedrooms", $post["Bed"], $post_id);
            update_field("bathrooms", $post["Bath"], $post_id);
            update_field("land", $post["Land"], $post_id);
            update_field("description", $post["Description"], $post_id);
            update_field("source", $post["Source"], $post_id);
            update_field("significance", $post["Significance"], $post_id);

            $my_post = array(
                'ID'           => $post_id,
                'post_content' => $post["Content"] ? $post["Content"] : $post["Description"],
            );

            wp_update_post( $my_post );
            if($post["Images"])
            {
                $images = explode(",", $post["Images"]);
                $uploads = [];
                foreach ($images as $image)
                {
                    $uploads[] = $id = $this->upload_image($post_id, $image);

                }
                echo "<pre>"; var_dump($uploads);echo "</pre>";

                if(count($uploads))
                {
                    update_post_meta( $post_id, '_thumbnail_id', $uploads[0] );
                    update_field("gallery", $uploads, $post_id);

                }
                //upload images
                //set thumbnail


            }

            $old_address = get_field("address", $post_id);
            if($old_address["lat"])
            {
                echo "<pre>"; var_dump("Already found address");echo "</pre>";
//                echo "<pre>"; var_dump($old_address);echo "</pre>";

            }else{
                $address =  array("address" => $post["Address"], "lat" => null, "lng" => null, "zoom" => 0);
                $address = array_merge($address, $this->get_latlng( $post["Address"]));
//                echo "<pre>"; var_dump($address);echo "</pre>";
                update_field("address", $address, $post_id);
            }





        }
        echo "<pre>"; var_dump($data);echo "</pre>";

//        echo "<pre>"; var_dump($csv);echo "</pre>";

        die;
    }

    function get_latlng( $address ) {
        $address = urlencode($address);

        $request = wp_remote_get("https://maps.googleapis.com/maps/api/geocode/json?address=$address&key=AIzaSyDCeQQG78ExASthfRztYOnZ_OMPLLl9haA");
        $json = wp_remote_retrieve_body( $request );

        if ( !$json ) {
            echo 'Google Maps returned an empty response';
            return false;
        }

        $data = json_decode($json);
        if ( !$data ) {
            echo '<h2>ERROR! Google Maps returned an invalid response, expected JSON data:</h2>';
            echo esc_html(print_r($json, true));
            exit;
        }

        if ( isset($data->{'error_message'}) ) {
            echo '<h2>ERROR! Google Maps API returned an error:</h2>';
            echo '<strong>'. esc_html($data->{'status'}) .'</strong> ' . esc_html($data->{'error_message'}) .'<br>';
            exit;
        }

        if ( empty($data->{'results'}[0]->{'geometry'}->{'location'}->{'lat'}) || empty($data->{'results'}[0]->{'geometry'}->{'location'}->{'lng'}) ) {
            echo '<h2>ERROR! Latitude/Longitude could not be found:</h2>';
            echo esc_html(print_r($data, true));
            exit;
        }

        $lat = $data->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
        $lng = $data->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

        // Value can be negative, so check for specifically 0.
        if ( floatval( $lat ) === 0 || floatval( $lng ) === 0 ) {
            echo '<h2>ERROR! Latitude/Longitude is invalid (exactly zero):</h2>';
            var_dump('Latitude:', $lat);
            var_dump('Longitude:', $lng);
            var_dump('Result:', $data->{'results'}[0]);
            exit;
        }

        return array( 'lat' => $lat, 'lng' => $lng );
    }

    private function upload_image($pid, $url)
    {
        $uploaddir = wp_upload_dir();
        $filename = 'image_upload_pid_' . $pid."_".substr($url, strripos($url, "/")+1);
        echo "<pre>"; var_dump("$filename");echo "</pre>";
        if(!is_dir($uploaddir['basedir'] ."/houses/"))
        {
            mkdir($uploaddir['basedir'] ."/houses/");
        }
        $uploadfile = $uploaddir['basedir'] ."/houses/". $filename;

        if(file_exists($uploadfile))
        {
            echo "<pre>"; var_dump("Image already exists");echo "</pre>";

            return  get_page_by_title($filename, OBJECT, 'attachment')->ID;
        }

        $contents= file_get_contents($url);
        $savefile = fopen($uploadfile, 'w');
        fwrite($savefile, $contents);
        fclose($savefile);

        $wp_filetype = wp_check_filetype(basename($uploadfile), null );

        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => $filename,
            'post_content' => '',
            'post_status' => 'inherit'
        );
        echo "<pre>"; var_dump($attachment);echo "</pre>";

        $attach_id = wp_insert_attachment( $attachment, $uploadfile );

        $imagenew = get_post( $attach_id );
        $fullsizepath = get_attached_file( $imagenew->ID );
        $attach_data = wp_generate_attachment_metadata( $attach_id, $fullsizepath );
        wp_update_attachment_metadata( $attach_id, $attach_data );
        return $attach_id;
    }



}

JM_API::get_instance();
