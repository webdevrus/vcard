# Генерация vCard
Пакет предназначен для генерации [vCard](https://ru.wikipedia.org/wiki/VCard).

## Установка
```console
$ composer require webdevrus/vcard
```

## Использование
```php
<?php

use WebDevRus\vCard\vCard;

$vcard = new vCard();
$vcard->setName('Иванов', 'Иван', 'Иванович');
      ->setNickname('webdevrus');
      ->setEmail('web.developers.rus@gmail.com');
      ->setUrl('https://github.com/webdevrus');
      ->setImage('/path/to/image/photo.jpg')
      ->setPhone('79999999999');

// Вывести в текстовом виде
echo $vcard->getContent();

// Скачать файл *.vcf
return $vcard->get();
```

Также есть возможность добавлять поля, под которые не написаны методы (см. пример).

```php
$vcard->setOther('X-SKYPE;TYPE=cell,msg:+79999999999');
```

## Прочие возможности
```php
$vcard->setBirthday('1990-12-31');
$vcard->setPhone('79999999999', 'cell', 'work');
$vcard->setImageUrl('https://avatars.githubusercontent.com/u/66517380');
```

К не интуитивно понятным методам добавил описание в PHPDoc.

# Тестирование
```console
$ composer test
```