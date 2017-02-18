# PettyTemplateEngine

Simple and small php template engine.

## Template samples

    <title> {{title}} </title>
    {iff}<span class="error">{{error_text}}</span>{/iff}

## Syntax

`{{name}}` - render "name" content.

`{iff}...{{name1}}..{{name2}}...{/iff}` - "if filled". If some internal part (e.g. {{name1}}) is filled, then all the text will be rendered. If all the {{name}} parts are empty strings, then empty string will be rendered.

The engine is **case sensitive**! 

## How to use

1. Copy `petty-template-engine.php` to your code directory.
2. `require_once(dirname(__FILE__) . '/petty-template-engine.php');`
3. Follow the usage samples.


## Usage samples

### Use as class object:

      $te = new PettyTemplateEngine();
      $te->AddDictionary(array(
         'title' => 'My page header',
         'error_text' => 'Something went wrong',
      ));
      $te->Template = file_get_contents('form.tpl');
      echo $te->Render();
      
### Use RenderString() static method:       

      echo PettyTemplateEngine::RenderString(file_get_contents('form.tpl'), array(
         'title' => 'My page header',
         'error_text' => 'Something went wrong',
      ));

### Use RenderFile() static method:       

      echo PettyTemplateEngine::RenderFile('form.tpl', array(
         'title' => 'My page header',
         'error_text' => 'Something went wrong',
      ));

### Include a rendered template to php code:

      $form_html = PettyTemplateEngine::RenderFile('form.tpl', array(...));
      eval("?>" . $form_html);

