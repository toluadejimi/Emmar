@extends('layout.main')
@section('content')

    <!-- Main Content -->
    <main class="main-content has-sidebar">
        <div class="main-inner">
            <div class="mb-6 flex flex-wrap items-center justify-between gap-4 lg:mb-8">
                <h2 class="h2">Admin Dashboard</h2>
                <button class="btn-primary ac-modal-btn">
                    <i class="las la-plus-circle text-base md:text-lg"></i>
                    Open an Account
                </button>
            </div>

            <div class="grid grid-cols-12 gap-4 xxl:gap-6">
                <!-- Statistics -->
                <div class="box col-span-12 bg-n0 dark:bg-bg4 min-[650px]:col-span-6 xxxl:col-span-3">
                    <div class="bb-dashed mb-4 flex items-center justify-between pb-4 lg:mb-6 lg:pb-6">
                        <span class="font-medium">Total Inflow</span>

                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="h4 mb-4">₦{{$total_inflow}}</h4>
                        </div>

                    </div>
                </div>
                <div class="box col-span-12 bg-n0 dark:bg-bg4 min-[650px]:col-span-6 xxxl:col-span-3">
                    <div class="bb-dashed mb-4 flex items-center justify-between pb-4 lg:mb-6 lg:pb-6">
                        <span class="font-medium">Total Outflow</span>

                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="h4 mb-4">₦{{$total_outflow}}</h4>
                        </div>

                    </div>
                </div>
                <div class="box col-span-12 bg-n0 dark:bg-bg4 min-[650px]:col-span-6 xxxl:col-span-3">
                    <div class="bb-dashed mb-4 flex items-center justify-between pb-4 lg:mb-6 lg:pb-6">
                        <span class="font-medium">Total Customer</span>

                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="h4 mb-4">{{$total_customer}}</h4>
                        </div>

                    </div>
                </div>
                <div class="box col-span-12 bg-n0 dark:bg-bg4 min-[650px]:col-span-6 xxxl:col-span-3">
                    <div class="bb-dashed mb-4 flex items-center justify-between pb-4 lg:mb-6 lg:pb-6">
                        <span class="font-medium">Total Cards</span>

                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="h4 mb-4">{{$total_cards}}</h4>
                        </div>
                    </div>
                </div>
                <!-- Assetchart -->


                <div class="box col-span-12 overflow-x-hidden">

                    <div class="box overflow-x-hidden">
                        <div
                            class="bb-dashed mb-4 flex flex-wrap items-center justify-between gap-3 pb-4 lg:mb-6 lg:pb-6">
                            <h4 class="h4">Inflow and Outflow</h4>
                        </div>
                        <div class="income-chart"></div>
                    </div>
                </div>

            </div>


            <!-- Latest Transactions -->
            <div class="box col-span-12 lg:col-span-6">
                <div class="bb-dashed mb-4 flex flex-wrap items-center justify-between gap-4 pb-4 lg:mb-6 lg:pb-6">
                    <h4 class="h4">Latest Transaction</h4>
                    {{--                    <div class="relative">--}}
                    {{--                        <i class="las la-ellipsis-h horiz-option-btn cursor-pointer popover-button"></i>--}}
                    {{--                        <ul class="horiz-option popover-content">--}}
                    {{--                            <li>--}}
                    {{--                                <span> Edit </span>--}}
                    {{--                            </li>--}}
                    {{--                            <li>--}}
                    {{--                                <span> Print </span>--}}
                    {{--                            </li>--}}
                    {{--                            <li>--}}
                    {{--                                <span> Share </span>--}}
                    {{--                            </li>--}}
                    {{--                        </ul>--}}
                    {{--                    </div>--}}

                </div>
                <div class="overflow-x-auto">
                    <table class="w-full whitespace-nowrap">
                        <thead>
                        <tr class="bg-secondary/5 dark:bg-bg3">
                            <th class="flex min-w-[300px] cursor-pointer items-center gap-1 px-6 py-5 text-start">
                                Title
                            </th>
                            <th class="min-w-[120px] cursor-pointer px-6 py-5 text-start">
                                <div class="flex items-center gap-1">Type</div>
                            </th>
                            <th class="min-w-[120px] cursor-pointer px-6 py-5 text-start">
                                <div class="flex items-center gap-1">Amount</div>
                            </th>
                            <th class="min-w-[120px] cursor-pointer px-6 py-5 text-start">
                                <div class="flex items-center gap-1">Status</div>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Transactions Data -->
                        <tr class="even:bg-secondary/5 dark:even:bg-bg3">

                        @forelse($transactions as $data)
                            <tr>
                                <td class="px-6 py-2">
                                    @php
                                        $icon = 'placeholder.png'; // fallback
                                        if ($data->transaction_type === 'transfer') {
                                            $icon = 'transfer.png';
                                        } elseif ($data->transaction_type === 'vas') {
                                            $icon = 'bills.png';
                                        }
                                    @endphp

                                    <div class="flex items-center gap-3">
                                        <img src="{{ url('') }}/public/assets/images/{{ $icon }}" width="32" height="32"
                                             class="rounded-full" alt="payment medium icon"/>
                                        <div>
                                            <p class="mb-1 font-medium">{{ $data->trx_ref ?? 'Transaction Ref' }}</p>
                                            <span class="text-xs">
                                                {{ \Carbon\Carbon::parse($data->created_at)->format('d M, y. h:i a') }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-2">{{ $data->transaction_type ?? 'Unknown' }}</td>

                                <td class="px-6 py-2">
                                    @php
                                        $amount = $data->credit > 0 ? $data->credit : $data->debit;
                                    @endphp
                                    ₦{{ number_format($amount, 2) }}
                                </td>

                                <td class="px-6 py-2">
                                    @php
                                        switch ($data->status) {
                                            case 2:
                                                $statusText = 'Successful';
                                                $statusClass = 'text-primary bg-primary/10 border border-n30 dark:border-n500 dark:bg-bg3';
                                                break;
                                            case 3:
                                                $statusText = 'Rejected';
                                                $statusClass = 'text-red-500 bg-red-100 border border-red-300 dark:border-red-500 dark:bg-red-900/20';
                                                break;
                                            case 0:
                                            default:
                                                $statusText = 'Pending';
                                                $statusClass = 'text-yellow-600 bg-yellow-100 border border-yellow-300 dark:border-yellow-500 dark:bg-yellow-900/20';
                                                break;
                                        }
                                    @endphp

                                    <span class="block w-28 rounded-[30px] py-2 text-center text-xs {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-gray-500">No transactions found.</td>
                            </tr>
                        @endforelse


                        <!-- Add more rows for the remaining data items -->
                        </tbody>
                    </table>
                </div>
                <a class="group mt-6 inline-flex items-center gap-1 font-semibold text-primary" href="index.html#">
                    See More
                    <i class="las la-arrow-right duration-300 group-hover:pl-2"></i>
                </a>
            </div>
            <!-- Transaction account -->
            <div class="box col-span-12 lg:col-span-6">
                <div class="bb-dashed mb-4 flex flex-wrap items-center justify-between gap-4 pb-4 lg:mb-6 lg:pb-6">
                    <h4 class="h4">Transaction Account</h4>
                    <div class="relative">
                        <i class="las la-ellipsis-h horiz-option-btn cursor-pointer popover-button"></i>
                        <ul class="horiz-option popover-content">
                            <li>
                                <span> Edit </span>
                            </li>
                            <li>
                                <span> Print </span>
                            </li>
                            <li>
                                <span> Share </span>
                            </li>
                        </ul>
                    </div>

                </div>
                <div class="overflow-x-auto">
                    <table class="w-full whitespace-nowrap">
                        <thead>
                        <tr class="bg-secondary/5 dark:bg-bg3">
                            <th class="min-w-[280px] cursor-pointer px-6 py-5 text-start">
                                <div class="flex items-center gap-1">Title</div>
                            </th>
                            <th class="w-[20%] cursor-pointer px-6 py-5 text-start">
                                <div class="flex items-center gap-1">Amount</div>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Transactions Data -->
                        <tr key="John Snow - Metal" class="even:bg-secondary/5 dark:even:bg-bg3">
                            <td class="px-6 py-2">
                                <div class="flex items-center gap-3">
                                    <img src="{{url('')}}/public/assets/images/card-sm-1.png" width="60" height="40"
                                         class="rounded" alt="payment medium icon"/>
                                    <div>
                                        <p class="mb-1 font-medium">John Snow - Metal</p>
                                        <span class="text-xs">**4291 - Exp: 12/26</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-2">
                                <div>
                                    <p class="font-medium">$95,200.00</p>
                                    <span class="text-xs">Account Balance</span>
                                </div>
                            </td>
                        </tr>
                        <tr key="John Snow - Virtual" class="even:bg-secondary/5 dark:even:bg-bg3">
                            <td class="px-6 py-2">
                                <div class="flex items-center gap-3">
                                    <img src="{{url('')}}/public/assets/images/card-sm-2.png" width="60" height="40"
                                         class="rounded" alt="payment medium icon"/>
                                    <div>
                                        <p class="mb-1 font-medium">John Snow - Virtual</p>
                                        <span class="text-xs">**4291 - Exp: 12/26</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-2">
                                <div>
                                    <p class="font-medium">€54,448.54</p>
                                    <span class="text-xs">Account Balance</span>
                                </div>
                            </td>
                        </tr>
                        <tr key="Ben Abramov - Metal" class="even:bg-secondary/5 dark:even:bg-bg3">
                            <td class="px-6 py-2">
                                <div class="flex items-center gap-3">
                                    <img src="{{url('')}}/public/assets/images/card-sm-3.png" width="60" height="40"
                                         class="rounded" alt="payment medium icon"/>
                                    <div>
                                        <p class="mb-1 font-medium">Ben Abramov - Metal</p>
                                        <span class="text-xs">**4291 - Exp: 12/26</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-2">
                                <div>
                                    <p class="font-medium">£74,215.32</p>
                                    <span class="text-xs">Account Balance</span>
                                </div>
                            </td>
                        </tr>
                        <tr key="John Cina - Virtual" class="even:bg-secondary/5 dark:even:bg-bg3">
                            <td class="px-6 py-2">
                                <div class="flex items-center gap-3">
                                    <img src="{{url('')}}/public/assets/images/card-sm-8.png" width="60" height="40"
                                         class="rounded" alt="payment medium icon"/>
                                    <div>
                                        <p class="mb-1 font-medium">John Cina - Virtual</p>
                                        <span class="text-xs">**4291 - Exp: 12/26</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-2">
                                <div>
                                    <p class="font-medium">د.ك 67,511.21</p>
                                    <span class="text-xs">Account Balance</span>
                                </div>
                            </td>
                        </tr>
                        <tr key="Kane Methew - Metal" class="even:bg-secondary/5 dark:even:bg-bg3">
                            <td class="px-6 py-2">
                                <div class="flex items-center gap-3">
                                    <img src="{{url('')}}/public/assets/images/card-sm-4.png" width="60" height="40"
                                         class="rounded" alt="payment medium icon"/>
                                    <div>
                                        <p class="mb-1 font-medium">Kane Methew - Metal</p>
                                        <span class="text-xs">**4291 - Exp: 12/26</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-2">
                                <div>
                                    <p class="font-medium">¥36,122,54</p>
                                    <span class="text-xs">Account Balance</span>
                                </div>
                            </td>
                        </tr>
                        <tr key="Jane Alam - Virtual" class="even:bg-secondary/5 dark:even:bg-bg3">
                            <td class="px-6 py-2">
                                <div class="flex items-center gap-3">
                                    <img src="{{url('')}}/public/assets/images/card-sm-5.png" width="60" height="40"
                                         class="rounded" alt="payment medium icon"/>
                                    <div>
                                        <p class="mb-1 font-medium">Jane Alam - Virtual</p>
                                        <span class="text-xs">**4291 - Exp: 12/26</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-2">
                                <div>
                                    <p class="font-medium">₹75,121,36</p>
                                    <span class="text-xs">Account Balance</span>
                                </div>
                            </td>
                        </tr>
                        <tr key="Jabed Miah - Metal" class="even:bg-secondary/5 dark:even:bg-bg3">
                            <td class="px-6 py-2">
                                <div class="flex items-center gap-3">
                                    <img src="{{url('')}}/public/assets/images/card-sm-6.png" width="60" height="40"
                                         class="rounded" alt="payment medium icon"/>
                                    <div>
                                        <p class="mb-1 font-medium">Jabed Miah - Metal</p>
                                        <span class="text-xs">**4291 - Exp: 12/26</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-2">
                                <div>
                                    <p class="font-medium">₽88,125.00</p>
                                    <span class="text-xs">Account Balance</span>
                                </div>
                            </td>
                        </tr>
                        <tr key="Bablu Sheikh - Virtual" class="even:bg-secondary/5 dark:even:bg-bg3">
                            <td class="px-6 py-2">
                                <div class="flex items-center gap-3">
                                    <img src="{{url('')}}/public/assets/images/card-sm-7.png" width="60" height="40"
                                         class="rounded" alt="payment medium icon"/>
                                    <div>
                                        <p class="mb-1 font-medium">Bablu Sheikh - Virtual</p>
                                        <span class="text-xs">**4291 - Exp: 12/26</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-2">
                                <div>
                                    <p class="font-medium">¢96,214.03</p>
                                    <span class="text-xs">Account Balance</span>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <a class="group mt-6 inline-flex items-center gap-1 font-semibold text-primary" href="index.html#">
                    See More
                    <i class="las la-arrow-right duration-300 group-hover:pl-2"></i>
                </a>
            </div>
        </div>
        </div>
    </main>


    <script>
        const inflowdata = @json($inflow);
        const outflowdata = @json($outflow);
    </script>

    <script src="{{ asset('js/charts.js') }}"></script>

@endsection
