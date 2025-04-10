<?php $__env->startSection('content'); ?>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <h4 class="card-title">Notification Information</h4>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex justify-content-end" style="min-width: 200px;">
                            <form class="" id="sort_categories" action="" method="GET">
                                <div class="form-outline">
                                    <input type="text" class="form-control" id="search" name="search"<?php if(isset($sort_search)): ?> value="<?php echo e($sort_search); ?>" <?php endif; ?> placeholder=" Type name & Enter">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="order-listig" class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User Name</th>
                                        <th>Title</th>
                                        <th>Customer Name</th>
                                        <th>Phone</th>
                                        <th>Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e(($key+1)); ?></td>
                                            <td><?php echo e($item->user->name ?? '-'); ?></td>
                                            <td><?php echo e($item->title ?? '-'); ?></td>
                                            <td><?php echo e($item->customer_name ?? '-'); ?></td>
                                            <td><?php echo e($item->customer_mobile ?? '-'); ?></td>
                                            <td><?php echo e($item->type ?? '-'); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end">
                                <?php echo e($notifications->links( "pagination::bootstrap-4")); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/laravel/resources/views/notifications/index.blade.php ENDPATH**/ ?>