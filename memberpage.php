<?php require('includes/config.php'); 

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); } 

//define page title
$title = 'Members Page';

//include header template
require('layout/header.php'); 
?>

<div class="container">
            
            
            <section>				
                <div id="container_demo" >
                   
                    <div id="wrapper">
                    
                        <h1>Member only page - Welcome <?php echo $_SESSION['username']; ?></h1>
				<p class="center"><a href='logout.php'>Logout</a></p>
				<hr>
						
                    </div>
                </div>  
            </section>
        </div>

<?php 

require('layout/footer.php'); 
?>

