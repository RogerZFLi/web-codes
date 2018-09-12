<!DOCTYPE html>
<?php
session_start();

 if(isset($_SESSION['AdminID']))
 {
  header("Location: mailinglist.php");
 }
?>
<html>
    <?php include 'header.php'; ?>
	<?php include 'inputValidation.php';?>
     <div id="content" class="clearfix">
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<table align ="center">
			<tr>
				<th colspan ="2">Login Form</th>
			</tr>
			<tr>
				<td style="padding:5">
				Username: </td>
				<td><input type="text" name="usr" value=""><span></span> </td>
			</tr>
			<tr>
				<td style="padding:5">
				Password: </td>
				<td><input type="password" name="pwd" value=""><span></span></td>
			</tr>
				<td style="padding:5">
				</td>
				<td align = "center" style="padding:5;text-align:center">
				<input type="submit" name="login" value="Login">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" name="reset" value="Reset"></td>
			</table>
		</form>
		
		<?php

		
		if ($_SERVER["REQUEST_METHOD"] == "POST")
		{
			// Get $username and $password 
			$username=@$_POST['usr']; 
			$password=@$_POST['pwd'];
			$clientip=$_SERVER['REMOTE_ADDR'];
			$validation = new Inputs($username,$password);
			if (@$_POST["login"] == "Login"){

				$mysql_host = "127.0.0.1";
				$mysql_user = "wp_eatery";
				$mysql_password = "password";
				$mysql_database = "wp_eatery";
				$mysql_commandetbl = "adminusers";
			
			
			//Instantiate database mysqli object (connect to database)
				$dbobj = new mysqli($mysql_host,$mysql_user,$mysql_password,$mysql_database); 
			//Check for connect errors
				if ($dbobj->connect_errno) { 
					die("<p style="."text-align:center".">Error connecting to database  ". $dbobj->connect_errno."  ". $dbobj->connect_error."</p>");
				}
				//require 'inc/lform.inc';
				if (empty($username)) {
					echo "<p style="."text-align:center".">User name is required!"."</p>";}
				if (empty($password)) {echo "<p style="."text-align:center".">Password is required!"."</p>";}
				
				if (!empty($username) && !empty($password))
				{
				//Build SQL query
					$sqlqry = "SELECT * FROM `$mysql_commandetbl` WHERE username = ? AND password = ?";
				//Prepare statement and instantiate mysqli_stmt object
					$dbstmt = $dbobj->prepare($sqlqry);
					if (!$dbstmt) { die("<p style="."text-align:center".">Error during preparation phase</p>");}
				//Bind params to statement
					$res = $dbstmt->bind_param('ss',$username, $password);
					if (!$res) { die("<p style="."text-align:center".">Error during bind_param phase</p>");}						
				//Execute statement
					$res = $dbstmt->execute();
				//Check execution status
					if($dbstmt->errno || !$res) { 
						die("<p style="."text-align:center".">Error executing SELECT query  ". $dbstmt->errno." ". $dbstmt->error."</p>");
					}

				//Instantiate mysqli_result object
					$dbresult = $dbstmt->get_result();
					if (!$dbresult) { die("<p style="."text-align:center".">Error creating result set</p>");}	
				//Output rows from result set
					if ($dbresult->num_rows != 0) { 
						$currentDate = date("Y-m-d");
						$_SESSION['username']=$username;
						$_SESSION['adr_ip']=$_SERVER['REMOTE_ADDR'];
						$_SESSION['AdminID']=1;
							$sqlqry = "SELECT LastLogin FROM `$mysql_commandetbl` WHERE username = ?";
							$dbstmt = $dbobj->prepare($sqlqry);
							$res = $dbstmt->bind_param('s',$username);				
							$res = $dbstmt->execute();
							$dbresult = $dbstmt->get_result();
							$row = $dbresult->fetch_assoc();
							$action = htmlspecialchars($_SERVER['PHP_SELF']);
						$_SESSION['Lastlogin']= $row['LastLogin'];
						header("Location:mailinglist.php");
						//Build SQL query
							$sqlqry = "UPDATE `$mysql_commandetbl` SET LastLogin = ? WHERE Username = ?";
						//Prepare statement and instantiate mysqli_stmt object
							$dbstmt = $dbobj->prepare($sqlqry);
							if (!$dbstmt) { die("<p style="."text-align:center".">Error during login date register</p>");}
						//Bind params to statement
							$res = $dbstmt->bind_param('ss',$currentDate, $username);
							if (!$res) { die("<p style="."text-align:center".">Error when login date register of during bind_param phase</p>");}						
						//Execute statement
							$res = $dbstmt->execute();
						//Check execution status
							if($dbstmt->errno || !$res) { 
								die("<p style="."text-align:center".">Error UPDATE query  ". $dbstmt->errno." ". $dbstmt->error."</p>");
							}

						//Instantiate mysqli_result object
							
					}else
					{
						echo "<p style =". "text-align:center".">User name or password is invalid! Please try again</p>";		
					}
				}
			}
		} 
		?>
 
        </div><!-- End Content -->
    <?php include 'footer.php'; ?>

	
    </body>
</html> 