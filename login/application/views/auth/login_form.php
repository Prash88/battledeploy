<?php $this->load->view('includes/header'); ?>

<?php
$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
if ($login_by_username AND $login_by_email) {
	$login_label = 'Email';
} else if ($login_by_username) {
	$login_label = 'Login';
} else {
	$login_label = 'Email';
}
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 30,
);
$remember = array(
	'name'	=> 'remember',
	'id'	=> 'remember',
	'value'	=> 1,
	'checked'	=> set_value('remember'),
	'style' => 'margin:0;padding:0',
);
$captcha = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'maxlength'	=> 8,
);
$submit = array(
	'name'	=> 'submit',
	'id'	=> 'submit',
	'class'	=> 'btn btn-info',
);

?>
<?php echo form_open($this->uri->uri_string()); ?>

<div class="container-fluid">  
<h2>Enter your credentials to login: </h2>
<div class = "well">
	
		<div class="control-group">
	        <?php echo form_label($login_label, $login['id']); ?>
			<div class="controls">
			    <div class="input-prepend">
				<span class="add-on"><i class="icon-envelope"></i></span>
					<?php echo form_input($login); ?>
				</div>
			</div>
			<div style="color:red;"><?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></div>
		</div>
		
		<div class="control-group">
			<?php echo form_label('Password', $password['id']); ?>
			<div class="controls">
			    <div class="input-prepend">
				<span class="add-on"><i class="icon-lock"></i></span>
					<?php echo form_password($password); ?>
				</div>
			</div>
			<div style="color:red;"><?php echo form_error($password['name']); ?><?php echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?></div>
		</div>
		
		<div class="control-group">
	        <?php echo form_label('Remember me', $remember['id']); ?>
			<?php echo form_checkbox($remember); ?>
		</div>
		
</div>
</div>

		<div class="control-group">
			<?php echo form_submit($submit, 'Sign In'); ?>
			<?php echo form_close(); ?>
		</div>
		
		<div class="control-group">
			<div class="controls">
				<?php echo anchor('/auth/forgot_password/', 'Forgot Password');?> |<?php if ($this->config->item('allow_registration', 'tank_auth')) echo anchor('/auth/register/', 'Sign Up'); ?>
			</div>
		</div>		

<?php $this->load->view('includes/footer'); ?>
