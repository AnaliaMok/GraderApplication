/**
 * File Name: export_widget.js
 * Description: Module that handles all methods and events that occur around the
 * export table.
 *
 * @author: Analia Mok
 */

var ExportWidget = (function(){

    // Download queue holder (not actually a Queue)
    var downloadQueue;

    // Common HTML Elements
    var gradeCheckBoxes,
        commentCheckBoxes,
        addToQueueBtns;

    // Main Constructor/Initializer for Widget
    var init = function(){

        // Initialize Fields
        downloadQueue = [];

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

    }; // End of init


    /**
     * storeOption - Method triggered by a checkbox. Adds an option to the json
     * string stored inside the corresponding addToQueue btn's value attr.
     * @param  {Node} item - Clicked Checkbox
     * @return null
     */
    var storeOption = function(item){

        var parentUl = item.parentNode.parentNode,
            addBtnLi = parentUl.children[parentUl.children.length - 1],
            addBtn = addBtnLi.children[0],
            currValue = JSON.parse(addBtn.value);

        currValue.push(item.id);
        addBtn.value = JSON.stringify(currValue);

    };


    /**
     * removeOption - Method triggered by a checkbox. Removes an option to the
     * json string stored inside the corresponding addToQueue btn's value attr.
     * @param  {Node} item - Clicked checkbox
     * @return null
     */
    var removeOption = function(item){
        var parentUl = item.parentNode.parentNode,
            addBtnLi = parentUl.children[parentUl.children.length - 1],
            addBtn = addBtnLi.children[0],
            currValue = JSON.parse(addBtn.value),
            idx = currValue.indexOf(item.id);

        currValue.splice(idx, 1);
        addBtn.value = JSON.stringify(currValue);
    };


    /**
     * addToQueue - Creates new download items in the visible download queue and
     * updates the downloadQueue
     * NOTE: Context of 'this' is the addToQueue button
     * @return null
     */
    var addToQueue = function(){
        var options = JSON.parse(this.value),
            assignment = this.getAttribute("data-assignment"),
            section = this.getAttribute("data-section");

        if(options.length > 0){
            console.log("Assignment: " + assignment + " Section: " + section);

            // Foreach option, create a new item and add a new object to the
            // queue
            for(var i = 0, length = options.length; i < length; i++){

            }

        }else{
            // Warning: Can't add anything
            console.log("No options selected");
        }

    };


    /**
     * removeFromQueue - Removes item from visible queue and the internal
     * download queue
     * @param  {int} itemIdx - Index of removable item from download queue
     * @return null
     */
    var removeFromQueue = function(itemIdx){
        // TODO
    };


    /**
     * downloadQueue - Prepares request for creating text files & spreadsheets
     * @return {[type]} [description]
     */
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
