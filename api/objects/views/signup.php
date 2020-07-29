<?php

    session_start();

    require_once('../../../apps/key/key.php');
    require_once('../../../apps/mail/mail.php');
    require_once('../../../apps/TextLocal/textlocal.class.php');

    class SignupView
    {
        function getSignupForm()
        {

            return "<div class='modal-wrapper transition animated ZoomInUp'>

                        <div class='modal-head animated fadeInUp'>

                            <span class='lni-close modal-close transition tip-parent' title='Close window'></span>

                            <img class='transition' src='assets/images/mc2d_logo.png' alt='Mycart2Door Signup' />

                            <h3 style='margin-top:20px;margin-bottom:0;'>

                                <span class='icon-basic-case title'>&nbsp;&nbsp;Sign Up</span>

                            </h3>

                            <h3 style='font-size:18px;margin-top:0;'>Continue to MyCart2Door</h3>

                        </div>

                        <div class='modal-body transition animated fadeInUp'>

                            <div class='group transition tip-parent'>

                                <select class='form-input titles'>

                                    <option value='Mr'>Mr.</option>

                                    <option value='Mrs'>Mrs.</option>

                                    <option value='Miss'>Miss.</option>

                                    <option value='Ms'>Ms.</option>

                                    <option value='Master'>Master.</option>

                                </select>

                                <label class='form-input-placeholder' for='title' placeholder='' required>Title</label>

                            </div>

                            <div class='group transition tip-parent transition'>

                                <input type='text' class='form-input fname transition' name='fname' />

                                <label class='form-input-placeholder' for='fname' placeholder='' required>First Name</label>

                                <span style='font-size:12px;color:red;padding-top:10px;margin-left:10px;' class='fname-msg transition'></span>

                            </div>

                            <div class='group transition tip-parent'>

                                <input type='text' class='form-input sname transition' name='sname' />

                                <label class='form-input-placeholder' for='sname' placeholder='' required>Surname/Lastname Name</label>

                                <span style='font-size:12px;color:red;padding-top:10px;margin-left:10px;' class='sname-msg transition'></span>

                            </div>

                            <div class='group transition tip-parent'>

                                <input type='email' class='form-input email transition' name='email' />

                                <label class='form-input-placeholder' for='email' placeholder='' required>Email</label>

                                <span style='font-size:12px;color:red;padding-top:10px;margin-left:10px;' class='email-msg transition'></span>

                            </div>

                            <div class='group transition tip-parent'>

                                <input type='email' class='form-input rmail transition' name='rmail' />

                                <label class='form-input-placeholder' for='rmail' placeholder='' required>Referrer Email</label>

                                <span style='font-size:12px;color:red;padding-top:10px;margin-left:10px;' class='rmail-msg transition'></span>

                            </div>

                            <div class='group transition tip-parent'>

                                <input type='password' class='form-input pwrd transition' name='pwrd' />

                                <label class='form-input-placeholder' for='pwrd' placeholder='' required>Password</label>

                                <span style='font-size:12px;color:red;padding-top:10px;margin-left:10px;' class='pwrd-msg transition'></span>

                            </div>

                            <div class='group transition tip-parent'>

                                <input type='password' class='form-input cpwd transition' name='cpwd' />

                                <label class='form-input-placeholder' for='cpwd' placeholder='' required>Confirm Password</label>

                                <span style='font-size:12px;color:red;padding-top:10px;margin-left:10px;' class='cpwd-msg transition'></span>

                            </div>

                            <div class='group transition tip-parent'>

                                <select class='form-input country transition' id='country' name='country'></select>

                                <label class='form-input-placeholder' for='country' placeholder='' required>Country</label>

                                <span style='font-size:12px;color:red;padding-top:10px;margin-left:10px;' class='country-msg transition'></span>

                            </div>

                            <div class='group transition tip-parent'>

                                <select class='form-input state transition' id='state' name='state'></select>

                                <label class='form-input-placeholder' for='state' placeholder='' required>State</label>

                                <span style='font-size:12px;color:red;padding-top:10px;margin-left:10px;' class='state-msg transition'></span>

                            </div>

                            <div class='group transition tip-parent'>

                                <input type='text' class='form-input city transition' name='city' />

                                <label class='form-input-placeholder' for='city' placeholder='' required>City</label>

                                <span style='font-size:12px;color:red;padding-top:10px;margin-left:10px;' class='city-msg transition'></span>

                            </div>

                            <div class='group transition tip-parent'>

                                <input type='text' class='form-input mobile transition' name='mobile' />

                                <label class='form-input-placeholder' for='mobile' placeholder='' required>Mobile</label>

                                <span style='font-size:12px;color:red;padding-top:10px;margin-left:10px;' class='mobile-msg transition'></span>

                            </div>

                            <div class='group transition tip-parent'>

                                <textarea class='form-input comment' name='comment' cols='3' rows='5' style='border-radius:5px;'></textarea>

                                <label class='form-input-placeholder' for='comment' placeholder='' required>How did you hear about us?</label>

                            </div>

                            <div class='group transition tip-parent'>

                                <label class='check-wrapper'>

                                    <div style='font-size:14px;margin-top:4px;'>I want to subscribe for Newsletter</div>

                                    <input type='checkbox' class='subscribe' />

                                    <span class='check-mark'></span>

                                </label>

                            </div>

                        </div>

                        <div class='modal-footer animated fadeInUp'>

                            <button class='button modal-button button-success button-rounded transition signup'>&nbsp;&nbsp; CONTINUE WITH SIGN UP &nbsp;&nbsp;<i class='transition lni-angle-double-right'></i></button>

                        </div>

                    </div>";

        }

        function verifyUserContact($arg)
        {

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

            $subscribe = $arg['subscribe'];

            

            $_SESSION['token'] = getToken(6);

            $fullname = $fname.' '.$sname;

            $subject = 'MyCart2Door Verification Code';

            $message = 'This is your verification<br><span style="font-size:25px;color:green;">'.$_SESSION['token'].'</span>';

            

            if(sendmail($fullname, $email, $subject, $message)==1)

            {

                return '<div class="animated fadeInUp">

                            <span class="stored-data" data-title="'.$title.'" data-fname="'.$fname.'" data-sname="'.$sname.'" data-email="'.$email.'" data-rmail="'.$rmail.'" data-password="'.$pwrd.'" data-country="'.$country.'" data-state="'.$state.'" data-city="'.$city.'" data-mobile="'.$mobile.'" data-comment="'.$comment.'" data-subscribe="'.$subscribe.'" data-key="'.$_SESSION['token'].'"></span>



                            <div style="text-align: justify; padding: 10px;font-size:12px; color:#145214;border:thin solid #248e24;background-color:rgba(173, 235, 173,0.3);margin-top:10px;margin-bottom:15px;">Please check the email address you provided, a confirmation code have been sent. Copy or enter this code in the form provided below and confirm to proceed.</div>

                            <div class="group">

                                <input type="text" class="form-input token transition" placeholder="" title="Enter security code sent to your mail" required />

                                <label class="form-input-placeholder" for="token" placeholder="" required>Mail security code</label>

                                <span style="font-size:12px;color:red;padding-top:10px;margin-left:10px;" class="token-msg transition"></span>

                            </div>

                        </div>';

            }

        }

        function verifyPhoneContact($arg)
        {

            $mobile = $arg['mobile'];

            $otp = getIdentity(6);

            $sender = 'MyCart2Door';

            $_SESSION['code'] = $otp;



            $apiKey = urlencode('d6cyRplFAmA-fq9aiYnHPhlOfhfFdIsDJ13nUnjGZw');
            $username = urlencode('kaykums@mycart2door.com');
            $hash = urlencode('209110744958241f4ee9dfa627869480113ee82ea0b9e69e780a98caed1c745f');
            $numbers = array($mobile);
            $sender = urlencode($sender);
            $message = rawurlencode($otp);

            $textlocal = new Textlocal($username, $hash, $apiKey);

            try{

                $result = $textlocal->sendSms($numbers, $message, $sender);

                return $this->OTP_Callback_Function($arg);

            } catch( Exception $e ){

                return 'Error: ' . $e->getMessage();

            }

        }

        function OTP_Callback_Function($arg)
        {

            $title = $arg['title'];

            $fname = $arg['fname'];

            $sname = $arg['sname'];

            $email = $arg['email'];

            $rmail = $arg['rmail'];

            $pwrd = $arg['pwrd'];

            $country = $arg['country'];

            $state = $arg['state'];

            $city = $arg['city'];

            $mobile = $arg['mobile'];

            $comment = $arg['comment'];

            $subscribe = $arg['subscribe'];



            return '<div class="animated fadeInUp">

                        <span class="stored-data" data-title="'.$title.'" data-fname="'.$fname.'" data-sname="'.$sname.'" data-email="'.$email.'" data-rmail="'.$rmail.'" data-password="'.$pwrd.'" data-country="'.$country.'" data-state="'.$state.'" data-city="'.$city.'" data-mobile="'.$mobile.'" data-comment="'.$comment.'" data-subscribe="'.$subscribe.'" data-code="'.$_SESSION['code'].'"></span>



                        <div style="text-align: justify; padding: 10px;font-size:12px; color:#145214;border:thin solid #248e24;background-color:rgba(173, 235, 173,0.3);margin-top:10px;margin-bottom:15px;">Please check the mobile number you provided, a confirmation code have been sent. enter this code in the form provided below and confirm to proceed.</div>

                        <div class="group">

                            <input type="text" class="form-input code transition" placeholder="" title="Enter security code sent to your mobile" required />

                            <label class="form-input-placeholder" for="code" placeholder="" required>Mobile security code</label>

                            <span style="font-size:12px;color:red;padding-top:10px;margin-left:10px;" class="code-msg transition"></span>

                        </div>

                        <a href="javascript:void(0)">Resend Code</a>

                    </div>';

        }

        function registrationSuccessful()
        {

            return '<div style="text-align: justify; padding: 10px;font-size:14px; color:#145214;border:thin solid #248e24;background-color:rgba(173, 235, 173,0.3);margin-top:0;margin-bottom:15px;"><h3>Congratulation!</h3><span>Welcome to MyCart2Door, your registration was successful. Please click the button below to sign in to your dashboard</span></div>';

        }

    }

?>