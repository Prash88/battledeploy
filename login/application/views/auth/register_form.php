<?php $this->load->view('includes/header'); ?>

<?php
if ($use_username) {
	$username = array(
		'name'	=> 'username',
		'id'	=> 'username',
		'value' => set_value('username'),
		'maxlength'	=> $this->config->item('username_max_length', 'tank_auth'),
		'size'	=> 30,
	);
}
$email = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'value'	=> set_value('email'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'value' => set_value('password'),
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
$confirm_password = array(
	'name'	=> 'confirm_password',
	'id'	=> 'confirm_password',
	'value' => set_value('confirm_password'),
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
$captcha = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'maxlength'	=> 8,
);
$register = array(
	'name'	=> 'register',
	'id'	=> 'register',
	'class'	=> 'btn btn-info',
);
?>
<?php echo form_open($this->uri->uri_string()); ?>

<div class="container-fluid">  
<h2>Enter your details to sign up: </h2>
<div class = "well">

		<div class="control-group">
	        <?php echo form_label('Username', $username['id']); ?>
			<div class="controls">
			    <div class="input-prepend">
				<span class="add-on"><i class="icon-user"></i></span>
					<?php echo form_input($username); ?>
				</div>
			</div>
			<div style="color:red;"><?php echo form_error($username['name']); ?><?php echo isset($errors[$username['name']])?$errors[$username['name']]:''; ?></div>
		</div>
		
		<div class="control-group">
	        <?php echo form_label('Email Address', $email['id']); ?>
			<div class="controls">
			    <div class="input-prepend">
				<span class="add-on"><i class="icon-envelope"></i></span>
					<?php echo form_input($email); ?>
				</div>
			</div>
			<div style="color:red;"><?php echo form_error($email['name']); ?><?php echo isset($errors[$email['name']])?$errors[$email['name']]:''; ?></div>
		</div>
	
		<div class="control-group">
	        <?php echo form_label('Password', $password['id']); ?>
			<div class="controls">
			    <div class="input-prepend">
				<span class="add-on"><i class="icon-lock"></i></span>
					<?php echo form_password($password); ?>
				</div>
			</div>
			<div style="color:red;"><?php echo form_error($password['name']); ?></div>
		</div>
		
		<div class="control-group">
	        <?php echo form_label('Confirm Password', $confirm_password['id']); ?>
			<div class="controls">
			    <div class="input-prepend">
				<span class="add-on"><i class="icon-lock"></i></span>
					<?php echo form_password($confirm_password); ?>
				</div>
			</div>
			<div style="color:red;"><?php echo form_error($confirm_password['name']); ?></div>
		</div>
		
		<?php if ($captcha_registration) {
		if ($use_recaptcha) { ?>
		
		<div class="control-group">
	        <div id="recaptcha_image"></div>
			<div class="controls">
				<a href="javascript:Recaptcha.reload()">Get another CAPTCHA</a>
				<div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type('audio')">Get an audio CAPTCHA</a></div>
				<div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type('image')">Get an image CAPTCHA</a></div>
			</div>
		</div>
		
		<div class="control-group">
			<div class="recaptcha_only_if_image">Enter the words above</div>
			<div class="recaptcha_only_if_audio">Enter the numbers you hear</div>
				<div class="controls">
					<input type="text" id="recaptcha_response_field" name="recaptcha_response_field" />
					<div style="color:red;"><?php echo form_error('recaptcha_response_field'); ?></div>
					<?php echo $recaptcha_html; ?>	
				</div>
		</div>
		
		<?php } else { ?>
		
		<div class="control-group">
			<p>Enter the code exactly as it appears:</p>
			<div class="controls">
				<?php echo $captcha_html; ?>
			</div>
		</div>
		
		<div class="control-group">
			<?php echo form_label('Confirmation Code', $captcha['id']); ?>
			<div class="controls">
				<?php echo form_input($captcha); ?>
				<div style="color:red;"><?php echo form_error($captcha['name']); ?></div>
			</div>
		</div>
		
		<?php }
		} ?>

</div>
</div>

		<div class="control-group">
			<?php echo form_submit($register, 'Register'); ?>
			<?php echo form_close(); ?>
		</div>
			
<?php $this->load->view('includes/footer'); ?>
