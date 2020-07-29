<?php
    class SigninView
    {
        function getLoginForm()
        {
            return '<div class="animated fadeInUp" style="min-width:100%;">
                        <div class="group transition tip-parent animated fadeInUp">
                            <input type="text" name="uname" id="uname" class="form-input uname" required placeholder="" />
                            <label class="form-input-placeholder" for="uname" placeholder="" required>Username</label>
                            <span style="font-size:12px;color:red;padding-top:10px;margin-left:10px;" class="uname-msg transition"></span>
                        </div>
                        <div class="group transition tip-parent animated fadeInUp">
                            <input type="password" name="passw" id="passw" class="form-input passw" required placeholder="" />
                            <label class="form-input-placeholder" for="passw" placeholder="" required>Password</label>
                            <span style="font-size:12px;color:red;padding-top:10px;margin-left:10px;" class="passw-msg transition"></span>
                        </div>
                    </div>';
        }
        function getSigninForm()
        {
            return "<div class='modal-wrapper transition animated ZoomInUp'>
                        <div class='modal-head animated fadeInUp'>
                            <span class='lni-close modal-close transition tip-parent' title='Close window'></span>
                            <img class='transition' src='assets/images/mc2d_logo.png' alt='My Cart 2 Door Signin' />
                            <h3 style='margin-top:20px;margin-bottom:0;'>
                                <span class='icon-basic-case title'>&nbsp;&nbsp;Sign In</span>
                            </h3>
                            <h3 style='font-size:18px;margin-top:0;'>Continue to MyCart2Door</h3>
                        </div>
                        <div class='modal-body transition animated fadeInUp'>
                            <div class='group transition tip-parent animated fadeInUp'>
                                <input type='text' name='uname' id='uname' class='form-input uname' required placeholder='' />
                                <label class='form-input-placeholder' for='uname' placeholder='' required>Username</label>
                                <span style='font-size:12px;color:red;padding-top:10px;margin-left:10px;' class='uname-msg transition'></span>
                            </div>
                            <div class='group transition tip-parent animated fadeInUp'>
                                <input type='password' name='passw' id='passw' class='form-input passw' required placeholder='' />
                                <label class='form-input-placeholder' for='passw' placeholder='' required>Password</label>
                                <span style='font-size:12px;color:red;padding-top:10px;margin-left:10px;' class='passw-msg transition'></span>
                            </div>
                        </div>
                        <div class='modal-footer animated fadeInUp'>
                            <button class='button modal-button button-success button-rounded transition login'>&nbsp;&nbsp; CONTINUE WITH SIGN IN &nbsp;&nbsp;<i class='transition lni-angle-double-right'></i></button>
                        </div>
                    </div>";
        }
        function loadDashboardContent()
        {
            return '<div class="animated fadeInDown transition">
                        <div class="profile-container transition drop-shadow">
                            <div class="user-avatar transition">
                                <div>
                                    <span>'.$_SESSION['fname'][0].'</span>
                                    <span>'.$_SESSION['lname'][0].'</span>
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
                        <div class="content-container transition">
                            <div class="getting-started drop-shadow transition"></div>
                            <div class="custom-orders drop-shadow transition"></div>
                            <div class="wallet drop-shadow transition"></div>
                            <div class="order-histroy drop-shadow transition"></div>
                        </div>
                    </div>';
        }
        function gettingStartedContent()
        {
            return '<div class="animated fadeInDown">
                        <div style="display:flex;">
                            <span><i class="lni-layers" style="font-size:25px"></i></span>&nbsp;&nbsp;
                            <h4>Getting Started</h4>
                        </div>
                        <div class="border"><div></div></div>
                        <div>
                            <div>Find out how <strong>My cart 2 door</strong> works in 3 easy steps!.</div>
                            <div style="margin-top:10px;">
                                <div><strong>Step 1:</strong> Shop at more than 50,000 overseas retail online stores.</div>
                                <div><strong>Step 2:</strong> Ship securely with no hidden membership fees.</div>
                                <div><strong>Step 3:</strong> Save more than 50% off compare to your local retail price.</div>
                            </div>
                        </div>
                        <div style="margin-top:10px;">
                            <div>Share on: <i class="lni-facebook-original" style="font-size:20px;color:blue;cursor:pointer" title="Facebook"></i> <i class="lni-twitter-original" style="font-size:20px;color:skyblue;cursor:pointer;" title="Twitter"></i></div>
                        </div>
                    </div>';
        }
        function OrderHistory()
        {
            return '<div class="animated fadeInDown transition">
                        <div style="display:flex;">
                            <span><i class="lni-credit-cards" style="font-size:25px"></i></span>&nbsp;&nbsp;
                            <h4>Order Histroy <span class="h-notify"></span></h4>
                        </div>
                        <div class="border"><div></div></div>
                        <div>
                            <div style="margin-bottom:20px;">If you want to know the list of the transactions from your MyCart2Door Webcard, click on the button provided below.</div>
                            <div class="btn-dashed history-btn transition">TRANSACTION HISTORY &nbsp;<i class="lni-angle-double-right"></i></div>
                        </div>
                    </div>';
        }
        function transaction_history()
        {
            return "<div class='modal-wrapper transition animated ZoomInUp'>
                        <div class='modal-head animated fadeInUp'>
                            <span class='lni-close modal-close transition tip-parent' title='Close window'></span>
                            <img class='transition' src='../assets/images/mc2d_logo.png' alt='My Cart 2 Door' />
                            <h3 style='margin-top:20px;margin-bottom:0;'>
                                <i class='lni-credit-cards title'></i>&nbsp;Custom Orders
                            </h3>
                            <h3 style='font-size:18px !important;margin-top:-8px !important;'>Transactions History</h3>
                        </div>
                        <div class='modal-body transition animated fadeInUp'>
                            <div class='order-history transition' style='min-width:100%;'></div>
                        </div>
                        <!--div class='modal-footer animated fadeInUp'>
                            <button class='button modal-button button-success button-rounded transition login'>&nbsp;&nbsp; CONTINUE WITH SIGN IN &nbsp;&nbsp;<i class='transition lni-angle-double-right'></i></button>
                        </div-->
                    </div>";
        }
        function customOrderForm()
        {
            $con = '<div class="modal-wrapper transition animated ZoomInUp">
                        <div class="modal-head animated fadeInUp">
                            <span class="lni-close modal-close transition tip-parent" title="Close window"></span>
                            <img class="transition" src="../assets/images/mc2d_logo.png" alt="Mycart2Door Signup" />
                            <h3 style="margin-top:20px;margin-bottom:0;">
                                <i class="lni-package title" style="font-size:26px;"></i>&nbsp;&nbsp;Custom Order
                            </h3>
                            <span style="font-size:18px;margin-top:-15px;">Fill out the form</span>
                        </div>
                        <div class="modal-body transition animated fadeInUp">
                            <div class="order-form-caption transition">
                                <div>Store Name <span style="color:red;">*</span></div>
                                <div>Product Name <span style="color:red;">*</span></div>
                                <div>Product URL <span style="color:red;">*</span></div>
                                <div>Colour</div>
                                <div>Size <span style="color:red;">*</span></div>
                                <div>Product ID</div>
                                <div>Quantity <span style="color:red;">*</span></div>
                            </div>';
            
                        for($i=1;$i<16;$i++)
                        {
                            $con .= '<div class="order-form">
                                        <div class="row-'.$i.'">
                                            <input type="text" data-name="store" class="input-normal store-'.$i.'" name="order[]" required placeholder="" />
                                        </div>
                                        <div class="row-'.$i.'">
                                            <input type="text" data-name="product" class="input-normal product-'.$i.'" name="order[]" required placeholder="" />
                                        </div>
                                        <div class="row-'.$i.'">
                                            <input type="text" data-name="prod-url" name="order[]" class="input-normal prod-url-'.$i.'" required placeholder="" />
                                        </div>
                                        <div class="row-'.$i.'">
                                            <input type="text" data-name="colour" class="input-normal colour-'.$i.'" name="order[]" placeholder="" />
                                        </div>
                                        <div class="row-'.$i.'">
                                            <input type="text" data-name="size" class="input-normal size-'.$i.'" name="order[]" placeholder="" required />
                                        </div>
                                        <div class="row-'.$i.'">
                                            <input type="text" data-name="item-id" class="input-normal item-id-'.$i.'" name="order[]" placeholder="" />
                                        </div>
                                        <div class="row-'.$i.'">
                                            <input type="number" data-name="qty" class="input-normal qty-'.$i.'" name="order[]" placeholder="" required/>
                                        </div>
                                    </div>';
                        }
            
            $con .= '   </div>
                        <div class="modal-footer animated fadeInUp">
                            <button class="button modal-button button-success button-rounded transition order">&nbsp;&nbsp; SUBMIT ORDER &nbsp;&nbsp;<i class="transition lni-angle-double-right"></i></button>
                        </div>
                    </div>';
            
            return $con;
        }
        function getDoor2DoorLocationForm()
        {
            return '<div class="animated fadeInUp" style="min-width:100%;">
                        <div class="flex-deflex">
                            <div class="group transition tip-parent animated fadeInUp">
                                <select name="country" class="form-input country" id="country"></select>
                                <label class="form-input-placeholder" for="country" placeholder="" required>Country of Shipping</label>
                                <span style="font-size:12px;color:red;padding-top:10px;margin-left:10px;" class="country-msg transition"></span>
                            </div>
                            <div class="group transition tip-parent animated fadeInUp">
                                <select name="state" class="form-input state" id="state" onchange="loadLGAContent()"></select>
                                <label class="form-input-placeholder" for="state" placeholder="" required>State of Shipping</label>
                                <span style="font-size:12px;color:red;padding-top:10px;margin-left:10px;" class="state-msg transition"></span>
                            </div>
                            <div class="group transition tip-parent animated fadeInUp">
                                <select name="lga" class="form-input lga" id="lga"></select>
                                <label class="form-input-placeholder" for="lga" placeholder="" required>Local Governmant Area of Shipping</label>
                                <span style="font-size:12px;color:red;padding-top:10px;margin-left:10px;" class="lga-msg transition"></span>
                            </div>
                        </div>
                        <div class="group transition tip-parent animated fadeInUp">
                            <input type="text" name="address" id="address" class="form-input address" required placeholder="" />
                            <label class="form-input-placeholder" for="address" placeholder="" required>Shipping Address</label>
                            <span style="font-size:12px;color:red;padding-top:10px;margin-left:10px;" class="address-msg transition"></span>
                        </div>
                    </div>';
        }
        
        
        function reportErrorMessage($message)
        {
            return '<div class="modal-wrapper transition animated ZoomInUp">
                        <div class="modal-head animated fadeInUp">
                            <span class="lni-close modal-close transition tip-parent" title="Close window"></span>
                            <img class="transition" src="assets/images/mc2d_logo.png" alt="Mycart2Door Signup" />
                            <h3 style="margin-top:20px;margin-bottom:0;">
                                <i class="lni-package title" style="font-size:26px;"></i>&nbsp;&nbsp;Custom Order
                            </h3>
                            <span style="font-size:18px;margin-top:-15px;">Sign In/Sign Up</span>
                        </div>
                        <div class="modal-body transition animated fadeInUp">
                            <div class="error transition animated fadeInDown">
                                <div><i class="icon icon-basic-ban"></i></div>
                                <div>'.$message.'</div>
                            </div>
                        </div>
                        <!--div class="modal-footer animated fadeInUp">
                            <button class="button modal-button button-success button-rounded transition signin">&nbsp;&nbsp; PROCEED WITH SIGN IN &nbsp;&nbsp;<i class="transition lni-angle-double-right"></i></button>
                        </div-->
                    </div>';
        }
    }
?>