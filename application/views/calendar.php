<main>
    <div class="content">
        <?php echo $calendar; ?>

        <div class="upcoming-container">
            <h2>Upcoming</h2>
            <?php

                for($i = 0, $length=count($upcoming); $i < $length; $i++){
                    $curr_assignment = $upcoming[$i];
                    echo '<div class="upcoming-list-item">'."\n";
                    // TODO: Calculate time between now and due_date assign class
                    // to indicator based on difference
                    echo '<div class="info-holder">'."\n";
                    echo '<span class="date">Due&nbsp;'.nice_date($curr_assignment['due_date'], 'm.d')."</span>";
                    echo "<span>".$curr_assignment['name']."</span>\n";
                    echo "</div>\n";
                    echo '<input type="checkbox" onclick="completed('.$curr_assignment['assignment_id'].')"/>';
                    echo "</div>\n";
                }
            ?>
        </div>
    </div>

</main>
