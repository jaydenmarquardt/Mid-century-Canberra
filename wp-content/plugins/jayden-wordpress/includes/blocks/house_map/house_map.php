<?php

class house_map extends JM_Block
{


    public function get_fields()
    {

        $fields = array();

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
            'posts_per_page' => -1,
        );
        $query = new WP_Query( $args );

        $markers = [];

        foreach ($query->get_posts() as $post)
        {
            $loc = get_field("address", $post);
            $markers[] = [
                "position" => [
                    "lat" => $loc["lat"],
                    "lng" => $loc["lng"]
                ],
                "title" => $post->post_title,
                "id" => $post->ID,
                "slug" => JM_UTIL::slugify($post->post_name),
                "icon" => get_the_post_thumbnail_url($post->ID),
                "content" => "<div class='info_box'>$post->post_title<br>$loc[address]<br>$post->post_title</div>"


            ];
        }
        ?>
        <div class="map-container">
            <div uk-parallax="y: -200, 200" id="googleMap" style="width:100%;height:100vh;"></div>

        </div>

        <script defer>

            var map;
            var service;
            var infowindow;
            var markers = [];



            function initMap() {
                var center = new google.maps.LatLng(<?php echo $markers[2]["position"]["lat"].", ". $markers[2]["position"]["lng"];?>);

                infowindow = new google.maps.InfoWindow();

                map = new google.maps.Map(
                    document.getElementById('googleMap'), {center: center, zoom: 14});
                myoverlay = new google.maps.OverlayView();
                myoverlay.draw = function () {
                    this.getPanes().markerLayer.id='markerLayer';
                };
                myoverlay.setMap(map);
                var styledMapType = new google.maps.StyledMapType([
                    {
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#212121"
                            }
                        ]
                    },
                    {
                        "elementType": "labels.icon",
                        "stylers": [
                            {
                                "visibility": "off"
                            }
                        ]
                    },
                    {
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "color": "#757575"
                            }
                        ]
                    },
                    {
                        "elementType": "labels.text.stroke",
                        "stylers": [
                            {
                                "color": "#212121"
                            }
                        ]
                    },
                    {
                        "featureType": "administrative",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#757575"
                            }
                        ]
                    },
                    {
                        "featureType": "administrative.country",
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "color": "#9e9e9e"
                            }
                        ]
                    },
                    {
                        "featureType": "administrative.land_parcel",
                        "stylers": [
                            {
                                "visibility": "off"
                            }
                        ]
                    },
                    {
                        "featureType": "administrative.locality",
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "color": "#bdbdbd"
                            }
                        ]
                    },
                    {
                        "featureType": "poi",
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "color": "#757575"
                            }
                        ]
                    },
                    {
                        "featureType": "poi.park",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#181818"
                            }
                        ]
                    },
                    {
                        "featureType": "poi.park",
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "color": "#616161"
                            }
                        ]
                    },
                    {
                        "featureType": "poi.park",
                        "elementType": "labels.text.stroke",
                        "stylers": [
                            {
                                "color": "#1b1b1b"
                            }
                        ]
                    },
                    {
                        "featureType": "road",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {
                                "color": "#2c2c2c"
                            }
                        ]
                    },
                    {
                        "featureType": "road",
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "color": "#8a8a8a"
                            }
                        ]
                    },
                    {
                        "featureType": "road.arterial",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#373737"
                            }
                        ]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#3c3c3c"
                            }
                        ]
                    },
                    {
                        "featureType": "road.highway.controlled_access",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#4e4e4e"
                            }
                        ]
                    },
                    {
                        "featureType": "road.local",
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "color": "#616161"
                            }
                        ]
                    },
                    {
                        "featureType": "transit",
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "color": "#757575"
                            }
                        ]
                    },
                    {
                        "featureType": "water",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#000000"
                            }
                        ]
                    },
                    {
                        "featureType": "water",
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "color": "#3d3d3d"
                            }
                        ]
                    }
                ]);
                map.mapTypes.set('styled_map', styledMapType);
                map.setMapTypeId('styled_map');

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

        <?php echo ob_get_clean();
    }

}