<?php $__env->startSection('content'); ?>


    <div class="body-wrapper">


        <div class="container-fluid">

            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>
            <?php if(session()->has('message')): ?>
                <div class="alert alert-success">
                    <?php echo e(session()->get('message')); ?>

                </div>
            <?php endif; ?>
            <?php if(session()->has('error')): ?>
                <div class="alert alert-danger">
                    <?php echo e(session()->get('error')); ?>

                </div>
            <?php endif; ?>

            <div class="row">



                <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
                    <div class="card-body px-4 py-3">
                        <div class="row align-items-center">
                            <div class="col-9">
                                <h4 class="fw-semibold mb-8">Geo Fence Information</h4>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a class="text-muted text-decoration-none" href="home">Home</a>
                                        </li>
                                        <li class="breadcrumb-item" aria-current="page">Manage Geo-Fence</li>
                                    </ol>
                                </nav>
                            </div>
                            <div class="col-3">
                                <div class="text-center mb-n5">
                                    <img src="<?php echo e(url('')); ?>/public/assets2/images/breadcrumb/ChatBc.png" alt="" class="img-fluid mb-n4" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card mt-4">
                    <div class="card-body">

                        <a href="add-new-zone" class="btn btn-primary my-4"> Add New Zone</a>
                        <h5 class="mb-3">List of Zones</h5>


                        <table class="table table-sm table-responsive-sm">
                            <thead style="border-radius: 100px; background: #10113D;color: #ffffff;">
                            <tr class>
                                <th>Zone Name</th>
                                <th>Status</th>
                                <th>Date Created</th>


                            </tr>
                            </thead>
                            <tbody>

                            <?php $__currentLoopData = $zone; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <td class="">
                                    <a href="view-zone?id=<?php echo e($data->id); ?>">
                                        <?php echo e($data->zone_name); ?>

                                    </a>
                                </td>

                                <td>
                                    <?php if($data->status == 2): ?>
                                        <span class="badge bg-success-subtle rounded-3 py-2 text-success fw-semibold fs-2 d-inline-flex align-items-center gap-1">
                                            <iconify-icon icon="fluent-mdl2:check-mark"></iconify-icon>
                                            Active
                                        </span>
                                    <?php elseif($data->status == 0): ?>

                                        <span class="badge bg-danger-subtle rounded-3 py-2 text-danger fw-semibold fs-2 d-inline-flex align-items-center gap-1">
                                            <iconify-icon icon="fluent-mdl2:check-mark"></iconify-icon>
                                            Inactive
                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-danger-subtle rounded-3 py-2 text-danger fw-semibold fs-2 d-inline-flex align-items-center gap-1">
                                            <iconify-icon icon="fluent-mdl2:check-mark"></iconify-icon>
                                            Inactive
                                        </span>
                                    <?php endif; ?>

                                </td>

                                <td><?php echo e($data->created_at); ?></td>

                            </tbody>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </table>

                    </div>

                </div>


            </div>







<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/project/etop/resources/views/zone.blade.php ENDPATH**/ ?>