<main>
    <div class="content">
        <a href="<?php echo base_url(); ?>gradebook" class="blue-btn"><< Back to gradebook</a>
        <h2>New Student(s)</h2>
        <div class="form-holder">
            <?php echo form_open('gradebook/add_student'); ?>
                <div class="student-info-group">
                    <div>
                        <label>First Name</label>
                        <input type="text" name="first_name0" />
                    </div>

                    <div>
                        <label>Last Name</label>
                        <input type="text" name="last_name0" />
                    </div>

                    <div>
                        <label>Section ID</label>
                        <?php echo $sections; ?>
                    </div>
                </div>

                <!-- Internal Counter to keep track of total student forms -->
                <input type="hidden" name="total_forms" value="1" />

                <div class="button-group">
                    <!-- <a href="#" class="blue-btn">Add Student</a> -->
                    <input type="submit" value="Add Student" class="blue-btn"/>
                    <a href="#" class="blue-btn">Add Another</a>
                </div>

            <?php echo form_close(); ?>

        </div><!-- End of form holder -->
    </div><!-- End of content -->
</main>
