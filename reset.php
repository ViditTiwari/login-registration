<?php require('includes/config.php'); 

//if logged in redirect to members page
if( $user->is_logged_in() ){ header('Location: memberpage.php'); } 

//if form has been submitted process it
if(isset($_POST['submit'])){

	//email validation
	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
	    $error[] = 'Please enter a valid email address';
	} else {
		$stmt = $db->prepare('SELECT email FROM members WHERE email = :email');
		$stmt->execute(array(':email' => $_POST['email']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(empty($row['email'])){
			$error[] = 'Email provided is not on recognised.';
		}
			
	}

	//if no errors have been created carry on
	if(!isset($error)){

		//create the activasion code
		$token = md5(uniqid(rand(),true));

		try {

			$stmt = $db->prepare("UPDATE members SET resetToken = :token, resetComplete='No' WHERE email = :email");
			$stmt->execute(array(
				':email' => $row['email'],
				':token' => $token
			));

			//send email
			$to = $row['email'];
			$subject = "Password Reset";
			$body = "Someone requested that the password be reset. \n\nIf this was a mistake, just ignore this email and nothing will happen.\n\nTo reset your password, visit the following address: ".DIR."resetPassword.php?key=$token";
			$additionalheaders = "From: <".SITEEMAIL.">\r\n";
			$additionalheaders .= "Reply-To: $".SITEEMAIL."";
			mail($to, $subject, $body, $additionalheaders);

			//redirect to index page
			header('Location: login.php?action=reset');
			exit;

		//else catch the exception and show the error.
		} catch(PDOException $e) {
		    $error[] = $e->getMessage();
		}

	}

}

//define page title
$title = 'Reset Paasword';

//include header template
require('layout/header.php'); 
?>

<div class="container">
            
            <header>
                <h1>Login and Registration</h1>
				
            </header>
            <section>				
                <div id="container_demo" >
                   
                    
                    <div id="wrapper">
                        <div id="login" class="animate form">
                            <form action="" method="post" autocomplete="on"> 

                                <h1>Reset Password</h1> 

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
										}
									}
									?>
                                
                                 <p> 
                                    <label for="emailsignup" class="youmail" data-icon="e" > Your email</label>
                                    <input id="email" name="email" required="required" type="email" placeholder="mysupermail@mail.com" value="<?php if(isset($error)){ echo $_POST['email']; } ?>"/> 
                                </p>

                                <p class="login button"> 
                                    <input type="submit" name="submit" value="Send Reset Link" /> 
								</p>

                                <p class="change_link">
									Go back to Login Page ?
									<a href="login.php" class="to_register">Login</a>
                                    
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
