<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 /**
  * Template Codeigniter it's a 
  * library to render layout views.
  * This template builder is like Laravel 
  * blade, but is not equal.
  *
  * @package  Template 
  * @author  Thiago <thiagolima86@gmail.com>
  * @version 1.0
  * @category Library
  * @url https://github.com/thiagolima86/template-codeigniter
  */
class View {
    private $template_data = array();
    private $layout;
    private $session;
    private $content;
    private $joinLayout;
    private $with;
    
    
    /**
    * Get is a public method. Is method called in controller
    *
    * @param string $view is url path in codeigniter
    * @param array $array array will be transformed in variable
    * @return false
    */    
    public function get($view, $array=array()){
        $this->CI =& get_instance();
        $document = $this->CI->load->view($view, array(), TRUE);
        /*Conversões*/
        
        $this->_compilerContent($document);
        $this->_compilerLayout();
        $this->_joinLayout();
        
        foreach($this->sessions as $campo=>$valor){ $$campo = $valor; }
        foreach($array as $campo=>$valor){ $$campo = $valor; }
        
        echo eval("?>".$this->joinLayout);
        return false;
    }
    
    /**
    * Compiler the content
    *
    * @param string $document is string content
    * @return null
    */
    private function _compilerContent($document){
        $this->content = $this->_compiler($document);
    }
    
     /*
    * Compila o layout
    * @param string $documento Entra todo o documento
    * @return string Sai todo o documento compilado
    */
    private function _compilerLayout(){
        $this->CI =& get_instance();
        
        //pega layout
        $layoutPattern = "/\@layout\((.*)\)/";
        preg_match($layoutPattern, $this->content, $layout);
        $this->content = preg_replace($layoutPattern, '', $this->content);
        
//        pega sessions
        $sessionPattern = "/\@session\((.*):(.*)\)/";
        preg_match_all($sessionPattern, $this->content, $sessions);
        $this->content = preg_replace($sessionPattern, '', $this->content);
        $this->sessions = array_combine($sessions[1], $sessions[2]);
        
        
        
//        print_r($sessions);
        $layout = $this->CI->load->view($layout[1], array(), TRUE);
        
        $this->layout = $this->_compiler($layout);
        return false;
        
    }
    
    private function _joinLayout(){
        
        $this->joinLayout = preg_replace('/\@content/', $this->content, $this->layout);
        
    }
    
    /*
    * Compila o chaves do documento
    * @param string $documento Entra todo o documento
    * @return string Sai todo o documento compilado
    */
    private function _compiler($document){
        
        /* Converte as chaves {{$a}} em <?php echo $a;?> */      
        $document = preg_replace('/\{\{(.+?)\}\}/', '<?php echo $1; ?>', $document);
        
        /* Converte as os laços @if @else @endif @foreach */
        $document = preg_replace('/(\s*)@(if|elseif|foreach|for|while)(\s*\(.*\))/', '$1<?php $2$3: ?>', $document);
        $document = preg_replace('/(\s*)@(endif|endforeach|endfor|endwhile)(\s*)/', '$1<?php $2; ?>$3', $document);
        $document = preg_replace('/(\s*)@(else)(\s*)/', '$1<?php $2: ?>$3', $document);
        
        /* converte @{$a="teste"} em <?php $a = "teste" ?> */
        $document = preg_replace('/\@var\{(.*):(.*)\}/', '<?php $$1 = $2; ?>', $document);
         /* converte @{$a="teste"} em <?php $a = "teste" ?> */
/*
        $document = preg_replace('/\@\{(.\s*)\}/', '<?php $1 ?>', $document);
*/
        
        /* converte @css(url/url) links externos */
        $document = preg_replace('/\@css\(http(.*)\)/', '<link rel="stylesheet" href="http$1">', $document);
         /* converte @css(url/url) links internos */
        $document = preg_replace('/\@css\((.*)\)/', '<link rel="stylesheet" href="<?php echo base_url("$1"); ?>">', $document);
         /* converte @script(url/url) links externos */
        $document = preg_replace('/\@script\(http(.*)\)/', '<script src="http$1"></script>', $document);
         /* converte @script(url/url) links internos */
        $document = preg_replace('/\@script\((.*)\)/', '<script src="$1"></script>', $document);
         
        
        return $document;
        
    }

}