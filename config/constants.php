<?php
return [
    'langs' => [
        'es' => 'www.domain.es',
        'en' => 'www.domain.us'
        // etc
    ],
	'Url' => [
	         'public_url' => "https://b2cdomain.in/kefih/",
    ],
    'Razorpay' => [
      'key' => "rzp_test_OsSZokXnscHxu2",
      'secret' => "0M7ZyGb9yc6pOP6BZZs4520U",
    ],

    'logisctics'=>[
                    "user_id"=>"1370",
                    "api_key"=>"547b973ca3bb38f911502e7ce46430b4",
                    "secret_key"=>"73892edc6f8ad6fcd345dfbb1f94a033",
                    "base_url"=>"https://apicouriers.getgologistics.com/"
                  ],
    'size' => [                        
        'banner_min'=>'1',
        'banner_max'=>'2048',
        'banner_dimensions'=>'Dimensions : 1920px * 650px', //Done
        'coupon_banner_dimensions'=>'Dimensions : 400px * 300px',//Done

        'logo_min'=>'1',
        'logo_max'=>'1024',
        'cat_logo_dimensions'=>'Dimensions : 300px * 278px', //Done
        'brand_logo_dimensions' =>'Dimensions : 255px * 255px',

        'app_icon_min'=>'1',
        'app_icon_max'=>'50',

        'size_chart_min'=>'1',
        'size_chart_max'=>'2048',
        'size_chart_dimensions' => 'Dimensions : 700px * 667px', //Done

        'product_img_min'=>'1',
        'product_img_max'=>'1024',
        'product_img_dimensions'=>'Dimensions : 1200px * 1500px', //Done

        // 'vendor_profile_image_min'=>'1',
        // 'vendor_profile_image_max'=>'500',
        // 'vendor_company_logo_min' =>'1',            
        // 'vendor_company_logo_max' =>'200',
        // 'vendor_cancel_cheque_image_min' =>'1',
        // 'vendor_cancel_cheque_image_max' =>'200',
        // 'vendor_pan_image_min' =>'1',
        // 'vendor_pan_image_max' =>'200',
        // 'vendor_gst_image_min' =>'1',
        // 'vendor_gst_image_max' =>'200',
        // 'vendor_sign_image_min' =>'1',
        // 'vendor_sign_image_max' =>'200',
        // 'vendor_profile_dimensions' =>'Dimensions : 100px * 100px',
        

        'blog_image_min'=> '1',
        'blog_image_max'=> '2048',
        'blog_image_dimensions' =>'Dimensions : 1000px * 621 px',

        'testimonial_image_min'=> '1',
        'testimonial_image_max'=> '1048',
        'testimonial_image_dimensions' =>'Dimensions : 90px * 90 px',

        'adimage_min'=> '1',
        'adimage_max'=> '2048',

        'offer_image_min'=> '1',
        'offer_image_max'=> '1048',
        'offer_image_dimensions' =>'Dimensions : 555px * 375 px',


        // 'whats_more_image_min'=> '1',
        // 'whats_more_image_max'=> '100',
        // 'whats_more_image_dimensions' =>'Dimensions : 245px * 131px',

        'page_banner_min'=>'1',
        'page_banner_max'=>'100',
        'page_banner_dimensions' =>'Dimensions : 1820px * 400px',

        'product_desc_img_min'=>'1',
        'product_desc_img_max'=>'50',
        'product_desc_img_dimensions'=>'Dimensions : 300px * 300px'
        ],

	'no_images'=>'public/images/no_image.png',
	'uploads' => [
        'brand_logo' => 'uploads/brand/logo',
        'product_images' => 'uploads/products',
          'product_images1' => 'uploads/product_images1',
        'customerCustomizedimage' => 'uploads/customerCustomizedimage',
        'product_size_chart' => 'uploads/sizechart',
        //'product_thumb_image' => 'uploads/products/480-360',
        'product_thumb_image' => 'uploads/products',
        'brand_banner' => 'uploads/brand/banner',
        'blog_banner' => 'uploads/blog/banner',
        'testimonial_banner' => 'uploads/testimonial/banner',
        'coupon_banner' => 'uploads/coupon_banner',
        'cat_logo' => 'uploads/category/logo',
        'vendor_profile_pic' => 'uploads/vendor/profile_pic',
        'vendor_signature_pic' => 'uploads/vendor/signature_pic',
        'company_logo' => 'uploads/vendor/company_logo',
        'cat_banner' => 'uploads/category/banner',
        'size_chart' => 'uploads/category/size_chart',
        'home_slider' => 'uploads/slider',
        'advertise' => 'uploads/advertise',
        'color' => 'uploads/color',
        'notification' => 'uploads/notification',
        'pages' => 'uploads/pages',
        'csv' => 'uploads/csv',
        'gst_file' => 'uploads/docs/gst',
        'pan_file' => 'uploads/docs/pan',
        'cheque_file' => 'uploads/docs/cheque',
        'signature_file' => 'uploads/docs/signature',
        'review_file' => 'uploads/review',
        'customer_profile_pic' => 'uploads/customers/profile_pic'
        
        
    ],
    'email'=>[
            'admin_from_name'=>'kefih.com',
              // 'admin_to'=>'yash@b2cinfosolutions.com',
              'admin_to'=>'sudhir@b2cmarketing.in',
            // 'admin_to'=>'yogendra@b2cmarketing.in',
            'host'=>'mail.b2cdomain.in',
            'password'=>';a(rHEUd0[_{',
            'port'=>'465',
            'username'=>'mailto@b2cdomain.com',
            'reply_to'=>'mailto@b2cdomain.com',
            'admin_cc'=>['mailto@b2cdomain.com'],
            'admin_bcc'=>['mailto@b2cdomain.com']
            ],
    'category'=>[
      'mancategoryid'=>'549',
        'womencategoryid'=>'548',     
  ]
];