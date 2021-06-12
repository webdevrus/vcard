<?php

namespace WebDevRus\vCard;

abstract class vCardAttributes
{
    public const URL         = 'URL:%s';
    public const NAME        = 'N:%s;%s%s';
    public const EMAIL       = 'EMAIL;TYPE=INTERNET:%s';
    public const PHONE       = 'TEL;TYPE=%s:+%d';
    public const IMAGE       = 'PHOTO;ENCODING=b;TYPE=%s:%s';
    public const IMAGE_URL   = 'PHOTO;VALUE=uri:%s';
    public const NICKNAME    = 'NICKNAME:%s';
    public const BDAY        = 'BDAY:%s';
    public const IMAGE_TYPES = ['png', 'jpg', 'jpeg', 'gif'];
}
