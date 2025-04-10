<?php $__env->startSection('content'); ?>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <h4 class="card-title">Package Information</h4>
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
                            <table id="order-listin" class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Calls</th>
                                        <th>Message</th>
                                        <th>Details</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e(($key+1) + ($packages->currentPage() - 1)*$packages->perPage()); ?></td>
                                            <td><?php echo e($package->name ?? '-'); ?></td>
                                            <td><?php echo e($package->package_calls ?? '-'); ?></td>
                                            <td><?php echo e($package->package_message ?? '-'); ?></td>
                                            <td><?php echo e($package->package_details ?? '-'); ?></td>
                                            <td><?php echo e($package->price ?? '-'); ?></td>
                                            <td>
                                                <label class="switch">
                                                    <input onchange="update_status_packages(this)" type="checkbox"  value="<?php echo e($package->id); ?>" <?php if($package->status == 1) echo "checked";?>>
                                                    <span class="slider"></span>
                                                </label>
                                            </td>
                                            <td>
                                                <a href="<?php echo e(route('packages.edit', encrypt($package->id))); ?>"  class="btn btn-info btn-sm"" >
                                                    <i class="fas fa-edit"></i></a>
                                                <!-- <a href="javascript:void(0)"  class="btn btn-danger btn-sm"
                                                    onclick="ConformAlerts(<?php echo e($package->id); ?>,'<?php echo e(route('packages.destroy')); ?>' )" >
                                                        <i class="fas fa-trash-alt"></i></a> -->
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end">
                                <?php echo e($packages->links( "pagination::bootstrap-4")); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>
<script>
function update_status_packages(el){
    if(el.checked){
        var status = 1;
    }
    else{
        var status = 0;
    }
    $.post('<?php echo e(route('packages.status')); ?>', {_token:'<?php echo e(csrf_token()); ?>', id:el.value, status:status}, function(data){
        if(data == 1){
            toastr.success('Status Changed successfully!');
        }
        else{
            toastr.error('Something Wrong!');
        }
    });
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/laravel/resources/views/packages/index.blade.php ENDPATH**/ ?>