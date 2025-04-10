<?php $__env->startSection('content'); ?>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <h4 class="card-title">Money Got & Give Report</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 col-sm-12 grid-margin">
                        <div class="card d-flex align-items-center">
                            <div class="card-body">
                                <div class="d-flex flex-row align-items-center">
                                    <div class="">
                                        <h6 class="text-facebook">Total Users</h6>
                                        <p class="mt-2 text-muted text-center card-text"><b> <?php echo e($totalUsers); ?></b></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12 grid-margin">
                        <div class="card d-flex align-items-center">
                            <div class="card-body">
                                <div class="d-flex flex-row align-items-center">
                                    <div class="">
                                        <h6 class="text-facebook">Total Give Money</h6>
                                        <p class="mt-2 text-muted text-center card-text"><b><?php echo e(FinalPrice($totalGiveAmount)); ?></b></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12 grid-margin">
                        <div class="card d-flex align-items-center">
                            <div class="card-body">
                                <div class="d-flex flex-row align-items-center">
                                    <div class="">
                                        <h6 class="text-facebook">Total Got Money</h6>
                                        <p class="mt-2 text-muted text-center card-text"><b><?php echo e(FinalPrice($totalGotAmount)); ?></b></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-12 grid-margin">
                        <div class="card d-flex align-items-center">
                            <div class="card-body">
                                <div class="d-flex flex-row align-items-center">
                                    <div class="">
                                        <h6 class="text-facebook">Percentage</h6>
                                        <p class="mt-2 text-muted text-center card-text"><b><?php echo e(number_format($percentage, 2)); ?> %</b></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="<?php echo e(route('money-got-report')); ?>" method="GET">
                    <div class="row g-3 align-items-end">
                        <!-- Date Range Filter -->
                        <div class="col-md-3">
                            <label for="start_date" class="form-label">Start Date:</label>
                            <input type="date" name="start_date" class="form-control" value="<?php echo e(request()->start_date ?? \Carbon\Carbon::today()->format('Y-m-d')); ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label">End Date:</label>
                            <input type="date" name="end_date" class="form-control" value="<?php echo e(request()->end_date ?? \Carbon\Carbon::today()->format('Y-m-d')); ?>">
                        </div>

                        <!-- User Filter -->
                        <div class="col-md-3">
                            <label for="user_id" class="form-label">User:</label>
                            <select name="user_id" class="form-select">
                                <option value="">All Users</option>
                                <?php $__currentLoopData = App\Models\User::where('user_type', 'customer')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($item->id); ?>" <?php echo e(request()->user_id == $item->id ? 'selected' : ''); ?>>
                                        <?php echo e($item->phone); ?> - (<?php echo e($item->name ?? '-'); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <!-- Transaction Type Filter (GOT or GIVE) -->
                        <div class="col-md-3">
                            <label for="type" class="form-label">Type:</label>
                            <select name="type" class="form-select">
                                <option value="GOT" <?php echo e(request()->type == 'GOT' ? 'selected' : ''); ?>>GOT</option>
                                <option value="GIVE" <?php echo e(request()->type == 'GIVE' ? 'selected' : ''); ?>>GIVE</option>
                            </select>
                        </div>

                        <!-- Submit and Export Buttons -->
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                            <a href="<?php echo e(route('money-got-export', request()->query())); ?>" class="btn btn-success w-100">
                                Export
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Transactions Table -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="money-got-list" class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User</th>
                                        <th>Business Name</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($key + 1); ?></td>
                                            <td><?php echo e($transaction->user->name ?? '-'); ?></td>
                                            <td><?php echo e($transaction->business->bus_name ?? '-'); ?></td>
                                            <td><?php echo e($transaction->amount); ?></td>
                                            <td><?php echo e(\Carbon\Carbon::parse($transaction->created_at)->format('d-m-Y')); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end">
                                <?php echo e($transactions->links("pagination::bootstrap-4")); ?>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/laravel/resources/views/reports/money_got_report.blade.php ENDPATH**/ ?>