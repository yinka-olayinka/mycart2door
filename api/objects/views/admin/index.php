<?php
    class Admin_Views
    {
        function signinForm()
        {
            return "<div class='modal-wrapper transition animated ZoomInUp'>
                        <div class='modal-head animated fadeInUp'>
                            <span class='lni-close modal-close transition tip-parent' title='Close window'></span>
                            <img class='transition' src='../assets/images/mc2d_logo.png' alt='My Cart 2 Door Signin' />
                            <h3 style='margin-top:20px;margin-bottom:0;'>
                                <span class='icon-basic-case title'>&nbsp;&nbsp;Sign In</span>
                            </h3>
                            <h3 class='sub-title'>Continue to MyCart2Door</h3>
                        </div>
                        <div class='modal-body transition animated fadeInUp'>
                            <!--div class='report transition'></div-->
                            <div class='group transition tip-parent animated fadeInUp'>
                                <input type='text' name='uname' id='uname' class='form-input uname transition' required placeholder='' />
                                <label class='form-input-placeholder' for='uname' placeholder='' required>Username</label>
                                <span style='font-size:12px;color:red;padding-top:10px;margin-left:10px;' class='uname-msg transition'></span>
                            </div>
                            <div class='group transition tip-parent animated fadeInUp'>
                                <input type='password' name='passw' id='passw' class='form-input passw transition' required placeholder='' />
                                <label class='form-input-placeholder' for='passw' placeholder='' required>Username</label>
                                <span style='font-size:12px;color:red;padding-top:10px;margin-left:10px;' class='passw-msg transition'></span>
                            </div>
                        </div>
                        <div class='modal-footer animated fadeInUp'>
                            <button class='button modal-button button-success button-rounded transition admin-login'>&nbsp;&nbsp; CONTINUE WITH SIGN IN &nbsp;&nbsp;<i class='transition lni-angle-double-right'></i></button>
                        </div>
                    </div>";
        }

        /**
        * @Administrator class methods
        */
        function adminDashboard()
        {
            return '<div class="animated fadeInDown transition">
                        <div class="profile-content transition drop-shadow">
                            <div class="user-avatar transition">
                                <div>
                                    <span>'.$_SESSION['fname'][0].'</span>
                                    <span>'.$_SESSION['sname'][0].'</span>
                                </div>
                            </div>
                            <div class="user-info transition">
                                <div class="transition">
                                    <div class="transition"></div>
                                    <div class="transition" style="display:flex;align-item:flex-start;"></div>
                                    <div class="transition" style="display:flex;align-item:flex-start;"></div>
                                    <div class="transition" style="display:flex;align-item:flex-start;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="backend-container transition">
                        </div>
                    </div>';
        }
        function administratorDashboard()
        {
            return '<div class="backend-users drop-shadow transition animated fadeInDown"></div>
                    <div class="finance drop-shadow transition animated fadeInDown"></div>
                    <div class="cust-order drop-shadow transition animated fadeInDown"></div>
                    <div class="drop-shadow transition animated fadeInDown"></div>';
        }
        function manageBackendUsers() //Function that loads admin backend user sub-panel sections content
        {
            return '<div class="animated fadeInDown">
                        <div style="display:flex;">
                            <span><i class="lni-users" style="font-size:25px"></i></span>&nbsp;&nbsp;
                            <h4>Backend Users</h4>
                        </div>
                        <div class="border"><div></div></div>
                        <div>
                            <div>Create, view, update, delete, set permissions and more for application backend users</div>
                            <div style="margin-top:10px;">
                                <div>Use the button below to manage backend users as listed above for this application.</div>
                            </div>
                        </div>
                        <div style="margin-top:10px;">
                            <div class="btn-dashed manage-users transition">MANAGE BACKEND USERS &nbsp;<i class="lni-angle-double-right"></i></div>
                        </div>
                    </div>';
        }
        function finance_panel_manager()
        {
            return '<div class="animated fadeInDown">
                        <div style="display:flex;">
                            <span>
                                <i class="lni-target-revenue" style="font-size:25px"></i>
                            </span>&nbsp;&nbsp;
                            <h4>Finance</h4>
                        </div>
                        <div class="border"><div></div></div>
                        <div>
                            <div>Set, refund and manage currency that will be used by this application for conversion</div>
                            <div style="margin-top:10px;">
                                <div>Use the button below to manage application currency as stated above for conversion.</div>
                            </div>
                        </div>
                        <div style="margin-top:10px;">
                            <div class="btn-dashed manage-finance transition">MANAGE CURRENCIES &nbsp;<i class="lni-angle-double-right"></i></div>
                        </div>
                    </div>';
        }
        function manageCustomOrders() //Function that loads admin backend customer order sub-panel section
        {
            return '<div class="animated fadeInDown">
                        <div style="display:flex;">
                            <span><i class="lni-package" style="font-size:25px"></i></span>&nbsp;&nbsp;
                            <h4>Custom Orders</h4>
                        </div>
                        <div class="border"><div></div></div>
                        <div>
                            <div>Access customer custom orders and perform the same custom order role as assigned to personnel.</div>
                            <div style="margin-top:10px;">
                                <div>Use the button below to manage Customer\'s custom orders.</div>
                            </div>
                        </div>
                        <div style="margin-top:10px;">
                            <div class="btn-dashed manage-orders transition" onclick="adminCustomOrderContent();">MANAGE CUSTOM ORDERS &nbsp;<i class="lni-angle-double-right"></i></div>
                        </div>
                    </div>';
        }
        function loadBackendUsersForm()
        {
            return "<div class='modal-wrapper transition animated ZoomInUp'>
                        <div class='modal-head animated fadeInUp'>
                            <span class='lni-close modal-close transition tip-parent' title='Close window'></span>
                            <img class='transition' src='../assets/images/mc2d_logo.png' alt='My Cart 2 Door Signin' />
                            <h3 style='margin-top:20px;margin-bottom:0;'>
                                <span class='title'><i class='lni-users' style='font-size:32px;'></i>&nbsp;Backend Users</span>
                            </h3>
                            <h3 class='sub-title'>Manage Backend Users</h3>
                        </div>
                        <div class='modal-body transition animated fadeInUp'>
                            <div class='transition animated fadeInUp backend-user-form'>
                            </div>
                        </div>
                        <div class='modal-footer animated fadeInUp'>
                            <button class='button modal-button button-success button-rounded transition create'>CREATE USER PROFILE &nbsp;&nbsp;<i class='transition lni-angle-double-right'></i></button>
                        </div>
                    </div>";
        }
        function newBackendUserForm()
        {
            return '<div class="transition animated fadeInDown" style="min-width:100%;">
                        <div class="group transition tip-parent animated fadeInUp">
                            <input type="text" name="fname" id="fname" class="form-input fname transition" required placeholder="" />
                            <label class="form-input-placeholder" for="fname" placeholder="" required>First name</label>
                            <span style="font-size:12px;color:red;padding-top:10px;margin-left:10px;" class="fname-msg transition"></span>
                        </div>
                        <div class="group transition tip-parent animated fadeInUp">
                            <input type="text" name="sname" id="sname" class="form-input sname transition" required placeholder="" />
                            <label class="form-input-placeholder" for="sname" placeholder="" required>Surname</label>
                            <span style="font-size:12px;color:red;padding-top:10px;margin-left:10px;" class="sname-msg transition"></span>
                        </div>
                        <div class="group transition tip-parent animated fadeInUp">
                            <input type="text" name="uname transition" id="uname" class="form-input uname" required placeholder="" />
                            <label class="form-input-placeholder" for="uname" placeholder="" required>Username</label>
                            <span style="font-size:12px;color:red;padding-top:10px;margin-left:10px;" class="uname-msg transition"></span>
                        </div>
                        <div class="group transition tip-parent animated fadeInUp">
                            <input type="password" name="passw transition" id="passw" class="form-input passw" required placeholder="" />
                            <label class="form-input-placeholder" for="passw" placeholder="" required>Password</label>
                            <span style="font-size:12px;color:red;padding-top:10px;margin-left:10px;" class="passw-msg transition"></span>
                        </div>
                        <div class="group transition tip-parent animated fadeInUp">
                            <input type="password" name="passc transition" id="passc" class="form-input passc" required placeholder="" />
                            <label class="form-input-placeholder" for="passc" placeholder="" required>Confirm Password</label>
                            <span style="font-size:12px;color:red;padding-top:10px;margin-left:10px;" class="passc-msg transition"></span>
                        </div>
                        <div class="group transition tip-parent animated fadeInUp">
                            <input type="text" name="stream transition" id="stream" class="form-input stream" required placeholder="" />
                            <label class="form-input-placeholder" for="stream" placeholder="" required>Stream</label>
                            <span style="font-size:12px;color:red;padding-top:10px;margin-left:10px;" class="stream-msg transition"></span>
                        </div>
                    </div>';
        }

        /**
         * This block is for custom order personnel dashboard to manage all customer's orders
         * @Custom Order Personnel Backend
         */
        function loadUserOrder()
        {
            return '<div class="transition animated fadeInDown">
                        <div style="display:flex;">
                            <i class="lni-users" style="font-size:36px;"></i>&nbsp;&nbsp;<h4>Customers</h4>
                        </div>
                        <div class="border"><div></div></div>
                    </div>
                    <div class="users-with-order transition animated fadeInDown"></div>';
        }

        /**
        * Finance personnel backend content functions
        * @Finance Personnel Backend
        */
        
        function finance_management_form()
        {
            return '<div class="currency drop-shadow transition animated fadeInDown"></div>
                    <div class="delivery drop-shadow transition animated fadeInDown"></div>
                    <div class="refund drop-shadow transition animated fadeInDown"></div>';
        }
        function currency_pane_content()
        {
            return '<div class="animated fadeInDown">
                        <div style="display:flex;">
                            <span><i class="lni-target-revenue" style="font-size:25px"></i></span>&nbsp;&nbsp;
                            <h4>Currency</h4>
                        </div>
                        <div class="border"><div></div></div>
                        <div>
                            <div>Set and manage currency conversion rate for this application.</div>
                            <div style="margin-top:10px;">
                                <div>Use the button below to manage current currency conversion rate according to the current internation rate.</div>
                            </div>
                        </div>
                        <div style="margin-top:10px;">
                            <div class="btn-dashed manage-currency transition">MANAGE CURRENCY &nbsp;<i class="lni-angle-double-right"></i></div>
                        </div>
                    </div>';
        }
        function delivery_pane_content()
        {
            return '<div class="animated fadeInDown">
                        <div style="display:flex;">
                            <span><i class="lni-ship" style="font-size:25px"></i></span>&nbsp;&nbsp;
                            <h4>Shipping Rate</h4>
                        </div>
                        <div class="border"><div></div></div>
                        <div>
                            <div>Set and manage shipping location rate for this application.</div>
                            <div style="margin-top:10px;">
                                <div>Use the button below to manage shipping location rate according to each localty.</div>
                            </div>
                        </div>
                        <div style="margin-top:10px;">
                            <div class="btn-dashed manage-delivery transition">MANAGE SHIPPING LOCATION &nbsp;<i class="lni-angle-double-right"></i></div>
                        </div>
                    </div>';
        }
        function provide_Delivery_rate()
        {
            return "<div class='group transition tip-parent animated fadeInUp'>
                        <select class='form-input country transition' id='country'></select>
                        <label class='form-input-placeholder' for='country' placeholder='' required>Country of Shippig</label>
                        <span style='font-size:12px;color:red;padding-top:10px;margin-left:10px;' class='country-msg transition'></span>
                    </div>
                    <div class='group transition tip-parent animated fadeInUp'>
                        <select class='form-input state transition' id='state'></select>
                        <label class='form-input-placeholder' for='state' placeholder='' required>State of Shippig</label>
                        <span style='font-size:12px;color:red;padding-top:10px;margin-left:10px;' class='state-msg transition'></span>
                    </div>
                    <div class='group transition tip-parent animated fadeInUp'>
                        <select class='form-input lga transition' id='lga'></select>
                        <label class='form-input-placeholder' for='lga' placeholder='' required>Local Government of Shippig</label>
                        <span style='font-size:12px;color:red;padding-top:10px;margin-left:10px;' class='lga-msg transition'></span>
                    </div>
                    <div class='group transition tip-parent animated fadeInUp'>
                        <input type='number' name='amount' id='amount' class='form-input amount' required placeholder='' />
                        <label class='form-input-placeholder' for='address' placeholder='' required>Amount</label>
                        <span style='font-size:12px;color:red;padding-top:10px;margin-left:10px;' class='amount-msg transition'></span>
                    </div>";
        }
        function refund_pane_content()
        {
            return '<div class="animated fadeInDown">
                        <div style="display:flex;">
                            <span><i class="lni-investment" style="font-size:25px"></i></span>&nbsp;&nbsp;
                            <h4>Customer Refund</h4>
                        </div>
                        <div class="border"><div></div></div>
                        <div>
                            <div>Set and manage customer refund for this application.</div>
                            <div style="margin-top:10px;">
                                <div>Use the button below to manage customer refund to their wallet.</div>
                            </div>
                        </div>
                        <div style="margin-top:10px;">
                            <div class="btn-dashed manage-refund transition">MANAGE REFUND &nbsp;<i class="lni-angle-double-right"></i></div>
                        </div>
                    </div>';
        }


        /**
         * General functions and procedures that manages backend
         * @General Backend
         */

        function userDashboard()
        {
            return '<div class="transition animated fadeInDown backend-users user-oders drop-shadow"></div>
                    <div class="transition animated fadeInDown user-order-lists drop-shadow"></div>';
        }
    }
?>