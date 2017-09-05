<main>
    <div class="content">
        <div class="form-holder">
            <h2>Backup Entire Database</h2>
            <?php echo form_open('simplepages/backup', array("onsubmit" => "return checkHidden();"))?>
                <input type="hidden" id="check" name="check">
                <input type="submit" value="Backup" class="blue-btn" style="width: 30%;">
            <?php echo form_close(); ?>
        </div>
    </div>
</main>
