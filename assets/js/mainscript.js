/*
    File Name: mainscript.js
    Description: Main JS Script to handle all events & interactions throughout
    application

    @author: Analia Mok
 */

window.onload = init;

function init(){

    if($(".table-body").length > 0){
        // if table-body exists
        // See if each table has to be modified
        modifyTables();
    }

    if($("#list") != null){
        getUpcomingList();
    }

} // End of init


/**
 * modifyTables - Setup function that looks at each table on the dashboard,
 *      and shortens a table if it is more than 3 rows long. If so, the last
 *      3 rows are hidden and replaced with a "See More" button.
 *
 * @return null
 */
function modifyTables(){

    var tables = $(".table-body");
    var tableGroups = $('.table-group');

    for(var i = 0; i < tables.length; i++){

        var currTable = tables.get(i);
        var currTableGroup = tableGroups.get(i);
        var rows = $("ul", currTable);

        // Hide Rows 3 and onward
        if(rows.length > 3){
            //rows.slice(3).css("display", "none");
            var remaining = rows.slice(3);
            remaining.toggle();

            // See More Button
            var button = document.createElement("button");
            button.appendChild(document.createTextNode("See More"));

            // Button Styling
            button.style.fontFamily = "Open Sans, sans-serif";
            button.style.margin = "0.5em";
            button.style.backgroundColor = "#EEE";
            button.style.border = "0";
            button.style.textDecoration = "underline";
            button.style.cursor = "pointer";
            button.style.fontSize = "1em";

            // Adding Toggle Event to Button
            button.onclick = function(){
                remaining.toggle();
            };

            // Button Holder
            var newRow = document.createElement("div");
            newRow.className = "table-button-holder";
            newRow.appendChild(button);

            // Row Styling
            newRow.style.textAlign = "center";

            // $table.append(newRow);
            currTableGroup.append(newRow);

        }

    }
} // End of modifyTables


/**
 * [getUpcomingList description]
 * @return {[type]} [description]
 */
function getUpcomingList(){

    // Requesting to populate upcoming list
    $.ajax({
        url: "http://localhost/GraderApplication/index.php/calendar/find_upcoming",
        type: "post",
        mimetype: "json",
        data: {},
        success: function(response){
            $("#list").html(response);
        },
        error: function(response){
            console.log("ERROR: " + response);
        }
    });

} // End of getUpcomingList


/**
 * completeAssignment - Given an assignment ID, send an AJAX request to
 *      Calendar controller to change is_completed state of assignment to true.
 * @param assignmentID - int
 * @return null
 */
function completedAssignment(assignmentID){
    $.ajax({
        url: "http://localhost/GraderApplication/index.php/calendar/complete_assignment",
        type: "post",
        mimetype: "json",
        data: { 'assignmentID': assignmentID },
        success: function(response){
            $("#list").html(response);
        },
        error: function(response){
            console.log("ERROR: " + response);
        }
    });
} // End of completedAssignment
