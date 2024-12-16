<div class="p">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

            <div class="p-6">
                <p class="text-gray-900 dark:text-gray-100 text-right">Credit: <?php echo e($credit); ?></p>
                <p class="text-gray-900 dark:text-gray-100 text-right">TotalCredit: <?php echo e($totalCredit); ?></p>
            </div>

            <table class="min-w-full divide-y divide-gray-200">
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $reels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $reel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <td class="px-6 py-4 whitespace-nowrap animate-reel text-center" data-original-value="<?php echo e($reel); ?>">
                            <!--[if BLOCK]><![endif]--><?php if($firstLoad): ?> 
                                <?php echo e($reel); ?> 
                            <?php else: ?> 
                                X
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </td>                        
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </tr>
                </tbody>
            </table>

            <div class="flex">
                <button wire:click="spin" 
                        class="mr-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Spin
                </button>
                
            </div>

            <div class="flex text-center">
                <button id="cashOutButton" 
                        wire:click="cashOut" 
                        wire:loading.attr="disabled" <?php echo e($cashOutDisabled ? 'disabled' : ''); ?> 
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                        style="left: <?php echo e($cashOutCoords['y']); ?>px; top: <?php echo e($cashOutCoords['x']); ?>px">
                    Cashout
                </button>
            </div>
        </div>
    </div>
</div>

    <?php
        $__scriptKey = '438263650-0';
        ob_start();
    ?>
<script>

    /***
     * REEL SPIN ANIMATION
     */

    $wire.on('animateReels', () => {

        let elements = document.querySelectorAll("td.animate-reel");
        
        // Start the animation
        revealOneByOne(elements);
    });

    function revealOneByOne(elements, index = 0, delay = <?php echo e($spinDelay); ?>) {

        if (index >= elements.length) {
            return;
        }

        // Schedule the revealing of the original value after the current delay
        setTimeout(() => {

            elements[index].textContent = elements[index].dataset.originalValue;

            // Increase delay for the next iteration
            delay = delay + delay * index;

            // Move to the next element recursively
            revealOneByOne(elements, index + 1, delay);
        }, delay);
    };
</script>
    <?php
        $__output = ob_get_clean();

        \Livewire\store($this)->push('scripts', $__output, $__scriptKey)
    ?><?php /**PATH C:\Users\Clint\Documents\Repos\the-house-always-wins-rhsgtf\resources\views/livewire/slot-machine.blade.php ENDPATH**/ ?>