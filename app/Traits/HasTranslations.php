<?php

namespace App\Traits;

trait HasTranslations
{
    public function trans(string $field): mixed
    {
        $locale = app()->getLocale();

        if ($locale !== 'tr') {
            $enField = $field . '_' . $locale;
            if (isset($this->attributes[$enField]) && !empty($this->attributes[$enField])) {
                return $this->attributes[$enField];
            }
        }

        return $this->attributes[$field] ?? null;
    }
}
