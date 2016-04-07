<!-- Displays the top navbar of the website. Conatains Login in pop up--!>
<div class = "navbar transparent navbar-default navbar-fixed-top">
  <nav class="navbar-inner">
   <div class = "container">
    <div class="navbar-header">
     <a href = "home.php" class = "navbar-brand">FeedMe</a>
   </div>
   <ul class = "nav navbar-nav navbar-right">
     <li><a href = "home.php">Home</a></li>
     <li><a href = "about.php">About Us</a></li>

     <?php $loginUser='login_user'?>
     <?php if ($_SESSION['login_user'] == "") : ?>
     <li><a href="#" data-toggle="modal" data-target="#login-modal">Login</a></li>

   <?php else : ?>
   <li><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
    Hey <?php echo $_SESSION['login_user'] ?> ! &#x25BE;</a>

    <ul class="dropdown-menu" role="menu">
      <li><a href="myorders.php">My orders</a></li>
      <li><a href="signup.php?edit">Edit my details</a></li>
      <li><a href="logout.php">Log Out</a></li>
    </ul>
  </li>

<?php endif; ?>


</ul>
</div>
</div>



<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h4 class="modal-title">Login to FeedMe</h4>
      </div>
      <div class="modal-body">
        <form class = "form-horizontal" action = "login.php" method = "post"> 

          <div class = "form-group">
           <label for="inputEmail" class="col-lg-4 control-label">Username</label>
           <div class="col-lg-8"><input type="text" class="form-control" placeholder="Username" name = "username"></div>
         </div>

         <div class = "form-group">
           <label for="inputPassword" class="col-lg-4 control-label">Password</label>
           <div class="col-lg-8"><input type="password" class="form-control" placeholder="Password" name = "password"></div>
         </div>

         <div class="col-lg-8 col-lg-offset-4">Not registered?&nbsp;&nbsp;<a href = "signup.php">Sign Up!</a></div>

       </div>

       <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type = "submit" class="btn btn-primary" name = "submit" value = "Login" >
      </form>
    </div>
  </div>
</div>
</div>

<div class = "container"> 
  <div class = "content"> 