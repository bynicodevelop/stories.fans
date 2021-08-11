<select {!! $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm']) !!}>
    <option value="monthly">{{ __('select-period.monthly') }}</option>
    <option value="quarterly">{{ __('select-period.quarterly') }}</option>
    <option value="annually">{{ __('select-period.annually') }}</option>
</select>
