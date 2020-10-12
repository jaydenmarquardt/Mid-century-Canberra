<?php

use ScssPhp\ScssPhp\Compiler;
use MatthiasMullie\Minify;
use MatthiasMullie\PathConverter\Converter;

class JM_DEV {

    public static function is_dev()
    {
        return true;
    }

    public static function compile_scss($styles)
    {
        $contents = "";
        foreach ($styles as $style)
        {
            if(is_file($style))
            {
                $contents .= "\n\n".file_get_contents($style);
            }else{
                $contents .= "\n\n".$style;
            }
        }

        require_once JM_PATH_VENDOR."compiler/scss.inc.php";
        $scss = new Compiler();

        return $scss->compile($contents);
    }

    public static function minify($output, $files, $type = "CSS")
    {
        require_once JM_PATH_VENDOR."minifier/src/Minify.php";
        require_once JM_PATH_VENDOR."minifier/src/CSS.php";
        require_once JM_PATH_VENDOR."minifier/src/JS.php";
        require_once JM_PATH_VENDOR . '/minifier/src/Exception.php';
        require_once JM_PATH_VENDOR . '/minifier/src/Exceptions/BasicException.php';
        require_once JM_PATH_VENDOR . '/minifier/src/Exceptions/FileImportException.php';
        require_once JM_PATH_VENDOR . '/minifier/src/Exceptions/IOException.php';
        require_once JM_PATH_VENDOR . '/pathconverter/src/ConverterInterface.php';
        require_once JM_PATH_VENDOR . '/pathconverter/src/Converter.php';

        if($type == "CSS"){
            $minifier = new Minify\CSS("");
        }else{
            $minifier = new Minify\JS("");
        }

        foreach ($files as $file)
        {
            $minifier->add($file);
        }


        $minifier->minify($output);
    }

    public static function enqueue_assets()
    {


        if(self::is_dev())
        {
            $scripts = apply_filters("jm_add_asset_script", []);
            $styles = apply_filters("jm_add_asset_styles", []);

            $style = self::compile_scss($styles);

            //only compile
            file_put_contents(JM_PATH_ASSETS."jm_master_css.css", $style);

//            self::minify(JM_PATH_ASSETS."jm_master_css.css", [$style]);
//            self::minify(JM_PATH_ASSETS."jm_master_js.js", $scripts, "JS");
        }
    }

}