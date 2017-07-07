<main id="login-main">
    <div class="form-holder">
        <h2>LOGIN</h2>

        <!-- Validation Error Area -->
        <div id="errors"><?php echo validation_errors(); ?></div>

        <?php echo form_open('users/index'); ?>

            <div class="input-group">
                <span></span><!-- TODO: Insert android-person icon -->
                <input type="text" name="username" placeholder="Username" />
            </div>

            <div class="input-group">
                <span></span><!-- TODO: Insert key icon -->
                <input type="password" name="password" placeholder="Password" />
            </div>

            <!-- Group that leads to either the login verfication or register page -->
            <div id="button-group">
                <button type="button" name="button"
                    onclick="return jumpPage('<?php echo base_url(); ?>register');">
                    REGISTER</button>

                <input type="submit" name="submit" value="SIGN IN"></input>
            </div>

        <?php echo form_close(); ?>
    </div>
</main>
