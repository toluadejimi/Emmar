<?php $__env->startSection('content'); ?>

    <div class="body-wrapper">
        <div class="container-fluid">
            <div class="row">


                <div class="col-12">

                    <div class="row">
                        <div class="col-xl-6 col-sm-12 d-flex align-items-stretch">
                            <div class="card w-100">
                                <div class="card-body p-4">
                                    <h4 class="fw-semibold text-success"><?php echo e(number_format($successful_transaction, 2)); ?></h4>
                                    <p class="mb-2 fs-3">Total Successful</p>
                                </div>
                            </div>

                        </div>

                        <div class="col-xl-6 col-sm-12 d-flex align-items-stretch">
                            <div class="card w-100">
                                <div class="card-body p-4">
                                    <h4 class="fw-semibold text-danger"><?php echo e(number_format($failed_transaction, 2)); ?></h4>
                                    <p class="mb-2 fs-3">Total Failed</p>
                                </div>
                            </div>

                        </div>


                    </div>


                </div>


                <div class="col-lg-12 d-flex align-items-stretch">
                    <div class="card">

                        <div class="card-body">

                            <div class="card-heading">

                                <h6>Recent Transactions</h6>
                            </div>


                            <div class="table-responsive mb-4">
                                <table class="table border text-nowrap customize-table mb-0 align-middle">
                                    <thead class="text-dark fs-4">
                                    <tr>
                                        <th>
                                            <h6 class="fs-4 fw-semibold mb-0">Refrence Number (RRN)</h6>
                                        </th>

                                        <th>
                                            <h6 class="fs-4 fw-semibold mb-0">Serial No</h6>
                                        </th>

                                        <th>
                                            <h6 class="fs-4 fw-semibold mb-0">Amount</h6>
                                        </th>

                                        <th>
                                            <h6 class="fs-4 fw-semibold mb-0">Transaction Type</h6>
                                        </th>


                                        <th>
                                            <h6 class="fs-4 fw-semibold mb-0">TID</h6>
                                        </th>

                                        <th>
                                            <h6 class="fs-4 fw-semibold mb-0">Merchant Name</h6>
                                        </th>


                                        <th>
                                            <h6 class="fs-4 fw-semibold mb-0">Status</h6>
                                        </th>

                                        <th>
                                            <h6 class="fs-4 fw-semibold mb-0">Date / Time</h6>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php $__empty_1 = true; $__currentLoopData = $trasnactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                                        <tr>

                                            <td><a href="#"  data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample<?php echo e($data->RRN); ?>"
                                                   aria-controls="offcanvasExample"><?php echo e($data->RRN); ?></a></td>

                                            <div class="offcanvas customizer offcanvas-end" tabindex="-1" id="offcanvasExample<?php echo e($data->RRN); ?>"
                                                 aria-labelledby="offcanvasExampleLabel">
                                                <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                                                    <h4 class="offcanvas-title fw-semibold" id="offcanvasExampleLabel">
                                                        <?php echo e($data->RRN); ?>

                                                    </h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                                </div>






                                                <div class="offcanvas-body" data-simplebar style="height: calc(100vh - 120px)">

                                                    <div class="row">


                                                        <div class="col-12">
                                                            <h6 class="mb-4">Transaction Details</h6>
                                                            <div class="my-3 d-flex justify-content-between">
                                                                <div>RRN</div>
                                                                <div class="text-black"><?php echo e($data->RRN); ?></div>
                                                            </div>

                                                            <hr>

                                                            <div class="my-3 d-flex justify-content-between">
                                                                <div>Serial No</div>
                                                                <div class="text-black"><?php echo e($data->SerialNo); ?></div>
                                                            </div>

                                                            <hr>

                                                            <div class="my-3 d-flex justify-content-between">
                                                                <div>TID</div>
                                                                <div class="text-black"><?php echo e($data->tid); ?></div>
                                                            </div>

                                                            <hr>

                                                            <div class="my-3 d-flex justify-content-between">
                                                                <div>STAN</div>
                                                                <div class="text-black"><?php echo e($data->STAN); ?></div>
                                                            </div>

                                                            <hr>

                                                            <div class="my-3 d-flex justify-content-between">
                                                                <div>Card Expire Data</div>
                                                                <div class="text-black"><?php echo e($data->cardExpireData); ?></div>
                                                            </div>


                                                            <hr>

                                                            <div class="my-3 d-flex justify-content-between">
                                                                <div>Card Name</div>
                                                                <div class="text-black"><?php echo e($data->cardName); ?></div>
                                                            </div>

                                                            <hr>

                                                            <div class="my-3 d-flex justify-content-between">
                                                                <div>Amount</div>
                                                                <div class="text-black">₦<?php echo e(number_format($data->amount, 2)); ?></div>
                                                            </div>

                                                            <hr>

                                                            <div class="my-3 d-flex justify-content-between">
                                                                <div>Response Code</div>
                                                                <div class="text-black"><?php echo e($data->respCode); ?></div>
                                                            </div>

                                                            <hr>

                                                            <div class="my-3 d-flex justify-content-between">
                                                                <div>Response Message</div>
                                                                <div class="text-black"><?php echo e($data->responseMessage); ?></div>
                                                            </div>

                                                            <hr>

                                                            <div class="my-3 d-flex justify-content-between">
                                                                <div>Transaction Type</div>
                                                                <div class="text-black"><?php echo e($data->transactionType); ?></div>
                                                            </div>

                                                        </div>



                                                    </div>

                                                    <div class="col-12">



                                                    </div>

                                                </div>
                                            </div>



                                            <td><?php echo e($data->SerialNo); ?></td>
                                            <td>₦<?php echo e(number_format($data->amount ,2)); ?></td>
                                            <td><?php echo e($data->transactionType); ?></td>
                                            <td><?php echo e($data->tid); ?></td>

                                            <td><?php echo e($data->merchantName); ?></td>
                                            <td>
                                                <?php if($data->status == 1): ?>
                                                    <span
                                                        class="badge bg-success-subtle rounded-3 py-2 text-success fw-semibold fs-2 d-inline-flex align-items-center gap-1">
                                            <iconify-icon icon="fluent-mdl2:check-mark"></iconify-icon>
                                            Successful
                                                </span>
                                                <?php else: ?>
                                                    <span
                                                        class="badge bg-danger-subtle rounded-3 py-2 text-danger fw-semibold fs-2 d-inline-flex align-items-center gap-1">
                                            <iconify-icon icon="fluent-mdl2:"></iconify-icon>
                                            Failed
                                                <?php endif; ?>

                                            </td>

                                            <td><?php echo e($data->created_at); ?></td>


                                        </tr>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                                        <tr>
                                            No record found
                                        </tr>

                                    <?php endif; ?>

                                    </tbody>


                                    <?php echo e($trasnactions->links()); ?>

                                </table>

                            </div>


                        </div>


                    </div>


                </div>
            </div>
        </div>
        <script>
            function handleColorTheme(e) {
                $("html").attr("data-color-theme", e);
                $(e).prop("checked", !0);
            }

        </script>
        <button
            class="btn btn-primary p-3 rounded-circle d-flex align-items-center justify-content-center customizer-btn"
            type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample"
            aria-controls="offcanvasExample">
            <i class="icon ti ti-settings fs-7"></i>
        </button>

        <div class="offcanvas customizer offcanvas-end" tabindex="-1" id="offcanvasExample"
             aria-labelledby="offcanvasExampleLabel">
            <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                <h4 class="offcanvas-title fw-semibold" id="offcanvasExampleLabel">
                    Settings
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body" data-simplebar style="height: calc(100vh - 80px)">
                <h6 class="fw-semibold fs-4 mb-2">Theme</h6>

                <div class="d-flex flex-row gap-3 customizer-box" role="group">
                    <input type="radio" class="btn-check" name="theme-layout" id="light-layout" autocomplete="off"/>
                    <label class="btn p-9 btn-outline-primary" for="light-layout"><i
                            class="icon ti ti-brightness-up fs-7 me-2"></i>Light</label>

                    <input type="radio" class="btn-check" name="theme-layout" id="dark-layout" autocomplete="off"/>
                    <label class="btn p-9 btn-outline-primary" for="dark-layout"><i
                            class="icon ti ti-moon fs-7 me-2"></i>Dark</label>
                </div>

                <h6 class="mt-5 fw-semibold fs-4 mb-2">Theme Direction</h6>
                <div class="d-flex flex-row gap-3 customizer-box" role="group">
                    <input type="radio" class="btn-check" name="direction-l" id="ltr-layout" autocomplete="off"/>
                    <label class="btn p-9 btn-outline-primary" for="ltr-layout"><i
                            class="icon ti ti-text-direction-ltr fs-7 me-2"></i>LTR</label>

                    <input type="radio" class="btn-check" name="direction-l" id="rtl-layout" autocomplete="off"/>
                    <label class="btn p-9 btn-outline-primary" for="rtl-layout"><i
                            class="icon ti ti-text-direction-rtl fs-7 me-2"></i>RTL</label>
                </div>

                <h6 class="mt-5 fw-semibold fs-4 mb-2">Theme Colors</h6>

                <div class="d-flex flex-row flex-wrap gap-3 customizer-box color-pallete" role="group">
                    <input type="radio" class="btn-check" name="color-theme-layout" id="Blue_Theme" autocomplete="off"/>
                    <label class="btn p-9 btn-outline-primary d-flex align-items-center justify-content-center"
                           onclick="handleColorTheme('Blue_Theme')" for="Blue_Theme" data-bs-toggle="tooltip"
                           data-bs-placement="top" data-bs-title="BLUE_THEME">
                        <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-1">
                            <i class="ti ti-check text-white d-flex icon fs-5"></i>
                        </div>
                    </label>

                    <input type="radio" class="btn-check" name="color-theme-layout" id="Aqua_Theme" autocomplete="off"/>
                    <label class="btn p-9 btn-outline-primary d-flex align-items-center justify-content-center"
                           onclick="handleColorTheme('Aqua_Theme')" for="Aqua_Theme" data-bs-toggle="tooltip"
                           data-bs-placement="top" data-bs-title="AQUA_THEME">
                        <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-2">
                            <i class="ti ti-check text-white d-flex icon fs-5"></i>
                        </div>
                    </label>

                    <input type="radio" class="btn-check" name="color-theme-layout" id="Purple_Theme"
                           autocomplete="off"/>
                    <label class="btn p-9 btn-outline-primary d-flex align-items-center justify-content-center"
                           onclick="handleColorTheme('Purple_Theme')" for="Purple_Theme" data-bs-toggle="tooltip"
                           data-bs-placement="top" data-bs-title="PURPLE_THEME">
                        <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-3">
                            <i class="ti ti-check text-white d-flex icon fs-5"></i>
                        </div>
                    </label>

                    <input type="radio" class="btn-check" name="color-theme-layout" id="green-theme-layout"
                           autocomplete="off"/>
                    <label class="btn p-9 btn-outline-primary d-flex align-items-center justify-content-center"
                           onclick="handleColorTheme('Green_Theme')" for="green-theme-layout" data-bs-toggle="tooltip"
                           data-bs-placement="top" data-bs-title="GREEN_THEME">
                        <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-4">
                            <i class="ti ti-check text-white d-flex icon fs-5"></i>
                        </div>
                    </label>

                    <input type="radio" class="btn-check" name="color-theme-layout" id="cyan-theme-layout"
                           autocomplete="off"/>
                    <label class="btn p-9 btn-outline-primary d-flex align-items-center justify-content-center"
                           onclick="handleColorTheme('Cyan_Theme')" for="cyan-theme-layout" data-bs-toggle="tooltip"
                           data-bs-placement="top" data-bs-title="CYAN_THEME">
                        <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-5">
                            <i class="ti ti-check text-white d-flex icon fs-5"></i>
                        </div>
                    </label>

                    <input type="radio" class="btn-check" name="color-theme-layout" id="orange-theme-layout"
                           autocomplete="off"/>
                    <label class="btn p-9 btn-outline-primary d-flex align-items-center justify-content-center"
                           onclick="handleColorTheme('Orange_Theme')" for="orange-theme-layout" data-bs-toggle="tooltip"
                           data-bs-placement="top" data-bs-title="ORANGE_THEME">
                        <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-6">
                            <i class="ti ti-check text-white d-flex icon fs-5"></i>
                        </div>
                    </label>
                </div>

                <h6 class="mt-5 fw-semibold fs-4 mb-2">Layout Type</h6>
                <div class="d-flex flex-row gap-3 customizer-box" role="group">
                    <div>
                        <input type="radio" class="btn-check" name="page-layout" id="vertical-layout"
                               autocomplete="off"/>
                        <label class="btn p-9 btn-outline-primary" for="vertical-layout"><i
                                class="icon ti ti-layout-sidebar-right fs-7 me-2"></i>Vertical</label>
                    </div>
                    <div>
                        <input type="radio" class="btn-check" name="page-layout" id="horizontal-layout"
                               autocomplete="off"/>
                        <label class="btn p-9 btn-outline-primary" for="horizontal-layout"><i
                                class="icon ti ti-layout-navbar fs-7 me-2"></i>Horizontal</label>
                    </div>
                </div>

                <h6 class="mt-5 fw-semibold fs-4 mb-2">Container Option</h6>

                <div class="d-flex flex-row gap-3 customizer-box" role="group">
                    <input type="radio" class="btn-check" name="layout" id="boxed-layout" autocomplete="off"/>
                    <label class="btn p-9 btn-outline-primary" for="boxed-layout"><i
                            class="icon ti ti-layout-distribute-vertical fs-7 me-2"></i>Boxed</label>

                    <input type="radio" class="btn-check" name="layout" id="full-layout" autocomplete="off"/>
                    <label class="btn p-9 btn-outline-primary" for="full-layout"><i
                            class="icon ti ti-layout-distribute-horizontal fs-7 me-2"></i>Full</label>
                </div>

                <h6 class="fw-semibold fs-4 mb-2 mt-5">Sidebar Type</h6>
                <div class="d-flex flex-row gap-3 customizer-box" role="group">
                    <a href="javascript:void(0)" class="fullsidebar">
                        <input type="radio" class="btn-check" name="sidebar-type" id="full-sidebar" autocomplete="off"/>
                        <label class="btn p-9 btn-outline-primary" for="full-sidebar"><i
                                class="icon ti ti-layout-sidebar-right fs-7 me-2"></i>Full</label>
                    </a>
                    <div>
                        <input type="radio" class="btn-check " name="sidebar-type" id="mini-sidebar"
                               autocomplete="off"/>
                        <label class="btn p-9 btn-outline-primary" for="mini-sidebar"><i
                                class="icon ti ti-layout-sidebar fs-7 me-2"></i>Collapse</label>
                    </div>
                </div>

                <h6 class="mt-5 fw-semibold fs-4 mb-2">Card With</h6>

                <div class="d-flex flex-row gap-3 customizer-box" role="group">
                    <input type="radio" class="btn-check" name="card-layout" id="card-with-border" autocomplete="off"/>
                    <label class="btn p-9 btn-outline-primary" for="card-with-border"><i
                            class="icon ti ti-border-outer fs-7 me-2"></i>Border</label>

                    <input type="radio" class="btn-check" name="card-layout" id="card-without-border"
                           autocomplete="off"/>
                    <label class="btn p-9 btn-outline-primary" for="card-without-border"><i
                            class="icon ti ti-border-none fs-7 me-2"></i>Shadow</label>
                </div>
            </div>
        </div>
    </div>

    <!--  Search Bar -->


























































































    <!--  Shopping Cart -->
    <div class="offcanvas offcanvas-end shopping-cart" tabindex="-1" id="offcanvasRight"
         aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header py-4">
            <h5 class="offcanvas-title fs-5 fw-semibold" id="offcanvasRightLabel">
                Shopping Cart
            </h5>
            <span class="badge bg-primary rounded-4 px-3 py-1 lh-sm">5 new</span>
        </div>
        <div class="offcanvas-body h-100 px-4 pt-0" data-simplebar>
            <ul class="mb-0">
                <li class="pb-7">
                    <div class="d-flex align-items-center">
                        <img src="<?php echo e(url('')); ?>/public/assets2/images/products/product-1.jpg" width="95" height="75"
                             class="rounded-1 me-9 flex-shrink-0" alt=""/>
                        <div>
                            <h6 class="mb-1">Supreme toys cooker</h6>
                            <p class="mb-0 text-muted fs-2">Kitchenware Item</p>
                            <div class="d-flex align-items-center justify-content-between mt-2">
                                <h6 class="fs-2 fw-semibold mb-0 text-muted">$250</h6>
                                <div class="input-group input-group-sm w-50">
                                    <button class="btn border-0 round-20 minus p-0 bg-success-subtle text-success"
                                            type="button" id="add1">
                                        -
                                    </button>
                                    <input type="text"
                                           class="form-control round-20 bg-transparent text-muted fs-2 border-0 text-center qty"
                                           placeholder="" aria-label="Example text with button addon"
                                           aria-describedby="add1" value="1"/>
                                    <button class="btn text-success bg-success-subtle p-0 round-20 border-0 add"
                                            type="button" id="addo2">
                                        +
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="pb-7">
                    <div class="d-flex align-items-center">
                        <img src="<?php echo e(url('')); ?>/public/assets2/images/products/product-2.jpg" width="95" height="75"
                             class="rounded-1 me-9 flex-shrink-0" alt=""/>
                        <div>
                            <h6 class="mb-1">Supreme toys cooker</h6>
                            <p class="mb-0 text-muted fs-2">Kitchenware Item</p>
                            <div class="d-flex align-items-center justify-content-between mt-2">
                                <h6 class="fs-2 fw-semibold mb-0 text-muted">$250</h6>
                                <div class="input-group input-group-sm w-50">
                                    <button class="btn border-0 round-20 minus p-0 bg-success-subtle text-success"
                                            type="button" id="add2">
                                        -
                                    </button>
                                    <input type="text"
                                           class="form-control round-20 bg-transparent text-muted fs-2 border-0 text-center qty"
                                           placeholder="" aria-label="Example text with button addon"
                                           aria-describedby="add2" value="1"/>
                                    <button class="btn text-success bg-success-subtle p-0 round-20 border-0 add"
                                            type="button" id="addon34">
                                        +
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="pb-7">
                    <div class="d-flex align-items-center">
                        <img src="<?php echo e(url('')); ?>/public/assets2/images/products/product-3.jpg" width="95" height="75"
                             class="rounded-1 me-9 flex-shrink-0" alt=""/>
                        <div>
                            <h6 class="mb-1">Supreme toys cooker</h6>
                            <p class="mb-0 text-muted fs-2">Kitchenware Item</p>
                            <div class="d-flex align-items-center justify-content-between mt-2">
                                <h6 class="fs-2 fw-semibold mb-0 text-muted">$250</h6>
                                <div class="input-group input-group-sm w-50">
                                    <button class="btn border-0 round-20 minus p-0 bg-success-subtle text-success"
                                            type="button" id="add3">
                                        -
                                    </button>
                                    <input type="text"
                                           class="form-control round-20 bg-transparent text-muted fs-2 border-0 text-center qty"
                                           placeholder="" aria-label="Example text with button addon"
                                           aria-describedby="add3" value="1"/>
                                    <button class="btn text-success bg-success-subtle p-0 round-20 border-0 add"
                                            type="button" id="addon3">
                                        +
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <div class="align-bottom">
                <div class="d-flex align-items-center pb-7">
                    <span class="text-dark fs-3">Sub Total</span>
                    <div class="ms-auto">
                        <span class="text-dark fw-semibold fs-3">$2530</span>
                    </div>
                </div>
                <div class="d-flex align-items-center pb-7">
                    <span class="text-dark fs-3">Total</span>
                    <div class="ms-auto">
                        <span class="text-dark fw-semibold fs-3">$6830</span>
                    </div>
                </div>
                <a href="eco-checkout.html" class="btn btn-outline-primary w-100">Go to shopping cart</a>
            </div>
        </div>
    </div>

    </div>
    <div class="dark-transparent sidebartoggler"></div>
    <!-- Import Js Files -->

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/project/etop/resources/views/all-transactions.blade.php ENDPATH**/ ?>
