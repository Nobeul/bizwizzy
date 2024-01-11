@component('mail::message')
<h3>Dear valued customer,</h3>
<p>
    We are happy to inform you that Mpesa gateway has been enabled for you. Now you can get payments using your mpesa account. You have to follow the below steps to confirm the settings.
    
    <ul>
        <li>Please go to <b>Settings > Business Locations</b>. Then click on the edit button for which location you want to enable Mpesa. Then click on the checkbox and click on 'Save' button.</li>
        <li>Please go to <b>Settings > Mpesa Settings</b>. Then click on the add button to provide your Mpesa credentials.</li>
    </ul>
    
    <b>N.B:</b> To enable confirmation url and validation url please contact with Mpesa.
</p>
<br>

{{-- @component('mail::button', ['url' => ''])
Button Text
@endcomponent --}}

Best Regards,<br>
<b>{{ config('app.name') }}</b>
@endcomponent