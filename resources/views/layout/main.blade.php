<!doctype html>
<html dir="ltr" lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="{{url('')}}/public/assets/images/favicon.ico" type="image/x-icon"/>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link rel="stylesheet" href="{{url('')}}/public/assets/css/nice-select2.css"/>
    <link rel="stylesheet" href="{{url('')}}/public/js-datepicker/dist/datepicker.min.css"/>
    <link rel="stylesheet" href="{{url('')}}/public/assets/css/line-awesome/css/line-awesome.min.css"/>
    <link rel="stylesheet" href="{{url('')}}/public/ajax/libs/tippy.js/2.5.4/tippy.css" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" href="{{url('')}}/public/tippy.js@6/animations/scale.css"/>
    <link rel="stylesheet"
          href="{{url('')}}/public/fonts.googleapis.com/css2%3Ffamily=Inter:wght@100..900&amp;display=swap.css"/>
    <meta name="description" content="EMAAR MICROFINANCE BANK"/>
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

<!-- Navigation -->
<section class="topbar-container z-30">
    <nav class="navbar-top topbarfull z-20 gap-3 border-b bg-n0 dark:bg-bg4 border-n0 py-3 shadow-sm duration-300 dark:border-n700 xl:py-4 xxxl:py-6"
         id="topbar">
        <div class="topbar-inner flex items-center justify-between">
            <div class="flex grow items-center gap-4 xxl:gap-6">
                <a href="#" class="topbar-logo hidden shrink-0">
                    <img width="174" height="38" src="{{url('')}}/public/assets/images/mainlogo.png" alt="logo"
                         class="logo-full2 hidden lg:block"/>
                </a>
                <button aria-label="sidebar-toggle-btn"
                        class="flex items-center rounded-s-2xl bg-primary px-0.5 py-3 text-xl text-n0"
                        id="sidebar-toggle-btn">
                    <i class="las la-angle-left text-lg"></i>
                </button>
                <!-- Select layout -->
{{--                <div class="topnav-layout">--}}
{{--                    <div id="layout-btn"--}}
{{--                         class="flex w-full cursor-pointer items-center justify-between gap-2 rounded-[30px] border border-n30 bg-primary/5 px-4 py-1 dark:border-n500 dark:bg-bg3 lg:py-1.5 xxl:px-6 xxl:py-2">--}}
{{--                            <span class="flex select-none items-center gap-2">--}}
{{--              <i class="las la-border-all text-3xl text-primary"></i>--}}
{{--              <span id="selected-layout" class="capitalize">Vertical</span>--}}
{{--                            </span>--}}
{{--                        <i id="drop-arrow" class="las la-angle-down shrink-0 text-lg duration-300"></i>--}}
{{--                    </div>--}}
{{--                    <ul id="layout"--}}
{{--                        class="hide absolute left-0 top-full z-20 w-full origin-top rounded-lg bg-n0 p-2 shadow-[0px_6px_30px_0px_rgba(0,0,0,0.08)] duration-300 dark:bg-bg4">--}}
{{--                        <li data-layout="vertical"--}}
{{--                            class="active block cursor-pointer select-none rounded-md p-2 duration-300 hover:text-primary">--}}
{{--                            Vertical--}}
{{--                        </li>--}}
{{--                        <li data-layout="two-column"--}}
{{--                            class="block cursor-pointer select-none rounded-md p-2 duration-300 hover:text-primary">--}}
{{--                            Two-Column--}}
{{--                        </li>--}}
{{--                        <li data-layout="hovered"--}}
{{--                            class="block cursor-pointer select-none rounded-md p-2 duration-300 hover:text-primary">--}}
{{--                            Hovered--}}
{{--                        </li>--}}
{{--                        <li data-layout="horizontal"--}}
{{--                            class="block cursor-pointer select-none rounded-md p-2 duration-300 hover:text-primary">--}}
{{--                            Horizontal--}}
{{--                        </li>--}}
{{--                        <li data-layout="detached"--}}
{{--                            class="block cursor-pointer select-none rounded-md p-2 duration-300 hover:text-primary">--}}
{{--                            Detached--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </div>--}}

{{--                <!-- Search bar -->--}}
{{--                <form class="topnav-search">--}}
{{--                    <input type="text" placeholder="Search"--}}
{{--                           class="w-full border-none bg-transparent py-2 focus:border-none focus:shadow-none focus:outline-none md:py-2.5 xxl:py-3 ltr:pl-4 rtl:pr-4"/>--}}
{{--                    <button aria-label="search btn"--}}
{{--                            class="flex h-8 w-9 items-center justify-center rounded-full bg-primary text-n0">--}}
{{--                        <i class="las la-search text-lg"></i>--}}
{{--                    </button>--}}
{{--                </form>--}}
            </div>
            <div class="flex items-center gap-3 sm:gap-4 xxl:gap-6">
                <!-- mobile Search  -->
                <div class="relative lg:hidden">
                    <button id="mobile-search-btn"
                            class="flex h-10 w-10 cursor-pointer select-none items-center justify-center gap-2 rounded-full border border-n30 bg-primary/5 dark:border-n500 dark:bg-bg3 md:h-12 md:w-12">
                        <i class="las la-search"></i>
                    </button>
                    <div id="mobile-search"
                         class="hide invisible absolute -left-8 top-full z-20 flex min-w-max max-w-[250px] origin-[20%_20%] gap-3 overflow-y-auto rounded-md bg-n0 p-3 opacity-0 shadow-[0px_6px_30px_0px_rgba(0,0,0,0.08)] duration-300 dark:bg-bg4">
                        <form class="flex w-full items-center justify-between gap-3 rounded-[30px] border border-n30 bg-secondary/5 p-1 focus-within:border-primary dark:border-n500 dark:bg-bg3 xxl:p-2">
                            <input type="text" placeholder="Search"
                                   class="w-full bg-transparent py-1 ltr:pl-4 rtl:pr-4"/>
                            <button class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-primary text-n0 lg:h-8 lg:w-8">
                                <i class="las la-search text-lg"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <!-- dark mode toggle -->
                <button id="darkModeToggle" aria-label="dark mode switch"
                        class="h-10 w-10 shrink-0 rounded-full border border-n30 bg-primary/5 dark:border-n500 dark:bg-bg3 md:h-12 md:w-12">
                    <i class="las la-sun text-2xl dark:hidden"></i>
                    <span class="hidden text-n30 dark:block">
            <i class="las la-moon text-2xl"></i>
          </span>
                </button>
                <!-- Notification -->
                <div class="relative">
                    <button id="notification-btn" class="topbar-btn">
                        <i class="las la-bell text-2xl"></i>
                        <span class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-primary text-xs text-n0"> 2 </span>
                    </button>
                    <div id="notification"
                         class="hide absolute top-full z-20 origin-[60%_0] rounded-md bg-n0 shadow-[0px_6px_30px_0px_rgba(0,0,0,0.08)] duration-300 dark:bg-bg4 ltr:-right-[110px] sm:ltr:right-0 sm:ltr:origin-top-right rtl:-left-[120px] sm:rtl:left-0 sm:rtl:origin-top-left">
                        <div class="flex items-center justify-between border-b p-3 dark:border-n500 lg:px-4">
                            <h5 class="h5">Notifications</h5>
                            <a href="index.html#" class="text-sm text-primary"> View All </a>
                        </div>
                        <ul class="flex w-[300px] flex-col p-4">
                            <div class="flex cursor-pointer gap-2 rounded-md px-2 py-1.5 duration-300 hover:bg-primary/10">
                                <img src="{{url('')}}/public/assets/images/user-3.png" width="44" height="40"
                                     class="shrink-0 rounded-full" alt="img"/>
                                <div class="text-sm">
                                    <div class="flex gap-1">
                                        <span class="font-medium">Benjamin</span>
                                        <span>Sent a message</span>
                                    </div>
                                    <span class="text-xs text-n100 dark:text-n50">1 hour ago</span>
                                </div>
                            </div>
                            <div class="flex cursor-pointer gap-2 rounded-md px-2 py-1.5 duration-300 hover:bg-primary/10">
                                <img src="{{url('')}}/public/assets/images/user-4.png" width="44" height="40"
                                     class="shrink-0 rounded-full" alt="img"/>
                                <div class="text-sm">
                                    <div class="flex gap-1">
                                        <span class="font-medium">Benjamin</span>
                                        <span>Left a Comment</span>
                                    </div>
                                    <span class="text-xs text-n100 dark:text-n50">1 hour ago</span>
                                </div>
                            </div>
                            <div class="flex cursor-pointer gap-2 rounded-md px-2 py-1.5 duration-300 hover:bg-primary/10">
                                <img src="{{url('')}}/public/assets/images/user-5.png" width="44" height="40"
                                     class="shrink-0 rounded-full" alt="img"/>
                                <div class="text-sm">
                                    <div class="flex gap-1">
                                        <span class="font-medium">Benjamin</span>
                                        <span>Sent a message</span>
                                    </div>
                                    <span class="text-xs text-n100 dark:text-n50">2 hour ago</span>
                                </div>
                            </div>
                            <div class="flex cursor-pointer gap-2 rounded-md px-2 py-1.5 duration-300 hover:bg-primary/10">
                                <img src="{{url('')}}/public/assets/images/user-7.png" width="44" height="40"
                                     class="shrink-0 rounded-full" alt="img"/>
                                <div class="text-sm">
                                    <div class="flex gap-1">
                                        <span class="font-medium">Samuel</span>
                                        <span>Uploaded a file</span>
                                    </div>
                                    <span class="text-xs text-n100 dark:text-n50">Yesterday</span>
                                </div>
                            </div>
                            <div class="flex cursor-pointer gap-2 rounded-md px-2 py-1.5 duration-300 hover:bg-primary/10">
                                <img src="{{url('')}}/public/assets/images/user-7.png" width="44" height="40"
                                     class="shrink-0 rounded-full" alt="img"/>
                                <div class="text-sm">
                                    <div class="flex gap-1">
                                        <span class="font-medium">David</span>
                                        <span>Left a Comment</span>
                                    </div>
                                    <span class="text-xs text-n100 dark:text-n50">Yesterday</span>
                                </div>
                            </div>
                        </ul>
                    </div>
                </div>
                <!-- Chat Link -->
                <a href="chat.html" class="topbar-btn max-[620px]:hidden">
                    <i class="lab la-facebook-messenger"></i>
                    <span class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-primary text-xs text-n0"> 3 </span>
                </a>
                <!-- language dropdown -->
                <div class="relative">
                    <button id="language-btn" class="topbar-btn">
                        <i class="las la-language"></i>
                    </button>
                    <div id="language"
                         class="hide absolute top-full z-20 rounded-md bg-n0 shadow-[0px_6px_30px_0px_rgba(0,0,0,0.08)] duration-300 dark:bg-bg4 ltr:right-0 ltr:origin-top-right rtl:left-0 rtl:origin-top-left">
                        <ul class="flex w-32 flex-col rounded-md bg-n0 p-1 dark:bg-bg4">
                            <li class="active language-btn">English</li>
                            <li class="language-btn">Arabic</li>
                            <li class="language-btn">Hindi</li>
                            <li class="language-btn">Spanish</li>
                        </ul>
                    </div>
                </div>
                <!-- Profile dropdown -->
                <div class="relative shrink-0">
                    <div id="profile-btn" class="size-10 cursor-pointer md:size-12">
                        <img src="{{url('')}}/public/assets/images/user-big-4.png" class="rounded-full" width="48"
                             height="48" alt="profile img"/>
                    </div>
                    <div id="profile"
                         class="hide absolute top-full z-20 rounded-md bg-n0 shadow-[0px_6px_30px_0px_rgba(0,0,0,0.08)] duration-300 dark:bg-bg4 ltr:right-0 ltr:origin-top-right rtl:left-0 rtl:origin-top-left">
                        <div class="flex flex-col items-center border-b p-3 text-center dark:border-n500 lg:p-4">
                            <img src="{{url('')}}/public/assets/images/user-big-4.png" width="60" height="60"
                                 class="rounded-full" alt="profile img"/>
                            <h6 class="h6 mt-2">William James</h6>
                            <span class="text-sm">james@mail.com</span>
                        </div>
                        <ul class="flex w-[250px] flex-col p-4">
                            <li>
                                <a href="profile.html"
                                   class="flex items-center gap-2 rounded-md px-2 py-1.5 duration-300 hover:bg-primary hover:text-n0">
                                        <span class="flex items-center">
                    <i class="las la-user text-xl"></i>
                  </span> Profile
                                </a>
                            </li>
                            <li>
                                <a href="chat.html"
                                   class="flex items-center gap-2 rounded-md px-2 py-1.5 duration-300 hover:bg-primary hover:text-n0">
                                        <span class="flex items-center">
                    <i class="las la-envelope mt-1 text-xl"></i>
                  </span> Messages
                                </a>
                            </li>
                            <li>
                                <a href="help-center.html"
                                   class="flex items-center gap-2 rounded-md px-2 py-1.5 duration-300 hover:bg-primary hover:text-n0">
                                        <span class="flex items-center">
                    <i class="las la-life-ring mt-1 text-xl"></i>
                  </span> Help
                                </a>
                            </li>
                            <li>
                                <a href="security.html"
                                   class="flex items-center gap-2 rounded-md px-2 py-1.5 duration-300 hover:bg-primary hover:text-n0">
                                        <span class="flex items-center">
                    <i class="las la-cog mt-1 text-xl"></i>
                  </span> Settings
                                </a>
                            </li>
                            <li>
                                <a href="logout"
                                   class="flex items-center gap-2 rounded-md px-2 py-1.5 duration-300 hover:bg-primary hover:text-n0">
                                        <span class="flex items-center">
                    <i class="las la-sign-out-alt mt-1 text-xl"></i>
                  </span> Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar">
        <div class="sidebar-inner relative">
            <div class="logo-column">
                <div class="logo-container">
                    <div class="logo-inner">

                        @if(Auth::user()->role == 0)
                            <a href="admin-dashboard" class="logo-wrapper">
                                <img src="{{url('')}}/public/assets/images/mainlogo.png" width="174" height="38"
                                     class="logo-full" alt="logo"/>
                                <img src="{{url('')}}/public/assets/images/mainlogo.png" width="37" height="36"
                                     class="logo-icon hidden" alt="logo"/>
                            </a>
                        @elseif(Auth::user()->role == 2)
                            <a href="user-dashboard" class="logo-wrapper">
                                <img src="{{url('')}}/public/assets/images/mainlogo.png" width="174" height="38"
                                     class="logo-full" alt="logo"/>
                                <img src="{{url('')}}/public/assets/images/mainlogo.png" width="37" height="36"
                                     class="logo-icon hidden" alt="logo"/>
                            </a>
                        @endif

                        <img width="141" height="38" class="logo-text hidden"
                             src="{{url('')}}/public/assets/images/mainlogo.png" alt="logo text"/>
                        <button class="sidebar-close-btn xl:hidden" id="sidebar-close-btn">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                </div>
                <div class="menu-container pb-28">
                    <div class="menu-wrapper">
                        <ul class="menu-ul">
                            <li class="menu-li">
                                <button class="menu-btn group">
                  <span class="flex items-center justify-center gap-2">
                    <span class="menu-icon">
                      <i class="las la-home"></i>
                    </span>
                    <span class="menu-title font-medium">Dashboard</span>
                  </span>
                                    <span class="plus-minus">
                    <i class="las la-plus text-xl"></i>
                    <i class="las la-minus text-xl"></i>
                  </span>
                                    <span class="chevron-down hidden">
                    <i class="las la-angle-down text-base"></i>
                  </span>
                                </button>
                                <ul class="submenu-hide submenu">
                                    <li>
                                        @if(Auth::user()->role == 0)
                                            <a href="admin-dashboard" class="submenu-link">
                                                <i class="las la-minus text-xl"></i>
                                                <span>Home</span>
                                            </a>
                                        @elseif(Auth::user()->role == 2)
                                            <a href="user-dashboard" class="submenu-link">
                                                <i class="las la-minus text-xl"></i>
                                                <span>Home</span>
                                            </a>
                                        @endif


                                    </li>

                                </ul>
                            </li>
                            <li class="menu-li">
                                <button class="menu-btn group">
                  <span class="flex items-center justify-center gap-2">
                    <span class="menu-icon">
                      <i class="las la-piggy-bank"></i>
                    </span>
                    <span class="menu-title font-medium">Accounts</span>
                  </span>
                                    <span class="plus-minus">
                    <i class="las la-plus text-xl"></i>
                    <i class="las la-minus text-xl"></i>
                  </span>
                                    <span class="chevron-down hidden">
                    <i class="las la-angle-down text-base"></i>
                  </span>
                                </button>
                                <ul class="submenu-hide submenu">
                                    <li>
                                        <a href="all-customers" class="submenu-link">
                                            <i class="las la-minus text-xl"></i>
                                            <span>Customers</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="bank-account" class="submenu-link">
                                            <i class="las la-minus text-xl"></i>
                                            <span>Bank Account</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="menu-li">
                                <button class="menu-btn group">
                  <span class="flex items-center justify-center gap-2">
                    <span class="menu-icon">
                      <i class="las la-credit-card"></i>
                    </span>
                    <span class="menu-title font-medium">Cards</span>
                  </span>
                                    <span class="plus-minus">
                    <i class="las la-plus text-xl"></i>
                    <i class="las la-minus text-xl"></i>
                  </span>
                                    <span class="chevron-down hidden">
                    <i class="las la-angle-down text-base"></i>
                  </span>
                                </button>
                                <ul class="submenu-hide submenu">
                                    <li>
                                        <a href="card-overview" class="submenu-link">
                                            <i class="las la-minus text-xl"></i>
                                            <span>Card Overview</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="menu-li">
                                <button class="menu-btn group">
                  <span class="flex items-center justify-center gap-2">
                    <span class="menu-icon">
                      <i class="las la-exchange-alt"></i>
                    </span>
                    <span class="menu-title font-medium">Transaction</span>
                  </span>
                                    <span class="plus-minus">
                    <i class="las la-plus text-xl"></i>
                    <i class="las la-minus text-xl"></i>
                  </span>
                                    <span class="chevron-down hidden">
                    <i class="las la-angle-down text-base"></i>
                  </span>
                                </button>
                                <ul class="submenu-hide submenu">
                                    <li>
                                        <a href="all-transactions" class="submenu-link">
                                            <i class="las la-minus text-xl"></i>
                                            <span>All Transactions</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="bills-trasnactions" class="submenu-link">
                                            <i class="las la-minus text-xl"></i>
                                            <span>Bills Transactions</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="pending-trasnactions" class="submenu-link">
                                            <i class="las la-minus text-xl"></i>
                                            <span>Pending Transactions</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
{{--                            <li class="menu-li">--}}
{{--                                <button class="menu-btn group">--}}
{{--                  <span class="flex items-center justify-center gap-2">--}}
{{--                    <span class="menu-icon">--}}
{{--                      <i class="las la-wallet"></i>--}}
{{--                    </span>--}}
{{--                    <span class="menu-title font-medium">Payment</span>--}}
{{--                  </span>--}}
{{--                                    <span class="plus-minus">--}}
{{--                    <i class="las la-plus text-xl"></i>--}}
{{--                    <i class="las la-minus text-xl"></i>--}}
{{--                  </span>--}}
{{--                                    <span class="chevron-down hidden">--}}
{{--                    <i class="las la-angle-down text-base"></i>--}}
{{--                  </span>--}}
{{--                                </button>--}}
{{--                                <ul class="submenu-hide submenu">--}}
{{--                                    <li>--}}
{{--                                        <a href="payment-overview.html" class="submenu-link">--}}
{{--                                            <i class="las la-minus text-xl"></i>--}}
{{--                                            <span>Payment Overview</span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="providers.html" class="submenu-link">--}}
{{--                                            <i class="las la-minus text-xl"></i>--}}
{{--                                            <span>Payment Providers</span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="exchange.html" class="submenu-link">--}}
{{--                                            <i class="las la-minus text-xl"></i>--}}
{{--                                            <span>Exchange</span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="make-payment.html" class="submenu-link">--}}
{{--                                            <i class="las la-minus text-xl"></i>--}}
{{--                                            <span>Make a Payment</span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                </ul>--}}
{{--                            </li>--}}
                            <li class="menu-li">
                                <button class="menu-btn group">
                  <span class="flex items-center justify-center gap-2">
                    <span class="menu-icon">
                      <i class="las la-coins"></i>
                    </span>
                    <span class="menu-title font-medium">Transfers</span>
                  </span>
                                    <span class="plus-minus">
                    <i class="las la-plus text-xl"></i>
                    <i class="las la-minus text-xl"></i>
                  </span>
                                    <span class="chevron-down hidden">
                    <i class="las la-angle-down text-base"></i>
                  </span>
                                </button>
                                <ul class="submenu-hide submenu">
                                    <li>
                                        <a href="all-transfers" class="submenu-link">
                                            <i class="las la-minus text-xl"></i>
                                            <span>All Transfers</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="local-transfer" class="submenu-link">
                                            <i class="las la-minus text-xl"></i>
                                            <span>Local Transfers</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="inter-bank-transfer" class="submenu-link">
                                            <i class="las la-minus text-xl"></i>
                                            <span>Inter Bank Transfer</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
{{--                            <li class="menu-li">--}}
{{--                                <button class="menu-btn group">--}}
{{--                  <span class="flex items-center justify-center gap-2">--}}
{{--                    <span class="menu-icon">--}}
{{--                      <i class="las la-file-invoice"></i>--}}
{{--                    </span>--}}
{{--                    <span class="menu-title font-medium">Invoicing</span>--}}
{{--                  </span>--}}
{{--                                    <span class="plus-minus">--}}
{{--                    <i class="las la-plus text-xl"></i>--}}
{{--                    <i class="las la-minus text-xl"></i>--}}
{{--                  </span>--}}
{{--                                    <span class="chevron-down hidden">--}}
{{--                    <i class="las la-angle-down text-base"></i>--}}
{{--                  </span>--}}
{{--                                </button>--}}
{{--                                <ul class="submenu-hide submenu">--}}
{{--                                    <li>--}}
{{--                                        <a href="add-new.html" class="submenu-link">--}}
{{--                                            <i class="las la-minus text-xl"></i>--}}
{{--                                            <span>Add New Invoice</span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="invoice-01.html" class="submenu-link">--}}
{{--                                            <i class="las la-minus text-xl"></i>--}}
{{--                                            <span>Style 01</span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="invoice-02.html" class="submenu-link">--}}
{{--                                            <i class="las la-minus text-xl"></i>--}}
{{--                                            <span>Style 02</span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                </ul>--}}
{{--                            </li>--}}
{{--                            <li class="menu-li">--}}
{{--                                <button class="menu-btn group">--}}
{{--                  <span class="flex items-center justify-center gap-2">--}}
{{--                    <span class="menu-icon">--}}
{{--                      <i class="las la-chart-bar"></i>--}}
{{--                    </span>--}}
{{--                    <span class="menu-title font-medium">Trading</span>--}}
{{--                  </span>--}}
{{--                                    <span class="plus-minus">--}}
{{--                    <i class="las la-plus text-xl"></i>--}}
{{--                    <i class="las la-minus text-xl"></i>--}}
{{--                  </span>--}}
{{--                                    <span class="chevron-down hidden">--}}
{{--                    <i class="las la-angle-down text-base"></i>--}}
{{--                  </span>--}}
{{--                                </button>--}}
{{--                                <ul class="submenu-hide submenu">--}}
{{--                                    <li>--}}
{{--                                        <a href="trading-01.html" class="submenu-link">--}}
{{--                                            <i class="las la-minus text-xl"></i>--}}
{{--                                            <span>Style 01</span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="trading-02.html" class="submenu-link">--}}
{{--                                            <i class="las la-minus text-xl"></i>--}}
{{--                                            <span>Style 02</span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="trading-03.html" class="submenu-link">--}}
{{--                                            <i class="las la-minus text-xl"></i>--}}
{{--                                            <span>Style 03</span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                </ul>--}}
{{--                            </li>--}}
                            <li class="menu-li">
                                <button class="menu-btn group">
                  <span class="flex items-center justify-center gap-2">
                    <span class="menu-icon">
                      <i class="las la-chart-pie"></i>
                    </span>
                    <span class="menu-title font-medium">Reports</span>
                  </span>
                                    <span class="plus-minus">
                    <i class="las la-plus text-xl"></i>
                    <i class="las la-minus text-xl"></i>
                  </span>
                                    <span class="chevron-down hidden">
                    <i class="las la-angle-down text-base"></i>
                  </span>
                                </button>
                                <ul class="submenu-hide submenu">
                                    <li>
                                        <a href="transaction-ledger" class="submenu-link">
                                            <i class="las la-minus text-xl"></i>
                                            <span>Ledger Report</span>
                                        </a>
                                    </li>
{{--                                    <li>--}}
{{--                                        <a href="reports-02.html" class="submenu-link">--}}
{{--                                            <i class="las la-minus text-xl"></i>--}}
{{--                                            <span>Style 02</span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
                                </ul>
                            </li>
                            <li class="menu-li">
                                <button class="menu-btn group">
                  <span class="flex items-center justify-center gap-2">
                    <span class="menu-icon">
                      <i class="las la-cog"></i>
                    </span>
                    <span class="menu-title font-medium">Settings</span>
                  </span>
                                    <span class="plus-minus">
                    <i class="las la-plus text-xl"></i>
                    <i class="las la-minus text-xl"></i>
                  </span>
                                    <span class="chevron-down hidden">
                    <i class="las la-angle-down text-base"></i>
                  </span>
                                </button>
                                <ul class="submenu-hide submenu">
                                    <li>
                                        <a href="app-setting" class="submenu-link">
                                            <i class="las la-minus text-xl"></i>
                                            <span>App Setting</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="security" class="submenu-link">
                                            <i class="las la-minus text-xl"></i>
                                            <span>Security</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="menu-li">
                                <button class="menu-btn group">
                  <span class="flex items-center justify-center gap-2">
                    <span class="menu-icon">
                      <i class="las la-handshake"></i>
                    </span>
                    <span class="menu-title font-medium">Support</span>
                  </span>
                                    <span class="plus-minus">
                    <i class="las la-plus text-xl"></i>
                    <i class="las la-minus text-xl"></i>
                  </span>
                                    <span class="chevron-down hidden">
                    <i class="las la-angle-down text-base"></i>
                  </span>
                                </button>
                                <ul class="submenu-hide submenu">
                                    <li>
                                        <a href="help-center" class="submenu-link">
                                            <i class="las la-minus text-xl"></i>
                                            <span>Help Center</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                        </ul>
                    </div>


                </div>
            </div>
        </div>
    </aside>
</section>


@yield('content')

<footer class="footer bg-n0 dark:bg-bg4">
    <div class="flex flex-col items-center justify-center gap-3 px-4 py-5 lg:flex-row lg:justify-between xxl:px-8">
        <p class="text-sm max-md:w-full max-md:text-center lg:text-base">
            Copyright @ <span id="current-year"></span>
            <a class="text-primary" href="https://softivuspro.com/"> EMAARMFB </a> . Designed By
            <a href="https://teamxtech.co" class="text-secondary"> TEAM X TECH LTD </a>
        </p>
        <div class="justify-center max-md:flex max-md:w-full"></div>

    </div>
</footer>
<!-- modal -->
<div class="ac-modal-overlay modalhide fixed inset-0 z-[99] overflow-y-auto bg-n900/80 duration-500">
    <div class="overflow-y-auto">
        <div class="modal box modal-inner absolute left-1/2 my-10 max-h-[90vh] w-[95%] max-w-[710px] -translate-x-1/2 overflow-y-auto duration-300 xl:p-8">
            <!-- { "translate-y-0 scale-100 opacity-100 my-10": open } -->
            <div class="relative">
                <button class="ac-modal-close-btn absolute top-0 ltr:right-0 rtl:left-0">
                    <i class="las la-times"></i>
                </button>
                <div class="bb-dashed mb-4 flex items-center justify-between pb-4 lg:mb-6 lg:pb-6">
                    <h4 class="h4">Create Bank Account</h4>
                </div>
                <form>
                    <p class="mb-4 text-lg font-medium">Profile Photo</p>
                    <div class="mb-6 flex flex-wrap items-center gap-5 xl:gap-10">
                        <img src="{{url('')}}/public/assets/images/placeholder.png"
                             class="h-20 w-20 lg:h-auto lg:w-auto" alt="placeholder"/>
                        <div class="flex gap-4">
                            <input type="file" name="photo" id="photo" class="hidden"/>
                            <label for="photo" class="btn-primary"> Upload Photo </label>
                            <button class="btn-outline">Cancel</button>
                        </div>
                    </div>
                    <div class="mt-6 grid grid-cols-2 gap-4 xl:mt-8 xxxl:gap-6">
                        <div class="col-span-2">
                            <label for="name" class="mb-4 block font-medium md:text-lg"> Account Holder Name </label>
                            <input type="text"
                                   class="w-full rounded-3xl border border-n30 bg-secondary/5 px-6 py-2.5 dark:border-n500 dark:bg-bg3 md:py-3"
                                   placeholder="Enter Name" id="name" required/>
                        </div>
                        <div class="col-span-2">
                            <label for="number" class="mb-4 block font-medium md:text-lg"> Phone Number </label>
                            <input type="number"
                                   class="w-full rounded-3xl border border-n30 bg-secondary/5 px-6 py-2.5 dark:border-n500 dark:bg-bg3 md:py-3"
                                   placeholder="Enter Number" id="number" required/>
                        </div>
                        <div class="col-span-2">
                            <label for="currency" class="mb-4 block font-medium md:text-lg"> Select Currency </label>
                            <select name="currency" class="nc-select full dark:!border-n500">
                                <option value="usd">USD</option>
                                <option value="gbp">GBP</option>
                                <option value="yen">YEN</option>
                                <option value="jpn">JPN</option>
                            </select>
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label for="rate" class="mb-4 block font-medium md:text-lg"> Interest Rate </label>
                            <input type="number"
                                   class="w-full rounded-3xl border border-n30 bg-secondary/5 px-6 py-2.5 dark:border-n500 dark:bg-bg3 md:py-3"
                                   placeholder="Interest Rate %" id="rate" required/>
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label for="expire" class="mb-4 block font-medium md:text-lg"> Expiry Date </label>
                            <div class="relative rounded-3xl border border-n30 bg-secondary/5 py-3 dark:border-n500 dark:bg-bg3">
                                <input id="date" class="border-none" placeholder="Select Date" autocomplete="off"/>
                                <i class="las la-calendar absolute top-1/2 -translate-y-1/2 cursor-pointer ltr:right-4 rtl:left-4"></i>
                            </div>
                        </div>
                        <div class="col-span-2">
                            <label for="amount" class="mb-4 block font-medium md:text-lg"> Amount </label>
                            <input type="number"
                                   class="w-full rounded-3xl border border-n30 bg-secondary/5 px-6 py-2.5 dark:border-n500 dark:bg-bg3 md:py-3"
                                   placeholder="Enter Amount" id="amount" required/>
                        </div>
                        <div class="col-span-2">
                            <label for="status" class="mb-4 block font-medium md:text-lg"> Select Status </label>
                            <select name="currency" class="nc-select full dark:!border-n500">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-span-2 mt-4">
                            <button class="btn-primary flex w-full justify-center" type="submit">Create Account</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{url('')}}/public/assets/js/@popperjs/core@2/dist/umd/popper.min.js"></script>
<script src="{{url('')}}/public/assets/js/tippy.js@6/dist/tippy-bundle.umd.js"></script>
<script src="{{url('')}}/public/assets/js/libs/nice-select2.js"></script>
<script src="https://unpkg.com/js-datepicker"></script>
<script src="{{url('')}}/public/assets/js/libs/apexcharts.min.js"></script>
<script src="{{url('')}}/public/assets/js/charts.js"></script>
<script src="{{url('')}}/public/assets/js/main.js"></script>
<script defer src="{{url('')}}/public/assets/js/app.js"></script>
</body>

</html>
