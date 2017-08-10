<main>
    <div class="content">

        <div class="input-section">
            <!-- Button Goes to "Add Student" page-->
            <a href="<?php echo base_url(); ?>gradebook/add_student" class="blue-btn">
                <span class="ion ion-plus"></span>
                <span>Add Student(s)</span>
            </a>
            <!-- TODO: Button Goes to "Edit Student" page-->
            <a href="<?php echo base_url(); ?>gradebook/edit_student" class="blue-btn">
                <span class="ion ion-edit"></span>
                <span>Edit Student</span>
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
        <div class="table-group" style="margin-top: 1rem; margin-bottom: 2rem;">
            <?php echo $grade_table; ?>
        </div>

        <!-- TODO: Add flash data styling -->
        <?php if($this->session->flashdata('assignment_created')): ?>
        <?php echo '<div class="alert-message modal" id="success">'.
            '<span class="lnr lnr-cross" onclick="disappear(this);"></span>'.
            $this->session->flashdata('assignment_created').'</div>'; ?>
        <?php endif; ?>

    </div><!-- End of content -->
</main>
