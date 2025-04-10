<?php $__env->startSection('content'); ?>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 mb-4">
                        <a href="<?php echo e(route('ads.create')); ?>" class="btn btn-info"> Add Ads </a>
                    </div>
                    <div class="col-lg-6">
                        <h4 class="card-title">Ads Information</h4>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex justify-content-end" style="min-width: 200px;">
                            <form class="" id="sort_categories" action="" method="GET">
                                <div class="form-outline">
                                    <input type="text" class="form-control" id="search" name="search"<?php if(isset($sort_search)): ?> value="<?php echo e($sort_search); ?>" <?php endif; ?> placeholder=" Type Ad Id & Enter">
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
                                        <th>Device Type</th>
                                        <th>Ad Id</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $ads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$ad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e(($key+1) + ($ads->currentPage() - 1)*$ads->perPage()); ?></td>
                                            <td><?php echo e($ad->device_type ?? '-'); ?></td>
                                            <td><?php echo e($ad->ad_id ?? '-'); ?></td>
                                            <td>
                                                <a href="<?php echo e(route('ads.edit', $ad->id)); ?>" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> </a>
                                                <a href="javascript:void(0)"  class="btn btn-danger btn-sm"
                                                    onclick="ConformAlerts(<?php echo e($ad->id); ?>,'<?php echo e(route('ads.destroy')); ?>' )" ><i class="fas fa-trash-alt"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end">
                                <?php echo e($ads->links( "pagination::bootstrap-4")); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/laravel/resources/views/ads/index.blade.php ENDPATH**/ ?>