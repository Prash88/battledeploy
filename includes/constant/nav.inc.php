<?php
/*Nav.inc.php*/
?>
	<div>
		<ul class="nav nav-tabs">

			<?php if(!isset($_COOKIE['user_id']))
			{
			?>
			<li <?php if(basename($_SERVER['SCRIPT_NAME']) == 'index.php') { ?>class="current"<?php } ?>><a href="<?php echo SITE_BASE; ?>/index.php">About</a></li>
			<li <?php if(basename($_SERVER['SCRIPT_NAME']) == 'register.php') { ?>class="current"<?php } ?>><a href="<?php echo SITE_BASE; ?>/register.php">Register</a></li>
			<li <?php if(basename($_SERVER['SCRIPT_NAME']) == 'login.php') { ?>class="current"<?php } ?>><a href="<?php echo SITE_BASE; ?>/login.php">Login</a></li>

			<?php
			}
			else
			{
			?>
			<li <?php if(basename($_SERVER['SCRIPT_NAME']) == 'index.php') { ?>class="current"<?php } ?>><a href="<?php echo SITE_BASE; ?>/users/index.php">My Profile</a></li>
			<li <?php if(basename($_SERVER['SCRIPT_NAME']) == 'profile.php') { ?>class="current"<?php } ?>><a href="<?php echo SITE_BASE; ?>/users/profile.php">Edit Profile</a></li>
			<li <?php if(basename($_SERVER['SCRIPT_NAME']) == 'grid.php') { ?>class="current"<?php } ?>><a href="<?php echo SITE_BASE; ?>/users/grid.php">Play game</a></li>

				<?php
				if(is_admin())
				{
				?>
					<li <?php if(basename($_SERVER['SCRIPT_NAME']) == 'admin.php') { ?>class="current"<?php } ?>><a href="<?php echo SITE_BASE; ?>/users/admin.php">Manage Users</a></li>
					<li <?php if(basename($_SERVER['SCRIPT_NAME']) == 'install.php') { ?>class="current"<?php } ?>><a href="<?php echo SITE_BASE; ?>/install.php">Install Tables</a></li>
					<li <?php if(basename($_SERVER['SCRIPT_NAME']) == 'install_security.php') { ?>class="current"<?php } ?>><a href="<?php echo SITE_BASE; ?>/install_security.php">Install Security Tables</a></li>

				<?php
				}
				?>
			<li><a href="<?php echo SITE_BASE; ?>/logout.php">Logout</a></li>
			<?php
			}
			?>
		</ul>
	</div>
