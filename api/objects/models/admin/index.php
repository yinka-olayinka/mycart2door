<?php
    require_once('../../../apps/mail/mail.php');

    class Admin_Logic
    {
        function get_customer_name($id)
        {
            $database = new Database();

            $cn = $database->getConnection();
            $sql = "select * from users where id='$id'";

            if($query = $cn->query($sql))
            {
                while($row = $query->fetch_assoc())
                {
                    return $row['title'].' '.$row['lastname'].' '.$row['firstname'];
                }
            }

            mysqli_close($cn);
        }

        /**
        * Administrator Class logic methods
        */
        function confirmAdminCredentials($uname, $passw)
        {
            $database = new Database();

            $cn = $database->getConnection();
            $sql = "select * from admin_users where username='$uname' and password='$passw' and status=1 limit 1";

            if($query = $cn->query($sql))
            {
                $count = $query->num_rows;

                if($count > 0)
                {
                    while($row = $query->fetch_assoc())
                    {
                        $_SESSION['id'] = $row['id'];
                        $_SESSION['fname'] = $row['firstname'];
                        $_SESSION['sname'] = $row['lastname'];
                        $_SESSION['role'] = $row['role'];
                        $_SESSION['username'] = $row['username'];
                    }
                    
                    $_SESSION['backend'] = $count;
                }

                return $count;
            }
            else
            {
                return mysqli_error($cn);
            }

            mysqli_close($cn);
        }
        function loadAdminProfile()
        {
            $database = new Database();
            $cn = $database->getConnection();
            $uname = $_SESSION['username'];
            
            $sql = "select * from admin_users where username='$uname' limit 1";
            
            if($query = $cn->query($sql))
            {
                while($row = $query->fetch_assoc())
                {
                    $role = 'User';

                    if($row['role']=='A')
                    {
                        $role = 'Administrator';
                    }

                    return '<div class="animated fadeInDown">Welcome!<br><h4>'.$row['firstname'].' '.$row['lastname'].'</h4></div>
                            <div class="animated fadeInDown" style="display:flex;align-item:flex-start;"><span><i class="icon icon-basic-key" style="font-size:25px;"></i></span>&nbsp;&nbsp;<span>Role: '.$role.'</span></div>
                            <div class="animated fadeInDown" style="display:flex;align-item:flex-start;"><span><i class="icon icon-basic-geolocalize-01" style="font-size:25px;"></i></span>&nbsp;&nbsp;<span>Stream: '.$row['stream'].'</span></div>
                            <div><div class="btn-dashed transition"><i class="lni-user"></i> &nbsp;<span>MANAGE USER PROFILE</span></div></div>';
                }
            }
            
            mysqli_close($cn);
        }
        function loadUserList()
        {
            $database = new Database();

            $cn = $database->getConnection();
            $sql = "select * from admin_users where role='U' order by firstname";
            $content = '';

            if($query = $cn->query($sql))
            {
                $count = $query->num_rows;

                if($count > 0)
                {
                    $content = '<div class="toolbar transition animated fadeInDown">
                                    <div>
                                        <div class="group transition tip-parent">
                                            <input type="text" class="form-input search transition" required placeholder="" />
                                            <label class="form-input-placeholder" for="search" placeholder="" required>Search...</label>
                                            <span style="font-size:12px;color:red;padding-top:10px;margin-left:10px;" class="search-msg transition"></span>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="new-user"><i class="lni-plus" title="Add new backend user"></i></span>
                                    </div>
                                </div>';
                    while($row = $query->fetch_assoc())
                    {
                        $cls = '';

                        if($row['status'] == 0){ $cls = 'suspension'; }

                        $content .= '<div class="animated fadeInDown transition user-list user-list-'.$row['id'].' '.$cls.'">
                                        <div class="transition" title="Click to add user\'s permissions" onclick=addPermissions("'.$row['id'].'")><i class="lni-user"></i>&nbsp;&nbsp;'.$row['firstname'].' '.$row['lastname'].'</div>
                                        <div>
                                            <span>
                                                <i class="lni-more-alt"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <span>
                                                <i class="lni-pencil-alt transition" title="Edit user credentials" onclick=editUserProfile("'.$row['id'].'")></i>
                                            </span>
                                            <span>
                                                <i class="lni-ban transition" title="Suspend selected user" onclick=suspendUserAccount("'.$row['id'].'")></i>
                                            </span>
                                            <span>
                                                <i class="icon icon-basic-trashcan transition" title="Delete selected user"  onclick=deleteUserAccount("'.$row['id'].'")></i>
                                            </span>
                                        </div>
                                    </div>';
                    }

                    return $content;
                }
                else
                {
                    return '<div class="empty-list animated fadeInDown transition">
                                <div>
                                    <i class="icon icon-basic-folder"></i><br>
                                    <span>Backend user list empty!</span>
                                    <div class="btn-dashed transition create-user"><i class="lni-plus"></i>&nbsp;CREATE BACKEND USER</div>
                                </div>
                            </div>';
                }
            }

            mysqli_close($cn);
        }
        function insertBackendUser($post)
        {
            $database = new Database();

            $cn = $database->getConnection();
            $fname = $post['fname'];
            $sname = $post['sname'];
            $uname = $post['uname'];
            $passw = md5($post['passw']);
            $stream = $post['stream'];
            $date = date('Y-m-d');
            $sql = "insert into admin_users values(default, '$fname', '$sname', '$uname', '$passw', '$stream', 'U',1, '$date')";

            if($query = $cn->query($sql))
            {
                return 'success';
            }
            else
            {
                return mysqli_error($cn);
            }

            mysqli_close($cn);
        }
        function edit_user_profile($id)
        {
            $database = new Database();

            $content = '';
            $cn = $database->getConnection();
            $sql = "select * from admin_users where id='$id' limit 1";

            if($query = $cn->query($sql))
            {
                while($row = $query->fetch_assoc())
                {
                    $content = '<div class="transition animated fadeInDown" style="min-width:100%;">
                                    <input type="hidden" class="id" value="'.$row['id'].'" />
                                    <div class="group transition tip-parent animated fadeInUp">
                                        <input type="text" name="fname" id="fname" class="form-input fname transition" required placeholder="" value="'.$row['firstname'].'" />
                                        <label class="form-input-placeholder" for="fname" placeholder="" required>First name</label>
                                        <span style="font-size:12px;color:red;padding-top:10px;margin-left:10px;" class="fname-msg transition"></span>
                                    </div>
                                    <div class="group transition tip-parent animated fadeInUp">
                                        <input type="text" name="sname" id="sname" class="form-input sname transition" required placeholder="" value="'.$row['lastname'].'" />
                                        <label class="form-input-placeholder" for="sname" placeholder="" required>Surname</label>
                                        <span style="font-size:12px;color:red;padding-top:10px;margin-left:10px;" class="sname-msg transition"></span>
                                    </div>
                                    <div class="group transition tip-parent animated fadeInUp">
                                        <input type="text" name="uname transition" id="uname" class="form-input uname" required placeholder="" value="'.$row['username'].'" />
                                        <label class="form-input-placeholder" for="uname" placeholder="" required>Username</label>
                                        <span style="font-size:12px;color:red;padding-top:10px;margin-left:10px;" class="uname-msg transition"></span>
                                    </div>
                                    <div class="group transition tip-parent animated fadeInUp">
                                        <input type="password" name="passw transition" id="passw" class="form-input passw" required placeholder="" value="'.$row['password'].'" />
                                        <label class="form-input-placeholder" for="passw" placeholder="" required>Password</label>
                                        <span style="font-size:12px;color:red;padding-top:10px;margin-left:10px;" class="passw-msg transition"></span>
                                    </div>
                                    <div class="group transition tip-parent animated fadeInUp">
                                        <input type="password" name="passc transition" id="passc" class="form-input passc" required placeholder="" value="'.$row['password'].'" />
                                        <label class="form-input-placeholder" for="passc" placeholder="" required>Confirm Password</label>
                                        <span style="font-size:12px;color:red;padding-top:10px;margin-left:10px;" class="passc-msg transition"></span>
                                    </div>
                                    <div class="group transition tip-parent animated fadeInUp">
                                        <input type="text" name="stream transition" id="stream" class="form-input stream" required placeholder="" value="'.$row['stream'].'" />
                                        <label class="form-input-placeholder" for="stream" placeholder="" required>Stream</label>
                                        <span style="font-size:12px;color:red;padding-top:10px;margin-left:10px;" class="stream-msg transition"></span>
                                    </div>
                                </div>';
                }

                return $content;
            }

            mysqli_close($cn);
        }
        function update_backend_user($post)
        {
            $database = new Database();

            $cn = $database->getConnection();
            $id = $post['id'];
            $fname = $post['fname'];
            $sname = $post['sname'];
            $uname = $post['uname'];
            $passw = md5($post['passw']);
            $stream = $post['stream'];
            $sql = "update admin_users set firstname='$fname', lastname='$sname', username='$uname', password='$passw', stream='$stream' where id='$id'";

            if($query = $cn->query($sql))
            {
                return 'success';
            }
            else
            {
                return mysqli_error($cn);
            }

            mysqli_close($cn);
        }
        function suspend_user_profile($id)
        {
            $database = new Database();

            $cn = $database->getConnection();
            $sql = "update admin_users set status=0 where id='$id'";
            
            if($query = $cn->query($sql))
            {
                return 'done';
            }

            mysqli_close($cn);
        }
        function unsuspend_user_profile($id)
        {
            $database = new Database();

            $cn = $database->getConnection();
            $sql = "update admin_users set status=1 where id='$id'";
            
            if($query = $cn->query($sql))
            {
                return 'done';
            }

            mysqli_close($cn);
        }
        function delete_user_profile($id)
        {
            $database = new Database();

            $cn = $database->getConnection();
            $sql = "delete from admin_users where id='$id'";
            
            if($query = $cn->query($sql))
            {
                return 'done';
            }

            mysqli_close($cn);
        }


        /**
         * Finance functions and procedures that manages backend
         * @Finance Backend
         */
         function currency_manager_luncher()
         {
            $database = new Database();

            $content = '';
            $symbol = '';
            $cn = $database->getConnection();
            $sql = "select * from currency";

            if($query = $cn->query($sql))
            {
                $content = "<div class='modal-wrapper transition animated ZoomInUp'>
                                <div class='modal-head animated fadeInUp'>
                                    <span class='lni-close modal-close transition tip-parent' title='Close window'></span>
                                    <img class='transition' src='../assets/images/mc2d_logo.png' alt='My Cart 2 Door' />
                                    <h3 style='margin-top:20px;margin-bottom:0;'>
                                        <span class='lni-target-revenue title'></span>&nbsp;Currency Rate
                                    </h3>
                                    <h3 class='sub-title'>Set Current Currency Rate</h3>
                                </div>
                                <div class='modal-body transition animated fadeInUp'>
                                    <div class='group transition tip-parent animated fadeInUp'>
                                        <select class='form-input curr transition' onchange=get_current_set_rate(this.value)>
                                            <option disabled value='' selected>Choose Currency</option>";

                            while($row = $query->fetch_assoc())
                            {
                                switch($row['currency'])
                                {
                                    case 'GBP':
                                        $symbol = '&pound';
                                        break;
                                    case 'EUR':
                                        $symbol = '&euro;';
                                        break;
                                    default:
                                        $symbol = '$';
                                        break;
                                }
                                $content .= "<option value=".$row['id'].">".$row['currency']." (".$symbol.")</option>";
                            }

                $content .= "               <span style='font-size:12px;color:red;padding-top:10px;margin-left:10px;' class='curr-msg transition'></span>
                                        </select>
                                    </div>
                                    <div class='group transition tip-parent animated fadeInUp'>
                                        <input type='number' class='form-input rate transition' required placeholder='' />
                                        <label class='form-input-placeholder' for='rate' placeholder='' required>Current Rate</label>
                                        <span style='font-size:12px;color:red;padding-top:10px;margin-left:10px;' class='rate-msg transition'></span>
                                    </div>
                                </div>
                                <div class='modal-footer animated fadeInUp'>
                                    <button class='button modal-button button-success button-rounded transition update-currency'>&nbsp;&nbsp; UPDATE CURRENCY RATE &nbsp;&nbsp;<i class='transition lni-angle-double-right'></i></button>
                                </div>
                            </div>";
            }

            return $content;

            mysqli_close($cn);
         }
         function get_current_set_rate($id)
         {
            $database = new Database();

            $cn = $database->getConnection();
            $sql = "select rate from currency where id='$id' limit 1";

            if($query = $cn->query($sql))
            {
                while($row = $query->fetch_assoc())
                {
                    return $row['rate'];
                }
            }

            mysqli_close($cn);
         }
         function update_currency_rate($id, $rate)
         {
            $database = new Database();

            $cn = $database->getConnection();
            $sql = "update currency set rate='$rate' where id='$id'";

            if($query = $cn->query($sql))
            {
               return 'done';
            }
            else
            {
                return mysqli_error($cn);
            }

            mysqli_close($cn);
         }
         function manage_Delivery_location()
         {
            $database = new Database();
            
            $cn = $database->getConnection();
            $sql = "select * from shipping";
            $content = '';

            if($query = $cn->query($sql))
            {
                $count = $query->num_rows;

                $content = "<div class='modal-wrapper transition animated ZoomInUp'>
                                <div class='modal-head animated fadeInUp'>
                                    <span class='lni-close modal-close transition tip-parent' title='Close window'></span>
                                    <img class='transition' src='../assets/images/mc2d_logo.png' alt='My Cart 2 Door' />
                                    <h3 style='margin-top:20px;margin-bottom:0;'>
                                        <span class='lni-ship title'></span>&nbsp;Shipping Rate
                                    </h3>
                                    <h3 class='sub-title'>Set Shipping Rate</h3>
                                </div>
                                <div class='modal-body transition animated fadeInUp'>";
                if($count > 0)
                {
                    $content .= '<div class="toolbar transition animated fadeInDown">
                                    <div>
                                        <div class="group transition tip-parent">
                                            <input type="text" class="form-input search transition" required placeholder="" />
                                            <label class="form-input-placeholder" for="search" placeholder="" required>Search...</label>
                                            <span style="font-size:12px;color:red;padding-top:10px;margin-left:10px;" class="search-msg transition"></span>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="shipping-form" onclick="get_shipping_form();"><i class="lni-plus" title="Add new backend user"></i></span>
                                    </div>
                                </div>';
                    while($row = $query->fetch_assoc())
                    {
                        $content .= '<div class="flex-deflex animated fadeInDown transition user-list" style="text-align:left;justify-content:flex-start;align-item:center;align-content:center;">
                                        <div class="transition" title="">
                                            <i class="lni-ship"></i>&nbsp;&nbsp;'.$row['lga'].' '.$row['state'].' '.$row['country'].' [ <i class="naira">N</i>'.number_format($row['amount'], 2).' ]
                                            <span style="float:right;background-color:transparent;padding:0;align-self:center;">
                                                <span style="margin-right:10px;padding:5px;border-radius:100%;background-color:rgba(229, 242, 255, .5);" onclick=editShipping("'.$row['id'].'")>
                                                    <i class="lni-pencil-alt transition" title="Edit shipping" ></i>
                                                </span>    
                                                <span style="padding:5px;border-radius:100%;background-color:rgba(229, 242, 255, .5);"  onclick=deleteShipping("'.$row['id'].'")>
                                                    <i class="icon icon-basic-trashcan transition" title="Delete selected shipping"></i>
                                                </span>
                                            </span>
                                        </div>
                                    </div>';
                    }
                }
                else
                {
                    $content .= '   <div class="empty-list animated fadeInDown transition">
                                        <div>
                                            <i class="icon icon-basic-folder"></i><br>
                                            <span>Shipping rate list empty!</span>
                                            <div class="btn-dashed transition create-shipping"><i class="lni-plus"></i>&nbsp;MANAGE SHIPPING RATE</div>
                                        </div>
                                    </div>
                                </div>';
                }
            }

            return $content;

            mysqli_close($cn);
         }
         function load_shipping_list()
         {
             $database = new Database();
             
             $cn = $database->getConnection();
             $sql = "select * from shipping";
             $content = '';

             if($query = $cn->query($sql))
             {
                 $content .= '<div class="toolbar transition animated fadeInDown">
                                    <div>
                                        <div class="group transition tip-parent">
                                            <input type="text" class="form-input search transition" required placeholder="" />
                                            <label class="form-input-placeholder" for="search" placeholder="" required>Search...</label>
                                            <span style="font-size:12px;color:red;padding-top:10px;margin-left:10px;" class="search-msg transition"></span>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="new-user"><i class="lni-plus" title="Add new backend user" onclick="get_shipping_form();"></i></span>
                                    </div>
                                </div>';

                while($row = $query->fetch_assoc())
                {
                    $content .= '<div class="flex-deflex animated fadeInDown transition user-list" style="text-align:left;justify-content:flex-start;align-item:center;align-content:center;">
                                    <div class="transition" title="">
                                        <i class="lni-ship"></i>&nbsp;&nbsp;'.$row['lga'].' '.$row['state'].' '.$row['country'].' [ <i class="naira">N</i>'.number_format($row['amount'], 2).' ]
                                        <span style="float:right;background-color:transparent;padding:0;align-self:center;">
                                            <span style="margin-right:10px;padding:5px;border-radius:100%;background-color:rgba(229, 242, 255, .5);" onclick=editShipping("'.$row['id'].'")>
                                                <i class="lni-pencil-alt transition" title="Edit shipping" ></i>
                                            </span>    
                                            <span style="padding:5px;border-radius:100%;background-color:rgba(229, 242, 255, .5);"  onclick=deleteShipping("'.$row['id'].'")>
                                                <i class="icon icon-basic-trashcan transition" title="Delete selected shipping"></i>
                                            </span>
                                        </span>
                                    </div>
                                </div>';
                }
             }

             return $content;

             mysqli_close($cn);
         }
         function update_shipping_rate($post)
         {
             $database = new Database();
             
             $cn = $database->getConnection();
             $country = $post['country'];
             $state = $post['state'];
             $lga = $post['lga'];
             $amount = $post['amount'];
             $sql = "insert into shipping values(default,'$country','$state','$lga','$amount')";

             if($query = $cn->query($sql))
             {
                 return 'done';
             }
             else
             {
                 return mysqli_error($cn);
             }

             mysqli_close($cn);
         }



        /**
         * This block is for custom order personnel dashboard to manage all customer's orders
         * @Custom Order Personnel Backend
         */
        function users_with_order()
        {
            $database = new Database();

            $content = '<div style="margin-bottom:20px;" class="transition animated fadeInDown">
                            <span>Below are Customers with custom orders</span>
                        </div>';
            $cn = $database->getConnection();
            $sql = "select distinct user_id from custom_order";

            if($query = $cn->query($sql))
            {
                while($row = $query->fetch_assoc())
                {
                    $content .= '<div class="animated fadeInDown transition user-list">'.$this->getUserDetail($row['user_id']).'</div>';
                }

                return $content;
            }

            mysqli_close($cn);
        }
        private function getUserDetail($id)
        {
            $database = new Database();

            $cn = $database->getConnection();
            $sql = "select * from users where id='$id'";

            if($query = $cn->query($sql))
            {
                while($row = $query->fetch_assoc())
                {
                    return '<div style="text-align:left !important;" onclick=get_user_order_list("'.$id.'")><i class="lni-user"></i>&nbsp;&nbsp;'.$row['title'].' '.$row['lastname'].' '.$row['firstname'].'</div>';
                }
            }

            mysqli_close($cn);
        }
        function get_only_order_list($id)
        {
            $database = new Database();

            $content = '';
            $cn = $database->getConnection();
            $sql = "select * from custom_order where user_id='$id' order by status";

            if($query = $cn->query($sql))
            {
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
            }

            return $content;

            mysqli_close($cn);
        }
        function get_user_order_list($id)
        {
            $database = new Database();

            $content = '';
            $cn = $database->getConnection();
            $sql = "select * from custom_order where user_id='$id' order by status";

            if($query = $cn->query($sql))
            {
                $content = '<div class="animated fadeInDown">
                                <div style="margin-bottom:20px;">
                                    <div style="display:flex;">
                                        <span><i class="lni-user" style="font-size:36px;"></i></span>&nbsp;&nbsp;<h4>'.$this->get_customer_name($id).'\'s Orders</h4>
                                    </div>
                                    <div class="border"><div></div></div>
                            </div>';
                $back_color = 'transparent';

                $content .= '<div class="group tip-parent transition">
                                <input type="hidden" class="user-id" value="'.$id.'" />
                                <select class="filter-order transition form-input">
                                    <option value="All">All Orders</option>
                                    <option value="Completed">Completed Orders</option>
                                    <option value="Pending">Pending Orders</option>
                                    <option value="Processed">Orders Customer responded to</option>
                                    <option value="Treated">Orders you\'ve treated</option>
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

                $content .= '</div>';
            }

            return $content;

            mysqli_close($cn);
        }
        function get_user_oder_details($id)
        {
            $database = new Database();

            $cn = $database->getConnection();
            $sql= "select * from custom_order where id='$id' limit 1";

            if($query = $cn->query($sql))
            {
                while($row = $query->fetch_assoc())
                {
                    return '<div class="animated fadeInDown">
                                <div style="margin-bottom:20px;">
                                    <div style="display:flex;">
                                        <span><i class="lni-user" style="font-size:36px;"></i></span>&nbsp;&nbsp;<h4>'.$this->get_customer_name($row['user_id']).'\'s Orders</h4>
                                    </div>
                                    <div class="border"><div></div></div>
                                </div>
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
                                <div>
                                    <div class="group transition tip-parent animated fadeInUp">
                                        <select class="form-input forex transition" onchange="pickup_current_rate(this.value)">'.$this->return_set_currencies().'</select>
                                        <span style="font-size:12px;color:red;padding-top:10px;margin-left:10px;" class="forex-msg transition"></span>
                                    </div>
                                    <div class="group transition tip-parent animated fadeInUp">
                                        <input type="number" class="form-input cur-rate transition" placeholder="" disabled />
                                        <label class="form-input-placeholder" for="cur-rate" placeholder="" required>Current Rate</label>
                                    </div>
                                    <div class="group transition tip-parent animated fadeInUp">
                                        <input type="number" name="unit" id="unit" class="form-input unit transition" required placeholder="" oninput="autoCalculate();" />
                                        <label class="form-input-placeholder" for="unit" placeholder="" required>Unit Price (as is on website)</label>
                                        <span style="font-size:12px;color:red;padding-top:10px;margin-left:10px;" class="unit-msg transition"></span>
                                    </div>

                                    <!--span style="padding:5px;margin-bottom:10px;min-width">Please note that prices are quoted in Nigeian Naira (NGN/<span class="naira">N</span>)</span-->

                                    <input type="hidden" class="id" value="'.$row['id'].'" />
                                    <input type="hidden" class="user_id" value="'.$row['user_id'].'" />
                                    <input type="hidden" class="qty" value="'.$row['quantity'].'" />
                                    <div class="group transition tip-parent animated fadeInUp">
                                        <input type="number" class="form-input cumulative transition" required placeholder="" oninput="autoCalculate();" disabled />
                                        <label class="form-input-placeholder" for="cumulative" placeholder="" required>Cummulative Price in NGN</label>
                                        <span style="font-size:12px;color:red;padding-top:10px;margin-left:10px;" class="cumulative-msg transition"></span>
                                    </div>
                                    <div class="group transition tip-parent animated fadeInUp">
                                        <input type="number" name="vat" id="vat" class="form-input vat transition" required placeholder="" oninput="autoCalculate();" value="'.$row['vat'].'" />
                                        <label class="form-input-placeholder" for="vat">VAT (%)</label>
                                        <span style="font-size:12px;color:red;padding-top:10px;margin-left:10px;" class="vat-msg transition"></span>
                                    </div>
                                    <div class="group transition tip-parent animated fadeInUp">
                                        <input type="number" name="shipping" id="shipping" class="form-input shipping transition" required placeholder="" disabled value="'.$this->get_shipping_rate($row['shipping_locale']).'" />
                                        <label class="form-input-placeholder" for="shipping">Shipping Rate</label>
                                        <span style="font-size:12px;color:red;padding-top:10px;margin-left:10px;" class="shipping-msg transition"></span>
                                    </div>
                                    <div class="group transition tip-parent animated fadeInUp">
                                        <input type="number" name="handling" id="handling" class="form-input handling transition" required placeholder="" disabled value="'.$this->convert_handling().'" />
                                        <label class="form-input-placeholder" for="handling">Handling Fee</label>
                                        <span style="font-size:12px;color:red;padding-top:10px;margin-left:10px;" class="handling-msg transition"></span>
                                    </div>
                                    <div class="group transition tip-parent animated fadeInUp">
                                        <input type="number" name="total" id="total" class="form-input total transition" disabled placeholder="" required value="'.$row['total_cost'].'" />
                                        <label class="form-input-placeholder" for="total" placeholder="" required>Total amount</label>
                                        <span style="font-size:12px;color:red;padding-top:10px;margin-left:10px;" class="total-msg transition"></span>
                                    </div>
                                    <div class="group transition tip-parent animated fadeInUp">
                                        <input type="number" name="delivery" id="delivery" class="form-input delivery transition" required placeholder="" value="'.$row['delivery'].'" />
                                        <label class="form-input-placeholder" for="delivery" placeholder="" required>Total days of delivery</label>
                                        <span style="font-size:12px;color:red;padding-top:10px;margin-left:10px;" class="delivery-msg transition"></span>
                                    </div>
                                </div>
                                <div>
                                    <button class="button modal-button button-success button-rounded transition admin-login" onclick="update_order();">&nbsp;&nbsp; UPDATE ORDER &nbsp;&nbsp;<i class="transition lni-angle-double-right"></i></button>
                                </div>
                            </div>';
                }
            }

            mysqli_close($cn);
        }
        private function get_shipping_rate($locale)
        {
            $database = new Database();

            $cn = $database->getConnection();
            $region = explode(' ',$locale);
            $sql = "select * from shipping where lga='$region[0]' and state='$region[1]' and country='$region[2]'";

            if($query = $cn->query($sql))
            {
                while($row = $query->fetch_assoc())
                {
                    return $row['amount'];
                }
            }
            else
            {
                return 0;
            }

            mysqli_close($cn);
        }
        private function convert_handling()
        {
            $database = new Database();

            $cn = $database->getConnection();
            $sql = "select * from currency where currency='USD'";

            if($query = $cn->query($sql))
            {
                while($row = $query->fetch_assoc())
                {
                    return ($row['rate']*10);
                }
            }
            mysqli_close($cn);
        }
        function update_custom_order($post) //Enables custom order personnel to update prices and delivery period for the order
        {
            $database = new Database();

            $cn = $database->getConnection();
            $id = $post['id'];
            $personnel = $_SESSION['id'];
            $currency = $post['currency'];
            $rate = $post['rate'];
            $unit = $post['unit'];
            $cumulative = $post['cumulative'];
            $vat = $post['vat'];
            $shipping = $post['shipping'];
            $handling = $post['handling'];
            $total = $post['total'];
            $delivery = $post['delivery'];
            $date = date('Y-m-d');

            $sql = "update custom_order set status='Treated', personel='$personnel', currency='$currency', rate='$rate', src_price='$unit', calculated_price='$cumulative', vat='$vat',shipping='$shipping',handling='$handling', total_cost='$total', delivery='$delivery', date_treated='$date' where id='$id'";

            if($query=$cn->query($sql))
            {
                return $this->generate_invoice($post);
            }
            else
            {
                return 'error'; //mysqli_error($cn);
            }

            mysqli_close($cn);
        }
        function generate_invoice($post)
        {
            require_once('../../../apps/MPDF60/mpdf.php');

            $database = new Database();

            $cn = $database->getConnection();

            $id = $post['id'];
            $vat = $post['vat'];
            $shipping = $post['shipping'];
            $handling = $post['handling'];
            $total = $post['total'];
            $cumulative = $post['cumulative'];
            $sql = "select * from custom_order where id='$id'";
            $product = '';
            $colour = '';
            $size = 0;
            $qty = 0;
            $uid = 0;

            if($query = $cn->query($sql))
            {
                while($row = $query->fetch_assoc())
                {
                    $product = $row['product'];
                    $colour = $row['color'];
                    $size = $row['size'];
                    $qty = $row['quantity'];
                    $uid = $row['user_id'];
                }
            }
            
            $unit = ($post['cumulative']/$qty);
            $vat_rate = (($vat/100)*$cumulative);
            $pdf = new mPDF();
            $html = '<div>
                        <div style="text-align:center;width:100%;"><img class="transition" src="../../../assets/images/mc2d_logo.png" alt="My Cart 2 Door" style="width:20% !important;" /></div>
                        <h3 style="text-align:center;font-size:25px;color:#aaa;"><i class="icon icon-basic-sheet-txt"></i>&nbsp;Customer Invoice</h3>
                        <div>
                            <div>To: '.$this->fetch_customer_details($uid, 'title').' '.$this->fetch_customer_details($uid, 'firstname').' '.$this->fetch_customer_details($uid, 'lastname').'</div>
                            <div>Email: '.$this->fetch_customer_details($uid, 'email').'</div>
                            <div>Phone: '.$this->fetch_customer_details($uid, 'mobile').'</div>
                            <div>Tracking ID: M2D-CO-'.$id.'</div>
                            <div>Date: '.date('Y-m-d').'</div>
                        </div>
                        <table style="margin-top:50px;width:100%;border:thin solid #ddd;padding:0;">
                            <thead>
                                <tr>
                                    <th style="padding:10px;border-right:thin solid #ddd;border-bottom:thin solid #ddd;">Product</th>
                                    <th style="padding:10px;border-right:thin solid #ddd;border-bottom:thin solid #ddd;">Colour</th>
                                    <th style="padding:10px;border-right:thin solid #ddd;border-bottom:thin solid #ddd;">Size</th>
                                    <th style="padding:10px;border-right:thin solid #ddd;border-bottom:thin solid #ddd;">Quantity</th>
                                    <th style="padding:10px;border-right:thin solid #ddd;border-bottom:thin solid #ddd;">Unit Price</th>
                                    <th style="padding:10px;border-bottom:thin solid #ddd;">Cumulative Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="padding:10px;border-right:thin solid #ddd;border-bottom:thin solid #ddd;">'.$product.'</td>
                                    <td style="padding:10px;border-right:thin solid #ddd;border-bottom:thin solid #ddd;">'.$colour.'</td>
                                    <td style="padding:10px;border-right:thin solid #ddd;border-bottom:thin solid #ddd;">'.$size.'</td>
                                    <td style="padding:10px;border-right:thin solid #ddd;border-bottom:thin solid #ddd;">'.$qty.'</td>
                                    <td style="padding:10px;border-right:thin solid #ddd;border-bottom:thin solid #ddd;">'.number_format($unit,2).'</td>
                                    <td style="padding:10px;border-bottom:thin solid #ddd;">'.number_format($cumulative,2).'</td>
                                </tr>
                                <tr>
                                    <td colspan="5" style="padding:10px;border-right:thin solid #ddd;border-bottom:thin solid #ddd;text-align:right;"><strong>VAT ('.$vat.'%)</strong></td>
                                    <td style="padding:10px;border-bottom:thin solid #ddd;"><strong>'.number_format($vat_rate,2).'</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="5" style="padding:10px;border-right:thin solid #ddd;border-bottom:thin solid #ddd;text-align:right;"><strong>Shipping</strong></td>
                                    <td style="padding:10px;border-bottom:thin solid #ddd;"><strong>'.number_format($shipping,2).'</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="5" style="padding:10px;border-right:thin solid #ddd;border-bottom:thin solid #ddd;text-align:right;"><strong>Handling Charges</strong></td>
                                    <td style="padding:10px;border-bottom:thin solid #ddd;"><strong>'.number_format($handling,2).'</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="5" style="padding:10px;border-right:thin solid #ddd;text-align:right;"><strong>Grand Total</strong></td>
                                    <td style="padding:10px;"><strong>'.number_format($total,2).'</strong></td>
                                </tr>
                            </tbody>
                        </table>
                        <div style="margin-top:20px;">
                            <h3>Please Note:</h3>
                            <ul>
                                <li>That the delivery period for this product will take up to '.$post['delivery'].' days, commencing from the payment date confirmation.</li>
                                <li>All amounts within this document are quoted in Nigerian Naira (NGN)</li>
                            </ul>
                        </div>
                    </div>';
            $outp = '../../../Custom_Order_Invoice_ M2D-CO-'.$id.'.pdf';

            $pdf->WriteHTML($html);
            $pdf->SetDisplayMode('fullpage');
            $pdf->output($outp,'F');

            $mess = 'Dear '.$this->fetch_customer_details($uid, 'lastname').',';
            $mess .= '<br>Thank you for your interest in our service, Please find attached copy of your invoice with this mail.';
            $mess .= '<br><br>Best regards';
            $mess .= '<br><strong>MyCart2Door Team</strong>';

            if(sendmailWithAttachment($this->fetch_customer_details($uid, 'lastname'), $this->fetch_customer_details($uid, 'email'), 'MyCart2Door Custom Order Invoice', $mess, $outp)==1)
            {
                unlink ($outp);
                return 'done';
            }
            else
            {
                return 'error';
            }

            mysqli_close($cn);
        }
        private function fetch_customer_details($id, $col)
        {
            $database = new Database();

            $cn = $database->getConnection();

            $sql = "select * from users where id='$id' limit 1";

            if($query = $cn->query($sql))
            {
                while($row = $query->fetch_assoc())
                {
                    return $row[$col];
                }
            }

            mysqli_close($cn);
        }
        function filter_order_list($id, $status)
        {
            $database = new Database();

            $content = '';
            $cn = $database->getConnection();
            $sql = "select * from custom_order where user_id='$id' and status='$status'";

            if($query=$cn->query($sql))
            {
                while($row=$query->fetch_assoc())
                {
                    switch($status)
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

                return $content;
            }

            mysqli_close($cn);
        }
        private function return_set_currencies()
        {
            $database = new Database();

            $content = '<option value="" selected disabled>Choose Currency</option>';
            $cn = $database->getConnection();
            $sql = "select * from currency";

            if($query = $cn->query($sql))
            {
                while($row = $query->fetch_assoc())
                {
                    $content .= '<option value="'.$row['id'].'">'.$row['currency'].'</option>';
                }
            }

            return $content;
        }
    }
?>
