<?php
return [
    'langs' => [
        'es' => 'www.domain.es',
        'en' => 'www.domain.us'
        // etc
    ],
	'Url' => [
      	  'public_url' =>  'https://b2cdomain.in/kefih/',
        'public_url2' => "https://b2cdomain.in/kefih/seller/"

      	 
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

        'vendor_profile_image_min'=>'1',
        'vendor_profile_image_max'=>'500',
        'vendor_company_logo_min' =>'1',            
        'vendor_company_logo_max' =>'200',
        'vendor_cancel_cheque_image_min' =>'1',
        'vendor_cancel_cheque_image_max' =>'200',
        'vendor_pan_image_min' =>'1',
        'vendor_pan_image_max' =>'200',
        'vendor_gst_image_min' =>'1',
        'vendor_gst_image_max' =>'200',
        'vendor_sign_image_min' =>'1',
        'vendor_sign_image_max' =>'200',
        'vendor_profile_dimensions' =>'Dimensions : 100px * 100px',
        

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
        'brand_logo' => '/home/b2cdomain/public_html/kefih/uploads/brand/logo',
        'brand_banner' => '/home/b2cdomain/public_html/kefih/uploads/brand/banner',
        'product_images' => '/home/b2cdomain/public_html/kefih/uploads/products',
        'product_images1' => '/home/b2cdomain/public_html/kefih/uploads/product_images1',
        'customerCustomizedimage' => '/home/b2cdomain/public_html/kefih/uploads/customerCustomizedimage',
        'product_size_chart' => '/home/b2cdomain/public_html/kefih/uploads/sizechart',
        //'product_thumb_image' => 'uploads/products/480-360',
        'product_thumb_image' => '/home/b2cdomain/public_html/kefih/uploads/products',
        'blog_banner' => '/home/b2cdomain/public_html/kefih/uploads/blog/banner',
        'testimonial_banner' => '/home/b2cdomain/public_html/kefih/uploads/testimonial/banner',
        'coupon_banner' => '/home/b2cdomain/public_html/kefih/uploads/coupon_banner',
        'cat_logo' => '/home/b2cdomain/public_html/kefih/uploads/category/logo',
        'vendor_profile_pic' => '/home/b2cdomain/public_html/kefih/uploads/vendor/profile_pic',
        'vendor_signature_pic' => '/home/b2cdomain/public_html/kefih/uploads/vendor/signature_pic',
        'company_logo' => '/home/b2cdomain/public_html/kefih/uploads/vendor/company_logo',
        'cat_banner' => '/home/b2cdomain/public_html/kefih/uploads/category/banner',
        'size_chart' => '/home/b2cdomain/public_html/kefih/uploads/category/size_chart',
        'home_slider' => '/home/b2cdomain/public_html/kefih/uploads/slider',
        'advertise' => '/home/b2cdomain/public_html/kefih/uploads/advertise',
        'color' => '/home/b2cdomain/public_html/kefih/uploads/color',
        'notification' => '/home/b2cdomain/public_html/kefih/b2cdomain/uploads/notification',
        'pages' => '/home/b2cdomain/public_html/kefih/uploads/pages',
          'csv' => 'uploads/csv',
        'gst_file' => '/home/b2cdomain/public_html/kefih/uploads/docs/gst',
        'pan_file' => '/home/b2cdomain/public_html/kefih/uploads/docs/pan',
        'cheque_file' => '/home/b2cdomain/public_html/kefih/uploads/docs/cheque',
        'signature_file' => '/home/b2cdomain/public_html/kefih/uploads/docs/signature',
        'review_file' => '/home/b2cdomain/public_html/kefih/uploads/review',
        'customer_profile_pic' => '/home/b2cdomain/public_html/kefih/uploads/customers/profile_pic'
        
    ],
    'email'=>[
            'admin_from_name'=>'b2cdomain.in',
            'admin_to'=>'manish@b2cmarketing.in',
            'host'=>'mail.b2cdomain.in',
            'password'=>';a(rHEUd0[_{',
            'port'=>'465',
            'username'=>'mailto@b2cdomain.in',
            'reply_to'=>'mailto@b2cdomain.in',
            'admin_cc'=>['mailto@b2cdomain.in'],
            'admin_bcc'=>['mailto@b2cdomain.in']
        ]
];