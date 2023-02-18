# Управление конфигурацией пакетов

[![Latest Version][badge-release]][packagist]
[![Software License][badge-license]][license]
[![PHP Version][badge-php]][php]
![Coverage Status][badge-coverage]
[![Total Downloads][badge-downloads]][downloads]
[![Support mail][badge-mail]][mail]

Предоставляет возможность использовать конфигурационные файлы в вашем пакете. Объединяет все конфигурационные файлы
из установленных пакетов в один и предоставляет доступ по группам. Данный пакет реализует систему плагинов Composer,
которая обеспечивает сборку конфигураций непосредственно при установке нового пакета.

## Установка

Установить этот пакет можно как зависимость, используя Composer.

``` bash
composer require fi1a/package-config
```

## Использование

Данный пакет представляет собой плагин Composer.
После установки или удаления пакета вызываются обработчики событий, которые запускают сборку конфигураций:

- проверяется наличие ключа `package-config` в секции `extra` файла `composer.json` установленных пакетов;
- формируется карта конфигурационных файлов всех пакетов и сохраняется в файл `vendor/fi1a/package-config/runtime/.map.json`.

Ключ `package-config` в секции `extra` файла `composer.json` может иметь слудующую структуру:

```json
{
  "extra": {
    "package-config": {
      "params": "params.php",
      "web": "web.php",
      "modules": [
        {
          "sort": 1000,
          "file": "modules2.php"
        },
        {
          "sort": 900,
          "file": "modules1.php"
        }
      ]
    }
  }
}
```

Ключи `params`, `web` и `modules` являются названием группы конфигураций. По данному параметру группируются
значения в объединенной конфигурации (например конфигурация из файла `params.php` будет записана в группу `params` и
будет доступна по данному значению).

Значения `params.php`, `web.php`, `modules1.php` и `modules2.php` являются названиями файлов конфигурации в директории
`configs`.

Значение ключа `sort` представляет собой сортировку по которой будут отсортированы в порядке возрастания файлы
конфигураций перед объединением. Соответсвенно данный параметр влияет из какого конфигурационного файла значение
будет перезаписано. По умолчанию для пакета сортировка равна 500, а для корневого пакета равна 1000.

Файлы конфигураций пакетов должны располагаться в папке `configs` вашего пакета.

Для доступа к значениям конфигурационных файлов можно воспользоваться хелпером `config(string $group)`:

```php
config('params')->get('foo:bar:baz', 'defaultValue');
```

Хелпер возвращает объект `Fi1a\Config\ConfigValuesInterface` из пакета [fi1a/config](https://github.com/fi1a/config#класс-со-значениями)
Данный класс позволяет получать доступ к ключам массива по пути (foo:bar:baz).

## Команды

Плагин добавляет команды `package-config-publish` и `package-config-rebuild` в Composer.

Команда `package-config-publish` публикует конфигурационные файлы пакета в директорию `configs`корневого пакета:

```bash
composer package-config-publish <package-name> [files]
```

Опубликовать все файлы конфигураций пакета:

```bash
composer package-config-publish foo/bar
```

Опубликовать указанные файлы конфигураций пакета:

```bash
composer package-config-publish foo/bar params.php web.php
```

Команда `package-config-rebuild` запускает сборку конфигураций. Она будет полезна при добавлении нового
файла конфигурации или изменение сортировки, когда требуется пересоздать файл карты конфигурационных файлов.

```bash
composer package-config-rebuild
```

[badge-release]: https://img.shields.io/packagist/v/fi1a/package-config?label=release
[badge-license]: https://img.shields.io/github/license/fi1a/package-config?style=flat-square
[badge-php]: https://img.shields.io/packagist/php-v/fi1a/package-config?style=flat-square
[badge-coverage]: https://img.shields.io/badge/coverage-100%25-green
[badge-downloads]: https://img.shields.io/packagist/dt/fi1a/package-config.svg?style=flat-square&colorB=mediumvioletred
[badge-mail]: https://img.shields.io/badge/mail-support%40fi1a.ru-brightgreen

[packagist]: https://packagist.org/packages/fi1a/package-config
[license]: https://github.com/fi1a/package-config/blob/master/LICENSE
[php]: https://php.net
[downloads]: https://packagist.org/packages/fi1a/package-config
[mail]: mailto:support@fi1a.ru