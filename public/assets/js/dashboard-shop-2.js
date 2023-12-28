/*
Template Name: Admin Mintone
Author: SRGIT
File: js
*/

// ============================================================== 
// r the chart
// ============================================================== 

$(function () {
"use strict";
	Morris.Area.prototype.fillForSeries = function(i) {
      var color;
	  return "0-#e2ecff:10-#6D9EFF:80";
};
	
$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
  var target = $(e.target).attr("href") // activated tab
  switch (target) {
    case "#last-year":
      homeBar.redraw();
      $(window).trigger('resize');
      break;
    case "#last-month":
      profileBar.redraw();
      $(window).trigger('resize');
      break;
	    case "#last-week":
      profileBar.redraw();
      $(window).trigger('resize');
      break;
  }
});
	
// ============================================================== 
// r the popup message while page load
// ============================================================== 
$('[data-plugin="knob"]').knob();
});


// ============================================================== 
// r scrollbar
// ============================================================== 




jQuery('#world-map-markers').vectorMap(
{
    map: 'world_mill_en',
    backgroundColor: 'transparent',
    borderColor: '#818181',
    borderOpacity: 0.25,
    borderWidth: 1,
    zoomOnScroll: false,
    color: '#d7e6ff',
    regionStyle : {
        initial : {
          fill : '#d7e6ff'
        }
      },
    markerStyle: {
      initial: {
                    r: 5,
                    'fill': '#4886ff',
                    'fill-opacity':1,
                    'stroke': '#000',
                    'stroke-width' : 2,
                    'stroke-opacity': 0
                },
                },
    enableZoom: true,
    hoverColor: '#d7e6ff',
    markers : [{
        latLng : [37.0902, 95.7129],
        name : 'USA', 
      }
	 
	  
	  ],
	  
    hoverOpacity: null,
    normalizeFunction: 'linear',
    scaleColors: ['#b6d6ff', '#005ace'],
    selectedColor: '#c9dfaf',
    selectedRegions: [],
    showTooltip: true,
    onRegionClick: function(element, code, region)
    {
        var message = 'You clicked "'
            + region
            + '" which has the code: '
            + code.toUpperCase();

        alert(message);
    }
});
 

        
  