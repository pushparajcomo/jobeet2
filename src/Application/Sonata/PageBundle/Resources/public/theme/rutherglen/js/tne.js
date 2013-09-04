var TNE = TNE || {};

TNE.core = (function () {

    // Private variables
    var 
    barLinks = $('#panel0').find('ul.inline a'),
    mobileLinks = $('#panel0').find('a.mobileHeader'),
    allMobileLinks = $('#panel0').find('.mobileHeader, .toggleMenu'),
    headerDrop = $('#headerDrop'),
    ww = window.innerWidth || document.documentElement.clientWidth,
    that = this,
    navPrevious = $('body'),
    firstNav = true,
    popTriggers = $('body.planner').find('a.infoTrigger'),
    sideBar = $('#sideBar'),
    slidrSideBar = $('#sideBarRight'),
    searchArticles  = $('#panelPlanner0').find('article'),

    // Initialise on page load
    init = function () {

      nav();
      responseInit();
      form();
      printTrigger();
      
      // not implementing yet
      //popOver();

      sideBarInit();

      if ($('form.nl-form').length >0) {
        nlForm();
          $('#nl-form').find('select').eq(2).val(moment())
        parseDates();
      }

      if ($('.carousel').length > 0)
        $('.carousel').carousel('pause'); // stop any carousels from playing

      if ($('.btn-search').length >0) {
        $('.btn-search').on('click', function(e){
          var thisI = $(this).find('i');
          thisI.addClass('spin');
          setTimeout(function(){thisI.removeClass('spin')},500);
        })
      }

      if (searchArticles.length >0)
        searchTrigger();

    },

    nav = function() {

      adjustMenu();

      $(window).bind('resize orientationchange', function() {
        ww = window.innerWidth || document.documentElement.clientWidth;
        if (!$('#navContentsearch .nl-form input[type=search]').is(":focus"))  // ios triggering close on :focus
          adjustMenu();
      });

    },

    form = function() {
      $('#cancelSearch').on('click', function() {
        $('.on').click();
        barLinks.removeClass('on active');
        allMobileLinks.removeClass('on active');
      });
    },
    
    desktopNav = function(links) {

      searchWidth();

      if (ww <= 1041) {
        // mob nav https://github.com/tessa-lt/dropdowns
        $(".nav li a").each(function() {
          if ($(this).next().length > 0) {
            $(this).addClass("parent");
          };
        });
        $(".toggleMenu").unbind('click').bind('click', function(e) {
          e.preventDefault();
          $('#whiteBgTop').css('height', 'auto');
          $('.mobileHeader').removeClass('active');
          $("#headerDrop").hide();
          $(this).toggleClass("active");
          $("#mobileNav").toggle();
          $("#mobileNav li.hover").removeClass('hover');
        });
        // mob header eg. Search
        $(".mobileHeader").unbind('click').bind('click', function(e) {
          e.preventDefault();
          $('#whiteBgTop').css('height', 'auto');
          $('.toggleMenu').removeClass('active');
          $("#mobileNav").hide();
          $("#mobileNav li.hover").removeClass('hover');
          $(this).toggleClass("active");
          $("#headerDrop").toggle();
        });
      }

      links.on('click', function(){  // slide down the panel when required

        links.not(this).removeClass('on');
        
        if ($(this).hasClass('on')) { // :active state
          $(this).removeClass('on');
        } else {
          $(this).addClass('on');
          headerDrop.find('div.navContent').not(this).css('position','absolute').fadeOut();
        }

        var ind = 'navContent'+$(this).parent().index();
          
        if ( !$(this).parents('ul').hasClass('nav') ) {
          var indLen = ind.length;
          var thisID = $(this).attr('id'); 
          ind = ind.slice(0,indLen-1);
          if (thisID = "search2")
            thisID = "search";
          ind = ind + thisID;
        }

        var contentHeight = headerDrop.find('#'+ind+'').height();

        if ( ($(this).hasClass('toggleDesktop')) || ($(this).hasClass('toggleDesktopShare')) || ($(this).hasClass('mobileHeader')) ) {

          if ($(this)[0]!=navPrevious[0]) {
            // Do this if new link
            if (!firstNav) {
              headerDrop.height('auto');
              headerDrop.find('#'+ind+'').css('position','relative').fadeIn('slow');
            } else {
              firstNav = false;
              headerDrop.animate({
                height: contentHeight
              }, 200, function() {
                // Animation complete.
                headerDrop.find('#'+ind+'').css('position','relative').fadeIn('slow');
              });

            }
              
          } else {

            if(headerDrop.height() != 0) {
              headerDrop.animate({
                  height: 0
                }, 200, function() {
                  // Animation complete.
                  headerDrop.find('#'+ind+'').hide();
                });
            } else {
              headerDrop.animate({
                  height: contentHeight
                }, 200, function() {
                  // Animation complete.
                  headerDrop.find('#'+ind+'').css('position','relative').fadeIn('slow');
                });
            }

            firstNav = true;
          }
          navPrevious = $(this);
        }

      });

    },

    adjustMenu = function() {

      $('.on').click();
      //console.log(ww)
      if (ww <= 1041) {
        $('#desktopNav,#headerDrop').hide();
        $(".toggleMenu").css("display", "inline-block");
        $('#search').show();
        if (!$(".toggleMenu").hasClass("active")) {
          $("#mobileNav").hide();
          $("#mobileNav li.hover").removeClass('hover');
        } else {
          $("#mobileNav").show();
        }
        if (!$(".toggleHeader").hasClass("active")) {
          $("#headerDrop").hide();
        } else {
          $("#headerDrop").show();
        }
        $(".nav li").unbind('mouseenter mouseleave');
        $(".nav li a.parent").unbind('click').bind('click', function(e) {
          // must be attached to anchor element to prevent bubbling
          e.preventDefault();
          $(this).parent("li").toggleClass("hover");
        });
        mobileLinks.unbind('click');

        // planner change nav type
        $('#tabMenu').removeClass('tabbable').addClass('nav-collapse').addClass('collapse');
        $('#tabMenu ul').removeClass('nav-tabs');
        // move sidebar into mob sidebar
        slidrSideBar.html(sideBar.html());
        // Planner Nav
        $('#tabMenu.nav-collapse a').on('click', function(){
          $("#panelGeneral1 .btn-navbar").click();
          $('#sFormWrap').toggle();
        });

        sideBarClose();
        desktopNav(mobileLinks);

      } 
      else if (ww > 1041) {
        $('#desktopNav,#headerDrop').show();
        $(".toggleMenu, #search, .toggle").css("display", "none");
        $("#mobileNav").hide();
        barLinks.unbind('click');

        // planner change nav type
        $('#tabMenu').addClass('tabbable').removeClass('nav-collapse').removeClass('collapse');
        $('#tabMenu ul').addClass('nav-tabs');
        // close
        if ($('#sideBarRight').length > 0)
          $.sidr('close', 'sideBarRight');


        desktopNav(barLinks);

      }
    },

    responseInit = function () {
      Response.create({
          prop: "width"  // "width" "device-width" "height" "device-height" or "device-pixel-ratio"
        , prefix: "min-width- r src"  // the prefix(es) for your data attributes (aliases are optional)
        , breakpoints: [1281,1025,961,641,481,320,0] // min breakpoints (defaults for width/device-width)
        , lazy: true // optional param - data attr contents lazyload rather than whole page at once
      });
    },

    sideBarInit = function() {

      if ($('#sideBarRight').length > 0) {

        $('.right-sidebar').sidr({
          name: 'sideBarRight',
          side: 'right'
        });

        sideBarClose();

      }

    },

    sideBarClose = function() {
      $('.hideSidebar').on('click', function() {
        $.sidr('close', 'sideBarRight');
      });
    },

    popOver = function() {
      
      popTriggers.on('click', function(e) {
        
        e.preventDefault();
        var thisPop = $(this),
            thisPopContent = thisPop.parents('.imgWrap').find('.popSourceBlock');

            thisPopContent.show();

      });
    
    },

    printTrigger = function() {

      $('span.print').parent('a').on('click', function(e){
        e.preventDefault();
        window.print();
      });

    },

    nlForm = function() {
      
      var now = moment();
      var n = 1,
          checked = 'selected';

      $('#nl-form').find('select').eq(2).empty();

      while (n <= 7) {

        $('#nl-form').find('select').eq(2).append('<option '+checked+'>'+now.format("Do MMM")+'</option>');
        now.add('d', 1);
        n++;
        checked = '';

      }
      
      $('#nl-form').find('select').eq(2).append('<option value="moreDates">more dates</option>');

      $('#nl-form').find('select').eq(2).on('change', function() {

        var $this = $('#nl-form').find('select').eq(2);

        if ($this.val() != 'moreDates' ) {

          parseDates();

        } else {
          $this.val('moreDates');
          $('#nl-form').find('input[type=hidden]').val('moreDates');
        }

      });

      if ($('#headerSearch')) {
        var nlformSearch = new NLForm( document.getElementById( 'headerSearch' ) );
        searchWidth();
      }

      if ($('#nl-form'))
        var nlformAccom = new NLForm( document.getElementById( 'nl-form' ) );

    },

    searchWidth = function() {
      var columnWidth = $('#headerSearch .span9').width(),
          selectWidth = $('#headerSearch .nl-field').width(),
          inputWidth = (columnWidth - selectWidth) - 150;
          $('#navContentsearch .nl-form input[type=search]').width(inputWidth);
    },

    parseDates = function() {
      var $this = $('#nl-form').find('select').eq(2);
      var thisYear = moment().year();
      var nights = $('#nl-form').find('select').eq(3).val();
      if (nights != null) {
          nights.replace(/[^0-9]/g, '');
      // set start date
          var dateStart = "";
          if(moment($this.val(),'D MMM').isValid())
              dateStart = moment($this.val()+' '+thisYear,"D MMM YYYY");
          else
              dateStart = moment($this.val(),"D M YYYY");

      $this.val(dateStart.format("DD/MM/YYYY"));
      $this.find('option:selected').val(dateStart.format("DD/MM/YYYY"));
      // set end date
      var dateEnd = dateStart.add('d', nights);
      //console.log(dateStart.format("D[/]M[/]YYYY"))
      //console.log(dateEnd.format("D[/]M[/]YYYY"))
      $('#nl-form').find('input[type=hidden]').val(dateEnd.format("DD/MM/YYYY"));
      }
    },

    searchTrigger = function() {
      searchArticles.on('click', function(){
        var thisLink = $(this).data('href');
        searchArticles.not($(this)).toggle( "scale", function(){
          window.location.href = thisLink;  
        });
        
      });
    }
    $('#nl-form').submit(function(){
        localStorage.removeItem('ls.filterStore.values');
    });

    // Public variables, these are exposed so that selected methods can be called externally
    return {
        run: function () {
            init();
        }
    };

})(document, jQuery);

$(function () {
    TNE.core.run();
});