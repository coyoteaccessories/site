define([
    "jquery",
    "jquery/ui"
], function ($) {
    "use strict";

    function main(config, element) {
        var $element = $(element);
        var AjaxUrl = config.AjaxUrl;
        var StoreUrl = config.StoreUrl;

        console.log("pdq fitment select");
        $(document).ready(function () {


            $(".select-fitment-label").on("click", function () {
                if ($(".fitment-label-direction").is(":visible")) {
                    $(".fitment-label-direction").toggleClass("fa-angle-right");
                    $(".fitment-label-direction").toggleClass("fa-angle-down");
                    $(".fitment-main-wrap").slideToggle("fast");
                }
            });

            $("#fitment_year").on('change', function () {
                $.ajax({
                    showLoader: true,
                    url: AjaxUrl,
                    data: {
                        year: $('#fitment_year').val()
                    },
                    beforeSend: function () {
                        $('#fitment_make').prop('selectedIndex', 0).attr('disabled', true);
                        $('#fitment_model').prop('selectedIndex', 0).attr('disabled', true);
                        $('#fitment_submodel').prop('selectedIndex', 0).attr('disabled', true);
                        $('#fitment_parts').attr('disabled', true);
                        $('#qualifier_wrap').html('');
                    },
                    success: function (resp) {
                        $('#fitment_make').attr('disabled', false);
                        $('#fitment_make').html(resp);
                        $('.make-wrapper').find(".fitment-loader").remove();
                    },
                    error: function (jqXHR, error, status) {

                    }
                });
            });

            $("#fitment_model").on('change', function () {
                var _AjaxUrl = AjaxUrl.replace("makes", "submodels");
                $.ajax({
                    showLoader: true,
                    url: _AjaxUrl,
                    data: {
                        year: $('#fitment_year').val(),
                        make: $('#fitment_make').val(),
                        model: $('#fitment_model').val()
                    },
                    beforeSend: function () {
                        $('#fitment_submodel').prop('selectedIndex', 0).attr('disabled', true);
                        $('#fitment_parts').attr('disabled', true);
                        $('#qualifier_wrap').html('');
                    },
                    success: function (resp) {
                        $('#fitment_submodel').attr('disabled', false);
                        $('#fitment_submodel').html(resp);
                    },
                    error: function (jqXHR, error, status) {

                    }
                });
            });
            $("#fitment_submodel").on('change', function () {
                var _AjaxUrl = AjaxUrl.replace("makes", "additionalcriteria");
                $.ajax({
                    showLoader: true,
                    url: _AjaxUrl,
                    data: {
                        year: $('#fitment_year').val(),
                        make: $('#fitment_make').val(),
                        model: $('#fitment_model').val(),
                        submodel: $('#fitment_submodel').val()
                    },
                    beforeSend: function () {
                        $('#fitment_parts').attr('disabled', false);
                        $(".select-fitment-label span").hide("slide", {direction: "left"}, 1500);
                    },
                    success: function (resp) {
                        $('.addl-criteria-question-wrapper').prepend(resp);
                        
                        if(resp!==''){
                            $('.addl-criteria-question-wrapper').removeClass("d-none");                            
                        }
                    },
                    error: function (jqXHR, error, status) {

                    }
                });
            });

            $("#fitment_make").on('change', function () {
                var _AjaxUrl = AjaxUrl.replace("makes", "models");
                $.ajax({
                    showLoader: true,
                    url: _AjaxUrl,
                    data: {
                        year: $('#fitment_year').val(),
                        make: $('#fitment_make').val()
                    },
                    beforeSend: function () {
                        $('#fitment_model').prop('selectedIndex', 0).attr('disabled', true);
                        $('#fitment_submodel').prop('selectedIndex', 0).attr('disabled', true);
                        $('#fitment_parts').attr('disabled', true);
                        $('#qualifier_wrap').html('');
                    },
                    success: function (resp) {
                        $('#fitment_model').attr('disabled', false);
                        $('#fitment_model').html(resp);
                        $('.make-wrapper').find(".fitment-loader").remove();
                    },
                    error: function (jqXHR, error, status) {

                    }
                });
            });

            $("button.criteria-option").on('click', function () {
                var option = $(this).data("selected");
                var criteria = $("#partMasterIds").val();

                if (option === 'no') {
                    criteria = "";
                }

                console.log("criteria" + criteria);
                $("#addl-criteria").val(criteria);

                $("button.criteria-option").removeClass("option-selected");
                $(this).addClass("option-selected");
            });
            

            $("#fitment_parts").on('click', function () {
                var qualifiers = [];
                var _qualifiers = [];
                var criteria = $("#addl-criteria").val();

                $('select[name="qualifiers[]"]').each(function (index) {
                    qualifiers.push($(this).val());
                    _qualifiers.push($(this).children("option:selected").data('title'));
                });

                var newurl = StoreUrl + 'fitment/index/categories/?year=' + $('#fitment_year').val() + '&make=' + $('#fitment_make').val() + '&model=' + $('#fitment_model').val() + '&submodel=' + $('#fitment_submodel').val() + '&qualifiers[]=' + qualifiers + '&_qualifiers[]=' + _qualifiers+'&criteria='+criteria;
                window.location = newurl;
            });

        });
    }
    ;
    return main;
});