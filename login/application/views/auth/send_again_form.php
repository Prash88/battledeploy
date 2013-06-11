<?php $this->load->view('includes/header'); ?>

<?php
$email = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'value'	=> set_value('email'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
$send = array(
	'name'	=> 'send',
	'id'	=> 'send',
	'class'	=> 'btn btn-info',
);
?>
<?php echo form_open($this->uri->uri_string()); ?>
<div class="container-fluid">  
<h2>Enter your email: </h2>
<div class = "well">

		<div class="control-group">
	        <?php echo form_label('Email Address', $email['id']); ?>
			<div class="controls">
			    <div class="input-prepend">
				<span class="add-on"><i class="icon-envelope"></i></span>
					<?php echo form_input($email); ?>
				</div>
			</div>
		<div style="color: red;"><?php echo form_error($email['name']); ?><?php echo isset($errors[$email['name']])?$errors[$email['name']]:''; ?></div>
		</div>
</div>
</div>

	<div class="control-group">
			<?php echo form_submit($send, 'Send'); ?>
			<?php echo form_close(); ?>
		</div>
<?php $this->load->view('includes/footer'); ?>
