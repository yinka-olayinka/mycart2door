var pBar = "<div><div class='page-preloader'><img src='../assets/images/mc2d_logo.png' width='100%' /></div></div>";
var once = false;

$(document).ready(function(){
    $('.backend').html(pBar).addClass('aminated fadeInDown').fadeIn(1000).promise().done(function(){
        $(this).lunchBackend();
    });
});

var interval,user_id,content;

function removeNotification()
{
    clearInterval(interval);
    
    if($('.modal-body > div').hasClass('uup'))
    {
        $('.success-msg').removeClass('fadeInDown').addClass('fadeOutUp').fadeOut(1000).promise().done(function () {
            $('.modal-body').listOfBackendUsers();
        });
    }
    if($('.user-order-lists').attr('class')!=undefined)
    {
        $('.user-order-lists').promise().done(function(){
            get_user_order_list(user_id);
        });
    }
    if($('.modal-body > div').hasClass('wpc'))
    {
        $('.error').removeClass('fadeInDown').addClass('fadeOutUp').fadeOut(1000).promise().done(function(){
            $('.modal-body').html(content);
            $('.admin-login').removeAttr('disabled').css({ 'cursor':'pointer' }).confirmBackendCredentials();
        });
    }
    if($('.modal-body > div').hasClass('inter'))
    {
        $('.modal-body > .inter').removeClass('fadeInDown').addClass('fadeOutUp').fadeOut(1000).promise().done(function(){
            $('.modal-body').html(content).fadeIn(1000);
        });
    }
    if($('.modal-body > div').hasClass('d2d'))
    {
        $('.success-msg').removeClass('fadeInDown').addClass('fadeOutUp').fadeOut(1000).promise().done(function () {
            $('.modal-body').door2doorLocation();
        });
    }
    if($('.modal-body > div').hasClass('shp-er'))
    {
        $('.error').removeClass('fadeInDown').addClass('fadeOutUp').fadeOut(1000).promise().done(function(){
            $('.modal-body').html(content).fadeIn(1000).promise().done(function(){
                $('update-shipping').disableButton('update-shipping');
            });
        });
    }
    if($('.modal-body > div').hasClass('shp-ok'))
    {
        $('.success-msg').removeClass('fadeInDown').addClass('fadeOutUp').fadeOut(1000).promise().done(function(){
            $('.modal-body').html(loader).fadeIn(1000).promise().done(function(){
                $(this).load_shipping_list();
            });
        });
    }
}


/**
 * Administrative functions and procedures that manages backend
 * @Administrator Backend
 */
$.fn.lunchBackend = function() //Loads backend user profile information + avatar
{
    $.ajax({
        type: 'post',
        url: '../api/objects/controllers/',
        data: {backend: 'login'},
        success: function(result){
            switch(result.replace('denied',''))
            {
                case 'login':
                    $('.backend').adminForm();
                    break;
                default:
                    $('.backend > div').addClass('animated fadeOutDown').fadeOut(1000).promise().done(function(){
                        $('.backend').html(result.replace('denied','')).fadeIn(1000).promise().done(function(){
                            $('.user-info > div > div').html(loader).loadAdminProfile();
                        });
                    });
                    break;
            }
        }
    });
}
$.fn.adminForm = function() //Lunches modal to manage backend users
{
    $.ajax({
        type: 'post',
        url: '../api/objects/controllers/',
        data:{ admin_login:'' },
        success: function(result){
            $('.backend').modalWindow(result.replace('denied',''));
        }
    });
}
$.fn.confirmBackendCredentials = function() //Verifies and submit backend user's credentials
{
    $('.admin-login').click(function(){
        var data = {
            uname : $('.uname').val(),
            passw : $('.passw').val()
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
            var fdata = new FormData();
            
            content = $('.modal-body').html();

            fdata.append('admin_signin','verify');
            fdata.append('username', data.uname);
            fdata.append('password', data.passw);

            $(this).disableButton('admin-login');
            $('.modal-body > div').addClass('animated fadeOutDown').fadeOut(1000).promise().done(function(){
                $('.modal-body').html(loader).fadeIn(1000).promise().done(function(){
                    $.ajax({
                        type: 'post',
                        url: '../api/objects/controllers/',
                        processData: false,
                        contentType: false,
                        data: fdata,
                        success: function(result){
                            switch(result.replace('denied',''))
                            {
                                case "0":
                                    $('.linear').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                                        $('.modal-body').css({'position':'relative'}).html('<div class="error transition animated fadeInDown wpc"><div><i class="lni-cross-circle"></i></div><div class="message">The backend login credentials provided is invalid!</div></div>').fadeIn(1000).promise().done(function(){
                                            interval = setInterval(removeNotification, 2000);
                                        });
                                    });
                                    break;
                                default:
                                    Custombox.modal.close();
                                    $('.backend').lunchBackend();
                                    break;
                            }
                        }
                    });
                });
            });
        }
    });
}
$.fn.loadAdminBackendCustomOrders = function() //Loads admin backend sub-panel section for custom order
{
    $.ajax({
        type: 'post',
        url: '../api/objects/controllers/',
        data: { manageCustomOrders: '' },
        success: function(result){
            $('.cust-order').html(result.replace('denied','')).fadeIn(1000);
        }
    });
}
$.fn.listOfBackendUsers = function() //List all created backend users for this application
{
    $.ajax({
        type: 'post',
        url: '../api/objects/controllers/',
        data: { user_list: '' },
        success: function(result){
            $('.modal-body > div').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                $('.modal-body').html(result.replace('denied','')).CreateBackendUser();
                $('.new-user').AddNewUser();
            });
        }
    });
}
$.fn.AddNewUser = function() //Lunches modal that displays new backend user's form
{
    $('.new-user').click(function(){
        $('.modal-body > div').addClass('animated fadeOutDown').fadeOut(1000).promise().done(function(){
            $('.modal-body').html(loader).fadeIn(1000).promise().done(function(){
                $.ajax({
                    type: 'post',
                    url: '../api/objects/controllers/',
                    data: { new_user_form: '' },
                    success: function(result){
                        $('.modal-body > div').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                            $('.modal-body').html(result.replace('denied',''));
                            $('.modal-footer').css({'display':'block'});
                            $('.create').EnableButton('create');
                            $('.create').validateUserProfile();
                        });
                    }
                });
            });
        });
    });
}
$.fn.CreateBackendUser = function() //Pulls up new form from backend users
{
    $('.create-user').click(function(){
        $('.modal-body > div').addClass('animated fadeOutDown').fadeOut(1000).promise().done(function(){
            $('.modal-body').html(loader).fadeIn(1000).promise().done(function(){
                $.ajax({
                    type: 'post',
                    url: '../api/objects/controllers/',
                    data: { new_user_form: '' },
                    success: function(result){
                        $('.modal-body > div').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                            $('.modal-body').html(result.replace('denied',''));
                            $('.modal-footer').css({'display':'block'});
                            $('.create').EnableButton('create');
                            $('.create').validateUserProfile();
                        });
                    }
                });
            });
        });
    });
}
$.fn.validateUserProfile = function() //Validates new backend user credentials and insert credentials to database
{
    $('.create').click(function(){
        var data = {
            fname: $('.fname').val(),
            sname: $('.sname').val(),
            uname: $('.uname').val(),
            passw: $('.passw').val(),
            passc: $('.passc').val(),
            stream: $('.stream').val()
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
            $('.sname-msg').html('Please enter surname');
            $('.sname').on('input',function(){ $(this).restoreDefault('sname'); });
        }
        else if(data.uname=='')
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
        else if(data.passw != data.passc)
        {
            $('.passw').css({'border':'thin solid red'}).focus().select();
            $('.passw-msg').html('Password doesn\'t match');
            $('.passw').on('input',function(){ $(this).restoreDefault('passw'); });
        }
        else if(!isValidPassword(data.passw))
        {
            $('.passw').css({'border':'thin solid red'}).focus().select();
            $('.passw-msg').html('Password must be alphanumeric');
            $('.passw').on('input',function(){ $(this).restoreDefault('passw'); });
        }
        else if(data.stream=='')
        {
            $('.stream').css({'border':'thin solid red'}).focus().select();
            $('.stream-msg').html('Enter user stream');
            $('.stream').on('input',function(){ $(this).restoreDefault('stream'); });
        }
        else
        {
            var fdata = new FormData();
            var conts = $('.modal-body > div').html();

            fdata.append('insert_backend_user','');
            fdata.append('fname',data.fname);
            fdata.append('sname',data.sname);
            fdata.append('uname',data.uname);
            fdata.append('passw',data.passw);
            fdata.append('stream',data.stream);

            $('.create').disableButton('create');
            $('.modal-body > div').addClass('animated fadeOutDown').fadeOut(1000).promise().done(function(){
                $('.modal-body').html(loader).fadeIn(1000).promise().done(function(){
                    $.ajax({
                        type: 'post',
                        url: '../api/objects/controllers/',
                        processData: false,
                        contentType: false,
                        data: fdata,
                        success: function(result){
                            $('.linear').addClass('animated fadeOut').promise().done(function(){
                                switch(result.replace('denied',''))
                                {
                                    case 'success':
                                        $('.modal-body').css({'position':'relative'}).css({'min-height':'100px'}).html('<div class="success-msg transition animated fadeInDown"><div><i class="lni-check-mark-circle"></i></div><div class="message">Application backend user profile was successfully created!</div></div>').fadeIn(1000).promise().done(function(){
                                            interval = setInterval(removeNotification, 2000);
                                            $('.modal-footer').css({'display':'none'});
                                        });
                                        break;
                                    default:
                                        $('.modal-body').html(conts).fadeIn(1000).promise().done(function(){
                                            $('.uname').css({'border':'thin solid red'}).focus().select();
                                            $('.uname-msg').html('Username already exist!');
                                            $('.uname').on('input',function(){ $(this).restoreDefault('uname'); });
                                            $('.create').removeAttr('disabled').css({'cursor':'pointer'}).validateUserProfile();
                                        });
                                        break;
                                }
                            });
                        }
                    });
                });
            });
        }
    });
}
$.fn.updateUserProfile = function() //Updates backend user's profile
{
    $('.create').click(function(){
        var data = {
            id: $('.id').val(),
            fname: $('.fname').val(),
            sname: $('.sname').val(),
            uname: $('.uname').val(),
            passw: $('.passw').val(),
            passc: $('.passc').val(),
            stream: $('.stream').val()
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
            $('.sname-msg').html('Please enter surname');
            $('.sname').on('input',function(){ $(this).restoreDefault('sname'); });
        }
        else if(data.uname=='')
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
        else if(data.passw != data.passc)
        {
            $('.passw').css({'border':'thin solid red'}).focus().select();
            $('.passw-msg').html('Password doesn\'t match');
            $('.passw').on('input',function(){ $(this).restoreDefault('passw'); });
        }
        else if(!isValidPassword(data.passw))
        {
            $('.passw').css({'border':'thin solid red'}).focus().select();
            $('.passw-msg').html('Password must be alphanumeric');
            $('.passw').on('input',function(){ $(this).restoreDefault('passw'); });
        }
        else if(data.stream=='')
        {
            $('.stream').css({'border':'thin solid red'}).focus().select();
            $('.stream-msg').html('Enter user stream');
            $('.stream').on('input',function(){ $(this).restoreDefault('stream'); });
        }
        else
        {
            var fdata = new FormData();
            var conts = $('.modal-body > div').html();

            fdata.append('update_backend_user','');
            fdata.append('id', data.id);
            fdata.append('fname',data.fname);
            fdata.append('sname',data.sname);
            fdata.append('uname',data.uname);
            fdata.append('passw',data.passw);
            fdata.append('stream',data.stream);

            $('.create').disableButton('create');
            $('.modal-body > div').addClass('animated fadeOutDown').fadeOut(1000).promise().done(function(){
                $('.modal-body').html(loader).fadeIn(1000).promise().done(function(){
                    $.ajax({
                        type: 'post',
                        url: '../api/objects/controllers/',
                        processData: false,
                        contentType: false,
                        data: fdata,
                        success: function(result){
                            $('.linear').addClass('animated fadeOut').promise().done(function(){
                                switch(result.replace('denied',''))
                                {
                                    case 'success':
                                        $('.modal-body').css({'position':'relative'}).css({'min-height':'100px'}).html('<div class="success-msg transition animated fadeInDown uup"><div><i class="lni-check-mark-circle"></i></div><div class="message">Application backend user profile was successfully updated!</div></div>').fadeIn(1000).promise().done(function(){
                                            interval = setInterval(removeNotification, 2000);
                                            $('.modal-footer').css({'display':'none'});
                                        });
                                        break;
                                    default:
                                        $('.modal-body').html(conts).fadeIn(1000).promise().done(function(){
                                            $('.uname').css({'border':'thin solid red'}).focus().select();
                                            $('.uname-msg').html('Username already exist!');
                                            $('.uname').on('input',function(){ $(this).restoreDefault('uname'); });
                                            $('.create').removeAttr('disabled').css({'cursor':'pointer'}).updateUserProfile();
                                        });
                                        break;
                                }
                            });
                        }
                    });
                });
            });
        }
    });
}
$.fn.finance_panel_manager = function()
{
    $('.finance').html(loader).fadeIn(1000).promise().done(function(){
        $.ajax({
            type: 'post',
            url: '../api/objects/controllers/',
            data: { finance_panel_manager: '' },
            success: function(result){
                $('.finance > div').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                    $('.finance').html(result.replace('denied','')).fadeIn(1000).promise().done(function(){
                        $('.manage-finance').start_finance_modal()
                    });
                });
            }
        });
    });
}


function addPermissions(param) //Assign permissions to backend users
{
    alert(param);
}
function editUserProfile(param)
{
    $('.modal-body > div').addClass('animated fadeOutDown').fadeOut(1000).promise().done(function(){
        $('.modal-body').html(loader).fadeIn(1000).promise().done(function(){
            $.ajax({
                type: 'post',
                url: '../api/objects/controllers/',
                data: { edit_user_profile: param },
                success: function(result){
                    $('.modal-body > div').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                        $('.modal-body').html(result.replace('denied',''));
                        $('.modal-footer').css({'display':'block'});
                        $('.create').EnableButton('create');
                        $('.create').html("UPDATE USER PROFILE &nbsp;&nbsp;<i class='transition lni-angle-double-right'></i>");
                        $('.create').updateUserProfile();
                    });
                }
            });
        });
    });
}
function suspendUserAccount(param)
{
    if($('.user-list-'+param).hasClass('suspension'))
    {
        if(confirm("You're about to unsuspend the select user.\nDo you still want to proceed with this action?"))
        {
            $('.user-list-'+param).append(loader).fadeIn(1000).promise().done(function(){
                $.ajax({
                    type: 'post',
                    url: '../api/objects/controllers/',
                    data: { unsuspend_user_profile: param },
                    success: function(result){
                        $('.user-list-'+param+' > .linear').remove().promise().done(function(){
                            $('.user-list-'+param).removeClass('suspension');
                        });
                    }
                });
            });
        }
    }
    else
    {
        if(confirm("Performing this operation will suspend the selected user account.\nDo you still want to proceed with this action?"))
        {
            $('.user-list-'+param).append(loader).fadeIn(1000).promise().done(function(){
                $.ajax({
                    type: 'post',
                    url: '../api/objects/controllers/',
                    data: { suspend_user_profile: param },
                    success: function(result){
                        $('.user-list-'+param+' > .linear').remove().promise().done(function(){
                            $('.user-list-'+param).addClass('suspension');
                        });
                    }
                });
            });
        }
    }
}
function deleteUserAccount(param)
{
    if(confirm("The action you're about to perform will parmanently delete the chosen user account without future retrieval.\nDo still want to proceed with this action?"))
    {
        $('.user-list-'+param).append(loader).fadeIn(1000).promise().done(function(){
            $.ajax({
                type: 'post',
                url: '../api/objects/controllers/',
                data: { delete_user_profile: param },
                success: function(result){
                    $('.user-list-'+param+' > .linear').remove().promise().done(function(){
                        $('.user-list-'+param).addClass('suspension').delay(2000).promise().done(function(){
                            $(this).addClass('animated fadeOutLeft').fadeOut(1000).promise().done(function(){
                                $(this).remove();
                                $('.modal-body').css({'position':'relative'}).css({'min-height':'100px'}).html('<div class="success-msg transition animated fadeInDown"><div><i class="lni-check-mark-circle"></i></div><div class="message">User account successfully deleted!</div></div>').fadeIn(1000).promise().done(function(){
                                    interval = setInterval(removeNotification, 2000);
                                });
                            });
                        });
                    });
                }
            });
        });
    }
}
function adminCustomOrderContent()
{
    $('.backend-container > div').addClass('animated fadeOutDown').fadeOut(1000).promise().done(function(){
        $('.backend-container').html(loader).fadeIn(1000).promise().done(function(){
            $.ajax({
                type: 'post',
                url: '../api/objects/controllers/',
                data: { admin_Custom_Order_Content: '' },
                success: function(result){
                    $('.linear').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                        $('.backend-container').html(result.replace('denied','')).fadeIn(1000).promise().done(function(){
                            $('.backend-container > div').html(loader).fadeIn(1000).promise().done(function(){
                                $('.backend-users').load_User_Orders();
                            });
                        });
                    });
                }
            });
        });
    });
}



/**
 * Custom order personnel to manage custom orders backend
 * @Custom Order
 */
$.fn.loadBackendUserForm = function()
{
    $('.manage-users').click(function(){
        $.ajax({
            type: 'post',
            url: '../api/objects/controllers/',
            data: { user_form: '' },
            success: function(result){
                $(this).modalWindow(result.replace('denied',''));
            }
        });
    });
}
$.fn.loadUserWithOrders = function()
{
    $('.users-with-order').html(loader).fadeIn(1000).promise().done(function(){
        $.ajax({
            type: 'post',
            url: '../api/objects/controllers/',
            data: { users_with_order: '' },
            success: function(result){
                $('.linear').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                    $('.users-with-order').html(result.replace('denied','')).fadeIn(1000).preloadEmptyList('user-order-lists','To access a customer order list, please select your preferred customer displayed by the left side of the window.');
                });
            }
        });
    });
}
$.fn.load_User_Orders = function()
{
    $.ajax({
        type: 'post',
        url: '../api/objects/controllers/',
        data: { load_User_Orders: '' },
        success: function(result){
            $('.user-oders > div').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                $('.user-oders').html(result.replace('denied','')).fadeIn(1000).promise().done(function(){
                    $('.users-with-order').html(loader).fadeIn(1000).promise().done(function(){
                        $(this).loadUserWithOrders();
                    });
                });
            });
        }
    });
}


function get_user_order_list(id)
{
    $('.user-order-lists > div').addClass('animated fadeOutDown').fadeOut(1000).promise().done(function(){
        $('.user-order-lists').html(loader).fadeIn(1000).promise().done(function(){
            $.ajax({
                type: 'post',
                url: '../api/objects/controllers/',
                data: { get_user_order_list: id },
                success: function(result){
                    $('.user-order-lists > .linear').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                        $('.user-order-lists').html(result.replace('denied','')).fadeIn(1000).promise().done(function(){
                            $('.filter-order').on('change',filter_order_list);
                        });
                    });
                }
            });
        });
    });
}
function get_user_oder_details(id)
{
    $('.order-'+id).addClass('animated fadeOutLeft').fadeOut(1000).promise().done(function(){
        $('.user-order-lists > div').addClass('animated fadeOutDown').fadeOut(1000).promise().done(function(){
            $('.user-order-lists').html(loader).fadeIn(1000).promise().done(function(){
                $.ajax({
                    type: 'post',
                    url: '../api/objects/controllers/',
                    data: { get_user_oder_details: id },
                    success: function(result){
                        $('.linear').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                            $('.user-order-lists').html(result.replace('denied',''));
                        });
                    }
                });
            });
        });
    });
}
function update_order()
{
    var data = {
        id: $('.id').val(),
        user_id: $('.user_id').val(),
        currency: $('.forex').val(),
        rate: $('.cur-rate').val(),
        unit: $('.unit').val(),
        cumulative: $('.cumulative').val(),
        vat: $('.vat').val(), //parseFloat(($('.vat').val()/100)*$('.cumulative').val()),
        shipping: $('.shipping').val(),
        handling: $('.handling').val(),
        total: $('.total').val(),
        delivery: $('.delivery').val()
    }
    
    user_id = data.user_id;

    if(data.currency==null)
    {
        $('.forex').css({'border':'thin solid red'}).focus().select();
        $('.forex-msg').html('Choose product currency');
        $('.forex').on('input',function(){ $(this).restoreDefault('forex'); });
    }
    else if(data.unit=='')
    {
        $('.unit').css({'border':'thin solid red'}).focus().select();
        $('.unit-msg').html('Please enter unit price');
        $('.unit').on('input',function(){ $(this).restoreDefault('unit'); });
    }
    else if(data.unit < 1)
    {
        $('.unit').css({'border':'thin solid red'}).focus().select();
        $('.unit-msg').html('Please enter a valid unit price');
        $('.unit').on('input',function(){ $(this).restoreDefault('unit'); });
    }
    else if(data.vat=='')
    {
        $('.vat').css({'border':'thin solid red'}).focus().select();
        $('.vat-msg').html('Please enter a valid vat price or 0 if no vat');
        $('.vat').on('input',function(){ $(this).restoreDefault('vat'); });
    }
    else if(data.delivery=='' || data.delivery < 1)
    {
        $('.delivery').css({'border':'thin solid red'}).focus().select();
        $('.delivery-msg').html('Please enter a valid delivery period in days');
        $('.delivery').on('input',function(){ $(this).restoreDefault('delivery'); });
    }
    else
    {
        var fdata = new FormData();

        fdata.append('update_order','');
        fdata.append('id',data.id);
        fdata.append('currency', data.currency);
        fdata.append('rate', data.rate);
        fdata.append('unit', data.unit);
        fdata.append('cumulative', data.cumulative);
        fdata.append('vat',data.vat);
        fdata.append('shipping',data.shipping);
        fdata.append('handling',data.handling);
        fdata.append('total',data.total);
        fdata.append('delivery',data.delivery);

        $('.user-order-lists > div').addClass('animated fadeOutDown').fadeOut(1000).promise().done(function(){
            $('.user-order-lists').html(loader).fadeIn(1000).promise().done(function(){
                $.ajax({
                    type: 'post',
                    url: '../api/objects/controllers/',
                    processData: false,
                    contentType: false,
                    data: fdata,
                    success: function(result){
                        switch(result.replace('denied',''))
                        {
                            case 'error':
                                $('.backend-container').html(result.replace('denied',''));
                                break;
                            default:
                                $('.user-order-lists > div').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                                    $('.user-order-lists').html('<div class="success-msg transition animated fadeInDown"><div><i class="lni-check-mark-circle"></i></div><div class="message">Custom order was successfully updated!</div></div>').fadeIn(1000).promise().done(function(){
                                        interval = setInterval(removeNotification, 2000);
                                    });
                                });
                                break;
                        }
                    }
                });
            });
        });
    }
}
function autoCalculate()
{
    var qty= parseInt($('.qty').val());

    if($('.unit').val()=='' && $('.vat').val()!='')
    {
        $('.vat').val('');
        $('.total').val('');
    }
    if($('.unit').val()!='' && $('.unit').val() > 0)
    {
        $('.cumulative').val(parseFloat(($('.unit').val()*qty)*$('.cur-rate').val()));
        $('.total').val(parseFloat($('.shipping').val())+parseFloat($('.handling').val())+parseFloat(($('.unit').val()*qty)*$('.cur-rate').val())+parseFloat(($('.vat').val()/100)*$('.cumulative').val()));
    }
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
function pickup_current_rate(id)
{
    $('.cur-rate').parent().css({'flex-direction':'column'}).prepend(loader).fadeIn(1000).promise().done(function(){
        $.ajax({
            type: 'post',
            url: '../api/objects/controllers/',
            data: { get_current_set_rate: id },
            success: function(result){
                $('.group > .linear').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                    $(this).remove().promise().done(function(){
                        $('.cur-rate').removeAttr('disabled').val(result.replace('denied','')).calculate_accrued_price();
                    });
                });
            }
        });
    });
}


/**
 * Finance functions and procedure that manages backend
 * @Finance Backend
 */
$.fn.start_finance_modal = function()
{
    $('.manage-finance').click(function(){
        $('.backend-container > div').addClass('animated fadeOutDown').fadeOut(1000).promise().done(function(){
            $('.backend-container').html(loader).fadeIn(1000).promise().done(function(){
                $.ajax({
                    type: 'post',
                    url: '../api/objects/controllers/',
                    data: { finance_management_form: '' },
                    success: function(result){
                        $('.linear').addClass('animated fadeOut').promise().done(function(){
                            $('.backend-container').html(result.replace('denied','')).fadeIn(1000).promise().done(function(){
                                $('.backend-container > div').html(loader).fadeIn(1000).currency_pane_content();
                            });
                        });
                    }
                });
            });
        });
    });
}
$.fn.currency_pane_content = function()
{
    $.ajax({
        type: 'post',
        url: '../api/objects/controllers/',
        data: { currency_pane_content: '' },
        success: function(result){
            $('.currency > div').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                $('.currency').html(result.replace('denied','')).fadeIn(1000).promise().done(function(){
                    $(this).delivery_pane_content();
                    $('.manage-currency').currency_manager_luncher();
                });
            });
        }
    });
}
$.fn.currency_manager_luncher = function(){
    $('.manage-currency').click(function(){
        $.ajax({
            type: 'post',
            url: '../api/objects/controllers/',
            data: { currency_manager_luncher: '' },
            success: function(result){
                $('.manage-currency').modalWindow(result.replace('denied',''));
            }
        });
    });
}
$.fn.update_currency_rate = function(){
    var id = $('.curr').val();
    var rate = $('.rate').val();
    content = $('.modal-body').html();

    $('.update-currency').disableButton('update-currency');
    $('.modal-body > div').addClass('animated fadeOutDown').fadeOut(1000).promise().done(function(){
        $('.modal-body').html(loader).fadeIn(1000).promise().done(function(){
            $.ajax({
                type: 'post',
                url: '../api/objects/controllers/',
                data: { update_currency_rate: rate, rate_id: id },
                success: function(result){
                    $('.linear').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                        switch(result.replace('denied',''))
                        {
                            case 'done':
                                $('.modal-body').css({'position':'relative'}).html('<div class="success-msg transition animated fadeInDown inter"><div><i class="lni-check-mark-circle"></i></div><div class="message">Currency rate was successfully updated!</div></div>').fadeIn(1000).promise().done(function(){
                                    interval = setInterval(removeNotification, 2000);
                                });
                                break;
                            default:
                                $('.modal-body').css({'position':'relative'}).html('<div class="error transition animated fadeInDown inter"><div><i class="lni-cross-circle"></i></div><div class="message">'+result.replace('denied','')+'!</div></div>').fadeIn(1000).promise().done(function(){
                                    interval = setInterval(removeNotification, 2000);
                                });
                                break;
                        }
                    });
                }
            });
        });
    });
}
$.fn.delivery_pane_content = function()
{
    $.ajax({
        type: 'post',
        url: '../api/objects/controllers/',
        data: { delivery_pane_content: '' },
        success: function(result){
            $('.delivery > div').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                $('.delivery').html(result.replace('denied','')).fadeIn(1000).promise().done(function(){
                    $(this).refund_pane_content();
                    $('.manage-delivery').manage_Delivery_location();
                });
            });
        }
    });
}
$.fn.manage_Delivery_location = function()
{
    $('.manage-delivery').click(function(){
        $.ajax({
            type: 'post',
            url: '../api/objects/controllers/',
            data: { manage_Delivery_location: '' },
            success: function(result){
                $('.manage-delivery').modalWindow(result.replace('denied',''));
            }
        });
    });
}
$.fn.provide_Delivery_rate = function()
{
    $('.create-shipping').click(function(){
        $('.empty-list').addClass('animated fadeOutDown').fadeOut(1000).promise().done(function(){
            $('.modal-body').html(loader).fadeIn(1000).promise().done(function(){
                $.ajax({
                    type: 'post',
                    url: '../api/objects/controllers/',
                    data: { provide_Delivery_rate: '' },
                    success: function(result){
                        $('.linear').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                            $('.modal-body').html(result.replace('denied','')).fadeIn(1000).promise().done(function(){
                                $('.modal-wrapper').append("<div class='modal-footer animated fadeInUp'><button class='button modal-button button-success button-rounded transition update-shipping'>&nbsp;&nbsp; UPDATE SHIPPING RATE &nbsp;&nbsp;<i class='transition lni-angle-double-right'></i></button></div>").fadeIn(1000).promise().done(function(){
                                    $(this).disableButton('update-shipping');
                                    $('.amount').attr('disabled','disabled');
                                });

                                populateCountries('country','state');
                                $('.state').on('change',function(){ populateLGA("state", "lga"); $('.amount').removeAttr('disabled'); });
                                $('.amount').on('input',function(){
                                    if($(this).val()=='')
                                    {
                                        $(this).disableButton('update-shipping');
                                    }
                                    else
                                    {
                                        $(this).EnableButton('update-shipping');
                                        $('.update-shipping').click(function(){ if(!once){ $(this).update_shipping_rate(); once=true; } });
                                    }
                                });
                            });
                        });
                    }
                });
            });
        });
    })
}
$.fn.update_shipping_rate = function()
{
    var data = {
        country: $('.country').val(),
        state: $('.state').val(),
        lga: $('.lga').val(),
        amount: $('.amount').val()
    }
    var fdata = new FormData();

    content = $('.modal-body').html();

    fdata.append('update_shipping_rate','');
    fdata.append('country',data.country);
    fdata.append('state',data.state);
    fdata.append('lga',data.lga);
    fdata.append('amount',data.amount);

    $('.modal-body > .ship').addClass('animated fadeOutDown').fadeOut(1000).promise().done(function(){
        $('.modal-body').html(loader).fadeIn(1000).promise().done(function(){
            $.ajax({
                type: 'post',
                url: '../api/objects/controllers/',
                processData: false,
                contentType: false,
                data: fdata,
                success: function(result){
                    switch(result.replace('denied',''))
                    {
                        case 'done':
                            $('.linear').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                                $('.modal-body').css({'position':'relative'}).html('<div class="success-msg transition animated fadeInDown shp-ok notf"><div><i class="lni-check-mark-circle"></i></div><div class="message">Shipping rate was successfully updated!</div></div>').fadeIn(1000).promise().done(function(){
                                    interval = setInterval(removeNotification, 2000);
                                });
                            });
                            break;
                        default:
                            $('.linear').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                                $('.modal-body').css({'position':'relative'}).html('<div class="error transition animated fadeInDown shp-er notf"><div><i class="lni-cross-circle"></i></div><div class="message">'+result.replace('denied','')+'!</div></div>').fadeIn(1000).promise().done(function(){
                                    interval = setInterval(removeNotification, 2000);
                                });
                            });
                            break;
                    }
                }
            });
        });
    });
}
$.fn.load_shipping_list = function()
{
    $.ajax({
        type: 'post',
        url: '../api/objects/controllers/',
        data: { load_shipping_list: '' },
        success: function(result){
            $('.linear').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                $('.modal-body').html(result.replace('denied','')).fadeIn(1000).promise().done(function(){
                    $('.modal-footer').remove();
                });
            });
        }
    });
}
$.fn.refund_pane_content = function()
{
    $.ajax({
        type: 'post',
        url: '../api/objects/controllers/',
        data: { refund_pane_content: '' },
        success: function(result){
            $('.refund > div').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                $('.refund').html(result.replace('denied','')).fadeIn(1000).promise().done(function(){
                    $('.manage-refund').currency_manager_luncher();
                });
            });
        }
    });
}


function get_current_set_rate(curr)
{
    $('.rate').parent().css({'flex-direction':'column'}).prepend(loader).fadeIn(1000).promise().done(function(){
        $.ajax({
            type: 'post',
            url: '../api/objects/controllers/',
            data: { get_current_set_rate: curr },
            success: function(result){
                $('.group > .linear').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                    $(this).remove().promise().done(function(){
                        $('.rate').removeAttr('disabled').val(result.replace('denied','')).EnableButton('update-currency');
                        $('.update-currency').click(function(){
                            $(this).update_currency_rate(curr);
                        });
                    });
                });
            }
        });
    });
}
function get_shipping_form()
{
    $('.modal-body > div').addClass('animated fadeOutDown').fadeOut(1000).promise().done(function(){
        $('.modal-body').html(loader).fadeIn(1000).promise().done(function(){
            $.ajax({
                type: 'post',
                url: '../api/objects/controllers/',
                data: { provide_Delivery_rate: '' },
                success: function(result){
                    $('.linear').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                        $('.modal-body').html(result.replace('denied','')).fadeIn(1000).promise().done(function(){
                            $('.modal-wrapper').append("<div class='modal-footer animated fadeInUp'><button class='button modal-button button-success button-rounded transition update-shipping'>&nbsp;&nbsp; UPDATE SHIPPING RATE &nbsp;&nbsp;<i class='transition lni-angle-double-right'></i></button></div>").fadeIn(1000).promise().done(function(){
                                $(this).disableButton('update-shipping');
                                $('.amount').attr('disabled','disabled');
                            });

                            populateCountries('country','state');
                            $('.state').on('change',function(){ populateLGA("state", "lga"); $('.amount').removeAttr('disabled'); });
                            $('.amount').on('input',function(){
                                if($(this).val()=='')
                                {
                                    $(this).disableButton('update-shipping');
                                }
                                else
                                {
                                    $(this).EnableButton('update-shipping');
                                    $('.update-shipping').click(function(){ if(!once){ $(this).update_shipping_rate(); once=true; } });
                                }
                            });
                        });
                    });
                }
            });
        });
    });
}



/**
 * General functions and procedures that manages backend
 * @General Backend
 */
$.fn.loadAdminProfile = function()
{
    $.ajax({
        type: 'post',
        url: '../api/objects/controllers/',
        data: {admin_profile:'content'},
        success: function(result){
            $('.user-info > div > div > div').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                $('.user-info > div').html(result.replace('denied',''));
                $('.backend-container').html(loader).fadeIn(1000).promise().done(function(){
                    $(this).loadBackendContent();
                });
            });
        }
    });
}
$.fn.loadBackendContent = function()
{
    $.ajax({
        type: 'post',
        url: '../api/objects/controllers/',
        data: {backend_content:''},
        success: function(result){
            $('.linear').addClass('amimated fadeOutDown').fadeOut(1000).promise().done(function(){
                $('.backend-container').html(result.replace('denied','')).fadeIn(1000).promise().done(function(){
                    $('.backend-container > div').html(loader).fadeIn(1000).promise().done(function(){
                        $('.backend-users').manageBackendUsers();
                    });
                });
            });
        }
    });
}
$.fn.manageBackendUsers = function()
{
    $.ajax({
        type: 'post',
        url: '../api/objects/controllers/',
        data: {backend_users:''},
        success: function(result){
            $('.backend-users > div.linear').addClass('amimated fadeOutDown').fadeOut(1000).promise().done(function(){
                $('.backend-users').html(result.replace('denied','')).fadeIn(1000).promise().done(function(){
                    $('.manage-users').loadBackendUserForm();

                    if($('.users-with-order').attr('class')!=undefined)
                    {
                        $(this).loadUserWithOrders();
                    }
                });
            });
            $('.finance > div.linear').addClass('animated fadeOut').promise().done(function(){
                $('.finance').finance_panel_manager();
            });
            $('.cust-order > div.linear').addClass('animated fadeOut').fadeOut(1000).promise().done(function(){
                $('cust-order').loadAdminBackendCustomOrders();
            });
        }
    });
}
$.fn.preloadEmptyList = function(param,msg)
{
    $('.'+param).html("<div class='transition'><div style='display:flex;' class='animated fadeInDown'><span><i class='lni-warning' style='font-size:32px;'></i></span>&nbsp;&nbsp;<h4>Content is Empty!</h4></div><div class=' animated fadeInDown border'><div></div></div><div style='margin-bottom:10px;' class='animated fadeInDown'>"+msg+"</div><div class='user-list animated fadeInDown transition' style='display:flex;justify-content:center;align-items:center;align-content:center;padding:50px;'><i class='icon icon-basic-folder-multiple' style='color:orange;font-size:42px;'></i></div></div>");
}
$.fn.showEmptyRecords = function(elem, content)
{
    $('.'+elem).html("<div style='display:flex;' class='transition'><div style='flex-basis:2%;'><i class='lni-question-circle' style='font-size:28px;'></i></div><div style='flex-basis:98%;margin-left:10px;' class='user-list transition'>"+content+"</div></div>");
}