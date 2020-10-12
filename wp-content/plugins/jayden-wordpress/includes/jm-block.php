<?php

/**
 * Created by PhpStorm.
 * User: jayden
 * Date: 20/11/19
 * Time: 8:08 AM
 */


abstract class JM_Block
{

    public $dir = "";


    public function __construct(){


        $this->init();

        add_filter("jm_add_asset_styles", function ($styles){
            $style_file = JM_PATH_INCLUDES."blocks/".$this->get_class_name()."/".$this->get_class_name().".scss";
            if(file_exists($style_file)) {
                $styles[] = $style_file;
            }

            return $styles;
        }, 7, 1);
    }

    /**

     Returns the array of fields for this block

     */
    abstract public function get_fields();


    /**

    This is called when the block is being rendered

    Arg 1: $variables is the fields inputted for this block

     */
    abstract public function render($block);


    /**

    This is called when the block is being rendered, It converts the data from guten to acf

    Arg 1: $variables is the fields inputted for this block

     */
    public function renderRaw($block){

        $fields =  $block;
        $bname = str_replace("acf/", "", $block["name"]);

        foreach ($block["data"] as $name => $value )
        {

            if(!JM_UTIL::startsWith($name, "_"))
            {
                $fields[$name] = $value;
            }
        }

        ob_start();
//        echo "<pre>"; var_dump($block);echo "</pre>";;

        $align = "align".$block["align"];

        echo  "<div class='jm-block $align $bname'>";
        $this->render($fields);
        echo "</div>";

        echo ob_get_clean();

    }



    /**

    This is called when the block is being initialised

     */
    public function init(){
        $this->register_block()->add_field_group();


    }

    /**

    This is called before it is rendered it is used for adding scripts to the pages

     */
    public function enqueue_scripts(){  }


    /**

    Returns the array of info for this block layout

     */
    public function get_layout(){
        return array( 'label' => ucwords($this->get_class_readable_name()) );//
    }

    /**

    Returns the description for this block layout

     */
    public function get_description(){

        return "Just another awesome block by Jayden";

    }

    /**

    Returns the category for this block layout

     */
    public function get_category(){

        return "JJM";

    }

    /**

    Returns the icon for this block layout

     */
    public function get_icon(){

        return "tagcloud";

    }


    /**

    Returns the keywords for this block layout

     */
    public function get_keywords(){

        return array("block", "jayden", "marquardt");

    }

    public function get_scss_file(){
        return "";
    }

    public function get_folder(){
        return JM_PATH_INCLUDES."/blocks/".str_replace("-", "_",$this->get_class_filename());
    }

    public function get_folder_url(){
        return JM_UR_INCLUDES."/blocks/".str_replace("-", "_",$this->get_class_filename());
    }


    public function get_image(){

        $url = $this->get_folder_url()."/image.png";
        $image = "<img src='$url'/>";

        return $image;
    }

    /** Return the current class name
     * @return bool|string
     */
    public function get_class_name(){
        return strtolower(get_called_class());
    }

    /**
     * Retunr the current class with dashes as opposed to underscores
     */
    public function get_class_filename(){
        return str_replace("_", "-", $this->get_class_name());
    }

    /** Return the class as a readable name
     * @return mixed
     */
    public function get_class_readable_name(){
        return str_replace("_", " ", $this->get_class_name());
    }

    public function register_block(){

        acf_register_block(array(
            'name' => 'jm-block-'.str_replace("_", "-", $this->get_class_name()),
            'title' => __($this->get_class_readable_name()),
            'description' => __($this->get_description()),
            'render_callback' => array($this, 'renderRaw'),
            'category' => "layout",
            'icon' => $this->get_icon(),
            'keywords' =>  $this->get_keywords(),
        ));

        return $this;
    }

    public function add_field_group(){


        acf_add_local_field_group(array(
            'key' => 'jm_group_'.$this->get_class_name(),
            'title' => 'Block: '.$this->get_class_readable_name(),
            'fields' => $this->get_fields(),
            'location' => array(
                array(
                    array(
                        'param' => 'block',
                        'operator' => '==',
                        'value' => 'acf/jm-block-'.str_replace("_", "-", $this->get_class_name()), //this needs to match register_block() > name. but with "acf/" at the start and with - replacing _
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
        ));
        return $this;
    }

    /**
     *
     * Blocks adds fields all blocks should have
     *
     * */
    function addDefaultFields($blockName, $fields)
    {
        array_unshift($fields, $this->doTab("Fields"));

        $fields[] = $this->doTab("Options");

        $fields[] = $this->doGroup("Style",
            array(
                $this->doToggle("Full Width", array(
                    'wrapper' => array(
                        'width' => '33',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => "1",
                )),
                $this->doSelect("Animation", array(
                    '' => 'None',
                    'uk-animation-fade' => 'Fade In',
                    'uk-animation-scale-up' => 'Scale Up',
                    'uk-animation-scale-down' => 'Scale Down',
                    'uk-animation-slide-top' => 'Slide Top',
                    'uk-animation-slide-bottom' => 'Slide Bottom',
                    'uk-animation-slide-left' => 'Slide Left',
                    'uk-animation-slide-right' => 'Slide Right',
                    'uk-animation-shake' => 'Shake',
                    'uk-animation-scale' => 'Scales Down No Fade',
                ),  array(
                    'wrapper' => array(
                        'width' => '33',
                        'class' => '',
                        'id' => '',
                    ),

                )),
                $this->doSelect("Margin", array(
                    '' => 'None',
                    'uk-margin' => 'Both',
                    'uk-margin-top' => 'Top',
                    'uk-margin-bottom' => 'Bottom',
                    'uk-margin-large' => 'Both Large',
                    'uk-margin-large-top' => 'Top Large',
                    'uk-margin-large-bottom' => 'Bottom Large',
                    'uk-margin-small' => 'Both Small',
                    'uk-margin-small-top' => 'Top Small',
                    'uk-margin-small-bottom' => 'Bottom Small',
                    'jm-margin-huge' => 'Both Huge',
                    'jm-margin-huge-top' => 'Top Huge',
                    'jm-margin-huge-bottom' => 'Bottom Huge',

                ), array( 'wrapper' => array(
                    'width' => '33',
                    'class' => '',
                    'id' => '',
                ), )),
                $this->doColorPicker("Background Colour", array(
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                )),
            )
        );

        $fields[] = $this->doGroup("Advanced",
            array(
                $this->doTextBox("Class",  array(
                    'wrapper' => array(
                        'width' => '33',
                        'class' => '',
                        'id' => '',
                    ),

                )),
                $this->doToggle("Lazy Load", array(
                    'wrapper' => array(
                        'width' => '33',
                        'class' => '',
                        'id' => '',
                    )
                )),

            )
        );


        return $fields;

    }

    function doLayout($name, $args = array())
    {
        $default = array(
            'key' => 'layout_jm_'.$this->get_class_name()."_".JM_UTIL::slugify($name),
            'name' => $name,
            'label' => $name,
            'display' => 'block',
            'sub_fields' => array(),
            'min' => '',
            'max' => '',
        );

        return array_merge($default, $args);

    }

    function doTextBox($label, $args = array())
    {
        $default = array(
            'key' => 'field_jm_'.$this->get_class_name()."_".JM_UTIL::slugify($label),
            'name' => JM_UTIL::slugify($label),
            'label' => $label,
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
        );

        return array_merge($default, $args);

    }

    function doToggle($label, $args = array())
    {
        $default = array(
            'key' => 'field_jm_'.$this->get_class_name()."_".JM_UTIL::slugify($label),
            'name' => JM_UTIL::slugify($label),
            'label' => $label,
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'message' => '',
            'default_value' => 0,
            'ui' => 1,
            'ui_on_text' => '',
            'ui_off_text' => '',
        );

        return array_merge($default, $args);

    }

    function doImage($label, $args = array())
    {
        $default = array(
            'key' => 'field_jm_'.$this->get_class_name()."_".JM_UTIL::slugify($label),
            'name' => JM_UTIL::slugify($label),
            'label' => $label,
            'type' => 'image',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'return_format' => 'array',
            'preview_size' => 'medium',
            'library' => 'all',
            'min_width' => '',
            'min_height' => '',
            'min_size' => '',
            'max_width' => '',
            'max_height' => '',
            'max_size' => '',
            'mime_types' => '',
        );

        return array_merge($default, $args);

    }

    function doGallery($label, $args = array())
    {
        $default = array(
            'key' => 'field_jm_'.$this->get_class_name()."_".JM_UTIL::slugify($label),
            'name' => JM_UTIL::slugify($label),
            'label' => $label,
            'type' => 'gallery',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'return_format' => 'array',
            'preview_size' => 'medium',
            'insert' => 'append',
            'library' => 'all',
            'min' => '',
            'max' => '',
            'min_width' => '',
            'min_height' => '',
            'min_size' => '',
            'max_width' => '',
            'max_height' => '',
            'max_size' => '',
            'mime_types' => '',
        );

        return array_merge($default, $args);

    }

    function doWysiwyg($label, $args = array())
    {
        $default = array(
            'key' => 'field_jm_'.$this->get_class_name()."_".JM_UTIL::slugify($label),
            'name' => JM_UTIL::slugify($label),
            'label' => $label,
            'type' => 'wysiwyg',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
            'delay' => 0,
        );

        return array_merge($default, $args);

    }

    function doCategory($label, $args = array())
    {
        $default = array(
            'key' => 'field_jm_'.$this->get_class_name()."_".JM_UTIL::slugify($label),
            'name' => JM_UTIL::slugify($label),
            'label' => $label,
            'type' => 'taxonomy',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'taxonomy' => 'category',
            'field_type' => 'multi_select',
            'allow_null' => 0,
            'add_term' => 1,
            'save_terms' => 0,
            'load_terms' => 0,
            'return_format' => 'id',
            'multiple' => 0,
        );

        return array_merge($default, $args);

    }

    function doPost($label, $args = array())
    {
        $default = array(
            'key' => 'field_jm_'.$this->get_class_name()."_".JM_UTIL::slugify($label),
            'name' => JM_UTIL::slugify($label),
            'label' => $label,
            'type' => 'post_object',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'post_type' => '',
            'taxonomy' => '',
            'allow_null' => 0,
            'multiple' => 0,
            'return_format' => 'object',
            'ui' => 1,
        );

        return array_merge($default, $args);

    }

    function doMap($label, $args = array())
    {
        $default = array(
            'key' => 'field_jm_'.$this->get_class_name()."_".JM_UTIL::slugify($label),
            'name' => JM_UTIL::slugify($label),
            'label' => $label,
            'type' => 'google_map',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'center_lat' => '',
            'center_lng' => '',
            'zoom' => '',
            'height' => '',
        );

        return array_merge($default, $args);

    }

    function doGroup($label, $fields = array(), $args = array())
    {
        $default = array(
            'key' => 'field_jm_'.$this->get_class_name()."_".JM_UTIL::slugify($label),
            'label' => $label,
            'name' => JM_UTIL::slugify($label),
            'type' => 'group',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'layout' => 'block',
            'sub_fields' => $fields,
        );

        return array_merge($default, $args);

    }

    function doRepeater($label, $fields = array(), $args = array())
    {
        $default = array(
            'key' => 'field_jm_'.$this->get_class_name()."_".JM_UTIL::slugify($label),
            'name' => JM_UTIL::slugify($label),
            'label' => $label,
            'type' => 'repeater',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'collapsed' => '',
            'min' => 0,
            'max' => 0,
            'layout' => 'row',
            'button_label' => '',
            'sub_fields' => $fields,
        );

        return array_merge($default, $args);

    }

    function doSelect($label, $choices = array(), $args = array())
    {
        $default = array(
            'key' => 'field_jm_'.$this->get_class_name()."_".JM_UTIL::slugify($label),
            'name' => JM_UTIL::slugify($label),
            'label' => $label,
            'type' => 'select',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'choices' => $choices,
            'default_value' => array(
            ),
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 0,
            'return_format' => 'value',
            'ajax' => 0,
            'placeholder' => '',
        );


        return array_merge($default, $args);

    }

    function doIcon($label, $args = array())
    {
        $choices = array(
            "uk-icon-file" => "file",
            "uk-icon-file-archive-o" => "file-archive-o",
            "uk-icon-file-audio-o" => "file-audio-o",
            "uk-icon-file-code-o" => "file-code-o",
            "uk-icon-file-excel-o" => "file-excel-o",
            "uk-icon-file-image-o" => "file-image-o",
            "uk-icon-file-movie-o" => "file-movie-o",
            "uk-icon-file-o" => "file-o",
            "uk-icon-file-pdf-o" => "file-pdf-o",
            "uk-icon-file-photo-o" => "file-photo-o",
            "uk-icon-file-picture-o" => "file-picture-o",
            "uk-icon-file-powerpoint-o" => "file-powerpoint-o",
            "uk-icon-file-sound-o" => "file-sound-o",
            "uk-icon-file-text" => "file-text",
            "uk-icon-file-text-o" => "file-text-o",
            "uk-icon-file-video-o" => "file-video-o",
            "uk-icon-file-word-o" => "file-word-o",
            "uk-icon-file-zip-o" => "file-zip-o",
            "uk-icon-facebook" => "facebook",
            "uk-icon-twitter" => "twitter",
            "uk-icon-youtube-play" => "youtube",
            "uk-icon-adjust" => "adjust",
            "uk-icon-anchor" => "anchor",
            "uk-icon-archive" => "archive",
            "uk-icon-area-chart" => "area-chart",
            "uk-icon-arrows" => "arrows",
            "uk-icon-arrows-h" => "arrows-h",
            "uk-icon-arrows-v" => "arrows-v",
            "uk-icon-asterisk" => "asterisk",
            "uk-icon-at" => "at",
            "uk-icon-automobile" => "automobile",
            "uk-icon-balance-scale" => "balance-scale",
            "uk-icon-ban" => "ban",
            "uk-icon-bank" => "bank",
            "uk-icon-bar-chart" => "bar-chart",
            "uk-icon-bar-chart-o" => "bar-chart-o",
            "uk-icon-barcode" => "barcode",
            "uk-icon-bars" => "bars",
            "uk-icon-battery-empty" => "battery-empty",
            "uk-icon-battery-0" => "battery-0",
            "uk-icon-battery-quarter" => "battery-quarter",
            "uk-icon-battery-1" => "battery-1",
            "uk-icon-battery-half" => "battery-half",
            "uk-icon-battery-2" => "battery-2",
            "uk-icon-battery-three-quarters" => "battery-three-quarters",
            "uk-icon-battery-3" => "battery-3",
            "uk-icon-battery-full" => "battery-full",
            "uk-icon-battery-4" => "battery-4",
            "uk-icon-bed" => "bed",
            "uk-icon-beer" => "beer",
            "uk-icon-bell" => "bell",
            "uk-icon-bell-o" => "bell-o",
            "uk-icon-bell-slash" => "bell-slash",
            "uk-icon-bell-slash-o" => "bell-slash-o",
            "uk-icon-bicycle" => "bicycle",
            "uk-icon-binoculars" => "binoculars",
            "uk-icon-birthday-cake" => "birthday-cake",
            "uk-icon-bluetooth" => "bluetooth",
            "uk-icon-bluetooth-b" => "bluetooth-b",
            "uk-icon-bolt" => "bolt",
            "uk-icon-bomb" => "bomb",
            "uk-icon-book" => "book",
            "uk-icon-bookmark" => "bookmark",
            "uk-icon-bookmark-o" => "bookmark-o",
            "uk-icon-briefcase" => "briefcase",
            "uk-icon-bug" => "bug",
            "uk-icon-building" => "building",
            "uk-icon-building-o" => "building-o",
            "uk-icon-bullhorn" => "bullhorn",
            "uk-icon-bullseye" => "bullseye",
            "uk-icon-bus" => "bus",
            "uk-icon-cab" => "cab",
            "uk-icon-calculator" => "calculator",
            "uk-icon-calendar" => "calendar",
            "uk-icon-calendar-check-o" => "calendar-check-o",
            "uk-icon-calendar-minus-o" => "calendar-minus-o",
            "uk-icon-calendar-o" => "calendar-o",
            "uk-icon-calendar-plus-o" => "calendar-plus-o",
            "uk-icon-calendar-times-o" => "calendar-times-o",
            "uk-icon-camera" => "camera",
            "uk-icon-camera-retro" => "camera-retro",
            "uk-icon-car" => "car",
            "uk-icon-caret-square-o-down" => "caret-square-o-down",
            "uk-icon-caret-square-o-left" => "caret-square-o-left",
            "uk-icon-caret-square-o-right" => "caret-square-o-right",
            "uk-icon-caret-square-o-up" => "caret-square-o-up",
            "uk-icon-cart-arrow-down" => "cart-arrow-down",
            "uk-icon-cart-plus" => "cart-plus",
            "uk-icon-cc" => "cc",
            "uk-icon-certificate" => "certificate",
            "uk-icon-check" => "check",
            "uk-icon-check-circle" => "check-circle",
            "uk-icon-check-circle-o" => "check-circle-o",
            "uk-icon-check-square" => "check-square",
            "uk-icon-check-square-o" => "check-square-o",
            "uk-icon-child" => "child",
            "uk-icon-circle" => "circle",
            "uk-icon-circle-o" => "circle-o",
            "uk-icon-circle-o-notch" => "circle-o-notch",
            "uk-icon-circle-thin" => "circle-thin",
            "uk-icon-clock-o" => "clock-o",
            "uk-icon-clone" => "clone",
            "uk-icon-close" => "close",
            "uk-icon-cloud" => "cloud",
            "uk-icon-cloud-download" => "cloud-download",
            "uk-icon-cloud-upload" => "cloud-upload",
            "uk-icon-code" => "code",
            "uk-icon-code-fork" => "code-fork",
            "uk-icon-coffee" => "coffee",
            "uk-icon-cog" => "cog",
            "uk-icon-cogs" => "cogs",
            "uk-icon-comment" => "comment",
            "uk-icon-comment-o" => "comment-o",
            "uk-icon-commenting" => "commenting",
            "uk-icon-commenting-o" => "commenting-o",
            "uk-icon-comments" => "comments",
            "uk-icon-comments-o" => "comments-o",
            "uk-icon-compass" => "compass",
            "uk-icon-copyright" => "copyright",
            "uk-icon-creative-commons" => "creative-commons",
            "uk-icon-credit-card" => "credit-card",
            "uk-icon-credit-card-alt" => "credit-card-alt",
            "uk-icon-crop" => "crop",
            "uk-icon-crosshairs" => "crosshairs",
            "uk-icon-cube" => "cube",
            "uk-icon-cubes" => "cubes",
            "uk-icon-cutlery" => "cutlery",
            "uk-icon-dashboard" => "dashboard",
            "uk-icon-database" => "database",
            "uk-icon-desktop" => "desktop",
            "uk-icon-diamond" => "diamond",
            "uk-icon-dot-circle-o" => "dot-circle-o",
            "uk-icon-download" => "download",
            "uk-icon-edit" => "edit",
            "uk-icon-ellipsis-h" => "ellipsis-h",
            "uk-icon-ellipsis-v" => "ellipsis-v",
            "uk-icon-envelope" => "envelope",
            "uk-icon-envelope-o" => "envelope-o",
            "uk-icon-envelope-square" => "envelope-square",
            "uk-icon-eraser" => "eraser",
            "uk-icon-exchange" => "exchange",
            "uk-icon-exclamation" => "exclamation",
            "uk-icon-exclamation-circle" => "exclamation-circle",
            "uk-icon-exclamation-triangle" => "exclamation-triangle",
            "uk-icon-external-link" => "external-link",
            "uk-icon-external-link-square" => "external-link-square",
            "uk-icon-eye" => "eye",
            "uk-icon-eye-slash" => "eye-slash",
            "uk-icon-eyedropper" => "eyedropper",
            "uk-icon-fax" => "fax",
            "uk-icon-female" => "female",
            "uk-icon-fighter-jet" => "fighter-jet",
            "uk-icon-file-archive-o" => "file-archive-o",
            "uk-icon-file-audio-o" => "file-audio-o",
            "uk-icon-file-code-o" => "file-code-o",
            "uk-icon-file-excel-o" => "file-excel-o",
            "uk-icon-file-image-o" => "file-image-o",
            "uk-icon-file-movie-o" => "file-movie-o",
            "uk-icon-file-pdf-o" => "file-pdf-o",
            "uk-icon-file-photo-o" => "file-photo-o",
            "uk-icon-file-picture-o" => "file-picture-o",
            "uk-icon-file-powerpoint-o" => "file-powerpoint-o",
            "uk-icon-file-sound-o" => "file-sound-o",
            "uk-icon-file-video-o" => "file-video-o",
            "uk-icon-file-word-o" => "file-word-o",
            "uk-icon-file-zip-o" => "file-zip-o",
            "uk-icon-film" => "film",
            "uk-icon-filter" => "filter",
            "uk-icon-fire" => "fire",
            "uk-icon-fire-extinguisher" => "fire-extinguisher",
            "uk-icon-flag" => "flag",
            "uk-icon-flag-checkered" => "flag-checkered",
            "uk-icon-flag-o" => "flag-o",
            "uk-icon-flash" => "flash",
            "uk-icon-flask" => "flask",
            "uk-icon-folder" => "folder",
            "uk-icon-folder-o" => "folder-o",
            "uk-icon-folder-open" => "folder-open",
            "uk-icon-folder-open-o" => "folder-open-o",
            "uk-icon-frown-o" => "frown-o",
            "uk-icon-futbol-o" => "futbol-o",
            "uk-icon-gamepad" => "gamepad",
            "uk-icon-gavel" => "gavel",
            "uk-icon-gear" => "gear",
            "uk-icon-gears" => "gears",
            "uk-icon-genderless" => "genderless",
            "uk-icon-gift" => "gift",
            "uk-icon-glass" => "glass",
            "uk-icon-globe" => "globe",
            "uk-icon-graduation-cap" => "graduation-cap",
            "uk-icon-group" => "group",
            "uk-icon-hand-lizard-o" => "hand-lizard-o",
            "uk-icon-hand-stop-o" => "hand-stop-o",
            "uk-icon-hand-paper-o" => "hand-paper-o",
            "uk-icon-hand-peace-o" => "hand-peace-o",
            "uk-icon-hand-pointer-o" => "hand-pointer-o",
            "uk-icon-hand-grab-o" => "hand-grab-o",
            "uk-icon-hand-rock-o" => "hand-rock-o",
            "uk-icon-hand-scissors-o" => "hand-scissors-o",
            "uk-icon-hand-spock-o" => "hand-spock-o",
            "uk-icon-hdd-o" => "hdd-o",
            "uk-icon-hashtag" => "hashtag",
            "uk-icon-headphones" => "headphones",
            "uk-icon-heart" => "heart",
            "uk-icon-heart-o" => "heart-o",
            "uk-icon-heartbeat" => "heartbeat",
            "uk-icon-history" => "history",
            "uk-icon-home" => "home",
            "uk-icon-hotel" => "hotel",
            "uk-icon-hourglass" => "hourglass",
            "uk-icon-hourglass-o" => "hourglass-o",
            "uk-icon-hourglass-1" => "hourglass-1",
            "uk-icon-hourglass-start" => "hourglass-start",
            "uk-icon-hourglass-2" => "hourglass-2",
            "uk-icon-hourglass-half" => "hourglass-half",
            "uk-icon-hourglass-3" => "hourglass-3",
            "uk-icon-hourglass-end" => "hourglass-end",
            "uk-icon-i-cursor" => "i-cursor",
            "uk-icon-image" => "image",
            "uk-icon-inbox" => "inbox",
            "uk-icon-industry" => "industry",
            "uk-icon-info" => "info",
            "uk-icon-info-circle" => "info-circle",
            "uk-icon-institution" => "institution",
            "uk-icon-key" => "key",
            "uk-icon-keyboard-o" => "keyboard-o",
            "uk-icon-language" => "language",
            "uk-icon-laptop" => "laptop",
            "uk-icon-leaf" => "leaf",
            "uk-icon-legal" => "legal",
            "uk-icon-lemon-o" => "lemon-o",
            "uk-icon-level-down" => "level-down",
            "uk-icon-level-up" => "level-up",
            "uk-icon-life-bouy" => "life-bouy",
            "uk-icon-life-buoy" => "life-buoy",
            "uk-icon-life-ring" => "life-ring",
            "uk-icon-life-saver" => "life-saver",
            "uk-icon-lightbulb-o" => "lightbulb-o",
            "uk-icon-line-chart" => "line-chart",
            "uk-icon-location-arrow" => "location-arrow",
            "uk-icon-lock" => "lock",
            "uk-icon-magic" => "magic",
            "uk-icon-magnet" => "magnet",
            "uk-icon-mail-forward" => "mail-forward",
            "uk-icon-mail-reply" => "mail-reply",
            "uk-icon-mail-reply-all" => "mail-reply-all",
            "uk-icon-male" => "male",
            "uk-icon-map" => "map",
            "uk-icon-map-marker" => "map-marker",
            "uk-icon-map-o" => "map-o",
            "uk-icon-map-pin" => "map-pin",
            "uk-icon-map-signs" => "map-signs",
            "uk-icon-meh-o" => "meh-o",
            "uk-icon-microphone" => "microphone",
            "uk-icon-microphone-slash" => "microphone-slash",
            "uk-icon-minus" => "minus",
            "uk-icon-minus-circle" => "minus-circle",
            "uk-icon-minus-square" => "minus-square",
            "uk-icon-minus-square-o" => "minus-square-o",
            "uk-icon-mobile" => "mobile",
            "uk-icon-mobile-phone" => "mobile-phone",
            "uk-icon-money" => "money",
            "uk-icon-moon-o" => "moon-o",
            "uk-icon-mortar-board" => "mortar-board",
            "uk-icon-motorcycle" => "motorcycle",
            "uk-icon-mouse-pointer" => "mouse-pointer",
            "uk-icon-music" => "music",
            "uk-icon-navicon" => "navicon",
            "uk-icon-newspaper-o" => "newspaper-o",
            "uk-icon-object-group" => "object-group",
            "uk-icon-object-ungroup" => "object-ungroup",
            "uk-icon-paint-brush" => "paint-brush",
            "uk-icon-paper-plane" => "paper-plane",
            "uk-icon-paper-plane-o" => "paper-plane-o",
            "uk-icon-paw" => "paw",
            "uk-icon-pencil" => "pencil",
            "uk-icon-pencil-square" => "pencil-square",
            "uk-icon-pencil-square-o" => "pencil-square-o",
            "uk-icon-percent" => "percent",
            "uk-icon-phone" => "phone",
            "uk-icon-phone-square" => "phone-square",
            "uk-icon-photo" => "photo",
            "uk-icon-picture-o" => "picture-o",
            "uk-icon-pie-chart" => "pie-chart",
            "uk-icon-plane" => "plane",
            "uk-icon-plug" => "plug",
            "uk-icon-plus" => "plus",
            "uk-icon-plus-circle" => "plus-circle",
            "uk-icon-plus-square" => "plus-square",
            "uk-icon-plus-square-o" => "plus-square-o",
            "uk-icon-power-off" => "power-off",
            "uk-icon-print" => "print",
            "uk-icon-puzzle-piece" => "puzzle-piece",
            "uk-icon-qrcode" => "qrcode",
            "uk-icon-question" => "question",
            "uk-icon-question-circle" => "question-circle",
            "uk-icon-quote-left" => "quote-left",
            "uk-icon-quote-right" => "quote-right",
            "uk-icon-random" => "random",
            "uk-icon-recycle" => "recycle",
            "uk-icon-refresh" => "refresh",
            "uk-icon-registered" => "registered",
            "uk-icon-remove" => "remove",
            "uk-icon-reorder" => "reorder",
            "uk-icon-reply" => "reply",
            "uk-icon-reply-all" => "reply-all",
            "uk-icon-retweet" => "retweet",
            "uk-icon-road" => "road",
            "uk-icon-rocket" => "rocket",
            "uk-icon-rss" => "rss",
            "uk-icon-rss-square" => "rss-square",
            "uk-icon-search" => "search",
            "uk-icon-search-minus" => "search-minus",
            "uk-icon-search-plus" => "search-plus",
            "uk-icon-send" => "send",
            "uk-icon-send-o" => "send-o",
            "uk-icon-server" => "server",
            "uk-icon-share" => "share",
            "uk-icon-share-alt" => "share-alt",
            "uk-icon-share-alt-square" => "share-alt-square",
            "uk-icon-share-square" => "share-square",
            "uk-icon-share-square-o" => "share-square-o",
            "uk-icon-shield" => "shield",
            "uk-icon-ship" => "ship",
            "uk-icon-shopping-bag" => "shopping-bag",
            "uk-icon-shopping-basket" => "shopping-basket",
            "uk-icon-shopping-cart" => "shopping-cart",
            "uk-icon-sign-in" => "sign-in",
            "uk-icon-sign-out" => "sign-out",
            "uk-icon-signal" => "signal",
            "uk-icon-sitemap" => "sitemap",
            "uk-icon-sliders" => "sliders",
            "uk-icon-smile-o" => "smile-o",
            "uk-icon-soccer-ball-o" => "soccer-ball-o",
            "uk-icon-sort" => "sort",
            "uk-icon-sort-alpha-asc" => "sort-alpha-asc",
            "uk-icon-sort-alpha-desc" => "sort-alpha-desc",
            "uk-icon-sort-amount-asc" => "sort-amount-asc",
            "uk-icon-sort-amount-desc" => "sort-amount-desc",
            "uk-icon-sort-asc" => "sort-asc",
            "uk-icon-sort-desc" => "sort-desc",
            "uk-icon-sort-down" => "sort-down",
            "uk-icon-sort-numeric-asc" => "sort-numeric-asc",
            "uk-icon-sort-numeric-desc" => "sort-numeric-desc",
            "uk-icon-sort-up" => "sort-up",
            "uk-icon-space-shuttle" => "space-shuttle",
            "uk-icon-spinner" => "spinner",
            "uk-icon-spoon" => "spoon",
            "uk-icon-square" => "square",
            "uk-icon-square-o" => "square-o",
            "uk-icon-star" => "star",
            "uk-icon-star-half" => "star-half",
            "uk-icon-star-half-empty" => "star-half-empty",
            "uk-icon-star-half-full" => "star-half-full",
            "uk-icon-star-half-o" => "star-half-o",
            "uk-icon-star-o" => "star-o",
            "uk-icon-sticky-note" => "sticky-note",
            "uk-icon-sticky-note-o" => "sticky-note-o",
            "uk-icon-street-view" => "street-view",
            "uk-icon-suitcase" => "suitcase",
            "uk-icon-sun-o" => "sun-o",
            "uk-icon-support" => "support",
            "uk-icon-tablet" => "tablet",
            "uk-icon-tachometer" => "tachometer",
            "uk-icon-tag" => "tag",
            "uk-icon-tags" => "tags",
            "uk-icon-tasks" => "tasks",
            "uk-icon-taxi" => "taxi",
            "uk-icon-television" => "television",
            "uk-icon-terminal" => "terminal",
            "uk-icon-thumb-tack" => "thumb-tack",
            "uk-icon-thumbs-down" => "thumbs-down",
            "uk-icon-thumbs-o-down" => "thumbs-o-down",
            "uk-icon-thumbs-o-up" => "thumbs-o-up",
            "uk-icon-thumbs-up" => "thumbs-up",
            "uk-icon-ticket" => "ticket",
            "uk-icon-times" => "times",
            "uk-icon-times-circle" => "times-circle",
            "uk-icon-times-circle-o" => "times-circle-o",
            "uk-icon-tint" => "tint",
            "uk-icon-toggle-down" => "toggle-down",
            "uk-icon-toggle-left" => "toggle-left",
            "uk-icon-toggle-off" => "toggle-off",
            "uk-icon-toggle-on" => "toggle-on",
            "uk-icon-toggle-right" => "toggle-right",
            "uk-icon-toggle-up" => "toggle-up",
            "uk-icon-trademark" => "trademark",
            "uk-icon-trash" => "trash",
            "uk-icon-trash-o" => "trash-o",
            "uk-icon-tree" => "tree",
            "uk-icon-trophy" => "trophy",
            "uk-icon-truck" => "truck",
            "uk-icon-tty" => "tty",
            "uk-icon-tv" => "tv",
            "uk-icon-umbrella" => "umbrella",
            "uk-icon-university" => "university",
            "uk-icon-unlock" => "unlock",
            "uk-icon-unlock-alt" => "unlock-alt",
            "uk-icon-unsorted" => "unsorted",
            "uk-icon-upload" => "upload",
            "uk-icon-usb" => "usb",
            "uk-icon-user" => "user",
            "uk-icon-user-plus" => "user-plus",
            "uk-icon-user-secret" => "user-secret",
            "uk-icon-user-times" => "user-times",
            "uk-icon-users" => "users",
            "uk-icon-video-camera" => "video-camera",
            "uk-icon-volume-down" => "volume-down",
            "uk-icon-volume-off" => "volume-off",
            "uk-icon-volume-up" => "volume-up",
            "uk-icon-warning" => "warning",
            "uk-icon-wheelchair" => "wheelchair",
            "uk-icon-wifi" => "wifi",
            "uk-icon-wrench" => "wrench",
        );

        $default = array(
            'key' => 'field_jm_'.$this->get_class_name()."_".JM_UTIL::slugify($label),
            'name' => JM_UTIL::slugify($label),
            'label' => $label,
            'type' => 'select',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => 'icon_select',
                'id' => '',
            ),
            'choices' => $choices,
            'default_value' => array(
            ),
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 1,
            'return_format' => 'value',
            'ajax' => 1,
            'placeholder' => '',
        );

        return array_merge($default, $args);

    }

    function doPosts($label, $args = array())
    {
        $default = array(
            'key' => 'field_jm_'.$this->get_class_name()."_".JM_UTIL::slugify($label),
            'name' => JM_UTIL::slugify($label),
            'label' => $label,
            'type' => 'post_object',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'post_type' => '',
            'taxonomy' => '',
            'allow_null' => 0,
            'multiple' => 1,
            'return_format' => 'object',
            'ui' => 1,
            'ajax' => 0,
        );

        return array_merge($default, $args);

    }

    function doLink($label, $args = array())
    {
        $default = array(
            'key' => 'field_jm_'.$this->get_class_name()."_".JM_UTIL::slugify($label),
            'name' => JM_UTIL::slugify($label),
            'label' => $label,
            'type' => 'link',
            'render' => 'icon',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'return_format' => 'array',
        );

        return array_merge($default, $args);

    }

    function doTab($label, $args = array())
    {
        $default = array(
            'key' => 'field_jm_'.$this->get_class_name()."_".JM_UTIL::slugify($label),
            'name' => JM_UTIL::slugify($label),
            'label' => $label,
            'type' => 'tab',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'placement' => 'top',
            'endpoint' => 0,
            'return_format' => 'array',
        );

        return array_merge($default, $args);

    }

    function doColorPicker($label, $args = array())
    {
        $default = array(
            'key' => 'field_jm_'.$this->get_class_name()."_".JM_UTIL::slugify($label),
            'name' => JM_UTIL::slugify($label),
            'label' => $label,
            'type' => 'color_picker',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'display_frontend' => 1,
            'default_value' => '',
        );

        return array_merge($default, $args);

    }

    function doFilePicker($label, $args = array())
    {
        $default = array(
            'key' => 'field_jm_'.$this->get_class_name()."_".JM_UTIL::slugify($label),
            'name' => JM_UTIL::slugify($label),
            'label' => $label,
            'type' => 'file',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'return_format' => 'array',
            'library' => 'all',
            'min_size' => '',
            'max_size' => '',
            'mime_types' => '',
        );

        return array_merge($default, $args);

    }

}

?>