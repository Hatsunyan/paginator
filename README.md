# php paginator

created ul list links for paginations

>demo  
>https://hatsunyan.github.io/paginator/  
>css from demo  
>https://hatsunyan.github.io/paginator/paginator.css

# Usage

## Simple usage
```php
$paginator = new \Hatsunyan\Paginator();
$paginator->setPages(300,10,15)->setLang('en')->setUrlPattern('/news/{p}')->render();
```
## methods
**setPages**
set pages numbers for calc
```php
$paginator->setPages(100, 10, 5);
// you can use constructor
$paginator = new \Hatsunyan\Paginator(100, 10, 5);
```
**setCurrentPage** default = 1;
```php
$paginator->setsetCurrentPage($page);
// you can use constructor or method setPages
```
**setUrlPattern**
Set patternt url, use {p} as number of page. default = '/{p}'

example
```
- '/news/{p}'
- '?page={p}'
- '/posts/{p}/category/5'
```
```php
$paginator->setUrlPattern('/page/{p}');
```
**showNextPrev**
set false to disable. default = true
```php
$pagitaror->showNextPrev(false);
```
**showLastFirst**
set false to disable. default = true
```php
$pagitaror->showLastFirst(false);
```
**setUlClass**
set ul class use for style. default = paginator
```php
$paginator->setUlClass('custom-paginator');
```
**setActivePageClass**
set active page class use for style. default = active
```php
$paginator->setUlClass('custom-active');
```

**render**
echo completed html
```php
$parinator->render();
```
**getHtml**
return completed html 
```php
$html = $paginator->getHtml();
echo $html;
//items html
echo $html;
```
**setMaxItems**
max items created without next/prev/first/last/current, must be even, or be incremented. default = 6
```php
$paginator->setMaxItems(8)
```
**useUlWrapper**
output html style if true all link inside in ul list or just list of a tags
default true
```php
$paginator->useUlWrapper(false);
```
**refreshHtml**
set created html to null and u can create html again with new settings like a class or without ul wrap
```php
$paginator->useUlWrapper(false);
$htmlWithUlWprap = $paginator->getHtml();
$paginator->refreshHtml()->useUlWrapper(true);
$htmlWithoutUlWprap = $paginator->getHtml();
```

**setLang**
set language. en|ru|arrows. arrows - use "»" instead text. default = 'ru'
```php
$paginator->setLang('en');
```
**setCustomLang**
you can set any lang if you want
```php
// first parameter flrst and last title
// second parametr next and prev titile
$paginator->setCustomLang(['start','end'],['back','forward']);
// you can use string to set both title
$paginator->setCustomLang(['start','end'],'...'); //now next and prev have title '...'
// you can use already setted langs
$paginator->setCustomLang('en','arrows');
```


