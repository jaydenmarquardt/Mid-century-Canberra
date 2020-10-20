<?php

class posts extends JM_Block
{


    public function get_fields()
    {

        $fields = array();
        $fields[] = $this->doTextBox("Title");

        return $fields;
    }

    public function init(){


        $this
            ->register_block()
            ->add_field_group();
    }


    public function render($variables)
    {
        ob_start();
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => 3,
            'category' => 2
        );
        $query = new WP_Query( $args );
        ?>
        <div>
            <h2 class="uk-text-center"><?php echo get_field("title");?></h2>
            <div  uk-grid uk-grid-margin uk-height-match="target: .post-base > .content;" >
                 <?php if( $query->have_posts() ) :
                        while( $query->have_posts() ) :
                            $query->the_post();
                            ?>
                            <div class="uk-width-1-3@xl uk-width-1-2@m uk-width-1-1@s ">

                                <?php get_template_part( 'template-parts/post', get_post_type() ); ?>

                            </div>
                        <?php endwhile; endif; wp_reset_postdata(); ?>

            </div>
            <div class="uk-flex uk-flex-center uk-margin-top">
                <a class="button" href="/category/news">View All</a>

            </div>

        </div>

        <?php echo ob_get_clean();
    }

}