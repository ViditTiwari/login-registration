<?php require('includes/config.php'); 

//if logged in redirect to members page
if( $user->is_logged_in() ){ header('Location: memberpage.php'); } 

$stmt = $db->prepare('SELECT resetToken, resetComplete FROM members WHERE resetToken = :token');
$stmt->execute(array(':token' => $_GET['key']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

//if no token from db then kill the page
if(empty($row['resetToken'])){
	$stop = 'Invalid token provided, please use the link provided in the reset email.';
} elseif($row['resetComplete'] == 'Yes') {
	$stop = 'Your password has already been changed!';
}

//if form has been submitted process it
if(isset($_POST['submit'])){

	//basic validation
	if(strlen($_POST['password']) < 3){
		$error[] = 'Password is too short.';
	}

	if(strlen($_POST['passwordConfirm']) < 3){
		$error[] = 'Confirm password is too short.';
	}

	if($_POST['password'] != $_POST['passwordConfirm']){
		$error[] = 'Passwords do not match.';
	}

	//if no errors have been created carry on
	if(!isset($error)){

		//hash the password
		$hashedpassword = $user->password_hash($_POST['password'], PASSWORD_BCRYPT);

		try {

			$stmt = $db->prepare("UPDATE members SET password = :hashedpassword, resetComplete = 'Yes'  WHERE resetToken = :token");
			$stmt->execute(array(
				':hashedpassword' => $hashedpassword,
				':token' => $row['resetToken']
			));

			//redirect to index page
			header('Location: login.php?action=resetAccount');
			exit;

		//else catch the exception and show the error.
		} catch(PDOException $e) {
		    $error[] = $e->getMessage();
		}

	}

}

//define page title
$title = 'Reset Account';

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
                    
                        <div id="login" >
                            <?php if(isset($stop)){

							    		echo "<p class='bg-danger'>$stop</p>";

							    	} else { ?>
                                    


										<form role="form" method="post" action="" autocomplete="off">
											<h2>Change Password</h2>
											<hr>

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
                                    <label for="passwordsignup" class="youpasswd" data-icon="p">Your password </label>
                                    <input id="password" name="password" required="required" type="password" placeholder="eg. X8df!90EO"/>
                                </p>
                                <p> 
                                    <label for="passwordsignup_confirm" class="youpasswd" data-icon="p">Please confirm your password </label>
                                    <input id="passwordConfirm" name="passwordConfirm" required="required" type="password" placeholder="eg. X8df!90EO"/>
                                </p>
                                <p class="signin button"> 
									<input type="submit" name = "submit" value="Change"/> 
								</p>
                                
                            </form>
                            <?php } ?>
                        </div>
						
                    </div>
                </div>  
            </section>
        </div>

<?php 

require('layout/footer.php'); 
?>

