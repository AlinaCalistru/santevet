jssor_1_slider_init = function() {

    var jssor_1_SlideoTransitions = [
        [{b:-1,d:1,o:-0.7}],
        [{b:900,d:2000,x:-379,e:{x:7}}],
        [{b:900,d:2000,x:-379,e:{x:7}}],
        [{b:-1,d:1,o:-1,sX:2,sY:2},{b:0,d:900,x:-171,y:-341,o:1,sX:-2,sY:-2,e:{x:3,y:3,sX:3,sY:3}},{b:900,d:1600,x:-283,o:-1,e:{x:16}}]
    ];

    var jssor_1_options = {
        $AutoPlay: 1,
        $SlideDuration: 800,
        $SlideEasing: $Jease$.$OutQuint,
        $CaptionSliderOptions: {
            $Class: $JssorCaptionSlideo$,
            $Transitions: jssor_1_SlideoTransitions
        },
        $ArrowNavigatorOptions: {
            $Class: $JssorArrowNavigator$
        },
        $BulletNavigatorOptions: {
            $Class: $JssorBulletNavigator$
        }
    };

    var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);

    /*#region responsive code begin*/

    var MAX_WIDTH = 3000;

    function ScaleSlider() {
        var containerElement = jssor_1_slider.$Elmt.parentNode;
        var containerWidth = containerElement.clientWidth;

        if (containerWidth) {

            var expectedWidth = Math.min(MAX_WIDTH || containerWidth, containerWidth);

            jssor_1_slider.$ScaleWidth(expectedWidth);
        }
        else {
            window.setTimeout(ScaleSlider, 30);
        }
    }

    ScaleSlider();

    $Jssor$.$AddEvent(window, "load", ScaleSlider);
    $Jssor$.$AddEvent(window, "resize", ScaleSlider);
    $Jssor$.$AddEvent(window, "orientationchange", ScaleSlider);
    /*#endregion responsive code end*/
};

initMap = function(){

    var map;
    var options = {
        zoom: 17,
        center:  new google.maps.LatLng(47.168574, 27.556774),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map($('#map')[0], options);

    var location = {lat: 47.168574, lng: 27.556774};
    var contentString = '<div id="map-content">'+

        '<h1 id="firstHeading" class="firstHeading">Clinica Veterinară Sante Vet</h1>'+
        '<div id="bodyContent">'+
        '<p><b>Adresa:</b> Strada Tabacului, nr. 1B, bl. 254 Iasi, Romania</p>'+
        '<p><b>Telefon:</b> <a href="tel:+40 755 266 508">+40 755 266 508</a></p>'+
        '<p><b>E-mail:</b><a href="mailto:info@santevet.ro">info@santevet.ro</a></p>'+
        '</div>'+
        '</div>';

    var infowindow = new google.maps.InfoWindow({
        content: contentString
    });
    var marker = new google.maps.Marker({
        position: location,
        label: 'S',
        map: map
    });
    marker.addListener('click', function() {
        infowindow.open(map, marker);
    });
};

jQuery(document).ready(function ($) {

    // Init Main Slider
    jssor_1_slider_init();

    $('#submitButton').on('click', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'form.php',
            type: 'POST',
            dataType: 'json',
            data: $('#contact-form').serialize(),
            success: function(message) {
                if (message.status == "success"){
                    $('#contact-form')[0].reset();
                    $('#errors').html(message.message[0] + '<br/>').delay(3000).fadeToggle();
                } else {
                    for (var i=0; i < message.message.length; i++){
                        $('#errors').append(message.message[i] + '<br/>')
                    }
                }
            }
        });
    });


});


