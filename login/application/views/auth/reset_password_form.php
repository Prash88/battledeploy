<?php $this->load->view('includes/header'); ?>

<?php
$new_password = array(
	'name'	=> 'new_password',
	'id'	=> 'new_password',
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
$confirm_new_password = array(
	'name'	=> 'confirm_new_password',
	'id'	=> 'confirm_new_password',
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size' 	=> 30,
);
$submit = array(
	'name'	=> 'submit',
	'id'	=> 'submit',
	'class'	=> 'btn btn-info',
);
$changepassword = array(
	'name'	=> 'changepassword',
	'id'	=> 'changepassword',
	'class'	=> 'btn btn-info',
);
?>
<?php echo form_open($this->uri->uri_string()); ?>
<div class="container-fluid">  
<h2>Enter your email: </h2>
<div class = "well">
		
	<div class="control-group">
	<?php echo form_label('New Password', $new_password['id']); ?>
	<div class="controls">
			<div class="input-prepend">
			<span class="add-on"><i class="icon-lock"></i></span>
				<?php echo form_password($new_password); ?>
			</div>
			<div style="color: red;"><?php echo form_error($new_password['name']); ?><?php echo isset($errors[$new_password['name']])?$errors[$new_password['name']]:''; ?></div>
	</div>
	</div>

		
	<div class="control-group">
	<?php echo form_label('Confirm New Password', $confirm_new_password['id']); ?>
	<div class="controls">
			<div class="input-prepend">
			<span class="add-on"><i class="icon-lock"></i></span>
				<?php echo form_password($confirm_new_password); ?>
			</div>
			<div style="color: red;"><?php echo form_error($confirm_new_password['name']); ?><?php echo isset($errors[$confirm_new_password['name']])?$errors[$confirm_new_password['name']]:''; ?></div>
	</div>
	</div>
	
</div>
</div>

<div class="control-group">
	<?php echo form_submit($changepassword, 'Change Password'); ?>
	<?php echo form_close(); ?>
</div>

<?php $this->load->view('includes/footer'); ?>
