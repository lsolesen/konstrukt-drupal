<?php
require_once 'konstrukt/konstrukt.inc.php';

class EnglishLanguage implements k_Language {
  function name() {
    return 'English';
  }
  function isoCode() {
    return 'en';
  }
}

class DanishLanguage implements k_Language {
  function name() {
    return 'Danish';
  }
  function isoCode() {
    return 'da';
  }
}

class MyLanguageLoader implements k_LanguageLoader {
  function load(k_Context $context) {
    if($context->query('lang') == 'da') {
      return new DanishLanguage();
    } else if($context->query('lang') == 'en') {
      return new EnglishLanguage();
    }
    return new EnglishLanguage();
  }
}

class SimpleTranslator implements k_Translator {
  protected $phrases;
  function __construct($phrases = array()) {
    $this->phrases = $phrases;
  }
  function translate($phrase, k_Language $language = null) {
    return isset($this->phrases[$phrase]) ? $this->phrases[$phrase] : $phrase;
  }
}

class SimpleTranslatorLoader implements k_TranslatorLoader {
  function load(k_Context $context) {
    // Default to English
    $phrases = array(
      'Hello' => 'Hello Drupal 7',
      'Nice to meet you' => 'Nice to meet you'
    );
    if($context->language()->isoCode() == 'da') {
      $phrases = array(
        'Hello' => 'Hej Drupal 7',
        'Nice to meet you' => 'Dejligt at møde dig'
      );
    }
    return new SimpleTranslator($phrases);
  }
}

class Root extends k_Component {
  function map($name) {
    if($name == 'template') {
      return 'Template';
    }
  }
  function renderHtml() {
    return sprintf("<p>%s<br>%s</p>", $this->translator()->translate('Hello'), $this->translator()->translate('Nice to meet you')) . $this->url('template');
  }
}

class Template extends k_Component {
  function renderHtml() {
    $template = new k_Template(dirname(__FILE__) .'/template.php');
    return $template->render($this);
  }
}

