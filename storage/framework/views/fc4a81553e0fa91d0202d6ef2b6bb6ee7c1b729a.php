<?php $__env->startSection('content'); ?>

<div class="main-panel">
    <div class="content-wrapper">
        <form method="POST" action="<?php echo e(route('user.excel.download')); ?>">
            <?php echo csrf_field(); ?>
            <div class="justify-content-end d-flex">
                <div class="form-outline">
                    <input type="date" class="form-control mr-2 mb-2"  name="start_date" <?php if(isset($start_date)): ?> value="<?php echo e($start_date); ?>" <?php endif; ?>>
                </div>
                <div class="form-outline">
                    <input type="date" class="form-control mr-2 mb-2" name="end_date" <?php if(isset($end_date)): ?> value="<?php echo e($end_date); ?>" <?php endif; ?>>
                </div>  
                <button type="submit"  class="btn btn-sm btn-primary mb-2"> Download Excel </button><br>
            </div>
            <div class="justify-content-end d-flex">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
               <p class="text-danger"><?php echo e($error); ?></p>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div> 
        </form>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <h4 class="card-title">User Information</h4>
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
                            <table id="order-ssss" class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Image</th>
                                        <th>User Name</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Phone</th>
                                        <th>Total Left Calls</th>
                                        <th>Total Left Message</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e(($key+1) + ($users->currentPage() - 1)*$users->perPage()); ?></td>
                                            <td> <img src="<?php echo e($user->image ?? asset('user.png')); ?>" /> </td>
                                            <td><?php echo e($user->username ?? '-'); ?></td>
                                            <td><?php echo e($user->name ?? '-'); ?></td>
                                            <td><?php echo e($user->lastname ?? '-'); ?></td>
                                            <td><?php echo e($user->phone ?? '-'); ?></td>
                                            <td> <button class="btn btn-success"><?php echo e($user->total_call ?? '-'); ?></button> </td>
                                            <td> <button class="btn btn-success"> <?php echo e($user->total_message ?? '-'); ?> </button> </td>
                                            <td><?php echo e($user->created_at->toDateString()); ?></td>
                                            <td><?php echo e($user->created_at->format('h:i A')); ?> </td>
                                            <td>
                                                <label class="switch">
                                                    <input onchange="update_status(this)" type="checkbox"  value="<?php echo e($user->id); ?>" <?php if($user->status == 1) echo "checked";?>>
                                                    <span class="slider"></span>
                                                </label>
                                            </td>
                                            <td>
                                                <a href="javascript:void(0)"  class="btn btn-danger btn-sm"
                                                onclick="ConformAlerts(<?php echo e($user->id); ?>,'<?php echo e(route('users.destroy')); ?>' )" >
                                                <i class="fas fa-trash-alt"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end">
                                <?php echo e($users->links( "pagination::bootstrap-4")); ?>

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

    function update_status(el){
        if(el.checked){
            var status = 1;
        }
        else{
            var status = 0;
        }
        $.post('<?php echo e(route('users.status')); ?>', {_token:'<?php echo e(csrf_token()); ?>', id:el.value, status:status}, function(data){
            if(data == 1){
                toastr.success('Status Changed successfully!');
            }
            else{
                toastr.error('Something Wrong!');
            }
        });
    }

    function getExcel(params) {
       var start_date = $("#start_date").val();
       var end_date = $("#end_date").val();

       if(start_date == "" || end_date == ""){
            toastr.error('Please select start date and end date');
       }else{
            $.ajax({  
                type: "POST",  
                url: '<?php echo e(route('user.excel.download')); ?>', 
                data: {
                    _token:'<?php echo e(csrf_token()); ?>',
                    start_date:start_date,
                    end_date:end_date,
                },
                success: function(data) {
                   
                }
            });
       }
    }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/laravel/resources/views/users/index.blade.php ENDPATH**/ ?>