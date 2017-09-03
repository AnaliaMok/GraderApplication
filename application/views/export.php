<main>
    <div class="content">
        <!-- Inputs -->
        <div class="input-section">
            <?php echo $sections; ?>
            <?php echo $assignments; ?>
        </div>
        <div class="input-section">
            <!-- Custom Search Bar -->
            <div class="search-bar">
                <span class="ion ion-android-search"></span>
                <input type="search" name="search_assignment" placeholder="Search Assignments">
            </div>
        </div>

        <!-- Table Content -->
        <h2>Export</h2>
        <div class="table-group">
            <?php echo $table; ?>
        </div>

        <div class="upcoming-container">
            <h2>Download Queue</h2>
            <!-- Holder for Download Items -->
            <div id="queue">
                <!-- TODO: Remove -->
                <div class="items">
                    <div class="remove"><span class="ion ion-close-round"></span></div>
                    <div class="info">
                        <span><strong>Assignment:</strong> HW 1</span>
                        <span><strong>Section:</strong> CSCI-141-01</span>
                    </div>
                    <div class="icon-holder">
                        <img
                            src="<?php echo base_url();?>assets/icons/ms-excel-icon-gray.svg"
                            alt="excel icon">
                    </div>
                </div><!-- End of first item -->

                <div class="items">
                    <div class="remove"><span class="ion ion-close-round"></span></div>
                    <div class="info">
                        <span><strong>Assignment:</strong> HW 1</span>
                        <span><strong>Section:</strong> CSCI-141-01</span>
                    </div>
                    <div class="icon-holder">
                        <span class="ion ion-android-list"></span>
                    </div>
                </div><!-- End of second item -->

            </div>
            <button type="button" name="download" class="blue-btn">Download</button>
        </div>
    </div><!-- End of content -->
</main>
