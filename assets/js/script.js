var loader = "<div class='linear transition'><div class='alternate'></div></div>";
var preloader = "<div><div class='page-preloader'><img src='../assets/images/mc2d_logo.png' width='100%' /><p>"+loader+"</p></div></div>";
var interval,content;

$(document).ready(function(){
    $('.icon').makeResponsive('icon');
    $(window).scroll(function(){
        if($(this).scrollTop() > 10)
        {
            $('nav').css({ 'background-color':'#2c2c2c' });
            $('.topnav > a').css({ 'color':'white' });
        }
        else
        {
            $('nav').css({ 'background-color':'transparent' });
            $('.topnav > a').css({ 'color':'steelblue' });
        }
    });
    $('.product-url').on('input',function(){
        if($(this).val() != "")
        { 
            $.ajax({
                type: 'post',
                url: 'api/objects/controllers/',
                data: {product: 'url'},
                success: function(result){
                    $(this).modalWindow(result);
                }
            });
        }
    });
    $('.register').click(function(){
        $.ajax({
            type: 'post',
            url: 'api/objects/controllers/',
            data: {request: 'signup'},
            success: function(result){
                $(this).modalWindow(result.replace('denied',''));
            }
        });
    });
    $('.signin').click(function(){
        $.ajax({
            type: 'post',
            url: 'api/objects/controllers/',
            data: {request: 'signin'},
            success: function(result){
                $(this).modalWindow(result.replace('denied',''));
            }
        });
    });
    $('.make-order').click(function(){ $(this).beginCustomOrder(); });
    
    if($('.wrapper').attr('class')!=undefined){ $('.wrapper').preloadContent(); }
});

$.fn.makeResponsive = function(param)
{
    $('.'+param).click(function(){
        var x = document.getElementById("myTopnav");

        if (x.className === "topnav") {
            x.className += " responsive";
            $('.topnav > a').css({ 'color':'white' });
        } else {
            x.className = "topnav";
            $('.topnav > a').css({ 'color':'steelblue' });
        }
    });
};
$.fn.modalWindow = function(content)
{
    $('.modalbox').html(loader).fadeIn(1000).promise().done(function(){
        var modal = new Custombox.modal({
            content: {
                target: '.modalbox',
                effect: 'contentscale',
                close: false,
                onComplete: function () {
                    $('modalbox > div').addClass('animated fadeOut').fadeIn().fadeOut(1000).promise().done(function () {
                        $('.modalbox').html(content).fadeIn(1000).promise().done(function(){
                            $('.modal-close').click(function(){ $(this).closeModalWindow(); });
                            $('.signup').click(function(){ $(this).submitSignup(); });
                            $('.login').click(function(){ $(this).signinWithCredentials(); });
                            $('.my-product').getPasteProduct();
                            
                            if($('.size').attr('class')!=undefined)
                            {
                                $('.order').click(function(){ $(this).processMyOrder(); });
                            }
                            if($('.country').attr('class')!=undefined){ populateCountries('country','state'); }
                            if($('.order-form-caption').attr('class')!=undefined){ $('.order').click(function(){ $(this).processMyOrder(); }); }
                            if($('button').hasClass('admin-login'))
                            {
                                $(this).confirmBackendCredentials();
                            }
                            if($('.backend-users').attr('class')!=undefined)
                            {
                                $('.modal-body > div').html(loader).css({'width':'100vw'}).listOfBackendUsers();
                                $('.modal-footer').css({'display':'none'});
                            }
                            if($('.modal-body > div').hasClass('order-history'))
                            {
                                $('.order-history').html(loader).loadTransactionHistory();
                            }
                            if($('.update-currency').attr('class')!=undefined)
                            {
                                $('.update-currency').disableButton('update-currency');
                            }
                            if($('.create-shipping').attr('class')!=undefined)
                            {
                                $(this).provide_Delivery_rate();
                            }
                            if($('.fund-my-wallet').attr('class')!=undefined)
                            {
                                $('.fund-my-wallet').click(function(){
                                    $(this).fund_my_wallet();
                                });
                            }
                        });
                    });
                },
                onClose: function () {
                    $('.modalbox').children().remove().css({ 'display': 'none' }).promise().done(function () {
                        Custombox.modal.close();
                    });
                },
                overlay: {
                    active: true,
                    opacity: 0.5,
                    close: false,
                    onClose: function () {
                        $('.modalbox').children().remove().css({ 'display': 'none' }).promise().done(function () {
                            Custombox.modal.close();
                        });
                    }
                }
            }
        });
        
        modal.open();
    });
}
$.fn.closeModalWindow = function()
{
    Custombox.modal.close();
}
$.fn.submitSignup = function()
{
    var data = {
        title: $('.titles').val(),
        fname: $('.fname').val(),
        sname: $('.sname').val(),
        email: $('.email').val(),
        rmail: $('.rmail').val(),
        pwrd: $('.pwrd').val(),
        pass: $('.cpwd').val(),
        country: $('.country').val(),
        state: $('.state').val(),
        city: $('.city').val(),
        mobile: $('.mobile').val(),
        comment: $('.comment').val(),
        subscribe: $('.subscribe').is(':checked')
    }
    
    if(data.fname=='')
    {
        $('.fname').css({'border':'thin solid red'}).focus().select();
        $('.fname-msg').html('Please enter first name');
        $('.fname').on('input',function(){ $(this).restoreDefault('fname'); });
    }
    else if(data.sname=='')
    {
        $('.sname').css({'border':'thin solid red'}).focus().select();
        $('.sname-msg').html('Please enter surname/last name');
        $('.sname').on('input',function(){ $(this).restoreDefault('sname'); });
    }
    else if(data.email=='')
    {
        $('.email').css({'border':'thin solid red'}).focus().select();
        $('.email-msg').html('Please enter your email');
        $('.email').on('input',function(){ $(this).restoreDefault('email'); });
    }
    else if(!isValidEmailAddress(data.email))
    {
        $('.email').css({'border':'thin solid red'}).focus().select();
        $('.email-msg').html('Invalid email address');
        $('.email').on('input',function(){ $(this).restoreDefault('email'); });
    }
    else if(data.rmail!='' && !isValidEmailAddress(data.rmail))
    {
        $('.rmail').css({'border':'thin solid red'}).focus().select();
        $('.rmail-msg').html('Invalid email address');
        $('.rmail').on('input',function(){ $(this).restoreDefault('rmail'); });
    }
    else if(data.pwrd=='')
    {
        $('.pwrd').css({'border':'thin solid red'}).focus().select();
        $('.pwrd-msg').html('Please enter your password');
        $('.pwrd').on('input',function(){ $(this).restoreDefault('pwrd'); });
    }
    else if(!isValidPassword(data.pwrd))
    {
        $('.pwrd').css({'border':'thin solid red'}).focus().select();
        $('.pwrd-msg').html('Must be a strong assword');
        $('.pwrd').on('input',function(){ $(this).restoreDefault('pwrd'); });
    }
    else if(data.pwrd!=data.pass)
    {
        $('.pwrd').css({'border':'thin solid red'}).focus().select();
        $('.pwrd-msg').html('Password doesn\'t match');
        $('.pwrd').on('input',function(){ $(this).restoreDefault('pwrd'); });
    }
    else if(data.country=='-1')
    {
        $('.country').css({'border':'thin solid red'}).focus().select();
        $('.country-msg').html('Please choose country');
        $('.country').on('input',function(){ $(this).restoreDefault('country'); });
    }
    else if(data.state=='-1')
    {
        $('.state').css({'border':'thin solid red'}).focus().select();
        $('.state-msg').html('Please choose state');
        $('.state').on('input',function(){ $(this).restoreDefault('state'); });
    }
    else if(data.city=='')
    {
        $('.city').css({'border':'thin solid red'}).focus().select();
        $('.city-msg').html('Please enter city');
        $('.city').on('input',function(){ $(this).restoreDefault('city'); });
    }
    else if(data.mobile=='')
    {
        $('.mobile').css({'border':'thin solid red'}).focus().select();
        $('.mobile-msg').html('Please enter mobile');
        $('.mobile').on('input',function(){ $(this).restoreDefault('mobile'); });
    }
    else if(!isValidMobile(data.mobile))
    {
        $('.mobile').css({'border':'thin solid red'}).focus().select();
        $('.mobile-msg').html('Invalid mobile number');
        $('.mobile').on('input',function(){ $(this).restoreDefault('mobile'); });
    }
    else
    {
        $('button').disableButton('signup');
        $('.modal-body > div').addClass('animated fadeOutDown').fadeOut(1000).promise().done(function(){
            $('.modal-body').html(loader).fadeIn(1000).promise().done(function(){
                var fdata = new FormData();
                
                fdata.append('verify','email');
                fdata.append('title',data.title);
                fdata.append('fname',data.fname);
                fdata.append('sname',data.sname);
                fdata.append('email',data.email);
                fdata.append('rmail',data.rmail);
                fdata.append('pwrd',data.pwrd);
                fdata.append('country',data.country);
                fdata.append('state',data.state);
                fdata.append('city',data.city);
                fdata.append('mobile',data.mobile);
                fdata.append('comment',data.comment);
                fdata.append('subscribe',data.subscribe);
                
                $.ajax({
                    type: 'post',
                    url: 'api/objects/controllers/',
                    processData: false,
                    contentType: false,
                    data: fdata,
                    success: function(result){
                        $('.linear').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                            $('.modal-body').html(result.replace('denied','')).fadeIn(1000).promise().done(function(){
                                $('.signup').removeAttr('disabled').css({'cursor':'pointer'});
                                $('.signup').click(function(){
                                    $(this).verifyMail();
                                });
                            });
                        });
                    }
                });
            });
        });
    }
    
    fdata = null;
}
$.fn.restoreDefault = function(param)
{
    $('.'+param).css({'border':'thin solid #bac7fb'});
    $('.'+param+'-msg').html('');
}
$.fn.disableButton = function(args)
{
    $('.'+args).unbind().prop('disabled', true).css({ 'cursor':'not-allowed' });
}
$.fn.EnableButton = function(args)
{
    $('.'+args).removeAttr('disabled').css({ 'cursor':'pointer' });
}
$.fn.verifyMail = function()
{
    var key = $('.token').val();
    var data = {
        title: $('.stored-data').data('title'),
        fname: $('.stored-data').data('fname'),
        sname: $('.stored-data').data('sname'),
        email: $('.stored-data').data('email'),
        rmail: $('.stored-data').data('rmail'),
        passw: $('.stored-data').data('password'),
        country: $('.stored-data').data('country'),
        state: $('.stored-data').data('state'),
        city: $('.stored-data').data('city'),
        mobile: $('.stored-data').data('mobile'),
        comment: $('.stored-data').data('comment'),
        subscribe: $('.stored-data').data('subscribe')
    }
    
    if(key !== $('.stored-data').data('key'))
    {
        $('.token').css({'border':'thin solid red'}).focus().select();
        $('.token-msg').html('Invalid verification code');
        $('.token').on('input',function(){ $(this).restoreDefault('token'); });
    }
    else
    {
        $('.signup').disableButton('signup');
        $('.modal-body > div').addClass('animated fadeOutDown').fadeOut(1000).promise().done(function(){
            $('.modal-body').html(loader).fadeIn(1000).promise().done(function(){
                var fdata = new FormData();
                
                fdata.append('validate', 'client');
                fdata.append('title', data.title);
                fdata.append('fname', data.fname);
                fdata.append('sname', data.sname);
                fdata.append('email', data.email);
                fdata.append('rmail', data.rmail);
                fdata.append('pwrd', data.passw);
                fdata.append('country', data.country);
                fdata.append('state', data.state);
                fdata.append('city', data.city);
                fdata.append('mobile', data.mobile);
                fdata.append('comment', data.comment);
                fdata.append('subscribe', data.subscribe);
                
                $.ajax({
                    type: 'post',
                    url: 'api/objects/controllers/',
                    processData: false,
                    contentType: false,
                    data: fdata,
                    success: function(result){
                        $('.linear').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                            $('.modal-body').html(result.replace('denied','')).fadeIn(1000).promise().done(function(){
                                $('.signup').removeAttr('disabled').css({'cursor':'pointer'});
                                $('.signup').click(function(){
                                    $('.signup').verifyMobile();
                                });
                            });
                        });
                    }
                });
            });
        });
    }
}
$.fn.verifyMobile = function()
{
    var data = {
        title: $('.stored-data').data('title'),
        fname: $('.stored-data').data('fname'),
        sname: $('.stored-data').data('sname'),
        email: $('.stored-data').data('email'),
        passw: $('.stored-data').data('password'),
        country: $('.stored-data').data('country'),
        state: $('.stored-data').data('state'),
        city: $('.stored-data').data('city'),
        mobile: $('.stored-data').data('mobile'),
        comment: $('.stored-data').data('comment'),
        subscribe: $('.stored-data').data('subscribe')
    }
    
    if($('.code').val()!=$('.stored-data').data('code'))
    {
        $('.code').css({'border':'thin solid red'}).focus().select();
        $('.code-msg').html('Invalid OTP code');
        $('.code').on('input',function(){ $(this).restoreDefault('code'); });
    }
    else
    {
        $('.signup').disableButton('signup');
        $('.modal-body > div').addClass('animated fadeOutDown').fadeOut(1000).promise().done(function(){
            $('.modal-body').html(loader).fadeIn(1000).promise().done(function(){
                var fdata = new FormData();
                
                fdata.append('register', 'client');
                fdata.append('title', data.title);
                fdata.append('fname', data.fname);
                fdata.append('sname', data.sname);
                fdata.append('email', data.email);
                fdata.append('rmail', data.rmail);
                fdata.append('pwrd', data.passw);
                fdata.append('country', data.country);
                fdata.append('state', data.state);
                fdata.append('city', data.city);
                fdata.append('mobile', data.mobile);
                fdata.append('comment', data.comment);
                fdata.append('subscribe', data.subscribe);
                
                $.ajax({
                    type: 'post',
                    url: 'api/objects/controllers/',
                    processData: false,
                    contentType: false,
                    data: fdata,
                    success: function(result){
                        $('.linear').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                            $('.modal-body').html(result.replace('denied','')).fadeIn(1000).promise().done(function(){
                                $('.signup').html('CONTINUE WITH LOGIN &nbsp;&nbsp;<i class="transition lni-angle-double-right"></i>').removeAttr('disabled').css({'cursor':'pointer'});
                                $('.signup').click(function(){
                                    $('.signup').getSigninForm();
                                });
                            });
                        });
                    }
                });
            });
        });
    }
}
$.fn.getSigninForm = function()
{
    $('.title').html('&nbsp;&nbsp;Sign In');
    $('.signup').disableButton('signup');
    $('.modal-body > div').addClass('animated fadeOutDown').fadeOut(1000).promise().done(function(){
        $('.modal-body').html(loader).fadeIn(1000).promise().done(function(){
            $.ajax({
                type: 'post',
                url: 'api/objects/controllers/',
                data: {request: 'login'},
                success: function(result){
                    $('.linear').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                        $('.modal-body').html(result.replace('denied','')).fadeIn(1000).promise().done(function(){
                            $('.signup').removeAttr('disabled').css({'cursor':'pointer'}).click(function(){
                                $(this).signinWithCredentials();
                            });
                        });
                    });
                }
            });
        });
    });
}
$.fn.signinWithCredentials = function()
{
    var data = {
        uname: $('.uname').val(),
        passw: $('.passw').val()
    }
    
    if(data.uname=='')
    {
        $('.uname').css({'border':'thin solid red'}).focus().select();
        $('.uname-msg').html('Please enter username');
        $('.uname').on('input',function(){ $(this).restoreDefault('uname'); });
    }
    else if(data.passw=='')
    {
        $('.passw').css({'border':'thin solid red'}).focus().select();
        $('.passw-msg').html('Please enter password');
        $('.passw').on('input',function(){ $(this).restoreDefault('passw'); });
    }
    else
    {
        $('.login').disableButton('login');
        $(this).restoreDefault('uname');
        $(this).restoreDefault('passw');
        $('.modal-body > div').find('input').prop('disabled',true);
        $('.modal-body').prepend(loader).fadeIn(1000).promise().done(function(){
            $.ajax({
                type: 'post',
                url: 'api/objects/controllers/',
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function(response){
                    switch(response.replace('denied',''))
                    {
                        case 'pass':
                            $('.modal-body > div').addClass('animated fadeOutDown').fadeOut(1000).promise().done(function(){
                                $('.modal-body').html(loader).fadeIn(1000).promise().done(function(){
                                    window.document.location.href = 'in/';
                                });
                            });
                            break;
                        default:
                            $('.login').removeAttr('disabled').css({ 'cursor':'pointer' }).click(function(){ $(this).signinWithCredentials(); });
                            $('.modal-body > div').find('input').removeAttr('disabled');
                            $('.linear').addClass('animated fadeOut').remove();
                            $('.uname').css({'border':'thin solid red'}).focus().select();
                            $('.uname-msg').html('Invalid login credential');
                            $('.uname').on('input',function(){ $(this).restoreDefault('uname'); });
                            $('.passw').css({'border':'thin solid red'}).focus().select();
                            $('.passw-msg').html('Invalid login credential');
                            $('.passw').on('input',function(){ $(this).restoreDefault('passw'); });
                            break;
                    }
                }
            });
        });
    }
}
$.fn.getPasteProduct = function()
{
    $('.my-product').html('<object data="'+$('.product-url').val()+'">');
}


function removeNotification()
{
    clearInterval(interval);
    
    if($('.modal-body > div').hasClass('d2d'))
    {
        $('.success-msg').removeClass('fadeInDown').addClass('fadeOutUp').fadeOut(1000).promise().done(function () {
            $('.modal-body').door2doorLocation();
        });
    }
    if($('.modal-body > div').hasClass('shp'))
    {
        $('.shp').removeClass('fadeInDown').addClass('fadeOutUp').fadeOut(1000).promise().done(function () {
            Custombox.modal.close();
        });
    }
    if($('.modal-body > div').hasClass('shp-er'))
    {
        $('.shp-er').removeClass('fadeInDown').addClass('fadeOutUp').fadeOut(1000).promise().done(function () {
            $('.modal-body').door2doorLocation();
        });
    }
}
function isValidTelephone(tel) {
    var filter = /^([\+][0-9]{1,3}([ \.\-])?)?([\(]{1}[0-9]{3}[\)])?([0-9A-Z \.\-]{1,32})((x|ext|extension)?[0-9]{1,4}?)$/;
    return filter.test(tel);
}
function isValidMobile(txtPhone) {
    var filter = /^((\+[1-9]{1,3}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/;
    return filter.test(txtPhone);
}
function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
    return pattern.test(emailAddress);
}
function isValidPassword(textval) {
    var passregex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$&%*_])(?=.{8,})");
    return passregex.test(textval);
}
function isValidURL(param){
    var passregex = /^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,}))\.?)(?::\d{2,5})?(?:[/?#]\S*)?$/i
    return passregex.test(param);
}



/*
JavaScript/JQuery functions handles dashboard
*/

$.fn.preloadContent = function()
{
    $('.wrapper').css({'height':'90%'}).append(preloader).promise().done(function(){
        $('.linear').css({'width':'90%'}).promise().done(function(){
            $.ajax({
                type: 'post',
                url: '../api/objects/controllers/',
                data: {dashboard:'content'},
                success: function(result){
                    $('.wrapper > div').addClass('animated fadeOutDown').fadeOut(1000).promise().done(function(){
                        $('.wrapper').html(result.replace('denied','')).fadeIn(1000).promise().done(function(){
                            $('.user-info > div > div').html(loader).loadProfileData();
                            $('.content-container > div').html(loader).loadDashboardPanels();
                        });
                    });
                }
            });
        });
    });
}
$.fn.loadProfileData = function()
{
    $.ajax({
        type: 'post',
        url: '../api/objects/controllers/',
        data: {profile:'content'},
        success: function(result){
            $('.user-info > div > div > div').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                $('.user-info > div').html(result.replace('denied',''));
            });
        }
    });
}
$.fn.loadDashboardPanels = function()
{
    gettingStarted();
    cutomOrder();
    wallet();
    purchaseHistory();
}
$.fn.startCustomOrder = function()
{
    $.ajax({
        type: 'post',
        url: '../api/objects/controllers/',
        data: {custom_order_form: 'form'},
        success: function(result){
            $('.modalbox').css({'min-width':'65%'}).modalWindow(result.replace('denied',''));
        }
    });
}
$.fn.beginCustomOrder = function()
{
    $.ajax({
        type: 'post',
        url: 'api/objects/controllers/',
        data: {custom_order_form: 'form'},
        success: function(result){
            $('.modalbox').modalWindow(result.replace('denied',''));
        }
    });
}
$.fn.CreateWallet = function()
{
    $('.wallet > div:last-child').addClass('animated fadeOutDown').fadeOut(1000).promise().done(function(){
        $('.wallet').append(loader).fadeIn(1000).promise().done(function(){
            $.ajax({
                type: 'post',
                url: '../api/objects/controllers/',
                data:{create_wallet:''},
                success: function(result){
                    $('.linear').addClass('animated fadeOut').fadeOut(1000).remove().promise().done(function(){
                        $('.wallet').css({'overflow':'hidden'}).append(result.replace('denied',''));
                    });
                }
            });
        });
    });
}

var fields = new Array();

$.fn.processMyOrder = function()
{
    var order = [];
    
    $('input[name^=order]').map(function(id, elem){
        if($(elem).val()!=''){
            if($(elem).data('name')=='store')
            {
                if($('.colour-'+(id+1)).val()==''){ $('.colour-'+(id+1)).val('default'); }
                fields.push('|'+$(elem).val()); }else{ fields.push($(elem).val());
            }
        }
    }).get();
    
    submitCustomOrder(fields);
}
$.fn.door2doorLocation = function()
{
    $('.modal-body > div').addClass('animated fadeOutDown').fadeOut(1000).promise().done(function(){
        $('.modal-body').html(loader).fadeIn(1000).promise().done(function(){
            $.ajax({
                type: 'post',
                url: '../api/objects/controllers/',
                data: {door2door_Location_Form:''},
                success: function(result){
                    $('.linear').addClass('animated fadeout').fadeOut(1000).promise().done(function(){
                        $('.modal-body').html(result.replace('denied','')).fadeIn(1000).promise().done(function(){
                            $('.order').html('&nbsp;&nbsp; SUBMIT SHIPPING LOCATION &nbsp;&nbsp;<i class="transition lni-angle-double-right"></i>');
                            $('.address').on('input',function(){
                                if($(this).val()!=''){ $('.order').EnableButton('order');$('.order').submit_shipping_form(); }else{ $('.order').disableButton('order'); }
                            });
                            
                            populateCountries('country', 'state');
                        });
                    });
                }
            });
        });
    });
}
$.fn.submit_shipping_form = function()
{
    $('.order').click(function(){
        var data = {
            country: $('.country').val(),
            state: $('.state').val(),
            lga: $('.lga').val(),
            address: $('.address').val()
        }
        var fdata = new FormData();

        content = $('.modal-body').html();
        fdata.append('submit_shipping_form','');
        fdata.append('country',data.country);
        fdata.append('state',data.state);
        fdata.append('lga',data.lga);
        fdata.append('address',data.address);

        $('.modal-body > div').addClass('animated fadeOutDown').fadeOut(1000).promise().done(function(){
            $('.modal-body').html(loader).fadeIn(1000).promise().done(function(){
                $.ajax({
                    type: 'post',
                    url: '../api/objects/controllers/',
                    processData: false,
                    contentType: false,
                    data: fdata,
                    success: function(result){
                        $('.linear').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                            switch(result.replace('denied',''))
                            {
                                case 'done':
                                    $('.modal-body').html('<div class="shp animated fadeInDown" style="text-align: justify; padding: 10px;font-size:14px; color:#145214;border:thin solid #248e24;background-color:rgba(173, 235, 173,0.3);margin-top:0;margin-bottom:15px;"><h3>Congratulation!</h3><span>Your custom order and shipping location was successfully submitted and have been received, we will send a quote within 2 business days.</span></div>').fadeIn(1000).promise().done(function(){
                                        interval = setInterval(removeNotification, 2000);
                                    });
                                    break;
                                default:
                                    $('.modal-body').html('<div class="shp-er error animated fadeInDown" style="text-align: justify; padding: 10px;font-size:14px;margin-top:0;margin-bottom:15px;"><h3>Errir Alert!</h3><span>An unexpected error occurred while trying to update your submission, please try again.</span></div>').fadeIn(1000).promise().done(function(){
                                        interval = setInterval(removeNotification, 2000);
                                    });
                                    break;
                            }
                        });
                    }
                });
            });
        });
    });
}
$.fn.orderNotification = function()
{
    $.ajax({
        type: 'post',
        url: '../api/objects/controllers/',
        data: {get_order_response:''},
        success: function(result){
            if(result.replace('denied','') > 0)
            {
                $('.h-notify').addClass('notify drop-shadow').html(result.replace('denied','')).attr('title','New notification(s)');
            }
        }
    });
}
$.fn.transactionHistory = function()
{
    $.ajax({
        type: 'post',
        url: '../api/objects/controllers/',
        data: { transaction_history:'' },
        success: function(result){
            $('.history-btn').modalWindow(result.replace('denied',''));
        }
    });
}
$.fn.loadTransactionHistory = function()
{
    $.ajax({
        type: 'post',
        url: '../api/objects/controllers/',
        data: { load_orders:'' },
        success: function(result){
            $('.linear').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                $('.modal-body').html(result.replace('denied','')).fadeIn(1000).promise().done(function(){
                    $('.filter-order').on('change',filter_order_list);
                });
            });
        }
    });
}

$.fn.fund_my_wallet = function()
{
    var data = {
        PBFPubKey: $('pkey').val(),
        email: $('.email').val(),
        phone: $('.phone').val(),
        txref: $('.txref').val(),
        amount: $('.amount').val()
    }
    var commission = parseFloat(1.4/100)*parseFloat(data.amount);
    var amount = parseFloat(data.amount) + parseFloat(commission.toFixed(2))
    update_customer_wallet(amount);
    
    var x = getpaidSetup({
        PBFPubKey: data.PBFPubKey,
        customer_email: data.email,
        amount: amount,
        customer_phone: data.phone,
        currency: 'NGN',
        txref: data.txref,
        onclose: function()
        {
            //alert("Operation was terminated by user.");
        },
        callback: function(response)
        {
            if(response.tx.chargesResponseCode=="00" || response.tx.chargesResponseCode=="0")
            {
                update_customer_wallet(amount);
            }
            else
            {
                alert("An error occured while trying to process your request.");
            }

            x.close();
        }
    });
}




function gettingStarted()
{
    $.ajax({
        type: 'post',
        url: '../api/objects/controllers/',
        data: {getting_started:'content'},
        success: function(result){
            $('.getting-started > div').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                $('.getting-started').html(result.replace('denied',''));
            });
        }
    });
}
function cutomOrder()
{
    $.ajax({
        type: 'post',
        url: '../api/objects/controllers/',
        data: {custom_order:'content'},
        success: function(result){
            $('.custom-orders > div').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                $('.custom-orders').html(result.replace('denied','')).promise().done(function(){
                    $('.custom-order').click(function(){
                        $(this).startCustomOrder();
                    });
                });
            });
        }
    });
}
function wallet()
{
    $.ajax({
        type: 'post',
        url: '../api/objects/controllers/',
        data: {wallet:'content'},
        success: function(result){
            $('.wallet > div').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                $('.wallet').html(result.replace('denied',''));
                $('.btn-wallet-create').click(function(){
                    $(this).CreateWallet().modalWindow(loader);
                });
            });
        }
    });
}
function fetch_wallet_info()
{
    $.ajax({
        type: 'post',
        url: '../api/objects/controllers/',
        data: {fetch_wallet_info: ''},
        success: function(result){
            $('.modalbox').modalWindow(result.replace('denied',''));
        }
    });
}
function update_customer_wallet(param)
{
    $.ajax({
        type: 'post',
        url: '../api/objects/controllers/',
        data: {update_customer_wallet: param},
        success: function(result){
            Custombox.modal.close();
            wallet();
        }
    });
}
function purchaseHistory()
{
    $.ajax({
        type: 'post',
        url: '../api/objects/controllers/',
        data: { purchase:'content' },
        success: function(result){
            $('.order-histroy > div').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                $('.order-histroy').html(result.replace('denied','')).orderNotification();
                $('.history-btn').click(function(){ $(this).transactionHistory(); });
            });
        }
    });
}
function tractInputField(param)
{
    var i = param.split('|');
    
    $('.row-'+i[1]+' > input').each(function(){
        return $(this).attr('name');
    });
    
}
function submitCustomOrder(param)
{
    var fdata = new FormData();
    
    fdata.append('submit_custom_order', param);
    
    $('.order').disableButton('order');
    $('.modal-body > div').addClass('animated FadeOutDown').fadeOut(1000).promise().done(function(){
        $('.modal-body').html(loader).fadeIn(1000).promise().done(function(){
            $.ajax({
                type: 'post',
                url: '../api/objects/controllers/',
                processData: false,
                contentType: false,
                data: fdata,
                success: function(result){
                    fields.length = 0;
                    
                    switch(result.replace('denied','').indexOf('success'))
                    {
                        case 0:
                            $('.linear').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                                $('.modal-body').door2doorLocation();
                            });
                            break;
                        default:
                            $('.linear').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                                $('.modal-body').html('<div style="text-align: justify; padding: 10px;font-size:14px; color:red;border:thin solid #ffcccc;background-color:rgba(255, 230, 230,0.3);margin-top:0;margin-bottom:15px;"><h3>Error Alert!</h3><span>'+result.replace('denied','')+'</span></div>').fadeIn(1000).promise().done(function(){
                                    $('.order').removeAttr('disabled').css({'cursor':'pointer'}).html('START CUSTOM ORDER HERE &nbsp;<i class="lni-angle-double-right"></i>');
                                    $('.order').click(function(){ $(this).startCustomOrder(); });
                                });
                            });
                            break;
                    }
                }
            });
        });
    });
}
function loadLGAContent()
{
    populateLGA("state", "lga");
}
function filter_order_list()
{
    var data = {
        id: $('.user-id').val(),
        status: $('.filter-order').val()
    }
    
    $('.filter-order').attr('disabled','disabled');
    $('.list-items > div').addClass('animated fadeOutDown').fadeOut(1000).promise().done(function(){
        $('.list-items').html(loader).fadeIn(1000).promise().done(function(){
            $.ajax({
                type: 'post',
                url: '../api/objects/controllers/',
                data: { filter_order_list: data.id, delimeter: data.status },
                success: function(result){
                    $('.list-items > div').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                        switch(result.replace('denied',''))
                        {
                            case '':
                                $('.list-items').showEmptyRecords('list-items','No record(s) found for '+data.status+' orders, please refined your search and try again.');
                                break;
                            default:
                                $('.list-items').html(result.replace('denied','')).fadeIn(1000);
                                break;
                        }

                        $('.filter-order').removeAttr('disabled');
                    });
                }
            });
        });
    });
}
function get_user_oder_details(id)
{
    $('.order-'+id).addClass('animated fadeOutLeft').fadeOut(1000).promise().done(function(){
        $('.modal-body > div').addClass('animated fadeOutDown').fadeOut(1000).promise().done(function(){
            $('.modal-body').html(loader).fadeIn(1000).promise().done(function(){
                $.ajax({
                    type: 'post',
                    url: '../api/objects/controllers/',
                    data: { get_my_order_details: id },
                    success: function(result){
                        $('.linear').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                            $('.modal-body').html(result.replace('denied',''));
                        });
                    }
                });
            });
        });
    });
}


/**
 * General functions and procedures that manages backend
 * @General Backend
 */
$.fn.showEmptyRecords = function(elem, content)
{
    $('.'+elem).html("<div style='display:flex;' class='transition'><div style='flex-basis:2%;'><i class='lni-question-circle' style='font-size:28px;'></i></div><div style='flex-basis:98%;margin-left:10px;' class='user-list transition'>"+content+"</div></div>");
}