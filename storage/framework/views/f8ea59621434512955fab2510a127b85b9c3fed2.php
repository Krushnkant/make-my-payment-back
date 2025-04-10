<?php $__env->startSection('content'); ?>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit Ads</h4>
                        <p class="card-description">
                            Basic Ads
                        </p>
                        <form class="forms-sample" action="<?php echo e(route('ads.update', $ads->id)); ?>" method="post">
                        <input name="_method" type="hidden" value="PATCH">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <label for="">Select Device Type</label>
                                <select class="form-control" name="device_type">
                                    <option value="android" <?php if($ads->device_type == 'android'): ?> selected <?php endif; ?>> Android </option>
                                    <option value="ios" <?php if($ads->device_type == 'ios'): ?> selected <?php endif; ?>> IOS </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Ad id</label>
                                <input type="text" class="form-control" name="ad_id" value="<?php echo e($ads->ad_id); ?>" placeholder="Enter Ad id">
                            </div>
                            <button type="submit" class="btn btn-primary me-2">Submit</button>
                            <button class="btn btn-light">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="https://cdn.ckeditor.com/ckeditor5/26.0.0/classic/ckeditor.js" ></script>
<script src="https://unpkg.com/@ckeditor/ckeditor5-inspector@2.2.1/build/inspector.js" ></script>

<script>
function CustomizationPlugin( editor ) {

}
    ClassicEditor
        .create( document.querySelector( '#editor' ), {
        extraPlugins: [ CustomizationPlugin ]
    } )
        .then( newEditor => {
        window.editor = newEditor;
        CKEditorInspector.attach( newEditor, {
            isCollapsed: true
        } );
    } )
        .catch( error => {
        console.error( error );
    });

    ClassicEditor
        .create( document.querySelector( '#editors' ), {
        extraPlugins: [ CustomizationPlugin ]
    } )
        .then( newEditor => {
        window.editor = newEditor;
        CKEditorInspector.attach( newEditor, {
            isCollapsed: true
        } );
    } )
        .catch( error => {
        console.error( error );
    });

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/laravel/resources/views/ads/edit.blade.php ENDPATH**/ ?>