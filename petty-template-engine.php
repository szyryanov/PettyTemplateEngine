<?php

class PettyTemplateEngine {

  // '<title> {{ title }} </title>'
  // '{iff}<span class="error">{{ error_text }}</span>{/iff}'

  public $Dictionary = array();
  public $Template = "";
  
  private $names_filled = false;

  public function AddDictionary($dictionary){
    $this->Dictionary = array_merge($this->Dictionary, $dictionary);
  }
  
  public function Render(){
    $result = $this->Template;
    $result = $this->replace_iffs($result);
    $result = $this->replace_names($result);
    return $result;
  }
  
  /*public function IncludeRendered(){
    $result = $this->Render();
    eval("?>" . $result);
  }*/
  
  public static function RenderFile($templatefilename, $dictionary){
    $engine = new PettyTemplateEngine();
    $engine->AddDictionary($dictionary);
    $engine->Template = file_get_contents($templatefilename);
    return $engine->Render();
  }
  public static function RenderString($template, $dictionary){
    $engine = new PettyTemplateEngine();
    $engine->AddDictionary($dictionary);
    $engine->Template = $template;
    return $engine->Render();
  }
  
  /*public static function IncludeRenderedFile($templatefilename, $dictionary){
    $engine = new PettyTemplateEngine();
    $engine->AddDictionary($dictionary);
    $engine->Template = file_get_contents($templatefilename);
    $engine->IncludeRendered();
  }*/
  
  private function replace_names($text){
    $this->names_filled = false;
    //
    return preg_replace_callback(
      '/{{\s*(\w+)\s*}}/s',
      array($this, 'replace_names_callback'),
      $text
    );
  }

  private function replace_names_callback($matches){
    $key = $matches[1];
    if (array_key_exists($key, $this->Dictionary)){
      $value = $this->Dictionary[$key];
      if (($value !== '') && ($value !== NULL)) $this->names_filled = true;
      return $value;
    }
    return $matches[0];
  }

  
  private function replace_iffs($text){
    return preg_replace_callback(
      '/{iff}(.*){\/iff}/sU',
      array($this, 'replace_iffs_callback'),
      $text
    );
  }
  
  private function replace_iffs_callback($matches){
    $content = $this->replace_names($matches[1]);
    if ($this->names_filled){
      return $content;
    }
    return '';
  }
  
 
}

?>
