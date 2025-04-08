<?php

if (!function_exists('passwordGeneration')) {
    /**
     * @param string $password
     *
     * @return string
     */
    function passwordGeneration(string $password): string
    {
        $data = htmlHideCode($password);

        return '#?!@^&*(@#$%^-@#$' . $data . '#?#$%^&*!@%^&*@#$()_+';
    }
}


if (!function_exists('htmlHideCode')) {
    /**
     * @param string $code
     *
     * @return string
     */
    function htmlHideCode(string $code): string
    {
        $data = trim($code);
        $data = stripslashes($data);

        return htmlspecialchars($data);
    }
}

if (!function_exists('createUniqueKey')) {
    /**
     * @param int $limit
     *
     * @return string
     */
    function createUniqueKey(int $limit = 6): string
    {
        return str(str()->uuid())->lower()->limit(limit: $limit, end: '')->toString();
    }
}

if (!function_exists('getStringLowerLimit')) {
    /**
     * @param string $field
     * @param int $limit
     *
     * @return string
     */
    function getStringLowerLimit(string $field, int $limit = 20): string
    {
        return str($field)->lower()->limit(limit: $limit)->toString();
    }
}

if (!function_exists('getStringLimit')) {
    /**
     * @param string $field
     * @param int $limit
     *
     * @return string
     */
    function getStringLimit(string $field, int $limit = 20): string
    {
        return str($field)->limit(limit: $limit)->toString();
    }
}


if (!function_exists('getSidebarActive')) {
    /**
     * @param string $field
     *
     * @return string
     */
    function getSidebarActive(string $url): string
    {
        return request()->is($url) ? 'active' : '';
    }
}


if (!function_exists('configMailCheck')) {
    /**
     * @return bool
     */
    function configMailCheck(): bool
    {
        // Kontrol öncesi e-posta ayarlarının geçerliliğini kontrol et
        if (
            empty(config('mail.mailers.smtp.transport'))
            || empty(config('mail.mailers.smtp.host'))
            || empty(config('mail.mailers.smtp.port'))
            || empty(config('mail.mailers.smtp.encryption'))
            || empty(config('mail.mailers.smtp.username'))
            || empty(config('mail.mailers.smtp.password'))
        ) {
            return false;
        }

        return true;
    }
}


if (!function_exists('initials')) {
    /**
     * Kullanıcının Baş Harflerini Almaya Yarar
     *
     * @param string $name
     *
     * @return string
     */
    function initials(string $name): string
    {
        return str()->of($name)
            ->explode(' ')
            ->map(fn(string $name) => str()->of($name)->substr(0, 1))
            ->implode('');
    }
}
