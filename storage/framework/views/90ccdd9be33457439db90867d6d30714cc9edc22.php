<?php $__env->startSection('content'); ?>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <h4 class="card-title">Transection Information</h4>
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
                            <table id="order-listng" class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Package Name</th>
                                        <th>Total Calls</th>
                                        <th>Total Message</th>
                                        <th>Transection Amount</th>
                                        <th>Payment Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $transections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$transection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e(($key+1) + ($transections->currentPage() - 1)*$transections->perPage()); ?></td>
                                            <td><?php echo e($transection->user->name ?? '-'); ?></td>
                                            <td><?php echo e($transection->package_name ?? '-'); ?></td>
                                            <td><?php echo e($transection->package_calls ?? '-'); ?></td>
                                            <td><?php echo e($transection->package_message ?? '-'); ?></td>
                                            <td><?php echo e(FinalPrice($transection->transection_amount)); ?></td>
                                            <?php if($transection->payment_status == 'paid'): ?>
                                                <td> <button class="btn btn-success"><?php echo e($transection->payment_status ?? '-'); ?></button></td>
                                            <?php else: ?> 
                                                <td> <button class="btn btn-danger"><?php echo e($transection->payment_status ?? '-'); ?></button></td>
                                            <?php endif; ?>
                                            <td>
                                                <a href="javascript:void(0)"  class="btn btn-danger btn-sm"
                                                    onclick="ConformAlerts(<?php echo e($transection->id); ?>,'<?php echo e(route('transections.destroy')); ?>' )" >
                                                        <i class="fas fa-trash-alt"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end">
                                <?php echo e($transections->links( "pagination::bootstrap-4")); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/laravel/resources/views/transections/index.blade.php ENDPATH**/ ?>