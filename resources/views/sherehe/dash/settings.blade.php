@extends('layouts.dash')

@section('template_title')
    Settings
@endsection

@section('content')
    <div class="dashboard__content bg-light-4">
        <div class="row pb-50 mb-10">
            <div class="col-auto">

                <h1 class="text-30 lh-12 fw-700">Settings {{$active}}</h1>
                <div class="mt-10">Your Personal Details and Settings.</div>

            </div>
        </div>


        <div class="row y-gap-30">
            <div class="col-12">
                <div class="rounded-16 bg-white -dark-bg-dark-1 shadow-4 h-100">
                    <div class="tabs -active-purple-2 js-tabs pt-0">
                        <div class="tabs__controls d-flex x-gap-30 items-center pt-20 px-30 border-bottom-light js-tabs-controls">
                            <button class="tabs__button text-light-1 js-tabs-button @if($active == null || $errors->has('name')) is-active @endif" data-tab-target=".-tab-item-1" type="button">
                                Edit Profile
                            </button>
                            <button class="tabs__button text-light-1 js-tabs-button" data-tab-target=".-tab-item-2" type="button">
                                Password
                            </button>
                            <button class="tabs__button text-light-1 js-tabs-button @if($active == 'social' || $errors->has('twitter')) is-active @endif" data-tab-target=".-tab-item-3" type="button">
                                Social Profiles
                            </button>
                            <button class="tabs__button text-light-1 js-tabs-button @if($active == 'business' || $errors->has('business_name')) is-active @endif" data-tab-target=".-tab-item-6" type="button">
                                Business Settings
                            </button>
                            <button class="tabs__button text-light-1 js-tabs-button" data-tab-target=".-tab-item-4" type="button">
                                Notifications
                            </button>
                            <button class="tabs__button text-light-1 js-tabs-button" data-tab-target=".-tab-item-5" type="button">
                                Close Account
                            </button>
                        </div>

                        <div class="tabs__content py-30 px-30 js-tabs-content">
                            <div class="tabs__pane -tab-item-1 @if($active == null || $errors->has('name')) is-active @endif">
                                <div class="row y-gap-20 x-gap-20 items-center">
                                    <div class="col-auto">
                                        @if($user->profile->avatar_status == 1)
                                            <img class="size-100 rounded-16" src="@if ($user->profile->avatar != NULL) {{ $user->profile->avatar }} @endif" alt="{{ $user->name }}">
                                        @else
                                            <img class="size-100 rounded-16" src="/img/misc/user-profile.png" alt="{{ $user->name }}">
                                        @endif
                                    </div>

                                    <div class="col-auto">
                                        <div class="text-16 fw-500 text-dark-1">Profile Image</div>
                                        <form id="profile_image" action="{{route('upload_profile')}}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <input type="file" name="file" accept="image/*" required>
                                            <input hidden="" name="settings" value="true">
                                        </form>
                                        <div class="text-14 lh-1 mt-10">Only Images accepted.</div>

                                        <div class="d-flex x-gap-10 y-gap-10 flex-wrap pt-15">
                                            <div>
                                                <div class="d-flex justify-center items-center size-40 rounded-8 bg-light-3">
                                                     <button form="profile_image" type="submit" class="fa fa-save text-16"></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="border-top-light pt-30 mt-30">
                                    <form method="post" id="profile" action="{{route('update_my_profile')}}" class="contact-form row y-gap-30">
                                        @csrf
                                        <div class="col-md-6">
                                            <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">User Name @if ($errors->has('name'))<span style="color: red">{{ $errors->first('name') }}</span>@endif</label>
                                            <input type="text" required @isset($user->id) value="{{$user->name}}" @else value="{{old('name')}}" @endisset name="name" placeholder="User name">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Email Address @if ($errors->has('email'))<span style="color: red">{{ $errors->first('email') }}</span>@endif</label>
                                            <input type="text" disabled @isset($user->id) value="{{$user->email}}" @else value="{{old('email')}}" @endisset name="email" placeholder="Your Email address">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">First Name @if ($errors->has('first_name'))<span style="color: red">{{ $errors->first('first_name') }}</span>@endif</label>
                                            <input type="text" required @isset($user->id) value="{{$user->first_name}}" @else value="{{old('first_name')}}" @endisset
                                            name="first_name" placeholder="First name">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Last Name @if ($errors->has('last_name'))<span style="color: red">{{ $errors->first('last_name') }}</span>@endif</label>
                                            <input type="text" required @isset($user->id) value="{{$user->last_name}}" @else value="{{old('last_name')}}" @endisset
                                            name="last_name" placeholder="Last name">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Gender @if ($errors->has('sex'))<span style="color: red">{{ $errors->first('sex') }}</span>@endif</label>
                                            <input type="text" required @isset($user->profile->sex) value="{{$user->profile->sex}}" @else value="{{old('gender')}}" @endisset
                                            name="sex" placeholder="Gender">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Phone @if ($errors->has('phone'))<span style="color: red">{{ $errors->first('phone') }}</span>@endif</label>
                                            <input type="text" required @isset($user->profile->phone) value="{{$user->profile->phone}}" @else value="{{old('phone')}}" @endisset
                                            name="phone" placeholder="Phone number">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Date of Birth @if ($errors->has('dob'))<span style="color: red">{{ $errors->first('dob') }}</span>@endif</label>
                                            <input type="text" @isset($user->profile->dob) value="{{$user->profile->dob}}" @else value="{{old('dob')}}" @endisset
                                            name="dob" placeholder="15-2-2000">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Country @if ($errors->has('country'))<span style="color: red">{{ $errors->first('country') }}</span>@endif</label>
                                            <input type="text"  @isset($user->profile->country) value="{{$user->profile->country}}" @else value="{{old('country')}}" @endisset
                                            name="country" placeholder="Country">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Region/City @if ($errors->has('city'))<span style="color: red">{{ $errors->first('city') }}</span>@endif</label>
                                            <input type="text"  @isset($user->profile->city) value="{{$user->profile->city}}" @else value="{{old('city')}}" @endisset
                                            name="city" placeholder="Your region or city">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Address @if ($errors->has('address'))<span style="color: red">{{ $errors->first('address') }}</span>@endif</label>
                                            <input type="text"  @isset($user->profile->address) value="{{$user->profile->address}}" @else value="{{old('address')}}" @endisset
                                            name="address" placeholder="Address">
                                        </div>

                                        <div class="col-12">
                                            <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Bio @if ($errors->has('bio'))<span style="color: red">{{ $errors->first('bio') }}</span>@endif</label>
                                            <textarea name="bio" placeholder="Tell us about yourself..." rows="7">@isset($user->profile->address){{$user->profile->address}}@else{{old('address')}}@endisset</textarea>
                                        </div>


                                        <div class="col-12">
                                            <button class="button -md -purple-1 text-white">Update Profile</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="tabs__pane -tab-item-2">
                                <form action="#" class="contact-form row y-gap-30">

                                    <div class="col-md-7">

                                        <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Current password</label>
                                        <input type="text" placeholder="Current password">
                                    </div>


                                    <div class="col-md-7">

                                        <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">New password</label>

                                        <input type="text" placeholder="New password">
                                    </div>


                                    <div class="col-md-7">

                                        <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Confirm New Password</label>

                                        <input type="text" placeholder="Confirm New Password">
                                    </div>

                                    <div class="col-12">
                                        <button class="button -md -purple-1 text-white">Save Password</button>
                                    </div>
                                </form>
                            </div>

                            <div class="tabs__pane -tab-item-3 @if($active == 'social' || $errors->has('twitter')) is-active @endif">
                                <form id="social" action="{{route('update_social')}}" method="post" class="contact-form row y-gap-30">
                                    @csrf
                                    <div class="col-md-6">

                                        <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Twitter profile url @if ($errors->has('twitter'))<span style="color: red">{{ $errors->first('twitter') }}</span>@endif</label>
                                        <input type="text" @isset($user->profile->twitter) value="{{$user->profile->twitter}}" @else value="{{old('twitter')}}" @endisset name="twitter" placeholder="Twitter Profile">
                                    </div>


                                    <div class="col-md-6">

                                        <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Facebook profile url @if ($errors->has('facebook'))<span style="color: red">{{ $errors->first('facebook') }}</span>@endif</label>

                                        <input type="text" @isset($user->profile->facebook) value="{{$user->profile->facebook}}" @else value="{{old('facebook')}}" @endisset name="facebook" placeholder="Facebook Profile">
                                    </div>


                                    <div class="col-md-6">

                                        <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Instagram profile url @if ($errors->has('instagram'))<span style="color: red">{{ $errors->first('instagram') }}</span>@endif</label>

                                        <input type="text" @isset($user->profile->instagram) value="{{$user->profile->instagram}}" @else value="{{old('instagram')}}" @endisset name="instagram" placeholder="Instagram Profile">
                                    </div>


                                    <div class="col-md-6">

                                        <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">LinkedIn profile url @if ($errors->has('linkedin'))<span style="color: red">{{ $errors->first('linkedin') }}</span>@endif</label>

                                        <input type="text" @isset($user->profile->linkedin) value="{{$user->profile->linkedin}}" @else value="{{old('linkedin')}}" @endisset name="linkedin" placeholder="LinkedIn Profile">
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" form="social" class="button -md -purple-1 text-white">Save Social Profile</button>
                                    </div>
                                </form>
                            </div>

                            <div class="tabs__pane -tab-item-4">
                                <form action="#" class="contact-form">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="text-16 fw-500 text-dark-1">Notifications - Choose when and how to be notified</div>
                                            <p class="text-14 lh-13 mt-5">Select push and email notifications you'd like to receive</p>
                                        </div>
                                    </div>

                                    <div class="pt-60">
                                        <div class="row y-gap-20 justify-between">
                                            <div class="col-auto">
                                                <div class="text-16 fw-500 text-dark-1">Choose when and how to be notified</div>
                                            </div>
                                        </div>


                                        <div class="pt-30">

                                            <div class="row y-gap-20 justify-between">
                                                <div class="col-auto">
                                                    <div class="text-16 fw-500 text-dark-1">Subscriptions</div>
                                                    <p class="text-14 lh-13 mt-5">Notify me about activity from the profiles I'm subscribed to</p>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="form-switch">
                                                        <div class="switch" data-switch=".js-switch-content">
                                                            <input type="checkbox">
                                                            <span class="switch__slider"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="border-top-light pt-20 mt-20">

                                            <div class="row y-gap-20 justify-between">
                                                <div class="col-auto">
                                                    <div class="text-16 fw-500 text-dark-1">Recommended Courses</div>
                                                    <p class="text-14 lh-13 mt-5">Notify me about activity from the profiles I'm subscribed to</p>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="form-switch">
                                                        <div class="switch" data-switch=".js-switch-content">
                                                            <input type="checkbox">
                                                            <span class="switch__slider"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="border-top-light pt-20 mt-20">

                                            <div class="row y-gap-20 justify-between">
                                                <div class="col-auto">
                                                    <div class="text-16 fw-500 text-dark-1">Replies to my comments</div>
                                                    <p class="text-14 lh-13 mt-5">Notify me about activity from the profiles I'm subscribed to</p>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="form-switch">
                                                        <div class="switch" data-switch=".js-switch-content">
                                                            <input type="checkbox">
                                                            <span class="switch__slider"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="border-top-light pt-20 mt-20">

                                            <div class="row y-gap-20 justify-between">
                                                <div class="col-auto">
                                                    <div class="text-16 fw-500 text-dark-1">Activity on my comments</div>
                                                    <p class="text-14 lh-13 mt-5">Notify me about activity from the profiles I'm subscribed to</p>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="form-switch">
                                                        <div class="switch" data-switch=".js-switch-content">
                                                            <input type="checkbox">
                                                            <span class="switch__slider"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="pt-60">
                                        <div class="row y-gap-20 justify-between">
                                            <div class="col-auto">
                                                <div class="text-16 fw-500 text-dark-1">Email notifications</div>
                                            </div>
                                        </div>


                                        <div class="pt-30">

                                            <div class="row y-gap-20 justify-between">
                                                <div class="col-auto">
                                                    <div class="text-16 fw-500 text-dark-1">Send me emails about my Cursus activity and updates I requested</div>
                                                    <p class="text-14 lh-13 mt-5">Notify me about activity from the profiles I'm subscribed to</p>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="form-switch">
                                                        <div class="switch" data-switch=".js-switch-content">
                                                            <input type="checkbox">
                                                            <span class="switch__slider"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="border-top-light pt-20 mt-20">

                                            <div class="row y-gap-20 justify-between">
                                                <div class="col-auto">
                                                    <div class="text-16 fw-500 text-dark-1">Promotions, course recommendations, and helpful resources from Cursus.</div>
                                                    <p class="text-14 lh-13 mt-5">Notify me about activity from the profiles I'm subscribed to</p>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="form-switch">
                                                        <div class="switch" data-switch=".js-switch-content">
                                                            <input type="checkbox">
                                                            <span class="switch__slider"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="border-top-light pt-20 mt-20">

                                            <div class="row y-gap-20 justify-between">
                                                <div class="col-auto">
                                                    <div class="text-16 fw-500 text-dark-1">Announcements from instructors whose course(s) I’m enrolled in.</div>
                                                    <p class="text-14 lh-13 mt-5">Notify me about activity from the profiles I'm subscribed to</p>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="form-switch">
                                                        <div class="switch" data-switch=".js-switch-content">
                                                            <input type="checkbox">
                                                            <span class="switch__slider"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row pt-30">
                                        <div class="col-12">
                                            <button class="button -md -purple-1 text-white">Save Changes</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="tabs__pane -tab-item-5">
                                <form action="#" class="contact-form row y-gap-30">
                                    <div class="col-12">
                                        <div class="text-16 fw-500 text-dark-1">Close account</div>
                                        <p class="mt-10">Warning: If you close your account, you will be unsubscribed from all your 5 courses, and will lose access forever.</p>
                                    </div>


                                    <div class="col-md-7">

                                        <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Enter Password</label>

                                        <input type="text" placeholder="Enter Password">
                                    </div>


                                    <div class="col-12">
                                        <button class="button -md -purple-1 text-white">Close Account</button>
                                    </div>
                                </form>
                            </div>

                            <div class="tabs__pane -tab-item-6 @if($active == 'business' || $errors->has('business_name')) is-active @endif">
                                @isset($user->business->id)
                                    <form action="{{route('update_my_business')}}" class="contact-form row y-gap-30" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <input hidden="hidden" name="business_id" value="{{$user->business->id}}">
                                        <div class="col-md-6">
                                            <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Business Name* @if ($errors->has('business_name'))<span style="color: red">{{ $errors->first('business_name') }}</span>@endif</label>
                                            <input type="text" required @isset($user->business->business_name) value="{{$user->business->business_name}}" @else value="{{old('business_name')}}" @endisset
                                            name="business_name" placeholder="Kelvin Kabenje Store">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Business Phone Number* @if ($errors->has('tel'))<span style="color: red">{{ $errors->first('tel') }}</span>@endif</label>
                                            <input type="text" required @isset($user->business->tel) value="{{$user->business->tel}}" @else value="{{old('tel')}}" @endisset
                                            name="tel" placeholder="+255765204506">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Address @if ($errors->has('business_address'))<span style="color: red">{{ $errors->first('business_address') }}</span>@endif</label>
                                            <input type="text" @isset($user->business->business_address) value="{{$user->business->business_address}}" @else value="{{old('business_address')}}" @endisset
                                            name="business_address" placeholder="Business Address">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Business Email*@if ($errors->has('business_email'))<span style="color: red">
                                                    {{ $errors->first('business_email') }}</span>@endif</label>
                                            <input type="email" @isset($user->business->business_email) value="{{$user->business->business_email}}" @else value="{{old('business_email')}}" @endisset
                                            name="business_email" placeholder="email@business.com">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Website @if ($errors->has('website'))<span style="color: red">
                                                    {{ $errors->first('website') }}</span>@endif</label>
                                            <input type="text" @isset($user->business->website) value="{{$user->business->website}}" @else value="{{old('website')}}" @endisset
                                            name="website" placeholder="www.yourbusiness.com">
                                        </div>

                                        <div class="col-md-6 row">`
                                            <div class="col-md-6">
                                                <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Logo @if ($errors->has('logo'))<span style="color: red">
                                                    {{ $errors->first('logo') }}</span>@endif</label>
                                                <br>
                                                @isset($user->business->logo)
                                                    <img class="size-100" src="{{url(Storage::url($user->business->logo))}}" alt="image">
                                                @else
                                                    <img class="size-100" src="/img/misc/user-profile.png" alt="image">
                                                @endisset
                                            </div>

                                            <div class="col-md-6">
                                                <input type="file" name="file" accept="image/*" >
                                            </div>
                                        </div>
                                        <div class="col-12 row">
                                            <div class="col-md-10">
                                                <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Banner @if ($errors->has('banner'))<span style="color: red">
                                                    {{ $errors->first('banner') }}</span>@endif</label>
                                                <br>
                                                @isset($user->business->banner)
                                                    <img  src="{{url(Storage::url($user->business->banner))}}" alt="image">
                                                @else
                                                    <img class="size-100" src="/img/misc/user-profile.png" alt="image">
                                                @endisset
                                            </div>

                                            <div class="col-md-2">
                                                <label>Choose Banner Image</label>
                                                <input type="file" name="banner_image" accept="image/*" >
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Business Description @if ($errors->has('description'))<span style="color: red">{{ $errors->first('description') }}</span>@endif</label>
                                            <textarea name="description" placeholder="Tell us about business..." rows="7">@isset($user->business->description){{$user->business->description}}@else{{old('description')}}@endisset</textarea>
                                        </div>

                                        <div class="col-12">
                                            <button class="button -md -purple-1 text-white">Save Social Profile</button>
                                        </div>
                                    </form>
                                @else
                                    <form action="{{route('create_my_business')}}" method="post" class="contact-form row y-gap-30">
                                        @csrf
                                        <div class="col-12">
                                            <div class="text-16 fw-500 text-dark-1">Create Business</div>
                                            <p class="mt-10">This action will change your account to a business account.</p>
                                            <p class="mt-10">You will get access to create your classes and sell products on this platform.</p>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Business Name* @if ($errors->has('business_name'))<span style="color: red">{{ $errors->first('business_name') }}</span>@endif</label>
                                            <input type="text" required @isset($user->business->business_name) value="{{$user->business->business_name}}" @else value="{{old('business_name')}}" @endisset
                                            name="business_name" placeholder="Kelvin Kabenje Store">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="text-16 lh-1 fw-500 text-dark-1 mb-10">Business Phone Number* @if ($errors->has('tel'))<span style="color: red">{{ $errors->first('tel') }}</span>@endif</label>
                                            <input type="text" required @isset($user->business->tel) value="{{$user->business->tel}}" @else value="{{old('tel')}}" @endisset
                                            name="tel" placeholder="+255765204506">
                                        </div>

                                         <div class="col-md-6">
                                            <input required type="checkbox">
                                            <label class="text-16 lh-1 fw-500  mb-10">Agree to Our <a class="text-blue-1" href="/">Business Terms</a> </label>
                                        </div>

                                        <div class="col-12">
                                            <button type="submit" class="button -md -purple-1 text-white">Create Business Account</button>
                                        </div>
                                    </form>
                                @endisset
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
