<?php

namespace Social;


final class Type
{
    const VK = 1;
    const MR = 2;
    const FB = 3;
    const TWITTER = 4;

    public static function getName($type)
    {
        switch ($type) {
            case self::VK:
                return 'Vkontakte';
            case self::MR:
                return 'Mailru';
            case self::FB:
                return 'Facebook';
            case self::TWITTER:
                return 'Twitter';
        }

        return 'Unknown';
    }
}