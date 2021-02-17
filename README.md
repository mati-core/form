# Mati-Core  | FORM

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