define([
    'jquery',
    'mage/smart-keyboard-handler',
    'jquery/ui',
    'mage/mage',
    'mage/ie-class-fixer',
    'carouselInit',
    'blockCollapse',
    'animateNumber'
], function($) {
    'use strict';
    $.widget('Zemez.theme', {
        options: {
            relatedCarousel: {
                selector: ".catalog-product-view .block.related .product-items",
                params: {
                    dots: false,
                    items: 3,
                    responsive: {
                        0 : {
                            items: 1
                        },
                        400 : {
                            items: 2
                        },
                        768 : {
                            items: 3
                        },
                        1024 : {
                            items: 4
                        }
                    }
                }
            },
            upsellCarousel: {
                selector: ".catalog-product-view .block.upsell .product-items",
                params: {
                    dots: false,
                    items: 3,
                    responsive: {
                        0 : {
                            items: 1
                        },
                        400 : {
                            items: 2
                        },
                        768 : {
                            items: 3
                        },
                        1024 : {
                            items: 4
                        }
                    }
                }
            },
            crosssellCarousel: {
                selector: ".block.crosssell .product-items",
                params: {}
            },
            animatedCounter: {
                selector: ".skills .number",
                speed: 2000
            },
            testimonialsCarousel: {
                selector: ".owl-testimonials",
                params: {
                    loop: true,
                    dotsSpeed: true,
                    nav: false,
                    items: 1,
                    responsive: {
                        0: {
                            items: 1
                        },
                        480: {
                            items: 1
                        },
                        768: {
                            items: 1
                        },
                        1024: {
                            items: 1
                        }
                    }
                }
            }
        },
        // Magento blank theme theme.js
        _checkoutCollapsible: function() {
            if ($('body').hasClass('checkout-cart-index')) {
                if ($('#co-shipping-method-form .fieldset.rates').length > 0 &&
                    $('#co-shipping-method-form .fieldset.rates :checked').length === 0
                ) {
                    $('#block-shipping').on('collapsiblecreate', function() {
                        $('#block-shipping').collapsible('forceActivate');
                    });
                }
            }
        },
        // Magento blank theme theme.js end
        _sidebarCollapsible: function() {
            /* Sidebar block collapse in mobile */
            var block = $(".sidebar-additional .block");
            if (block.length > 0) {
                block.sidebarCollapse();
            }
        },
        _initProductsCarousel: function(selector) {
            var limit = $(selector).data('limit'),
                itemsCount = 1;
            if (limit != 0) {
                $('.product-item', selector).each(function() {
                    if (itemsCount > limit) {
                        $(this).remove();
                    }
                    itemsCount++;
                });
            }
        },
        _productsCarousel: function() {
            /* Related init */
            var relatedCarouselData = this.options.relatedCarousel;
            this._initProductsCarousel('.catalog-product-view .block.related');
            $(relatedCarouselData.selector).carouselInit(relatedCarouselData.params);
            /* Upsell init */
            var upsellCarouselData = this.options.upsellCarousel;
            this._initProductsCarousel('.catalog-product-view .block.upsell');
            $(upsellCarouselData.selector).carouselInit(upsellCarouselData.params);
            /* Crosssell init */
            var crosssellCarouselData = this.options.crosssellCarousel;
            $(crosssellCarouselData.selector).carouselInit(crosssellCarouselData.params);
        },
        _animatedCounter: function() {
            var animatedCounterData = this.options.animatedCounter;
            var number = $(animatedCounterData.selector);
            if (number.length > 0) {
                number.each(function() {
                    var finish = $(this).data('finish');
                    $(this).animateNumber({number: finish}, animatedCounterData.speed);
                })
            }
        },
        _testimonialsCarousel: function() {
            var testimonialsData = this.options.testimonialsCarousel;
            $(testimonialsData.selector).carouselInit(testimonialsData.params);
        },
        _messageClear: function() {
            $(document).on('click', '.page-main .page.messages .message', function() {
                $(this).hide();
            });
        },
        _faqAccordion: function() {
            $("#faq-accordion .accordion-trigger").click(function() {
                var _accTrigger = $(this);
                var _accBlock = $(_accTrigger).parent(".accordion-block");
                var _accContent = $(_accBlock).find(".accordion-content");
                if ($(_accTrigger).hasClass("close")) {
                    $(this).removeClass("close").addClass("open");
                    $(_accBlock).removeClass("close").addClass("open");
                    $(_accContent).slideDown();
                } else {
                    $(this).removeClass("open").addClass("close");
                    $(_accBlock).removeClass("open").addClass("close");
                    $(_accContent).slideUp();
                }
            });
        },
        _customLabelFocus: function() {
            var _inputSelector = $(".custom-label .control input");
            $($(_inputSelector)).each(function() {
                if ($(this).val()) {
                    $(this).parents(".custom-label").addClass("focus");
                }
            });
            $(document).on("focus", ".custom-label .control input", function() {
                var _this = $(this);
                var _inputField = $(_this).parents(".custom-label");
                if (!$(_inputField).hasClass("focus")) {
                    $(_inputField).addClass("focus")
                }
            });
            $(document).on("focusout", ".custom-label .control input", function() {
                var _this = $(this);
                var _inputField = $(_this).parents(".custom-label");
                if ($(_inputField).hasClass("focus") && !$(_this).val()) {
                    $(_inputField).removeClass("focus")
                }
            });
        },
        _dataItemsScroll: function() {
            $('.data.item.title').click(function(evt) {
                var _tabTitle = $(this);
                setTimeout(function() {
                    var _thisOffset = $(_tabTitle).offset().top - 61;
                    evt.preventDefault();
                    $('html, body').stop().animate({
                        scrollTop: _thisOffset,
                    }, 300);
                }, 200);
            });
        },
        _customerMenuDropdown: function() {
            $(".sm-header_customer-menu").dropdownDialog({
                appendTo: ".sm-header_customer-menu-container",
                triggerTarget: ".sm-header_customer-menu-toggle",
                closeOnMouseLeave: false,
                closeOnClickOutside: true,
                parentClass: "open",
                triggerClass: "open",
                position: {my: "right top", at: "right bottom", of: "sm-header_customer-menu-container"}
            });
        },
        _create: function() {
            this._customerMenuDropdown();
            this._checkoutCollapsible();
            this._sidebarCollapsible();
            this._productsCarousel();
            this._animatedCounter();
            this._testimonialsCarousel();
            this._messageClear();
            this._faqAccordion();
            this._customLabelFocus();
            this._dataItemsScroll();
        }
    });
    return $.Zemez.theme;
});