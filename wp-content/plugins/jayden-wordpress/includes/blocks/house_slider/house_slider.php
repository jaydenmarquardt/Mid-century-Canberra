<?php

class house_slider extends JM_Block
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
            'post_type' => 'house',
            'posts_per_page' => 9,
        );
        $query = new WP_Query( $args );
        ?>
        <div>
            <h2 class="uk-text-center"><?php echo get_field("title");?></h2>
            <div class="uk-position-relative " tabindex="-1" uk-slider="finite: false;">

                <ul class="uk-slider-items uk-grid" uk-height-match="target: .post-base > .content;">
                    <?php if( $query->have_posts() ) :
                        while( $query->have_posts() ) :
                            $query->the_post();
                            ?>
                            <li class="uk-width-1-4">

                                <?php get_template_part( 'template-parts/post', get_post_type() ); ?>

                            </li>
                        <?php endwhile; endif; wp_reset_postdata(); ?>


                </ul>

                <a class="uk-position-center-left " href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                <a class="uk-position-center-right " href="#" uk-slidenav-next uk-slider-item="next"></a>

            </div>
            <div class="uk-flex uk-flex-center uk-margin-top">
                <a class="button" href="/houses">View All</a>

            </div>

        </div>

        <?php echo ob_get_clean();
    }

}