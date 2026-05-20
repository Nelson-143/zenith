<?php Basset::basset('https://unpkg.com/jquery@3.6.1/dist/jquery.min.js'); ?>
<?php Basset::basset('https://unpkg.com/@popperjs/core@2.11.6/dist/umd/popper.min.js'); ?>
<?php Basset::basset('https://unpkg.com/noty@3.2.0-beta-deprecated/lib/noty.min.js'); ?>
<?php Basset::basset('https://unpkg.com/sweetalert@2.1.2/dist/sweetalert.min.js'); ?>

<?php if(backpack_theme_config('scripts') && count(backpack_theme_config('scripts'))): ?>
    <?php $__currentLoopData = backpack_theme_config('scripts'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $path): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if(is_array($path)): ?>
            <?php Basset::basset(...$path); ?>
        <?php else: ?>
            <?php Basset::basset($path); ?>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>

<?php if(backpack_theme_config('mix_scripts') && count(backpack_theme_config('mix_scripts'))): ?>
    <?php $__currentLoopData = backpack_theme_config('mix_scripts'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $path => $manifest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <script type="text/javascript" src="<?php echo e(mix($path, $manifest)); ?>"></script>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>

<?php if(backpack_theme_config('vite_scripts') && count(backpack_theme_config('vite_scripts'))): ?>
    <?php echo app('Illuminate\Foundation\Vite')(backpack_theme_config('vite_scripts')); ?>
<?php endif; ?>

<?php echo $__env->make(backpack_view('inc.alerts'), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php if(config('app.debug')): ?>
    <?php echo $__env->make('crud::inc.ajax_error_frame', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>

<?php $__env->startPush('after_scripts'); ?>
    <?php Basset::basset(base_path('vendor/backpack/crud/src/resources/assets/js/common.js')); ?>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\rstoresV1R\zenith\resources\views/vendor/backpack/ui/inc/scripts.blade.php ENDPATH**/ ?>