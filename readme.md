# Facebook Pixel pro Nette Framework

Nastavení v **config.neon**
```neon
extensions:
    facebookPixel: NAtrreid\FacebookPixel\DI\FacebookPixelExtension

facebookPixel:
    pixelId: 'XXXXXXXXXXXXXXX'
    # nebo vice
    pixelId: 
        - 'XXXXXXXXXXXXXXX'
        - 'XXXXXXXXXXXXXXX'
```

Použití
```php
/** @var NAttreid\FacebookPixel\IFacebookPixelFactory @inject */
public $facebookPixelFactory;

protected function createComponentFacebookPixel() {
    return $this->facebookPixelFactory->create();
}

public function someRender(){
    $this['facebookPixel']->search();               // vyhledavani
    $this['facebookPixel']->viewContent();          // detail
    $this['facebookPixel']->addToCart();            // pridani do kosiku
    $this['facebookPixel']->addToWishList();        // pridani do prani
    $this['facebookPixel']->initiateCheckout();     // prechod k zaplaceni nakupu
    $this['facebookPixel']->addPaymentInfo();       // pridani platebnich udaju
    $this['facebookPixel']->purchase()              // provedeni nakupu
                          ->setValue(5)
                          ->setCurrency('EUR');
    $this['facebookPixel']->lead();                 // potencialni zakaznik
    $this['facebookPixel']->completeRegistration(); // dokonceni registrace
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
