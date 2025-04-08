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

    'accepted' => ':attribute kabul edilmiş olmalıdır.',
    'accepted_if' => ':attribute, :other :value olduğunda kabul edilmelidir.',
    'active_url' => ':attribute geçerli bir URL olmalıdır.',
    'after' => ':attribute, :date tarihinden sonra olmalıdır.',
    'after_or_equal' => ':attribute, :date ile aynı veya sonraki bir tarih olmalıdır.',
    'alpha' => ':attribute yalnızca harf içermelidir.',
    'alpha_dash' => ':attribute yalnızca harfler, sayılar, tireler, ve alt-tirelerden oluşmalıdır.',
    'alpha_num' => ':attribute yalnızca harf ve rakam içermelidir.',
    'array' => ':attribute, dizi olmalıdır.',
    'ascii' => ':attribute yalnızca tek baytlık alfasayısal karakterler ve simgeler içermelidir.',
    'before' => ':attribute, :date tarihinden önce olmalıdır.',
    'before_or_equal' => ':attribute, :date ile aynı veya önceki bir tarih olmalıdır.',
    'between' => [
        'array' => ':attribute :min ve :max arasında öğeye sahip olmalıdır.',
        'file' => ':attribute :min ve :max kilobyte arasında olmalıdır.',
        'numeric' => ':attribute, :min ve :max arasında olmalıdır.',
        'string' => ':attribute :min ve :max karakter içermelidir.',
    ],
    'boolean' => ':attribute, yalnızca doğru veya yanlış olabilir.',
    'can' => ':attribute geçersiz bir değer içeriyor.',
    'confirmed' => ':attribute doğrulaması eşleşmiyor.',
    'current_password' => 'Parola yanlış.',
    'date' => ':attribute geçerli bir tarih olmalıdır.',
    'date_equals' => ':attribute, :date tarihi ile aynı olmalıdır.',
    'date_format' => ':attribute :format biçimiyle uyumlu olmalıdır.',
    'decimal' => ':attribute, :decimal ondalık basamaklara sahip olmalıdır.',
    'declined' => ':attribute kabul edilmemelidir.',
    'declined_if' => ':attribute, :other :value olduğunda kabul edilmemelidir.',
    'different' => ':attribute ve :other farklı olmalıdır.',
    'digits' => ':attribute, :digits rakam içermelidir.',
    'digits_between' => ':attribute, :min ve :max rakamdan oluşmalıdır.',
    'dimensions' => ':attribute geçersiz resim boyunlarına sahip.',
    'distinct' => ':attribute tekrar kullanılmış.',
    'doesnt_end_with' => ':attribute şunlardan biri ile bitmemelidir: :values.',
    'doesnt_start_with' => ':attribute şunlardan biri ile başlamamalıdır: :values.',
    'email' => ':attribute geçerli bir e-posta adresi olmalıdır.',
    'ends_with' => ':attribute şunlardan biri ile bitmelidir: :values.',
    'enum' => 'Seçilen :attribute geçersiz.',
    'exists' => 'Seçilen :attribute geçersiz.',
    'extensions' => ':attribute şu uzantılardan birine sahip olmalıdır: :values.',
    'file' => ':attribute bir dosya olmalıdır.',
    'filled' => ':attribute bir değere sahip olmalıdır.',
    'gt' => [
        'array' => ':attribute, :value adetten fazla olmalıdır.',
        'file' => ':attribute, :value kilobyte üzerinde olmalıdır.',
        'numeric' => ':attribute, :value değerinden büyük olmalıdır.',
        'string' => ':attribute, :value karakterden uzun olmalıdır.',
    ],
    'gte' => [
        'array' => ':attribute ,:value veya daha fazla öğe içermelidir.',
        'file' => ':attribute, :value kilobyte veya daha büyük boyuta sahip olmalıdır.',
        'numeric' => ':attribute, :value veya daha büyük olmalıdır.',
        'string' => ':attribute, :value veya daha fazla karakter içermelidir.',
    ],
    'hex_color' => ':attribute geçerli bir hex rengi olmalıdır.',
    'image' => ':attribute resim olmalıdır.',
    'in' => 'Seçilen :attribute geçersiz.',
    'in_array' => ':attribute, :other içinde olmalıdır.',
    'integer' => ':attribute tamsayı olmalıdır.',
    'ip' => ':attribute geçerli bir IP adresi olmalıdır.',
    'ipv4' => ':attribute geçerli bir IPv4 adresi olmalıdır.',
    'ipv6' => ':attribute geçerli bir IPv6 adresi olmalıdır.',
    'json' => ':attribute geçerli bir JSON dizesi olmalıdır.',
    'list' => ':attribute bir liste olmalıdır.',
    'lowercase' => ':attribute küçük harf olmalıdır.',
    'lt' => [
        'array' => ':attribute, :value adetten az olmalıdır..',
        'file' => ':attribute, :value kilobyte altında olmalıdır..',
        'numeric' => ':attribute, :value değerinden küçük olmalıdır.',
        'string' => ':attribute, :value karakterden kısa olmalıdır.',
    ],
    'lte' => [
        'array' => ':attribute, :value veya daha az öğe içermelidir.',
        'file' => ':attribute, :value kilobyte veya daha küçük boyuta sahip olmalıdır.',
        'numeric' => ':attribute, :value veya daha küçük olmalıdır.',
        'string' => ':attribute, :value veya daha az karakter içermelidir.',
    ],
    'mac_address' => ':attribute geçerli bir MAC adresi olmalıdır.',
    'max' => [
        'array' => ':attribute :max öğeden daha fazlasına sahip olmamalıdır.',
        'file' => ':attribute :max kilobyte boyutundan büyük olmamalıdır.',
        'numeric' => ':attribute, :max değerinden büyük olmamalıdır.',
        'string' => ':attribute, :max karakterden daha uzun olmamalıdır.',
    ],
    'max_digits' => ':attribute :max rakamdan fazla olmamalıdır.',
    'mimes' => ':attribute şu türlerden biri olmalıdır: :values.',
    'mimetypes' => ':attribute şu türlerden biri olmalıdır: :values.',
    'min' => [
        'array' => ':attribute en az :min öğe içermelidir.',
        'file' => ':attribute en az :min kilobyte olmalıdır.',
        'numeric' => ':attribute en az :min olmalıdır.',
        'string' => ':attribute en az :min karakterden oluşmalıdır.',
    ],
    'min_digits' => ':attribute en az :min rakam içermelidir.',
    'missing' => ':attribute eksik olmalıdır.',
    'missing_if' => ':other, :value olduğunda :attribute eksik olmalıdır.',
    'missing_unless' => ':other, :value olmadığı sürece :attribute eksik olmalıdır.',
    'missing_with' => ':attribute, :values mevcut olduğunda eksik olmalıdır.',
    'missing_with_all' => ':attribute, :values mevcut olduğunda eksik olmalıdır.',
    'multiple_of' => ':attribute, :value değerinin katı olmalıdır.',
    'not_in' => 'Seçilen :attribute geçersiz.',
    'not_regex' => ':attribute biçimi geçersiz.',
    'numeric' => ':attribute sayı olmalıdır.',
    'password' => [
        'letters' => ':attribute en az bir harf içermelidir.',
        'mixed' => ':attribute en az bir küçük harf ve büyük harf içermelidir.',
        'numbers' => ':attribute en az bir sayı içermelidir.',
        'symbols' => ':attribute en az bir sembol içermelidir.',
        'uncompromised' => ':attribute veri sızıntısında tespit edildi. Lütfen farklı bir :attribute seçin.',
    ],
    'present' => ':attribute mevcut olmalıdır.',
    'present_if' => ':other, :value olduğunda :attribute mevcut olmalıdır.',
    'present_unless' => ':other, :value olmadığı sürece :attribute mevcut olmalıdır.',
    'present_with' => ':attribute, :values değerleri bulunduğunda mevcut olmalıdır.',
    'present_with_all' => ':attribute, :values değerleri bulunduğunda mevcut olmalıdır.',
    'prohibited' => ':attribute gönderilmemelidir.',
    'prohibited_if' => ':other, :value olduğunda :attribute gönderilmemelidir.',
    'prohibited_unless' => ':other, :values olmadığı sürece :attribute gönderilmemelidir.',
    'prohibits' => ':attribute, :other ile gönderilmemelidir.',
    'regex' => ':attribute biçimi geçersiz.',
    'required' => ':attribute zorunludur.',
    'required_array_keys' => ':attribute şu girdileri içermelidir: :values.',
    'required_if' => ':other, :value olduğunda :attribute zorunludur.',
    'required_if_accepted' => ':other kabul edildiğinde :attribute zorunludur.',
    'required_unless' => ':other, :values içermediği sürece :attribute zorunludur.',
    'required_with' => ':values mevcut olduğunda :attribute zorunludur.',
    'required_with_all' => ':values mevcut olduğunda :attribute gereklidir.',
    'required_without' => ':values bulunmadığında :attribute zorunludur.',
    'required_without_all' => ':attribute, şunlardan biri olmadığında zorunludur: :values',
    'same' => ':attribute, :other ile uyuşmalıdır.',
    'size' => [
        'array' => ':attribute, :size öğe içermelidir.',
        'file' => ':attribute, :size kilobyte olmalıdır.',
        'numeric' => ':attribute, :size olmalıdır.',
        'string' => ':attribute, :size karakter olmalıdır.',
    ],
    'starts_with' => ':attribute şunlardan biri ile başlamalıdır: :values.',
    'string' => ':attribute bir karakter dizisi olmalıdır.',
    'timezone' => ':attribute geçerli bir saat dilimi olmalıdır.',
    'unique' => ':attribute daha önce kullanılmış.',
    'uploaded' => ':attribute yüklemesi başarısız.',
    'uppercase' => ':attribute büyük harf olmalıdır.',
    'url' => ':attribute geçerli bir URL olmalıdır.',
    'ulid' => ':attribute geçerli bir ULID olmalıdır.',
    'uuid' => ':attribute geçerli bir UUID olmalıdır.',
    'throttle' => 'Çok fazla deneme yaptınız. Lütfen daha sonra tekrar deneyin.',

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

    'phone' => ':attribute alanı geçerli bir telefon numarası olmalıdır.',
];
