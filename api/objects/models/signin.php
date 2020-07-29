<?php
    require_once('../../config/database.php');
    require_once('../../../apps/key/key.php');

    class SigninModel
    {
        function loginWithCredentials($uname, $passw)
        {
            $database = new Database();
            $cn = $database->getConnection();
            
            $sql = "select * from users where BINARY (email='$uname' or mobile='$uname') and password='$passw' limit 1";
            
            if($query = $cn->query($sql))
            {
                $count = $query->num_rows;

                if( $count > 0 )
                {
                    $_SESSION['session'] = session_id();
                    
                    while($row = $query->fetch_assoc())
                    {
                        $_SESSION['fname'] = $row['firstname'];
                        $_SESSION['lname'] = $row['lastname'];
                        $_SESSION['uname'] = $uname;
                        $_SESSION['id'] = $row['id'];

                        $this->loadUserWallet();
                    }
                    
                    return 'pass';
                }
                if( $count = 0 )
                { 
                    return 'denied'; 
                }
            }
            else
            {
                return mysqli_error($cn);
            }
            
            mysqli_close($cn);
        }
        function loadProfileContent()
        {
            $database = new Database();
            $cn = $database->getConnection();
            $uname = $_SESSION['uname'];
            
            $sql = "select * from users where email='$uname' or mobile='$uname' limit 1";
            
            if($query = $cn->query($sql))
            {
                while($row = $query->fetch_assoc())
                {
                    return '<div class="animated fadeInDown">Welcome!<br><h4>'.$row['firstname'].' '.$row['lastname'].'</h4></div>
                            <div class="animated fadeInDown" style="display:flex;align-item:flex-start;"><span><i class="icon icon-basic-mail" style="font-size:25px;"></i></span>&nbsp;&nbsp;<span>Email:<br>'.$row['email'].'</span></div>
                            <div class="animated fadeInDown" style="display:flex;align-item:flex-start;"><span><i class="icon icon-basic-smartphone" style="font-size:25px;"></i></span>&nbsp;&nbsp;<span>Phone:<br>'.$row['mobile'].'</span></div>
                            <div><div class="btn-dashed transition"><i class="lni-user"></i> &nbsp;<span>MANAGE USER PROFILE</span></div></div>';
                }
            }
            
            mysqli_close($cn);
        }
        function customOrder()
        {
            return '<div class="animated fadeInDown transition">
                        <div style="display:flex;">
                            <span><i class="lni-package" style="font-size:25px"></i></span>&nbsp;&nbsp;
                            <h4>Custom Order</h4>
                        </div>
                        <div class="border"><div></div></div>
                        <div>
                            <div style="margin-bottom:10px;">
                                Need something from a site that you need us to deliver? Do you want to request we ship you a large/overweight item? If yes please fill out the custom order form and we will send a quote within 2 business days.
                            </div>
                            <div class="btn-dashed transition custom-order">START CUSTOM ORDER &nbsp;<i class="lni-angle-double-right"></i></div>
                        </div>
                    </div>';
        }
        function load_orders()
        {
            $database = new Database();
            
            $content = '';
            $id = $_SESSION['id'];
            $cn = $database->getConnection();
            $sql = "select * from custom_order where user_id='$id'";

            if($query=$cn->query($sql))
            {
                $content = '<div class="animated fadeInDown transition" style="min-width:100%;">
                                <div class="group tip-parent transition">
                                    <input type="hidden" class="user-id" value="'.$id.'" />
                                    <select class="filter-order transition form-input">
                                        <option value="All">All Orders</option>
                                        <option value="Completed">Completed Orders</option>
                                        <option value="Pending">Pending Orders</option>
                                        <option value="Processed">Orders you\'ve responded to</option>
                                        <option value="Treated">Orders admin have treated</option>
                                    </select>
                                </div>
                                <div class="list-items">';
                                    while($row = $query->fetch_assoc())
                                    {
                                        switch($row['status'])
                                        {
                                            case 'Completed':
                                                $content .= '<div title="Completed order" class="user-list animated fadeInDown order-'.$row['id'].'" style="background-color:#e5ffe5;border-color:#33ff33;" onclick=get_user_oder_details("'.$row['id'].'")><div style="text-align:left !important;"><i class="lni-package"></i>&nbsp;&nbsp;'.$row['product'].' from '.$row['store'].' ('.$row['product_url'].')</div></div>';
                                                break;
                                            case 'Treated':
                                                $content .= '<div title="Treated order" class="user-list animated fadeInDown order-'.$row['id'].'" style="background-color:#ffe0b3;" onclick=get_user_oder_details("'.$row['id'].'")><div style="text-align:left !important;"><i class="lni-package"></i>&nbsp;&nbsp;'.$row['product'].' from '.$row['store'].' ('.$row['product_url'].')</div></div>';
                                                break;
                                            case 'Processed':
                                                $content .= '<div title="Processed order" class="user-list animated fadeInDown order-'.$row['id'].'" style="background-color:#eee;border-color:#ddd;" onclick=get_user_oder_details("'.$row['id'].'")><div style="text-align:left !important;"><i class="lni-package"></i>&nbsp;&nbsp;'.$row['product'].' from '.$row['store'].' ('.$row['product_url'].')</div></div>';
                                                break;
                                            default:
                                                $content .= '<div title="Pending order" class="user-list animated fadeInDown order-'.$row['id'].'"><div style="text-align:left !important;" onclick=get_user_oder_details("'.$row['id'].'")><i class="lni-package"></i>&nbsp;&nbsp;'.$row['product'].' from '.$row['store'].' ('.$row['product_url'].')</div></div>';
                                                break;
                                        }
                                    }
                $content .= '   </div>
                            </div>';
            }

            return $content;

            mysqli_close($cn);
        }
        function userWallet()
        {
            $this->loadUserWallet();
            
            if(isset($_SESSION['wallet']))
            {
                return '<div class="wallet-pane animated fadeInDown transition">
                            <div style="display:flex;">
                                <span><i class="lni-wallet" style="font-size:25px"></i></span>&nbsp;&nbsp;
                                <h4>Wallet</h4>
                            </div>
                            <div class="border"><div></div></div>
                            <div>
                                <span>To fund your wallet, click on the button provided below to commence the process.</span>
                                <div style="margin-top:10px;">Wallet ID : <span style="text-align:center;font-size:16px;font-weight:900px;">'.$_SESSION['wallet'].'</span></div>
                                <div>Balance : <span  style="text-align:center;font-weight:400;font-size:18px;"><i class="naira">N</i>'.number_format($_SESSION['wallet_fund'],2).'</span></div>
                                <div class="btn-dashed btn-fund-wallet transition" onclick="fetch_wallet_info()"><i class="lni-plus"></i> &nbsp;<span>FUND YOUR WALLET</span></div>
                            </div>
                        </div>';
            }
            else
            {
                return '<div class="animated fadeInDown transition">
                            <div style="display:flex;">
                                <span><i class="lni-wallet" style="font-size:25px"></i></span>&nbsp;&nbsp;
                                <h4>Wallet</h4>
                            </div>
                            <div class="border"><div></div></div>
                            <div>
                                <span>You currently do not have a wallet. Please create your wallet using the button provided below and fund it for easy payment.</span>
                                <div class="btn-dashed btn-wallet-create transition"><i class="lni-plus"></i> &nbsp;<span>CREATE YOUR WALLET</span></div>
                            </div>
                        </div>';
            }
        }
        function createUserWallet()
        {
            $database = new Database();
            
            $cn = $database->getConnection();
            $user = $_SESSION['id'];
            $id = getToken(16);
            $_SESSION['wallet'] = $id;
            $amount = 0;
            $date = date('Y-m-d');
            $sql = "insert into wallet values(default,'$user','$id','$amount','$date')";

            if($query = $cn->query($sql))
            {
                $this->loadUserWallet();
                return $this->userWallet();
            }
            else
            {
                return mysqli_error($cn);
            }

            mysqli_close($cn);
        }
        function loadUserWallet()
        {
            $database = new Database();
            
            $cn = $database->getConnection();
            $id = $_SESSION['id'];
            $sql = "select * from wallet where user_id='$id' limit 1";
            
            if($query = $cn->query($sql))
            {
                while($row = $query->fetch_assoc())
                {
                    $_SESSION['wallet'] = $row['wallet_id'];
                    $_SESSION['wallet_fund'] = $row['amount'];
                }
            }

            mysqli_close($cn);
        }
        function fetch_wallet_info()
        {
            $database = new Database();
            
            $content = '';
            $id = $_SESSION['id'];
            $cn = $database->getConnection();
            $sql = "select * from wallet where user_id='$id' limit 1";

            if($query = $cn->query($sql))
            {
                $content =" <div class='modal-wrapper transition animated ZoomInUp'>
                                <div class='modal-head animated fadeInUp'>
                                    <span class='lni-close modal-close transition tip-parent' title='Close window'></span>
                                    <img class='transition' src='../assets/images/mc2d_logo.png' alt='My Cart 2 Door' />
                                    <h3 style='margin-top:20px;margin-bottom:0;'>
                                        <span class='lni-wallet title'></span>&nbsp;&nbsp;My Wallet
                                    </h3>
                                    <h5 style='font-size:18px;margin-top:-10px;color:#999'>Fund My Account Wallet</h5>
                                    <span>Please note that using this online platform for payment would incure 1.4% of the amount you intend to pay.</span>
                                </div>
                                <div class='modal-body transition animated fadeInUp'>
                                    <input type='hidden' class='pkey form-input' value='FLWPUBK-13e821c79fda18416452e9e805d4a6f0-X' />
                                    <input type='hidden' class='email form-input' value='".$this->get_current_user_details()['email']."' />
                                    <input type='hidden' class='phone form-input' value='".$this->get_current_user_details()['mobile']."' />'
                                    <input type='hidden' class='txref form-input' value='mc2d-".getIdentity(6)."' />";
                    while($row = $query->fetch_assoc())
                    {
                        $content .= "<div style='min-width:100%;text-align:left;margin-bottom:10px;'>
                                        <h5><span style='font-weight:normal;font-size:15px;'>Wallet ID :</span> ".$row['wallet_id']."</h5>
                                        <h5><span style='font-weight:normal;font-size:15px;'>Current Balance :</span> <i class='naira'>N</i>".number_format($row['amount'], 2)."</h5>
                                    </div>";
                    }
                $content .= "       <div class='group transition tip-parent animated fadeInUp'>
                                        <input type='number' name='amount' id='amount' class='form-input amount' required placeholder='' />
                                        <label class='form-input-placeholder' for='amount' placeholder='' required>Amount</label>
                                        <span style='font-size:12px;color:red;padding-top:10px;margin-left:10px;' class='amount-msg transition'></span>
                                    </div>
                                </div>
                                <div class='modal-footer animated fadeInUp'>
                                    <button class='button modal-button button-success button-rounded transition fund-my-wallet'>&nbsp;&nbsp; CONTINUE WITH FUNDING &nbsp;&nbsp;<i class='transition lni-angle-double-right'></i></button>
                                </div>
                                <script src='../assets/js/flwpbf-inline.js'></script>
                            </div>";
            }

            return $content;
            mysqli_close($cn);
        }
        private function get_current_user_details()
        {
            $database = new Database();
            
            $cn = $database->getConnection();
            $id = $_SESSION['id'];
            $sql = "select * from users where id='$id' limit 1";

            if($query = $cn->query($sql))
            {
                while($row = $query->fetch_assoc())
                {
                    return $row;
                }
            }

            mysqli_close($cn);
        }
        function update_customer_wallet($post)
        {
            $database = new Database();
            
            $cn = $database->getConnection();
            $id = $_SESSION['id'];
            $amount = $post['update_customer_wallet'];
            $sql = "update wallet set amount='$amount' where user_id='$id'";

            if($query = $cn->query($sql))
            {
                return 'done';
            }
        }
        function submitCustomOrder($store,$product,$prod_url,$color,$size,$item_id,$quantity)
        {
            $database = new Database();
            
            $cn = $database->getConnection();
            $date = date('Y-m-d');
            $user = $_SESSION['id'];
            $flag = '';
            
            if(!empty($store) && !empty($product))
            {
                if(empty($quantity))
                {
                    $quantity = $item_id;
                    $item_id = NULL;
                }
                
                $sql = "insert into custom_order values(default,'$user','$store','$product','$prod_url','$color','$size','$item_id','$quantity','', '','$date', 'Pending', NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL)";

                if($query = $cn->query($sql))
                {
                    $flag = 'success';
                }
                else
                {
                    $flag = 'error';
                }
            }
            return $size; //$flag;

            mysqli_close($cn);
        }
        function submit_shipping_form($post)
        {
            $database = new Database();
            
            $cn = $database->getConnection();
            $country = $post['country'];
            $state = $post['state'];
            $lga = $post['lga'];
            $address = $post['address'];
            $date = date('Y-m-d');
            $id = $_SESSION['id'];
            $locale = $lga.' '.$state.' '.$country;
            $sql = "update custom_order set shipping_locale='$locale', shipping_address='$address' where user_id='$id' and order_date='$date'";

            if($query = $cn->query($sql))
            {
                return 'done';
            }
            else
            {
                return mysql_error($cn);
            }

            mysqli_close($cn);
        }
        function get_order_response()
        {
            $database = new Database();
            
            $cn = $database->getConnection();
            $id = $_SESSION['id'];
            $sql = "select * from custom_order where user_id='$id' and status='Treated'";
            $count = 0;

            if($query=$cn->query($sql))
            {
                $count = $query->num_rows;
            }

            return $count;

            mysqli_close($cn);
        }
        function get_my_oder_details($id)
        {
            $database = new Database();

            $content = '';
            $cn = $database->getConnection();
            $sql= "select * from custom_order where id='$id' limit 1";

            if($query = $cn->query($sql))
            {
                while($row = $query->fetch_assoc())
                {
                    switch($row['status'])
                    {
                        case 'Treated':
                            $content = '<div>
                                            <div style="display:flex;"><h4 style="font-size:18px !important;">Unit Cost : </h4> <div class="detail"><h4 style="font-size:18px !important;"><span class="naira">N</span>'.number_format($row['unit_cost'],2).'</h4></div></div>
                                            <div style="display:flex;"><h4 style="font-size:18px !important;">VAT : </h4> <div class="detail"><h4 style="font-size:18px !important;"><span class="naira">N</span>'.number_format($row['vat'],2).'</h4></div></div>
                                            <div style="display:flex;"><h4 style="font-size:18px !important;">Total Cost : </h4> <div class="detail"><h4 style="font-size:18px !important;"><span class="naira">N</span>'.number_format($row['total_cost'],2).'</h4></div></div>
                                            <div style="display:flex;"><h4 style="font-size:18px !important;">Delivery Period : </h4> <div class="detail"><h4 style="font-size:18px !important;">'.$row['delivery'].' day(s)</h4></div></div>
                                        </div>
                                        <div class="sep"></div>
                                        <div>
                                            Please choose your preferred method of product delivery by using either of the button provided below.
                                        </div>
                                        <div class="make-responsive">
                                            <div class="btn-dashed pick-up transition">PICKUP DELIVERY &nbsp;<i class="lni-angle-double-right"></i></div>
                                            &nbsp;&nbsp;&nbsp;
                                            <div class="btn-dashed door-2-door transition">DOOR 2 DOOR DELIVERY &nbsp;<i class="lni-angle-double-right"></i></div>
                                        </div>';
                            break;
                    }

                    return '<div class="animated fadeInDown">
                                <div style="display:flex;"><h4 style="font-size:18px !important;">Store : </h4> <div class="detail"><h4 style="font-size:18px !important;">'.$row['store'].'</h4></div></div>
                                <div style="display:flex;"><h4 style="font-size:18px !important;">Product : </h4> <div class="detail"><h4 style="font-size:18px !important;">'.$row['product'].'</h4></div></div>
                                <div style="display:flex;"><h4 style="font-size:18px !important;">Product URL : </h4> <div class="detail"><h4 style="font-size:18px !important;">'.$row['product_url'].'</h4></div></div>
                                <div style="display:flex;"><h4 style="font-size:18px !important;">Color : </h4> <div class="detail"><h4 style="font-size:18px !important;">'.$row['color'].'</h4></div></div>
                                <div style="display:flex;"><h4 style="font-size:18px !important;">Size : </h4> <div class="detail"><h4 style="font-size:18px !important;">'.$row['size'].'</h4></div></div>
                                <div style="display:flex;"><h4 style="font-size:18px !important;">Product ID : </h4> <div class="detail"><h4 style="font-size:18px !important;">'.$row['item_id'].'</h4></div></div>
                                <div style="display:flex;"><h4 style="font-size:18px !important;">Quantity : </h4> <div class="detail"><h4 style="font-size:18px !important;">'.$row['quantity'].'</h4></div></div>
                                <div style="display:flex;"><h4 style="font-size:18px !important;">Date Ordered : </h4> <div class="detail"><h4 style="font-size:18px !important;">'.$row['order_date'].'</h4></div></div>
                                <div style="display:flex;"><h4 style="font-size:18px !important;">Status : </h4> <div class="detail"><h4 style="font-size:18px !important;">'.$row['status'].'</h4></div></div>
                                <div class="sep"></div>
                                '.$content.'
                            </div>';
                }
            }

            mysqli_close($cn);
        }
    }
?>