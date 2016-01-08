<?php

/**
 * Prepends a resource URL with the CDN prefix specified in .env "CDN" value
 *
 * @param  array  $asset The relative path to the asset. Should not include a preceeding slash
 * @return string
 *
 */
if (!function_exists('cdn')) {
    function cdn($asset)
    {
        //Check if we added cdn's to the .env file
        $cdn = env('CDN', null);

        if (is_null($cdn)) {
            return asset($asset);
        }

        return "//" . rtrim($cdn, "/") . "/" . ltrim($asset, "/");
    }
}

/**
 * Produces a Redirect object with the appropriate validation message
 * @param string $route
 * @param Illuminate\Support\Facades\Validator $validator
 * @param array $params
 * @return \Illuminate\Support\Facades\Redirect
 */
if (!function_exists('redirectWithValidation')) {
    function redirectWithValidation($route, $validator, $params = [])
    {
        return redirect()->route($route, $params)
            ->withErrors($validator)
            ->withInput();
    }
}

/**
 * Pre-fills a form input with value from session / model
 *
 * @param  string  $fieldName
 * @param  \Illuminate\Database\Eloquent\Model|null  $model
 * @param string prefix
 * @return string
 *
 */
if (!function_exists('field')) {
    function field($fieldName, $model = null, $prefix = '')
    {
        return old($prefix ? $prefix . '.' . $fieldName : $fieldName, !empty($model) ? $model->$fieldName : "");
    }
}

/**
 * Generates HTML for a set of select field options
 *
 * @param  array  $optionList Array of options. Each element should be an array with "value" and "label" elements.
 * @param  multiple  $slectedValue
 * @return string
 *
 */
if (!function_exists('options')) {
    function options($optionList, $selectedValue = null)
    {
        $options = [];
        foreach ($optionList as $option) {
            $options[] = '<option value="' . e($option['value']) . '"' . ($option['value'] == $selectedValue ? ' selected="selected"' : '') .
            '>' . e($option['label']) . '</option>';
        }
        return implode("", $options);
    }
}
