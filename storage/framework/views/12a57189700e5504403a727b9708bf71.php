<!DOCTYPE html>

<html lang="<?php echo e(app()->getLocale()); ?>" dir="<?php echo e(backpack_theme_config('html_direction')); ?>">

<head>
    <?php echo $__env->make(backpack_view('inc.head'), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <style>
        footer {
            width: 100%;
            position: fixed;
            bottom: 0;
            background-color: transparent !important;
            border: none !important;
        }
        .switch-mode {
            position: absolute;
            top: 0;
            right: 0;
            z-index: 999;
        }
    </style>
</head>

<body class="<?php echo e(backpack_theme_config('classes.body')); ?> <?php if(backpack_theme_config('auth_layout') === 'cover'): ?> d-flex flex-column theme-light <?php endif; ?>">

<?php echo $__env->make(backpack_view('layouts.partials.light_dark_mode_logic'), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php if(backpack_theme_config('options.showColorModeSwitcher')): ?>
    <div class="switch-mode p-3">
        <?php echo $__env->renderWhen(backpack_theme_config('options.showColorModeSwitcher'), backpack_view('layouts.partials.switch_theme'), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path'])); ?>
    </div>
<?php endif; ?>

<?php echo $__env->yieldContent('content'); ?>

<?php echo $__env->yieldContent('before_scripts'); ?>
<?php echo $__env->yieldPushContent('before_scripts'); ?>

<?php echo $__env->make(backpack_view('inc.footer'), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php echo $__env->make(backpack_view('inc.scripts'), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make(backpack_view('inc.theme_scripts'), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php echo $__env->yieldContent('after_scripts'); ?>
<?php echo $__env->yieldPushContent('after_scripts'); ?>
</body>
</html><?php /**PATH C:\rstoresV1R\zenith\resources/views/vendor/backpack/theme-tabler/layouts/auth.blade.php ENDPATH**/ ?>