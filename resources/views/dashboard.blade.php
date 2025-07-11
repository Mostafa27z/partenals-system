<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden transition duration-300">
                <div class="p-6 sm:p-8">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-800 dark:text-white mb-2">
                        {{ __("Welcome!") }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm sm:text-base leading-relaxed">
                        {{ __("You're logged in!") }}
                    </p>
                </div>
                <div class="bg-gray-100 dark:bg-gray-700 px-6 py-4 text-sm text-gray-600 dark:text-gray-300 border-t border-gray-200 dark:border-gray-600">
                    {{ __('We hope you have a productive session.') }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
