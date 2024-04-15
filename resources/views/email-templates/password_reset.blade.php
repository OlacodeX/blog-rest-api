<div style='background-color: #white!important; border-color: #4b545c; height:100%; wdith:100%;padding-top:10px;padding-right:auto;padding-left:auto;padding-bottom:20px;'>
    
    <center><img src='/logos/logo.png' alt='{{ ucfirst(config('custom.app_name')) }}'><h4 style='font-size:25px; font-weight:400;color:black;'>{{ ucfirst(config('custom.app_name')) }}</h4><center>

    <div style='background-color: #ffffff; height:100%; wdith:100%;padding:10px;color:black;text-align:left;'>
        <h4 style='color:black;'>Hello !</h4>
        <p style='color:black;'>Please click the button below to reset your password. .</p>

        <p style='color:black;'><a href='{{ $url }}' style='background-color: #263544;padding:10px;text-decoration:none;color:white;border:1 px solid  #263544;border-radius:10px;justifly-content:center; '>Reset Password</a></p>

        <p style='color:black;'>If you did not make this request, no further action is required.</p>
        <br>
        Regards,
        <p style='color:black;'>{{ ucfirst(config('custom.app_name')) }}</p>

        <p style='color:black;'>
        If you're having trouble clicking the 'Reset Password' button, copy and paste the URL below into your web browser: </p> <a href="{{ $url }}">{{ $url }}</a>

    </div>
    <p style='text-align: center;color:black'>
        Copyright &copy; {{ date("Y") }} {{ ucfirst(config('custom.app_name')) }} <a href='{{ config('custom.app_url') }}' target='_blank' rel='noopener'></a>
    </p>
</div>