<?php


class JM_UTIL {

    public static function slugify($string)
    {
        return str_replace("-", "_",strtolower(trim(preg_replace('/[^A-Za-z0-9-\-]+/', '_', $string), '_')));;
    }

    public static function no_image()
    {
        return "/wp-content/uploads/2020/09/no.jpg";
    }

    public static function get_image($id, $size = "thumbnail")
    {
        return self::has_image($id, $size) ? get_the_post_thumbnail_url($id, $size) : self::no_image();
    }

    public static function has_image($id, $size = "thumbnail")
    {
        return get_the_post_thumbnail_url($id, $size) ? true : false;
    }

    /**
     * Returns bool if string Starts with substring
     **/
    static function startsWith ($haystack, $needle)
    {
        return substr($haystack, 0, strlen($needle)) === $needle;
    }

    /**
     * Returns bool if string Ends with substring
     **/
    static function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }

    /**
     * Returns bool if string contains substring
     **/
    static function contains($haystack, $needle)
    {
        return strpos($haystack, $needle) !== false;
    }

    /**
     * Replaces only the first occurance
     **/
    static function replaceFirst($haystack, $needle, $replace)
    {
        $pos = strpos($haystack, $needle);
        if ($pos !== false) {
            return substr_replace($haystack, $replace, $pos, strlen($needle));
        }

        return $haystack;
    }

}