<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Make My Payment</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('backend/css/feather/feather.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('backend/css/themify/themify-icons.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('backend/css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('backend/css/custom.css')); ?>">
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('backend/images/bell.png')); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/6.5.95/css/materialdesignicons.min.css" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <?php echo toastr_css(); ?>
    
</head>
<body>

    <div class="container-scroller">
        <?php echo $__env->make('inc.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="container-fluid page-body-wrapper">
            <?php echo $__env->make('inc.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="main-panel">
                <div class="content-wrapper">
                    <?php echo $__env->yieldContent('content'); ?>
                </div> 
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="<?php echo e(asset('backend/js/vendor.bundle.base.js')); ?>"></script>
    <script src="<?php echo e(asset('backend/js/jquery.dataTables.js')); ?>"></script>
    <script src="<?php echo e(asset('backend/js/data-table.js')); ?>"></script>
    <script src="<?php echo e(asset('backend/js/Chart.min.js')); ?>"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php echo $__env->yieldContent('script'); ?>

    <?php echo jquery(); ?>
    <?php echo toastr_js(); ?>
    <?php echo app('toastr')->render(); ?>
<script>

    $(document).ready(function () {
        $('#order-listing').dataTable({
            "bPaginate": false
        });
    });

    function ConformAlerts(id, url){
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({  
                    type: "POST",  
                    url: url, 
                    data: {
                        _token:'<?php echo e(csrf_token()); ?>',
                        id:id
                    },
                    success: function(data) {
                        if(data.status == true){
                            toastr.success('User Deleted successfully!');
                            location.reload();
                        }else{
                            toastr.error('Something Wrong!');
                        }
                    }
                });
            }
        })
    }
    
</script>

</body>
</html>
<?php /**PATH /var/www/laravel/resources/views/layouts/app.blade.php ENDPATH**/ ?>