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

    if($("#add_another") != null){
        // Add Another Button Event
        $("#add_another").click(addAnotherStudent);
    }

} // End of init


/**
 * jumpPage - Simple method that takes a url and directs to the page
 * represented by that url
 * @param  String location - Full url
 * @return null
 */
function jumpPage(location){
    window.location.href = location;
} // End of jumpPage


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

    var withCheckBoxes = (document.getElementById("home-upcoming") == null);

    // Requesting to populate upcoming list
    $.ajax({
        url: "http://localhost/GraderApplication/index.php/calendar/find_upcoming",
        type: "post",
        mimetype: "json",
        data: {'withCheckBoxes' : withCheckBoxes},
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
            // Replace list items to reflect change
            $("#list").html(response);

            // Look for event in calendar to remove
            $("#event"+assignmentID).css("display", "none");

        },
        error: function(response){
            console.log("ERROR: " + response);
        }
    });
} // End of completedAssignment


/**
 * addAnotherStudent - Makes an AJAX request to the Gradebook
 *      controller to create another new student form
 */
function addAnotherStudent(){
    // Total Current forms
    var totalForms = $("#total_forms").attr("value");

    $.ajax({
        url: "http://localhost/GraderApplication/index.php/gradebook/set_more_rules",
        type: "post",
        mimetype: "json",
        data: { 'total_forms': totalForms },
        success: function(response){
            // TODO: If successful, append a a set of
            // Reset and delete buttons to latest form,
            // then append new form
            var currForm = "#form_" + (totalForms-1);

            // Adding a button group to latest group
            var buttonGroup = document.createElement("div");
            buttonGroup.className = "button-group";

            // Clear Button
            var clearBtn = document.createElement("button");
            clearBtn.name = "clear_"+(totalForms-1);
            clearBtn.value = "clear_"+(totalForms-1);
            clearBtn.className = "blue-btn";
            clearBtn.appendChild(document.createTextNode("Clear"));

            // Clear values of this forms inputs
            clearBtn.onclick = function(){
                var underscoreIdx = this.name.lastIndexOf("_");
                var formNumber = this.name.substr(underscoreIdx+1);

                var firstName = document.getElementById("first_name_"+formNumber);

                if(firstName != null){
                    firstName.value = "";
                }

                var lastName = document.getElementById("last_name_"+formNumber);
                if(lastName != null){
                    lastName.value = "";
                }

                // Do not submit
                return false;
            };

            // Delete Button
            var deleteBtn = document.createElement("button");
            deleteBtn.name = "delete_"+(totalForms-1);
            deleteBtn.value = "delete_"+(totalForms-1);
            deleteBtn.className = "blue-btn";
            deleteBtn.appendChild(document.createTextNode("Delete"));

            deleteBtn.onclick = function(){
                var underscoreIdx = this.name.lastIndexOf("_");
                var formNumber = parseInt(this.name.substr(underscoreIdx+1));

                // Remove form entirely
                $("#form_"+formNumber).remove();

                // Decrement value in total_form hidden input
                var totalForms = $("#total_forms").attr("value") - 1;
                $("#total_forms").attr("value", totalForms);

                // Re-number forms
                var forms = $(".student-info-group");
                for(var i = 0; i < totalForms; i++){
                    var curr = forms[i];
                    underscoreIdx = curr.id.lastIndexOf("_");
                    var currFormNumber = curr.id.substr(underscoreIdx+1);

                    curr.id = "form_" + i;
                    var firstName = document.getElementById("first_name_"+currFormNumber);
                    firstName.id = "first_name_"+i;
                    firstName.name = "first_name_"+i;

                    var lastName = document.getElementById("last_name_"+currFormNumber);
                    lastName.id = "last_name_"+i;
                    lastName.name = "last_name_"+i;

                    var sections = document.getElementsByClassName("section-dropdown")[i];
                    sections.name = "sections_"+i;

                }

                // Do not submit
                return false;
            };

            buttonGroup.appendChild(clearBtn);
            buttonGroup.appendChild(deleteBtn);

            $(currForm).append(buttonGroup);

            // Inserting new form after the latest form
            $(currForm).after(response);

            // Increment value in total_form hidden input
            $("#total_forms").attr("value", ++totalForms);

            // Change value of "Add Student" button
            $("input[type=submit]").attr("value", "Add All Students");

            console.log("Success: New Form Added");

        },
        error: function(response){
            console.log("ERROR: " + response);
        }
    });

} // End of addAnotherStudent
