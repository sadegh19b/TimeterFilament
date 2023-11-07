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
    */

    'accepted'               => 'فیلد :attribute باید پذیرفته شود.',
    'accepted_if'            => 'فیلد :attribute باید پذیرفته شود، زمانی که :other برابر با :value باشد.',
    'active_url'             => 'فیلد :attribute یک URL معتبر نیست.',
    'after'                  => 'فیلد :attribute باید تاریخی بعد از :date باشد.',
    'after_or_equal'         => 'فیلد :attribute باید تاریخی بعد از :date، یا برابر با آن باشد.',
    'alpha'                  => 'فیلد :attribute باید فقط شامل حروف الفبا باشد.',
    'alpha_dash'             => 'فیلد :attribute باید فقط شامل حروف الفبا، اعداد، خط تیره و زیرخط باشد.',
    'alpha_num'              => 'فیلد :attribute باید فقط شامل حروف الفبا و اعداد باشد.',
    'array'                  => 'فیلد :attribute باید یک آرایه باشد.',
    'ascii'                  => 'فیلد :attribute فقط باید شامل کاراکترها و نمادهای الفبایی تک بایتی (ASCII) باشد.',
    'before'                 => 'فیلد :attribute باید تاریخی قبل از :date باشد.',
    'before_or_equal'        => 'فیلد :attribute باید تاریخی قبل از :date، یا برابر با آن باشد.',
    'between'                => [
        'array'   => 'فیلد :attribute باید بین :min و :max آیتم باشد.',
        'file'    => 'فیلد :attribute باید بین :min و :max کیلوبایت باشد.',
        'numeric' => 'فیلد :attribute باید بین :min و :max باشد.',
        'string'  => 'فیلد :attribute باید بین :min و :max کاراکتر باشد.',
    ],
    'boolean'                => 'فیلد :attribute فقط می‌تواند true و یا false باشد.',
    'can'                    => 'فیلد :attribute حاوی یک مقدار غیرمجاز است.',
    'confirmed'              => 'فیلد :attribute با فیلد تکرار مطابقت ندارد.',
    'current_password'       => 'رمزعبور اشتباه است.',
    'date'                   => 'فیلد :attribute یک تاریخ معتبر نیست.',
    'date_equals'            => 'فیلد :attribute باید تاریخی برابر با :date باشد.',
    'date_format'            => 'فیلد :attribute با الگوی :format مطابقت ندارد.',
    'decimal'                => 'فیلد :attribute باید دارای :decimal رقم اعشار باشد.',
    'declined'               => 'فیلد :attribute باید رد شود.',
    'declined_if'            => 'فیلد :attribute باید رد شود، زمانی که :other برابر با :value است.',
    'different'              => 'فیلد :attribute و :other باید از یکدیگر متفاوت باشند.',
    'digits'                 => 'فیلد :attribute باید :digits رقم باشد.',
    'digits_between'         => 'فیلد :attribute باید بین :min و :max رقم باشد.',
    'dimensions'             => 'فیلد :attribute دارای ابعاد تصویر نامعتبر است.',
    'distinct'               => 'فیلد :attribute دارای یک مقدار تکراری است.',
    'doesnt_end_with'        => 'فیلد :attribute ممکن است به یکی از موارد روبرو ختم نشود: :values.',
    'doesnt_start_with'      => 'فیلد :attribute ممکن است با یکی از موارد روبرو شروع نشود: :values.',
    'email'                  => 'فیلد :attribute باید یک ایمیل معتبر باشد.',
    'ends_with'              => 'فیلد :attribute باید به یکی از موارد روبرو ختم شود: :values.',
    'enum'                   => 'فیلد :attribute انتخاب شده، معتبر نیست.',
    'exists'                 => 'فیلد :attribute انتخاب شده، معتبر نیست.',
    'file'                   => 'فیلد :attribute باید یک فایل معتبر باشد.',
    'filled'                 => 'فیلد :attribute باید مقدار داشته باشد.',
    'gt'                     => [
        'array'   => 'فیلد :attribute باید بیشتر از :value آیتم داشته باشد.',
        'file'    => 'فیلد :attribute باید بزرگتر از :value کیلوبایت باشد.',
        'numeric' => 'فیلد :attribute باید بزرگتر از :value باشد.',
        'string'  => 'فیلد :attribute باید بیشتر از :value کاراکتر داشته باشد.',
    ],
    'gte'                    => [
        'array'   => 'فیلد :attribute باید بیشتر یا برابر با :value آیتم داشته باشد.',
        'file'    => 'فیلد :attribute باید بزرگتر یا برابر با :value کیلوبایت باشد.',
        'numeric' => 'فیلد :attribute باید بزرگتر یا برابر با :value باشد.',
        'string'  => 'فیلد :attribute باید بیشتر یا برابر با :value کاراکتر داشته باشد.',
    ],
    'image'                  => 'فیلد :attribute باید یک تصویر معتبر باشد.',
    'in'                     => 'فیلد :attribute انتخاب شده، معتبر نیست.',
    'in_array'               => 'فیلد :attribute در :other وجود ندارد.',
    'integer'                => 'فیلد :attribute باید عدد صحیح باشد.',
    'ip'                     => 'فیلد :attribute باید یک آدرس IP معتبر باشد.',
    'ipv4'                   => 'فیلد :attribute باید یک آدرس معتبر از نوع IPv4 باشد.',
    'ipv6'                   => 'فیلد :attribute باید یک آدرس معتبر از نوع IPv6 باشد.',
    'json'                   => 'فیلد :attribute باید یک رشته از نوع JSON باشد.',
    'lowercase'              => 'فیلد :attribute باید با حروف کوچک باشد.',
    'lt'                     => [
        'array'   => 'فیلد :attribute باید کمتر از :value آیتم داشته باشد.',
        'file'    => 'فیلد :attribute باید کوچکتر از :value کیلوبایت باشد.',
        'numeric' => 'فیلد :attribute باید کوچکتر از :value باشد.',
        'string'  => 'فیلد :attribute باید کمتر از :value کاراکتر داشته باشد.',
    ],
    'lte'                    => [
        'array'   => 'فیلد :attribute باید کمتر یا برابر با :value آیتم داشته باشد.',
        'file'    => 'فیلد :attribute باید کوچکتر یا برابر با :value کیلوبایت باشد.',
        'numeric' => 'فیلد :attribute باید کوچکتر یا برابر با :value باشد.',
        'string'  => 'فیلد :attribute باید کمتر یا برابر با :value کاراکتر داشته باشد.',
    ],
    'mac_address'            => 'فیلد :attribute باید یک MAC آدرس معتبر باشد.',
    'max'                    => [
        'array'   => 'فیلد :attribute نباید بیشتر از :max آیتم داشته باشد.',
        'file'    => 'فیلد :attribute نباید بزرگتر از :max کیلوبایت باشد.',
        'numeric' => 'فیلد :attribute نباید بزرگتر از :max باشد.',
        'string'  => 'فیلد :attribute نباید بیشتر از :max کاراکتر داشته باشد.',
    ],
    'max_digits'             => 'فیلد :attribute نباید بیش از :max رقم داشته باشد.',
    'mimes'                  => 'فرمت‌های معتبر فایل عبارتند از: :values.',
    'mimetypes'              => 'فرمت‌های معتبر فایل عبارتند از: :values.',
    'min'                    => [
        'array'   => 'فیلد :attribute نباید کمتر از :min آیتم داشته باشد.',
        'file'    => 'فیلد :attribute نباید کوچکتر از :min کیلوبایت باشد.',
        'numeric' => 'فیلد :attribute نباید کوچکتر از :min باشد.',
        'string'  => 'فیلد :attribute نباید کمتر از :min کاراکتر داشته باشد.',
    ],
    'min_digits'             => 'فیلد :attribute باید حداقل :min رقم داشته باشد.',
    'missing'                => 'فیلد :attribute باید مفقود باشد.',
    'missing_if'             => 'فیلد :attribute باید مفقود باشد، زمانی که :other برابر با :value باشد.',
    'missing_unless'         => 'فیلد :attribute باید مفقود باشد، مگر اینکه :other برابر با :value باشد.',
    'missing_with'           => 'فیلد :attribute باید در صورت وجود :values مفقود باشد.',
    'missing_with_all'       => 'فیلد :attribute باید در صورت وجود :values مفقود باشد.',
    'multiple_of'            => 'فیلد :attribute باید یکی از موارد :value باشد.',
    'not_in'                 => 'فیلد :attribute انتخاب شده، معتبر نیست.',
    'not_regex'              => 'فرمت فیلد :attribute معتبر نیست.',
    'numeric'                => 'فیلد :attribute باید یک عدد باشد.',
    'password'               => [
        'letters'       => 'فیلد :attribute باید حداقل یک حرف داشته باشد.',
        'mixed'         => 'فیلد :attribute باید حداقل دارای یک حرف بزرگ و یک حرف کوچک باشد.',
        'numbers'       => 'فیلد :attribute باید حداقل دارای یک عدد باشد.',
        'symbols'       => 'فیلد :attribute باید حداقل دارای یک نماد (Symbol) باشد.',
        'uncompromised' => 'فیلد :attribute داده شده در نشت داده ظاهر شده است. لطفاً یک فیلد :attribute متفاوت انتخاب کنید.',
    ],
    'present'                => 'فیلد :attribute باید وجود داشته باشد.',
    'prohibited'             => 'فیلد :attribute ممنوع است.',
    'prohibited_if'          => 'فیلد :attribute وقتی :other برابر با :value باشد ممنوع است.',
    'prohibited_unless'      => 'فیلد :attribute ممنوع است مگر اینکه :other در :values باشد.',
    'prohibits'              => 'فیلد :attribute حضور :other را ممنوع می کند.',
    'regex'                  => 'فرمت فیلد :attribute معتبر نیست.',
    'required'               => 'فیلد :attribute الزامی است.',
    'required_array_keys'    => 'فیلد :attribute باید شامل موارد روبرو باشد: :values.',
    'required_if'            => 'وقتی :other برابر با :value باشد، فیلد :attribute الزامی است.',
    'required_if_accepted'   => 'فیلد :attribute در صورت پذیرفته شدن :other مورد نیاز است.',
    'required_unless'        => 'فیلد :attribute الزامی است مگر اینکه :other در :values باشد.',
    'required_with'          => 'فیلد :attribute در صورت وجود فیلد :values الزامی است.',
    'required_with_all'      => 'در صورت وجود فیلدهای :values، فیلد :attribute الزامی است.',
    'required_without'       => 'در صورت عدم وجود فیلد :values، فیلد :attribute الزامی است.',
    'required_without_all'   => 'در صورت عدم وجود هر یک از فیلدهای :values، فیلد :attribute الزامی است.',
    'same'                   => 'فیلد :attribute و :other باید همانند هم باشند.',
    'size'                   => [
        'array'   => 'فیلد :attribute باید شامل :size آیتم باشد.',
        'file'    => 'فیلد :attribute باید برابر با :size کیلوبایت باشد.',
        'numeric' => 'فیلد :attribute باید برابر با :size باشد.',
        'string'  => 'فیلد :attribute باید برابر با :size کاراکتر باشد.',
    ],
    'starts_with'            => 'فیلد :attribute باید با یکی از موارد روبرو شروع شود: :values.',
    'string'                 => 'فیلد :attribute باید رشته باشد.',
    'timezone'               => 'فیلد :attribute باید یک منطقه زمانی معتبر باشد.',
    'unique'                 => 'فیلد :attribute قبلا انتخاب شده است.',
    'uploaded'               => 'بارگذاری فایل فیلد :attribute موفقیت آمیز نبود.',
    'uppercase'              => 'فیلد :attribute باید با حروف بزرگ باشد.',
    'url'                    => 'فیلد :attribute باید یک URL معتبر باشد.',
    'ulid'                   => 'فیلد :attribute باید یک ULID معتبر باشد.',
    'uuid'                   => 'فیلد :attribute باید یک UUID معتبر باشد.',

    /*
     * Verta Validations
     */
    'jdate'                  => ':attribute معتبر نمی باشد.',
    'jdate_equal'            => ':attribute برابر :date نمی باشد.',
    'jdate_not_equal'        => ':attribute نامساوی :date نمی باشد.',
    'jdatetime'              => ':attribute معتبر نمی باشد.',
    'jdatetime_equal'        => ':attribute مساوی :date نمی باشد.',
    'jdatetime_not_equal'    => ':attribute نامساوی :date نمی باشد.',
    'jdate_after'            => ':attribute باید بعد از :date باشد.',
    'jdate_after_equal'      => ':attribute باید بعد یا برابر از :date باشد.',
    'jdatetime_after'        => ':attribute باید بعد از :date باشد.',
    'jdatetime_after_equal'  => ':attribute باید بعد یا برابر از :date باشد.',
    'jdate_before'           => ':attribute باید قبل از :date باشد.',
    'jdate_before_equal'     => ':attribute باید قبل یا برابر از :date باشد.',
    'jdatetime_before'       => ':attribute باید قبل از :date باشد.',
    'jdatetime_before_equal' => ':attribute باید قبل یا برابر از :date باشد.',

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
        'attribute-name' => [
            'rule-name' => 'custom-message',
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

    'attributes' => [],
];
