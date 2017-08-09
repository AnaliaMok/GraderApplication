/**
 * File Name:       assignment.js
 * Description:     Methods specific to the new_assignment page
 * Created On:      8/7/2017
 * Last Modfied:    8/7/2017
 *
 * @author: Analia Mok
 */


/**
 * createListItem - Helper Method to create a list item to insert into a
 *      list of error messages.
 * @param  {String} text - Errors Message
 * @return {Node} HTML List Item
 */
 function createListItem(text){
    var textNode = document.createTextNode(text),
        listItem = document.createElement("li");

    listItem.appendChild(textNode);

    return listItem;
 } // End of createListItem


// Category Methods

 /**
  * createCategory - Helper method to create a new category block
  *
  * @param {int} index - 0-based index of used for class, and names
  * @param  {String} [type="main"] - Denotes what kind of category to make.
  *                                By default, create a main category block
  * @param {int} [subIdx=0] - Denotes the sub-category index to apply. By default,
  *                         sub-index is 0.
  * @return HTMLNode - Category Structure
  */
 function createCategory(index, type, subIdx){

     // Assigning default values
     if(type === undefined){
         type = "main";
     }

     if(subIdx === undefined){
         subIdx = 0;
     }

     // Create New Category Block
     var divContainer = document.createElement("div");
     // class="category_totalCategories main"
     divContainer.className = "category_"+index+" ";
     divContainer.className += (type === "main") ? type : "sub";

     var label = document.createElement("label"),
         name = (type === "main") ? "Category " : "Sub-Category ";

     if(type === "main"){
         // Use given index as the Category name
         label.appendChild(document.createTextNode(name + (index+1)));
     }else{
         // Use subIDx for Sub-Category name
         label.appendChild(document.createTextNode(name + (subIdx+1)));
     }

     // <div class="item">
     var divInputContainer = document.createElement("div");
     divInputContainer.className = "item";

     // Category Name Input
     var nameInput = document.createElement("input");
     nameInput.setAttribute("type", "text");

     var nameInputName = "category_name_"+index;

     if(type === "sub"){ nameInputName = "sub_" + nameInputName; }

     nameInput.setAttribute("name", nameInputName);
     divInputContainer.appendChild(nameInput);

     // <div class="item">
     var divInputContainerTwo = document.createElement("div");
     divInputContainerTwo.className = "item";

     // Total Points Input
     var ptsInput = document.createElement("input");
     ptsInput.setAttribute("type", "text");

     var ptsInputName = "category_pts_"+index;

     if(type === "sub"){ ptsInputName = "sub_" + ptsInputName; }

     ptsInput.setAttribute("name", ptsInputName);
     divInputContainerTwo.appendChild(ptsInput);

     // Remove Button
     var removeBtn = document.createElement("button");
     removeBtn.appendChild(document.createTextNode("Remove"));
     removeBtn.setAttribute("type", "button");
     removeBtn.setAttribute("name", "remove");
     removeBtn.className="blue-btn";

     removeBtn.onclick = removeCategory;

     removeBtn = (document.createElement("label")).appendChild(removeBtn);

     // Appending All element to entire category
     divContainer.appendChild(label);
     divContainer.appendChild(divInputContainer);
     divContainer.appendChild(divInputContainerTwo);
     divContainer.appendChild(removeBtn);

     return divContainer;
 } // End of createCategory


 /**
  * removeCategory - Removes an entire category group or just a single
  *      sub-category row. After DOM element is removed, all category groups and
  *      their respective subcategories are re-numbered to make validation easier
  */
 function removeCategory(){
     // Delete remove button's parent div from DOM completely
     // If parent div is a main div, remove it and it's sub-categories
     var parentNode = this.parentNode,
         classList = parentNode.className.split(" "),
         categoryNum = classList[0].substr(classList[0].lastIndexOf("_")+1);

     if(classList.indexOf("main") != -1){
         var catGroup = document.getElementsByClassName("category_" + categoryNum);

         for(var i = 0, length = catGroup.length; i < length; i++){
             catGroup[0].remove();
         }

         // Remove new sub category button
         $("#new_sub_category_"+categoryNum).remove();

         // Decrement Total Categories
         var totalCategories = parseInt($("#total_categories").val());
         $("#total_categories").attr("value", --totalCategories);

         // Renumber categories
         renumberCategories();
     }else{
         // else, remove just sub-category block
         parentNode.remove();

         // Decrement counter for sub-category total
         var totalSubCategories = parseInt($("#total_sub_cat_"+categoryNum).val());
         $("#total_sub_cat_"+categoryNum).attr("value", totalSubCategories-1);

         // Re-number all sub-category elements in current category group
         renumberSubCategories(categoryNum);
     }

 } // End of removeCategory


 /**
  * renumberCategories - Scans all categories and its subgroups to renumber
  *  the groups' names and ids.
  *
  * Pre-condition: Total Categories should be decremented already
  *
  * @return true on success; false otherwise
  */
 function renumberCategories(){

     var totalCategories = parseInt($("#total_categories").val()),
         categoryNum = 0;

     for(var i = 0; i < totalCategories; i++){
         // Renumber each group based on current value of i
         var currCategory = $(".category_"+categoryNum);

         while(currCategory.length === 0){
             categoryNum++;
             currCategory = $(".category_"+categoryNum);
         }

         // Loop through each category & sub-category group
         for(var j = 0, length = currCategory.length; j < length; j++){
             var currGroup = currCategory[j],
                 children = currGroup.children;
             // NOTE: Structure of Category Groups are strictly the same

             if((currGroup.className).indexOf("main") != -1){
                 // If currGroup is the main category group

                 currGroup.className = "category_" + i + " main";

                 // Label
                 children[0].innerHTML = "Category " + (i + 1);
                 // Name & Points Input
                 (children[1].getElementsByTagName("input")[0]).setAttribute("name", "category_name_"+i);
                 (children[2].getElementsByTagName("input")[0]).setAttribute("name", "category_pts_"+i);

             }else if((currGroup.className).indexOf("sub") != -1){
                 currGroup.className = "category_" + i + " sub";

                 // Label
                 children[0].innerHTML = "Sub-Category " + j;
                 (children[1].getElementsByTagName("input")[0]).setAttribute("name", "sub_category_name_"+(j-1));
                 (children[2].getElementsByTagName("input")[0]).setAttribute("name", "sub_category_pts_"+(j-1));
             }

         } // End of inner loop

         // Re-name new_sub_category button
         var new_sub_btn = $("#new_sub_category_" + categoryNum);
         if(new_sub_btn != null){
             new_sub_btn.attr("id", "new_sub_category_" + i);
         }

         categoryNum++;
     } // End of outer loop

 } // End of renumberCategories


 /**
  * renumberSubCategories - Helper Method to renumber the names of subcategory
  *      groups based on a given category number. Used when only a subcategory
  *      is removed.
  *
  * Pre-condition: total subcategory count is already decremented
  *
  * @param  {int} categoryNum - Category Number to target
  */
 function renumberSubCategories(categoryNum){

     var totalSubCategories = parseInt($("#total_sub_cat_"+categoryNum).val()),
         currCategory = $(".category_"+categoryNum);

     // Remove first object: 1st obj is the main category
     currCategory.splice(0, 1);

     for(var i = 0; i < totalSubCategories; i++){

         var curr = currCategory[i],
             children = curr.children;

         // Label
         children[0].innerHTML = "Sub-Category " + (i+1);

         // NOTE: Not touching inputs. Sub-Categories for each category will be
         // read in chunks of two to grab both name & points

     }

 } // End of renumberSubCategories


 /**
  * addCategory - Creates a new set of category inputs.
  *      - Increments total categories count
  */
 function addCategory(){

     // Current Category Count
     var totalCategories = parseInt($("#total_categories").val());

     // New category block
     var divContainer = createCategory(totalCategories);

     // Increment Total Categories
     $("#total_categories").attr("value", ++totalCategories);

     // Grabbing & Copying Sub Category Button
     var subCatBtn = $("#new_sub_category_0").clone();
     subCatBtn.attr("id", "new_sub_category_"+(totalCategories-1));
     subCatBtn.children()[1].onclick = addSubCategory;

     // Hidden input counter for this category group
     var subCount = subCatBtn.children()[2];
     subCount.setAttribute("name", "total_sub_cat_"+(totalCategories-1));
     subCount.setAttribute("id", "total_sub_cat_"+(totalCategories-1));
     subCount.setAttribute("value", "0");

     // Add category block & sub-category button before new category button
     $("#new_category").before(divContainer);
     $("#new_category").before(subCatBtn);

     // Assign onclick event to div.item
     //$(".sub-category").click(addSubCategory);

     return false;
 } // End of addCategory


 /**
  * addSubCategory - Adds a new sub-category block before the pressed button
  */
 function addSubCategory(){
     // Add sub-category block before button
     var parentId = this.parentNode.id;
     // category group index
     var index = parseInt(parentId.substr(parentId.lastIndexOf("_")+1));

     // Total Sub Categories
     var totalSubCategories = parseInt($("#total_sub_cat_" + index).val());

     // New Category Block
     var divContainer = createCategory(index, "sub", totalSubCategories);

     // Place category block before new sub button
     $("#new_sub_category_"+index).before(divContainer);

     // Increment Total Sub-categories count
     $("#total_sub_cat_" + index).attr("value", (totalSubCategories+1).toString());

     return false;
 } // End of addSubCategory


/**
 * sendAssignment - Prepares new assignment data before sending to
 *      server side.
 */
function sendAssignment(){

    // 1. Prepare json literal of grading breakdown
    // 2. Grab Errors Holder
    // 3. Define Error Message variables
    var breakdown = {},
        totalCategories = parseInt($("#total_categories").val()),
        errorsHolder = $("#errors-holder"),
        errorsList = document.createElement("ul"),
        errMsg = "",
        errItem = {};

    // Empty errors holder
    errorsHolder.html("");

    for(var i = 0; i < totalCategories; i++){
        var currCategory = $(".category_"+i),
            totalSubCategories = parseInt($("#total_sub_cat_"+i).val());

        // First Child of All Category groups is the main category
        var mainCategory = currCategory[0],
            children = mainCategory.children;

        var mainName = children[1].getElementsByTagName("input")[0].value;
        var mainPts = children[2].getElementsByTagName("input")[0].value;

        if(mainName === "" || mainPts === ""){
            // Display error message
            errMsg = "ERROR: Category " + (i+1) + " Has An Empty Input";
            errItem = createListItem(errMsg);
            errorsList.appendChild(errItem);
            errorsHolder.append(errorsList);

            console.error(errMsg);

            return false;
        }

        if(isNaN(mainPts)){
            // Display error message
            errMsg = "ERROR: Category " + (i+1) + " Points Not A Number";
            errItem = createListItem(errMsg);
            errorsList.appendChild(errItem);
            errorsHolder.append(errorsList);

            console.error(errMsg);
            return false;
        }

        // Set mainName to empty object
        breakdown[mainName] = {};

        var totalSubPoints = 0;
        for(var j = 1; j <= totalSubCategories; j++){
            var currSubCat = currCategory[j],
                subChildren = currSubCat.children;

            var subName = subChildren[1].getElementsByTagName("input")[0].value,
                subPts = subChildren[2].getElementsByTagName("input")[0].value;

            // Just skip this sub category if left completely empty
            if(subName === "" && subPts === ""){ continue; }

            if(subName === "" || subPts === ""){
                errMsg = "ERROR: Category " + (i+1) + " Subcategory " + j + " left unfinished";
                errItem = createListItem(errMsg);
                console.error(errMsg);

                errorsList.appendChild(errItem);
                errorsHolder.append(errorsList);

                return false;
            }

            if(isNaN(subPts)){
                errMsg = "ERROR: Category " + (i+1) + " Subcategory " + j + " Points Not a Number";
                errItem = createListItem(errMsg);

                console.error(errMsg);

                errorsList.appendChild(errItem);
                errorsHolder.append(errorsList);

                return false;
            }

            // Convert valid subPts value to a number
            subPts = parseInt(subPts);
            totalSubPoints += subPts;

            // Add new subName entry
            breakdown[mainName][subName] = subPts;

        }

        // Check if mainPts === Sum of Sub category points
        if(totalSubCategories != 0 && mainPts != totalSubPoints){

            errMsg = "ERROR: Category " + (i+1) + ": Main Points does not equal total sub-category points";
            errItem = createListItem(errMsg);

            console.error(errMsg);

            errorsList.appendChild(errItem);
            errorsHolder.append(errorsList);

            return false;
        }

    } // End of outer loop

    // Converting breakdown object to a JSON string before assigning to hidden
    // input
    breakdown = JSON.stringify(breakdown);
    $("#category").attr("value", breakdown);

    // Set value of section dropdown to all selections
    SectionDropdownNDisplay.assignValue();

    return true;

} // End of sendAssignment
