# Mati-Core  | FORM

[![Latest Stable Version](https://poser.pugx.org/mati-core/form/v)](//packagist.org/packages/mati-core/form)
[![Total Downloads](https://poser.pugx.org/mati-core/form/downloads)](//packagist.org/packages/mati-core/form)
[![Latest Unstable Version](https://poser.pugx.org/mati-core/form/v/unstable)](//packagist.org/packages/mati-core/form)
[![License](https://poser.pugx.org/mati-core/form/license)](//packagist.org/packages/mati-core/form)

Install
-------

Comoposer command:
```bash
composer require mati-core/form
```

Trait for Presenter
-------------------

```php
final class *Presenter extends BasePresenter
{
    use FormFactoryTrait; 
}
```

Using
-----

```php
final class *Presenter extends BasePresenter{
    
    use FormFactoryTrait;
    
    public function createComponentMyForm(): Form
    {
        $form = $this->formFactory->create(); 
        
        $form->addText('myText', 'My text');
        
        $form->onSuccess[] = function (Form $form, ArrayHash $values): void {
            $text = $values->myText;
        };
        
        return $form;
    }
}
```