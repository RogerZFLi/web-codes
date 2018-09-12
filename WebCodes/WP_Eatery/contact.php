<!DOCTYPE html>
<html>
    <?php include 'header.php'; ?>
	<?php include 'PasswordHash.php'; ?>
            <div id="content" class="clearfix">
                <aside>
                        <h2>Mailing Address</h2>
                        <h3>1385 Woodroffe Ave<br>
                            Ottawa, ON K4C1A4</h3>
                        <h2>Phone Number</h2>
                        <h3>(613)727-4723</h3>
                        <h2>Fax Number</h2>
                        <h3>(613)555-1212</h3>
                        <h2>Email Address</h2>
                        <h3>info@wpeatery.com</h3>
                </aside>
                <div class="main">
                    <h1>Sign up for our newsletter</h1>
                    <p>Please fill out the following form to be kept up to date with news, specials, and promotions from the WP eatery!</p>
                    <form name="frmNewsletter" id="frmNewsletter" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
                        <table>
                            <tr>
                                <td>Customer Name:</td>
                                <td><input type="text" name="customerName" id="customerName" size='40'></td>
                            </tr>
                            <tr>
                                <td>Phone Number:</td>
                                <td><input type="text" name="phoneNumber" id="phoneNumber" size='40'></td>
                            </tr>
                            <tr>
                                <td>Email Address:</td>
                                <td><input type="text" name="emailAddress" id="emailAddress" size='40'>
                            </tr>
                            <tr>
                                <td>How did you hear<br> about us?</td>
                                <td>Newspaper<input type="radio" name="referrer" id="referralNewspaper" value="newspaper">
                                    Radio<input type="radio" name='referrer' id='referralRadio' value='radio'>
                                    TV<input type='radio' name='referrer' id='referralTV' value='TV'>
                                    Other<input type='radio' name='referrer' id='referralOther' value='other'>
                            </tr>
							<tr>
								<td></td><td><input type="file" name="uploadedFile" ></td>
							</tr>
                            <tr>
                                <td></td><td><input type='submit' name='btnSubmit' id='btnSubmit' value='Sign up!'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='reset' name="btnReset" id="btnReset" value="Reset Form"></td>
                            </tr>
                        </table>
                    </form>
<?php
	/*Following code was partially based on Mr. Perron's sample code.*/
		$customerName=@$_POST['customerName'];
		$phoneNumber=@$_POST['phoneNumber'];
		$emailAddress=@$_POST['emailAddress'];
		$referrer=@$_POST['referrer'];
	
		if (isset($_POST["btnSubmit"])&&$_POST["btnSubmit"] == "Sign up!"){

			if (!empty(@$_FILES["uploadedFile"])){
				$target_dir = "files/";
				$target_file = $target_dir . basename($_FILES["uploadedFile"]["name"]);
				
				if(move_uploaded_file($_FILES["uploadedFile"]["tmp_name"], $target_file)) {
					$fileUploaded = "The file ".  basename($_FILES["uploadedFile"]["name"]). 
					" has been uploaded";
					echo "<p style="."text-align:center".">$fileUploaded</p>";
				} 
			}
			if(empty($_POST["uploadedFile"])){
					$mysql_host = "127.0.0.1";
					$mysql_user = "wp_eatery";
					$mysql_password = "password";
					$mysql_database = "wp_eatery";
					$mysql_commandetbl = "mailingList";
				
					
					
					//Instantiate database mysqli object (connect to database)
						$dbobj = new mysqli($mysql_host,$mysql_user,$mysql_password,$mysql_database); 
					//Check for connect errors
						if ($dbobj->connect_errno) { 
							die("<p style="."text-align:center".">Error connecting to database  ". $dbobj->connect_errno."  ". $dbobj->connect_error."</p>");
						}
						
						if (empty($customerName)) {echo "<p style="."text-align:center".">Customer name field missing or empty!</p>";}
						if (empty($phoneNumber)) {echo "<p style="."text-align:center".">Phone number field missing or empty!</p>";}
						if (empty($emailAddress)) {echo "<p style="."text-align:center".">Email field missing or empty!</p>";}

						if (isset($_POST['customerName'])  && !empty($customerName) && !empty($phoneNumber) && !empty($emailAddress))

						{
							if (!preg_match("/([0-9]{3})[-]([0-9]{3})[-]([0-9]{4})$/",$phoneNumber)){die ("<p style =". "text-align:center".">Invalid phone number.</p>");}
							if (!preg_match("/([a-zA-Z0-9._-]+)[@]([a-zA-Z0-9-]+)[.]([a-zA-Z.]{2,5})$/",$emailAddress)){die ("<p style =". "text-align:center".">Invalid email address.</p>");}
							//Build SQL query
							$sqlqry = "SELECT * FROM `$mysql_commandetbl` WHERE emailAddress = ? ";
						//Prepare statement and instantiate mysqli_stmt object
							$dbstmt = $dbobj->prepare($sqlqry);
							if (!$dbstmt) { die("<p style="."text-align:center".">Error during preparation phase</p>");}
						//Bind params to statement
							$res = $dbstmt->bind_param('s',$emailAddress);
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
							if ($dbresult->num_rows == 0)  { 
								//Build SQL query
								$sqlqry = "INSERT INTO $mysql_database.$mysql_commandetbl(customerName,phoneNumber,emailAddress,referrer) VALUES (?,?,?,?)";
							//Prepare statement and instantiate mysqli_stmt object
								$dbstmt = $dbobj->prepare($sqlqry);
								if (!$dbstmt) { die("Error during preparation phase");}
							//Bind params to statement
								$emailHash = password_hash($emailAddress,PASSWORD_DEFAULT);
								$res = $dbstmt->bind_param('ssss',$customerName,$phoneNumber,$emailHash, $referrer);
								if (!$res) { die("Error during bind_param phase");}
								
							//Execute statement
								$res = $dbstmt->execute();
							//Check execution status
								if ( $dbstmt->errno || !$res ) { 
									die("Error inserting new row  ".$dbstmt->errno." ".$dbstmt->error);
								}
							
								echo "<p style =". "text-align:center".">Customer $emailAddress successfully inserted</p>";
							}else
							{
								echo "<p style =". "text-align:center".">The Email Address already exists in the mailing list!</p>";		
							}
						}
					
			}	
		}
			
	?>
                </div><!-- End Main -->
            </div><!-- End Content -->
    <?php include 'footer.php'; ?>
		<h3>Database Action Results</h3>

    </body>
</html>
