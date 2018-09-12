<!DOCTYPE html>
<?php
session_start();

 if(!isset($_SESSION['AdminID']))
 {
  header("Location: userlogin.php");
 }
?>
<html>
    <?php include 'header.php'; ?>
            <div id="content" class="clearfix">

                    <?php

					$mysql_host = "127.0.0.1";
					$mysql_user = "wp_eatery";
					$mysql_password = "password";
					$mysql_database = "wp_eatery";
					$mysql_commandetbl = "mailingList";
					
					
					//Instantiate database mysqli object (connect to database)
						$dbobj = new mysqli($mysql_host,$mysql_user,$mysql_password,$mysql_database); 
					//Check for connect errors
						if ($dbobj->connect_errno) { 
							die("Error connecting to database  ". $dbobj->connect_errno."  ". $dbobj->connect_error);
						}
							//Build SQL query
							$sqlqry = "SELECT * FROM `$mysql_commandetbl`";
						//Prepare statement and instantiate mysqli_stmt object
							$dbstmt = $dbobj->prepare($sqlqry);
							if (!$dbstmt) { die("Error during preparation phase");}
						//Execute statement
							$res = $dbstmt->execute();
						//Check execution status
							if($dbstmt->errno || !$res) { 
								die("Error executing SELECT query  ". $dbstmt->errno." ". $dbstmt->error);
							}

						//Instantiate mysqli_result object
							$dbresult = $dbstmt->get_result();
							if (!$dbresult) { die("Error creating result set");}	
						//Output rows from result set
							
							if ($dbresult->num_rows == 0){ 
								echo "Session ID: ". session_id()."<br>";
								echo "Admin ID: ". $_SESSION['AdminID']."<br>";
								echo "Last login: ". $_SESSION['Lastlogin'];
								echo "<p style="."text-align:center".">Table contains no rows</p>";
								
							} else
							{
								echo "Session ID: ". session_id()."<br>";
								echo "Admin ID: ". $_SESSION['AdminID']."<br>";
								echo "Last login: ". $_SESSION['Lastlogin'];
								echo "<div><table border='1'>";
								echo "<tr><th>Customer Name</th><th>Phone Number</th><th>Email Address</th><th>Referrer</th></tr>";
								while ($row = $dbresult->fetch_assoc())
								{
									$action = htmlspecialchars($_SERVER['PHP_SELF']);
									echo "<tr>";
									echo "<td>".$row['customerName']."</td><td>".$row['phoneNumber']."</td><td width="."565px"."><a  href=\"$action?email=".$row['emailAddress']."&amp;optype=SEL\">".$row['emailAddress']."</a> </td><td>".$row['referrer']."</td>";
									echo "</tr>";

								}
								echo "</table></div>";

							}

			
	?>
 
            </div><!-- End Content -->
			<?php

				$sid=session_id();  //Save the session id


			?>
    <?php include 'footer.php'; ?>

	
    </body>
</html>