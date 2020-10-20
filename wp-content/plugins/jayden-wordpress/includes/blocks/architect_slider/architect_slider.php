<?php

class architect_slider extends JM_Block
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
            'post_type' => 'architect',
            'posts_per_page' => 5,
        );
        $query = new WP_Query( $args );
        ?>
        <div>
            <h2 class="uk-text-center"><?php echo get_field("title");?></h2>
            <div class="arch-slider uk-position-relative " tabindex="-1" uk-slider="finite: false; center: true;">

                <ul class="uk-slider-items uk-grid" uk-height-match="target: .post-base > .content;">
                    <?php if( $query->have_posts() ) :
                        $i = 0;
                        while( $query->have_posts() ) :
                            $i++;
                            $query->the_post();
                            ?>
                            <li class="uk-width-1-1 <?php echo $i = 2? "uk-active": "";?>">

                                <?php get_template_part( 'template-parts/post', "architect-big" ); ?>

                            </li>
                        <?php endwhile; endif; wp_reset_postdata(); ?>


                </ul>

                <a class="uk-position-center-left " href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                <a class="uk-position-center-right arch-right " href="#" uk-slidenav-next uk-slider-item="next"></a>

            </div>
            <div class="uk-flex uk-flex-center uk-margin-top">
                <a class="button" href="/architect_tax/all-architects/">View All</a>

            </div>

        </div>
        <script>
            deferArch();

            function deferArch(method) {
                if (window.jQuery && window) {
                    jquery_jm_arch();
                } else {
                    setTimeout(function() { deferArch(method) }, 50);
                }
            }

            function jquery_jm_arch() {
                jQuery(function ($) {

                    $(document).ready(function () {
                        console.log("ready")
                        <?php if(!is_admin()):?>
                        UIkit.slider( $(".arch-slider")).show(1);
                        <?php endif;?>

                    });
                })
            }
        </script>

        <?php echo ob_get_clean();
    }

}