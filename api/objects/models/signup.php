<?php
    require_once('../../config/database.php');

    class SignupModel
    {
        function register($arg)
        {
            $database = new Database();
            $cn = $database->getConnection();
            
            $title = $arg['title'];
            $fname = $arg['fname'];
            $sname = $arg['sname'];
            $email = $arg['email'];
            $rmail = $arg['rmail'];
            $pwrd = md5($arg['pwrd']);
            $country = $arg['country'];
            $state = $arg['state'];
            $city = $arg['city'];
            $mobile = $arg['mobile'];
            $comment = $arg['comment'];
            
            if($arg['subscribe']=='true'){ $subscribe = 1; }else{ $subscribe = 0; }
            
            $date = date('Y-m-d');
            
            $sql = "insert into users values(default, '$title', '$fname', '$sname', '$email', '$rmail', '$pwrd', '$country', '$mobile', '$state', '$city', '$comment','$subscribe', '$date')";
            
            if(mysqli_query($cn, $sql))
            {
                return 'true';
            }
            else
            {
                return mysqli_error($cn);
            }
            
            mysqli_close($cn);
        }
    }
?>