<?php
include_once('controller/config.php');

if(isset($_POST["do"])&&($_POST["do"]=="update_admin_profile")){

	$id=$_POST['id'];
	$full_name=$_POST['full_name'];
	$i_name=$_POST['i_name']; 
	$gender=$_POST['gender']; 
	$address=$_POST['address'];
	$phone=$_POST['phone'];
	$email=$_POST['email']; 
	$user_name=$_POST['user_name']; 
	$password=$_POST['password']; 
	// Compare the hashed passwords
	if (md5($password) == $password_hash) {
		// The passwords match
		echo "Login successful.";
		}
		else {
		// The passwords don't match
		echo "Wrong email or password.";
		}	
	
	$sql2="SELECT * FROM admin WHERE id='$id'";
	$result2=mysqli_query($conn,$sql2);
	$row2=mysqli_fetch_assoc($result2);
	$email2=$row2['email'];
	
	$tarPOST_dir = "uploads/";
	$name = basename($_FILES["fileToUpload"]["name"]);
	$size = $_FILES["fileToUpload"]["size"];
	$type = $_FILES["fileToUpload"]["type"];
	$tmpname = $_FILES["fileToUpload"]["tmp_name"];

	$max = 31457280;
	$extention = strtolower(substr($name, strpos($name, ".")+ 1));
	$filename = date("Ymjhis");
	
	$msg=0;//for alerts
	$image_path = $tarPOST_dir.$filename.".".$extention;
	
	if(!$name){
		
		$sql = "update admin set full_name='".$full_name."',i_name='".$i_name."', gender='".$gender."',address='".$address."', phone='".$phone."',email='".$email."' where id='$id'";
		
		if(mysqli_query($conn,$sql)){
							
			if($email == $email2){
				$sql1 = "update user set password='".$password."' where email='$email'";
				mysqli_query($conn,$sql1);
					
			}else{
				$sql3="DELETE FROM user WHERE email='$email2'";
				mysqli_query($conn,$sql3);
					
				$sql4="INSERT INTO user (email,password,type) 
					   VALUES ( '".$email."','".$password."','Admin')";
				mysqli_query($conn,$sql4);
					
			}
			
			$msg+=1;
			//MSK-000143-U-4 The record has been successfully updated in the database.
		
		}else{
			$msg+=2;
			//MSK-000143-U-6 Connection problem	
		}
		
	}else{
		
		if(move_uploaded_file($tmpname, $image_path)){
			
			$sql = "update admin set full_name='".$full_name."',i_name='".$i_name."', gender='".$gender."',address='".$address."', phone='".$phone."',email='".$email."',image_name='".$image_path."' where id='$id'";
		
			if(mysqli_query($conn,$sql)){
							
				if($email == $email2){
					$sql1 = "update user set password='".$password."' where email='$email'";
					mysqli_query($conn,$sql1);
					
				}else{
					$sql3="DELETE FROM user WHERE email='$email2'";
					mysqli_query($conn,$sql3);
					
					$sql4="INSERT INTO user (email,password,type) 
						   VALUES ( '".$email."','".$password."','Admin')";
					mysqli_query($conn,$sql4);
					
				}
				$msg+=1;
				//MSK-000143-U-4 The record has been successfully updated in the database.
		
			}else{
				$msg+=2;
				//MSK-000143-U-6 Connection problem	
			}
			
			
			
		}else{
			
			//There is haven't image root folder.
		}
		
	}
	
	header("Location:view/admin_profile.php?do=alert_from_update&msg=$msg");//MSK-000143-5

}
?>