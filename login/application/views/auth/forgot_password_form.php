<?php $this->load->view('includes/header'); ?>

<?php
$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
);

$reset = array(
	'name'	=> 'reset',
	'id'	=> 'reset',
	'class'	=> 'btn btn-info',
);

if ($this->config->item('use_username', 'tank_auth')) {
	$login_label = 'Email or login';
} else {
	$login_label = 'Email';
}
?>
<?php echo form_open($this->uri->uri_string()); ?>
<div class="container">    
<h2>Enter your email: </h2>
<div class = "well">


	<div class="control-group">
		<?php echo form_label($login_label, $login['id']); ?>
		<div class="controls">
			<div class="input-prepend">
				<span class="add-on"><i class="icon-lock"></i></span>
					<?php echo form_input($login); ?>
			</div>
		</div>
		<div style="color: red;"><?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></div>
	</div>

</div>
</div>

<div class="control-group">
	<?php echo form_submit($reset, 'Get a new password'); ?>
	<?php echo form_close(); ?>
</div>
<?php $this->load->view('includes/footer'); ?>
