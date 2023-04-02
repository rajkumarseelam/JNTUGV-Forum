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
        <form action="" id="manage-user">	
		<input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id']: '' ?>">
		<div class="form-group">
			<label for="name">Name</label>
			<input type="text" name="name" id="name" class="form-control" value="<?php echo isset($meta['name']) ? $meta['name']: '' ?>" required>
		</div>
		<div class="form-group">
			<label for="username">Username</label>
			<input type="text" name="username" id="username" class="form-control" value="<?php echo isset($meta['username']) ? $meta['username']: '' ?>" required  autocomplete="off">
		</div>
		<div class="form-group">
			<label for="password">Password</label>
			<input type="password" id="password" name="password" class="form-control" value="" autocomplete="off" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least 1 number and 1 uppercase and lowercase letter, and at least 8 or more characters" required>
			<!-- <input type="password" name="password" id="password" class="form-control" value="" autocomplete="off" required> -->
			<?php if(isset($meta['id'])): ?>
			<!-- <small><i>Leave this blank if you dont want to change the password.</i></small> -->
		<?php endif; ?>
		</div>
		<?php if(isset($meta['type']) && $meta['type'] == 3): ?>
			<input type="hidden" name="type" value="3">
		<?php else: ?>
		<?php if(!isset($_GET['mtype'])): ?>
		<div class="form-group">
			<label for="type">User Type</label>
			<select name="type" id="type" class="custom-select">
				<option value="3" <?php echo isset($meta['type']) && $meta['type'] == 3 ? 'selected': '' ?>>Student</option>
				<option value="2" <?php echo isset($meta['type']) && $meta['type'] == 2 ? 'selected': '' ?>>Staff</option>
				<option value="1" <?php echo isset($meta['type']) && $meta['type'] == 1 ? 'selected': '' ?>>Admin</option>
			</select>
		</div>
		<?php endif; ?>
		<?php endif; ?>
		
        <button type="submit" id='submit'  class="btn-sm btn-block btn-wave col-md-4 btn-primary">signup</button>

	</form>

					
					<div class="text-center" >

						<!-- <div class="form-nav-row">
							<a href="#" class="form-link small">Forgot password?</a>
						</div> -->
				<div class="login-row form-nav-row">
					<p class="small m-0 mt-3">Already user?</p>
					<a href="login.php" class="btn btn-outline-secondary m-0 small btn-sm">Login Now</a>
				</div>
				<!-- <div class="col-lg-12"> -->
			<!-- <button class="btn btn-primary float-right btn-sm" id="new_user"><i class="fa fa-plus"></i> New user</button> -->
	<!-- </div> -->
				<!-- <div class="login-row form-nav-row">
					<p class="small mt-4 mb-1">May also signup with</p>
					<a href="#"><img src="view/images/icon-google.png"
					class="signup-icon" /></a><a href="#"><img
						src="view/images/icon-twitter.png" class="signup-icon" /></a><a
						href="#"><img src="view/images/icon-linkedin.png"
						class="signup-icon" /></a>
					</div> -->
				</div>
			</div>
  			</div>
  		</div>
   

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
                    // console.log("Go")
					$('#msg').html('<div class="alert alert-danger">Username already exist</div>')
					// end_load()
				}
			}
		})
	})
	$('#login-form').submit(function(e){
		e.preventDefault()
		$('#login-form button[type="button"]').attr('disabled',true).html('Logging in...');
		if($(this).find('.alert-danger').length > 0 )
			$(this).find('.alert-danger').remove();
		$.ajax({
			url:'ajax.php?action=login',
			method:'POST',
			data:$(this).serialize(),
			error:err=>{
				console.log(err)
		$('#login-form button[type="button"]').removeAttr('disabled').html('Login');

			},
			success:function(resp){
				if(resp == 1){
					location.href ='index.php?page=home';
				}else{
					$('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
					$('#login-form button[type="button"]').removeAttr('disabled').html('Login');
				}
			}
		})
	})
</script>	
</html>