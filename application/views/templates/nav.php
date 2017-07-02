<!-- Global Navigation Bar -->
<nav>
    <div id="inner-container">
        <!-- TODO: Current User's Profile Image or Initials -->
        <div id="profile-img"><span>AM</span></div>

        <ul>
            <!-- TODO: Add Appropriate Icons -->
            <li>
                <a href="<?php echo base_url(); ?>dashboard" <?php echo ($active === "dashboard") ? "id=active" : ""; ?>>
                    <span class="ion ion-ios-keypad-outline"></span>
                </a>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>">
                    <span class="ion ion-ios-bookmarks-outline"></span>
                </a>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>calendar" <?php echo ($active === "calendar") ? "id=active" : ""; ?>>
                    <span class="lnr lnr-calendar-full"></span>
                </a>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>">
                    <span class="lnr lnr-printer"></span>
                </a>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>">
                    <span class="lnr lnr-clock"></span>
                </a>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>">
                    <span class="lnr lnr-database"></span>
                </a>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>">
                    <span class="ion ion-ios-gear-outline"></span>
                </a>
            </li>
        </ul>
        <!-- TODO: Add link -->
        <a href="#" id="logout-btn" class="lnr lnr-exit"></a>
    </div>
</nav>
