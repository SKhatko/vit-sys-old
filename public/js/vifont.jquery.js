/*
 * vifont plugin
 * dependancy: jQuery
 * author: Shady AF (VITisch UG)
 */
(function ($) {

    $.fn.vifont = function (options) {

        var settings = $.extend({
            'fontHeight': 45,
            'fontsImage': '/img/google-fonts.gif',
            'displayActiveFont': true,
            'enableFilter': true,
            'filter': 'Filter',
            'filterSelector': '#font-filter-input',
            'active': 'Active',
            'activeFont': null,
            'fontPicked': function (fontName) {
                alert(fontName)
            },
            'widgetOpened': function () {
            },
            'selectFont': 'Select font'


        }, options);

        var fonts = [
            'Open+Sans',
            'Oswald',
            'Pacifico',
            'David+Libre',
            'Roboto',
            'Slabo+27px',
            'Lato',
            'Yatra+One',
            'Raleway',
            'Source+Sans+Pro',
            'Montserrat',
            'Lora',
            'PT+Sans',
            'Open+Sans+Condensed',
            'Roboto+Slab',
            'Droid+Sans',
            'Baloo+Bhaina',
            'Playfair+Display',
            'PT+Serif',
            'Indie+Flower',
            'Inconsolata',
            'Dosis',
            'BioRhyme',
            'Oxygen',
            'Lobster',
            'Nunito',
            'Sumana',
            'Abel',
            'Libre+Baskerville',
            'Josefin+Sans',
            'Exo+2',
            'Caveat+Brush',
            'Poiret+One',
            'Dancing+Script',
            'Shadows+Into+Light',
            'Play',
            'Vollkorn',
            'Maven+Pro',
            'Fira+Sans',
            'Rokkitt',
            'Abril+Fatface',
            'Amatic+SC',
            'Monda',
            'Quattrocento+Sans',
            'Modak',
            'Gloria+Hallelujah',
            'Lobster+Two',
            'Architects+Daughter',
            'Yellowtail',
            'Domine',
            'Hammersmith+One',
            'UnifrakturCook',
            'Kaushan+Script',
            'BenchNine',
            'Coming+Soon',
            'Old+Standard+TT',
            'Cinzel',
            'Kreon',
            'Shrikhand',
            'Satisfy',
            'Courgette',
            'Amaranth',
            'Philosopher',
            'Oleo+Script',
            'Aldrich',
            'Didact+Gothic',
            'Arima+Madurai',
            'Playball',
            'Tangerine',
            'Kalam',
            'Rancho',
            'Vidaloka',
            'Rasa',
            'Eczar',
            'Space+Mono',
            'Neuton',
            'Mate',
            'Shadows+Into+Light+Two',
            'Mogra',
            'Radley',
            'Hind+Siliguri',
            'Hind+Madurai',
            'Cutive',
            'Ewert',
            'Fruktur',
            'Covered+By+Your+Grace',
            'Patrick+Hand',
            'Asul',
            'Damion',
            'Prompt',
            'Alike+Angular',
            'Montez',
            'Special+Elite',
            'Neucha',
            'Ranga',
            'Calligraffitti',
            'Scada',
            'Unica+One'
        ];

        var obj = this;
        obj.html = '';

        /*
         if (settings.enableFilter) {
         var filterHtml = '<div id="filter-holder">';
         filterHtml += '<input name="font_filter" id="font-filter-input" placeholder="'+settings.filter+'" />';
         filterHtml += '</div>';
         obj.append(filterHtml);
         }
         */
        //Add feature
        //@TODO


        for (var i = 0; i < fonts.length; i++) {

            var height = settings.fontHeight;
            var backgroundY = height * i;
            var thisFont = fonts[i];

            var html = '<div class="font-holder" data-font="' + thisFont + '">';
            html += '<div class="font-controls">';
            html += '<span class="font-info">' + cleanFontName(thisFont) + '</span>';
            html += '<a href="javascript:;" class="pick-font-btn" data-font="' + thisFont + '">' + settings.selectFont + '</a>';
            html += '<span class="active-holder">' + settings.active + '</span>';
            html += '</div>';
            html += '<div class="font-img" style="background-position:0 -' + backgroundY + 'px; height:' + height + 'px;"></div>';
            html += '</div>';

            if (settings.displayActiveFont && settings.activeFont && settings.activeFont == thisFont) {
                alert(thisFont);
                obj.prepend(html);
            }
            else {
                obj.append(html);
            }
        }

        $(".pick-font-btn").click(function () {
            settings.fontPicked($(this).attr('data-font'));
        });

        obj.setSetting = function (setting, value) {
            if (setting in settings) {
                settings[setting] = value;
                if (setting == 'fontPicked') {
                    $(".pick-font-btn").unbind('click');
                    $(".pick-font-btn").click(function () {
                        settings.fontPicked($(this).attr('data-font'));
                    });
                }
                else if (setting == 'activeFont') {

                    $(".font-holder.active").removeClass('active');
                    var selectedFontDiv = $("div[data-font='" + value + "']");
                    $("#active-font-seperator").remove();

                    if (selectedFontDiv) {
                        var seperatorHtml = '<hr id="active-font-seperator">';
                        obj.prepend(seperatorHtml);
                        obj.prepend(selectedFontDiv);
                        selectedFontDiv.addClass('active');
                    }
                }
            }
        }

        if (settings.enableFilter) {
            $(settings.filterSelector).on('keyup', function () {
                var text = $(this).val();

                text = reverseCleanFontName(text).toLowerCase();

                for (var i = 0; i < fonts.length; i++) {

                    $fontHolder = $(".font-holder[data-font='" + fonts[i] + "']");

                    if (fonts[i].toLowerCase().indexOf(text) === -1) {
                        $fontHolder.hide();
                    }
                    else {
                        $fontHolder.show();
                    }
                }
            });
        }

        return obj;

        function cleanFontName(font) {
            return font.split('+').join(' ');
        }

        function reverseCleanFontName(font) {
            return font.split(' ').join('+');
        }
    }

}(jQuery));