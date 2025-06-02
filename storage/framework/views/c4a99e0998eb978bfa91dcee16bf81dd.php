<?php $__env->startSection('content'); ?>
    <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-xl font-bold mb-4">Edit Profile</h1>

        <?php if(session('success')): ?>
            <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('profile.update')); ?>">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label>Business Name</label>
                    <input name="business_name" value="<?php echo e(old('business_name', $profile->business_name ?? '')); ?>" class="w-full border p-2" />
                </div>
                <div>
                    <label>Contact Name</label>
                    <input name="contact_name" value="<?php echo e(old('contact_name', $profile->contact_name ?? '')); ?>" class="w-full border p-2" />
                </div>
                <div>
                    <label>Phone Number</label>
                    <input name="phone_number" value="<?php echo e(old('phone_number', $profile->phone_number ?? '')); ?>" class="w-full border p-2" />
                </div>
                <div>
                    <label>Address Line 1</label>
                    <input name="address_line1" value="<?php echo e(old('address_line1', $profile->address_line1 ?? '')); ?>" class="w-full border p-2" />
                </div>
                <div>
                    <label>Address Line 2</label>
                    <input name="address_line2" value="<?php echo e(old('address_line2', $profile->address_line2 ?? '')); ?>" class="w-full border p-2" />
                </div>
                <div>
                    <label>City</label>
                    <input name="city" value="<?php echo e(old('city', $profile->city ?? '')); ?>" class="w-full border p-2" />
                </div>
                <div>
                    <label>State</label>
                    <input name="state" value="<?php echo e(old('state', $profile->state ?? '')); ?>" class="w-full border p-2" />
                </div>
                <div>
                    <label>ZIP Code</label>
                    <input name="zip_code" value="<?php echo e(old('zip_code', $profile->zip_code ?? '')); ?>" class="w-full border p-2" />
                </div>
                <div>
                    <label>Country</label>
                    <input name="country" value="<?php echo e(old('country', $profile->country ?? '')); ?>" class="w-full border p-2" />
                </div>
                <div>
                    <label>Business License Number</label>
                    <input name="business_license_number" value="<?php echo e(old('business_license_number', $profile->business_license_number ?? '')); ?>" class="w-full border p-2" />
                </div>
                <div class="col-span-2">
                    <label>Incorporation Details</label>
                    <textarea name="incorporation_details" rows="4" class="w-full border p-2"><?php echo e(old('incorporation_details', $profile->incorporation_details ?? '')); ?></textarea>
                </div>
            </div>

            <div class="mt-4">
                <button class="bg-blue-500 text-white px-4 py-2 rounded">Save Profile</button>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\BBvb-main\resources\views/profile/edit.blade.php ENDPATH**/ ?>