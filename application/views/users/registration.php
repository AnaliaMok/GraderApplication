<main id="login-main">
    <div class="form-holder">
        <h2>SIGN UP</h2>

        <!-- Validation Error Area -->
        <div id="errors"><?php echo validation_errors(); ?></div>

        <!-- TODO: Design & Style This page later -->
        <?php echo form_open('users/register'); ?>

            <div class="input-group">
                <input type="text" name="name" placeholder="Name" />
            </div>
            <div class="input-group">
                <input type="text" name="username" placeholder="Username" />
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Password" />
            </div>
            <div class="input-group">
                <input type="password" name="password_two" placeholder="Confirm Password" />
            </div>
            
            <div id="button-group">
                <button type="button" name="button"
                    onclick="return jumpPage('<?php echo base_url(); ?>index');">
                    BACK TO LOGIN</button>
                <input type="submit" name="submit" value="SUBMIT"></input>
            </div>

        <?php echo form_close(); ?>

        <!-- Alert Message Set In Session Flash Data -->
        <?php if($this->session->flashdata('user_registered')): ?>
            <?php echo '<div class="alert-message">'.
                $this->session->flashdata('user_registered').'</div>'; ?>
        <?php endif; ?>
    </div>
</main>
