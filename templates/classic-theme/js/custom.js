(function ($) {
  "use strict";

  if ($("body").hasClass("rtl")) var rtl = true;
  else rtl = false;

  $(document).ready(function () {
    /*--------------------------------------------------*/
    /*  Sticky Header
                  /*--------------------------------------------------*/
    function stickyHeader() {
      $(window).on("scroll load", function () {
        if ($(window).width() < "1099") {
          $("#header-container").removeClass("cloned");
        }

        if ($(window).width() > "1099") {
          // CSS adjustment
          $("#header-container").css({
            position: "fixed",
          });

          var headerOffset = $("#header-container").height();

          if ($(window).scrollTop() >= headerOffset) {
            $("#header-container").addClass("cloned");
            $(".wrapper-with-transparent-header #header-container")
              .addClass("cloned")
              .removeClass("transparent-header unsticky");
          } else {
            $("#header-container").removeClass("cloned");
            $(".wrapper-with-transparent-header #header-container")
              .addClass("transparent-header unsticky")
              .removeClass("cloned");
          }

          // Sticky Logo
          var transparentLogo = $("#header-container #logo img").attr(
            "data-transparent-logo"
          );
          var stickyLogo = $("#header-container #logo img").attr(
            "data-sticky-logo"
          );

          if (
            $(".wrapper-with-transparent-header #header-container").hasClass(
              "cloned"
            )
          ) {
            $("#header-container.cloned #logo img").attr("src", stickyLogo);
          }

          if (
            $(".wrapper-with-transparent-header #header-container").hasClass(
              "transparent-header"
            )
          ) {
            $("#header-container #logo img").attr("src", transparentLogo);
          }
        }
      });
      $(window).on("load resize", function () {
        var headerOffset = $("#header-container").height();
        $("#wrapper").css({ "padding-top": headerOffset });
      });
    }

    // Sticky Header Init
    if ($("#header-container").hasClass("sticky")) {
      stickyHeader();
    } else if ($("#header-container").hasClass("unsticky")) {
      var stickyLogo = $("#header-container #logo img").attr(
        "data-sticky-logo"
      );
      $("#header-container #logo img").attr("src", stickyLogo);
    } else {
      var transparentLogo = $("#header-container #logo img").attr(
        "data-transparent-logo"
      );
      if (
        $(".wrapper-with-transparent-header #header-container").hasClass(
          "transparent-header"
        )
      ) {
        $("#header-container #logo img").attr("src", transparentLogo);
      }
    }
    stickyHeader();

    /*--------------------------------------------------*/
    /*  Transparent Header Spacer Adjustment
                  /*--------------------------------------------------*/
    $(window).on("load resize", function () {
      var transparentHeaderHeight = $(".transparent-header").outerHeight();
      $(".transparent-header-spacer").css({
        height: transparentHeaderHeight,
      });
    });

    function backToTop() {
      $("body").append('<div id="backtotop"><a href="#"></a></div>');
    }
    if (!LIVE_CHAT) {
      backToTop();
    }
    var pxShow = 600;
    var scrollSpeed = 500;
    $(window).scroll(function () {
      if ($(window).scrollTop() >= pxShow) {
        $("#backtotop").addClass("visible");
      } else {
        $("#backtotop").removeClass("visible");
      }
    });
    $("#backtotop a").on("click", function () {
      $("html, body").animate({ scrollTop: 0 }, scrollSpeed);
      return false;
    });

    $(".ripple-effect, .ripple-effect-dark").on("click", function (e) {
      var rippleDiv = $('<span class="ripple-overlay">'),
        rippleOffset = $(this).offset(),
        rippleY = e.pageY - rippleOffset.top,
        rippleX = e.pageX - rippleOffset.left;
      rippleDiv
        .css({
          top: rippleY - rippleDiv.height() / 2,
          left: rippleX - rippleDiv.width() / 2,
        })
        .appendTo($(this));
      window.setTimeout(function () {
        rippleDiv.remove();
      }, 800);
    });

    $(".switch, .radio").each(function () {
      var intElem = $(this);
      intElem.on("click", function () {
        intElem.addClass("interactive-effect");
        setTimeout(function () {
          intElem.removeClass("interactive-effect");
        }, 400);
      });
    });

    $(window).on("load", function () {
      $(".button.button-sliding-icon")
        .not(".task-listing .button.button-sliding-icon")
        .each(function () {
          var buttonWidth = $(this).outerWidth() + 30;
          $(this).css("width", buttonWidth);
        });

      $("img.lazy-load").each(function () {
        $(this)
          .attr("src", $(this).attr("data-original"))
          .removeClass("lazy-load");
      });
    });

    $(".bookmark-icon").on("click", function (e) {
      e.preventDefault();
      $(this).toggleClass("bookmarked");
    });
    $(".bookmark-button").on("click", function (e) {
      e.preventDefault();
      $(this).toggleClass("bookmarked");
    });
    $("a.close")
      .removeAttr("href")
      .on("click", function () {
        function slideFade(elem) {
          var fadeOut = { opacity: 0, transition: "opacity 0.5s" };
          elem.css(fadeOut).slideUp();
        }

        slideFade($(this).parent());
      });
    $(".header-notifications").each(function () {
      var userMenu = $(this);
      var userMenuTrigger = $(this).find(".header-notifications-trigger a");
      $(userMenuTrigger).on("click", function (event) {
        event.preventDefault();
        if ($(this).closest(".header-notifications").is(".active")) {
          close_user_dropdown();
        } else {
          close_user_dropdown();
          userMenu.addClass("active");
        }
      });
    });

    function close_user_dropdown() {
      $(".header-notifications").removeClass("active");
    }

    var mouse_is_inside = false;
    $(".header-notifications").on("mouseenter", function () {
      mouse_is_inside = true;
    });
    $(".header-notifications").on("mouseleave", function () {
      mouse_is_inside = false;
    });
    $("body").mouseup(function () {
      if (!mouse_is_inside) close_user_dropdown();
    });
    $(document).keyup(function (e) {
      if (e.keyCode == 27) {
        close_user_dropdown();
      }
    });
    if ($(".status-switch label.user-invisible").hasClass("current-status")) {
      $(".status-indicator").addClass("right");
    }
    $(".status-switch label.user-invisible").on("click", function () {
      $(".status-indicator").addClass("right");
      $(".status-switch label").removeClass("current-status");
      $(".user-invisible").addClass("current-status");
    });
    $(".status-switch label.user-online").on("click", function () {
      $(".status-indicator").removeClass("right");
      $(".status-switch label").removeClass("current-status");
      $(".user-online").addClass("current-status");
    });

    function wrapperHeight() {
      var headerHeight = $("#header-container").outerHeight();
      var windowHeight = $(window).outerHeight() - headerHeight;
      $(
        ".full-page-content-container, .dashboard-content-container, .dashboard-sidebar-inner, .dashboard-container, .full-page-container"
      ).css({ height: windowHeight });
      $(".dashboard-content-inner").css({ "min-height": windowHeight });
    }

    function fullPageScrollbar() {
      $(".full-page-sidebar-inner, .dashboard-sidebar-inner").each(function () {
        var headerHeight = $("#header-container").outerHeight();
        var windowHeight = $(window).outerHeight() - headerHeight;
        var sidebarContainerHeight = $(this)
          .find(".sidebar-container, .dashboard-nav-container")
          .outerHeight();
        if (sidebarContainerHeight > windowHeight) {
          $(this).css({ height: windowHeight });
        } else {
          $(this).find(".simplebar-track").hide();
        }
      });
    }

    $(window).on("load resize", function () {
      wrapperHeight();
      fullPageScrollbar();
    });
    wrapperHeight();
    fullPageScrollbar();

    // Show More toggle
    $(".show-more-button").on("click", function (e) {
      e.preventDefault();
      $(this).parent().toggleClass("visible");
    });
    // advance search toggle
    $(".enable-filters-button").on("click", function () {
      $(".sidebar-container").slideToggle();
      $(this).toggleClass("active");
    });
    /*----------------------------------------------------*/
    /*  Searh Form More Options
                  /*----------------------------------------------------*/
    $(".more-search-options-trigger").on("click", function (e) {
      e.preventDefault();
      $(".more-search-options, .more-search-options-trigger").toggleClass(
        "active"
      );
      $(".more-search-options.relative").animate(
        { height: "toggle", opacity: "toggle" },
        300
      );
    });

    /*----------------------------------------------------*/
    /*  Chosen Plugin
                  /*----------------------------------------------------*/

    var config = {
      ".chosen-select": { disable_search_threshold: 10, width: "100%" },
      ".chosen-select-deselect": { allow_single_deselect: true, width: "100%" },
      ".chosen-select-no-single": {
        disable_search_threshold: 100,
        width: "100%",
      },
      ".chosen-select-no-single.no-search": {
        disable_search_threshold: 10,
        width: "100%",
      },
      ".chosen-select-no-results": { no_results_text: "Oops, nothing found!" },
      ".chosen-select-width": { width: "95%" },
    };

    for (var selector in config) {
      if (config.hasOwnProperty(selector)) {
        $(selector).chosen(config[selector]);
      }
    }
    /*----------------------------------------------------*/
    /*  Chosen Plugin
                  /*----------------------------------------------------*/
    /*  Custom Input With Select
                     /*----------------------------------------------------*/
    $(".select-input").each(function () {
      var thisContainer = $(this);
      var $this = $(this).children("select"),
        numberOfOptions = $this.children("option").length;

      $this.addClass("select-hidden");
      $this.wrap('<div class="select"></div>');
      $this.after('<div class="select-styled"></div>');
      var $styledSelect = $this.next("div.select-styled");
      $styledSelect.text($this.children("option").eq(0).text());

      var $list = $("<ul />", {
        class: "select-options",
      }).insertAfter($styledSelect);

      for (var i = 0; i < numberOfOptions; i++) {
        $("<li />", {
          text: $this.children("option").eq(i).text(),
          rel: $this.children("option").eq(i).val(),
        }).appendTo($list);
      }

      var $listItems = $list.children("li");

      $list.wrapInner('<div class="select-list-container"></div>');

      $(this)
        .children("input")
        .on("click", function (e) {
          $(".select-options").hide();
          e.stopPropagation();
          $styledSelect
            .toggleClass("active")
            .next("ul.select-options")
            .toggle();
        });

      $(this)
        .children("input")
        .keypress(function () {
          $styledSelect.removeClass("active");
          $list.hide();
        });

      $listItems.on("click", function (e) {
        e.stopPropagation();
        $(thisContainer)
          .children("input")
          .val($(this).text())
          .removeClass("active");
        $this.val($(this).attr("rel"));
        $list.hide();
      });

      $(document).on("click", function (e) {
        $styledSelect.removeClass("active");
        $list.hide();
      });

      // Unit character
      var fieldUnit = $(this).children("input").attr("data-unit");
      $(this)
        .children("input")
        .before('<i class="data-unit">' + fieldUnit + "</i>");
    });
    $(window).on("load", function () {
      $(".filter-button-tooltip")
        .css({ left: $(".enable-filters-button").outerWidth() + 48 })
        .addClass("tooltip-visible");
    });

    function avatarSwitcher() {
      var readURL = function (input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
            $(".profile-pic").attr("src", e.target.result);
          };
          reader.readAsDataURL(input.files[0]);
        }
      };
      $(".file-upload").on("change", function () {
        readURL(this);
      });
      $(".upload-button").on("click", function () {
        $(".file-upload").click();
      });
    }

    avatarSwitcher();
    $(".dashboard-nav ul li a").on("click", function (e) {
      if ($(this).closest("li").children("ul").length) {
        if ($(this).closest("li").is(".active-submenu")) {
          $(".dashboard-nav ul li").removeClass("active-submenu");
        } else {
          $(".dashboard-nav ul li").removeClass("active-submenu");
          $(this).parent("li").addClass("active-submenu");
        }
        e.preventDefault();
      }
    });
    $(".dashboard-responsive-nav-trigger").on("click", function (e) {
      e.preventDefault();
      $(this).toggleClass("active");
      var dashboardNavContainer = $("body").find(".dashboard-nav");
      if ($(this).hasClass("active")) {
        $(dashboardNavContainer).addClass("active");
      } else {
        $(dashboardNavContainer).removeClass("active");
      }
      $(".dashboard-responsive-nav-trigger .hamburger").toggleClass(
        "is-active"
      );
    });

    function funFacts() {
      function hexToRgbA(hex) {
        var c;
        if (/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)) {
          c = hex.substring(1).split("");
          if (c.length == 3) {
            c = [c[0], c[0], c[1], c[1], c[2], c[2]];
          }
          c = "0x" + c.join("");
          return (
            "rgba(" +
            [(c >> 16) & 255, (c >> 8) & 255, c & 255].join(",") +
            ",0.07)"
          );
        }
      }

      $(".fun-fact").each(function () {
        var factColor = $(this).attr("data-fun-fact-color");
        if (factColor !== undefined) {
          $(this)
            .find(".fun-fact-icon")
            .css("background-color", hexToRgbA(factColor));
          $(this).find("i").css("color", factColor);
        }
      });
    }

    funFacts();

    $(window).on("load resize", function () {
      var winwidth = $(window).width();
      if (winwidth > 1199) {
        $(".row").each(function () {
          var mbh = $(this).find(".main-box-in-row").outerHeight();
          var cbh = $(this).find(".child-box-in-row").outerHeight();
          if (mbh < cbh) {
            var headerBoxHeight = $(this)
              .find(".child-box-in-row .headline")
              .outerHeight();
            var mainBoxHeight =
              $(this).find(".main-box-in-row").outerHeight() -
              headerBoxHeight +
              39;
            $(this)
              .find(".child-box-in-row .content")
              .wrap(
                '<div class="dashboard-box-scrollbar" style="max-height: ' +
                  mainBoxHeight +
                  'px" data-simplebar></div>'
              );
          }
        });
      }
    });
    $(".buttons-to-right").each(function () {
      var btr = $(this).width();
      if (btr < 36) {
        $(this).addClass("single-right-button");
      }
    });
    $(window).on("load resize", function () {
      var smallFooterHeight = $(".small-footer").outerHeight();
      $(".dashboard-footer-spacer").css({
        "padding-top": smallFooterHeight + 45,
      });
    });
    jQuery.each(jQuery("textarea[data-autoresize]"), function () {
      var offset = this.offsetHeight - this.clientHeight;
      var resizeTextarea = function (el) {
        jQuery(el)
          .css("height", "auto")
          .css("height", el.scrollHeight + offset);
      };
      jQuery(this)
        .on("keyup input", function () {
          resizeTextarea(this);
        })
        .removeAttr("data-autoresize");
    });

    function starRating(ratingElem) {
      $(ratingElem).each(function () {
        var dataRating = $(this).attr("data-rating");

        function starsOutput(
          firstStar,
          secondStar,
          thirdStar,
          fourthStar,
          fifthStar
        ) {
          return (
            "" +
            '<span class="' +
            firstStar +
            '"></span>' +
            '<span class="' +
            secondStar +
            '"></span>' +
            '<span class="' +
            thirdStar +
            '"></span>' +
            '<span class="' +
            fourthStar +
            '"></span>' +
            '<span class="' +
            fifthStar +
            '"></span>'
          );
        }

        var fiveStars = starsOutput("star", "star", "star", "star", "star");
        var fourHalfStars = starsOutput(
          "star",
          "star",
          "star",
          "star",
          "star half"
        );
        var fourStars = starsOutput(
          "star",
          "star",
          "star",
          "star",
          "star empty"
        );
        var threeHalfStars = starsOutput(
          "star",
          "star",
          "star",
          "star half",
          "star empty"
        );
        var threeStars = starsOutput(
          "star",
          "star",
          "star",
          "star empty",
          "star empty"
        );
        var twoHalfStars = starsOutput(
          "star",
          "star",
          "star half",
          "star empty",
          "star empty"
        );
        var twoStars = starsOutput(
          "star",
          "star",
          "star empty",
          "star empty",
          "star empty"
        );
        var oneHalfStar = starsOutput(
          "star",
          "star half",
          "star empty",
          "star empty",
          "star empty"
        );
        var oneStar = starsOutput(
          "star",
          "star empty",
          "star empty",
          "star empty",
          "star empty"
        );
        if (dataRating >= 4.75) {
          $(this).append(fiveStars);
        } else if (dataRating >= 4.25) {
          $(this).append(fourHalfStars);
        } else if (dataRating >= 3.75) {
          $(this).append(fourStars);
        } else if (dataRating >= 3.25) {
          $(this).append(threeHalfStars);
        } else if (dataRating >= 2.75) {
          $(this).append(threeStars);
        } else if (dataRating >= 2.25) {
          $(this).append(twoHalfStars);
        } else if (dataRating >= 1.75) {
          $(this).append(twoStars);
        } else if (dataRating >= 1.25) {
          $(this).append(oneHalfStar);
        } else if (dataRating < 1.25) {
          $(this).append(oneStar);
        }
      });
    }

    starRating(".star-rating");

    function userMenuScrollbar() {
      $(".header-notifications-scroll").each(function () {
        var scrollContainerList = $(this).find("ul");
        var itemsCount = scrollContainerList.children("li").length;
        var notificationItems;
        if (scrollContainerList.children("li").outerHeight() > 140) {
          var notificationItems = 2;
        } else {
          var notificationItems = 3;
        }
        if (itemsCount > notificationItems) {
          var listHeight = 0;
          $(scrollContainerList)
            .find("li:lt(" + notificationItems + ")")
            .each(function () {
              listHeight += $(this).height();
            });
          $(this).css({ height: listHeight });
        } else {
          $(this).css({ height: "auto" });
          $(this).find(".simplebar-track").hide();
        }
      });
    }

    userMenuScrollbar();

    tippy("body", {
      target: "[data-tippy-placement]",
      dynamicTitle: true,
      delay: 100,
      arrow: true,
      arrowType: "sharp",
      size: "regular",
      duration: 200,
      animation: "shift-away",
      animateFill: true,
      theme: "dark",
      distance: 10,
    });

    var accordion = (function () {
      var $accordion = $(".js-accordion");
      var $accordion_header = $accordion.find(".js-accordion-header");
      var settings = { speed: 400, oneOpen: false };
      return {
        init: function ($settings) {
          $accordion_header.on("click", function () {
            accordion.toggle($(this));
          });
          $.extend(settings, $settings);
          if (settings.oneOpen && $(".js-accordion-item.active").length > 1) {
            $(".js-accordion-item.active:not(:first)").removeClass("active");
          }
          $(".js-accordion-item.active").find("> .js-accordion-body").show();
        },
        toggle: function ($this) {
          if (
            settings.oneOpen &&
            $this[0] !=
              $this
                .closest(".js-accordion")
                .find("> .js-accordion-item.active > .js-accordion-header")[0]
          ) {
            $this
              .closest(".js-accordion")
              .find("> .js-accordion-item")
              .removeClass("active")
              .find(".js-accordion-body")
              .slideUp();
          }
          $this.closest(".js-accordion-item").toggleClass("active");
          $this.next().stop().slideToggle(settings.speed);
        },
      };
    })();
    $(document).ready(function () {
      accordion.init({ speed: 300, oneOpen: true });
    });
    $(window)
      .on("load resize", function () {
        if ($(".tabs")[0]) {
          $(".tabs").each(function () {
            var thisTab = $(this);
            var activePos = thisTab.find(".tabs-header .active").position();

            function changePos() {
              activePos = thisTab.find(".tabs-header .active").position();
              if (activePos) {
                thisTab
                  .find(".tab-hover")
                  .stop()
                  .css({
                    left: activePos.left,
                    width: thisTab.find(".tabs-header .active").width(),
                  });
              }
            }

            changePos();
            var tabHeight = thisTab.find(".tab.active").outerHeight();

            function animateTabHeight() {
              tabHeight = thisTab.find(".tab.active").outerHeight();
              thisTab
                .find(".tabs-content")
                .stop()
                .css({ height: tabHeight + "px" });
            }

            animateTabHeight();

            function changeTab() {
              var getTabId = thisTab
                .find(".tabs-header .active a")
                .attr("data-tab-id");
              thisTab
                .find(".tab")
                .stop()
                .fadeOut(300, function () {
                  $(this).removeClass("active");
                })
                .hide();
              thisTab
                .find(".tab[data-tab-id=" + getTabId + "]")
                .stop()
                .fadeIn(300, function () {
                  $(this).addClass("active");
                  animateTabHeight();
                });
            }

            thisTab.find(".tabs-header a").on("click", function (e) {
              e.preventDefault();
              var tabId = $(this).attr("data-tab-id");
              thisTab
                .find(".tabs-header a")
                .stop()
                .parent()
                .removeClass("active");
              $(this).stop().parent().addClass("active");
              changePos();
              tabCurrentItem = tabItems.filter(".active");
              thisTab
                .find(".tab")
                .stop()
                .fadeOut(300, function () {
                  $(this).removeClass("active");
                })
                .hide();
              thisTab
                .find('.tab[data-tab-id="' + tabId + '"]')
                .stop()
                .fadeIn(300, function () {
                  $(this).addClass("active");
                  animateTabHeight();
                });
            });
            var tabItems = thisTab.find(".tabs-header ul li");
            var tabCurrentItem = tabItems.filter(".active");
            thisTab.find(".tab-next").on("click", function (e) {
              e.preventDefault();
              var nextItem = tabCurrentItem.next();
              tabCurrentItem.removeClass("active");
              if (nextItem.length) {
                tabCurrentItem = nextItem.addClass("active");
              } else {
                tabCurrentItem = tabItems.first().addClass("active");
              }
              changePos();
              changeTab();
            });
            thisTab.find(".tab-prev").on("click", function (e) {
              e.preventDefault();
              var prevItem = tabCurrentItem.prev();
              tabCurrentItem.removeClass("active");
              if (prevItem.length) {
                tabCurrentItem = prevItem.addClass("active");
              } else {
                tabCurrentItem = tabItems.last().addClass("active");
              }
              changePos();
              changeTab();
            });
          });
        }
      })
      .trigger("resize");
    $(".keywords-container").each(function () {
      var keywordInput = $(this).find(".keyword-input");
      var keywordsList = $(this).find(".keywords-list");

      function addKeyword() {
        var $newKeyword = $(
          "<span class='keyword'><span class='keyword-remove'></span><span class='keyword-text'>" +
            keywordInput.val() +
            "</span></span>"
        );
        keywordsList.append($newKeyword).trigger("resizeContainer");
        keywordInput.val("");
      }

      keywordInput.on("keyup", function (e) {
        if (e.keyCode == 13 && keywordInput.val() !== "") {
          addKeyword();
        }
      });
      $(".keyword-input-button").on("click", function () {
        if (keywordInput.val() !== "") {
          addKeyword();
        }
      });
      $(document).on("click", ".keyword-remove", function () {
        $(this).parent().addClass("keyword-removed");

        function removeFromMarkup() {
          $(".keyword-removed").remove();
        }

        setTimeout(removeFromMarkup, 500);
        keywordsList.css({ height: "auto" }).height();
      });
      keywordsList.on("resizeContainer", function () {
        var heightnow = $(this).height();
        var heightfull = $(this)
          .css({ "max-height": "auto", height: "auto" })
          .height();
        $(this).css({ height: heightnow }).animate({ height: heightfull }, 200);
      });
      $(window).on("resize", function () {
        keywordsList.css({ height: "auto" }).height();
      });
      $(window).on("load", function () {
        var keywordCount = $(".keywords-list").children("span").length;
        if (keywordCount > 0) {
          keywordsList.css({ height: "auto" }).height();
        }
      });
    });

    function ThousandSeparator(nStr) {
      nStr += "";
      var x = nStr.split(".");
      var x1 = x[0];
      var x2 = x.length > 1 ? "." + x[1] : "";
      var rgx = /(\d+)(\d{3})/;
      while (rgx.test(x1)) {
        x1 = x1.replace(rgx, "$1" + "," + "$2");
      }
      return x1 + x2;
    }

    var avgValue =
      (parseInt($(".bidding-slider").attr("data-slider-min")) +
        parseInt($(".bidding-slider").attr("data-slider-max"))) /
      2;
    if ($(".bidding-slider").data("slider-value") === "auto") {
      $(".bidding-slider").attr({ "data-slider-value": avgValue });
    }
    $(".bidding-slider").slider();
    $(".bidding-slider").on("slide", function (slideEvt) {
      $("#biddingVal").text(ThousandSeparator(parseInt(slideEvt.value)));
    });
    $("#biddingVal").text(
      ThousandSeparator(parseInt($(".bidding-slider").val()))
    );
    var currencyAttr = $(".range-slider").attr("data-slider-currency");
    $(".range-slider").slider({
      formatter: function (value) {
        return (
          currencyAttr +
          ThousandSeparator(parseInt(value[0])) +
          " - " +
          currencyAttr +
          ThousandSeparator(parseInt(value[1]))
        );
      },
    });
    $(".range-slider-single").slider();
    var radios = document.querySelectorAll(".payment-tab-trigger > input");
    for (var i = 0; i < radios.length; i++) {
      radios[i].addEventListener("change", expandAccordion);
    }

    function expandAccordion(event) {
      var tabber = this.closest(".payment");
      var allTabs = tabber.querySelectorAll(".payment-tab");
      for (var i = 0; i < allTabs.length; i++) {
        allTabs[i].classList.remove("payment-tab-active");
      }
      event.target.parentNode.parentNode.classList.add("payment-tab-active");
    }

    $(".billing-cycle-radios").on("click", function () {
      if ($(".billed-yearly-radio input").is(":checked")) {
        $(".pricing-plans-container")
          .addClass("billed-yearly")
          .removeClass("billed-lifetime");

        $(".pricing-plan").show();
        $('.pricing-plan[data-annual-price="0"]').hide();
      }
      if ($(".billed-monthly-radio input").is(":checked")) {
        $(".pricing-plans-container")
          .removeClass("billed-yearly")
          .removeClass("billed-lifetime");

        $(".pricing-plan").show();
        $('.pricing-plan[data-monthly-price="0"]').hide();
      }
      if ($(".billed-lifetime-radio input").is(":checked")) {
        $(".pricing-plans-container")
          .addClass("billed-lifetime")
          .removeClass("billed-yearly");

        $(".pricing-plan").show();
        $('.pricing-plan[data-lifetime-price="0"]').hide();
      }
    });
    $(".billing-cycle-radios input").first().trigger("click");

    function qtySum() {
      var arr = document.getElementsByName("qtyInput");
      var tot = 0;
      for (var i = 0; i < arr.length; i++) {
        if (parseInt(arr[i].value)) tot += parseInt(arr[i].value);
      }
    }

    qtySum();
    $(".qtyDec, .qtyInc").on("click", function () {
      var $button = $(this);
      var oldValue = $button.parent().find("input").val();
      if ($button.hasClass("qtyInc")) {
        $button
          .parent()
          .find("input")
          .val(parseFloat(oldValue) + 1);
      } else {
        if (oldValue > 1) {
          $button
            .parent()
            .find("input")
            .val(parseFloat(oldValue) - 1);
        } else {
          $button.parent().find("input").val(1);
        }
      }
      qtySum();
      $(".qtyTotal").addClass("rotate-x");
    });

    function inlineBG() {
      $(".single-page-header, .intro-banner").each(function () {
        var attrImageBG = $(this).attr("data-background-image");
        if (attrImageBG !== undefined) {
          $(this).append('<div class="background-image-container"></div>');
          $(".background-image-container").css(
            "background-image",
            "url(" + attrImageBG + ")"
          );
        }
      });
    }

    inlineBG();
    $(".intro-search-field").each(function () {
      var bannerLabel = $(this).children("label").length;
      if (bannerLabel > 0) {
        $(this).addClass("with-label");
      }
    });
    $(".photo-box, .photo-section, .video-container").each(function () {
      var photoBox = $(this);
      var photoBoxBG = $(this).attr("data-background-image");
      if (photoBox !== undefined) {
        $(this).css("background-image", "url(" + photoBoxBG + ")");
      }
    });

    $(".share-buttons-icons a").each(function () {
      var buttonBG = $(this).attr("data-button-color");
      if (buttonBG !== undefined) {
        $(this).css("background-color", buttonBG);
      }
    });
    var $tabsNav = $(".popup-tabs-nav"),
      $tabsNavLis = $tabsNav.children("li");
    $tabsNav.each(function () {
      var $this = $(this);
      $this
        .next()
        .children(".popup-tab-content")
        .stop(true, true)
        .hide()
        .first()
        .show();
      $this.children("li").first().addClass("active").stop(true, true).show();
    });
    $tabsNavLis.on("click", function (e) {
      var $this = $(this);
      $this.siblings().removeClass("active").end().addClass("active");
      $this
        .parent()
        .next()
        .children(".popup-tab-content")
        .stop(true, true)
        .hide()
        .siblings($this.find("a").attr("href"))
        .fadeIn();
      e.preventDefault();
    });
    var hash = window.location.hash;
    var anchor = $('.tabs-nav a[href="' + hash + '"]');
    if (anchor.length === 0) {
      $(".popup-tabs-nav li:first").addClass("active").show();
      $(".popup-tab-content:first").show();
    } else {
      anchor.parent("li").click();
    }
    $(".register-tab").on("click", function (event) {
      event.preventDefault();
      $(".popup-tab-content").hide();
      $("#register.popup-tab-content").show();
      $("body")
        .find('.popup-tabs-nav a[href="#register"]')
        .parent("li")
        .click();
    });
    $(".popup-tabs-nav").each(function () {
      var listCount = $(this).find("li").length;
      if (listCount < 2) {
        $(this).css({ "pointer-events": "none" });
      }
    });
    $(".indicator-bar").each(function () {
      var indicatorLenght = $(this).attr("data-indicator-percentage");
      $(this)
        .find("span")
        .css({ width: indicatorLenght + "%" });
    });

    $(".default-slick-carousel").slick({
      rtl: rtl,
      infinite: false,
      slidesToShow: 3,
      slidesToScroll: 1,
      dots: false,
      arrows: true,
      adaptiveHeight: true,
      responsive: [
        { breakpoint: 1292, settings: { dots: true, arrows: false } },
        {
          breakpoint: 993,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2,
            dots: true,
            arrows: false,
          },
        },
        {
          breakpoint: 769,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
            dots: true,
            arrows: false,
          },
        },
      ],
    });
    $(".testimonial-carousel").slick({
      rtl: rtl,
      centerMode: true,
      centerPadding: "30%",
      slidesToShow: 1,
      dots: false,
      arrows: true,
      adaptiveHeight: true,
      responsive: [
        {
          breakpoint: 1600,
          settings: { centerPadding: "21%", slidesToShow: 1 },
        },
        {
          breakpoint: 993,
          settings: { centerPadding: "15%", slidesToShow: 1 },
        },
        {
          breakpoint: 769,
          settings: { centerPadding: "5%", dots: true, arrows: false },
        },
      ],
    });
    $(".logo-carousel").slick({
      rtl: rtl,
      infinite: true,
      slidesToShow: 5,
      slidesToScroll: 1,
      dots: false,
      arrows: true,
      responsive: [
        {
          breakpoint: 1365,
          settings: { slidesToShow: 5, dots: true, arrows: false },
        },
        {
          breakpoint: 992,
          settings: { slidesToShow: 3, dots: true, arrows: false },
        },
        {
          breakpoint: 768,
          settings: { slidesToShow: 1, dots: true, arrows: false },
        },
      ],
    });
    $(".blog-carousel").slick({
      rtl: rtl,
      infinite: false,
      slidesToShow: 3,
      slidesToScroll: 1,
      dots: false,
      arrows: true,
      responsive: [
        {
          breakpoint: 1365,
          settings: { slidesToShow: 3, dots: true, arrows: false },
        },
        {
          breakpoint: 992,
          settings: { slidesToShow: 2, dots: true, arrows: false },
        },
        {
          breakpoint: 768,
          settings: { slidesToShow: 1, dots: true, arrows: false },
        },
      ],
    });
    $(".mfp-gallery-container").each(function () {
      $(this).magnificPopup({
        type: "image",
        delegate: "a.mfp-gallery",
        fixedContentPos: true,
        fixedBgPos: true,
        overflowY: "auto",
        closeBtnInside: false,
        preloader: true,
        removalDelay: 0,
        mainClass: "mfp-fade",
        gallery: { enabled: true, tCounter: "" },
      });
    });
    $(".popup-with-zoom-anim").magnificPopup({
      type: "inline",
      fixedContentPos: false,
      fixedBgPos: true,
      overflowY: "auto",
      closeBtnInside: true,
      preloader: false,
      midClick: true,
      removalDelay: 300,
      mainClass: "my-mfp-zoom-in",
    });
    $(".mfp-image").magnificPopup({
      type: "image",
      closeOnContentClick: true,
      mainClass: "mfp-fade",
      image: { verticalFit: true },
    });
    $(".popup-youtube, .popup-vimeo, .popup-gmaps").magnificPopup({
      disableOn: 700,
      type: "iframe",
      mainClass: "mfp-fade",
      removalDelay: 160,
      preloader: false,
      fixedContentPos: false,
    });

    // cookie consent
    if (localStorage.getItem("Quick-cookie") != "1") {
      $(".cookieConsentContainer").delay(2000).fadeIn();
    }
    $(".cookieAcceptButton").on("click", function () {
      localStorage.setItem("Quick-cookie", "1");
      $(".cookieConsentContainer").fadeOut();
    });

    // testimonial carousel
    $(".single-carousel").slick({
      rtl: rtl,
      centerMode: true,
      centerPadding: "0",
      slidesToShow: 1,
      dots: true,
      arrows: false,
      adaptiveHeight: true,
      autoplay: true,
    });

    // ai template blocks
    $(".ai-templates-category").on("click", function (e) {
      e.preventDefault();
      // make active
      $(".template-categories li").removeClass("active");
      $(this).parents("li").addClass("active");

      if ($(this).data("category") === "all") {
        $(".ai-template-blocks > div").show();
        $(".ai-templates-category-title").show();
      } else if ($(this).data("category") === "hired") {
        $("[data-hired]").hide();
        $("[data-hired=1]").show();
      } else if ($(this).data("category") === "uai") {
        $("[data-uai=0]").hide();
        $("[data-uai=1]").show();
      } else {
        $("[data-hired]").show();
        $("[data-hired=1]").hide();
      }
    });

    // header icon
    $(".header-icon").on("click", function (e) {
      e.preventDefault();
      if ($(".dashboard-sidebar").hasClass("hide-sidebar")) {
        $(".dashboard-sidebar").removeClass("hide-sidebar");
        setTimeout(function () {
          $(".dashboard-sidebar").css("width", "auto");
        }, 200);
      } else {
        $(".dashboard-sidebar").css("width", 0);
        setTimeout(function () {
          $(".dashboard-sidebar").addClass("hide-sidebar");
        }, 200);
      }
    });

    $(".toggleFullScreen").on("click", function (e) {
      e.preventDefault();
      if (
        document.fullScreenElement ||
        (!document.mozFullScreen && !document.webkitIsFullScreen)
      ) {
        if (document.documentElement.requestFullScreen) {
          document.documentElement.requestFullScreen();
        } else if (document.documentElement.mozRequestFullScreen) {
          document.documentElement.mozRequestFullScreen();
        } else if (document.documentElement.webkitRequestFullScreen) {
          document.documentElement.webkitRequestFullScreen(
            Element.ALLOW_KEYBOARD_INPUT
          );
        }
        $(this)
          .find("i")
          .removeClass("icon-feather-maximize")
          .addClass("icon-feather-minimize");
      } else {
        if (document.cancelFullScreen) {
          document.cancelFullScreen();
        } else if (document.mozCancelFullScreen) {
          document.mozCancelFullScreen();
        } else if (document.webkitCancelFullScreen) {
          document.webkitCancelFullScreen();
        }
        $(this)
          .find("i")
          .removeClass("icon-feather-minimize")
          .addClass("icon-feather-maximize");
      }
    });

    // ai template blocks
    $(".ai-templates-category").on("click", function (e) {
      e.preventDefault();
      // make active
      $(".template-categories li").removeClass("active");
      $(this).parents("li").addClass("active");

      if ($(this).data("category") === "all") {
        $(".ai-template-blocks > div").show();
        $(".ai-templates-category-title").show();
      } else if ($(this).data("category") === "hired") {
        $("[data-hired]").hide();
        $("[data-hired=1]").show();
      } else if ($(this).data("category") === "uai") {
        $("[data-uai=0]").hide();
        $("[data-uai=1]").show();
      } else {
        $("[data-hired]").show();
        $("[data-hired=1]").hide();
      }

      if ($(".ai-template-blocks-toggle").length) {
        if ($(".ai-template-blocks").height() <= 690) {
          $(".ai-template-blocks-toggle").removeClass("show-blocks-toggle");
          $(".ai-template-blocks-toggle-button").hide();
        } else {
          $(".ai-template-blocks-toggle").addClass("show-blocks-toggle");
          $(".ai-template-blocks-toggle-button").show();
        }
      }
    });

    $("#export_to_word").on("click", function (e) {
      e.preventDefault();

      var preHtml =
        "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'><head><meta charset='utf-8'><title>Export HTML To Doc</title></head><body>";
      var postHtml = "</body></html>";
      var html = preHtml + tinymce.activeEditor.getContent() + postHtml;

      var blob = new Blob(["\ufeff", html], {
        type: "application/msword",
      });

      // Specify link url
      var url =
        "data:application/vnd.ms-word;charset=utf-8," +
        encodeURIComponent(html);

      // Specify file name
      var filename = "document.doc";

      // Create download link element
      var downloadLink = document.createElement("a");

      document.body.appendChild(downloadLink);

      if (navigator.msSaveOrOpenBlob) {
        navigator.msSaveOrOpenBlob(blob, filename);
      } else {
        // Create a link to the file
        downloadLink.href = url;

        // Setting the file name
        downloadLink.download = filename;

        //triggering the function
        downloadLink.click();
      }

      document.body.removeChild(downloadLink);
    });

    $("#export_to_txt").on("click", function (e) {
      e.preventDefault();

      var txt = tinymce.activeEditor.getContent();

      // replace br with \n
      var regex = /<br\s*[\/]?>/gi;
      txt = txt.replace(regex, "\n");

      // remove html tags
      txt = $("<div>" + txt + "</div>").text();

      var downloadableLink = document.createElement("a");
      downloadableLink.setAttribute(
        "href",
        "data:text/plain;charset=utf-8," + encodeURIComponent(txt)
      );
      downloadableLink.download = "Text File" + ".txt";
      document.body.appendChild(downloadableLink);
      downloadableLink.click();
      document.body.removeChild(downloadableLink);
    });

    $("#copy_text").on("click", function (e) {
      e.preventDefault();

      tinyMCE.activeEditor.selection.select(tinyMCE.activeEditor.getBody());
      tinyMCE.activeEditor.execCommand("Copy");

      Snackbar.show({
        text: LANG_COPIED_SUCCESSFULLY,
        pos: "bottom-center",
        showAction: false,
        actionText: "Dismiss",
        duration: 3000,
        textColor: "#fff",
        backgroundColor: "#383838",
      });
    });

    // credit
    if (DEVELOPER_CREDIT) {
      $(".footer-copyright-text").append(
        '&nbsp; | &nbsp;Developed by <a href="https://AITeamUp.com/">AITeamUp</a>'
      );
    }

    // template search
    $(document).on("keyup", "#template-search", function () {
      $('[data-category="all"]').click();

      var searchTerm = $(this).val().toLowerCase();
      $(".ai-template-blocks")
        .find("> div")
        .each(function () {
          if (
            $(this).filter(function () {
              return (
                $(this).find("h4").text().toLowerCase().indexOf(searchTerm) > -1
              );
            }).length > 0 ||
            searchTerm.length < 1
          ) {
            $(this).show();
          } else {
            $(this).hide();
          }
        });
    });

    // chatbot search
    $(document).on("keyup", "#chat-bot-search", function () {
      $('[data-category="all"]').click();

      var searchTerm = $(this).val().toLowerCase();
      $(".ai-template-blocks")
        .find("> div")
        .each(function () {
          if (
            $(this).filter(function () {
              return (
                $(this).data("search").toLowerCase().indexOf(searchTerm) > -1
              );
            }).length > 0 ||
            searchTerm.length < 1
          ) {
            $(this).show();
          } else {
            $(this).hide();
          }
        });
    });

    // ai images
    $(".image-advance-settings-trigger").on("click", function (e) {
      e.preventDefault();
      $(".image-advance-settings").slideToggle();
      var $plus = $(this).find("strong");
      if ($plus.text() === "+") {
        $plus.text("-");
      } else {
        $plus.text("+");
      }
    });

    $(".ai-template-blocks-toggle-button a").on("click", function (e) {
      e.preventDefault();
      $(".ai-template-blocks-toggle").toggleClass("show-blocks-toggle");
    });
  });
})(this.jQuery);

function readImageURL(input, id) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      $("#" + id).attr("src", e.target.result);
      $("#" + id).show();
    };
    reader.readAsDataURL(input.files[0]);
  } else {
    $("#" + id).hide();
  }
}

var w = screen.width - (screen.width * 25) / 100;
var h = screen.height - (screen.height * 25) / 100;
var left = screen.width / 2 - w / 2;
var top = screen.height / 2 - h / 2;

function fblogin() {
  var newWin = window.open(
    siteurl + "includes/social_login/facebook/index.php",
    "fblogin",
    "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, copyhistory=no,display=popup, width=" +
      w +
      ", height=" +
      h +
      ", top=" +
      top +
      ", left=" +
      left
  );
}

function gmlogin() {
  var newWin = window.open(
    siteurl + "includes/social_login/google/index.php",
    "gmlogin",
    "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, copyhistory=no, width=" +
      w +
      ", height=" +
      h +
      ", top=" +
      top +
      ", left=" +
      left
  );
}

// copy code
function copyAICode(button) {
  const pre = button.parentElement;
  const code = pre.querySelector("code");
  const range = document.createRange();
  range.selectNode(code);
  window.getSelection().removeAllRanges();
  window.getSelection().addRange(range);
  document.execCommand("copy");
  window.getSelection().removeAllRanges();
  Snackbar.show({
    text: LANG_COPIED_SUCCESSFULLY,
    pos: "bottom-center",
    showAction: false,
    actionText: "Dismiss",
    duration: 3000,
    textColor: "#fff",
    backgroundColor: "#383838",
  });
}

function hire_bot(id) {
  var formData = new FormData();
  formData.append("bot_id", id);

  $.ajax({
    type: "POST",
    url: ajaxurl + "?action=hire_bot",
    data: formData,
    dataType: "json",
    cache: false,
    contentType: false,
    processData: false,
    success: function (response) {
      if (response.success) {
        // Toggle visibility of buttons
        $("#hireButton-" + id).hide();
        $("#fireButton-" + id).show();
      } else {
        $error.html(response.error).slideDown().focus();
      }
    },
  });
}

function fire_bot(id) {
  var formData = new FormData();
  formData.append("bot_id", id);

  $.ajax({
    type: "POST",
    url: ajaxurl + "?action=fire_bot",
    data: formData,
    dataType: "json",
    cache: false,
    contentType: false,
    processData: false,
    success: function (response) {
      if (response.success) {
        // Toggle visibility of buttons
        $("#fireButton-" + id).hide();
        $("#hireButton-" + id).show();
      } else {
        $error.html(response.error).slideDown().focus();
      }
    },
  });
}

function save_theme_color() {
  const selectElement = document.getElementById("theme_mode");
  const selectedOption =
    selectElement.options[selectElement.selectedIndex].value;

  var formData = new FormData();
  formData.append("theme_color_value", selectedOption);

  $.ajax({
    type: "POST",
    url: ajaxurl + "?action=save_theme_color",
    data: formData,
    dataType: "json",
    cache: false,
    contentType: false,
    processData: false,
    success: function (response) {
      if (response.success) {
        location.reload();
      } else {
        $error.html(response.error).slideDown().focus();
      }
    },
  });
}
function save_autoresponder() {
  const formData = new FormData();
  const fields = [
    "mailchimp_api_key",
    "mailchimp_list_id",
    "activecampaign_api_key",
    "activecampaign_list_id",
    "activecampaign_account_id",
    "sendlane_api_key",
    "sendlane_list_id",
    "getresponse_api_key",
    "getresponse_campaign_id",
    "icontact_api_username",
    "icontact_api_password",
    "icontact_account_id",
    "icontact_list_id",
    "constantcontact_api_key",
    "constantcontact_list_id",
    "drip_api_token",
    "drip_account_id",
    "drip_tag",
  ];

  fields.forEach((field) => {
    const value = document.getElementById(field).value;
    formData.append(field, value);
  });

  $.ajax({
    type: "POST",
    url: `${ajaxurl}?action=save_autoresponder`,
    data: formData,
    dataType: "json",
    cache: false,
    contentType: false,
    processData: false,
    success: function (response) {
      if (response.success) {
        Snackbar.show({
          text: "Autoresponder keys saved sccessfully.",
          pos: "bottom-center",
          showAction: false,
          actionText: "Dismiss",
          duration: 3000,
          textColor: "#fff",
          backgroundColor: "#383838",
        });
      } else {
        $error.html(response.error).slideDown().focus();
      }
    },
  });
}

function save_custom_instruction() {
  const formData = new FormData();
  const fields = [
    "user_based",
    "user_do_for",
    "user_hobbies",
    "user_subjects",
    "user_goals",
    "res_formal",
    "res_long",
    "res_address",
    "res_opinions",
  ];

  fields.forEach((field) => {
    const value = document.getElementById(field).value;
    formData.append(field, value);
  });

  $.ajax({
    type: "POST",
    url: `${ajaxurl}?action=save_custom_instruction`,
    data: formData,
    dataType: "json",
    cache: false,
    contentType: false,
    processData: false,
    success: function (response) {
      if (response.success) {
        Snackbar.show({
          text: "Custom instruction saved sccessfully.",
          pos: "bottom-center",
          showAction: false,
          actionText: "Dismiss",
          duration: 3000,
          textColor: "#fff",
          backgroundColor: "#383838",
        });
      } else {
        $error.html(response.error).slideDown().focus();
      }
    },
  });
}

function hide_template_block(element) {
  const categoryValue = element.getAttribute("data-category");

  console.log(categoryValue);
  for (i = 1; i < 8; i++) {
    const calssName = ".category-" + i;
    const elements = document.querySelectorAll(calssName);
    for (var j = 0; j < elements.length; j++) {
      if (elements[j].classList.contains(`category-${categoryValue}`)) {
        elements[j].style.display = "block";
      } else {
        elements[j].style.display = "none";
      }
    }
  }
}

function updateFileName(input) {
  const selectedFileNameElement = document.getElementById("selectedFileName");
  if (input.files.length > 0) {
    selectedFileNameElement.innerText = input.files[0].name;
  } else {
    selectedFileNameElement.innerText = "No file selected";
  }
}

// all document page search bar functionality
$("#search-document").on("keyup", function () {
  var searchTerm = $(this).val().toLowerCase();
  $("#get-search-records")
    .find("tr")
    .each(function () {
      if (
        $(this).filter(function () {
          return (
            $(this).find("td").text().toLowerCase().indexOf(searchTerm) > -1
          );
        }).length > 0 ||
        searchTerm.length < 1
      ) {
        $(this).show();
      } else {
        $(this).hide();
      }
    });
});

// preview ticket
$("body").on("click", ".preview-ticket", function (e) {
  e.preventDefault();
  e.stopPropagation();

  var $btn = $(this);
  var action = $btn.data("action");

  $.ajax({
    type: "POST",
    url: ajaxurl + "?action=" + action,

    data: {
      id: $btn.data("id"),
    },
    dataType: "json",
    success: function (response) {
      if (response.success) {
        $("#ticket-view-modal").modal("show");
        $("#ticket-view-modal").find('[id="ticket-view-id"]').text(response.id);
        $("#ticket-view-modal")
          .find('[id="ticket-view-title"]')
          .attr("value", response.title);
        $("#ticket-view-modal")
          .find('[id="ticket-view-category"]')
          .attr("value", response.category);
        $("#ticket-view-modal")
          .find('[id="ticket-view-priority"]')
          .attr("value", response.priority);
        $("#ticket-view-modal")
          .find('[id="ticket-view-time"]')
          .attr("value", response.update_at);
        $("#ticket-view-modal")
          .find('[id="ticket-view-comment"]')
          .text(response.admin_comment);
        $("#ticket-view-modal")
          .find('[id="ticket-view-status"]')
          .attr("value", response.status);

        $("#ticket-view-chat-history").empty();

        $.each(response.chat_data, function (index, chat) {
          // var messageHTML = '<div><a>' + chat.author + '</a>' + chat.message + '</div>';

          side = chat.author == "admin" ? "me" : "";

          const msgHTML = `
              <div class="message-bubble ${side}">
                  <div class="message-bubble-inner">
                      <div class="message-text" >
                          <div class="markdown-body">${chat.message}</div>
                      </div>
                  </div>
                  <div class="clearfix"></div>
              </div>
            `;

          $("#ticket-view-chat-history").append(msgHTML);
        });
      }
    },
  });
});

// ticket delete
$("body").on("click", ".quick-delete-ticket", function (e) {
  e.preventDefault();
  e.stopPropagation();

  var $btn = $(this);
  var action = $btn.data("action");

  if (confirm(LANG_ARE_YOU_SURE)) {
    $btn.addClass("button-progress").prop("disabled", true);
    $.ajax({
      type: "POST",
      url: ajaxurl + "?action=" + action,

      data: {
        id: $btn.data("id"),
      },
      dataType: "json",
      success: function (response) {
        $btn.removeClass("button-progress").prop("disabled", false);
        if (response.success) {
          $btn.closest("tr").fadeOut("slow", function () {
            $(this).remove();
            if (
              document.getElementById("get-search-records").childElementCount ==
              0
            ) {
              var newRow = document.createElement("tr");
              newRow.className = "no-order-found";

              var newCell = document.createElement("td");
              newCell.colSpan = 9;
              newCell.className = "text-center";
              newCell.textContent = "No tickets found.";

              newRow.appendChild(newCell);

              document.getElementById("get-search-records").appendChild(newRow);
            }
          });

          Snackbar.show({
            text: response.message,
            pos: "bottom-center",
            showAction: false,
            actionText: "Dismiss",
            duration: 3000,
            textColor: "#fff",
            backgroundColor: "#383838",
          });
        }
      },
    });
  }
});

$("#support-chat-send-btn").on("click", function () {
  var message = document.getElementById("ticket-chat-send").value;
  var ticket_id = document.getElementById("ticket-view-id").text;

  var $btn = $(this);

  $btn.addClass("button-progress").prop("disabled", true);

  var formData = new FormData();
  formData.append("ticket_id", ticket_id);
  formData.append("message", message);

  $.ajax({
    type: "POST",
    url: ajaxurl + "?action=send_ticket_message",
    data: formData,
    dataType: "json",
    cache: false,
    contentType: false,
    processData: false,
    success: function (response) {
      if (response.success) {
        $btn.removeClass("button-progress").prop("disabled", false);

        side = "";

        const msgHTML = `
              <div class="message-bubble ${side}">
                  <div class="message-bubble-inner">
                      <div class="message-text" >
                          <div class="markdown-body">${message}</div>
                      </div>
                  </div>
                  <div class="clearfix"></div>
              </div>
            `;

        $("#ticket-view-chat-history").append(msgHTML);
      } else {
        $error.html(response.error).slideDown().focus();
      }
    },
  });
});

// ticket-create modal open
$(".support-create-btn").on("click", function () {
  $("#ticket-add-modal").modal("show");
});
// embed modal open
$(".uai-agent-embed").on("click", function () {
  $(".uai-agent-embed").removeClass("active");
  $(this).addClass("active");
  $("#embed-modal").modal("show");
});

// ticket-creat button action
$(".ticket-create-btn").on("click", function (e) {
  e.preventDefault();
  e.stopPropagation();

  var $btn = $(this);

  $btn.addClass("button-progress").prop("disabled", true);

  const title = document.getElementById("ticket-create-title").value;
  const content = document.getElementById("ticket-create-content").value;
  const category = document.getElementById("ticket-create-category").value;
  const priority = document.getElementById("ticket-create-priority").value;
  var formData = new FormData();
  formData.append("title", title);
  formData.append("content", content);
  formData.append("category", category);
  formData.append("priority", priority);

  $.ajax({
    type: "POST",
    url: ajaxurl + "?action=create_ticket",
    data: formData,
    dataType: "json",
    cache: false,
    contentType: false,
    processData: false,
    success: function (response) {
      if (response.success) {
        if (response.total_count == 1) {
          window.location.reload();
        } else {
          $btn.removeClass("button-progress").prop("disabled", false);

          // resetting initial values in form
          $("#ticket-add-modal").find('[id = "ticket-create-title"]').val("");

          $("#ticket-add-modal").find('[id = "ticket-create-content"]').val("");

          $("#ticket-add-modal")
            .find('[id = "ticket-create-category"]')
            .val("General Inquiry");

          $("#ticket-add-modal")
            .find('[id = "ticket-create-priority"]')
            .val("Low");

          $("#ticket-add-modal").modal("hide");

          const newRow = $($("#get-search-records").children()[0]).clone();
          $("#get-search-records").prepend(newRow);
          newRow.find('[data-label="Ticket"] strong').html("# " + response.id);
          newRow.find('[data-label="Category"]').html(response.category);
          newRow.find('[data-label="Title"]').html(response.title);
          newRow.find('[data-label="Priority"] span').remove();
          newRow
            .find('[data-label="Priority"]')
            .append(
              '<span class="ticket-priority ticket-priority-' +
                response.priority?.toLowerCase() +
                '">' +
                response.priority +
                "</span>"
            );
          newRow.find('[data-label="Content"]').html(response.content);
          newRow.find('[data-label="Date"] small').html(response.update_at);
          newRow
            .find('[data-label="Comment by Admin"]')
            .html(response.admin_comment);
          newRow.find('[data-label="Status"] span').html(response.status);
          newRow
            .find('[data-label="Status"] span')
            .removeClass("ticket-status-progress")
            .prop("disabled", false);
          newRow
            .find('[data-label="Status"] span')
            .addClass("ticket-status-open")
            .prop("disabled", true);
          newRow
            .find('[data-action="delete_ticket"]')
            .attr("data-id", response.id);
          newRow
            .find('[data-action="preview_ticket"]')
            .attr("data-id", response.id);

          Snackbar.show({
            text: response.message,
            pos: "bottom-center",
            showAction: false,
            actionText: "Dismiss",
            duration: 3000,
            textColor: "#fff",
            backgroundColor: "#383838",
          });
        }
      } else {
        $error.html(response.error).slideDown().focus();
      }
    },
  });
});

/*************************** NEW MEMBERSHIP PLAN PAGE SCRIPT START ***************************/
$("body").on("click", ".show_price_by_action", function () {
  var action = $(this).data("action");
  $(".show_price_by_action").removeClass("btnOne").addClass("btnTwo");
  $(this).removeClass("btnTwo").addClass("btnOne");

  if (action === "monthly") {
    $(".monthly_price_number").removeClass("d-none");
    $(".annual_price_number").addClass("d-none");

    $(".annually_membership_plan").addClass("d-none");
    $(".monthly_membership_plan").removeClass("d-none");
  }
  if (action === "annually") {
    $(".monthly_price_number").addClass("d-none");
    $(".annual_price_number").removeClass("d-none");

    $(".annually_membership_plan").removeClass("d-none");
    $(".monthly_membership_plan").addClass("d-none");
  }
});

function chooseMembershipPlan(planDuration, planIndex, planPrice) {
  $("body").find("#billed-type").val(planDuration);
  $("body").find("#upgrade").val(planIndex);
  setTimeout(function () {
    $("body").find("form#new_membership_form").trigger("submit");
  }, 300);
}
/**************************** NEW MEMBERSHIP PLAN PAGE SCRIPT END ****************************/
$(".embed-create-btn").on("click", function (e) {
  e.preventDefault();
  e.stopPropagation();

  let botId;
  const activeEmbed = $(".uai-agent-embed.active")[0];
  
  if (activeEmbed) {
      botId = activeEmbed.id;
  } else {
      botId = $("#embed-modal").data("bot_id");
  }

  var $btn = $(this);

  const brandToggle = document.getElementById("brand_toggle").value;
  const embedName = document.getElementById("embed-name").value;
  const embedWebsite = document.getElementById("embed-website").value;
  const autoresponderList = document.getElementById(
    "embed-autoresponder-list"
  ).value;

  const uaiFontLetterColor = document.getElementById(
    "uai_font_letter_color"
  ).value;
  const uaiBoardColor = document.getElementById("uai_board_color").value;
  const userFontLetterColor = document.getElementById(
    "user_font_letter_color"
  ).value;
  const userBoardColor = document.getElementById("user_board_color").value;
  const embed_start_chat_btn_background_color = document.getElementById(
    "embed_start_chat_btn_background_color"
  ).value;
  const embed_start_chat_text = document.getElementById(
    "embed_start_chat_text"
  ).value;
  const embed_terms = document.getElementById("embed_terms").value;

  var formData = new FormData();

  if (embedName) formData.append("embed_name", embedName);
  if (embedWebsite) formData.append("embed_website", embedWebsite);
  if (autoresponderList)
    formData.append("autoresponder_list", autoresponderList);

  formData.append("bot_id", botId);

  formData.append("uai_font_letter_color", uaiFontLetterColor);
  formData.append("uai_board_color", uaiBoardColor);
  formData.append("user_font_letter_color", userFontLetterColor);
  formData.append("user_board_color", userBoardColor);
  formData.append("embed_terms", embed_terms);
  formData.append(
    "embed_start_chat_btn_background_color",
    embed_start_chat_btn_background_color
  );
  formData.append("embed_start_chat_text", embed_start_chat_text);
  formData.append("brand_toggle", brandToggle);
  formData.append("embed_horizental", embedHorizental);
  formData.append("file", $("#embed_image")[0].files[0]);

  $btn.addClass("button-progress").prop("disabled", true);

  $.ajax({
    type: "POST",
    url: ajaxurl + "?action=create_embed",
    data: formData,
    dataType: "json",
    cache: false,
    contentType: false,
    processData: false,
    success: function (response) {
      $btn.removeClass("button-progress").prop("disabled", false);
      if (!response.success) {
        Snackbar.show({
          text: response.error,
          pos: "top-center",
          showAction: false,
          actionText: "Dismiss",
          duration: 4000,
          textColor: "#ea5252",
          backgroundColor: "#ffe6e6",
        });
      } else {
        let generatedCode = response.generate_code;
        let standAlonelink = response.stand_alone_link;
        let embedId = response.embed_id;

        $("#embed-generate-modal").data("embed-id", embedId);          

        let premadedQuestions = response.pre_made_questions;
        let questionsArray = premadedQuestions.split("||");
        $(".pre_made_message_board").empty();

        let appendPromises = [];

        let index = 0;

        questionsArray.forEach(function (question) {
          let words = question.split(" ");
          let firstThreeWords = words.slice(0, 3).join(" ");
          let remainingPart = words.slice(3).join(" ");

          let questionItem = `
            <div class="col-sm-6 pre_made_message_item" data-question="${question}">
              <div class="form-group">
                <div>
                  <div class="pre_made_content_board">
                    <div style="font-weight: bold; font-size: 18px; margin: 10px;">${firstThreeWords}</div>
                    <div style="font-size: 15px; margin: 10px;">${remainingPart}</div>
                  </div>
                </div>
              </div>
            </div>
          `;

          let appendPromise = new Promise((resolve, reject) => {
            $(".pre_made_message_board").append(questionItem);
            resolve();
          });

          index++;
          $("#embed-generate-modal").data(`embed-q-${index}`, question);          
          appendPromises.push(appendPromise);
          // // Append question item to modal
          // $(".pre_made_message_board").append(questionItem);
        });

        Promise.all(appendPromises).then(() => {
          // Update content for other elements
          let generateIframeCode =
            `<div style="height: {your_height}px; width: {your_width}px; min-width: 400px !important; min-height: 500px !important; margin: auto" id="iframe-embed" aiteamup_data_id=${embedId}></div><br>` +
            response.generate_iframe_code;
        
          $(".embed_generate_letter").text(generatedCode.toString());
          $(".embed_link_letter").text(standAlonelink.toString());
          $(".iframe_script").text(generateIframeCode.toString());
        
          // Show the modal
          $("#embed-modal").modal("hide");
          $("#embed-generate-modal").modal("show");
        });
        // let generateIframeCode =
        //   `<div style="height: {your_height}px; width: {your_width}px; min-width: 400px !important; min-height: 500px !important; margin: auto" id="iframe-embed" aiteamup_data_id=${embedId}></div><br>` +
        //   response.generate_iframe_code;

        // $(".embed_generate_letter").text(generatedCode.toString());
        // $(".embed_link_letter").text(standAlonelink.toString());
        // $(".iframe_script").text(generateIframeCode.toString());

        // $("#embed-modal").modal("hide");
        // $("#embed-generate-modal").modal("show");
      }
    },
  });
});

$(".edit_pre_made_questions_btn").on("click", function(e) {
  $(".pre_made_questions_edit_pan").css("display", "block");
  $(".pre_made_message_pan").css("display", "none");

  let question1 = $("#embed-generate-modal").data('embed-q-1');   
  let question2 = $("#embed-generate-modal").data('embed-q-2');   
  let question3 = $("#embed-generate-modal").data('embed-q-3');   
  let question4 = $("#embed-generate-modal").data('embed-q-4');   
  
  $("#pre_made_questions_edit_1").attr("value", question1);
  $("#pre_made_questions_edit_2").attr("value", question2);
  $("#pre_made_questions_edit_3").attr("value", question3);
  $("#pre_made_questions_edit_4").attr("value", question4);
});

$(".edit_pre_made_questions_cancel_btn").on("click", function(e) {
  $(".pre_made_questions_edit_pan").css("display", "none");
  $(".pre_made_message_pan").css("display", "block");
});

$(".edit_pre_made_questions_ok_btn").on("click", function(e) {
  let question1 = $("#pre_made_questions_edit_1").val();
  let question2 = $("#pre_made_questions_edit_2").val();
  let question3 = $("#pre_made_questions_edit_3").val();
  let question4 = $("#pre_made_questions_edit_4").val();

  let totalQuestions = question1 + "||" + question2 + "||" + question3 + "||" + question4;
  let embedId = $("#embed-generate-modal").data("embed-id")

  let ajaxurl = "https://aiteamup.com/php/awwhehgrjy.php";

  let formData = new FormData();
  formData.append("embed_id", embedId);
  formData.append("total_questions", totalQuestions);

  var $btn = $(this);

  $btn.addClass("button-progress").prop("disabled", true);

  $.ajax({
    type: "POST",
    url: ajaxurl + "?action=edit_premade_questions",
    data: formData,
    dataType: "json",
    cache: false,
    contentType: false,
    processData: false,
    success: function (response) {
      $btn.removeClass("button-progress").prop("disabled", false);
      if (!response.success) {
        Snackbar.show({
          text: response.error,
          pos: "top-center",
          showAction: false,
          actionText: "Dismiss",
          duration: 4000,
          textColor: "#ea5252",
          backgroundColor: "#ffe6e6",
        });
      } else {
        let premadedQuestions = response.pre_made_questions;
        let questionsArray = premadedQuestions.split("||");
        $(".pre_made_message_board").empty();

        let appendPromises = [];

        let index = 0;

        questionsArray.forEach(function (question) {
          let words = question.split(" ");
          let firstThreeWords = words.slice(0, 3).join(" ");
          let remainingPart = words.slice(3).join(" ");

          let questionItem = `
            <div class="col-sm-6 pre_made_message_item" data-question="${question}">
              <div class="form-group">
                <div>
                  <div class="pre_made_content_board">
                    <div style="font-weight: bold; font-size: 18px; margin: 10px;">${firstThreeWords}</div>
                    <div style="font-size: 15px; margin: 10px;">${remainingPart}</div>
                  </div>
                </div>
              </div>
            </div>
          `;

          let appendPromise = new Promise((resolve, reject) => {
            $(".pre_made_message_board").append(questionItem);
            resolve();
          });

          index++;
          $("#embed-generate-modal").data(`embed-q-${index}`, question);          
          appendPromises.push(appendPromise);
          // // Append question item to modal
          // $(".pre_made_message_board").append(questionItem);
        });

        Promise.all(appendPromises).then(() => {
          // Update content for other elements
          $(".pre_made_questions_edit_pan").css("display", "none");
          $(".pre_made_message_pan").css("display", "block");
        });
      }
    }
  });
});

$(".stand_alone_link_copy_btn").on("click", function (e) {
  let standAloneLink = $(".embed_link_letter");
  let linkeToCopy = $(".embed_link_letter").text();

  navigator.clipboard
    .writeText(linkeToCopy)
    .then(() => {
      // Create a range and select the text content
      let range = document.createRange();
      range.selectNodeContents(standAloneLink[0]); // Select the text node
      window.getSelection().removeAllRanges(); // Clear existing selections
      window.getSelection().addRange(range); // Add the new selection

      $(".stand_alone_link_copy_btn")
        .find("i")
        .attr("class", "icon-feather-check");
      $(".stand_alone_link_copy_btn").attr("title", "Copied");
    })
    .catch((err) => {
      Snackbar.show({
        text: "Unable to copy code to clipboard. Please upgrade browser.",
        pos: "top-right",
        showAction: false,
        actionText: "Dismiss",
        duration: 4000,
        textColor: "#ea5252",
        backgroundColor: "#ffe6e6",
      });
    });
});

$(".iframe_code_copy_btn").on("click", function (e) {
  let iframeScript = $(".iframe_script");
  let iframeScriptToCopy = $(".iframe_script").text();

  navigator.clipboard
    .writeText(iframeScriptToCopy)
    .then(() => {
      // Create a range and select the text content
      let range = document.createRange();
      range.selectNodeContents(iframeScript[0]); // Select the text node
      window.getSelection().removeAllRanges(); // Clear existing selections
      window.getSelection().addRange(range); // Add the new selection

      $(".iframe_code_copy_btn").find("i").attr("class", "icon-feather-check");
      $(".iframe_code_copy_btn").attr("title", "Copied");
    })
    .catch((err) => {
      Snackbar.show({
        text: "Unable to copy code to clipboard. Please upgrade browser.",
        pos: "top-right",
        showAction: false,
        actionText: "Dismiss",
        duration: 4000,
        textColor: "#ea5252",
        backgroundColor: "#ffe6e6",
      });
    });
});

$(".copy-code-btn").on("click", function (e) {
  let embedLetter = $(".embed_generate_letter");
  let codeToCopy = $(".embed_generate_letter").text();

  navigator.clipboard
    .writeText(codeToCopy)
    .then(() => {
      // Create a range and select the text content
      let range = document.createRange();
      range.selectNodeContents(embedLetter[0]); // Select the text node
      window.getSelection().removeAllRanges(); // Clear existing selections
      window.getSelection().addRange(range); // Add the new selection

      $(".copy-code-btn").find("i").attr("class", "icon-feather-check");
      $(".copy-code-btn").attr("title", "Copied");
    })
    .catch((err) => {
      Snackbar.show({
        text: "Unable to copy code to clipboard. Please upgrade browser.",
        pos: "top-right",
        showAction: false,
        actionText: "Dismiss",
        duration: 4000,
        textColor: "#ea5252",
        backgroundColor: "#ffe6e6",
      });
    });
});

let embedStatus = "";
let embedHorizental = "right";

let autoresponder = $("#embed_chat_icon").data("autoresponder");

if (autoresponder == "None") {
  embedStatus = "setup";
} else {
  embedStatus = "initial";
}
// <?php if($auto_responder == "None") { ?>
//     embedStatus = "setup"
// <?php } else {?>
//     embedStatus = "initial"
// <?php } ?>

$(".aiteamup_embed_chat_icon_wrapper").on("click", function (e) {
  e.preventDefault();
  e.stopPropagation();

  if (embedStatus == "initial") {
    $("#embed_chat_pre_board").toggleClass("d-none");
  } else if (embedStatus == "setup") {
    $("#embed_chat_board").toggleClass("d-none");
  }
});

$(".left-horizental").on("click", function (e) {
  e.preventDefault();
  e.stopPropagation();

  embedHorizental = "left";

  var $left_button = $(".left-horizental");
  var $right_button = $(".right-horizental");

  $left_button
    .removeClass("unselected-horizental")
    .addClass("selected-horizental");

  $right_button
    .removeClass("selected-horizental")
    .addClass("unselected-horizental");
});

$(".right-horizental").on("click", function (e) {
  e.preventDefault();
  e.stopPropagation();

  embedHorizental = "right";

  var $left_button = $(".left-horizental");
  var $right_button = $(".right-horizental");

  $left_button
    .removeClass("selected-horizental")
    .addClass("unselected-horizental");

  $right_button
    .removeClass("unselected-horizental")
    .addClass("selected-horizental");
});

function sendEmbedChatMessage(e) {
  // e.preventDefault();
  // e.stopPropagation();

  //   // local
  //   let ajaxurl = "http://localhost/aiteamup/php/awwhehgrjy.php";

  // live
  let ajaxurl = "https://aiteamup.com/php/awwhehgrjy.php";
  let message = $("#aiteamup_embed_message_input").val();

  let embed_id = $("#embed_chat_icon").data("embed-id");
  let embed_user_name = $("#embed_chat_icon").data("embed-user-name");
  let botImage = $("#embed_chat_icon").data("embed-bot-image");
  let userImage = $("#embed_chat_icon").data("embed-user-image");
  let uaiFontLetterColor = $("#embed_chat_icon").data("uai_font_letter_color");
  let uaiBoardColor = $("#embed_chat_icon").data("uai_board_color");
  let userFontLetterColor = $("#embed_chat_icon").data(
    "user_font_letter_color"
  );
  let userBoardColor = $("#embed_chat_icon").data("user_board_color");

  var formData = new FormData();
  formData.append("embed_id", embed_id);
  formData.append("message", message);
  formData.append("embed_user_name", embed_user_name);

  var $btn = $(".aiteamup_embed_message_send_btn");

  // Hide the button image before making the AJAX request
  $btn.find("img").hide();

  $btn.addClass("button-progress").prop("disabled", true);

  if (message != "") {
    $(".aiteamup_embed_chat_pan").append(
      `<div class="aiteamup_embed_chat_content_board dynamic-element">
                    <div class="aiteamup_embed_chat_content_user" style="background-color: ${userBoardColor} !important"><a class="aiteamup_embed_letter" style="color: ${userFontLetterColor} !important">${message}</a></div>
                    <img class="aiteamup_embed_chat_user_avatar" src="${userImage}" ; width="100%" ; height="100%" ;>
                </div> `
    );
    $(".aiteamup_embed_chat_pan").animate(
      {
        scrollTop: $(".aiteamup_embed_chat_pan").prop("scrollHeight"),
      },
      1
    );
  }

  let typing = `
              <div class="aiteamup_embed_chat_content_board dynamic-element typing-dec">
                <img class="aiteamup_embed_chat_profile_avatar" src="${botImage}" ; width="100%" ; height="100%" ;>
                <div class="aiteamup_embed_chat_content" style="background-color: ${uaiBoardColor} !important;">
                  <div class="typing-indicator">
                      <span style="background-color: ${uaiFontLetterColor} !important;"></span>
                      <span style="background-color: ${uaiFontLetterColor} !important;"></span>
                      <span style="background-color: ${uaiFontLetterColor} !important;"></span>
                  </div> 
                </div>
              </div>`;

  $(".aiteamup_embed_chat_pan").append(typing);

  $.ajax({
    type: "POST",
    url: ajaxurl + "?action=send_embed_chat_message",
    data: formData,
    dataType: "json",
    cache: false,
    contentType: false,
    processData: false,
    success: function (response) {
      $(".typing-dec").remove();
      // Show the button image after receiving the response
      $btn.find("img").show();
      $btn.removeClass("button-progress").prop("disabled", false);
      let randomId = Math.floor(Math.random() * 900000) + 100000;
      $("#aiteamup_embed_message_input").val("");

      if (response.success) {
        $(".aiteamup_embed_chat_pan").append(
          `<div class="aiteamup_embed_chat_content_board dynamic-element">
            <img class="aiteamup_embed_chat_profile_avatar" src="${botImage}" ; width="100%" ; height="100%" ;>
            <div class="aiteamup_embed_chat_content" style="background-color: ${uaiBoardColor} !important">
              <a id="aiteamup_embed_letter-${randomId}" class="aiteamup_embed_letter" style="color: ${uaiFontLetterColor} !important"></a>
            </div>
          </div>`
        );
        $(".aiteamup_embed_chat_pan").animate(
          {
            scrollTop: $(".aiteamup_embed_chat_pan").prop("scrollHeight"),
          },
          1
        );

        $a = $(`#aiteamup_embed_letter-${randomId}`);

        async function simulateTyping(msg, i) {
          if (i < msg.length) {
            const char = msg.charAt(i);

            // Replace newline characters with HTML line break tags
            if (char === "\n") {
              $a.append("<br>");
            } else {
              $a.append(char);
            }

            i++;
            setTimeout(function () {
              simulateTyping(msg, i);
            }, 30); // Adjust the typing speed (milliseconds per character)
          }
        }

        simulateTyping(response.ai_message, 0);
      } else {
        Snackbar.show({
          text: response.error,
          pos: "top-right",
          showAction: false,
          actionText: "Dismiss",
          duration: 4000,
          textColor: "#ea5252",
          backgroundColor: "#ffe6e6",
        });
      }
    },
  });
}

$(".aiteamup_embed_message_send_btn").on("click", sendEmbedChatMessage);

$("#aiteamup_embed_message_input").on("keypress", function (e) {
  if (e.key === "Enter") {
    sendEmbedChatMessage();
  }
});

$(".pre_made_message_item").on("click", function (e) {
  let $preMadeMessageItem = $(this);

  let question = $preMadeMessageItem.data("question");

  $("#aiteamup_embed_message_input").val(question);

  $(".aiteamup_embed_chat_pan").css("height", "calc(100% - 190px)");
  $(".aiteamup_embed_chat_pan_embed").css("height", "calc(100% - 190px)");

  $(".pre_made_message_pan").css("display", "none");
  sendEmbedChatMessage();
});

$(".new_chat_btn").on("click", function (e) {
  var $btn = $(this);

  let embed_id = $("#embed_chat_icon").data("embed-id");
  let formData = new FormData();

  let ajaxurl = "https://aiteamup.com/php/awwhehgrjy.php";

  formData.append("embed_id", embed_id);

  $btn.addClass("button-progress").prop("disabled", true);

  $.ajax({
    type: "POST",
    url: ajaxurl + "?action=refresh_embed_chat",
    data: formData,
    dataType: "json",
    cache: false,
    contentType: false,
    processData: false,
    success: function (response) {
      $btn.removeClass("button-progress").prop("disabled", false);

      if (!response.success) {
        Snackbar.show({
          text: response.error,
          pos: "top-right",
          showAction: false,
          actionText: "Dismiss",
          duration: 4000,
          textColor: "#ea5252",
          backgroundColor: "#ffe6e6",
        });
      } else {
        $(".aiteamup_embed_chat_pan .dynamic-element").remove();
        $(".aiteamup_embed_chat_pan").css("height", "calc(100% - 411px)");
        $(".aiteamup_embed_chat_pan_embed").css("height", "calc(100% - 411px)");
        $(".pre_made_message_pan").css("display", "block");
      }
    },
  });
});

$(".embed_pre_start_chat_btn").on("click", function (e) {
  e.preventDefault();
  e.stopPropagation();

  let embed_term_value = $("#embed_chat_terms").prop("checked");

  if (!embed_term_value) {
    Snackbar.show({
      text: "You should check the terms of condition to proceed.",
      pos: "top-right",
      showAction: false,
      actionText: "Dismiss",
      duration: 4000,
      textColor: "#ea5252",
      backgroundColor: "#ffe6e6",
    });
    return;
  }
  var $btn = $(this);

  let email = $("#aiteamup_embed_pre_email_input").val();
  let embed_id = $("#embed_chat_icon").data("embed-id");
  let standAlone = $("#embed_chat_icon").data("stand_alone");

  $btn.addClass("button-progress").prop("disabled", true);

  var formData = new FormData();
  formData.append("email", email);
  formData.append("embed_id", embed_id);

  //   // local
  //   let ajaxurl = "http://localhost/aiteamup/php/awwhehgrjy.php";

  // live
  let ajaxurl = "https://aiteamup.com/php/awwhehgrjy.php";

  $.ajax({
    type: "POST",
    url: ajaxurl + "?action=autoresponder_collect",
    data: formData,
    dataType: "json",
    cache: false,
    contentType: false,
    processData: false,
    success: function (response) {
      $btn.removeClass("button-progress").prop("disabled", false);
      if (!response.success) {
        Snackbar.show({
          text: response.error,
          pos: "top-right",
          showAction: false,
          actionText: "Dismiss",
          duration: 4000,
          textColor: "#ea5252",
          backgroundColor: "#ffe6e6",
        });
      } else {
        // let file = $("#embed_pre_image")[0].files[0];

        // if (file) {
        //     objectUrl = URL.createObjectURL(file);
        // }

        // $("#aiteamup_embed_chat_user_avatar").attr("src", objectUrl);

        embedStatus = "setup";

        if (standAlone) {
          $("#embed_chat_pre_board")[0].style.display = "none";
          $("#embed_chat_board")[0].style.display = "block";
        } else {
          $("#embed_chat_pre_board").addClass("d-none");
          $("#embed_chat_board").removeClass("d-none");
        }
      }
    },
  });
});
