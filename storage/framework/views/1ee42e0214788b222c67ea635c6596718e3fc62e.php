<?php $__env->startSection('content'); ?>
<style>
  .device_info_card .btn {
    color: #fff;
}
.device_info_card .table .delete_btn {
    display: inline-block;
    color: #484848;
    font-size: 21px;
}
#addModal .modal_body {
    padding: 5px 20px;
}
  </style>


<div class="row">
    <div class="col-sm-12">
        <div class="home-tab">
            <div class="d-sm-flex align-items-center justify-content-between border-bottom">
            </div>
                    </div>
                    <div class="main-panel">
                      <?php if($message = Session::get('error')): ?>
                      <div class="alert alert-danger alert-dismissible fade show" role="alert">
                          <strong><?php echo e($message); ?>

                              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>
                      <?php endif; ?>
                      <?php if($message = Session::get('updateStatus')): ?>
                      <div class="alert alert-success alert-dismissible fade show" role="alert">
                          <strong><?php echo e($message); ?>

                              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>
                      <?php endif; ?>
                        <div class="content-wrapper">
                            <div class="card device_info_card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <h4 class="card-title">Device Information</h4>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="d-flex justify-content-end" style="min-width: 200px;">
                                                <form class="" id="sort_categories" action="" method="GET">
                                                    <div class="form-outline">
                                                      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                                                        Add Device
                                                      </button>
                                                        
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="modal" id="addModal">
                                      <div class="modal-dialog">
                                        <div class="modal-content">
                                    
                                          <!-- Modal Header -->
                                          <div class="modal-header">
                                            <h4 class="modal-title">Add Device Info</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                          </div>
                                          <div class="modal_body">
                                          <!-- Modal body -->
                                          <form action="<?php echo e(route('version.storeData')); ?>" method="post">
                                            <?php echo csrf_field(); ?>
                                            <div class="mb-3 mt-3">
                                              <label for="" class="form-label">Device Type:</label>
                                              <input type="text" class="form-control" id="" placeholder="Device Type" name="device_type">
                                            </div>
                                            <div class="mb-3">
                                              <label for="" class="form-label">Version No:</label>
                                              <input type="text" class="form-control" id="" placeholder="Enter version no" name="version_no">
                                            </div>
                                            <div class="mb-3 mt-3">
                                              <label class="form-check-label"> Status: </label>
                                            </div>
                                            <div class="mb-3 mt-3">
                                              <label class="switch">
                                                <input onchange="update_on_off(this)" type="checkbox" name="status" id="switch_on" value="">
                                                <span class="slider"></span>
                                                </label> 
                                            </div>
                                                
                                            
                                            
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </form>
                                      </div>
                                    
                                          <!-- Modal footer -->
                                          
                                    
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
                                                            <th>Device Name</th>
                                                            <th>Version Number</th>
                                                            
                                                            
                                                            <th>Action</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                      <?php $__currentLoopData = $versionData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr>
                                                            <td><?php echo e($item->id); ?></td>
                                                            <td><?php echo e($item->device_type); ?></td>
                                                            <td><?php echo e($item->version_no); ?></td>
                                                            
                                                            
                                                            <td>
                                                              <span type="button" class="mdi mdi-trash-can-outline delete_btn" data-bs-toggle="modal" data-bs-target="#deleteModel">
                                                              </span>
                                                                
                                    <div class="modal" id="deleteModel">
                                      <div class="modal-dialog">
                                        <div class="modal-content">
                                    
                                          <!-- Modal Header -->
                                          <div class="modal-header">
                                            <h4 class="modal-title">Delete Device Info</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                          </div>
                                          <center>
                                          <div class="modal_body">
                                            <br/>
                                            <h6 >Do you want to delete this info?</h6>
                                          <!-- Modal body -->
                                          <br/>
                                          
                                          <button type="button" data-bs-dismiss="modal" class="btn btn-primary" style=" width: 120px">No</button>
                                          <button type="delete" class="btn btn-danger" style="width:120px; margin-left:30px;"><a href="<?php echo e(route('version.delete',['id' => $item->id])); ?>" style="text-decoration: none; color: inherit;">Yes</a></button>
                                         
                                          </div>
                                          <br/>
                                        </center>
                                    
                                          <!-- Modal footer -->
                                          
                                    
                                        </div>
                                      </div>
                                    </div>
                                                                    
                                                                 
                                                            </td>

                                                            <td>
                                                              <label class="switch">
                                                              <input onchange="update_status_on_off(this)" type="checkbox"  value="<?php echo e($item->id); ?>" <?php if($item->status == 1) echo "checked";?>>
                                                              
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php $__env->stopSection(); ?>
<script>
  function update_on_off(el){
    if(el.checked){
       document.getElementById("switch_on").value = "1"; 
        // alert('1')
    }
    else{
      document.getElementById("switch_on").value = "0"; 
      // alert('0')
        // var status = 0;
    }
  }
</script>
<script>
  function update_status_on_off(el){
    if(el.checked){
        var status = 1;
    }
    else{
        var status = 0;
    }
    $.post('<?php echo e(route('version.status')); ?>', {_token:'<?php echo e(csrf_token()); ?>', id:el.value, status:status}, function(data){
        if(data == 1){
            toastr.success('Status Changed successfully!');
        }
        else{
            toastr.error('Something Wrong!');
        }
    });
}
</script>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/laravel/resources/views/version/showdata.blade.php ENDPATH**/ ?>