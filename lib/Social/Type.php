<?php

namespace Social;


final class Type
{
    const VK = 1;
    const MR = 2;
    const FB = 3;
    const GITHUB = 4;
    const TWITTER = 5;

    public static function getName($type)
    {
        switch ($type) {
            case self::VK:
                return 'Vkontakte';
            case self::MR:
                return 'Mailru';
            case self::FB:
                return 'Facebook';
            case self::GITHUB:
                return 'Github';
            case self::TWITTER:
                return 'Twitter';
        }

        return 'Unknown';
    }

    public static function exists($type)
    {
        return $type >= self::VK && $type <= self::TWITTER;
    }
}