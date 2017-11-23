<?php
require_once("inc/inc.php");

session_start();
//$nuser=$_SESSION['nama_user'];
?>

<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
<title>Cristo Resto Information System - CResto V.201608</title>
<script src="jquery/jquery.js"></script>
<script src="jquery/jquery-ui.js"></script>
<link rel="stylesheet" href="jquery/jquery-ui.css">

<style>
.mySlides {display:none;}
</style>

</head>
<body style="background-image:url('images/pattern.png')">


<div class="w3-content w3-section" style="max-width:200px;margin:10 auto;">
  <img class="mySlides" src="images/kategori-menu.png" style="width:100%"/>
  <img class="mySlides" src="images/data-meja.png" style="width:100%"/>
  <img class="mySlides" src="images/data-menu.png" style="width:100%"/>
  <img class="mySlides" src="images/jam-shift.png" style="width:100%"/>
</div>

<script type="text/javascript">
var myIndex = 0;
carousel();

function carousel() {
    var i;
    var x = document.getElementsByClassName("mySlides");
    for (i = 0; i < x.length; i++) {
       x[i].style.display = "none";
    }
    myIndex++;
    if (myIndex > x.length) {myIndex = 1}
    x[myIndex-1].style.display = "block";
    setTimeout(carousel, 2000); // Change image every 2 seconds
}
</script>


<div style="width:400px;margin:0 auto;border:solid 1px #ccc;border-radius:8px;text-align:center;padding:5px;background-color:#fff;vertical-align: middle;">
  <h3 style="color:#830;">Cristho Resto App. Login</h3><hr style="border:1px dashed #ccc;"/>
  <form method="post" action="" autocomplete="off">
    <input type="text" name="txuser" id="txuser" size="30" placeholder="User Name" style="margin:2px;" required/><br/>
    <input type="password" id="prevent_autofill" style="display:none"/>
    <input type="password" name="txpass" id="txpass" size="30" placeholder="Password" style="margin:2px;" required/><br/>
    <input type="submit" name="submit" id="submit" value="Login" style="margin:10px;width:100px;" class="ui-button ui-widget ui-corner-all"/>
  </form>
  <div style="color:#f00;font-style:italic;">
    <?php
    if (isset($_POST['submit'])) {
      $user=$_POST['txuser'];
      $pass=$_POST['txpass'];
      if (strlen(trim($user))>0 && strlen(trim($pass))>0 ) {
        /*if ($rs=$con->query("SELECT name, password FROM users WHERE name=md5('$user') AND password=md5('$pass');")) {
          if ($row=$rs->fetch_row()) {
            $_SESSION['user_name']=$user;
            header('location: http://localhost/cresto/');
          }else{
            echo "Login data tidak ditemukan!";
          }
        }*/
        $sql="
        SELECT
          AES_DECRYPT(name,$keys),
          AES_DECRYPT(password,$keys),
          AES_DECRYPT(role,$keys)
        FROM
          users
        WHERE
          name=AES_ENCRYPT(?,?) AND password=AES_ENCRYPT(?,?)
        ;";
        #use this prepare statement to prevent sql injection
        $stmt = $con->prepare($sql);
        $stmt->bind_param('ssss', $user,$keys,$pass,$keys);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_row()) {
          $_SESSION['user_name']=$user;
          header('location: /cresto/');
        }else{
          echo "Login data tidak ditemukan!";
        }

      }else{
        //echo"not valid entry";
      }
    }
    ?>
  </div>
</div>
</body>
</html>
