/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'matchMedia',
    'mage/tabs',
    'domReady!',
    'dropdownDialog',
    'Magento_Theme/js/device.min'
], function($, mediaCheck) {
    'use strict';
    mediaCheck({
        media: '(min-width: 768px)',
        // Switch to Desktop Version
        entry: function() {
            // Smart header
            var headerNavDropdown = $('.sm-header-nav-wrap__topnav .navigation');
            if (headerNavDropdown.hasClass('ui-dialog-content')) {
                headerNavDropdown.dropdownDialog("destroy");
                $('.sm-header-nav-wrap__topnav').removeClass('open');
                $('.sm-header-nav-toggle').removeClass('open');
            }
            // Smart header end
            // Magento blank theme responsive.js
            var galleryElement;
            (function() {
                var productInfoMain = $('.product-info-main'),
                    productInfoAdditional = $('#product-info-additional');
                if (productInfoAdditional.length) {
                    productInfoAdditional.addClass('hidden');
                    productInfoMain.removeClass('responsive');
                }
            })();
            galleryElement = $('[data-role=media-gallery]');
            if (galleryElement.length && galleryElement.data('mageZoom')) {
                galleryElement.zoom('enable');
            }
            if (galleryElement.length && galleryElement.data('mageGallery')) {
                galleryElement.gallery('option', 'disableLinks', true);
                galleryElement.gallery('option', 'showNav', false);
                galleryElement.gallery('option', 'showThumbs', true);
            }
            // Magento blank theme responsive.js end
            $(window).load(function() {
                setTimeout(function() {
                    $('.product.data.items').tabs('option', 'openOnFocus', true);
                }, 500);
            });
            var _footerBlock = $('.footer'),
                _footerColTitles = $('.footer-col h4'),
                _footerCols = $('.footer-col'),
                _footerConts = $('.footer-col-content');
            _footerBlock.find('.footer-col h4').unbind('click');
            if (_footerColTitles.hasClass('active')) {
                $(this).removeClass('active');
            }
            if (_footerCols.hasClass('active')) {
                $(this).removeClass('active');
            }
            if (_footerConts.hasClass('active')) {
                $(this).removeClass('active');
            }
            _footerConts.each(function() {
                $(this).css("display", "block");
            });
            if (_footerBlock.hasClass('has-active')) {
                _footerBlock.removeClass('has-active');
            }
        },
        // Switch to Mobile Version
        exit: function() {
            // Smart header
            $(".sm-header-nav-wrap__topnav .navigation").dropdownDialog({
                appendTo: ".sm-header-nav-wrap__topnav",
                triggerTarget: ".sm-header-nav-toggle",
                closeOnMouseLeave: false,
                closeOnClickOutside: true,
                parentClass: "open",
                triggerClass: "open",
                position: {my: "left top", at: "left bottom", of: "sm-header-nav-toggle"}
            });
            // Smart header end
            // Magento blank theme responsive.js
            var galleryElement;
            $('.action.toggle.checkout.progress').on('click.gotoCheckoutProgress', function() {
                var myWrapper = '#checkout-progress-wrapper';
                scrollTo(myWrapper + ' .title');
                $(myWrapper + ' .title').addClass('active');
                $(myWrapper + ' .content').show();
            });
            $('body').on('click.checkoutProgress', '#checkout-progress-wrapper .title', function() {
                $(this).toggleClass('active');
                $('#checkout-progress-wrapper .content').toggle();
            });
            galleryElement = $('[data-role=media-gallery]');
            setTimeout(function() {
                if (galleryElement.length && galleryElement.data('mageZoom')) {
                    galleryElement.zoom('disable');
                }
                if (galleryElement.length && galleryElement.data('mageGallery')) {
                    galleryElement.gallery('option', 'disableLinks', false);
                    galleryElement.gallery('option', 'showNav', true);
                    galleryElement.gallery('option', 'showThumbs', false);
                }
            }, 2000);
            // Magento blank theme responsive.js end
            $('.action.toggle.checkout.progress').on('click.gotoCheckoutProgress', function() {
                var myWrapper = '#checkout-progress-wrapper';
                scrollTo(myWrapper + ' .title');
                $(myWrapper + ' .title').addClass('active');
                $(myWrapper + ' .content').show();
            });
            $('body').on('click.checkoutProgress', '#checkout-progress-wrapper .title', function() {
                $(this).toggleClass('active');
                $('#checkout-progress-wrapper .content').toggle();
            });
            $('.footer-col-content').each(function() {
                $(this).css("display", "none");
            });
            $('.footer-col h4').click(function() {
                var _footerColTitle = $(this),
                    _footerCol = _footerColTitle.parent('.footer-col'),
                    _footerCont = _footerColTitle.parent('.footer-col').find('.footer-col-content'),
                    _footerBlock = _footerColTitle.parents('.footer');
                if (_footerColTitle.hasClass('active')) {
                    _footerColTitle.removeClass('active');
                    _footerCol.removeClass('active');
                    _footerCont.slideUp();
                    _footerBlock.removeClass('has-active');
                } else {
                    if (_footerBlock.hasClass('has-active')) {
                        _footerBlock.find('.footer-col.active').each(function() {
                            $(this).removeClass('active');
                            $(this).find('h4').removeClass('active');
                            $(this).find('.footer-col-content').stop().slideUp();
                        });
                        _footerBlock.removeClass('has-active');
                    }
                    _footerBlock.addClass('has-active');
                    _footerColTitle.addClass('active');
                    _footerCol.addClass('active');
                    _footerCont.stop().slideDown();
                }
            });
            galleryElement = $('[data-role=media-gallery]');
            setTimeout(function() {
                if (galleryElement.length && galleryElement.data('mageZoom')) {
                    galleryElement.zoom('disable');
                }
                if (galleryElement.length && galleryElement.data('mageGallery')) {
                    galleryElement.gallery('option', 'disableLinks', false);
                    galleryElement.gallery('option', 'showNav', true);
                    galleryElement.gallery('option', 'showThumbs', false);
                }
            }, 2000);
            setTimeout(function() {
                $('.product.data.items').tabs('option', 'openOnFocus', false);
            }, 500);
        }
    });
});
