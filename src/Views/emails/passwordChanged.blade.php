@component('mail::message')
# You have recently changed your password

Hey, Your password has recently changed <br>
Your new password is : <b>{{$password}}</b>

@component('mail::button', ['url' => 'https://bitfumes.com/profile/@'.$user->fumesid])
Login Now
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
