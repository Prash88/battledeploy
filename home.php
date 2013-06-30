<!DOCTYPE html>
<html>
	<?php 
		//Include all function calls
		require 'constants/constants.php';
		
		//Set the title for the page
		$meta_title = "Battleship Home";
		
		//Common meta data file
		return_meta($meta_title);
		secure_page();

		//Display current user information
		$user_name = $_SESSION['user_name']; 
	
		echo "<script>$(function(){";
		echo "$('<b>logged in as: $user_name</b><br  />').hide().appendTo('#success').fadeIn(1000);";
		echo "});</script>";
		
	?>
	
	<body>
		<div id="game-page-wrap">
			
			<div id="header-wrap">
				<!-- Get the header -->
				<?php getHeader(); ?>
			</div>
			
			<div id="nav-wrap">
			</div>
			
			<div id="content-wrap">
				<div id = "logged-in-users">
					<!-- To Do ... Search database for logged in users and add to <ul><li></li></ul> list -->
				<div class="dropdown clearfix">
                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                  <li><a tabindex="-1" href="#">Action</a></li>
                  <li><a tabindex="-1" href="#">Another action</a></li>
                  <li><a tabindex="-1" href="#">Something else here</a></li>
                  <li class="divider"></li>
                  <li class="dropdown-submenu">
                    <a tabindex="-1" href="#">More options</a>
                    <ul class="dropdown-menu">
                      <li><a tabindex="-1" href="#">Second level link</a></li>
                      <li><a tabindex="-1" href="#">Second level link</a></li>
                      <li><a tabindex="-1" href="#">Second level link</a></li>
                      <li><a tabindex="-1" href="#">Second level link</a></li>
                      <li><a tabindex="-1" href="#">Second level link</a></li>
                    </ul>
                  </li>
                </ul>
				</div>
              </div>
				</div>
				
				<div id = "game-board">
					<!-- Prashanth - Place game board here -->
				</div>
					
				
				<div id="error"></div>
				<div id="success"></div>
			</div>
		</div>
	</body>
	
</html>