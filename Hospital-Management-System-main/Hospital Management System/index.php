<?php
session_start();

require "includes/db_connect.inc.php";

//FIRST TIME OPENING ADMIN CREATION
$sql = "Select * from admin";

$result = mysqli_query($conn,$sql);

if(mysqli_num_rows($result)<1)
{
	date_default_timezone_set('Asia/Dhaka');
	$pass = password_hash("admin", PASSWORD_DEFAULT);
	$today = date('Y-m-d');

	$insert_Query = "INSERT INTO `admin`(`id`, `username`, `name`, `password`, `phone`, `email`, `hiredate`, `address`) VALUES ( NULL , 'admin', 'admin','".$pass."','00000000000','admin@admin.com','".$today."','admin' )";
	mysqli_query($conn,$insert_Query);
}

//------------------


/*if(isset($_SESSION['doneQuery']))
{

	$currTime = time();

	if($currTime > $_SESSION['startTime']+300)
	{
		unset($_SESSION['startTime']);
		unset($_SESSION['doneQuery']);

	}

}
*/
$fullName="";
$email="";
$query="";

$iptNameErr="";
$iptEmailErr="";
$iptQueryErr="";



if($_SERVER["REQUEST_METHOD"]=="POST")
{

	$flag =0 ;
	$fullName = mysqli_real_escape_string($conn, $_POST['fullName']);
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$query = mysqli_real_escape_string($conn, $_POST['query']);



	if(!preg_match('/^[a-z , " ",".",","]*$/i', $fullName) || $fullName=="")
	{
		$iptNameErr = "Enter a Valid Name";
		$flag =1;
	}
	else
	{
		$iptNameErr="";
	}

	if(!filter_var($email, FILTER_VALIDATE_EMAIL))
	{
		$iptEmailErr="Enter A Valid Email Address";
		$flag = 1;
	}
	else
	{
		$iptEmailErr="";
	}

	if(strlen($query)<20)
	{

		$iptQueryErr = "Your Query Seems To Be Invalid (Minimum Character Length is 20)";
		$flag = 1;
	}
	else
	{
		$iptQueryErr ="";
	}

	if($flag==0)
	{

		date_default_timezone_set('Asia/Dhaka');
		$dateTime = date('Y-m-d h:i:s');

		$sql = "INSERT INTO `query` (`id`, `Name`, `Query`, `Email`, `dateTime`,`status`) VALUES (NULL, '".$fullName."','".$query."', '".$email."', '".$dateTime."', '0' );";

		
		mysqli_query($conn, $sql);


		setcookie('doneQuery','Yes',time()+300);

		$fullName="";
		$email="";
		$query="";

		$iptNameErr="";
		$iptEmailErr="";
		$iptQueryErr="";

		header("Refresh:0");

		
	}

}

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="shortcut icon" href="" type=""> 
	<title>CMS</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- ---fonts S--- -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"> 
<!-- ---fonts E--- -->

<link rel="stylesheet" type="text/css" href="StyleSheet/topNav.css">
<link rel="stylesheet" type="text/css" href="StyleSheet/footer.css">
<link rel="stylesheet" type="text/css" href="StyleSheet/home.css">
</head>


<body>

	<?php 


		if(isset($_SESSION["userid"]) && isset($_SESSION["username"]))
		{
			include 'includes/homeTopNavIfLoggedIn.php'; 
		}
		else
		{
			include 'includes/homeTopNav.php';
		}

	?>

	<section id="lander">
		<div class="cont_quote">
			<p class="main_quote">Let's Make Health Care <br> Better Together</p>

			<p class="sub_info"> </p>
		</div>
	</section>

	

	<section id="about">
        <div class="cont_about">
            <h1 class="about_head"></h1>
            <p class="para"> <br><br>

            </p>
        </div>
        <img class="about_pic" src="skins/us.png">
    </section>

	<section id="query">
		
		<?php 

			if(isset($_SESSION['utype']))
			{
				echo "<h1 class="."\"query_head\"".">Thank You For Being With Us!";
			}
			else if(isset($_COOKIE['doneQuery']))
			{

				echo "<h1 class="."\"query_head\"".">Query Submitted Sucessfully</h1>";
				echo "<p align ="."\"center\"".">You Can Post Another One after 5 minutes <br> You Will Get Your Answer Via Email </p>";

			}
			else
			{
				include 'query.php';
			}


		?>



	</section>


	<div id="footer">

		
	</div>
	


</body>
</html>
