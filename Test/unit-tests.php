<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>PettyTemplate unit tests</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body>

  <h2>PettyTemplate - test</h2>

  <?php
  require_once(dirname(__FILE__) . '/../../simpletest/autorun.php');

  require_once(dirname(__FILE__) . '/../petty-template-engine.php');

  class TestCases extends UnitTestCase {
      
      function testNames() {     
        $this->doTest('', array(), '');
        $this->doTest('x', array(), 'x');
        $this->doTest('{{x}}', array(), '{{x}}');
        $this->doTest('{{x}}', array(), '{{x}}');
        //
        $this->doTest('{{x}}', array('x' => 'y'), 'y');
        $this->doTest('{{ x}}', array('x' => 'y'), 'y');
        $this->doTest('{{x }}', array('x' => 'y'), 'y');
        $this->doTest('{{ x   }}', array('x' => 'y'), 'y');
        //
        $this->doTest('a{{x }}b', array('x' => 'y'), 'ayb');
        $this->doTest('{{x }}{{x}}', array('x' => 'y'), 'yy');
        $this->doTest('{{x }} {{x}}', array('x' => 'y'), 'y y');
        $this->doTest('{{x1}}', array('x' => 'y'), '{{x1}}');
      }
      
      function testIff() {
        $this->doTest('{iff}{/iff}', array(), '');
        $this->doTest('{iff}xxx{/iff}', array(), '');
        $this->doTest('{iff}xxx{/iff}', array('xxx' => 'YYY'), '');
        //
        $this->doTest('{iff}{{xxx}}{/iff}', array('xxx' => 'YYY'), 'YYY');
        $this->doTest('{iff}<p>{{xxx}}</p>{/iff}', array('xxx' => 'YYY'), '<p>YYY</p>');
        $this->doTest('{iff}<p>{{xxx}}</p>{/iff}', array('xxx' => ''), '');
        $this->doTest('{iff}<p>{{xxx}}</p>{/iff}', array(), '');
        //
        $this->doTest('{iff}<p>{{xxx}}{{aaa}}</p>{/iff}', array('xxx' => '', 'aaa' => ''), '');
        $this->doTest('{iff}<p>{{xxx}}{{aaa}}</p>{/iff}', array('xxx' => 'YYY', 'aaa' => ''), '<p>YYY</p>');
        $this->doTest('{iff}<p>{{xxx}}{{aaa}}</p>{/iff}', array('xxx' => 'YYY', 'aaa' => 'BBB'), '<p>YYYBBB</p>');
        //        
        $this->doTest("{iff}\r\n{{xxx}}\r\n{/iff}", array('xxx' => 'YYY'), "\r\nYYY\r\n");
        $this->doTest("{iff}\r\n{{xxx}}\r\n{/iff}", array('xxx1' => 'YYY'), "");
        //
        $this->doTest('{iff}<p><span class="error">{{ error_text }}</span></p>{/iff}', array('error_text' => 'error'), '<p><span class="error">error</span></p>');
        //
        $this->doTest('{iff}{{xxx}}{/iff}{{xxx}}', array('xxx' => 'YYY'), 'YYYYYY');
        $this->doTest('{iff}{{xxx1}}{/iff}{{xxx}}', array('xxx' => 'YYY'), 'YYY');
        //
        $this->doTest('{iff}1{{xxx}}2{/iff}{iff}3{{yyy}}4{/iff}', array('xxx' => 'XXX', 'yyy' => 'YYY'), '1XXX23YYY4');
        $this->doTest('{iff}1{{xxx}}2{/iff}{iff}3{{yyy}}4{/iff}', array('xxx' => '', 'yyy' => 'YYY'), '3YYY4');
        $this->doTest('{iff}1{{xxx}}2{/iff}{iff}3{{yyy}}4{/iff}', array('xxx' => 'XXX', 'yyy' => ''), '1XXX2');       
        //
        $this->doTest('{iff}1{{xxx}}2{/iff}', array('xxx' => NULL), '');
      }
            
      function doTest($template, $dictionary, $estimated){         
        $tpl = new PettyTemplateEngine();
        $tpl->AddDictionary($dictionary);
        $tpl->Template = $template;
        $result = $tpl->Render();
        //          
        $this->assertEqual($estimated, $result);
      }
     
  }
  ?>
    
</body>
</html>
