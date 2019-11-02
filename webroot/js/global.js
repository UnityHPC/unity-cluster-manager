// Set Wrapper Dimensions
$("#leftSide").css("top", $("#topNav").outerHeight());

// If the document is clicked somewhere, hide any context menu
$(document).on("mousedown", function (e) {
  // If the clicked element is not the menu
  if (!$(e.target).parents(".contextMenu").length > 0) {
    // Hide it
    $(".contextMenu").hide();
  }
});

//
// Generic functions specific to #leftSide on every page
//

function leftListFunctions() {
  $("#leftList li").on("contextmenu", function (event) {
    event.preventDefault();  // Hide Default context menu
    $(this).trigger("click");  // Trigger click for selection
  });

  $("#leftList li").on("click", function(event) {
    // Update css selected styles
    var curPage = $(this).parent().children(".selected");
    curPage.removeClass("selected");
    $(this).addClass("selected");

    var newPageStr = $(this).clone().children().remove().end().text();
    var rightSide = $("#rightSide");

    //ajax php script
    $.ajax($(this).parent().attr("data-ajax") + "?page=" + newPageStr, {
      success: function(data) {
        rightSide.html(data); // Update Main
        pageViewFunctions();
        $(".pageTabs a:first-child").trigger("click");
      },
      error: function() {
        alert("AJAX call failed to update page failed.");
      }
    });
  });
}

function pageViewFunctions() {
  $(".pageModule").hide();

  $(".pageTabs a").on("click", function() {
    $(".pageModule").hide();
    $(this).parent().children().removeClass("selected");

    var divText = $(this).text().replace(" ", "_");
    $("[data-module=" + divText + "].pageModule").show();
    $(this).addClass("selected");
  });

  $("#pageTop").css({
    top: $("#topNav").outerHeight(),
    left: $("#leftSide").outerWidth()
  });

  $("div.pageModule").css({
    top: $("#topNav").outerHeight() + $("#pageTop").outerHeight(),
    left: $("#leftSide").outerWidth()
  });
}

//
// Functions specific to the node page
//

$(document).ready(updateNodeStatus);
// Update node status every 15 seconds
window.setInterval(function(){
  updateNodeStatus();
}, 15000);

function updateNodeStatus() {
  $.ajax("js/ajax/node-list.php", {
    success: function(data) {
      var selected = $("#leftList li.selected");
      if (selected.length > 0) {
        var selIndex = selected.index();
      } else {
        var selIndex = -1;
      }

      $("#leftList").html(data);
      if (selIndex != -1) {
        $("#leftList li").eq(selIndex).addClass("selected");
      }

      leftListFunctions();
      nodeListFunctions();
    },
    error: function() {
      alert("AJAX call failed!");
    }
  });
}

function nodeListFunctions() {
  // Node List Context Menu
  $("#leftList li").on("contextmenu", function (event) {
    // Show contextmenu
    var nodeMenu = $("#nodeMenu");
    if ($(this).children("span").hasClass("upDot")) {
      // Node is Up
      nodeMenu.children("[data-vis=down]").hide();
      nodeMenu.children("[data-vis=up]").show();
    } else {
      // Node is Down
      nodeMenu.children("[data-vis=down]").show();
      nodeMenu.children("[data-vis=up]").hide();
    }

    var nodeText = $(this).clone().children().remove().end().text();
    nodeMenu.find(".contextHeader").html($(this).html());
    nodeMenu.attr("node", nodeText);
    nodeMenu.finish().toggle();

    // In the right position (the mouse)
    nodeMenu.css({
      top: event.pageY + "px",
      left: event.pageX + "px"
    });
  });
}

// If the menu element is clicked
$("#nodeMenu li").on("click", function() {

  var nodeName = $(this).parent().attr("node");
  // This is the triggered action name
  switch($(this).attr("data-action")) {
    // A case for each action. Your actions here
    case "on": setNodePower("on", nodeName); break;
    case "reboot": setNodePower("reboot", nodeName); break;
    case "off": setNodePower("off", nodeName); break;
    case "force-reboot": setNodePower("reboot", nodeName, true); break;
    case "force-off": setNodePower("off", nodeName, true); break;
  }

  // Hide it AFTER the action was triggered
  $("#nodeMenu").hide();
});

function setNodePower(state, node, hard=false) {
  $.ajax("js/ajax/power.php?state=" + state + "&node=" + node + "&hard=" + hard, {
    success: function(data) {
      alert(data);
    },
    error: function() {
      alert("AJAX call failed!");
    }
  });
}
