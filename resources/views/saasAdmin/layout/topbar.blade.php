    <nav class="bg-white w-full flex justify-between items-center mx-auto px-8 h-20 border-b border-gray-300 fixed top-0 left-0 right-0 z-30">
        <div class="inline-flex">
            <a href="/">
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
                    <!-- AlpineJS Dropdown Start -->
                    <div class="inline relative" x-data="{ open: false }">
                        <button @click="open = !open" type="button"
                            class="inline-flex items-center relative px-2 border rounded-full hover:shadow-lg">
                            <div class="pl-1">
                                <!-- Menu Icon -->
                                <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
                                    role="presentation" focusable="false"
                                    style="display: block; fill: none; height: 16px; width: 16px; stroke: currentcolor; stroke-width: 3; overflow: visible;">
                                    <g fill="none" fill-rule="nonzero">
                                        <path d="m2 16h28"></path>
                                        <path d="m2 24h28"></path>
                                        <path d="m2 8h28"></path>
                                    </g>
                                </svg>
                            </div>

                            <div class="block flex-grow-0 flex-shrink-0 h-10 w-12 pl-5">
                                <!-- Profile Icon -->
                                <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
                                    role="presentation" focusable="false"
                                    style="display: block; height: 100%; width: 100%; fill: currentcolor;">
                                    <path
                                        d="m16 .7c-8.437 0-15.3 6.863-15.3 15.3s6.863 15.3 15.3 15.3 15.3-6.863 15.3-15.3-6.863-15.3-15.3-15.3zm0 28c-4.021 0-7.605-1.884-9.933-4.81a12.425 12.425 0 0 1 6.451-4.4 6.507 6.507 0 0 1 -3.018-5.49c0-3.584 2.916-6.5 6.5-6.5s6.5 2.916 6.5 6.5a6.513 6.513 0 0 1 -3.019 5.491 12.42 12.42 0 0 1 6.452 4.4c-2.328 2.925-5.912 4.809-9.933 4.809z">
                                    </path>
                                </svg>
                            </div>
                        </button>
                        <!-- Dropdown -->
                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg z-50">
                           
                            <form method="POST" action="{{ route('saasAdmin.logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    🚪 Logout
                                </button>
                            </form>
                        </div>
                    </div>
                    <!-- AlpineJS Dropdown End -->
                </div>
            </div>
        </div>
    </nav>