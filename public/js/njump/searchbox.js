// Author: Ryan Heath
// http://rpheath.com

(function($) {
  $.searchbox = {}
  
  $.extend(true, $.searchbox, {
    settings: {
      url: '/search',
      param: 'query',
      dom_id: '#results',
      delay: 100,
      loading_css: '#loading'
    },
    
    loading: function() {
      $($.searchbox.settings.loading_css).show()
    },
    
    resetTimer: function(timer) {
      if (timer) clearTimeout(timer)
    },
    
    idle: function() {
      $($.searchbox.settings.loading_css).hide()
    },
    
    process: function(terms) {
      var path = $.searchbox.settings.url.split('?'),
        query = [$.searchbox.settings.param, '=', terms].join(''),
        base = path[0], params = path[1], query_string = query
      
      if (params) query_string = [params.replace('&amp;', '&'), query].join('&')
      
      /*
      $.get([base, '?', query_string].join(''), function(data) {
        $($.searchbox.settings.dom_id).empty().html(data)
      })
      */

      if(terms == '') {

        //checkar if country ou source são vazios, se sim:
        if($("#countrySideBar").chosen().val() == '' && $("#sourcesSideBar").chosen().val() == '') {
          $($.searchbox.settings.dom_id).empty()
          $("#firstrowresults").text('- no filter applied -').css("color", "grey");
          $("#firstrowresults").css("font-weight", "lighter");
          $("#myUL2").show();
          $("#style-1").hide();
          return
        } 
      } else {
        if($("#countrySideBar").chosen().val() != '' || $("#sourcesSideBar").chosen().val() != '') {
            


        } else {
          if(terms.length < 2) {
            $($.searchbox.settings.dom_id).empty()
            $("#firstrowresults").text('- at least 2 characters -').css("color", "grey");
            $("#firstrowresults").css("font-weight", "lighter");
            $("#myUL2").show();
            $("#style-1").hide();
            return;
          }
        }

      }

      $("#style-1").hide();
      $($.searchbox.settings.dom_id).empty();
      $("#spinner").show();
      $("#myUL2").hide();

      jQuery.ajax({
          url: [base, '?', query_string].join('') + '&country=' + $("#countrySideBar").chosen().val() + '&source=' + $("#sourcesSideBar").chosen().val(),
          success: function (data) {

              if(data != '') {
                $($.searchbox.settings.dom_id).empty().html('<a class="totalRows"><div class="resultRows"><span id="totalResultsNumber"></span> results</div></a>' + data)
                $("#totalResultsNumber").html($( ".resultsSearch" ).size());
                $("#style-1").show();
                $("#style-1").height($( "#sidebar-wrapper" ).height() - 265);

                $( ".resultsSearch" ).bind( "click", function(event) {
                    firechange(this);

                    event.preventDefault()	
          					event.stopPropagation();

          					var hash = $(this).attr("hash");
          					var country = $("#countrySideBar").chosen().val();
          					var source = $("#sourcesSideBar").chosen().val();
          					var text = $("#myInput").val();

          					var allContent = $('#sidebar-wrapper').html();
          				  localStorage.setItem('allSidebar', allContent);

          				  var hash = $(this).attr("hash");
          				  window.location.replace('/njump/njumpedit?njumphash=' + hash + '&red=1&country=' + country + '&source=' + source + '&text=' + text);
                });

                $( ".resultsSearch" ).bind( "mousedown", function(event) {
                    switch (event.which) {
                        case 2:
                            firechange(this)

          							var hash = $(this).attr("hash");
          							var country = $("#countrySideBar").chosen().val();
          							var source = $("#sourcesSideBar").chosen().val();
          							var text = $("#myInput").val();

          							var allContent = $('#sidebar-wrapper').html();
          						  localStorage.setItem('allSidebar', allContent);

        						    var hash = $(this).attr("hash");
        						    $(this).attr('href', '/njump/njumpedit?njumphash=' + hash + '&red=1&country=' + country + '&source=' + source + '&text=' + text);
                                    break;
                    }
                });
				
              }
              else {
                $("#firstrowresults").text('- no results -').css("color", "red");
                $("#firstrowresults").css("font-weight", "700");
                $("#myUL2").show();
                $("#style-1").hide();
              }
          },
          async: true
      });

      $( document ).ajaxStop(function() {
        $("#spinner").hide();
      });
    },
    
    start: function() {
      $(document).trigger('before.searchbox')
      $.searchbox.loading()
    },
    
    stop: function() {
      $.searchbox.idle()
      $(document).trigger('after.searchbox')
    }
  })
  
  $.fn.searchbox = function(config) {
    var settings = $.extend(true, $.searchbox.settings, config || {})
    
    $(document).trigger('init.searchbox')
    $.searchbox.idle()
    
    return this.each(function() {
      var $input = $(this)
      
      $input
      .focus()
      .ajaxStart(function() { $.searchbox.start(); })
      .ajaxStop(function() { $.searchbox.stop() })
      .keyup(function() {
        if ($input.val() != this.previousValue) {
          $.searchbox.resetTimer(this.timer)

          this.timer = setTimeout(function() {  
            $.searchbox.process($input.val())
          }, $.searchbox.settings.delay)
        
          this.previousValue = $input.val()
        }
      })
    })
  }
})(jQuery);