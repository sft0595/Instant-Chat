<?php
session_start();
$users = array('simam','shafayet');
if(isset($_POST['replyText']) && !empty($_POST['replyText']) && !empty($_SESSION['nickName'])){
    $reply = $_POST['replyText'];
    $userName = $_SESSION['nickName'];
    $text = "$userName : $reply";
    $fileResource = fopen("chatHistory.txt","a+");
    fwrite($fileResource,"$text\n");
    fclose($fileResource);
}?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Chat On</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/chat.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
</head>

<body>
<?php 
if(!isset($_POST['nickName']) && empty($_SESSION['nickName'])){
?>
<h2>Welcome to Private chat</h2>  
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
        <input type="text" name="nickName" placeholder="User Name">
        <input type="submit" value="submit">
    </form>

<?php }elseif(isset($_POST['nickName'])){

$user = $_POST['nickName'];
if(empty($user)){
    echo "<h2>Welcome to Private chat</h2>";
    echo "<p class=\"error\">User Name can not be empty</p>";
    ?>

    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
        <input type="text" name="nickName" placeholder="User Name">
        <input type="submit" value="submit">
    </form>
<?php
}else{
    if(!in_array($user,$users)){
    echo "<p class=\"error\">You are not an User</p>";?>
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
        <input type="text" name="nickName" placeholder="User Name">
        <input type="submit" value="submit">
    </form>
<?php
    }else{
        $_SESSION['nickName'] = $user;
}
}
}
if(!isset($_POST['destroyChat']) && !isset($_POST['logOut']) && isset($_SESSION['nickName']) && !empty($_SESSION['nickName'])){
    $userName = $_SESSION['nickName'];
        echo "<h2>$userName's window</h2>";
        ?>
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" class="window">
            <textarea rows="10" placeholder="No Conversation to show" readonly><?php 
           $fileSize = filesize("chatHistory.txt");
            if($fileSize>0){
            $fileResource = fopen("chatHistory.txt","r+"); 
            $chat = fread($fileResource,filesize("chatHistory.txt"));
            echo $chat;
            }
           ?></textarea>
            <input type="text" name="replyText" class="replyText" placeholder="Type your reply">
            <input type="submit"  value="Reply" class="Reply">
        </form>
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post"> 
            <input type="submit" name="logOut" value="Log Off" class="danger">
        </form>
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post"> 
            <input type="submit" name="destroyChat" value="Clear History" class="danger">
        </form>
    <?php }
    if(isset($_POST['logOut']) && !empty($_SESSION['nickName'])){
    unset($_SESSION['nickName']);
    echo "<p>Logged off successfully! to log back in <a href=\"chat.php\"> Click Me<a></p>";
    }
    if(isset($_POST['destroyChat']) && !empty($_SESSION['nickName'])){
    file_put_contents("chatHistory.txt", "");
    echo "<p>chat history successfully destroyed !! to go back<a href=\"chat.php\"> Click Me<a></p>";
    }
    ?>

</body>
</html>