<?php
/**
 * The default template for displaying content
 *
 * Used for both singular and index.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */
$entry_header_classes = "";

?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

    <header class="entry-header has-text-align-center<?php echo esc_attr( $entry_header_classes ); ?>">

        <div class="entry-header-inner section-inner medium">

            <?php

            $image = get_the_post_thumbnail_url();
            echo "<div style='text-align: center'><img style='display: inline-block' src='$image' width='400px'></div>";

            the_title( '<h1 class="entry-title">', '</h1>' );

            $DOB = get_field("dob");
            $source = get_field("source");

            echo "<h3 style='color:white'>Date of birth:</h3><small style='color:white'>$DOB</small>";
            ?>



        </div><!-- .entry-header-inner -->

    </header><!-- .entry-header -->


    <div class="poster post-inner <?php echo is_page_template( 'templates/template-full-width.php' ) ? '' : 'thin'; ?> ">

		<div class="entry-content">

			<?php
            the_content( __( 'Continue reading', 'twentytwenty' ) );
			?>
           <div> <h3 >Sources:</h3><small style=''><?php echo $source;?></small>
           </div>
            <h2>My Houses</h2>
            <?php
            $posts = get_field("houses");
            ?>

              <div class="uk-container uk-container-center">
                  <div class=" uk-grid uk-child-width-1-3@l uk-child-width-1-2@m uk-child-width-1-1@s uk-grid-margin" uk-height-match="target: .post-base > .content;">
                      <?php if( $posts ) :
                          foreach( $posts as $postOBJ ) :


                              global $post;
                              $post = get_post($postOBJ);
                              setup_postdata($post);

                              ?>
                              <div style="margin-bottom: 20px">

                                  <?php get_template_part( 'template-parts/post', get_post_type() ); ?>

                              </div>
                          <?php endforeach; endif; wp_reset_postdata(); ?>


                  </div>
              </div>



		</div><!-- .entry-content -->




        <?php



        /**
         *  Output comments wrapper if it's a post, or if comments are open,
         * or if there's a comment number – and check for password.
         * */
        if ( ( is_single() || is_page() ) && ( comments_open() || get_comments_number() ) && ! post_password_required() ) {
            ?>

            <div class="comments-wrapper section-inner">

                <?php comments_template(); ?>

            </div><!-- .comments-wrapper -->

            <?php
        }
        ?>

	</div><!-- .post-inner -->



</article><!-- .post -->
