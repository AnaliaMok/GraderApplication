<main>
    <div class="content">
        <a href="<?php echo base_url(); ?>gradebook" class="blue-btn" style="margin-left:2rem;">&lt;&lt; Back to gradebook</a>
        <h2>New Student(s)</h2>

        <div id="errors">
            <?php echo validation_errors(); ?>
        </div>

        <div class="form-holder">
            <?php echo form_open('gradebook/add_student'); ?>
                <div class="student-info-group" id="form_0">
                    <div>
                        <label>First Name</label>
                        <input type="text" name="first_name_0" id="first_name_0" />
                    </div>

                    <div>
                        <label>Last Name</label>
                        <input type="text" name="last_name_0" id="last_name_0" />
                    </div>

                    <div>
                        <label>Section ID</label>
                        <?php echo $sections; ?>
                    </div>
                </div>

                <!-- Internal Counter to keep track of total student forms -->
                <input type="hidden" name="total_forms" id="total_forms" value="1" />

                <div class="button-group">
                    <!-- <a href="#" class="blue-btn">Add Student</a> -->
                    <input type="submit" value="Add Student" class="blue-btn" />
                    <!-- <a href="#" class="blue-btn">Add Another</a> -->
                    <input type="button" value="Create Another" class="blue-btn" id="add_another"/>
                </div>

            <?php echo form_close(); ?>

        </div><!-- End of form holder -->

        <div class="alert-space">
            <?php if($this->session->flashdata('students_added')): ?>
            <?php echo '<div class="alert-message modal" id="success">'.
                '<span class="lnr lnr-cross" onclick="disappear(this);"></span>'.
                $this->session->flashdata('students_added').'</div>'; ?>
            <?php endif; ?>

            <?php if($this->session->flashdata('student_exists')): ?>
                <?php echo '<div class="alert-message modal" id="error">'.
                    '<span class="lnr lnr-cross" onclick="disappear(this);"></span>'.
                    $this->session->flashdata('student_exists').'</div>'; ?>
            <?php endif; ?>

        </div>

    </div><!-- End of content -->
</main>
