<?php

class JM_QUERY
{

    public static function init()
    {

        add_action( 'pre_get_posts', function( $query )
        {
            if( ! $query->is_main_query() )
                return;
            if ( is_tax( 'house_tax' ) )
            {
                $query->query_vars['posts_per_page'] = 50;
                return;
            }
        }, 1 );
    }


}