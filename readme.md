# Facebook Pixel pro Nette Framework

Nastavení v **config.neon**
```neon
extensions:
    facebookPixel: NAtrreid\FacebookPixel\DI\FacebookPixelExtension

facebookPixel:
    apiKey: 'apiKey'
```

Použití
```php
/** @var NAttreid\FacebookPixel\FacebookPixelFactory @inject */
public $facebookPixelFactory;

protected function createComponentFacebookPixel() {
    return $this->facebookPixelFactory->create();
}

public function someRender(){
    $this['facebookPixel']->search('searchWord');
}
```

v latte
```latte
<html>
<head>
    <!-- html kod -->
    {control facebookPixel}
</head>

```
