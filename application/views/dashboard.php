<main>
    <div class="content">
        <!-- Time Table -->
        <div class="table-group">
            <div class="table-heading">
                <h2>Timestamps this Week</h2>
                <span class="outer-span">Total Time Logged: <span><?php echo $total_time; ?></span></span>
            </div>
            <?php echo $time_table; ?>
        </div><!-- End of table group -->

        <!-- Unfinished Grading Table -->
        <div class="table-group">
            <div class="table-heading">
                <h2>Unfinished Grading</h2>
            </div>
            <?php echo $unfinished_table; ?>
        </div><!-- End of table group -->

        <!-- Finished Grading Table -->
        <div class="table-group">
            <div class="table-heading">
                <h2>Finished Grading</h2>
            </div>
            <?php echo $finished_table; ?>
        </div><!-- End of table group -->

        <div class="upcoming-container" id="home-upcoming">
            <?php echo $calendar; ?>

            <h2>Upcoming</h2>
            <!-- Holder for upcoming list items -->
            <div id="list"></div>
        </div><!-- End of upcoming-container -->

    </div><!-- End of content -->
</main>

<script type="text/javascript">
    // TODO: Check if this works after time table implementation
    modifyTables();
</script>
