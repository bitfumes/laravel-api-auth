@component('mail::message')
# Welcome to Bitfumes

Hii {{$user->name}},

We are very happy to see you here.

@component('mail::button', ['url' => 'http://twitter.com/intent/tweet?text=Just joined @bitfumes to start my learning journey. Check all courses at https://bitfumes.com/courses&hashtags=laravel,php,javascript,vuejs,reactjs,python,developer'])
Tweet About it
@endcomponent

@if($password)
Your email registered with us is  <b> {{$user->email}} </b> <br>
Your password is <b>{{$password}}</b><br>

You can change it, go to settings->password<br>
You can use these details to login directly. <br>
@endif

# Courses
You can learn, how to code with love and how to write efficient, reuseable and clean code.

# Discussion
Ask anything at this place
Also answer others to help them and enhance your knowledge.

# Blog
Read most important blogs in details.

# Setting
You can change password, go to settings->password<br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
