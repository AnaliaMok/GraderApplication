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

        // Assigning Events to download button
        document.getElementById("download").onclick = download;

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

        if(currValue.indexOf(item.id) === -1){
            // If item hasn't already been added
            currValue.push(item.id);
            addBtn.value = JSON.stringify(currValue);
        }else{
            console.log("USAGE: Option already exists");
        }

    }; // End of storeOption


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

        if(idx === -1){
            currValue.splice(idx, 1);
            addBtn.value = JSON.stringify(currValue);
        }else{
            console.error("ERROR: Could not remove option");
        }

    };


    /**
     * createItem - Creates the DOM Element that will represent a download
     *      item in the queue
     * @param  {String}  assignmentName
     * @param  {String}  section - Section ID
     * @param  {Boolean} isComments - By defeault, is true. When true, will use
     *                             the android-list icon. Will use the excel
     *                             sheet image otherwise.
     * @return {Node} the DOM element representing the list item
     */
    var createItem = function(assignmentName, section, isComments){

        // isComments has DEFAULT of 0
        if(isComments === undefined){ isComments = true; }

        var container = document.createElement("div");
        container.className = "items";

        // Remove button and cross icon
        var removeBtn = document.createElement("div"),
            cross = document.createElement("span");

        removeBtn.className = "remove";
        removeBtn.onclick = removeFromQueue;
        cross.className = "ion ion-close-round";

        // Connecting parent to children
        removeBtn.appendChild(cross);

        var infoContainer = document.createElement("div"),
            assignmentInfo = document.createElement("span"),
            sectionInfo = document.createElement("span");

        infoContainer.className = "info";
        assignmentInfo.innerHTML = "<strong>Assignment: </strong> " + assignmentName;
        sectionInfo.innerHTML = "<strong>Section: </strong> " + section;

        // Connecting parents to children
        infoContainer.appendChild(assignmentInfo);
        infoContainer.appendChild(sectionInfo);

        // Icon determination & creation
        var iconHolder = document.createElement("div");
        iconHolder.className = "icon-holder";

        if(isComments){
            var icon = document.createElement("span");
            icon.className = "ion ion-android-list";
            iconHolder.appendChild(icon);
        }else{
            var image = document.createElement('img');
            image.setAttribute("src",
                "http://localhost/GraderApplication/assets/icons/ms-excel-icon-gray.svg");
            image.setAttribute("alt", "excel icon");
            iconHolder.appendChild(image);
        }

        // Connecting components to container
        container.appendChild(removeBtn);
        container.appendChild(infoContainer);
        container.appendChild(iconHolder);

        return container;
    }; // End of createItem


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
            // Foreach option, create a new item and add a new object to the
            // queue
            var queue = document.getElementById("queue");

            for(var i = 0, length = options.length; i < length; i++){
                // Add to downloadQueue
                var newKey = {
                    "assignment":   assignment,
                    "section":      section,
                    "type":         options[i]
                };

                newKey = JSON.stringify(newKey);

                if(downloadQueue.indexOf(newKey) === -1){
                    var newItem = createItem(assignment, section, (options[i] === "comments"));
                    newItem.setAttribute("key", newKey);

                    queue.appendChild(newItem);
                    downloadQueue.push(newKey);
                }

            }

        }else{
            // Make Warning: Can't add anything
            console.log("No options selected");
        }

    };


    /**
     * removeFromQueue - Removes item from visible queue and the internal
     * download queue
     * @return null
     */
    var removeFromQueue = function(){
        var parent = this.parentNode,
            key = parent.getAttribute("key");

        // Removing key from internal queue
        downloadQueue.splice(downloadQueue.indexOf(key), 1);

        parent.remove();

    };


    /**
     * download - Prepares request for creating text files & spreadsheets
     * @return {[type]} [description]
     */
    var download = function(){

        if(downloadQueue.length > 0){
            var queue = JSON.stringify(downloadQueue);

            $.ajax({
                url: "http://localhost/GraderApplication/export/create_files",
                type: "post",
                mimetype: "json",
                data: {'download_queue' : queue},
                success: function(response){
                    console.log("SUCCESS: " + response);
                    // TODO: Refresh page?
                },
                error: function(response){
                    console.log("ERROR: " + response);
                }
            });
        }
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
        download: download
    };

})();
