<?php
/**
 * Template Name: Locate Page Template
 *
 * @package WordPress
 * @subpackage JM Wordpress
 * @since 2020
 */

$args = array(
    'post_type' => 'house',
    'posts_per_page' => -1,
);
$query = new WP_Query( $args );

$markers = [];

foreach ($query->get_posts() as $post)
{
    $loc = get_field("address", $post);
//    echo "<pre>"; var_dump($loc);echo "</pre>";
    $markers[] = [
            "position" => [
                "lat" => $loc["lat"],
                "lng" => $loc["lng"]
            ],
            "title" => $post->post_title,
            "id" => $post->ID,
            "slug" => JM_UTIL::slugify($post->post_name),
            "icon" => JM_UTIL::get_image($post->ID),
            "content" => "<div class='info_box'>$post->post_title<br>$loc[address]<br>$post->post_title</div>"


    ];
}
//echo "<pre>"; var_dump($markers);echo "</pre>";

get_header();

?>

<main id="site-content" role="main">

    <?php

    if ( have_posts() ) {

        while ( have_posts() ) {
            the_post();

            ?>

                <div class="uk-grid uk-grid-collapse">

                    <div class="uk-width-1-3@m uk-width-1-1@s">

                        <div><h2 class='uk-text-center'>Houses</h2></div>
                        <div class="locate-houses uk-height-1-1">

                            <?php

                            if ( $query->have_posts() ) :

                                echo "<div class='uk-child-width-1-2@m uk-child-width-1-1@s uk-grid-small uk-grid-margin' uk-grid  uk-height-match=\"target: .post-base > .content;\">";
                                global $house_to_text;
                                $house_to_text = "Go To";
                                while ($query->have_posts() ) :
                                    $query->the_post();
                                    echo "<div><div class='uk-position-relative' style='height: 600px;'>";
                                    get_template_part( 'template-parts/post', get_post_type() );
                                    echo "<a class='big-clicker' style='z-index:15' href='#' data-map-location='".get_the_ID()."'></a></div></div>";

                                endwhile;
                                wp_reset_postdata();
                                echo "</div>";
                            endif;?>


                        </div>

                    </div>
                    <div class="uk-width-2-3@m uk-width-1-1@s">
                        <div><h2 class='uk-text-center'>House Locations</h2></div>

                        <div>

                            <div id="googleMap" style="width:100%;height:90vh;"></div>

                        </div>

                    </div>


                </div>


            <?php
        }
    }

    ?>

</main>

<?php get_template_part( 'template-parts/footer-menus-widgets' ); ?>

<script defer>

    var map;
    var service;
    var infowindow;
    var markers = [];



    function initMap() {
        var center = new google.maps.LatLng(<?php echo $markers[0]["position"]["lat"].", ". $markers[0]["position"]["lng"];?>);

        infowindow = new google.maps.InfoWindow();

        map = new google.maps.Map(
            document.getElementById('googleMap'), {center: center, zoom: 15});
        myoverlay = new google.maps.OverlayView();
        myoverlay.draw = function () {
            this.getPanes().markerLayer.id='markerLayer';
        };
        myoverlay.setMap(map);



        <?php

        foreach ($markers as $slug => $place)
        {
            echo " var image_$place[slug] = { 
                    url: '$place[icon]',
                  size: new google.maps.Size(120, 120),
                  origin: new google.maps.Point(0, 0),
                  anchor: new google.maps.Point(17, 34),
                  scaledSize: new google.maps.Size(100, 100),
                };";
            echo "var info_$place[slug] = new google.maps.InfoWindow({ content: '".addslashes($place["content"])."' });";
            echo "var marker_$place[slug] = new google.maps.Marker({
                     position: ".json_encode($place["position"]).",
                    map: map,
                    animation: google.maps.Animation.DROP,
                    title: '$place[title]',
                    icon: image_$place[slug],
                    optimized: false,
               
                   });";
            echo "google.maps.event.addListener(marker_$place[slug], 'mouseover', function() {
                     info_$place[slug].open(map, marker_$place[slug]);
               });google.maps.event.addListener(marker_$place[slug], 'mouseout', function() {
                     info_$place[slug].close();
               });";
            echo "markers[$place[id]] = ".json_encode($place["position"]).";";




        }

        ?>

    }
    defer();

    function defer(method) {
        if (window.jQuery) {
           jquery_jm();
        } else {
            setTimeout(function() { defer(method) }, 50);
        }
    }

    function jquery_jm() {
        jQuery(function ($) {

            $(document).ready(function () {

                $("[data-map-location]").on("click", function (event) {
                    event.preventDefault();
                    $pid = $(this).attr("data-map-location");
                    $loc = markers[$pid];
                    map.panTo(new google.maps.LatLng(  $loc.lat, $loc.lng ));
                    setTimeout(function(){
                        smoothZoom( 18, map.getZoom(), true);

                    }, 200);

                })
            });
        })
    }

    function smoothZoom (level, cnt, mode) {
        //alert('Count: ' + cnt + 'and Max: ' + level);

        // If mode is zoom in
        if(mode == true) {

            if (cnt >= level) {
                return;
            }
            else {
                var z = google.maps.event.addListener(map, 'zoom_changed', function(event){
                    google.maps.event.removeListener(z);
                    smoothZoom(map, level, cnt + 1, true);
                });
                setTimeout(function(){map.setZoom(cnt)}, 80);
            }
        } else {
            if (cnt <= level) {
                return;
            }
            else {
                var z = google.maps.event.addListener(map, 'zoom_changed', function(event) {
                    google.maps.event.removeListener(z);
                    smoothZoom(map, level, cnt - 1, false);
                });
                setTimeout(function(){map.setZoom(cnt)}, 100);
            }
        }
    }


</script>

<style>

    <?php

     foreach ($markers as $slug => $place)
        {

            echo " #googleMap img[src='$place[icon]']{";
            echo "border-radius: 50%; border: 2px solid #eee026; transition: all 0.2s ease-in-out; }";
       }

    ?>
    #googleMap img.marker-click
    {
        border-radius: 80%;
    }
</style>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo acf_get_setting("google_api_key");?>&libraries=places&callback=initMap"></script>

<?php get_footer(); ?>
