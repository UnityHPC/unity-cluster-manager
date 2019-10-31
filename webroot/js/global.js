// Set Wrapper Dimensions
$("#leftSide").css("top",$("#mainNav").outerHeight());
$("#rightSide").css({
  top: $("#mainNav").outerHeight(),
  left: $("#leftSide").outerWidth()
});

// Trigger action when the contexmenu is about to be shown
$("#nodes li").bind("contextmenu", function (event) {
  // Avoid the real one
  event.preventDefault();

  $(this).trigger("click");  // Trigger click for selection

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

$("#nodes li").bind("click", function(event) {
  $(this).parent().find("*").removeClass("selected");
  $(this).addClass("selected");

  //ajax php script
  $.ajax("js/ajax/node-info.php?node=" + $(this).clone().children().remove().end().text(), {
    success: function(data) {
      $("#rightSide").html(data); // Update Main
    },
    error: function() {
      alert("AJAX call failed!");
    }
  });
});

// If the document is clicked somewhere
$(document).bind("mousedown", function (e) {
  // If the clicked element is not the menu
  if (!$(e.target).parents("#nodeMenu").length > 0) {
    // Hide it
    $("#nodeMenu").hide();
  }
});

// If the menu element is clicked
$("#nodeMenu li").click(function() {

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
      alert(node + " power state: " + state);
    },
    error: function() {
      alert("AJAX call failed!");
    }
  });
}
