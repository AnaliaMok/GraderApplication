<main>
    <div class="content">
        <div class="input-section">
            <?php echo $sections; ?>
            <!-- Button Goes to "Add Student" page-->
            <a href="<?php echo base_url(); ?>gradebook/add_student" class="blue-btn">
                <span class="ion ion-plus"></span>
                <span>Add Student(s)</span>
            </a>
        </div>
        <div class="input-section">
            <input type="search" name="search_student" placeholder="Search Student Names">
        </div>

        <div class="table-group">
            <?php echo $grade_table; ?>
        </div>

    </div>
</main>
