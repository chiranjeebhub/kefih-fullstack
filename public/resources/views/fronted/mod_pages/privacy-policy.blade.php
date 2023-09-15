@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">Privacy Policy</a>
@endsection
<section class="about-section">
<div class="container">
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="about-banner">
     @if($page_data->banner!='')
    <img class="img-responsive" src="{{URL::to('/uploads/pages')}}/{{$page_data->banner}}" alt="{{$page_data->title}}">    
    @endif
</div>       
<div class="about-txt">
<h6 class="mb20 fs35 fw700">Privacy Policy</h6>
 
    <p>This website www.18up.in ("Website") is is an internet-based e-commerce platform, owned by Sri Mrigasya Marketing Pvt Ltd   a company incorporated under the Companies Act, 1956, having its Registered Office at No:3/23, 2nd Floor, Anand Complex, Service Road, Outer Ring Road, Malagala, Nagarbhavi 2nd Stage Bangalore, Karnataka 560072.<br>
The objective of this privacy policy (policy) is to educate you about the information we collect when you use our website, its use thereafter and the extent of your rights. This Policy shall be read in consonance with the Terms & Conditions for use of this Website. 
Our aim is to protect you and your personal privacy and provide this Privacy Statement in order to help you understand what we may do with any personal information you provide. Provision of personal information will be acknowledged as your acceptance of this Privacy Statement and agreement to our collecting and use/disclose of your personal information only as described in this Privacy Statement. <br>
<b>Disclaimer:</b> If you do not agree to this statement, please do not provide your personal details to us. This Privacy Statement forms part of the Terms of Use of this Website, which govern your use of the site. If at any point in time, you wish to remove or delete your information from this website, you may request the Company to do the same, by writing at the grievance e-mail address  mentioned below, upon receiving your request Company shall do the needful within a reasonable time.
</p>
    <h6 class="mt30 mb5 fs18 fw600">Consent</h6>
    <p>By using this site, you agree to the terms of this Privacy Policy. Whenever you submit information via this site, you expressly consent to the collection, use, and disclosure of the information in accordance with this Privacy Policy. Further collection and continued use of your information by the Company shall not amount to unauthorized use of your information. Further, you represent that you are above 18 years, and not below the legal age when you transact with us through this website. INFORMATION COLLECTED</p>
    
    <p>In general, you can visit this Website without telling us who you are or revealing any information about yourself. We may collect personal information from you including your first and last name, address, telephone and mobile number(s), email address, credit/debit card details (card no., name on the card, CVV no., etc), payment instrument details and any other information, when you knowingly provide us with this information, for the purpose of fulfilling your order for the products made available for sale anywhere on the Website. </p>
        
        <h6 class="mt30 mb5 fs18 fw600">This will generally occur when you either</h6>
        <ul>
        <li>Visit Online ordering site to place order;</li> 
        <li>participate in a survey;</li> 
        <li>subscribe to our mailing list;</li> 
        <li>submit website feedback</li> 
        </ul>
  
    
<p>Wherever we collect personal information about you from someone else, we will take reasonable steps to advise you.</p>
    
    <h6 class="mt30 mb5 fs18 fw600">USE and Disclosure</h6>
    <p>By using the Website and/or by providing your information, you consent to the collection and use of the information you disclose on the website in accordance with this Privacy Policy, including but not limited to your consent for sharing your information as per this privacy policy. Except as otherwise stated, we may use information collected via this site to improve the content of our site, to customize the site to your preferences, to communicate information to you (if you have requested it), and for any other purpose specified. We may use the personal information that you submit to store and process that information in order to provide the Services. Generally, we may use your personal information in the ways in which you would expect, </p>
    <h6 class="mt30 mb5 fs18 fw600">including but not limited, to any of the following purposes:</h6>
    <ul> 
  <li>   to provide you with any store or promotion information you may request;</li> 
   <li>   to process your online orders;</li> 
   <li>   to determine the number of visitors to our websites and conduct reviews of our sites;</li> 
   <li>   to keep you informed about any changes to our websites;</li> 
   <li>   to conduct marketing research;</li> 
   <li>   to now and again send you offers or information on products or services that we consider will be of interest to you;</li> 
   <li>   to comply with the order of any statutory, government, judicial, quasi-judicial authority which requires disclosure of your information;</li> 
   <li>   to determine your online shopping behavior;</li> 
   <li>   to sell your information to a company acquiring or merging with us (however the usage by that company shall also be subject <li>  to this privacy policy.)</li> 
   <li>   to protect ourselves from fraud or criminal activity and</li> 
   <li>   to enforce the terms and conditions mentioned herein.</li> 
        </ul>
        <p>
        We will take reasonable steps to ensure that the personal information collected is, complete and up-to-date. However, you understand and acknowledge that we shall not be responsible for the accuracy of your information provided by you. You can access and request correction of any personal information concerning you at any time. You may also request that your personal information is deleted at any time. Any such requests should be made directly by contacting us. We will take reasonable steps to protect personal information from misuse, loss and unauthorized access, modification or disclosure.</p>
    
        <h6 class="mt30 mb5 fs18 fw600">Sensitive Information</h6>
        <p>We will not collect, use or disclose sensitive information except with your specific consent.</p>

         <h6 class="mt30 mb5 fs18 fw600">Click-Stream Data</h6>
      <p>  Each time you visit the Website our server collects some anonymous information, known as click-stream data, including the type of browser and system you are using; the address of the site you have come from and move to after your visit; the date and time of your visit; and your server IP address. We may collect this information for statistical purposes to find out how the websites are used and navigated, including the number of hits, the frequency, and duration of visits, most popular session times. We may use this information to evaluate and improve our Website.</p>
    
        <h6 class="mt30 mb5 fs18 fw600">Cookies</h6>
       <p>We use data collection devices such as cookies on certain pages of the Website to help analyze our web page flow, measure promotional effectiveness, and promote trust and safety. cookies are small files placed on your hard drive that assist us in providing our services. We offer certain features that are only available through the use of a cookies. We also use cookies to allow you to enter your password less frequently during a session. Cookies can also help us provide information that is targeted to your interests. Most cookies are session cookies, meaning that they are automatically deleted from your hard drive at the end of a session. You are always free to decline our cookies if your browser permits, although in that case, you may not be able to use certain features on the website and you may be required to reenter your password more frequently during a session. A Cookie is a piece of information that our web server may send to your machine when you visit the Website. A Cookie helps us to recognize you when you re-visit our Website and to co-ordinate your access to different pages on the sites.</p> 
     <h6 class="mt30 mb5 fs18 fw600">Changes</h6>
    <p>If we decide to change our privacy policy, we will post those changes on this page so that you are always aware of what information we collect, how we use it, and under what circumstances we disclose it. Please check back periodically, and especially before you provide any personally identifiable information. This Privacy Policy was last updated on 8 November 2018</p>
    <h6 class="mt30 mb5 fs18 fw600">Copyrights</h6>
       <p>We grant you permission to view the contents of this Website without limitation. However, we reserve the right to restrict copying of any content whatsoever from this Website. If you need permission to copy any content on the Website for your own personal, non-commercial use, you may contact us for permission for such copying. We reserve the right to allow or deny permission for such copying and any such permission shall be subject to the notices, terms, and conditions set forth in this agreement. Subject to permission being granted to you for copying content on this Website, content which You copy may not be modified, copied (except as set forth in the preceding sentence), distributed, transmitted, displayed, performed, reproduced, published, licensed, create derivative works from, transferred, or sold in any manner whatsoever. Your use of this Website constitutes your agreement and acceptance without modification of the notices, terms and conditions set forth herein. In addition, as a condition of your use of this Website, you represent and warrant to us that you will not use this Website for any purpose that is unlawful, immoral, or prohibited by these terms, conditions, and notices. If you do not agree and accept without modification the notices, terms, and conditions set forth herein, please do not use this Website. Other than this agreement, we will not enter into any agreement with you or obligation to you through this Website, and no attempt to create such an agreement or obligation will be effective.</p> 
</div>    
</div>    
</div>
</div>     
</section>    
 @endsection   
 