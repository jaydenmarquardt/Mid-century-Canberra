<?php

class JM_CPT
{

    public static function add_custom_post_type($name, $atts = [])
    {
        $labels = array(
            "name"                => _x( "$name's", "jm-wordpress" ),
            "singular_name"       => _x( "$name", "jm-wordpress" ),
            "menu_name"           => __( $name."s", "jm-wordpress" ),
            "parent_item_colon"   => __( "Parent $name", "jm-wordpress" ),
            "all_items"           => __( "All $name's", "jm-wordpress" ),
            "view_item"           => __( "View $name", "jm-wordpress" ),
            "add_new_item"        => __( "Add New $name", "jm-wordpress" ),
            "add_new"             => __( "Add New", "jm-wordpress" ),
            "edit_item"           => __( "Edit $name", "jm-wordpress" ),
            "update_item"         => __( "Update $name", "jm-wordpress" ),
            "search_items"        => __( "Search $name", "jm-wordpress" ),
            "not_found"           => __( "Not Found", "jm-wordpress" ),
            "not_found_in_trash"  => __( "Not found in Trash", "jm-wordpress" ),
        );
        $labels = array_merge($labels, $atts);



        $args = array(
            "label"               => __( $name."s", "jm-wordpress" ),
            "description"         => __( "No description", "jm-wordpress" ),
            "labels"              => $labels,
            "supports"            => array( "title", "editor", "excerpt", "author", "thumbnail", "comments", "revisions", "category" ),
            "taxonomies"          => array( "category" ),
            "hierarchical"        => false,
            "public"              => true,
            "show_ui"             => true,
            "show_in_menu"        => true,
            "show_in_nav_menus"   => true,
            "show_in_admin_bar"   => true,
            "menu_position"       => 3,
            "menu_icon"       => "dashicons-businessman",
            "can_export"          => true,
            "has_archive"         => true,
            "exclude_from_search" => false,
            "publicly_queryable"  => true,
            "capability_type"     => "post",
            "show_in_rest" => true,

        );
        $atts = array_merge($args, $atts);

        register_post_type( JM_UTIL::slugify($name), $atts );
    }

    public static function add_custom_taxonomy($name, $post_types = [], $atts = [])
    {
        $labels = array(
            "name"              => _x( "$name Categories",  "jm-wordpress" ),
            "singular_name"     => _x( "$name Category", "jm-wordpress" ),
            "search_items"      => __( "Search $name Categories", "jm-wordpress" ),
            "all_items"         => __( "All $name Categories", "jm-wordpress" ),
            "parent_item"       => __( "Parent $name Category", "jm-wordpress" ),
            "parent_item_colon" => __( "Parent $name Category:", "jm-wordpress" ),
            "edit_item"         => __( "Edit $name Category", "jm-wordpress" ),
            "update_item"       => __( "Update $name Category", "jm-wordpress" ),
            "add_new_item"      => __( "Add New $name Category", "jm-wordpress" ),
            "new_item_name"     => __( "New $name Category Name", "jm-wordpress" ),
            "menu_name"         => __( "$name Categories", "jm-wordpress" ),
        );
        $labels = array_merge($labels, $atts);

        $args = array(
            "hierarchical"      => true,
            "labels"            => $labels,
            "show_ui"           => true,
            "show_admin_column" => true,
            "query_var"         => true,
            "rewrite"           => array( "slug" => JM_UTIL::slugify($name)."_tax" ),
        );
        $args = array_merge($args, $atts);

        register_taxonomy( JM_UTIL::slugify($name)."_tax", $post_types, $args );

    }
}