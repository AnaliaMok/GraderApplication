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
                    storeOption(this);
                }else{
                    removeOption(this);
                }
            };
        }

        for(i = 0, length = commentCheckBoxes.length; i < length; i++){
            commentCheckBoxes[i].onclick = function(){
                if(this.checked){
                    storeOption(this);
                }else{
                    removeOption(this);
                }
            };
        }

        for(i = 0, length = addToQueueBtns.length; i < length; i++){
            addToQueueBtns[i].onclick = addToQueue;
        }

    };

    // Method triggered by a checkbox. Adds an option to the json string stored
    // inside the corresponding addToQueue btn's value attr.
    var storeOption = function(item){

        var parentUl = item.parentNode.parentNode,
            addBtnLi = parentUl.children[parentUl.children.length - 1],
            addBtn = addBtnLi.children[0],
            currValue = JSON.parse(addBtn.value);

        currValue.push(item.id);
        addBtn.value = JSON.stringify(currValue);

        console.log("store: " + addBtn.value);
    };


    // Method triggered by a checkbox. Removes an option to the json string stored
    // inside the corresponding addToQueue btn's value attr.
    var removeOption = function(item){
        var parentUl = item.parentNode.parentNode,
            addBtnLi = parentUl.children[parentUl.children.length - 1],
            addBtn = addBtnLi.children[0],
            currValue = JSON.parse(addBtn.value),
            idx = currValue.indexOf(item.id);

        currValue.splice(idx, 1);
        addBtn.value = JSON.stringify(currValue);

        console.log("remove: " + addBtn.value);
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
