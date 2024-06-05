<?php

namespace App\Http\Collections;

class LogableCollection extends Collection
{
    public function toArray($request)
    {

        $this->collection = $this->collection->map(function ($item) {
            $data = $item->data;

            if (isset($data['password'])) {
                $data['password'] = 'xxxxxxxx';
            }

            if (isset($data['password_confirmation'])) {
                $data['password_confirmation'] = 'xxxxxxxx';
            }

            if (isset($data['remember_token'])) {
                $data['remember_token'] = 'xxxxxxxx';
            }

            return collect($item)
                ->except('logable', 'logable_type', 'user_id')
                ->put('data', $this->parsedSensitiveData($item->data));
        });

        return parent::toArray($request);

    }

    protected function parsedSensitiveData($data)
    {
        if (isset($data['password'])) {
            $data['password'] = 'xxxxxxxx';
        }

        if (isset($data['password_confirmation'])) {
            $data['password_confirmation'] = 'xxxxxxxx';
        }

        if (isset($data['remember_token'])) {
            $data['remember_token'] = 'xxxxxxxx';
        }

        return $data;
    }
}
