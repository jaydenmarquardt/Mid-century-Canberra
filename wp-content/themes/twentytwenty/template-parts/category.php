<?php
$archive_title    = '';
$archive_subtitle = '';

if(! have_posts() ) {
} elseif ( ! is_home() ) {
    $archive_title    = get_queried_object()->name;
    $archive_subtitle = get_the_archive_description();
}

if ( $archive_title || $archive_subtitle ) {
    ?>

    <header class="archive-header has-text-align-center header-footer-group">

        <div class="archive-header-inner section-inner medium">

            <?php if ( $archive_title ) { ?>
                <h1 class="archive-title"><?php echo wp_kses_post( $archive_title ); ?></h1>
            <?php } ?>

            <?php if ( $archive_subtitle ) { ?>
                <div class="archive-subtitle section-inner thin max-percentage intro-text"><?php echo wp_kses_post( wpautop( $archive_subtitle ) ); ?></div>
            <?php } ?>

        </div><!-- .archive-header-inner -->

    </header><!-- .archive-header -->

    <?php
}

if ( have_posts() ) {
?>
    <div class="uk-margin-large-top uk-container uk-container-center">
        <div class="uk-grid uk-grid-margin uk-child-width-1-3@l uk-child-width-1-2@m uk-child-width-1-1@s" uk-height-match="target: .post-base > .content;">

    <?php
    $i = 0;

    while ( have_posts() ) {
        $i++;

        the_post();
        echo "<div style='margin-bottom: 40px;;'>";
        get_template_part( 'template-parts/post', get_post_type() );
        echo "</div>";

    }
    ?>
        </div>
    </div>
    <?php
}

get_template_part( 'template-parts/pagination' ); ?>