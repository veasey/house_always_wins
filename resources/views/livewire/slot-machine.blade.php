<div class="p">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

            <div class="p-6">
                <p class="text-gray-900 dark:text-gray-100 text-right">Credit: {{ $credit }}</p>
                <p class="text-gray-900 dark:text-gray-100 text-right">TotalCredit: {{ $totalCredit }}</p>
            </div>

            <table class="min-w-full divide-y divide-gray-200">
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                    @foreach ($reels as $index => $reel)
                        <td class="px-6 py-4 whitespace-nowrap animate-reel text-center" data-original-value="{{ $reel }}">
                            @if ($firstLoad) 
                                {{ $reel }} 
                            @else 
                                X
                            @endif
                        </td>                        
                    @endforeach
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
                        wire:loading.attr="disabled" {{ $cashOutDisabled ? 'disabled' : '' }} 
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                        style="left: {{ $cashOutCoords['y'] }}px; top: {{ $cashOutCoords['x'] }}px">
                    Cashout
                </button>
            </div>
        </div>
    </div>
</div>

@script
<script>

    /***
     * REEL SPIN ANIMATION
     */

    $wire.on('animateReels', () => {

        let elements = document.querySelectorAll("td.animate-reel");
        
        // Start the animation
        revealOneByOne(elements);
    });

    function revealOneByOne(elements, index = 0) {

        if (index >= elements.length) {
            return;
        }

        // Schedule the revealing of the original value after the current delay
        setTimeout(() => {

            elements[index].textContent = elements[index].dataset.originalValue;

            // Increase delay for the next iteration
            //delay = delay + delay * index;

            // Move to the next element recursively
            revealOneByOne(elements, index + delay);
        }, delay);
    };
</script>
@endscript