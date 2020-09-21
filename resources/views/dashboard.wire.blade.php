<div class="flex flex-col justify-between container items-center justify-center h-screen mx-auto px-8 py-8 gap-8 space-y-8">
    <div class="max-w-screen-xl mx-auto overflow-hidden space-y-4">
        <h2 class="text-4xl leading-9 font-handwritten font-extrabold text-twitter-500 tracking-tight sm:text-5xl text-center mt-8 md:mt-16">
            Tweet Markdown
        </h2>
        <p class="text-center font-handwritten text-gray-600 text-3xl">Effortlessly turn a Tweet into beautiful markdown</p>
    </div>

    <div class="w-full">
        <div class="bg-white flex flex-col flex-1 rounded-lg shadow-lg p-8 space-y-8 w-full">
            <div class="flex space-x-4">
                <div class="space-y-2 flex-1">
                    <div>
                        <div class="relative rounded-md shadow-sm">
                            <input
                                class="
                                    form-input block w-full pr-10 sm:leading-5
                                    @error('tweetUrl')
                                        border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:shadow-outline-red 
                                    @elseif (!empty($tweetUrl))
                                        border-green-300 text-green-900 placeholder-green-300 focus:border-green-300 focus:shadow-outline-green
                                    @enderror
                                "
                                placeholder="Enter Tweet URL..."
                                aria-invalid="true"
                                aria-describedby="tweet-url-error"
                                wire:model="tweetUrl"
                            >

                            @error('tweetUrl')
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @elseif (!empty($tweetUrl))
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            @enderror
                        </div>
                        @error('tweetUrl')
                            <p class="mt-2 text-sm text-red-600" id="tweet-url-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                @if (empty($tweetUrl))
                    <button class="cursor-pointer select-none text-center align-middle whitespace-no-wrap inline-block border-transparent border-solid border px-4 py-2 leading-normal rounded overflow-visible bg-twitter-500 hover:bg-twitter-400 focus:shadow-outline focus:outline-none text-white transition duration-150 ease-in-out text-xs uppercase flex items-center font-semibold tracking-wide" wire:click="setRandomTweetUrl">
                        <svg class="w-5 h-5 inline mr-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"/></svg>
                        Random Tweet
                    </button>
                @elseif (!$errors->has('tweetUrl'))
                    <button class="cursor-pointer select-none text-center align-middle whitespace-no-wrap inline-block border-transparent border-solid border px-4 py-2 leading-normal rounded overflow-visible bg-gray-200 hover:bg-gray-100 focus:shadow-outline focus:outline-none text-gray-900 transition duration-150 ease-in-out text-xs uppercase flex items-center font-semibold tracking-wide" onclick="document.getElementById('markdown').select(); document.getElementById('markdown').setSelectionRange(0, 99999); document.execCommand('copy');">
                        <svg class="w-5 h-5 inline mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                        Copy to clipboard
                    </button>
                @endif
            </div>

            <div class="w-full flex flex-col md:flex-row space-y-8 md:space-y-0 md:space-x-8">
                <textarea class="form-textarea w-full min-h-xs bg-gray-50" wire:model="markdown" readonly id="markdown" placeholder="Your markdown will appear here..."></textarea>

                <div class="form-textarea w-full min-h-xs bg-gray-50 prose">
                    @if (!empty($markdown))
                        {!! App\Services\Markdown::parse($markdown) !!}
                    @else
                        <p class="text-gray-400">The markdown preview will appear here...</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-screen-xl mx-auto overflow-hidden">
        <p class="flex items-center justify-center text-center text-base leading-6 text-gray-500 space-x-1">
            <span>Made with</span>
            <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="heart" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="text-red-500 w-4 h-4"><title>Love</title><path fill="currentColor" d="M462.3 62.6C407.5 15.9 326 24.3 275.7 76.2L256 96.5l-19.7-20.3C186.1 24.3 104.5 15.9 49.7 62.6c-62.8 53.6-66.1 149.8-9.9 207.9l193.5 199.8c12.5 12.9 32.8 12.9 45.3 0l193.5-199.8c56.3-58.1 53-154.3-9.8-207.9z" class=""></path></svg>
            <span>by</span>
            <a href="https://twitter.com/LiamHammett" class="text-gray-500 hover:text-gray-600 font-bold">@LiamHammett</a>
        </p>
    </div>

</div>
