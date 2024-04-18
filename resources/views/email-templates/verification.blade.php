<div style='background-color: white!important; border-color: #4b545c; height:100%; width:100%;padding-top:10px;padding-right:auto;padding-left:auto;padding-bottom:20px;'>

    <center><img src='/logos/logo.png' alt='{{ ucfirst(config('custom.app_name')) }}'><h4 style='font-size:25px; font-weight:400;color:black;'>{{ ucfirst(config('custom.app_name')) }}</h4><center>

    <div style='background-color: #ffffff; height:100%; width:100%;padding:10px;color:black;text-align:left;'>
        <h4 style='color:black;'>Hello !</h4>
        <p style='color:black;'>Please use the code below to verify your email address.</p>

        <p style='background-color: #263544;padding:10px;text-decoration:none;color:white;border:1 px solid  #263544;border-radius:10px;justifly-content:center; '>{{ $code }}</p>

        <p style='color:black;'>If you did not create an account, no further action is required.</p>
        <br>
        Regards,
        <p style='color:black;'>{{ ucfirst(config('custom.app_name')) }}</p>
    </div>
    <p style='text-align: center;color:black'>
        Copyright &copy; {{ date("Y") }} {{ ucfirst(config('custom.app_name')) }} <a href='{{ config('custom.app_url') }}' target='_blank' rel='noopener'></a>
    </p>
</div>