<!-- Global Navigation Bar -->
<nav>
    <div id="inner-container">
        <!-- TODO: Current User's Initials -->
        <div id="profile-img"><span><?php echo $this->session->userdata('abbr_username'); ?></span></div>

        <ul>
            <!-- TODO: Add Appropriate Icons -->
            <li>
                <a href="<?php echo base_url(); ?>dashboard" <?php echo ($active === "dashboard") ? "id=active" : ""; ?>>
                    <span class="ion ion-ios-keypad-outline"></span>
                </a>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>gradebook" <?php echo ($active === "gradebook") ? "id=active" : ""; ?>>
                    <span class="ion ion-ios-bookmarks-outline"></span>
                </a>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>calendar" <?php echo ($active === "calendar") ? "id=active" : ""; ?>>
                    <span class="lnr lnr-calendar-full"></span>
                </a>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>export" <?php echo ($active === "export") ? "id=active" : ""; ?>>
                    <span class="lnr lnr-printer"></span>
                </a>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>timestamps" <?php echo ($active === "timestamps") ? "id=active" : ""; ?>>
                    <span class="lnr lnr-clock"></span>
                </a>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>backup" <?php echo ($active === "backup") ? "id=active" : ""; ?>>
                    <span class="lnr lnr-database"></span>
                </a>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>settings" <?php echo ($active === "settings") ? "id=active" : ""; ?>>
                    <span class="ion ion-ios-gear-outline"></span>
                </a>
            </li>
        </ul>
        <!-- TODO: Add link -->
        <a href="<?php echo base_url(); ?>logout" id="logout-btn" class="lnr lnr-exit"></a>
    </div>
</nav>
