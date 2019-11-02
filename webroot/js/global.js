// Set Wrapper Dimensions
$("#leftSide").css("top", $("#topNav").outerHeight());

// If the document is clicked somewhere
$(document).on("mousedown", function (e) {
  // If the clicked element is not the menu
  if (!$(e.target).parents(".contextMenu").length > 0) {
    // Hide it
    $(".contextMenu").hide();
  }
});

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

$(document).ready(updateNodeStatus);
// Update node status every 15 seconds
window.setInterval(function(){
  updateNodeStatus();
}, 15000);

function updateNodeStatus() {
  var selected = $("#nodes li.selected");
  if (selected.length > 0) {
    var selIndex = selected.index();
  } else {
    var selIndex = -1;
  }

  $.ajax("js/ajax/node-list.php", {
    success: function(data) {
      $("#nodes").html(data);
      if (selIndex != -1) {
        $("#nodes li").eq(selIndex).addClass("selected");
      }
      nodeFunctions();
    },
    error: function() {
      alert("AJAX call failed!");
    }
  });
}

function nodeFunctions() {
  $("#nodes li").on("contextmenu", function (event) {
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

  $("#nodes li").on("click", function(event) {
    $(this).parent().find("*").removeClass("selected");
    $(this).addClass("selected");

    //ajax php script
    $.ajax("js/ajax/node.php?node=" + $(this).clone().children().remove().end().text(), {
      success: function(data) {
        $("#rightSide").html(data); // Update Main
        pageViewFunctions();
        nodeViewFunctions();
        $(".pageTabs a:first-child").trigger("click");
      },
      error: function() {
        alert("AJAX call failed!");
      }
    });
  });
}

function nodeViewFunctions() {
  $("iframe.serial").css("padding-top", $("#topSOL").outerHeight());
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
