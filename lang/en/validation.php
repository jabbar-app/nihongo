<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    | These messages are customized to be friendly and encouraging for
    | language learners starting their speaking journey.
    |
    */

    'accepted' => 'Please accept the :attribute to continue.',
    'accepted_if' => 'Please accept the :attribute when :other is :value.',
    'active_url' => 'Hmm, that doesn\'t look like a valid URL. Please check and try again.',
    'after' => 'Please choose a date after :date.',
    'after_or_equal' => 'Please choose a date on or after :date.',
    'alpha' => 'The :attribute should only contain letters.',
    'alpha_dash' => 'The :attribute should only contain letters, numbers, dashes, and underscores.',
    'alpha_num' => 'The :attribute should only contain letters and numbers.',
    'array' => 'Please provide valid :attribute options.',
    'ascii' => 'The :attribute should only contain single-byte characters and symbols.',
    'before' => 'Please choose a date before :date.',
    'before_or_equal' => 'Please choose a date on or before :date.',
    'between' => [
        'array' => 'Please select between :min and :max items.',
        'file' => 'The file should be between :min and :max kilobytes.',
        'numeric' => 'Please enter a number between :min and :max.',
        'string' => 'Please enter between :min and :max characters.',
    ],
    'boolean' => 'This field should be true or false.',
    'can' => 'The :attribute field contains an unauthorized value.',
    'confirmed' => 'The passwords don\'t match. Please try again.',
    'current_password' => 'That password isn\'t quite right. Please try again.',
    'date' => 'Please enter a valid date.',
    'date_equals' => 'Please choose a date equal to :date.',
    'date_format' => 'The date format should be :format.',
    'decimal' => 'Please enter a number with :decimal decimal places.',
    'declined' => 'The :attribute must be declined.',
    'declined_if' => 'The :attribute must be declined when :other is :value.',
    'different' => 'The :attribute and :other should be different.',
    'digits' => 'Please enter exactly :digits digits.',
    'digits_between' => 'Please enter between :min and :max digits.',
    'dimensions' => 'The image dimensions are invalid.',
    'distinct' => 'You\'ve entered a duplicate value. Please use unique values.',
    'doesnt_end_with' => 'The :attribute should not end with: :values.',
    'doesnt_start_with' => 'The :attribute should not start with: :values.',
    'email' => 'Please enter a valid email address.',
    'ends_with' => 'The :attribute should end with one of: :values.',
    'enum' => 'The selected :attribute is invalid.',
    'exists' => 'The selected :attribute is invalid.',
    'extensions' => 'The :attribute must have one of the following extensions: :values.',
    'file' => 'Please upload a valid file.',
    'filled' => 'This field is required.',
    'gt' => [
        'array' => 'Please select more than :value items.',
        'file' => 'The file should be larger than :value kilobytes.',
        'numeric' => 'Please enter a number greater than :value.',
        'string' => 'Please enter more than :value characters.',
    ],
    'gte' => [
        'array' => 'Please select at least :value items.',
        'file' => 'The file should be at least :value kilobytes.',
        'numeric' => 'Please enter a number of at least :value.',
        'string' => 'Please enter at least :value characters.',
    ],
    'hex_color' => 'Please enter a valid hexadecimal color.',
    'image' => 'Please upload an image file.',
    'in' => 'The selected :attribute is invalid.',
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer' => 'Please enter a whole number.',
    'ip' => 'Please enter a valid IP address.',
    'ipv4' => 'Please enter a valid IPv4 address.',
    'ipv6' => 'Please enter a valid IPv6 address.',
    'json' => 'Please provide valid JSON.',
    'lowercase' => 'The :attribute should be lowercase.',
    'lt' => [
        'array' => 'Please select fewer than :value items.',
        'file' => 'The file should be smaller than :value kilobytes.',
        'numeric' => 'Please enter a number less than :value.',
        'string' => 'Please enter fewer than :value characters.',
    ],
    'lte' => [
        'array' => 'Please select no more than :value items.',
        'file' => 'The file should be no larger than :value kilobytes.',
        'numeric' => 'Please enter a number no greater than :value.',
        'string' => 'Please enter no more than :value characters.',
    ],
    'mac_address' => 'Please enter a valid MAC address.',
    'max' => [
        'array' => 'Please select no more than :max items.',
        'file' => 'The file should be no larger than :max kilobytes.',
        'numeric' => 'Please enter a number no greater than :max.',
        'string' => 'Please keep it under :max characters.',
    ],
    'max_digits' => 'Please enter no more than :max digits.',
    'mimes' => 'Please upload a file of type: :values.',
    'mimetypes' => 'Please upload a file of type: :values.',
    'min' => [
        'array' => 'Please select at least :min items.',
        'file' => 'The file should be at least :min kilobytes.',
        'numeric' => 'Please enter a number of at least :min.',
        'string' => 'Please enter at least :min characters.',
    ],
    'min_digits' => 'Please enter at least :min digits.',
    'missing' => 'The :attribute field must be missing.',
    'missing_if' => 'The :attribute field must be missing when :other is :value.',
    'missing_unless' => 'The :attribute field must be missing unless :other is :value.',
    'missing_with' => 'The :attribute field must be missing when :values is present.',
    'missing_with_all' => 'The :attribute field must be missing when :values are present.',
    'multiple_of' => 'Please enter a multiple of :value.',
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'The format is invalid.',
    'numeric' => 'Please enter a number.',
    'password' => [
        'letters' => 'Your password should include at least one letter.',
        'mixed' => 'Your password should include both uppercase and lowercase letters.',
        'numbers' => 'Your password should include at least one number.',
        'symbols' => 'Your password should include at least one symbol.',
        'uncompromised' => 'This password has appeared in a data leak. Please choose a different password.',
    ],
    'present' => 'The :attribute field must be present.',
    'present_if' => 'The :attribute field must be present when :other is :value.',
    'present_unless' => 'The :attribute field must be present unless :other is :value.',
    'present_with' => 'The :attribute field must be present when :values is present.',
    'present_with_all' => 'The :attribute field must be present when :values are present.',
    'prohibited' => 'The :attribute field is prohibited.',
    'prohibited_if' => 'The :attribute field is prohibited when :other is :value.',
    'prohibited_unless' => 'The :attribute field is prohibited unless :other is in :values.',
    'prohibits' => 'The :attribute field prohibits :other from being present.',
    'regex' => 'The format is invalid.',
    'required' => 'This field is required to get started.',
    'required_array_keys' => 'The :attribute field must contain entries for: :values.',
    'required_if' => 'This field is required when :other is :value.',
    'required_if_accepted' => 'This field is required when :other is accepted.',
    'required_unless' => 'This field is required unless :other is in :values.',
    'required_with' => 'This field is required when :values is present.',
    'required_with_all' => 'This field is required when :values are present.',
    'required_without' => 'This field is required when :values is not present.',
    'required_without_all' => 'This field is required when none of :values are present.',
    'same' => 'The :attribute and :other should match.',
    'size' => [
        'array' => 'Please select exactly :size items.',
        'file' => 'The file should be exactly :size kilobytes.',
        'numeric' => 'Please enter :size.',
        'string' => 'Please enter exactly :size characters.',
    ],
    'starts_with' => 'The :attribute should start with one of: :values.',
    'string' => 'Please enter text.',
    'timezone' => 'Please select a valid timezone.',
    'unique' => 'This :attribute is already taken. Please try another one.',
    'uploaded' => 'The :attribute failed to upload. Please try again.',
    'uppercase' => 'The :attribute should be uppercase.',
    'url' => 'Please enter a valid URL.',
    'ulid' => 'Please enter a valid ULID.',
    'uuid' => 'Please enter a valid UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'email' => [
            'required' => 'We\'ll need your email address to create your account.',
            'email' => 'Please enter a valid email address so we can keep you updated on your speaking progress.',
            'unique' => 'This email is already registered. Ready to log in and continue speaking?',
        ],
        'password' => [
            'required' => 'Please create a password to secure your account.',
            'min' => 'Your password should be at least :min characters for security.',
            'confirmed' => 'The passwords don\'t match. Please try again.',
        ],
        'name' => [
            'required' => 'We\'d love to know what to call you!',
            'max' => 'Please keep your name under :max characters.',
        ],
        'study_goal_minutes' => [
            'required' => 'Please select your daily speaking practice goal.',
            'numeric' => 'Please enter a valid number of minutes.',
            'min' => 'We recommend at least :min minutes of daily practice.',
        ],
        'conversation_topics' => [
            'array' => 'Please select at least one conversation topic that interests you.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'email' => 'email address',
        'password' => 'password',
        'password_confirmation' => 'password confirmation',
        'name' => 'name',
        'study_goal_minutes' => 'speaking practice goal',
        'cards_per_day_goal' => 'daily cards goal',
        'conversation_topics' => 'conversation topics',
    ],

];
