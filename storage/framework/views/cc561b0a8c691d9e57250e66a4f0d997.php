<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
<?php if(backpack_theme_config('meta_robots_content')): ?>
<meta name="robots" content="<?php echo e(backpack_theme_config('meta_robots_content', 'noindex, nofollow')); ?>">
<?php endif; ?>

<?php echo $__env->renderWhen(view()->exists('vendor.backpack.ui.inc.header_metas'), 'vendor.backpack.ui.inc.header_metas', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path'])); ?>

<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>"/> 
<title><?php echo e(isset($title) ? $title.' :: '.backpack_theme_config('project_name') : backpack_theme_config('project_name')); ?></title>

<?php echo $__env->yieldContent('before_styles'); ?>
<?php echo $__env->yieldPushContent('before_styles'); ?>

<?php echo $__env->make(backpack_view('inc.theme_styles'), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make(backpack_view('inc.styles'), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php echo $__env->yieldContent('after_styles'); ?>
<?php echo $__env->yieldPushContent('after_styles'); ?>
<?php /**PATH C:\rstoresV1R\zenith\resources/views/vendor/backpack/theme-tabler/inc/head.blade.php ENDPATH**/ ?>