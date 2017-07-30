<main>
    <div class="content">
        <a href="<?php echo base_url(); ?>gradebook" class="blue-btn">&lt;&lt; Back to gradebook</a>
        <h2>New Student(s)</h2>
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

        <?php if($this->session->flashdata('students_added')): ?>
            <?php echo '<div class="alert-message modal">'.
                $this->session->flashdata('students_added').'</div>'; ?>
        <?php endif; ?>

        <?php if($this->session->flashdata('student_exists')): ?>
            <?php echo '<div class="alert-message modal">'.
                $this->session->flashdata('student_exists').'</div>'; ?>
        <?php endif; ?>

    </div><!-- End of content -->
</main>
