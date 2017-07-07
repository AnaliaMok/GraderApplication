<main id="login-main">
    <div class="form-holder">
        <h2>LOGIN</h2>

        <!-- Validation Error Area -->
        <div id="errors">
            <?php echo validation_errors(); ?>
            <!-- Alert Message Set In Session Flash Data -->
            <?php if($this->session->flashdata('login_failed')): ?>
                <?php echo '<div class="alert-message"><p>'.
                    $this->session->flashdata('login_failed').'</p></div>'; ?>
            <?php endif; ?>
        </div>
        
        <div id="successes">
            <?php if($this->session->flashdata('user_registered')): ?>
                <?php echo '<p>'.
                    $this->session->flashdata('user_registered').'</p>'; ?>
            <?php endif; ?>

            <?php if($this->session->flashdata('user_loggedout')): ?>
                <?php echo '<p>'.
                    $this->session->flashdata('user_loggedout').'</p>'; ?>
            <?php endif; ?>
        </div>

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

                <input type="submit" name="submit" value="SIGN IN"/>
            </div>

        <?php echo form_close(); ?>
    </div>
</main>
