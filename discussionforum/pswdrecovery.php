<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
include('./db_connect.php');
?>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>JNTU-GV&nbsp;Forum</title>
 	

<?php include('./header.php'); ?>
<?php 
if(isset($_SESSION['login_id']))
header("location:index.php?page=home");

?>

</head>
<style>
	body{
		width: 100%;
	    height: calc(100%);
	    /*background: #007bff;*/
	}
	main#main{
		width:100%;
		height: calc(100%);
		background:white;
	}
	#login-right{
		position: absolute;
		right:0;
		width:40%;
		height: calc(100%);
		background:white;
		display: flex;
		align-items: center;
	}
	#login-left{
		position: absolute;
		left:0;
		width:60%;
		height: calc(100%);
		flex-direction:column;
		/* background:#59b6ec61; */
		display: flex;
		align-items: center;
		/* background: url(assets/uploads/<?php echo $_SESSION['system']['cover_img'] ?>); */
		background-image: url("assets\img\login-left.png");
	    background-repeat: no-repeat;
	    background-size: cover;
	}
	#login-right .card{
		margin: auto;
		z-index: 1
	}
	.logo {
    margin: auto;
    font-size: 8rem;
    background: white;
    padding: .5em 0.7em;
    border-radius: 50% 50%;
    color: #000000b3;
    z-index: 10;
}
div#login-right::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: calc(100%);
    height: calc(100%);
    background:lightblue;
}
.heading{
	font-size:5rem;
	color:#1877f2;
}

</style>

<body>


  <main id="main" class=" bg-dark">
  		<div id="login-left" class="justify-content-center">
  			<!-- <h1>JAWAHARLAL NEHRU TECHNOLOGICAL UNIVERSITY GURAJADA VIZIANAGARAM</h1> -->
			<h1 class="mb-5 heading">JNTU-GV &nbsp;FORUM</h1><img src="assets\img\login-left.png" alt="" srcset="" class="img-fluid" width="60%">
  		</div>

  		<div id="login-right">
  			<div class="card col-md-8">
  				<div class="card-body">
					<h4 class="text-center">
				  <img src="assets\img\logo-min.jpeg" alt="" class="img-fluid" width="60px">
				  JNTU-GV
				  </h4>
        <form name="myform" method="post" onsubmit="return validPassword()" action="/home.php" id="manage-user">	
		<input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id']: '' ?>">
		<!-- <div class="form-group">
			<label for="name">Name</label>
			<input type="text" name="name" id="name" class="form-control" value="<?php echo isset($meta['name']) ? $meta['name']: '' ?>" required>
		</div> -->
		<div class="form-group">
			<label for="username">Username</label>
			<input type="text" name="username" id="username" class="form-control" value="<?php echo isset($meta['username']) ? $meta['username']: '' ?>" required  autocomplete="off">
		</div>
		<div class="form-group">
			<label for="password">New Password</label>
			<input type="password" id="password" name="pswd1" class="form-control" value="" autocomplete="off" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least 1 number and 1 uppercase and lowercase letter, and at least 8 or more characters" required>
			<!-- <input type="password" name="password" id="password" class="form-control" value="" autocomplete="off" required> -->
			<?php if(isset($meta['id'])): ?>
			<!-- <small><i>Leave this blank if you dont want to change the password.</i></small> -->
		<?php endif; ?>
        <div class="form-group">
			<label for="name">Confirm Password</label>
			<input type="password" name="pswd2" id="name" class="form-control"  autocomplete="off" required>
		</div>
        <input type="submit" value="Submit">
		</div>
		
		
        <!-- <button type="submit" id='submit'  class="btn-sm btn-block btn-wave col-md-4 btn-primary">signup</button> -->

	</form>
    <script>
 function validPassword(){
    var pswd=document.myform.pass1.value;
    var cpswd=document.myform.pass2.value;
    if(pswd==cpswd){
        return true;
    }
    else{
        alert(" New Password and Confirm Password must be same");
        return false;
    }

 }
 </script>
					
					
  </main>

  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>


</body>
<script>
$('#manage-user').submit(function(e){
    e.preventDefault();
    // start_load()
    $.ajax({
        url:'ajax.php?action=save_user',
        method:'POST',
        data:$(this).serialize(),
        success:function(resp){
            if(resp ==1){
                // alert_toast("Data successfully saved",'success')
                setTimeout(function(){
                    console.log("Hello");
                    location.href ='index.php?page=home';
					},1500)
				}else{
                    console.log("Go")
					$('#msg').html('<div class="alert alert-danger">Username already exist</div>')
					// end_load()
				}
			}
		})
	})
	// $('#login-form').submit(function(e){
	// 	e.preventDefault()
	// 	$('#login-form button[type="button"]').attr('disabled',true).html('Logging in...');
	// 	if($(this).find('.alert-danger').length > 0 )
	// 		$(this).find('.alert-danger').remove();
	// 	$.ajax({
	// 		url:'ajax.php?action=login',
	// 		method:'POST',
	// 		data:$(this).serialize(),
	// 		error:err=>{
	// 			console.log(err)
	// 	$('#login-form button[type="button"]').removeAttr('disabled').html('Login');

	// 		},
	// 		success:function(resp){
	// 			if(resp == 1){
	// 				location.href ='index.php?page=home';
	// 			}else{
	// 				$('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
	// 				$('#login-form button[type="button"]').removeAttr('disabled').html('Login');
	// 			}
	// 		}
	// 	})
	// })
</script>	
</html>