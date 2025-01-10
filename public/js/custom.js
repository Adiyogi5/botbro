    
    
    setTimeout(() => {
        $("#loader").fadeOut();
    },500);

    function alert_success(msg)
    {
        toastr.success(msg);
    }

    function alert_error(msg)
    {
        toastr.error(msg);
    }

    function alert_warning(msg)
    {
        toastr.warning(msg);
    }

    function showLoader()
    {
        $("#loader").fadeIn();
    }

    function hideLoader()
    {
        $('#loader').fadeOut();
    }

    function alert_error(msg)
    {
        toastr.error(msg);
    }

    function init_quick_view_galley(id='')
    {
       
        $(".mini-image").click(function(){
            
            let parent = $(this).parentsUntil('.row');
          
            let image = $(this).data('src');
        
            let main_image = $(parent).find('.main_image');
           
            main_image.fadeOut('fast', function () {
            
                main_image.attr('src', image);
                main_image.fadeIn('fast');
            });
    
            $(parent).find('.mini-image').removeClass('active');
            $(this).addClass('active');

        })
    }

    function init_attributes()
    {

        $(".form-check-input-attr").click(function()
        {   
            
            parent = $('.cart-class');
            
            let new_old_price  = $(parent).find('.new_old_price');
            

            let image_div  = (parent).find('.main_image');
            
            let values = [];

            $(parent).find('.form-check-input:checked').each(function(){
                values.push($(this).val());
            })
           
            let sorted_ids = values.sort((a, b) => a - b);
            
            $(variant_attributes).each(function(index,elem){
                
                let attr_ids = elem.sort_attribute_id;
                

                var isMatched = attr_ids.toString() === sorted_ids.toString();
               
                if(isMatched)
                {
                    let price = +elem.price;
                    let special_price = +elem.special_price;
                    let attribute_ids = elem.attribute_ids;
                    let attribute_master_id = elem.attribute_master_id;
                    let images = elem.attribute_images;
                    let default_image = elem.default_image;
                  
                    let price_html = '';
                   
                      
                    if(price > special_price && special_price > 0 && special_price != null)
                    {
                        price_html = '<p class="pro-discount"> ₹'+special_price+'<span class="pro-mrp">₹'+price+'</span></p>';   
                    }
                    else
                    {                       
                        price_html = '<p class="pro-discount"> ₹'+price+'</p>';
                    }

                    new_old_price.animate({ opacity: 0 }, 0);
                    
                    $(new_old_price).html(price_html).animate({ opacity: 1 },1000);

                    image_div.animate({ opacity: 0 }, 200);
                    
                    $(image_div).attr('src',default_image).animate({ opacity: 1 },500);
                

                    $(parent).find('.master_id').val(attribute_master_id);
                    $(parent).find('.attr_id').val(attribute_ids);

                    let gallary_thumbs = '';

                    if(images)
                    {   
                        $(images).each(function(index,elem){
                            let active = '';

                            if(index == 0)
                            {
                                active='active';
                            }

                            gallary_thumbs += '<div class="item cursor-pointer"> <img data-src="'+ elem.image +'" class="img-pro-detail '+active+' mini-image" src="'+elem.thumb+'"></div>'
                        })
                    }

                    

                    let gallary_template = '<div id="thumb_carousel" class="cf d-inline-flex text-center justify-content-between owl-carousel thumb-carousel ">'+gallary_thumbs+'</div>';

                    $("#thumb_carousel").remove();
                    $("#featured_img").after(gallary_template);

                   $("#thumb_carousel").owlCarousel({
                        loop: false,
                        margin: 10,
                        autoplay: false,
                        dots: false,
                        nav: false,
                        responsive: {
                            0: {
                                items: 5
                            },
                            600: {
                                items: 4
                            },
                            1000: {
                                items: 5
                            }
                        }
                    });

                    init_quick_view_galley();

                    init_cart();
                    hideLoader();
                    return false;

                }
                else
                {
                    $(parent).find('.master_id').val('');
                    $(parent).find('.attr_id').val('');
                    $(new_old_price).html("<p class='text-danger no-combo'>Combination Not Available<br/><small class='fw-normal'>Please select other variant</small></p>");
                }

            })
            

        })
    }

    function init_quantity()
    {

        $(".qty_minus").click(function()
        {
            let qty = + $(this).next().val();
            
            if(qty>1)
            {
                $(this).next().val(qty-1);
                $(this).next().trigger('change');
            }

        })


        $(".qty_plus").click(function()
        {
            let qty = + $(this).prev().val();

            $(this).prev().val(qty+1);
            $(this).prev().trigger('change');
            
        })

    }


    // Like Product
    function init_like()
    {
        $('.like_product').click(function(event){

            event.preventDefault();

            showLoader();
            
            let elem = this;
        
            let product_id = $(this).data('product');
            let user_id = $(this).data('user');
        
            if(!product_id)
            {
                alert_error("This is an invalid request. Please reload the page and try again.");
                hideLoader();
                return false;
            }
            else if(!user_id)
            {
                alert_error("To add a product to your list, please log in first.");
                hideLoader();
                return false;
            }
        
            let site_url = $("meta[name='site_url']").attr('content');
        
            $.ajax({
                url:site_url+'/edit-my-list',
                data:{product_id:product_id},
                success:function(success)
                {
                        alert_success("The product has been successfully added to your list.");
                        $(elem).parent().find('.rm_like_product').removeClass('d-none');
                        $(elem).addClass('d-none');
                        hideLoader();
                },
                error:function(success)
                {
                    alert_error("An error occurred. Please try again");
                    hideLoader();
                }
            })
        
        })
        
        $('.rm_like_product').click(function(event){

            event.preventDefault();

            showLoader();
            
            let product_id = $(this).data('product');
            let user_id = $(this).data('user');
        
            let elem = this;
        
            if(!product_id)
            {
                alert_error("This is an invalid request. Please reload the page and try again.");
                hideLoader();
                return false;
            }
            else if(!user_id)
            {
                alert_error("To add a product to your list, please log in first.");
                hideLoader();
                return false;
            }
        
            let site_url = $("meta[name='site_url']").attr('content');
        
            $.ajax({
                url:site_url+'/edit-my-list',
                data:{product_id:product_id},
                success:function(success)
                {
                        alert_success("The product has been successfully removed from your list.");
                        $(elem).parent().find('.like_product').removeClass('d-none');
                        $(elem).addClass('d-none');
                        hideLoader();
                   
                },
                error:function(success)
                {
                    alert_error("An error occurred. Please try again");
                    hideLoader();
                }
            })
        
        })
    }

    //  Product Details Page gallry
    
 
 

 


