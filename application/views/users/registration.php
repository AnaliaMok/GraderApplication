<h2>Sign Up</h2>

<?php echo validation_errors(); ?>
<!-- TODO: Design & Style This page later -->
<?php echo form_open('users/register'); ?>

    <label>Name</label>
    <input type="text" name="name" placeholder="Name" /><br/>
    <label>Username</label>
    <input type="text" name="username" placeholder="Username" /><br/>
    <label>Password</label>
    <input type="password" name="password" placeholder="Password" /><br/>
    <label>Confirm Password</label>
    <input type="password" name="password_two" placeholder="Confirm Password" /><br/>
    <button type="submit" name="submit">Submit</button>
<?php echo form_close(); ?>

<!-- Alert Message Set In Session Flash Data -->
<?php if($this->session->flashdata('user_registered')): ?>
    <?php echo '<div class="alert-message">'.
        $this->session->flashdata('user_registered').'</div>'; ?>
<?php endif; ?>
