<?php

namespace Statamic\Http\Requests;

use Statamic\Http\Requests\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PublishRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules($rules = [])
    {
        $new = [];

        collect($rules)->each(function ($rule, $key) use (&$new) {
            if (! is_array($this->input($key))) {
                $new[$key] = $rule;

                return;
            }

            foreach($this->input($key) as $index => $value) {
                $new[$key . '.' . $index] = $rule;
            }
        });

        return $new;
    }

    public function validationData($rules = [])
    {
        $request = clone $this;

        collect($rules)->each(function ($rules, $key) use ($request) {
            $fields = $request->fields;

            if (is_string($rules)) {
                $rules = explode('|', $rules);
            }

            $hasMime = array_filter($rules, function ($rule) {
                return str_contains($rule, 'mime');
            });

            if (! $hasMime) {
                return;
            }

            if (! str_contains($key, 'fields')) {
                return;
            }

            $fields[str_replace('fields.', '', $key)] = collect($request->input($key))->map(function ($file) {
                return new UploadedFile(
                    root_path($file),
                    pathinfo($file, PATHINFO_BASENAME),
                    mime_content_type(root_path($file)),
                    $size  = null,
                    $error = false,
                    $test  = true
                );
            })->toArray();

            $request->replace(['fields' => $fields]);
        });

        return $request->all();
    }
}
