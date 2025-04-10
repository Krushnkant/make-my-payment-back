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
                                <div > <a href="<?php echo e(route('send-notifications.create')); ?>" class="btn btn-primary mb-3"> Add Notification </a></div>
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
                                        <th>User</th>
                                        <th>Title</th>
                                        <th>Message</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e(($key+1)); ?></td>
                                            <td><?php echo e($item->user->name ?? '-'); ?></td>
                                            <td><?php echo e($item->title ?? '-'); ?></td>
                                            <td><?php echo e($item->message ?? '-'); ?></td>
                                            
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


<?php $__env->startSection('script'); ?>
<script>
function update_status_membership(el){
    if(el.checked){
        var status = 1;
    }
    else{
        var status = 0;
    }
    $.post('<?php echo e(route('membership.status')); ?>', {_token:'<?php echo e(csrf_token()); ?>', id:el.value, status:status}, function(data){
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/laravel/resources/views/send_notifications/index.blade.php ENDPATH**/ ?>