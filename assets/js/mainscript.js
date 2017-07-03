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
}
