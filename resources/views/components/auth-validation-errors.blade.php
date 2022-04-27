@props(['errors'])

@if ($errors->any())
<div {{ $attributes }}>
    {{-- <div class="font-medium text-red-600">
        {{ __('Whoops! Something went wrong.') }}
    </div>

    <ul class="mt-3 list-disc list-inside text-sm text-red-600">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div> --}}

<div class="bg-red-100 border-t-4 border-red-500 rounded-b text-teal-900 px-3 py-2 shadow-md" role="alert">
    <div class="flex">
        <div class="py-1"><svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20">
                <path
                    d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z" />
            </svg></div>
        <div>
            @foreach ($errors->all() as $error)
            <p class="font-semibold text-red-500">{{ $error }}</p>
            @endforeach
            <p class="text-xs text-gray-500">Having problems logging in?
                <span class="underline">
                    Contact IP Phone 1847
                </span>
            </p>
        </div>
    </div>
</div>
@endif