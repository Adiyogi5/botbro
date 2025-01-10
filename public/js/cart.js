
let site_url = $("meta[name='site_url']").attr('content');
let csrf_token = $("meta[name='csrf-token']").attr('content');

function init_cart()
{
    
    $(".add_cart").unbind("click");
    $(".add_cart").click(function(e)
    {
        e.preventDefault();

        let parent = $(this).closest('.cart-class');
        
        let product_id    = $(parent).find('.product_id').val();
        let product_type  = +$(parent).find('.product_type').val();
        let quantity      = 1;
        let href          = $(this).data('href');
        
        let no_redirect   = +$(parent).find('.no-redirect').length;
        
        if($(parent).find('.cart_quantity').length)
        {
            quantity = $(parent).find('.cart_quantity').val();
        }
        
        if(product_type && !attr_id)
        {
            alert_error("Please select a variant first.");
            setTimeout(() => {
                if(no_redirect !== 1)
                {
                    location.href = href;
                }
            }, 500);
        }
        else
        {
            add_to_cart(product_id,quantity);
        }
    })

}

function add_to_cart(product_id ,quantity=1) 
{
    $.ajax({
        url:site_url+"/add-to-cart",
        type:'POST',
        data:{
            _token: csrf_token,
            product_id: product_id,
            quantity: quantity
        },
        product_id: product_id,
        quantity: quantity,
        success:function(response)
        {  
            alert_success("The product has been successfully added to your cart.");
            update_cart_count();
        },
        error:function(response){
            alert_error(response.responseJSON.message);
        }
    })
}

function update_cart_count()
{
$.ajax({
    url:site_url+"/get-cart-count",
    success:function(response)
    {
        $('.cart_counter').text(response.data.cart_total);
    }
})
}

function init_update_cart()
{ 
$(".cart_page_quantity").unbind("change");

$('.cart_page_quantity').change(function(){
    
    let qty = this.value;
    let cart_id = $(this).data('id');

    showLoader();

    $.ajax({
        url:site_url+"/update-cart",
        data:{quantity:qty,cart_id:cart_id},
        success:function(response)
        {
            alert_success("The quantity has been updated successfully.");

            update_cart_count();
            re_render_cart();
        },
        error:function(response){
            alert_error(response.responseJSON.message);
            re_render_cart();
        }
    })


})
}

function re_render_cart()
{
    $.ajax({
        url:site_url+"/cart",
        success:function(response)
        {   
            if($(response).find('.cart-items').length)
            {
                
                $('.cart-items').html($(response).find('.cart-items').html());
                $('.amount_box').html($(response).find('.amount_box').html());
                $("#error_div").html($(response).find("#error_div").html());
                hideLoader();

                
            }
            else
            {
                location.reload();
            }

            setTimeout(() => {
                $(".alert-dismissible").slideUp();
            }, 5000);
            
            setTimeout(() => {
                init_update_cart();
                init_quantity();
                hideLoader();
            }, 200);
        },
        error:function(response){
            location.reload();
        }
    })
}

function remove_from_cart(cart_id)
{
    $.ajax({
        url:site_url+"/remove-from-cart",
        data:{cart_id:cart_id},
        success:function(response)
        {
            alert_success("The product has been successfully removed.");

            update_cart_count();
            re_render_cart();
        },
        error:function(response){
            alert_error(response.responseJSON.message);
            re_render_cart();
        }
    })
}

function apply_coupan()
{
    let code = $("#coupan_code").val();

    $.ajax({
        url:site_url+"/check-coupon",
        data:{coupon:code},
        success:function(response)
        {
            alert_success("The coupon code has been successfully applied.");
            $("#coupan_code").val('');
            re_render_cart();
        },
        error:function(response){
            alert_error(response.responseJSON.message);
            re_render_cart();
        }
    })

}

function remove_coupan()
{
    $.ajax({
        url:site_url+"/remove-coupon",
        success:function(response)
        {
            $(".coupan_div").remove();
            alert_success("The coupon has been successfully removed.");
            re_render_cart();
        },
        error:function(response){
            alert_error(response.responseJSON.message);
            re_render_cart();
        }
    })
}

function apply_reward_points()
{
    let reward_points = +$("#reward_points").val();
    
    if(reward_points)
    {
        $.ajax({
            url:site_url+"/check-reward-points",
            data:{reward_points:reward_points},
            success:function(response)
            {
                alert_success("Reward points applied successfully");
                $("#reward_points").val('');
                re_render_cart();
            },
            error:function(response){
                alert_error(response.responseJSON.message);
                re_render_cart();
            }
        })
    }
    else
    {
        alert_error("Please enter reward points");
    }
}

function remove_reward_points()
{
    $.ajax({
        url:site_url+"/remove-reward-points",
        success:function(response)
        {
            alert_success("Reward points removed successfully");
            $("#reward_points").val('');
            re_render_cart();
        },
        error:function(response){
            alert_error(response.responseJSON.message);
            re_render_cart();
        }
    })
}

function empty_cart()
{
    $.ajax({
        url:site_url+"/empty-cart",
        success:function(response)
        {
            location.reload();
        },
        error:function(response){
            alert_error(response.responseJSON.message);
            
        }
    })
}

function re_order(elem)
{
    let parent = $(elem).parent().parent();
    
    let product_id = $(parent).find('.product_id').val();
    let user_id = $(parent).find('.user_id').val();
    let master_id = $(parent).find('.master_id').val();
    let attr_id = $(parent).find('.attr_id').val();
    let product_type = +$(parent).find('.product_type').val();
    let quantity = +$(parent).find('.quantity').val();
    
    if(product_type && !attr_id)
    {
        alert_error("Please select an attribute.");
    }
    else
    {
        add_to_cart(product_id, master_id,attr_id,quantity);
    }

}

function return_product(elem)
{
    let order_product_id = $(elem).data('order-product-id');
    let product_id = $(elem).data('product-id');
    let order_id = $(elem).data('order-id');
    
    $("#product_id").val(product_id);    
    $("#order_product_id").val(order_product_id);    
    $("#order_id").val(order_id);
    $("#comment").val('');    
    $("#return_reason_id").val('');    

    $("#return_modal").modal('show');
}

function add_review(elem)
{   

    let product_id = $(elem).data('product-id');

    $("#rating").val(0);
    $("#review").val('');
    $("#review_product_id").val(product_id);

    $("#starts").rating({
        "color":"#ce160e",
        "click":function (e) {
            $("#rating").val(e.stars);
        }     
    });

    $("#review_modal").modal('show');
}

// get shipping methods
$("#shipping_address").change(function(){

    let address_id = +this.value;

    if(address_id)
    {   
        $.ajax({
            url:site_url+"/get-shipping-methods",
            data:{address_id:address_id},
            success:function(response)
            {
                $("#payment_methods").html(response);

                setTimeout(() => {
                    $(".payment-method:first").trigger('click');
                }, 200);
            },
            error:function(response){
                alert_error(response.responseJSON.message);
            }
        })
    }
    else
    {
        $("#payment_methods").html('<p class="text-danger">Please choose your address.</p>');
    }

})


function calculate_shipping(elem)
{
    let address_id = +$('#shipping_address').val();

    let payment_method = + elem.value;

    if(address_id && payment_method)
    {
        $.ajax({
            url:site_url+"/calculate-shipping",
            data:{address_id:address_id,payment_method:payment_method},
            success:function(response)
            {
                $("#checkout-html").html(response);
            },
            error:function(response){
                alert_error(response.responseJSON.message);
                if(response.responseJSON.redirect)
                {
                    setTimeout(() => {
                        location.href = response.responseJSON.redirect;
                    }, 1000);
                }
            }
        })
    }
    else
    {
        alert_error("Please select address & payment method first");
    }
}