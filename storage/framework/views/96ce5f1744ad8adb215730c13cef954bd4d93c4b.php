<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-sm-12">
        <div class="home-tab">
            <div class="d-sm-flex align-items-center justify-content-between border-bottom">
            </div>
            <div class="tab-content tab-content-basic">
                <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                    <div class="row justify-content-center">
                        <div class="col-md-3 grid-margin">
							<div class="card d-flex align-items-center">
								<div class="card-body">
									<div class="d-flex flex-row align-items-center">
										<i class="ti-user text-facebook icon-md"></i>
										<div class="ms-3">
											<h6 class="text-facebook">Total Users</h6>
											<p class="mt-2 text-muted text-center card-text"><b><?php echo e(App\Models\User::where('user_type', 'customer')->count()); ?></b></p>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-3 grid-margin">
							<div class="card d-flex align-items-center">
								<div class="card-body">
									<div class="d-flex flex-row align-items-center">
										<i class="ti-package text-facebook icon-md"></i>
										<div class="ms-3">
											<h6 class="text-facebook">Total Packges</h6>
											<p class="mt-2 text-muted  text-center card-text"><b><?php echo e(App\Models\Package::count()); ?></b></p>
										</div>
									</div>
								</div>
							</div>
						</div>
                        <div class="col-md-3 grid-margin">
							<div class="card d-flex align-items-center">
								<div class="card-body">
									<div class="d-flex flex-row align-items-center">
										<i class="ti-receipt text-facebook icon-md"></i>
										<div class="ms-3">
											<h6 class="text-facebook">Total Revanue</h6>
											<p class="mt-2 text-muted  text-center card-text"><?php echo e(FinalPrice(App\Models\Transection::where('payment_status', 'paid')->sum('transection_amount'))); ?></p>
										</div>
									</div>
								</div>
							</div>
						</div>
                    </div>
                    <div class="main-panel">
                        <div class="content-wrapper">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <h4 class="card-title">10 Latest User</h4>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="d-flex justify-content-end" style="min-width: 200px;">
                                                <form class="" id="sort_categories" action="" method="GET">
                                                    <div class="form-outline">
                                                        <input type="text" class="form-control" id="searchuser" name="searchuser"<?php if(isset($sort_search_user)): ?> value="<?php echo e($sort_search_user); ?>" <?php endif; ?> placeholder=" Type name & Enter">
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
                                                            <th>Image</th>
                                                            <th>First Name</th>
                                                            <th>Last Name</th>
                                                            <th>Phone</th>
                                                            <th>Total Left Calls</th>
                                                            <th>Total Left Message</th>
                                                            <th>Date</th>
                                                            <th>Time</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <tr>
                                                                <td><?php echo e(($key+1)); ?></td>
                                                                <td><img src="<?php echo e($user->image ?? asset('user.png')); ?>" /></td>
                                                                <td><?php echo e($user->name ?? '-'); ?></td>
                                                                <td><?php echo e($user->lastname ?? '-'); ?></td>
                                                                <td><?php echo e($user->phone ?? '-'); ?></td>
                                                                <td><button class="btn btn-success text-white"><?php echo e($user->total_call ?? '-'); ?></button></td>
                                                                <td><button class="btn btn-success text-white"> <?php echo e($user->total_message ?? '-'); ?></button></td>
                                                                <td><?php echo e($user->created_at->toDateString()); ?></td>
                                                                <td><?php echo e($user->created_at->format('h:i A')); ?> </td>
                                                                <td><label class="switch">
                                                                    <input onchange="update_status(this)" type="checkbox"  value="<?php echo e($user->id); ?>" <?php if($user->status == 1) echo "checked";?>>
                                                                    <span class="slider"></span>
                                                                    </label> 
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <h4 class="card-title"> 10 Latest Transection </h4>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="d-flex justify-content-end" style="min-width: 200px;">
                                                <form class="" id="sort_categories" action="" method="GET">
                                                    <div class="form-outline">
                                                        <input type="text" class="form-control" id="searchmembership" name="searchmembership"<?php if(isset($sort_search_membership)): ?> value="<?php echo e($sort_search_membership); ?>" <?php endif; ?> placeholder=" Type name & Enter">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table id="orderlisting" class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Name</th>
                                                            <th>Package Name</th>
                                                            <th>Total Calls</th>
                                                            <th>Total Message</th>
                                                            <th>Transection Amount</th>
                                                            <th>Payment Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $__currentLoopData = $transections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$transection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <tr>
                                                                <td><?php echo e($key+1); ?></td>
                                                                <td><?php echo e($transection->user->name ?? '-'); ?></td>
                                                                <td><?php echo e($transection->package_name ?? '-'); ?></td>
                                                                <td><?php echo e($transection->package_calls ?? '-'); ?></td>
                                                                <td><?php echo e($transection->package_message ?? '-'); ?></td>
                                                                <td><?php echo e(FinalPrice($transection->transection_amount)); ?></td>
                                                                <?php if($transection->payment_status == 'paid'): ?>
                                                                    <td> <button class="btn btn-success text-white"><?php echo e($transection->payment_status ?? '-'); ?></button></td>
                                                                <?php else: ?> 
                                                                    <td> <button class="btn btn-danger text-white"><?php echo e($transection->payment_status ?? '-'); ?></button></td>
                                                                <?php endif; ?>
                                                            </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/laravel/resources/views/dashboard.blade.php ENDPATH**/ ?>