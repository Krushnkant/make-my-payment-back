<?php $__env->startSection('content'); ?>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 mb-4">
                        <a href="<?php echo e(route('abusives.create')); ?>" class="btn btn-info"> Add Abusive </a>
                    </div>
                    <div class="col-lg-6">
                        <h4 class="card-title">Abusive Information</h4>
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
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $abusives; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$abusive): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e(($key+1) + ($abusives->currentPage() - 1)*$abusives->perPage()); ?></td>
                                            <td><?php echo e($abusive->name ?? '-'); ?></td>
                                            <td>
                                                <a href="<?php echo e(route('abusives.edit', $abusive->id)); ?>" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> </a>
                                                <a href="javascript:void(0)"  class="btn btn-danger btn-sm"
                                                    onclick="ConformAlerts(<?php echo e($abusive->id); ?>,'<?php echo e(route('abusives.destroy')); ?>' )" ><i class="fas fa-trash-alt"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end">
                                <?php echo e($abusives->links( "pagination::bootstrap-4")); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/laravel/resources/views/abusives/index.blade.php ENDPATH**/ ?>