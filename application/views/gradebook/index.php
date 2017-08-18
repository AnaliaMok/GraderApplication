<main>
    <div class="content">

        <div class="input-section">
            <!-- Button Goes to "Add Student" page-->
            <a href="<?php echo base_url(); ?>gradebook/add_student" class="blue-btn">
                <span class="ion ion-plus"></span>
                <span>Add Student(s)</span>
            </a>
            <!-- Button Goes to "Add Assignment" page-->
            <a href="<?php echo base_url(); ?>gradebook/add_assignment" class="blue-btn">
                <span class="ion ion-plus"></span>
                <span>Add Assignment(s)</span>
            </a>
            <!-- TODO: Button Goes to "Edit Assignment" page-->
            <a href="<?php echo base_url(); ?>gradebook/edit_assignment" class="blue-btn">
                <span class="ion ion-edit"></span>
                <span>Edit Assignment</span>
            </a>
        </div>
        <div class="input-section">
            <!-- Class Section Dropdown -->
            <?php echo $sections; ?>

            <!-- Custom Search Bar -->
            <div class="search-bar">
                <span class="ion ion-android-search"></span>
                <input type="search" name="search_student" placeholder="Search Students">
            </div>
        </div>

        <!-- Grades -->
        <h2 id="selected_section"><?php echo $selected; ?></h2>
        <div id="errors"><?php echo validation_errors(); ?></div>
        <div class="table-group" id="grade-table" style="margin-top: 1rem; margin-bottom: 2rem;">
            <?php echo $grade_table; ?>
        </div>

        <!-- TODO: Add flash data styling -->
        <?php if($this->session->flashdata('assignment_created')): ?>
        <?php echo '<div class="alert-message modal" id="success">'.
            '<span class="lnr lnr-cross" onclick="disappear(this);"></span>'.
            $this->session->flashdata('assignment_created').'</div>'; ?>
        <?php endif; ?>

        <div class="modal" id="student-modal">
            <div class="modal-content">
                <h2>
                    <span id="title"></span>
                    <span class="lnr lnr-cross" onclick="disappearModal('student-modal');"></span>
                </h2>
                <div class="form-holder">
                    <?php echo form_open('gradebook/edit_student'); ?>
                        <div class="student-info-group">
                            <div>
                                <label>First Name</label>
                                <input type="text" name="first_name" id="first_name" />
                            </div>

                            <div>
                                <label>Last Name</label>
                                <input type="text" name="last_name" id="last_name" />
                            </div>

                            <div>
                                <label>Section ID</label>
                                <?php echo $modal_sections; ?>
                            </div>
                        </div>

                        <!-- Holder of current student's id -->
                        <input type="hidden" name="student_id" id="student_id">
                        <input type="submit" class="blue-btn" value="Submit">

                    <?php echo form_close(); ?>

                </div><!-- End of form holder -->
            </div>
        </div><!-- End of student modal -->

        <div class="modal" id="grade-modal">
            <div class="modal-content">
                <h2>
                    <span id="title"></span>
                    <span class="lnr lnr-cross" onclick="disappearModal('grade-modal');"></span>
                </h2>
                <div class="form-holder">
                    <?php echo form_open('gradebook/add_grade'); ?>
                        <div class="form-column">
                            <!-- Total Score Input -->
                            <h3>Grade:</h3>
                            <span>
                                <input type="text" id="score">&nbsp;/&nbsp;
                                <span id="total"></span>
                            </span>

                            <!-- Grading Breakdown -->
                            <h3>Breakdown:</h3>
                            <ul id="breakdown"></ul>
                        </div>
                        <div class="form-column">
                            <!-- TODO -->
                            <h3>Comments</h3>
                            <textarea name="comments" id="comments" rows="8" cols="80"></textarea>
                        </div>

                        <input type="submit" class="blue-btn" value="Submit">

                    <?php echo form_close(); ?>
                </div>
            </div>
        </div><!-- End of grade-modal -->

    </div><!-- End of content -->
</main>
