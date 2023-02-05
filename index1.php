<html >
<head>
    
	<link rel="stylesheet" type="text/css" href="css/template_normal.min.css">
  
</head>
<body ng-app="app" id="dsocial-body" ng-controller="ComplexController" class="whiteTheme ng-scope">

<div class="loading-welcome active welcome-screen" id="welcomeid" ng-style="{'background-color': view.code.welcome_extra.background || view.welcome_extra.background}" style="background-color: rgb(255, 255, 255);">
    <div class="progress">
        <div class="loading-bar indeterminate" ng-style="{'background-color': view.code.color1 || view.color1}" style="background-color: rgb(51, 51, 51);"></div>
    </div>
    <div class="helper"></div>
    <img id="welcomeImg" imageonload="" ng-src="img/001.png" ng-style="{
            'max-width': view.code.welcome_extra.zoom *2 || view.welcome_extra.zoom *2 + 'px',
            'max-height': view.code.welcome_extra.zoom *2 || view.welcome_extra.zoom *2 + 'px',
            'animation-iteration-count' :  view.showPreview == 'infinite' ? 'infinite' : '',
            'animation-direction' :  view.showPreview == 'infinite' ? 'alternate' : ''
            }" src="//qrcgcustomers.s3-eu-west-1.amazonaws.com/account17395777/23284070_1.png?0.2460923308276317" style="max-width: 100px; max-height: 100px; display: inline;">
</div>
 
<script type="text/javascript" src="js/dist/jquery.min.js?v=1.260"></script>
<script type="text/javascript" src="js/dist/angular.rendering.min.js?v=1.260"></script>
<script type="text/javascript">
/*<![CDATA[*/


    /**
     * Angular connection
     */
    app.loadBasePreviewController();


    /**
     * Replace a point with a space character
     */
    app.filter('point2space', function () {
        return function (input) {
            if (input) {
                return input.replace(/\./g, ' ');
            }
        }
    });

    /**
     * Return date in number format '1490189463876'
     *
     * @param input - date
     * */
    app.filter('toDay', function () {
        return function (input) {
            if (input) {
                var result = new Date(input).getTime();
                return result || '';
            } else
                return '';
        };
    });

    /**
     * Return value with '-' instead of spaces
     *
     * @param input - string
     * */
    app.filter('dashEncode', function () {
        return function (input) {
            if (input) {
                return input.replace(/ /g, "-");
            } else
                return ' ';
        };
    });

    /**
     * Return escape value of param
     *
     * @param input - string
     * */
    app.filter('escape', function () {
        return function (input) {
            if (input) {
                return escape(input);
            } else
                return ' ';
        };
    });

    /**
     * Return icon name based on the channel name
     * */
    app.filter('toIconName', function () {
        return function (input) {
            if (input) {
                return input.replace(/\s+/g, '').toLowerCase();
            } else
                return ' ';
        };
    });

    /**
     * Return link with the correct prefix
     * */
    app.filter('prefixChannel', ['$filter', function ($filter) {
        return function (input, name) {
            if (input) {
                switch (name) {
                    case ('WhatsApp'):
                        return 'https://wa.me/' + input.replace(/[\s()]/g, '');
                        break;
                    case ('Twitter'):
                        return $filter('prefixURL')('www.twitter.com/' + input);
                        break;
                    case ('Instagram'):
                        return $filter('prefixURL')('www.instagram.com/' + input);
                        break;
                    case ('Snapchat'):
                        return $filter('prefixURL')('www.snapchat.com/add/' + input);
                        break;
                    case ('WeChat'):
                        return 'weixin://dl/chat?' + input;
                        break;
                    case ('Skype'):
                        return 'skype:' + input + '?chat';
                        break;
                    case ('Line'):
                        return $filter('prefixURL')('https://line.me/R/ti/p/~' + input);
                        break;
                    default:
                        return $filter('prefixURL')(input);
                        break;
                }
            } else
                return ' ';
        };
    }]);

    /**
     * Return domain of input
     * */
    app.filter('domain', function () {
        return function (input) {
            if (input.indexOf('://') >= 0) {
                return input.split('/')[2]
            }
            return input.split('/')[0]
        };
    });

    /**
     * The QR service for the app.
     * */
    app.service('qr', function () {
        /**
         * Scroll page from buttom to top
         * */
        this.scrollFromBottomtoTop = function (loadDemo) {
            setTimeout(function () {
                if (loadDemo == true) {
                    $("html, body").animate({
                        scrollTop: $('.channel-container:last-child').offset().top
                    }, 0)
                    ;$("html, body").animate({
                        scrollTop: 0
                    }, 1000);
                }
            }, 0)
        }
    });

    /**
     * Directive
     *
     * When image is loaded fadeout welcome screen
     * */
    app.directive('imageonload', ['qr', function (qr) {
        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                element.bind('load', function () {
                    $("#welcomeImg").fadeIn(1000);
                    window.setTimeout(function () {
                        $(".loading-welcome.welcome-screen").fadeOut();
                        scope.welcomeScreenScrollWindow++;
                        if (scope.welcomeScreenScrollWindow == 1) {
                            qr.scrollFromBottomtoTop(scope.loadDemo)
                        }
                    }, 2000);
                });
                element.bind('error', function () {
                });
            }
        };
    }]);
    //Override the base preview controller
    app.controller('ComplexController', function ($scope, $controller, $sce, $rootScope, $filter, previewService, $window) {

            $scope.welcomeScreenScrollWindow = 0;
            /**
             * Get channel name from channel item
             *
             * @param channel
             * */
            $scope.getChannelLink = function (channel) {
                if (!$scope.loadDemo) {
                    return $filter('prefixChannel')(channel.link, channel.name);
                }
                return '#channel-item-' + channel.name;
            };

            /**
             * Get channel name or link to the channel based on channel info
             *
             * @param channel
             * */
            $scope.getChannelNameOrLink = function (channel) {
                if (channel.name == 'Website')
                    if (channel.link) {
                        return $scope.loadDemo ? channel.link : $filter('domain')(channel.link);
                    } else {
                        return channel.name;
                    } else {
                    return channel.name;
                }
            };

            /**
             * Set solid background for the header based on the title section height
             * */
            $scope.setSolidBgd = function () {
                if ($scope.view.code) {
                    var titleSectionHeight = 142;
                    setTimeout(function () {
                        if ($scope.view.code.teaser) {
                            titleSectionHeight = $('.event-content-container').parent().outerHeight() + 60 + 128
                        }
                        else
                            titleSectionHeight = $('.event-content-container').parent().outerHeight() + 60;
                        $('.solid-bgd').css({
                            'background-color': $scope.view.code.color1 ? $scope.view.code.color1 : '#447fb6',
                            'height': !$scope.view.code.avatar ? titleSectionHeight + 'px' : '420px'
                        })
                    }, 0);
                }
            };

            /**
             * Open the dialog popup
             * Event is for getting the current target element
             * Target:
             *      - shareAction: Button with got it information
             *      - channelAction: Button with got it information
             *
             * @param event - string
             * @param target - string
             * */
            $scope.callAction = function (event, target) {
                switch (target) {
                    case 'shareAction':
                        if ($scope.loadDemo) {
                            $scope.gotItText = 'Your contacts can click this button to share your page.';
                            $scope.toggleDialog('gotIt', $(event.currentTarget));
                        }
                        break;
                    case 'channelAction':
                        if ($scope.loadDemo) {
                            $scope.gotItText = 'By tapping here, users are navigated to the linked social media profile or website.';
                            $scope.toggleDialog('gotIt', $(event.currentTarget));
                        }
                        break;
                }
            };


            /**
             * Toggle dialog
             *
             * @param id - string
             * @param element - current target
             * */
            $scope.toggleDialog = function (id, element) {
                if (element) {
                    var elem = element.context.outerHTML;
                    $('#' + id + ' .dialog-container .event-gotIt-button').html(elem);
                }
                $($('#' + id).parent()).toggleClass('fabOnTop');
                $('#' + id + ' .dialog-container').toggleClass('is-visible');
                $('#prime.fab').toggleClass('disabledClick');
                $('#' + id + ' .fixed-blur-bgd').toggle();
            };

            /**
             * Callback of the dialog yes/no buttons
             * Can close the dialog or it can redirect to a url and close the dialog
             * If the url is not defined it will use the targetUrl from the callAction button
             *
             * @param proceed - string
             * @param url - current target
             * */
            $scope.dialogRedirectCallback = function (proceed, url) {
                if (proceed) {
                    if (url) {
                        $window.open(url, '_blank');
                    } else {
                        if ($scope.view.code.callToAction.targetUrl.indexOf('http') < 0)
                            $window.open('//' + $scope.view.code.callToAction.targetUrl, '_blank');
                        else
                            $window.open($scope.view.code.callToAction.targetUrl, '_blank');
                    }
                    $scope.closeDialog();
                } else {
                    $scope.closeDialog();
                }
            };

            /**
             * Close dialog
             *
             * */
            $scope.closeDialog = function () {
                $('.dialog-container').removeClass('is-visible');
                $('#prime.fab').toggleClass('disabledClick');
                $('.event-gotIt-button').html('');
                $('.fixed-blur-bgd').hide();
            };


            /**
             * Set position of the fab Button
             *If we have only one fab you have to use bottom 5px!!! else use 75px as default
             * @param buttonName - string
             * */
            $scope.setPosition = function (buttonName) {
                if ($(window).innerWidth() < 667) {
                    var bottom = '5px',
                        right = '17px';
                    if (buttonName == 'calendar')
                        bottom = '5px';
                    if ($(window).innerWidth() < 321)
                        right = '-85px';
                    return {
                        'position': 'fixed',
                        'bottom': bottom,
                        'right': right,
                        'width': '80px'
                    }
                }
                return '';
            };

            //Inherrit from basePreviewController
            angular.extend(this, $controller('BasePreviewController', {$scope: $scope}));

            $scope.loadDemo = parent && typeof parent.ImHere === "function";

            //Get the json data form the file
            json_data = {"form":[{"section_design":{"fold":false}},{"section_basic_info":{"fold":false}},{"section_media_channels":{"fold":false}},{"section_welcome_screen":{"fold":false}},{"section_advance_options":{"fold":true}}],"code":{"title":"Connect with us","teaser":"","channels":[{"input_prefix_label":"URL","link":"www.bo-maison.com","label":"Visit our website","name":"Website"},{"input_prefix_label":"URL","label":"Become a fan","name":"Facebook","link":"www.fb.com\/bo-maison.rdc"},{"input_prefix_label":"@","link":"bomaison.rdc","label":"Follow us","name":"Instagram"},{"input_prefix_label":"URL","link":"www.tiktok.com\/@bomaison.rdc","label":"Find us on TikTok","name":"TikTok"},{"input_prefix_label":"URL","link":"https:\/\/www.youtube.com\/channel\/UCzWgvM186nxBo1LX6PKqW5w","label":"Subscribe to our channel","name":"YouTube"},{"input_prefix_label":"Phone","link":"+243 827 444 463","label":"Message us","name":"WhatsApp"}],"color1":"#333333","color2":"#333333","avatar":"\/\/qrcgcustomers.s3-eu-west-1.amazonaws.com\/account17395777\/23283782_1.png?0.9377483488601115","avatar_extra":{"zoom":100,"background":"#ffffff"},"welcome_extra":{"zoom":50,"background":"#ffffff"}},"showPreview":1,"welcome_screen":"\/\/qrcgcustomers.s3-eu-west-1.amazonaws.com\/account17395777\/23284070_1.png?0.2460923308276317","sharing":true};

            //Upgrade old vcards created before 13.04 with show_directions parameter
            if (angular.isUndefined(json_data.show_directions))
                json_data.show_directions = true;

            previewService.pushData(json_data);

            function setAvatarBackgroundImage() {
                if ($scope.view.code) {
                    if ($scope.view.code.avatar)
                        return {
                            'background': 'url(' + $scope.view.code.avatar + ')'
                        };
                    else return {
                        'height': '0px',
                        'background': 'none'
                    }
                }
            }

            function getBackgroundColor() {
                if ($scope.view.code) {
                    var color1 = $scope.view.code.color1 || '#607d8b';
                    if (!$scope.view.code.show_gradient)
                        return {"background": color1};
                }
            }

            $scope.getBackgroundColor = getBackgroundColor;
            $scope.setAvatarBackgroundImage = setAvatarBackgroundImage;

            window.setTimeout(function () {

                $(".loading-vcard").fadeOut();
                $(".vcard-functions-wrapper a:visible:last").addClass("last-element");
            }, 500);

            /**
             * Copy short ulr to clipboard
             *
             * @param elem - input element with the link
             * */
            $scope.ifCopySucceed = false;
            $scope.copyLinkToClipboard = function (elem) {
                var targetId = "_hiddenCopyText_";
                var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
                var origSelectionStart, origSelectionEnd;
                if (isInput) {
                    // can just use the original source element for the selection and copy
                    target = elem;
                    origSelectionStart = elem.selectionStart;
                    origSelectionEnd = elem.selectionEnd;
                } else {
                    // must use a temporary form element for the selection and copy
                    target = document.getElementById(targetId);
                    if (!target) {
                        var target = document.createElement("textarea");
                        target.style.position = "absolute";
                        target.style.left = "-9999px";
                        target.style.top = "0";
                        target.id = targetId;
                        document.body.appendChild(target);
                    }
                    target.textContent = elem.textContent;
                }
                // select the content
                var currentFocus = document.activeElement;
                target.focus();
                target.setSelectionRange(0, target.value.length);

                // copy the selection
                var succeed;
                try {
                    succeed = document.execCommand("copy");
                } catch (e) {
                    succeed = false;
                }
                // restore original focus
                if (currentFocus && typeof currentFocus.focus === "function") {
                    currentFocus.focus();
                }

                if (isInput) {
                    // restore prior selection
                    elem.setSelectionRange(origSelectionStart, origSelectionEnd);
                } else {
                    // clear temporary content
                    target.textContent = "";
                }
                $scope.$apply(function () {
                    $scope.ifCopySucceed = succeed;
                });
                setTimeout(function () {
                    $scope.$apply(function () {
                        $scope.ifCopySucceed = false;
                    });
                }, 1500)
            };

            /**
             * Set text color based on the background
             *
             * @param color - background color
             * */
            $scope.isColorLight = function (color) {
                var c;
                if (/^#([A-Fa-f0-9]{3}){1,2}$/.test(color)) {
                    c = color.substring(1).split('');
                    if (c.length == 3) {
                        c = [c[0], c[0], c[1], c[1], c[2], c[2]];
                    }
                    c = '0x' + c.join('');

                    var r = (c >> 16) & 255,
                        g = (c >> 8) & 255,
                        b = c & 255;
                    // Counting the perceptive luminance
                    // human eye favors green color...
                    var a = 1 - (0.240 * r + 0.470 * g + 0.150 * b) / 255;
                    return (a < 0.4);
                }
            };
            /**
             * Watch showPreview value
             *
             * @param p1 - new value
             * @param p2 - old value
             * */
            $scope.$watch("view.showPreview", function (p1, p2, p3) {
                if (p1 != undefined && p1 == 'infinite') {
                    setTimeout(function () {
                        $(".loading-welcome.welcome-screen").show();
                        $("#welcomeImg").fadeIn(1000);
                    }, 500)
                } else if (p1 == undefined && p2 != undefined && p2 == 'infinite') {
                    $("#welcomeImg").hide();
                    $(".loading-welcome").fadeOut();
                } else if ((p1 != undefined && p2 != undefined && p1 != p2) || (p1 != undefined && p1 != 0 && p2 == undefined && p1 != p2)) {
                    setTimeout(function () {
                        $(".loading-welcome.welcome-screen").show();
                        $("#welcomeImg").hide();
                        $("#welcomeImg").fadeIn(1000);
                        setTimeout(function () {
                            $(".loading-welcome").fadeOut();
                        }, 2000)
                    }, 500)
                }
            });

            $scope.setAvatar = function () {
                return {
                    'background': $scope.view.code.avatar ? 'url(' + $scope.view.code.avatar + ')' : 'none'
                }
            };

            /**
             * Set avatar background with extra options like zoom and background color
             * */
            $scope.setAvatarExtra = function () {
                var style = {};
                if ($scope.view.code) {
                    /*
                    * Apply custom header image and background color
                    * */
                    if ($scope.view.code.customHeaderImage && $scope.view.code.customHeaderImage.url) {
                        if ($scope.view.code.customHeaderImage.color != null) {
                            style = {
                                'background-color': $scope.view.code.customHeaderImage.color,
                                '-webkit-mask': 'url("' + $scope.view.code.customHeaderImage.url + '") no-repeat 50% 50%',
                                'mask': 'url("' + $scope.view.code.customHeaderImage.url + '") no-repeat 50% 50%',
                                '-webkit-mask-size': 'cover',
                                'mask-size':'cover'
                            };
                            $('.vcard-top-info.avatar-container').css('background-color', $scope.view.code.customHeaderImage.color);
                        } else
                            style = {
                                'background-image': 'url("' + $scope.view.code.customHeaderImage.url + '")',
                                'background-size': '100%'
                            }
                    }
                    else if ($scope.view.code.avatar)
                        style = {
                            'background': 'url("' + $scope.view.code.avatar + '")',
                            'background-size': '100%!important',
                            'background-color': '#ffffff'
                        };
                    else
                        style = {
                            'height': '0px',
                            'background': 'none'
                        };
                    if ($scope.view.code.avatar_extra) {
                        style['background-size'] = $scope.view.code.avatar_extra.zoom + '%';
                        if (!($scope.view.code.customHeaderImage && $scope.view.code.customHeaderImage.url && $scope.view.code.customHeaderImage.color != null)) {
                            style['background-color'] = $scope.view.code.avatar_extra.background;
                        }
                    }
                }
                // Convert array to string to avoid problems with vendor prefixes.
                var string_style = '';
                angular.forEach(style, function (value, key) {
                    string_style += key + ': ' + value + ';';
                });
                // Bad solution, but works (Alex).
                $('.vcard-top-info.avatar-container').attr('style', string_style);
                return {};
            };

            /**
             * Watch avatarExtra values and set the css
             * */
            $scope.$watch('view.code.avatar_extra', function (newVal) {
                if (newVal) {
                    $('.avatar-container').css({
                        'background-size': newVal.zoom + '%!important',
                        'background-color': newVal.background,
                        'background-position': 'center center'
                    });
                }
            });

            $(document).ready(function () {
                /**
                 * To work, function 'copyLinkToClipboard' needs to be called from an event listener on the copy button
                 * */
                document.getElementById("copyButton").addEventListener("click", function () {
                    $scope.copyLinkToClipboard(document.getElementById("shortUrl"));
                });
            })

        }
    );

    if (parent && typeof parent.ImHere === "function") {
        parent.ImHere();
    } else {
        //Only load this on standalone pages not inframe working
        SocialShareKit.init();
    }

    /**
     * Toggle fab
     *
     * @param id - id of the fab
     */
    function toggleFab(id) {

        $(id + ' .prime').toggleClass('is-active');
        $(id + ' #prime').toggleClass('is-float');
        $($(id).parent()).toggleClass('fabOnTop');
        $('#prime.fab').toggleClass('disabledClick');
        $(id + ' .fixed-blur-bgd').toggle();
        $(id + ' .chat').toggleClass('is-visible');

    }

    $(document).ready(function () {

        /**
         * On window resize if width is bigger then tablet remove calendar and share mobile style
         * */
        $(window).resize(function () {
            if (window.innerWidth >= 667) {
                $('.follow-scroll.calendar-container').removeAttr('style');
                $('.follow-scroll.share-container').removeAttr('style');
            }
        });

        /**
         * Close fab from close icon
         * */
        $('#prime, .icon-fab-close').click(function () {
            var id = $($(this).closest('.fabs')).attr('id');
            toggleFab('#' + id);
        });

        /**
         * Close fab on blur bgd click
         * */
        $('.fixed-blur-bgd').click(function () {
            if (event.target == $(this)[0]) {
                $($("#prime.is-float").parent().parent()).removeClass('fabOnTop');
                $('.prime').removeClass('is-active');
                $('#prime').removeClass('is-float');
                $('#prime.fab').removeClass('disabledClick');
                $('.chat, .dialog-container').removeClass('is-visible');
                $(this).hide();
            }
        });


        /**
         * Ripple effect on element
         */
        var ink, d, x, y;
        $(".ripplelink").click(function (e) {
            if ($(this).find(".ink").length === 0) {
                $(this).prepend("<span class='ink'></span>");
            }

            ink = $(this).find(".ink");
            ink.removeClass("animate");

            if (!ink.height() && !ink.width()) {
                d = Math.max($(this).outerWidth(), $(this).outerHeight());
                ink.css({height: d, width: d});
            }

            x = e.pageX - $(this).offset().left - ink.width() / 2;
            y = e.pageY - $(this).offset().top - ink.height() / 2;

            ink.css({top: y + 'px', left: x + 'px'}).addClass("animate");
        });
    });


/*]]>*/
</script>
<style>
.addhid{
	display: none;
}
</style>

 


</body></html>