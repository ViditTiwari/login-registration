<?php
//include config
require_once('includes/config.php');

//check if already logged in move to home page
if( $user->is_logged_in() ){ header('Location: index.php'); } 

//process login form if submitted
if(isset($_POST['submit'])){

	$username = $_POST['username'];
	$password = $_POST['password'];
	
	if($user->login($username,$password)){ 
		$_SESSION['username'] = $username;
		header('Location: memberpage.php');
		exit;
	
	} else {
		$error[] = 'Wrong username or password or your account has not been activated.';
	}

}//end if submit

//define page title
$title = 'Login';

//include header template
require('layout/header.php'); 
?>

	
<div class="container">
            
            <header>
                <h1>Login and Registration Form</h1>
				
            </header>
            <section>				
                <div id="container_demo" >
                   
                    
                    <div id="wrapper">
                        <div id="login" class="animate form">
                            <form action="" method="post" autocomplete="on"> 

                                <h1>Log in</h1> 

				                 <?php


								//check for any errors
								if(isset($error)){
									foreach($error as $error){
										echo '<p class="bg-danger">'.$error.'</p>';
									}
								}

								if(isset($_GET['action'])){

									//check the action
									switch ($_GET['action']) {
										case 'active':
											echo "<h2 class='bg-success'>Your account is now active you may now log in.</h2>";
											break;
										case 'reset':
											echo "<h2 class='bg-success'>Please check your inbox for a reset link.</h2>";
											break;
										case 'resetAccount':
											echo "<h2 class='bg-success'>Password changed, you may now login.</h2>";
											break;
									}

								}

								
								?>
                                <p> 
                                    <label for="username" class="uname" data-icon="u" > Your username </label>
                                    <input id="username" name="username" required="required" type="text" placeholder="myusername" value="<?php if(isset($error)){ echo $_POST['username']; } ?>"/>
                                </p>
                                <p> 
                                    <label for="password" class="youpasswd" data-icon="p"> Your password </label>
                                    <input id="password" name="password" required="required" type="password" placeholder="eg. X8df!90EO" /> 
                                </p>
                                
                                <p class="login button"> 
                                    <input type="submit" name="submit" value="Login" /> 
								</p>

								<p>
						         <a href='reset.php'>Forgot your Password?</a>
					              </p>

                                <p class="change_link">
									Not a member yet ?
									<a href="./" class="to_register">Join us</a>
                                    
								</p>

                                

                    
                    
               
                            </form>
                        </div>

                       
						
                    </div>
                </div>  
            </section>
        </div>

<?php 

require('layout/footer.php'); 
?>
