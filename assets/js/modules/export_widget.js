/**
 * File Name: export_widget.js
 * Description: Module that handles all methods and events that occur around the
 * export table.
 *
 * @author: Analia Mok
 */

var ExportWidget = (function(){

    // Download queue holder (not actually a Queue)
    var queue;

    // Common HTML Elements
    var gradeCheckBoxes,
        commentCheckBoxes,
        addToQueueBtns;

    // Main Constructor/Initializer for Widget
    var init = function(){

        // Initialize Fields
        queue = [];

        // Assign Events to Common widgets
        gradeCheckBoxes = document.getElementsByName("grade");
        commentCheckBoxes = document.getElementsByName("comments");
        addToQueueBtns = document.getElementsByName("add");

        for(var i = 0, length = gradeCheckBoxes.length; i < length; i++){
            gradeCheckBoxes[i].onclick = function(){
                if(this.checked){
                    storeOption();
                }else{
                    removeOption();
                }
            };
        }

        Object.keys(commentCheckBoxes).forEach(function(ccb){
            ccb.onclick = function(){
                if(this.checked){
                    storeOption();
                }else{
                    removeOption();
                }
            };
        });

        Object.keys(addToQueueBtns).forEach(function(atqb){
            atqb.onclick = function(){
                // TODO
            };
        });


    };

    // Method triggered by a checkbox. Adds an option to the json string stored
    // inside the corresponding addToQueue btn's value attr.
    var storeOption = function(){
        // TODO
        console.log("store");
    };


    // Method triggered by a checkbox. Removes an option to the json string stored
    // inside the corresponding addToQueue btn's value attr.
    var removeOption = function(){
        // TODO
        console.log("remove");
    };


    var addToQueue = function(){
        // TODO
        // NOTE: Maybe store a json string inside corresponding addToQueue btn
        // and use that to keep track of checkboxes
    };


    var removeFromQueue = function(itemIdx){
        // TODO
    };

    var downloadQueue = function(){
        // TODO: Sends AJAX request to create text files and or spreadsheets
    };

    // NOTE: Will make hooks/connections to APIs and prepares requests to
    // write files


    // Mapping Methods to method names
    return {
        init: init,
        storeOption: storeOption,
        removeOption: removeOption,
        addToQueue: addToQueue,
        removeFromQueue: removeFromQueue,
        downloadQueue: downloadQueue
    };

})();
