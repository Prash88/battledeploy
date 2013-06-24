<?php $this->load->view('includes/header'); ?>

<div class="container-fluid">  

Hi, <strong><?php echo $username; ?></strong>! You are logged in now. <?php echo anchor('/auth/logout/', 'Logout'); ?>

</div>

<?php $this->load->view('includes/footer'); ?>
