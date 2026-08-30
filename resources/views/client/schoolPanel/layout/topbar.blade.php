<nav class="bg-white w-full flex relative justify-between items-center mx-auto px-8 h-20 border-b border-gray-300"
style="position: absolute; z-index:2000;">
<div class="inline-flex">
    <a class="_o6689fn" href="/">
        <div class="hidden md:block">
            <img class="w-auto" src="{{ asset('landing/img/Group (2).png') }}" alt=""
                style="height: 50px">
        </div>
        <div class="block md:hidden">
            <img class="w-auto" src="{{ asset('landing/img/Group (2).png') }}" alt=""
                style="height: 20px">
        </div>
    </a>
</div>

<div class="hidden sm:block flex-shrink flex-grow-0 justify-start px-2 ml-auto">
    <div class="inline-block">
        <div class="inline-flex items-center max-w-full">
            <button
                class="flex items-center flex-grow-0 flex-shrink pl-2 relative w-60 border rounded-full px-1 py-1"
                type="button">
                <div class="block flex-grow flex-shrink overflow-hidden">Start your search</div>
                <div class="flex items-center justify-center relative h-8 w-8 rounded-full">
                    <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
                        role="presentation" focusable="false"
                        style="display: block; fill: none; height: 12px; width: 12px; stroke: currentcolor; stroke-width: 5.33333; overflow: visible;">
                        <g fill="none">
                            <path d="m13 24c6.075 0 11-4.925 11-11s-4.925-11-11-11-11 4.925-11 11 4.925 11 11 11zm8-3 9 9">
                            </path>
                        </g>
                    </svg>
                </div>
            </button>
        </div>
    </div>
</div>

<div class="flex-initial">
    <div class="flex justify-end items-center relative">
        <div class="block">
        
        <a href="{{route('school.logout')}}"  type="submit"
                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                            🚪 Logout
                        </a>
           
        </div>
    </div>
</div>
</nav>