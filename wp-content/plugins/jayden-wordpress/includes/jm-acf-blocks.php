<?php

/**
 * Created by PhpStorm.
 * User: JAYDEN
 * Date: 26/09/2020
 */


class JM_Blocks {



    /**
     *
     * Blocks initialisation
     *
     * */
    public static function  init()
    {
        $blocks_folder = JM_PATH_INCLUDES."/blocks/";
        $files = scandir($blocks_folder);

        foreach ($files as $folder)
        {

            if(is_dir($blocks_folder.$folder))
            {

                $block_name = $folder;
                $block_folder = $blocks_folder.$folder;
                $block_file = "$block_folder/$block_name.php";

               if(file_exists($block_file))
               {
                   include_once $block_file;
                   new $block_name();
               }

            }
        }
    }


}