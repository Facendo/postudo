@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-brand-accent dark:focus:border-brand-accent focus:ring-brand-accent dark:focus:ring-brand-accent rounded-md shadow-sm']) }}>
