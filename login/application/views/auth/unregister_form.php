<?php $this->load->view('includes/header'); ?>

<?php
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 30,
);

$cancel = array(
	'name'	=> 'cancel',
	'id'	=> 'cancel',
	'class'	=> 'btn btn-info',
);
?>
<?php echo form_open($this->uri->uri_string()); ?>

<div class="container-fluid">  
<h2>Enter your email: </h2>
<div class = "well">

		<div class="control-group">
	        <?php echo form_label('Password', $password['id']); ?>
			<div class="controls">
			    <div class="input-prepend">
				<span class="add-on"><i class="icon-envelope"></i></span>
					<?php echo form_password($password); ?>
				</div>
			</div>
		<div style="color: red;"><?php echo form_error($password['name']); ?><?php echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?></div>
		</div>
</div>
</div>

	<div class="control-group">
			<?php echo form_submit($cancel, 'Delete account'); ?>
			<?php echo form_close(); ?>
		</div>

<?php $this->load->view('includes/footer'); ?>
