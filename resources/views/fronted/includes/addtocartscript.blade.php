@if(Auth::guard('customer')->check())
<script> $(document).ready(function() {
        window.user_login = 1;
        update_wishlist();
    }); </script>
@else
    <script> $(document).ready(function() {
        window.user_login = 0;
    }); </script>
@endif
    <script src = "{{ asset('public/fronted/js/jquery.jcarousellite.min.js') }}" >
    </script>
     <script >
    localStorage.setItem("pincodechecked", 0);
$("#rateit").bind('rated', function(event, value) {
    $('#rating_value').val(value);
});
$("#rateit").bind('reset', function() {
    $('#rating_value').val(0);

});
$(document).ready(function() {
    var pincode = readCookie("pincode");
    var shipping_address_id = readCookie("shipping_address_id");
    var pincode_error = readCookie("pincode_error");
    if (pincode) {
        $('#pincode').val(pincode);
        if (pincode_error == 1) {
            $('.outofDelivery').show();
            $('.noProblem').hide();
            $('#pincode_msg').html('No delivery available in your port code');
        } else {
            $('.outofDelivery').hide();
            $('.noProblem').show();
            $('#pincode_msg').html('Delivery available in your port code');
        }
    }
    window.color_id = 0;
    window.size_id = 0;
    window.size_color_require = 0;
    setTimeout(function() {
        $(".alert_message").hide();
        $(".alert").hide();
        $(".help-block").hide();
    }, 100000);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    update_cart();
});
$(document).ready(function() {
    setTimeout(function() {
        $("#rateit-reset-3").trigger("click");
        $("#rateit-reset-2").trigger("click");
    }, 2000);
    window.color_id = 0;
    window.size_id = 0;
    window.w_size_id = 0;
    $(".customThumnnail").on('click', function(event) {
        console.log("sfsdfdfdffdd");
        var img = $(this).attr('data-large-image');
        $('.imgs').attr('src', img);
        $('.imgs').attr('data-zoom-image', img);
        $('.fancyZoom').attr('href', img);
        $('.zoomWindow').css('background-image', 'url("' + img + '")');

    });
          $(".otherSeller").click(function() {

                    var prd_id = $(this).attr('prd_id');

                    var token = "{{ csrf_token() }}";
                    $.ajax({
                        type: 'POST',
                        async: false,
                        url: "{{ route('otherSeller') }}",
                        data: {
                            "prd_id": prd_id,
                            "_token": token
                        },
                        success: function(data) {
                            var myObj = JSON.parse(data);
                                $('#otherSellerModal').modal('show');
                                $(".otherSellerBodyResponse").html(myObj.products);

                        }
                    });
          });
    $(".compareProduct").click(function() {
        var prd_id = $(this).attr('prd_id');
        var token = "{{ csrf_token() }}";
        $.ajax({
            type: 'POST',
            async: false,
            url: "{{ route('addProductToCompare') }}",
            data: {
                "prd_id": prd_id,
                "_token": token
            },
            success: function(data) {
                var myObj = JSON.parse(data);
                $(".wishlistModalResponse").html(myObj.msg);
                $('#wishlistModal').modal('show');
                setTimeout(function() {
                    $('#wishlistModal').modal('hide');
                    $(".wishlistModalResponse").html("");
                }, 2000);
            }
        });
    });
    $(".RemovecompareProduct").click(function() {
        var prd_id = $(this).attr('prd_id');
        var token = "{{ csrf_token() }}";
        $.ajax({
            type: 'POST',
            async: false,
            url: "{{ route('RemovecompareProduct') }}",
            data: {
                "prd_id": prd_id,
                "_token": token
            },
            success: function(data) {
                var myObj = JSON.parse(data);
                $(".compareProductResponse").html(myObj.compareProductResponse);
            }
        });
    });
    $(".addTocart").click(function() {

        var size_require = $(this).attr('size_require');
        var color_require = $(this).attr('color_require');
        var size_id = $(this).attr('size_id');
        var color_id = $(this).attr('color_id');
        window.color_id = color_id;
        var prd_page = $(this).attr('prd_page');
        var url = $(this).attr('url');
        var chekced = localStorage.getItem("pincodechecked");
        if (chekced == null || chekced == 1) {
            if (prd_page == 0) {
                window.location.href = url;
            } else {
                if (chekced == null) {
                    $('#wishlistModal').modal('show');
                    $(".wishlistModalResponse").html("Please check your area pincode.");
                    setTimeout(function() {
                        $('#wishlistModal').modal('hide');
                    }, 2000);

                }
                if (chekced == 1) {
                    $('#wishlistModal').modal('show');
                    $(".wishlistModalResponse").html("Delivery not available for your location");
                    setTimeout(function() {
                        $('#wishlistModal').modal('hide');
                    }, 2000);
                }
            }
        } else {
            if (prd_page == 0) {
                if (size_require == 1 || color_require == 1) {
                    window.location.href = url;
                } else {
                    if (size_require == 0 && color_require == 0) {
                        addToCart(this, 0, 0);
                    }
                }
            } else {
                if (size_require == 0 && color_require == 0) {
                    addToCart(this, 0, 0);
                } else if (size_require == 1 && color_require == 0) {
                    if (size_id == 0) {
                        if (window.size_id == 0) {
                            alert("Select size");
                        } else {
                            addToCart(this, 0, window.size_id);
                        }
                    } else {
                        addToCart(this, 0, size_id);
                    }
                } else if (size_require == 0 && color_require == 1) {
                    if (color_id == 0) {
                        if (window.color_id == 0) {
                            alert("Select Color");
                        } else {
                            addToCart(this, window.color_id, 0);
                        }
                    } else {
                        addToCart(this, color_id, 0);
                    }
                } else {
                    if (size_id != 0 && color_id != 0) {
                        addToCart(this, color_id, size_id);
                    } else if (size_id !== 0 && color_id != 0) {
                        if (window.size_id == 0) {
                            alert("Select size");
                        } else {
                            addToCart(this, color_id, window.size_id);
                        }
                    } else if (size_id != 0 && color_id == 0) {
                        if (window.color_id == 0) {
                            alert("Select Color");
                        } else {
                            addToCart(this, window.color_id, size_id);
                        }
                    } else {
                        if (window.color_id == 0 && window.size_id == 0) {
                            alert("Select Color and Size");
                        }
                        if (window.color_id != 0 && window.size_id == 0) {
                            alert("Select size");
                        }
                        if (window.color_id == 0 && window.size_id != 0) {
                            alert("Select color");
                        }
                        if (window.color_id != 0 && window.size_id != 0) {
                            addToCart(this, window.color_id, window.size_id);
                        }
                    }
                }
            }
        }
    });
    $(".showSizechart").click(function() {
        var prd_id = $(this).attr('prd_id');
        var token = "{{ csrf_token() }}";
        $.ajax({
            type: 'POST',
            async: false,
            url: "{{ route('get_size_chart') }}",
            data: {
                "prd_id": prd_id,
                "_token": token
            },
            success: function(data) {
                var myObj = JSON.parse(data);
                $("#showsizechart").modal('show');
                $(".showsizechartResponse").html(myObj.data);
            }
        });
    });
    $(".buyNow").click(function() {
        var size_require = $(this).attr('size_require');
        var color_require = $(this).attr('color_require');
        var size_id = $(this).attr('size_id');
        var color_id = $(this).attr('color_id');
        var prd_page = $(this).attr('prd_page');
        var url = $(this).attr('url');
        var chekced = localStorage.getItem("pincodechecked");
        if (chekced == null || chekced == 1) {
            if (prd_page == 0) {
                window.location.href = url;
            } else {
                if (chekced == null) {
                    $('#wishlistModal').modal('show');
                    $(".wishlistModalResponse").html("Please check your area pincode.");
                    setTimeout(function() {
                        $('#wishlistModal').modal('hide');
                    }, 2000);
                }
                if (chekced == 1) {
                    $('#wishlistModal').modal('show');
                    $(".wishlistModalResponse").html("Delivery not available for your location");
                    setTimeout(function() {
                        $('#wishlistModal').modal('hide');
                    }, 2000);
                }
            }
        } else {
            if (prd_page == 0) {
                if (size_require == 1 || color_require == 1) {
                    window.location.href = url;
                } else {
                    if (size_require == 0 && color_require == 0) {
                        buyNowCart(this, 0, 0);
                    }
                }
            } else {
                if (size_require == 0 && color_require == 0) {
                    buyNowCart(this, 0, 0);
                } else if (size_require == 1 && color_require == 0) {
                    if (size_id == 0) {
                        if (window.size_id == 0) {
                            alert("Select size");
                        } else {
                            buyNowCart(this, 0, window.size_id);
                        }
                    } else {
                        buyNowCart(this, 0, size_id);
                    }
                } else if (size_require == 0 && color_require == 1) {
                    if (color_id == 0) {
                        if (window.color_id == 0) {
                            alert("Select Color");
                        } else {
                            buyNowCart(this, window.color_id, 0);
                        }
                    } else {
                        buyNowCart(this, color_id, 0);
                    }
                } else {
                    if (size_id != 0 && color_id != 0) {
                        buyNowCart(this, color_id, size_id);
                    } else if (size_id == 0 && color_id != 0) {
                        if (window.size_id == 0) {
                            alert("Select size");
                        } else {
                            buyNowCart(this, color_id, window.size_id);
                        }
                    } else if (size_id != 0 && color_id == 0) {
                        if (window.color_id == 0) {
                            alert("Select Color");
                        } else {
                            buyNowCart(this, window.color_id, size_id);
                        }
                    } else {
                        if (window.color_id == 0 && window.size_id == 0) {
                            alert("Select Color and Size");
                        } else if (window.color_id != 0 && window.size_id == 0) {
                            alert("Select size");
                        } else if (window.color_id == 0 && window.size_id != 0) {
                            alert("Select color");
                        } else {
                            buyNowCart(this, window.color_id, window.size_id);
                        }
                    }
                }
            }
        }
    });
    $(".brandSelection").change(function() {
        var pos = $(this).attr('pos');
        var brand = $(this).val();
        $('#productSelection' + pos).children('option:not(:first)').remove();
        var token = "{{ csrf_token() }}";
        $.ajax({
            type: 'POST',
            async: false,
            url: "{{ route('brandSelection') }}",
            data: {
                "brand": brand,
                "_token": token
            },
            success: function(data) {
                var myObj = JSON.parse(data);
                $("#productSelection" + pos).append(myObj.html);
            }
        });
    });
    $(".productSelection").change(function() {
        var prd_id = $(this).val();
        var token = "{{ csrf_token() }}";
        $.ajax({
            type: 'POST',
            async: false,
            url: "{{ route('addProductToCompare') }}",
            data: {
                "prd_id": prd_id,
                "_token": token
            },
            success: function(data) {
                var myObj = JSON.parse(data);
                $(".wishlistModalResponse").html(myObj.msg);
                $('#wishlistModal').modal('show');
                setTimeout(function() {
                    $('#wishlistModal').modal('hide');
                    $(".wishlistModalResponse").html("");
                    var prd_id = 0;
                    var token = "{{ csrf_token() }}";
                    $.ajax({
                        type: 'POST',
                        async: false,
                        url: "{{ route('RemovecompareProduct') }}",
                        data: {
                            "prd_id": prd_id,
                            "_token": token
                        },
                        success: function(data) {
                            var myObj = JSON.parse(data);
                            $(".compareProductResponse").html(myObj.compareProductResponse);
                        }
                    });
                }, 2000);
            }
        });
    });
    $(document).on('click', '.sizeClass', function() {

          $('#size_name').html($(this).attr('size_name'));
        var size_id = $(this).attr('size_id');
        $('.addTocart').attr('size_id', size_id);
        $('.buyNow').attr('size_id', size_id);
        $('.waddTocart').attr('size_id', size_id);
        $('.wbuyNow').attr('size_id', size_id);
        window.size_id = size_id;
        $(".sizeClass").removeClass("active");
        $(this).addClass('active');
        var prd_id = $('.addTocart').attr('prd_id');
        var color_id = $('.addTocart').attr('color_id');
        var prd_type = $('.addTocart').attr('prd_type');
        sizeStock(prd_id, size_id, color_id, prd_type);
        getAttPrice(prd_id,color_id, size_id);
        //  getAttPrice(prd_id,0, size_id);
    });

    $(document).on('click', '.wsizeClass', function() {
        var size_id = $(this).attr('w_size_id');
        $('.addTocart').attr('w_size_id', size_id);
        $('.buyNow').attr('w_size_id', size_id);
        $('.waddTocart').attr('w_size_id', size_id);
        $('.wbuyNow').attr('w_size_id', size_id);
        window.w_size_id = size_id;
        $(".wsizeClass").removeClass("active");
        $(this).addClass('active');
    });

    function getAttPrice(prd_id, color_id, size_id) {
        var token = "{{ csrf_token() }}";
        $.ajax({
            type: 'POST',
            async: false,
            url: "{{ route('getAttPrice') }}",
            data: {
                "size_id": size_id,
                "prd_id": prd_id,
                "color_id": color_id,
                "_token": token
            },
            success: function(data) {
                var myObj = JSON.parse(data);
                $("#prd_price_0").html(myObj.new_price);
                $("#prd_old_price_0").html(myObj.old_price);
                $(".percent").html(myObj.percent);
                $("#saveamt"+prd_id).html(myObj.saveAmount);

                if ((myObj.qty) == 0) {
                    $('.noProblem').hide();
                    $('.outOfStcok').show();
                    var html = '<p><strong>Availability</strong>';
                    html += ':<span class="in-stock"> Out of stock</span></p>';
                    $("#qtyDiv").html(html);
                } else {
                    if ($('#pincode').val() != '') {
                        $('.checkPinCode').trigger('click');
                    } else {
                        $('.noProblem').show();
                    }
                    $('.outOfStcok').hide();
                    var html = '<p> <strong>Availability</strong>';
                    html += ': <span class="out-stock clrred"> In stock</span></p>';
                    $("#qtyDiv").html(html);
                }
            }
        });
    }

    function sizeStock(prd_id, size_id, color_id, prd_type = 3) {
        var token = "{{ csrf_token() }}";
        $.ajax({
            type: 'POST',
            async: false,
            url: "{{ route('sizeStock') }}",
            data: {
                "size_id": size_id,
                "color_id": color_id,
                "prd_id": prd_id,
                "prd_type": prd_type,
                "_token": token
            },
            success: function(data) {
                var myObj = JSON.parse(data);
                if ((myObj.qty) == 0) {
                    $('.noProblem').hide();
                    $('.outOfStcok').show();
                    var html = '<p><strong>Availability</strong>';
                    html += ':<span class="in-stock"> Out of stock</span></p>';
                    $("#qtyDiv").html(html);
                } else {
                    $('.outOfStcok').hide();
                    $('.noProblem').show();
                    var html = '<p> <strong>Availability</strong>';
                    html += ': <span class="out-stock clrred"> In stock</span></p>';
                    $("#qtyDiv").html(html);
                }
            }
        });
    }
    $(document).on('click', '.colorClass', function() {
        window.color_id = color_id;
        window.size_id = 0;
        window.w_size_id = 0;
        $(".colorClass").html('');
        this.innerHTML = "<img src='{{asset('public/fronted/images/checkicon.png')}}'>";

        $(this).attr('prd_type')
        $('#color_box').css('background-color',$(this).attr('color_code'));
        $('#color_name').html($(this).attr('color_name'));
        $(".colorClass").removeClass("active");
        $(".sizeClass").removeClass("active");
        $(this).addClass('active');
        var color_id = $(this).attr('color_id');
        $('.addTocart').attr('color_id', color_id);
        $('.buyNow').attr('color_id', color_id);
        $('.waddTocart').attr('color_id', color_id);
        $('.wbuyNow').attr('color_id', color_id);
        $('.addTocart').attr('size_id', 0);
        $('.buyNow').attr('size_id', 0);
        $('.waddTocart').attr('size_id', 0);
        $('.wbuyNow').attr('w_size_id', 0);
        var prd_id = $(this).attr('prd_id');
        var prd_type = $(this).attr('prd_type');
        var token = "{{ csrf_token() }}";
        if (prd_type == 3 || prd_type == 2) {
            $.ajax({
                type: 'POST',
                async: true,
                url: "{{ route('get_attr_dependend') }}",
                data: {
                    "size_id": color_id,
                    "prd_id": prd_id,
                    "attr_name": 'Sizes',
                    "_token": token

                },
                success: function(data) {
                    var myObj = JSON.parse(data);
                    $("#" + myObj.print_to).html(myObj.html);
                     $("#size_name").html(myObj.name);
                    if (prd_type == 2) {
                        $("#wsizes_html").html(myObj.whtml);
                    }
                }
            });
            var options = {
                arrows: false,
                slidesToShow: 1,
                variableWidth: true,
                centerPadding: '10px'
            }
            var myObj = '';
            $.ajax({
                type: 'POST',
                async: true,
                url: "{{ route('setColoredImages') }}",
                data: {
                    "prd_id": prd_id,
                    "color_id": color_id,
                    "_token": token
                },
                success: function(data) {
                    myObj = JSON.parse(data);
                    getAttPrice(prd_id, color_id, 0);
                    if (myObj.size > 0) {
                        $('#img_zoom').attr('data-zoom-image', myObj.main_image);
                        $('#img_zoom').attr('src', myObj.main_image);
                        $("#facnyBoxitem").html(myObj.fancyBox_image);
                        $(".zoomWindow").css("background-image", "url(" + myObj.main_image + ")");
                        $("#leftthumbslidercontainer").empty();
                        $("#leftthumbslidercontainer").html(myObj.thumbnails);
                        $(".customThumnnail").on('click', function(event) {
                            var z_url = $(this).attr("src");
                            $("#img_zoom").attr("src", z_url);
                            $('.fancyZoom').attr('href', z_url);
                            $('.zoomWindow').css('background-image', 'url("' + z_url + '")');
                        });
                        if ($('.detail-gallery').length > 0) {
                            $('.detail-gallery').each(function() {
                                var data = $(this).find(".carousel").data();
                                $(this).find(".carousel").jCarouselLite({
                                    btnNext: ".nextMe",
                                    btnPrev: ".prevMe",
                                    speed: 400,
                                    visible: 5,
                                    vertical: true,
                                    circular: true,
                                    start: 0,
                                    scroll: 1
                                });
                            });

                        }


                    }
                }

            });
        }
    });
    $('.product_preview').on('click', 'a', function(event) {
        console.log("hello");
        var idx = $(this).attr('data-image');
        var fcnyItem = $(this).attr("fcnyItem");
        console.log(fcnyItem);
        $('#img_zoom').attr('data-zoom-image', idx).attr('src', idx);
        $(".zoomWindow").css("background-image", "url(" + idx + ")");
    });
    $(document).on('click', '.deleteItemFromWishList', function() {
        var response = confirm('Do you want to remove this from your wishlist?');
        if (response) {
            var prd_id = $(this).attr('prd_id');
            $.ajax({
                type: 'POST',
                async: false,
                url: "{{ route('removeWishlistItem') }}",
                data: {
                    "prd_id": prd_id
                },
                success: function(data) {
                    var myObj = JSON.parse(data);
                    if (myObj.Error == 0) {
                        $('#tableRow_' + prd_id).remove();
                          $('#wishlistModal').modal('show');
                        $(".wishlistModalResponse").html("Product removed from wishlist");
                        setTimeout(function() {
                            $('#wishlistModal').modal('hide');
                            location.reload();
                        }, 3000);

                    }
                }
            });
        }
    });
    $(document).on('click', '.deleteItemFromSavelater', function() {
        var response = confirm('Do you want to delete this ?');
        if (response) {
            var prd_id = $(this).attr('prd_id');
            $.ajax({
                type: 'POST',
                async: false,
                url: "{{ route('removeSavelaterItem') }}",
                data: {
                    "prd_id": prd_id
                },
                success: function(data) {
                    var myObj = JSON.parse(data);
                    if (myObj.Error == 0) {
                        $('#tableRow_' + prd_id).remove();
                    }
                }
            });
        }
    });
});
$(".wbuyNow").click(function() {
    var size_require = $(this).attr('size_require');
    var color_require = $(this).attr('color_require');
    var size_id = $(this).attr('size_id');
    var w_size_id = $(this).attr('w_size_id');
    var color_id = $(this).attr('color_id');
    var prd_page = $(this).attr('prd_page');
    var url = $(this).attr('url');
    if (window.size_id == 0 || window.color_id == 0 || window.w_size_id == 0) {
        $('#wishlistModal').modal('show');
        $(".wishlistModalResponse").html("Please select color and size");
        setTimeout(function() {
            $('#wishlistModal').modal('hide');
        }, 2000);
        return false;
    }
    wbuyNowCart(this, window.color_id, window.size_id, window.w_size_id);
});

function wbuyNowCart(obj, color_id, size_id, w_size_id) {
    var prd_index = $(obj).attr('prd_index');
    var prd_id = $(obj).attr('prd_id');
    var qty = $('#prd_qty_' + prd_index).val();
    var price = $("#prd_price_" + prd_index).text();
    var token = "{{ csrf_token() }}";
    $.ajax({
        type: 'POST',
        async: false,
        url: "{{ route('add_to_cart') }}",
        data: {
            "prd_index": prd_index,
            "prd_id": prd_id,
            "qty": qty,
            "price": price,
            "color_id": color_id,
            "size_id": size_id,
            "w_size_id": w_size_id,
            "_token": token
        },
        success: function(data) {
            var myObj = JSON.parse(data);
            if (myObj.method == 3) {
                $('#wishlistModal').modal('show');
                $(".wishlistModalResponse").html("No More Quantity In Stock");
                setTimeout(function() {
                    $('#wishlistModal').modal('hide');
                }, 2000);
            } else {
                if (myObj.method == 1) {
                    var buyNow = "{{route('cart')}}";
                    window.location.href = buyNow;
                } else {
                    var buyNow = "{{route('cart')}}";
                    window.location.href = buyNow;
                }
            }
        }
    });
}
$(".waddTocart").click(function() {
    var size_require = $(this).attr('size_require');
    var color_require = $(this).attr('color_require');
    var size_id = $(this).attr('size_id');
    var w_size_id = $(this).attr('w_size_id');
    var color_id = $(this).attr('color_id');
    window.color_id = color_id;
    var prd_page = $(this).attr('prd_page');
    var url = $(this).attr('url');
    var chekced = localStorage.getItem("pincodechecked");
    if (chekced == null || chekced == 1) {
        if (prd_page == 0) {
            window.location.href = url;
        } else {
            if (chekced == null) {
                $('#wishlistModal').modal('show');
                $(".wishlistModalResponse").html("Please check your area pincode.");
                setTimeout(function() {
                    $('#wishlistModal').modal('hide');
                }, 2000);
            }
            if (chekced == 1) {
                $('#wishlistModal').modal('show');
                $(".wishlistModalResponse").html("Delivery not available for your location");
                setTimeout(function() {
                    $('#wishlistModal').modal('hide');
                }, 2000);
            }
        }
    } else {
        if (window.size_id == 0 || window.color_id == 0 || window.w_size_id == 0) {
            $('#wishlistModal').modal('show');
            $(".wishlistModalResponse").html("Please select color and sizess");
            setTimeout(function() {
                $('#wishlistModal').modal('hide');
            }, 2000);
            return false;
        }
        waddToCart(this, color_id, size_id, w_size_id);
    }
});

function waddToCart(obj, color_id, size_id, w_size_id) {
    var prd_index = $(obj).attr('prd_index');
    var prd_id = $(obj).attr('prd_id');
    var qty = $('#prd_qty_' + prd_index).val();
    var price = $("#prd_price_" + prd_index).text();
    var token = "{{ csrf_token() }}";
    $.ajax({
        type: 'POST',
        async: false,
        url: "{{ route('add_to_cart') }}",
        data: {
            "prd_type": 2,
            "prd_index": prd_index,
            "prd_id": prd_id,
            "qty": qty,
            "price": price,
            "color_id": color_id,
            "size_id": size_id,
            "w_size_id": w_size_id,
            "_token": token
        },
        success: function(data) {
            var myObj = JSON.parse(data);
            if (myObj.method == 1) {
                $('#wishlistModal').modal('show');
                $(".wishlistModalResponse").html("Product added To Your Cart");
                update_cart();
            }
            if (myObj.method == 3) {
                $('#wishlistModal').modal('show');
                $(".wishlistModalResponse").html("No More Quantity In Stock");
            }
            if (myObj.method == 2) {
                $('#wishlistModal').modal('show');
                $(".wishlistModalResponse").html("Product Updated To Your Cart");
                update_cart();
            }
            setTimeout(function() {
                $('#wishlistModal').modal('hide');
            }, 2000);
        }
    });
}

function addToCart(obj, color_id, size_id) {
    var prd_index = $(obj).attr('prd_index');
    var prd_id = $(obj).attr('prd_id');
    var prd_type = $(obj).attr('prd_type');
    var qty = $('#prd_qty_' + prd_index).val();
    var price = $("#prd_price_" + prd_index).text();
    var token = "{{ csrf_token() }}";
    $.ajax({
        type: 'POST',
        async: false,
        url: "{{ route('add_to_cart') }}",
        data: {
            "prd_index": prd_index,
            "prd_id": prd_id,
            "qty": qty,
            "price": price,
            "color_id": color_id,
            "size_id": size_id,
            "prd_type": prd_type,
            "_token": token
        },
        success: function(data) {
            var myObj = JSON.parse(data);
            if (myObj.method == 1) {
                $('#wishlistModal').modal('show');
                $(".wishlistModalResponse").html("Product added To Your Cart");
                update_cart();
                $('#openCart').click();
            }
            if (myObj.method == 3) {
                $('#wishlistModal').modal('show');
                $(".wishlistModalResponse").html("No More Quantity In Stock");
            }
             if (myObj.method == 4) {
                $('#wishlistModal').modal('show');
                $(".wishlistModalResponse").html(myObj.msg);
            }
            if (myObj.method == 2) {
                $('#wishlistModal').modal('show');
                $(".wishlistModalResponse").html("Product Updated To Your Cart");
                update_cart();
                $('#openCart').click();
            }
            setTimeout(function() {
                $('#wishlistModal').modal('hide');
            }, 2000);
        }
    });
}

function buyNowCart(obj, color_id, size_id) {
    var prd_index = $(obj).attr('prd_index');
    var prd_id = $(obj).attr('prd_id');
    var prd_type = $(obj).attr('prd_type');
    var qty = $('#prd_qty_' + prd_index).val();
    var price = $("#prd_price_" + prd_index).text();
    var token = "{{ csrf_token() }}";
    $.ajax({
        type: 'POST',
        async: false,
        url: "{{ route('add_to_cart') }}",
        data: {
            "prd_index": prd_index,
            "prd_id": prd_id,
            "qty": qty,
            "price": price,
            "color_id": color_id,
            "size_id": size_id,
            "prd_type": prd_type,
            "_token": token
        },
        success: function(data) {
            var myObj = JSON.parse(data);
            if (myObj.method == 4) {
                    $('#wishlistModal').modal('show');
                    $(".wishlistModalResponse").html(myObj.msg);
                      setTimeout(function() {
                    $('#wishlistModal').modal('hide');
                }, 2000);
            }
           else if (myObj.method == 3) {
                $('#wishlistModal').modal('show');
                $(".wishlistModalResponse").html("No More Quantity In Stock");
                setTimeout(function() {
                    $('#wishlistModal').modal('hide');
                }, 2000);
            } else {
                if (myObj.method == 1) {
                    var buyNow = "{{route('cart')}}";
                    window.location.href = buyNow;
                } else {
                    var buyNow = "{{route('cart')}}";
                    window.location.href = buyNow;
                }
            }
        }
    });
} </script>
