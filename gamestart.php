
<?php  
session_start();

if($_REQUEST['id']!='')
{
     if(!isset($_SESSION['user_id'])) {

     	$_SESSION['user_id'] = 	$_REQUEST['id'];

     }


    	
}
if($_REQUEST['login_id']!='')
{
     if(!isset($_SESSION['login_id'])) {

        $_SESSION['login_id'] = $_REQUEST['login_id'];
     }


    	
}

 //header("Location: https://".$_SERVER['HTTP_HOST']."/rummy-game/newgame.php");
header("Location: /rummy-game/newgame.php");

?>

<?php echo $_POST['id'];exit;?>

