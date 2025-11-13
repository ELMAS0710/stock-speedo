<?php $__env->startSection('content'); ?>
<style>
    .login-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 50%, #1e3a5f 100%);
    }
    
    .login-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        overflow: hidden;
    }
    
    .login-header {
        background: white;
        color: #1e3a5f;
        padding: 2rem;
        text-align: center;
        border: none;
        border-bottom: 3px solid #c9a961;
    }
    
    .login-header img {
        max-width: 220px;
        height: auto;
        margin-bottom: 1rem;
    }
    
    .login-header h4 {
        margin: 0;
        font-weight: 600;
        font-size: 1.5rem;
        color: #1e3a5f;
    }
    
    .login-body {
        padding: 3rem 2.5rem;
        background: white;
    }
    
    .logo-container {
        text-align: center;
        margin-bottom: 2rem;
        animation: fadeInDown 0.8s ease-in-out;
        display: none; /* Caché car le logo est maintenant dans l'en-tête */
    }
    
    .logo-container img {
        max-width: 280px;
        height: auto;
        filter: drop-shadow(0 5px 15px rgba(0, 0, 0, 0.2));
    }
    
    .form-control {
        border-radius: 10px;
        padding: 0.75rem 1rem;
        border: 2px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: #c9a961;
        box-shadow: 0 0 0 0.2rem rgba(201, 169, 97, 0.25);
    }
    
    .btn-login {
        background: linear-gradient(135deg, #c9a961 0%, #d4b76a 100%);
        border: none;
        border-radius: 10px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        color: #1e3a5f;
        transition: all 0.3s ease;
        width: 100%;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .btn-login:hover {
        background: linear-gradient(135deg, #d4b76a 0%, #c9a961 100%);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(201, 169, 97, 0.4);
        color: #1e3a5f;
    }
    
    .form-label {
        color: #1e3a5f;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .form-check-label {
        color: #4a5568;
    }
    
    .forgot-password {
        color: #c9a961;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .forgot-password:hover {
        color: #1e3a5f;
        text-decoration: underline;
    }
    
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .welcome-text {
        text-align: center;
        color: white;
        margin-bottom: 1rem;
        font-size: 1.1rem;
    }
</style>

<div class="login-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="logo-container">
                    <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Groupe Speedo">
                </div>
                
                <div class="card login-card">
                    <div class="login-header">
                        <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Groupe Speedo">
                        <h4>Connexion</h4>
                        <p class="mb-0 mt-2" style="font-size: 0.95rem; color: #4a5568;">Gestion de Stock</p>
                    </div>

                    <div class="login-body">
                        <form method="POST" action="<?php echo e(route('login')); ?>">
                            <?php echo csrf_field(); ?>

                            <div class="mb-4">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope-fill me-2"></i>Adresse Email
                                </label>
                                <input id="email" 
                                       type="email" 
                                       class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       name="email" 
                                       value="<?php echo e(old('email')); ?>" 
                                       required 
                                       autocomplete="email" 
                                       autofocus
                                       placeholder="votre@email.com">

                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock-fill me-2"></i>Mot de passe
                                </label>
                                <input id="password" 
                                       type="password" 
                                       class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       name="password" 
                                       required 
                                       autocomplete="current-password"
                                       placeholder="••••••••">

                                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="remember" 
                                           id="remember" 
                                           <?php echo e(old('remember') ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="remember">
                                        Se souvenir de moi
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-login">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                                </button>
                            </div>

                            <?php if(Route::has('password.request')): ?>
                                <div class="text-center">
                                    <a class="forgot-password" href="<?php echo e(route('password.request')); ?>">
                                        Mot de passe oublié ?
                                    </a>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <small style="color: rgba(255, 255, 255, 0.8);">
                        © <?php echo e(date('Y')); ?> Groupe Speedo - Europe Affaires
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\GESTION STOCK\gestion_stock_speedo\resources\views/auth/login.blade.php ENDPATH**/ ?>