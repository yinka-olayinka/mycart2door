<?php
    include_once('../inc/headers.php');

    $signupV = new SignupView();
    $signupM = new SignupModel();
    $signinV = new SigninView();
    $signinM = new SigninModel();
    $admin_views = new Admin_Views();
    $admin_logic = new Admin_Logic();


    if(isset($_POST['request']))
    {
        switch($_POST['request'])
        {
            case 'signup':
                echo $signupV->getSignupForm();
                break;
            case 'signin':
                echo $signinV->getSigninForm();
                break;
            case 'login':
                echo $signinV->getLoginForm();
                break;
        }
    }
    if(isset($_POST['verify']))
    {
        echo $signupV->verifyUserContact($_POST);
    }
    if(isset($_POST['validate']))
    {
        echo $signupV->verifyPhoneContact($_POST);
    }
    if(isset($_POST['register']))
    {
        if($signupM->register($_POST)=='true')
        {
            echo $signupV->registrationSuccessful();
        }
    }
    if(isset($_POST['product']))
    {
        echo $product->getProductPage();
    }

    if(isset($_SERVER['CONTENT_TYPE']))
    {
        $data = file_get_contents('php://input');
        $objs = json_decode($data);
        $uname = @$objs->uname;
        $passw = md5(@$objs->passw);  
        
        echo $signinM->loginWithCredentials($uname, $passw);
    }



    /*
    Customer's Dashboard content after successful login
    */

    if(isset($_POST['dashboard']))
    {
        echo $signinV->loadDashboardContent();
    }
    if(isset($_POST['profile']))
    {
        echo $signinM->loadProfileContent();
    }
    if(isset($_POST['getting_started']))
    {
        echo $signinV->gettingStartedContent();
    }
    if(isset($_POST['custom_order']))
    {
        echo $signinM->customOrder();
    }
    if(isset($_POST['wallet']))
    {
        echo $signinM->userWallet();
    }
    if(isset($_POST['purchase']))
    {
        echo $signinV->OrderHistory();
    }
    if(isset($_POST['create_wallet']))
    {
        echo $signinM->createUserWallet();
    }
    if(isset($_POST['fetch_wallet_info']))
    {
        echo $signinM->fetch_wallet_info();
    }
    if(isset($_POST['update_customer_wallet']))
    {
        echo $signinM->update_customer_wallet($_POST);
    }
    if(isset($_POST['transaction_history']))
    {
        echo $signinV->transaction_history();
    }
    if(isset($_POST['load_orders']))
    {
        echo $signinM->load_orders();
    }

    if(isset($_POST['custom_order_form']))
    {
        if(!@$_SESSION['session'])
        {
            echo $signinV->reportErrorMessage('Before you can perform this operation, you need to sign in using your login credentials if you already have an account with us or register as new');
        }
        else
        {
            echo $signinV->customOrderForm();
        }
    }
    if(isset($_POST['submit_custom_order']))
    {
        $content = explode('|', $_POST['submit_custom_order']);
        $result = '';

        for($i=0;$i<count($content);$i++)
        {
            $post = array_filter(explode(',', $content[$i]));
            $size = @$post[4];

            $result = $signinM->submitCustomOrder(@$post[0],@$post[1],@$post[2],@$post[3],$size,addslashes(@$post[5]),@$post[6]);
        }

        echo $result;
    }
    if(isset($_POST['door2door_Location_Form']))
    {
        echo $signinV->getDoor2DoorLocationForm();
    }
    if(isset($_POST['submit_shipping_form']))
    {
        echo $signinM->submit_shipping_form($_POST);
    }
    if(isset($_POST['get_order_response']))
    {
        echo $signinM->get_order_response();
    }
    if(isset($_POST['get_my_order_details']))
    {
        echo $signinM->get_my_oder_details($_POST['get_my_order_details']);
    }



    /**
    * This blocks is for Administrator Dashboard to manage bacnkend users and customer's requests
    * @Administrator Backend
    */
    if(isset($_POST['backend']))
    {
        if(!isset($_SESSION['backend']))
        {
            echo 'login';
        }
        else
        {
            echo $admin_views->adminDashboard();
        }
    }
    if(isset($_POST['admin_login']))
    {
        echo $admin_views->signinForm();
    }
    if(isset($_POST['admin_signin']))
    {
        $uname = $_POST['username'];
        $passw = md5($_POST['password']);

        echo $admin_logic->confirmAdminCredentials($uname, $passw);
    }
    if(isset($_POST['admin_profile']))
    {
        echo $admin_logic->loadAdminProfile();
    }
    if(isset($_POST['finance_panel_manager']))
    {
        echo $admin_views->finance_panel_manager();
    }
        if(isset($_POST['manageCustomOrders']))
    {
        echo $admin_views->manageCustomOrders();
    }
    if(isset($_POST['user_list']))
    {
        echo $admin_logic->loadUserList();
    }
    if(isset($_POST['new_user_form']))
    {
        echo $admin_views->newBackendUserForm();
    }
    if(isset($_POST['insert_backend_user']))
    {
        echo $admin_logic->insertBackendUser($_POST);
    }
    if(isset($_POST['edit_user_profile']))
    {
        $id = $_POST['edit_user_profile'];

        echo $admin_logic->edit_user_profile($id);
    }
    if(isset($_POST['update_backend_user']))
    {
        echo $admin_logic->update_backend_user($_POST);
    }
    if(isset($_POST['suspend_user_profile']))
    {
        echo $admin_logic->suspend_user_profile($_POST['suspend_user_profile']);
    }
    if(isset($_POST['unsuspend_user_profile']))
    {
        echo $admin_logic->unsuspend_user_profile($_POST['unsuspend_user_profile']);
    }
    if(isset($_POST['delete_user_profile']))
    {
        echo $admin_logic->delete_user_profile($_POST['delete_user_profile']);
    }
    if(isset($_POST['admin_Custom_Order_Content']))
    {
        echo $admin_views->userDashboard();
    }


    /**
     * Finance functions and procedures that manages backend
     * @Finance Backend
     */
     if(isset($_POST['finance_management_form']))
    {
        echo $admin_views->finance_management_form();
    }
    if(isset($_POST['currency_pane_content']))
    {
        echo $admin_views->currency_pane_content();
    }
    if(isset($_POST['currency_manager_luncher']))
    {
        echo $admin_logic->currency_manager_luncher();
    }
    if(isset($_POST['get_current_set_rate']))
    {
        echo $admin_logic->get_current_set_rate($_POST['get_current_set_rate']);
    }
    if(isset($_POST['update_currency_rate']))
    {
        $id = $_POST['rate_id'];
        $rate = $_POST['update_currency_rate'];

        echo $admin_logic->update_currency_rate($id, $rate);
    }
    if(isset($_POST['delivery_pane_content']))
    {
        echo $admin_views->delivery_pane_content();
    }
    if(isset($_POST['manage_Delivery_location']))
    {
        echo $admin_logic->manage_Delivery_location();
    }
    if(isset($_POST['provide_Delivery_rate']))
    {
        echo $admin_views->provide_Delivery_rate();
    }
    if(isset($_POST['update_shipping_rate']))
    {
        echo $admin_logic->update_shipping_rate($_POST);
    }
    if(isset($_POST['load_shipping_list']))
    {
        echo $admin_logic->load_shipping_list();
    }
    if(isset($_POST['refund_pane_content']))
    {
        echo $admin_views->refund_pane_content();
    }


    /**
    * This block is for custom order personnel dashboard to manage all customer's orders
    * @Custom Order Personnel Backend
    */
    
    if(isset($_POST['users_with_order']))
    {
        echo $admin_logic->users_with_order();
    }
    if(isset($_POST['load_User_Orders']))
    {
        echo $admin_views->loadUserOrder();
    }
    if(isset($_POST['get_user_order_list']))
    {
        echo $admin_logic->get_user_order_list($_POST['get_user_order_list']);
    }
    if(isset($_POST['get_user_oder_details']))
    {
        echo $admin_logic->get_user_oder_details($_POST['get_user_oder_details']);
    }
    if(isset($_POST['update_order']))
    {
        echo $admin_logic->update_custom_order($_POST);
    }
    if(isset($_POST['filter_order_list']))
    {
        if($_POST['delimeter']=='All')
        {
            echo $admin_logic->get_only_order_list($_POST['filter_order_list']);
        }
        else
        {
            echo $admin_logic->filter_order_list($_POST['filter_order_list'], $_POST['delimeter']);
        }
    }



    /**
    * General functions and procedures that manages backend for all users
    * @General Backend
    */
    if(isset($_POST['user_form']))
    {
        echo $admin_views->loadBackendUsersForm();
    }
    if(isset($_POST['backend_content']))
    {
        switch($_SESSION['role'])
        {
            case 'A':
                echo $admin_views->administratorDashboard();
                break;
            default:
                echo $admin_views->userDashboard();
                break;
        }
    }
    if(isset($_POST['backend_users']))
    {
        switch($_SESSION['role'])
        {
            case 'A':
                echo $admin_views->manageBackendUsers();
                break;
            default:
                echo $admin_views->loadUserOrder();
                break;
        }
    }
?>