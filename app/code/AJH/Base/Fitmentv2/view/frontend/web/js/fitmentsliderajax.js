define([
    "jquery",
    "jquery/ui"
], function ($) {
    "use strict";

    var ModelsUrl;

    function main(config, element) {
//        var $element = $(element);
//        var AjaxUrl = config.AjaxUrl;
//        var StoreUrl = config.StoreUrl;
//        var YearsUrl = config.YearsUrl;
//        var MakesUrl = config.MakesUrl;
//        ModelsUrl = config.ModelsUrl;
//        var SubModelsUrl = config.SubModelsUrl;

        var slider_carousel = carouselSlider();
        slider_carousel.trigger('refresh.owl.carousel');

        reloadElements(config, slider_carousel);
    }

    function reloadElements(config, slider_carousel) {
        $(".fitment-slider-make").on("click", function (e) {
            e.preventDefault();

            var _url = $(this).data("url");

            $.ajax({
                url: _url,
                dataType: 'json',
                showLoader: true,
                data: {
                    makeID: parseInt($(this).data("makeid"))
                },
                beforeSend: function () {
//                      $(".homepage-slider-loading-wrapper").show("fast");                        
                },
                success: function (resp) {
                    $(".fitment-to-select").text("Year");
                    $("#fitmentSlider").html(resp.outputHtml);
                    slider_carousel.trigger('destroy.owl.carousel');
                    slider_carousel.html(slider_carousel.find('.owl-stage-outer').html()).removeClass('owl-loaded');
                    carouselSlider();
                    reloadElements(config, slider_carousel);
//                      $(".homepage-slider-loading-wrapper").hide("fast");
                },
                error: function (jqXHR, error, status) {
                    console.log("Slider Years:" + status);
                }
            });
        });

        $(".fitment-slider-year").on("click", function (e) {
            e.preventDefault();
            var year = $(this).data("year");
            var make = $(this).data("makeid");
            var _url = $(this).data("url");
            $.ajax({
                url: _url,
                dataType: 'json',
                showLoader: true,
                data: {
                    year: parseInt(year),
                    make: parseInt(make)
                },
                beforeSend: function () {
//                    $(".homepage-slider-loading-wrapper").show("fast");
                },
                success: function (resp) {
                    $(".fitment-to-select").text("Model");
                    $("#fitmentSlider").html(resp.outputHtml);
                    slider_carousel.trigger('destroy.owl.carousel');
                    slider_carousel.html(slider_carousel.find('.owl-stage-outer').html()).removeClass('owl-loaded');
                    carouselSlider();
                    reloadElements(config, slider_carousel);
                },
                error: function (jqXHR, error, status) {
                    console.log(error);
                }
            });
        });

        $(".fitment-slider-model").on("click", function (e) {
            e.preventDefault();

            var year = $(this).data("year");
            var make = $(this).data("makeid");
            var model = $(this).data("modelid");

            $.ajax({
                url: $(this).data("url"),
                dataType: 'json',
                showLoader: true,
                data: {
                    year: parseInt(year),
                    make: parseInt(make),
                    model: parseInt(model)
                },
                beforeSend: function () {
//                    $(".homepage-slider-loading-wrapper").show("fast");
                },
                success: function (resp) {
                    $(".fitment-to-select").text("Submodel");
                    $("#fitmentSlider").html(resp.outputHtml);
                    slider_carousel.trigger('destroy.owl.carousel');
                    slider_carousel.html(slider_carousel.find('.owl-stage-outer').html()).removeClass('owl-loaded');
                    carouselSlider();

                    reloadElements(config, slider_carousel);
                },
                error: function (jqXHR, error, status) {
                    console.log(error);
                }
            });
        });

        $(".fitment-slider-submodel").on("click", function (e) {
            e.preventDefault();

            $('body').trigger('show.loader');
            
            var url = $(this).data("url");
            var year = $(this).data("year");
            var make = $(this).data("makeid");
            var model = $(this).data("modelid");
            var submodel = $(this).data("submodelid");

            var category_url = url + "?year=" + year + "&make=" + make + "&model=" + model + "&submodel=" + submodel;

            console.log(category_url);

            window.location = category_url;
        });
    }

    function fitmentSlider() {
        return {
            year: 0,
            makeID: 0,
            make: 0,
            loadYears: function (makeID) {
                fitmentSlider.makeID = parseInt(makeID);
                $.ajax({
                    url: YearsUrl,
                    dataType: 'json',
                    data: {
                        makeID: parseInt(makeID)
                    },
                    beforeSend: function () {
//                            $(".homepage-slider-loading-wrapper").show("fast");
                    },
                    success: function (resp) {
                        fitmentSlider.setYears(resp.outputHtml);
                        slider_caoursel.trigger('refresh.owl.carousel');
//                            $(".homepage-slider-loading-wrapper").hide("fast");
                    },
                    error: function (jqXHR, error, status) {

                    }
                });
            },
            setYears: function (resp) {
//                    $(".fitment-slider-row .fitment-group-makes").hide();
//                    $(".fitment-slider-row .fitment-group-years").remove();
                $("#fitmentSlider").html(resp);
            },
            loadModels: function (year, make) {
                fitmentSlider.year = year;
                fitmentSlider.make = make;
                $.ajax({
                    url: ModelsUrl,
                    dataType: 'json',
                    data: {
                        year: parseInt(year),
                        make: parseInt(make)
                    },
                    beforeSend: function () {
                        $(".homepage-slider-loading-wrapper").show("fast");
                    },
                    success: function (resp) {
                        fitmentSlider.setModels(resp.outputHtml);
                        $(".homepage-slider-loading-wrapper").hide("fast");
                    },
                    error: function (jqXHR, error, status) {
                        console.log(error);
                    }
                });
            },
            setModels: function (resp) {
                $(".fitment-slider-row .fitment-group-years").hide();
                $(".fitment-slider-row .fitment-group-models").remove();
                $(".fitment-slider-row").prepend(resp);
            },
            loadSubModels: function (model, url) {
                var year = fitmentSlider.year;
                var make = fitmentSlider.make;

                fitmentSlider.model = model;

                $.ajax({
                    url: SubModelsUrl,
                    data: {
                        year: parseInt(year),
                        make: parseInt(make),
                        model: parseInt(model)
                    },
                    dataType: 'json',
                    beforeSend: function () {
                        $(".homepage-slider-loading-wrapper").show("fast");
                    },
                    success: function (resp) {
                        fitmentSlider.setSubModels(resp.outputHtml);
                        $(".homepage-slider-loading-wrapper").hide("fast");
                    },
                    error: function (jqXHR, error, status) {

                    }
                });
            },
            setSubModels: function (resp) {
                $(".fitment-slider-row .fitment-group-models").hide();
                $(".fitment-slider-row .fitment-group-submodels").remove();
                $(".fitment-slider-row").prepend(resp);
            },
            loadParts: function (submodel, url, cat) {
                var year = fitmentSlider.year;
                var make = fitmentSlider.make;
                var model = fitmentSlider.model;

                $(".homepage-slider-loading-wrapper").show("fast");

                window.location = url + '?year=' + year + '&make=' + make + '&model=' + model + '&submodel=' + submodel + '&cat=' + cat;
            },
            prev: function (ths) {
                var elements = $(ths).parents(".fitment-slider-row").children(".fitment-group-wrapper");

                elements.each(function (indx) {
                    var $this = $(this);
                    if ($this.is(":visible")) {
                        $this.hide('fast', function () {
                            $this.next(".fitment-group-wrapper").toggle();
                        });
                    }
                });
            },
            next: function (ths) {
                var elements = $(ths).parents(".fitment-slider-row").children(".fitment-group-wrapper");

                elements.each(function (indx) {
                    var $this = $(this);
                    if ($this.is(":visible")) {
                        $this.hide('fast', function () {
                            $this.next(".fitment-group-wrapper").show();
                        });
                    }
                });
            }
        };

    }

    function carouselSlider() {
        var carousel = $("#fitmentSlider").owlCarousel({
            autoPlay: true,
            margin: 10,
            items: 5,
            itemsDesktop: [1199, 5],
            itemsDesktopSmall: [979, 5],
            itemsTablet: [768, 5],
            dots: false,
            pagination: false,
            loop: true,
            responsiveClass: true,
            navigation: false,
            nav: true,
            navText: [
                '<i class="fa fa-angle-left" aria-hidden="true"></i>',
                '<i class="fa fa-angle-right" aria-hidden="true"></i>'
            ],
            navContainer: '.main-content .custom-nav',
            responsive: {
                0: {
                    items: 1,
                    nav: true
                },
                414: {
                    items: 2,
                    nav: false,
                    slideBy: 2
                },
                560: {
                    items: 3,
                    nav: false
                },
                768: {
                    items: 4,
                    nav: false,
                    slideBy: 4
                },
                1000: {
                    items: 5,
                    nav: true,
                    loop: false,
                    slideBy: 5
                }
            }
        });

        return carousel;
    }

    return main;
});
