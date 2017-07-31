<main>
    <div class="content">

        <div class="input-section">
            <!-- Class Section Dropdown -->
            <?php echo $sections; ?>
            <!-- Button Goes to "Add Student" page-->
            <a href="<?php echo base_url(); ?>gradebook/add_student" class="blue-btn">
                <span class="ion ion-plus"></span>
                <span>Add Student(s)</span>
            </a>
            <!-- TODO: Button Goes to "Add Assignment" page-->
            <a href="<?php echo base_url(); ?>gradebook/add_assignment" class="blue-btn">
                <span class="ion ion-plus"></span>
                <span>Add Assignment(s)</span>
            </a>
        </div>
        <div class="input-section">
            <div class="search-bar">
                <span class="ion ion-android-search"></span>
                <input type="search" name="search_student" placeholder="Search Students">
            </div>
        </div>

        <!-- Grades -->
        <h2 id="selected_section"><?php echo $selected; ?></h2>
        <div class="table-group" style="margin-top: 1rem;">
            <?php echo $grade_table; ?>
        </div>

    </div><!-- End of content -->
</main>
