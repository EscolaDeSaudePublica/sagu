<?php
/**
* MAjax - CPAINT Adapted for Miolo
*
* @author     Ely Edison Matos <ely.matos@ufjf.edu.br>
**/
/**
* CPAINT - Cross-Platform Asynchronous INterface Toolkit
*
* http://sf.net/projects/cpaint
*
* released under the terms of the LGPL
* see http://www.fsf.org/licensing/licenses/lgpl.txt for details
*
* @package    CPAINT
* @author     Paul Sullivan <wiley14@gmail.com>
* @author     Dominique Stender <dstender@st-webdevelopment.de>
* @copyright  Copyright (c) 2005-2006 Paul Sullivan, Dominique Stender - http://sf.net/projects/cpaint
* @version    $id: cpaint2.inc.php 311 2006-09-30 08:21:25Z saloon12yrd $
*/

//---- variables ---------------------------------------------------------------
  $GLOBALS['__cpaint_json'] = new JSON();

//---- classes -----------------------------------------------------------------
  /**
  * cpaint base class.
  *
  * @package    CPAINT
  * @access     public
  * @author     Paul Sullivan <wiley14@gmail.com>
  * @author     Dominique Stender <dstender@st-webdevelopment.de>
  * @copyright  Copyright (c) 2005-2006 Paul Sullivan, Dominique Stender - http://sf.net/projects/cpaint
  * @version    2.1.0
  */
  class MAjax extends MService {
    /**
    * version number
    *
    * @access private
    * @var    string $version
    */
    public $version = '2.1.0';

    /**
    * response type.
    *
    * @access   protected
    * @var      string    $response_type
    */
    public $response_type;

    /**
    * the basenode ajaxResponse.
    *
    * @access   protected
    * @var      object    $basenode
    */
    public $basenode;

    /**
    * list of registered methods available through the CPAINT API
    *
    * @access   protected
    * @var      array     $api_functions
    */
    public $api_functions;

    /**
    * list of registered complex types used in the CPAINT API
    *
    * @access protected
    * @var    array $api_datatypes
    */
    public $api_datatypes;

    /**
    * whether or not the CPAINT API generates a WSDL when called with ?wsdl querystring
    *
    * @access private
    * @var    boolean $use_wsdl
    */
    public $use_wsdl;

    /**
    * arguments send by front-end
    *
    * @access private
    * @var    array $arguments
    */
    public $arguments;

    /**
    * user function name
    *
    * @access private
    * @var    string $user_function
    */
    public $user_function;

    /**
    * response.
    *
    * @access   protected
    * @var      string    $response
    */
    public $response = NULL;


    /**
    * PHP4 constructor.
    *
    * @access   public
    * @return   void
    */
    public function cpaint() {
      $this->__construct();
    }

    /**
    * PHP 5 constructor.
    *
    * @access   public
    * @return   void
    * @todo -o"Dominique Stender" -ccpaint implement a better debugging
    */
    public function __construct() {
      parent::__construct();
      // initialize properties
      $this->basenode       = new cpaint_node();
      $this->basenode->set_name('ajaxResponse');
      $this->basenode->set_attribute('id', '');
      $this->basenode->set_encoding('UTF-8');

      $this->response_type  = 'TEXT';
      $this->api_functions  = array();
      $this->api_datatypes  = array();

      $this->response = NULL;

      /* $this->use_wsdl       = true;

      $this->complex_type(array(
          'name'      => 'cpaintResponseType',
          'type'      => 'restriction',   // (restriction|complex|list)
          'base_type' => 'string',        // scalar type of all values, e.g. 'string', for type = (restriction|list) only
          'values'    => array(           // for type = 'restriction' only: list of allowed values
            'XML', 'TEXT', 'OBJECT', 'E4X', 'JSON',
          ),
        )
      );
      $this->complex_type(array(
          'name'      => 'cpaintDebugLevel',
          'type'      => 'restriction',
          'base_type' => 'long',
          'values'    => array(
            -1, 0, 1, 2
          ),
        )
      );
      $this->complex_type(array(
          'name'      => 'cpaintDebugMessage',
          'type'      => 'list',
          'base_type' => 'string',
        )
      );

      $this->complex_type(array(
          'name'      => 'cpaintRequestHead',
          'type'      => 'complex',
          'struct'    => array(
            0 => array('name' => 'functionName',  'type' => 'string'),
            1 => array('name' => 'responseType',  'type' => 'cpaintResponseType'),
            2 => array('name' => 'debugLevel',    'type' => 'cpaintDebugLevel'),
          ),
        )
      );

      // $this->complex_type(array(
          'name'      => 'cpaintResponseHead',
          'type'      => 'complex',
          'struct'    => array(
            0 => array('name' => 'success',  'type' => 'boolean'),
            1 => array('name' => 'debugger', 'type' => 'cpaintDebugMessage'),
          ),
        )
      );
      */
      // determine response type
      if (isset($_REQUEST['cpaint_response_type'])) {
        $this->response_type = htmlentities(strip_tags(strtoupper((string) $_REQUEST['cpaint_response_type'])));
      } // end: if
    }

    /**
    * initialize arguments and user_function
    *
    * @access   public
    * @param    string      $input_encoding         input data character encoding, default is UTF-8
    * @return   void
    */
    public function initialize($input_encoding = 'UTF-8') {
      $this->user_function  = '';
      $this->arguments      = array();

      // work only if there is no API version request
      if (!isset($_REQUEST['api_query'])
        && !isset($_REQUEST['wsdl'])) {
        $this->basenode->set_encoding($input_encoding);

        if ($_REQUEST['cpaint_function'] != '') {
          $this->user_function  = $_REQUEST['cpaint_function'];
		  if (isset($_REQUEST['cpaint_argument'])) {
	          $this->arguments      = $_REQUEST['cpaint_argument'];
		  }
        }

        // perform character conversion on every argument
        foreach ($this->arguments as $key => $value) {
          if (get_magic_quotes_gpc() == true) {
            $value = stripslashes($value);
          } // end: if

          // convert from JSON string if it is an object or an array
          if (preg_match('/^(\[|\{).*(\]|\})$/', $value)) {
            $this->arguments[$key] = $GLOBALS['__cpaint_json']->parse($value);
          } // end: if
        } // end: foreach

$this->manager->trace('ajax:initialize: ' . $this->basenode->get_encoding());
//        $this->arguments = cpaint_transformer::decode_array($this->arguments, $this->basenode->get_encoding());

        $_REQUEST = cpaint_transformer::decode_array($_REQUEST, $this->basenode->get_encoding());

      } // end: if
    }


    /**
    * calls the user function responsible for this specific call.
    *
    * @access   public
    * @param    string      $input_encoding         input data character encoding, default is UTF-8
    * @return   void
    */
    public function start($input_encoding = 'UTF-8') {

      // work only if there is no API version request
      if (!isset($_REQUEST['api_query'])
        && !isset($_REQUEST['wsdl'])) {
        if (is_array($this->api_functions[$this->user_function])
          && is_callable($this->api_functions[$this->user_function]['call'])) {
          // a valid API function is to be called
          call_user_func_array($this->api_functions[$this->user_function]['call'], $this->arguments);

        } else if ($this->user_function != '') {
          // desired function is not registered as API function
          $this->basenode->set_data('[CPAINT] A function name was passed that is not allowed to execute on this server.');
        }
      } // end: if
    }

    /**
    * generates and prints the response based on response type supplied by the frontend.
    *
    * @access  public
    * @return  void
    */
    public function return_data() {
      // send appropriate headers to avoid caching
      header('Expires: Fri, 14 Mar 1980 20:53:00 GMT');
      header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
      header('Cache-Control: no-cache, must-revalidate');
      header('Pragma: no-cache');
      header('X-Powered-By: CPAINT v' . $this->version . '/PHP v' . phpversion());

      // work only if there is no API version request

//$this->manager->trace('encoding: ' . $this->basenode->get_encoding());
      if (!isset($_REQUEST['api_query'])
        /* && !isset($_REQUEST['wsdl']) */) {
        // trigger generation of response
        switch (trim($this->response_type)) {

          case 'TEXT':
            header('Content-type: text/plain; charset=' . cpaint_transformer::find_output_charset($this->basenode->get_encoding()));
//            echo cpaint_transformer::toString($this->basenode);
            $a = cpaint_transformer::toString($this->basenode);
            echo $a;
            break;

          case 'JSON':
          case 'OBJECT':
            header('Content-type: text/plain; charset=' . cpaint_transformer::find_output_charset($this->basenode->get_encoding()));
//            $this->manager->trace('momami  = '.$this->basenode->get_encoding());
            echo cpaint_transformer::toJSON($this->basenode);
            break;

          case 'E4X':
          case 'XML':
            header('Content-type:  text/xml; charset=' . cpaint_transformer::find_output_charset($this->basenode->get_encoding()));
            $a = '<?xml version="1.0" encoding="' . cpaint_transformer::find_output_charset($this->basenode->get_encoding()) . '"?>'
                . cpaint_transformer::toXML($this->basenode);
            $this->manager->trace('momami  = '.$a);
            echo $a;
            break;

          default:
            echo 'ERROR: invalid response type \'' . $this->response_type . '\'';
        } // end: switch

      } elseif (isset($_REQUEST['api_query'])) {
        // API version request
        header('Content-type: text/plain; charset=ISO-8859-1');
        echo 'CPAINT v' . $this->version . '/PHP v' . phpversion();
/*
      } elseif ($this->use_wsdl == true
        && isset($_REQUEST['wsdl'])) {

        if (is_file(dirname(__FILE__) . '/cpaint2.wsdl.php')
          && is_readable(dirname(__FILE__) . '/cpaint2.wsdl.php')) {

          require_once(dirname(__FILE__) . '/cpaint2.wsdl.php');

          if (class_exists('cpaint_wsdl')) {
            // create new instance of WSDL library
            $wsdl = new cpaint_wsdl();

            // build WSDL info
            header('Content-type: text/xml; charset=UTF-8');
            echo $wsdl->generate($this->api_functions, $this->api_datatypes);

          } else {
            header('Content-type: text/plain; charset=ISO-8859-1');
            echo 'WSDL generator is unavailable';
          } // end: if

        } else {
          header('Content-type: text/plain; charset=ISO-8859-1');
          echo 'WSDL generator is unavailable';
        } // end: if
*/
      } // end: if
    }

    /**
    * registers a new function or method as part of the CPAINT API
    *
    * @access     public
    * @param      mixed     $func     function name, array(&$object, 'function_name') or array('class', 'function_name')
    * @param      string    $alias    alias name for the function. Will be used by the frontend.
    * @param      array     $input    function input parameters (not yet used by CPAINT and subject to change)
    * @param      array     $output   function output format (not yed used by CPAINT and subject to change)
    * @param      string    $comment  description of the functionality
    * @return     boolean
    * @version    1.0.1     16.09.2006 23:07:44    [dstender]  Added alias functionality.
    */
    public function register($func, $alias = '', $input = array(), $output = array(), $comment = '') {
      $return_value = false;
      $alias        = (string)  $alias;
      $input        = (array)   $input;
      $output       = (array)   $output;
      $comment      = (string)  $comment;
      $callable     = '';

      if (is_array($func)
        && (is_object($func[0]) || is_string($func[0]))
        && is_string($func[1])
        && is_callable($func)) {

        // set correct function name if alias is used.
        if ($alias != '') {
          $callable = $alias;

        } else {
          $callable = $func[1];
        } // end: if

        // calling a method of an object
        $this->api_functions[$callable] = array(
          'call'    => $func,
          'input'   => $input,
          'output'  => $output,
          'comment' => $comment,
        );
        $return_value = true;

      } elseif (is_string($func)) {

        // set correct function name if alias is used.
        if ($alias != '') {
          $callable = $alias;

        } else {
          $callable = $func;
        } // end: if

        // calling a standalone function
        $this->api_functions[$callable] = array(
          'call'    => $func,
          'input'   => $input,
          'output'  => $output,
          'comment' => $comment,
        );
        $return_value = true;
      } // end: if

      return $return_value;
    }



    /**
    * unregisters a function that is currently part of the CPAINT API.
    *
    * proves useful when the same set of functions is to be used in the
    * frontend and in some kind of administration environment. you might
    * want to unregister a few (admin) functions for the frontend in this
    * case.
    *
    * Note that if you supplied an alias when registering a method, you must
    * use the alias to unregister the function / method.
    *
    * @access     public
    * @param      string     $func     function name
    * @return     boolean
    * @see        cpaint:register()
    * @version    1.0.1    16.09.2006 23:10:58    [dstender]  Extended PHPDoc for use of aliases.
    */
    public function unregister($func) {
      $retval = false;

      if (is_array($this->api_functions[$func])) {
        unset($this->api_functions[$func]);
      } // end: if

      return $retval;
    }



    /**
    * registers a complex data type
    *
    * @access public
    * @param  array     $schema     schema definition for the complex type
    * @return boolean

    public function complex_type($schema) {
      $return_value = false;
      $schema       = (array)   $schema;

      if ($schema['name'] != ''
        && in_array($schema['type'], array('restriction', 'complex', 'list'))) {

        $this->api_datatypes[] = $schema;
        $return_value          = true;
      } // end: if

      return $return_value;
    }
    */
    /**
    * switches the generation of WSDL on/off. default is on
    *
    * @access public
    * @param  boolean   $state      state of WSDL generation
    * @return void

    public function use_wsdl($state) {
      $this->use_wsdl = (boolean) $state;
    }
    */
    /**
    * adds a new subnode to the basenode.
    *
    * will return a reference to it for further processing.
    *
    * @access   public
    * @param    string    $nodename     name of the new node
    * @param    string    $id           id of the new node
    * @return   object
    */
    public function &add_node($nodename, $id = '') {
      return $this->basenode->add_node($nodename, $id);
    }

    /**
    * assigns textual data to the basenode.
    *
    * @access   public
    * @param    mixed    $data    data to assign to this node
    * @return   void
    */
    public function set_data($data) {
      $this->basenode->set_data($data);
    }

    /**
    * returns the data assigned to the basenode.
    *
    * @access   public
    * @return   mixed
    */
    public function get_data() {
      return $this->basenode->get_data();
    }

    /**
    * sets the id property of the basenode.
    *
    * @deprecated   deprecated since version 2.0.0
    * @access       public
    * @param        string    $id      the id
    * @return       void
    */
    public function set_id($id) {
      $this->basenode->set_attribute('id', $id);
    }

    /**
    * gets the id property of the basenode.
    *
    * @deprecated   deprecated since version 2.0.0
    * @access       public
    * @return       string
    */
    public function get_id() {
      return $this->basenode->get_attribute('id');
    }

    /**
    * adds a new attribute to the basenode.
    *
    * @access   public
    * @param    string    $name       attribute name
    * @param    mixed     $value      attribute value
    * @return   void
    */
    public function set_attribute($name, $value) {
      $this->basenode->set_attribute($name, $value);
    }

    /**
    * retrieves an attribute of the basenode by name.
    *
    * @access   public
    * @param    string    $name       attribute name
    * @return   string
    */
    public function get_attribute($name) {
      return $this->basenode->get_attributes($name);
    }

    /**
    * set name property of the basenode.
    *
    * @access   public
    * @param    string    $name   the name
    * @return   void
    */
    public function set_name($name) {
      $this->basenode->set_name($name);
    }

    /**
    * get name property of the basenode.
    *
    * @access   public
    * @return   string
    */
    public function get_name() {
      return $this->basenode->get_name();
    }



    /**
    * returns the response type as requested by the client
    *
    * @access public
    * @return string
    */
    public function get_response_type() 
    {
      return $this->response_type;
    }

    /**
    * prepare response object
    *
    * @access   public
    * @return   string
    */

    public function isEmpty() 
    {
      return (count($this->basenode->composites) == 0) && ($this->get_data() == '');
    }

    public function setResponseControls($controls, $elements='', $generateFormLayout=false)
    {
        MUtil::SetIfNull($this->response, new stdClass());
        if ( !is_array($controls) )
        {
            $controls = array( $controls );
        }
        if ( is_array($elements) )
        {
            foreach ( $controls as $control )
            {
                if ( $generateFormLayout )
                {
                    $control = MForm::generateLayoutField($control);
                }
                $this->response->html[] = $this->manager->getPage()->painter->generateToString($control);
            }
            foreach ( $elements as $element )
            {
                $this->response->element[] = $element;

                if ( $element != '__mainForm' )
                {
                    if ( $this->manager->checkMobile() )
                    {
                        $this->manager->getPage()->onload("dojox.mobile.parser.parse(dojo.byId('$element'));");
                    }
                    else
                    {
                        $this->manager->getPage()->onload("dojo.parser.parse('$element');");
                    }
                }
            }
        }
        else
        {
            if ( $generateFormLayout )
            {
                foreach ( $controls as $key => $control )
                {
                    $controls[$key] = MForm::generateLayoutField($control);
                }
            }
            $this->response->html[] = $this->manager->getPage()->painter->generateToString($controls);
            $this->response->element[] = $elements;

            if ( $this->manager->checkMobile() )
            {
                $this->manager->getPage()->onload("dojox.mobile.parser.parse('$elements');");
            }
            else
            {
                $this->manager->getPage()->onload("dojo.parser.parse('$elements');");
            }
        }
    }
    
    public function setResponseScripts($scripts=array()) 
    {
      MUtil::SetIfNull($this->response,new stdClass());
      $this->response->scripts = $scripts;
    }
    
    public function setResponseForm($form='') 
    {
      MUtil::SetIfNull($this->response,new stdClass());
      $this->response->form = $form;
    }

    public function setResponse($controls,$elements='',$scripts=array(), $form='') 
    {
      $this->setResponseControls($controls,$elements);
      $this->setResponseScripts($scripts);
      $this->setResponseForm($form);
    }

  }

  /**
  * a cpaint data node. Data nodes are used to build up the response.
  *
  * @package   CPAINT
  * @access    public
  * @author    Dominique Stender <dstender@st-webdevelopment.de>
  * @copyright 2005-2006 (Dominique Stender); All rights reserved
  * @version   2.1.0
  */
  class cpaint_node {
    /**
    * array of subnodes.
    *
    * @access   public
    * @var      array     $composites
    */
    public $composites;

    /**
    * node attributes.
    *
    * @access   public
    * @var      array     $attributes
    */
    public $attributes;

    /**
    * name of this node.
    *
    * @access   public
    * @var      string    $nodename
    */
    public $nodename;

    /**
    * textual data of this node.
    *
    * @access   public
    * @var      string    $data
    */
    public $data;

    /**
    * character encoding for input data
    *
    * @access   private
    * @var      $input_encoding
    */
    public $input_encoding;

    /**
    * PHP4 constructor.
    *
    * @package  CPAINT
    * @access   public
    * @return   void
    */
    public function cpaint_node() {
      $this->__construct();
    }

    /**
    * PHP 5 constructor.
    *
    * @access   public
    * @return   void
    */
    public function __construct() {
      // initialize properties
      $this->composites     = array();
      $this->attributes     = array();
      $this->data           = '';

      $this->set_encoding('UTF-8');
      $this->set_name('');
      $this->set_attribute('id', '');
    }

    /**
    * adds a new subnode to this node.
    *
    * will return a reference to it for further processing.
    *
    * @access   public
    * @param    string    $nodename     name of the new node
    * @param    string    $id           id of the new node
    * @return   object
    */
    public function &add_node($nodename, $id = '') {
      $composites = count($this->composites);

      // create new node
      $this->composites[$composites] =& new cpaint_node();
      $this->composites[$composites]->set_name($nodename);
      $this->composites[$composites]->set_attribute('id', $id);
      $this->composites[$composites]->set_encoding($this->input_encoding);

      return $this->composites[$composites];
    }

    /**
    * assigns textual data to this node.
    *
    * @access   public
    * @param    mixed    $data    data to assign to this node
    * @return   void
    */
    public function set_data($data) {
      $this->data = $data;
    }

    /**
    * returns the textual data assigned to this node.
    *
    * @access   public
    * @return   mixed
    */
    public function get_data() {
      return $this->data;
    }

    /**
    * sets the id property of this node.
    *
    * @deprecated   deprecated since version 2.0.0
    * @access       public
    * @param        string    id      the id
    * @return       void
    */
    public function set_id($id) {
      if ($id != '') {
        $this->set_attribute('id', $id);
      } // end: if
    }

    /**
    * returns the id property if this node.
    *
    * @deprecated   deprecated since version 2.0.0
    * @access       public
    * @return       string
    */
    public function get_id() {
      return $this->get_attribute('id');
    }

    /**
    * adds a new attribute to this node.
    *
    * @access   public
    * @param    string    $name       attribute name
    * @param    mixed     $value      attribute value
    * @return   void
    */
    public function set_attribute($name, $value) {
      $this->attributes[$name] = (string) $value;
    }

    /**
    * retrieves an attribute by name.
    *
    * @access   public
    * @param    string    $name       attribute name
    * @return   string
    */
    public function get_attribute($name) {
      return $this->attributes[$name];
    }

    /**
    * set name property.
    *
    * @access   public
    * @param    string    $name   the name
    * @return   void
    */
    public function set_name($name) {
      $this->nodename = (string) $name;
    }

    /**
    * get name property.
    *
    * @access   public
    * @return   string
    */
    public function get_name() {
      return $this->nodename;
    }

    /**
    * sets the character encoding for this node
    *
    * @access   public
    * @param    string      $encoding     character encoding
    * @return   void
    */
    public function set_encoding($encoding) {
      $this->input_encoding = strtoupper((string) $encoding);
    }

    /**
    * returns the character encoding for this node
    *
    * @access   public
    * @return   string
    */
    public function get_encoding() {
      return $this->input_encoding;
    }
  }

  /**
  * static class of output transformers.
  *
  * @package   CPAINT
  * @access    public
  * @author    Dominique Stender <dstender@st-webdevelopment.de>
  * @copyright 2003-2006 (Dominique Stender); All rights reserved
  * @version   2.1.0
  */
  class cpaint_transformer {
    /**
    * toString method, used to generate response of type TEXT.
    * will perform character transformation according to parameters.
    *
    * @access   public
    * @param    object    $node               a cpaint_node object
    * @return   string
    */
    public function toString(&$node) {
      $return_value = '';

      foreach ($node->composites as $composite) {
        $return_value .= cpaint_transformer::toString($composite);
      }

      $return_value .= cpaint_transformer::encode($node->get_data(), $node->get_encoding());

      return $return_value;
    }

    /**
    * XML response generator.
    * will perform character transformation according to parameters.
    *
    * @access   public
    * @param    object    $node               a cpaint_node object
    * @return   string
    */
    public function toXML(&$node) {
      $return_value = '<' . $node->get_name();

      // handle attributes
      foreach ($node->attributes as $name => $value) {
        if ($value != '') {
          $return_value .= ' ' . $name . '="' . $node->get_attribute($name) . '"';
        }
      } // end: foreach

      $return_value .= '>';

      // handle subnodes
      foreach ($node->composites as $composite) {
        $return_value .= cpaint_transformer::toXML($composite);
      }

      $return_value .= cpaint_transformer::encode($node->get_data(), $node->get_encoding())
                    . '</' . $node->get_name() . '>';

      return $return_value;
    }

    /**
    * JSON response generator.
    * will perform character transformation according to parameters.
    *
    * @access   public
    * @param    object    $node               a cpaint_node object
    * @return   string
    */
    public function toJSON($node) {
      $return_value = '';
      $JSON_node    = new stdClass();

      // handle attributes
      $JSON_node->attributes = $node->attributes;

      // handle subnodes
      foreach ($node->composites as $composite) {

        if (!is_array($JSON_node->{$composite->nodename})) {
          $JSON_node->{$composite->nodename} = array();
        } // end: if

        // we need to parse the JSON object again to avoid multiple encoding
        $JSON_node->{$composite->nodename}[] = $GLOBALS['__cpaint_json']->parse(cpaint_transformer::toJSON($composite));
      }

      // handle data
      $JSON_node->data = $node->data;

//      return $GLOBALS['__cpaint_json']->stringify($JSON_node);

      $MIOLO = MIOLO::getInstance();
      if ( $MIOLO->getConf('options.json_encode') == 'php' )
      {
          $return_value = json_encode($JSON_node);
      }
      else
      {
          $return_value = $GLOBALS['__cpaint_json']->stringify($JSON_node);
      }

      $return_value = cpaint_transformer::encode($return_value, $node->get_encoding());
      return $return_value;

    }

    public function detectUTF8($string)
    {
        return preg_match('%(?:
        [\xC2-\xDF][\x80-\xBF]        # non-overlong 2-byte
        |\xE0[\xA0-\xBF][\x80-\xBF]               # excluding overlongs
        |[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}      # straight 3-byte
        |\xED[\x80-\x9F][\x80-\xBF]               # excluding surrogates
        |\xF0[\x90-\xBF][\x80-\xBF]{2}    # planes 1-3
        |[\xF1-\xF3][\x80-\xBF]{3}                  # planes 4-15
        |\xF4[\x80-\x8F][\x80-\xBF]{2}    # plane 16
        )+%xs', $string);
    }
    /**
    * performs conversion to JavaScript-safe UTF-8 characters
    *
    * @access   public
    * @param    string    $data         data to convert
    * @param    string    $encoding     character encoding
    * @return   string
    */
    public function encode($data, $encoding) {
      // convert string
      if (function_exists('iconv')) {
        // iconv is by far the most flexible approach, try this first
        $return_value = iconv($encoding, 'UTF-8', $data);

      } elseif ($encoding == 'ISO-8859-1') {
        // for ISO-8859-1 we can use utf8-encode()
        $return_value = utf8_encode($data);

      } else {
        // give up. if UTF-8 data was supplied everything is fine!
        $return_value = $data;
      } /* end: if */

      // now encode non-printable characters
//      for ($i = 0; $i < 32; $i++) {
//        $return_value = str_replace(chr($i), '\u00' . sprintf('%02x', $i), $return_value);
//      } // end: for

      // encode <, >, and & respectively for XML sanity
//      $return_value = str_replace(chr(0x26), '\u0026', $return_value);
//      $return_value = str_replace(chr(0x3c), '\u003c', $return_value);
//      $return_value = str_replace(chr(0x3e), '\u003e', $return_value);

      return $return_value;
    }

    /**
    * performs conversion from JavaScript encodeURIComponent() string (UTF-8) to
    * the charset in use.
    *
    * @access   public
    * @param    string    $data         data to convert
    * @param    string    $encoding     character encoding
    * @return   string
    */
    public function decode($data, $encoding) {
      // convert string

      if (is_string($data)) {
        if (!cpaint_transformer::detectUTF8($data))
        {
          $return_value = $data;
        }
        elseif (function_exists('iconv')) {
          // iconv is by far the most flexible approach, try this first
          $return_value = iconv('UTF-8', $encoding, $data);

        } elseif ($encoding == 'ISO-8859-1') {
          // for ISO-8859-1 we can use utf8-decode()
          $return_value = utf8_decode($data);

        } else {
          // give up. if data was supplied in the correct format everything is fine!
          $return_value = $data;
        } // end: if

      } else {
        // non-string value
        $return_value = $data;
      } // end: if

      return $return_value;
    }

    /**
    * decodes a (nested) array of data from UTF-8 into the configured character set
    *
    * @access   public
    * @param    array     $data         data to convert
    * @param    string    $encoding     character encoding
    * @return   array
    */
    public function decode_array($data, $encoding) {
      $return_value = array();

      foreach ($data as $key => $value) {

        if (!is_array($value)) {
          $return_value[$key] = cpaint_transformer::decode($value, $encoding);

        } else {
          $return_value[$key] = cpaint_transformer::decode_array($value, $encoding);
        }
      }

      return $return_value;
    }

    /**
    * determines the output character set
    * based on input character set
    *
    * @access   public
    * @param    string    $encoding     character encoding
    * @return   string
    */
    public function find_output_charset($encoding) {
      $return_value = 'UTF-8';

      if (function_exists('iconv')
        || $encoding == 'UTF-8'
        || $encoding == 'ISO-8859-1') {

        $return_value = 'UTF-8';

      } else {
        $return_value = $encoding;
      } // end: if

      return $return_value;
    }
  }

?>
