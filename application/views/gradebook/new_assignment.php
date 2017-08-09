<main>
    <div class="content">
        <a href="<?php echo base_url(); ?>gradebook" class="blue-btn">&lt;&lt; Back to gradebook</a>
        <h2>New Assignment</h2>

        <div class="form-holder">
            <?php echo form_open("gradebook/add_assignment", array("onsubmit" => "return sendAssignment();")); ?>
                <div class="student-info-group assign-group">
                    <div>
                        <label>Name</label>
                        <input type="text" name="assign_name" />
                        <label>&nbsp;</label>
                    </div>
                    <div>
                        <label>Due Date</label>
                        <input type="date" name="due_date" placeholder="mm/dd/yyyy"/>
                        <label>&nbsp;</label>
                    </div>
                    <div>
                        <label>Total Pts.</label>
                        <input type="text" name="total_points" />
                        <label>&nbsp;</label>
                    </div>
                    <div>
                        <label>Section(s)</label>
                        <div class="item"><?php echo $sections; ?></div>
                        <label><button type="button" name="add" class="blue-btn" id="section_add">Add</button></label>
                    </div>
                    <div>
                        <div class="item">&nbsp;</div>
                        <!-- Where to display selected class sections -->
                        <ul id="selected_sections"></ul>
                        <div class="item">&nbsp;</div>
                    </div>

                    <!-- Break down table -->
                    <div id="table-header">
                        <label>Breakdown</label>
                        <label>Name</label>
                        <label>Point Value</label>
                    </div>
                    <!--
                        Class Assignment:
                        - category_#: # Being the category index
                            - #: 0-based index. Used to group categories with
                            their sub-categories
                        - main: Assigned to main categories
                        - sub: Assigned to sub categories
                    -->
                    <div class="category_0 main">
                        <label>Category 1</label>
                        <div class="item">
                            <input type="text" name="category_name_0" />
                        </div>
                        <div class="item">
                            <input type="text" name="category_pts_0" />
                        </div>
                    </div>

                    <!-- Add Sub Category Button -->
                    <div id="new_sub_category_0">
                        <label>&nbsp;</label>
                        <!-- New Sub-Category Button -->
                        <div class="item sub-category">
                            <span class="ion ion-plus-circled"></span>
                            <span>Add Sub-Category</span>
                        </div>
                        <input type="hidden" name="total_sub_cat_0"
                            id="total_sub_cat_0" value="0">
                    </div>
                    <div id="new_category">
                        <!-- New Category Button -->
                        <div class="item category">
                            <span class="ion ion-plus-circled"></span>
                            <span>Add Category</span>
                        </div>
                    </div>
                </div><!-- End of assign-group -->

                <!-- Where to display errors -->
                <div id="errors-holder"></div>
                <div id="errors"><?php echo validation_errors(); ?></div>

                <div class="button-group">
                    <input type="reset" value="Clear" class="blue-btn"/>
                    <input type="submit" value="Submit" class="blue-btn" />
                </div>

                <!-- Hidden Holder for Formatted Category String -->
                <input type="hidden" name="category" id="category"/>

                <!-- Inner Category Counter -->
                <input type="hidden" name="total_categories"
                    id="total_categories" value="1">
            <?php echo form_close(); ?>
        </div> <!-- End of form-holder -->
    </div><!-- End of content -->
</main>
