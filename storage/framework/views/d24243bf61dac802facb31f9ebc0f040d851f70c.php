

            <?php
                $totaluser = App\Models\User::where('view', 1)->count();
                $totalmembership = App\Models\Membership::where('view', 1)->count();
                $total = $totaluser + $totalmembership;
            ?>


<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item <?php echo e(areActiveRoutes(['home'])); ?>">
            <a class="nav-link" href="<?php echo e(route('home')); ?>">
                <i class="mdi mdi-grid-large menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item nav-category">Navigations</li>
        <li class="nav-item <?php echo e(areActiveRoutes(['users.index'])); ?>">
            <a class="nav-link" href="<?php echo e(route('users.index')); ?>">
                <i class="mdi mdi-account menu-icon"></i>
                <span class="menu-title">Users  <?php if($totaluser > 0): ?><span style="margin-left:20px" class="text-danger"> <b> (<?php echo e($totaluser); ?>) </b></span> <?php endif; ?> </span>
            </a>
        </li>
        <li class="nav-item <?php echo e(areActiveRoutes(['packages.index', 'packages.edit'])); ?>">
            <a class="nav-link" href="<?php echo e(route('packages.index')); ?>">
                <i class="mdi mdi-package-variant menu-icon"></i>
                <span class="menu-title">Package</span>
            </a>
        </li>
        <li class="nav-item <?php echo e(areActiveRoutes(['transections.index'])); ?>">
            <a class="nav-link" href="<?php echo e(route('transections.index')); ?>">
                <i class="mdi mdi-layers menu-icon"></i>
                <span class="menu-title">Transection</span>
            </a>
        </li>
        <li class="nav-item <?php echo e(areActiveRoutes(['notifications.index'])); ?>">
            <a class="nav-link" href="<?php echo e(route('notifications.index')); ?>">
                <i class="mdi mdi-bell menu-icon"></i>
                <span class="menu-title">Notification <?php if($totalmembership > 0): ?><span style="margin-left:20px" class="text-danger"> <b> (<?php echo e($totalmembership); ?>) </b></span> <?php endif; ?> </span>
            </a>
        </li>
        <li class="nav-item <?php echo e(areActiveRoutes(['send-notifications.index', 'send-notifications.create'])); ?>">
            <a class="nav-link" href="<?php echo e(route('send-notifications.index')); ?>">
                <i class="mdi mdi-notification-clear-all  menu-icon"></i>
                <span class="menu-title">Send Notification </span>
            </a>
        </li>
        <li class="nav-item <?php echo e(areActiveRoutes(['abusives.index', 'abusives.create', 'abusives.edit'])); ?>">
            <a class="nav-link" href="<?php echo e(route('abusives.index')); ?>">
                <i class="mdi mdi-layers menu-icon"></i>
                <span class="menu-title">Abusive</span>
            </a>
        </li>
        <li class="nav-item <?php echo e(areActiveRoutes(['user.report'])); ?>">
            <a class="nav-link " data-bs-toggle="collapse" href="#ui-advanced" aria-expanded="false" aria-controls="ui-advanced">
              <i class="menu-icon mdi mdi-arrow-down-drop-circle-outline"></i>
              <span class="menu-title">Reports</span>
                <i class="mdi mdi-arrow-down-box"></i>
            </a>
            <div class="collapse" id="ui-advanced">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link <?php echo e(areActiveRoutes(['user.report'])); ?>" href="<?php echo e(route('user.report')); ?>">User Report</a></li>
                <li class="nav-item"> <a class="nav-link <?php echo e(areActiveRoutes(['transection.report'])); ?>" href="<?php echo e(route('transection.report')); ?>">Transaction Report</a></li>
                <li class="nav-item"> <a class="nav-link <?php echo e(areActiveRoutes(['money-got-report'])); ?>" href="<?php echo e(route('money-got-report')); ?>">Money Got & Give Report</a></li>
                <li class="nav-item"> <a class="nav-link <?php echo e(areActiveRoutes(['call-sms-report'])); ?>" href="<?php echo e(route('call-sms-report')); ?>">Reminder Report</a></li>
                <li class="nav-item"> <a class="nav-link <?php echo e(areActiveRoutes(['user-activity-report'])); ?>" href="<?php echo e(route('user-activity-report')); ?>">Daily Active Users Report</a></li>
                <li class="nav-item"> <a class="nav-link <?php echo e(areActiveRoutes(['monthly-user-activity-report'])); ?>" href="<?php echo e(route('monthly-user-activity-report')); ?>">Monthly Active Users Report</a></li>
                <li class="nav-item"> <a class="nav-link <?php echo e(areActiveRoutes(['device-user-report'])); ?>" href="<?php echo e(route('device-user-report')); ?>">iOS & Android Users Report</a></li>
              </ul>
            </div>
          </li>
        <li class="nav-item <?php echo e(areActiveRoutes(['general_settings.index'])); ?>">
            <a class="nav-link" href="<?php echo e(route('general_settings.index')); ?>">
                <i class="mdi mdi-package-variant menu-icon"></i>
                <span class="menu-title">General Settings</span>
            </a>
        </li>
        <li class="nav-item <?php echo e(areActiveRoutes(['faqs.index', 'faqs.create', 'faqs.edit'])); ?>">
            <a class="nav-link" href="<?php echo e(route('faqs.index')); ?>">
                <i class="mdi mdi-layers menu-icon"></i>
                <span class="menu-title">Faqs</span>
            </a>
        </li>
        <li class="nav-item <?php echo e(areActiveRoutes(['ads.index', 'ads.create', 'ads.edit'])); ?>">
            <a class="nav-link" href="<?php echo e(route('ads.index')); ?>">
                <i class="mdi mdi-layers menu-icon"></i>
                <span class="menu-title">Ads</span>
            </a>
        </li>
        <li class="nav-item <?php echo e(areActiveRoutes(['version.showData'])); ?>">
            <a class="nav-link" href="<?php echo e(route('version.showData')); ?>">
                <i class="mdi mdi-layers menu-icon"></i>
                <span class="menu-title">version</span>
            </a>
        </li>
    </ul>
</nav>
<?php /**PATH /var/www/laravel/resources/views/inc/sidebar.blade.php ENDPATH**/ ?>