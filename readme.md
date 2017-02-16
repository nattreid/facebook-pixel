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
    $this['facebookPixel']->search('searchWord');       // vyhledavani
    $this['facebookPixel']->viewContent(3.5, 'CZK');    // detail
    $this['facebookPixel']->addToCart(3.5, 'CZK');      // pridani do kosiku
    $this['facebookPixel']->addToWishList(3.5, 'CZK');  // pridani do prani
    $this['facebookPixel']->initiateCheckout();         // prechod k zaplaceni nakupu
    $this['facebookPixel']->addPaymentInfo();           // pridani platebnich udaju
    $this['facebookPixel']->purchase(3.5, 'CZK');       // provedeni nakupu
    $this['facebookPixel']->lead();                     // potencialni zakaznik
    $this['facebookPixel']->completeRegistration();     // dokonceni registrace
}
```

v latte
```latte
<html>
<head>
    <!-- html kod -->
    {control facebookPixel}
</head>
<body>
    {control facebookPixel:ajax}
    <!-- html kod -->
</body>
</html>

```
