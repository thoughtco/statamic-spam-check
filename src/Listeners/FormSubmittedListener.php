<?php

namespace Thoughtco\StatamicSpamCheck\Listeners;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Statamic\Events\FormSubmitted;

class FormSubmittedListener
{
    public function handle(FormSubmitted $event)
    {
        if (! $apiKey = config('statamic-spam-check.api_key')) {
            return;
        }
            
        $submission = $event->submission->data();
        $form = $event->submission->form();
        $handle = $form->handle();

        $forms = config('statamic-spam-check.forms', 'all');

        if ($forms !== 'all') {
            if (! in_array($handle, $forms)) {
                return;
            }
        }

        if (app()->environment() != 'production') {
            $testMode = config('statamic-spam-check.test_mode', 'off');

            if ($testMode == 'disable') {
                return;
            }

            if ($testMode == 'fail') {
                if (config('statamic-spam-check.fail_silently')) {
                    return false;
                }

                $this->throwFailure();
            }
        }

        $form->blueprint()
            ->fields()->all()
            ->filter(fn ($field) => $field->type() == 'textarea' || ($field->type() == 'text' && $field->get('input_type', '') == 'email'))
            ->each(function ($field) use ($apiKey, $submission) {

                if ($content = $submission->get($field->handle())) {
                    $response = Http::withHeaders(['apikey' => $apiKey])
                        ->withBody($content, 'text/plain')
                        ->post('https://api.apilayer.com/spamchecker?threshold='.config('statamic-spam-check.threshold'));
        
                    $json = $response->json();
                            
                    if (Arr::get($json, 'is_spam', false)) {
        
                        if (config('statamic-spam-check.fail_silently')) {
                            return false;
                        }
        
                        $this->throwFailure();
                   }
        
                }
            });
    }

    public function throwFailure()
    {
        throw ValidationException::withMessages([
            '_unspecified' => __('Failed spam check'),
        ]);
    }
}
