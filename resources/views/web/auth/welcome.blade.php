<!doctype html>
<html dir="ltr" lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="shortcut icon" href="{{url('')}}/public/assets/images/favicon.ico" type="image/x-icon"/>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap"/>
    <link rel="stylesheet" href="{{url('')}}/public/assets/css/line-awesome/css/line-awesome.min.css"/>
    <meta name="description" content="Bankhub - Html Tailwindcss Banking and Fintech Dashboard Template"/>
    <title>EMAAR MICROFINANCE BANK</title>
    <link href="{{url('')}}/public/assets/css/index.css" rel="stylesheet">
</head>

<body class="vertical hidden bg-secondary/5 dark:bg-bg3">
<!-- Loader -->
<div class="loader min-w-screen fixed inset-0 !z-50 flex min-h-screen items-center justify-center bg-n0 dark:bg-bg4">
    <svg viewBox="25 25 50 50">
        <circle r="20" cy="50" cx="50"></circle>
    </svg>
</div>
<div class="relative min-h-screen bg-secondary/5 dark:bg-bg3">
    <img src="{{url('')}}/public/assets/images/ellipse1.webp" class="absolute top-16 md:top-5 ltr:right-10 rtl:left-10"
         alt="ellipse"/>
    <img src="{{url('')}}/public/assets/images/ellipse2.webp"
         class="absolute bottom-6 ltr:left-0 ltr:sm:left-32 rtl:right-0 rtl:sm:right-32" alt="ellipse"/>
    <a href="index.html">
        <img src="{{url('')}}/public/assets/images/mainlogo.png" alt="logo"
             class="logo-full2 relative z-[2] p-6 lg:block lg:p-8"/>
    </a>
    <div class="mt-7 flex items-center justify-center">
        <div class="relative z-[2] mx-auto max-w-[1416px] px-3 pb-10">


            <div class="box grid grid-cols-12 items-center gap-6 p-3 md:p-4 xl:p-6">


                <form id="loginForm" action="login-now" method="post" class="col-span-12 lg:col-span-7">

                    @if ($errors->any())
                        <div class="flex gap-5 items-center">
                            <i class="las la-exclamation-triangle text-3xl text-error"></i>
                            @foreach ($errors->all() as $error)
                                <span class="l-text font-medium text-error">{{ $error }}</span>
                            @endforeach
                        </div>

                    @endif


                    @if (session()->has('message'))

                            <div class="py-3 px-4 md:px-6 lg:px-8 rounded-xl bg-primary/20 flex justify-between items-center">
                                <div class="flex gap-5 items-center">
                                    <i class="las la-info-circle text-3xl text-primary"></i>
                                    <span class="l-text font-medium text-primary">{{ session()->get('message') }}</span>
                                </div>
                            </div>
                    @endif
                    @if (session()->has('error'))

                        <div class="py-3 px-4 md:px-6 mb-2 lg:px-8 rounded-xl bg-error/20 flex justify-between items-center">
                            <div class="flex gap-5 items-center">
                                <i class="las la-exclamation-triangle text-3xl text-error"></i>
                                <span class="l-text font-medium text-error">{{ session()->get('error') }}</span>
                            </div>
                        </div>

                    @endif

                    @csrf
                    <div class="box border border-n30 bg-primary/5 dark:border-n500 dark:bg-bg3 lg:p-6 xl:p-8">
                        <h3 class="h3 mb-4">Welcome Back!</h3>
                        <p class="bb-dashed mb-4 pb-4 text-sm md:mb-6 md:pb-6 md:text-base">Sign in to your account and
                            join us</p>
                        <label for="email" class="mb-4 block font-medium md:text-lg"> Enter Your Email ID </label>
                        <div class="mb-4">
                            <input type="text" name="email"
                                   class="w-full rounded-3xl border border-n30 bg-n0 px-3 py-2 text-sm dark:border-n500 dark:bg-bg4 md:px-6 md:py-3"
                                   placeholder="Enter Your Email" id="email"/>
                        </div>
                        <label for="password" class="mb-4 block font-medium md:text-lg"> Enter Your Password </label>
                        <div class="col-span-2 md:col-span-1">
                            <div id="passwordfield"
                                 class="relative rounded-3xl border border-n30 bg-n0 px-3 py-2 dark:border-n500 dark:bg-bg4 md:px-6 md:py-2.5">
                                <input type="password" name="password"
                                       class="w-11/12 border-none bg-transparent p-0 text-sm"
                                       placeholder="Enter Password" id="password2"/>
                                <span
                                    class="eye-icon absolute top-1/2 -translate-y-1/2 cursor-pointer ltr:right-5 rtl:left-5"
                                    onclick="togglePassword('password2',this)">
                      <i class="las la-eye" style="display: none"></i>
                      <i class="las la-eye-slash"></i>
                    </span>
                            </div>
                        </div>

                        <a href="#" class="mt-4 flex justify-end text-primary"> Forgot Password? </a>
                        <p class="mt-3">
                            Don&apos;t have an account?
                            <a class="text-primary" href="/auth/sign-up.html"> Signup </a>
                        </p>
                        <div class="mt-8 flex gap-6">
                            <button type="submit" class="btn-primary px-5">Login</button>
                        </div>
                    </div>
                </form>
                <div class="col-span-12 lg:col-span-5">
                    <img src="{{url('')}}/public/assets/images/auth.webp" alt="img" width="533" height="560"/>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{url('')}}/public/assets/js/main.js"></script>
<script defer src="{{url('')}}/public/assets/js/app.js"></script>
</body>
</html>
