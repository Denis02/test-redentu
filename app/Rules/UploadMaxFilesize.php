<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UploadMaxFilesize implements Rule
{
    public function passes($attribute, $value)
    {
        return is_file($value) && filesize($value) < ((int)ini_get('upload_max_filesize')*1048576);
    }

    public function message()
    {
        return 'The uploaded file is larger than the allowed size.';
    }
}
