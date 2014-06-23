<?php

/**
 * Logic layer of table category this object
 * has the basic CRUD functions build-in, for
 * normalized databases tables.
 *
 * @author Gabriel Gonzalez Disla
 */
class bll_category extends portfolio_category {
     
        
   /**
    * Construct the object and initialize its values.
    *
    * @param int $id id of the entity to initialize 
    *
    */
    public function __construct($id) 
    {
        parent::__construct($id);
    }
    
    /**
     * Selects one object from the table depending on which
     * attribute you are looking for.
     *
     * @param string|array $field name of the field to search for delete.
     * when $field is an array. field array(array(fieldname => OP)) when value is
     * the statement field[i] of the value value[i] and OP are 
     * the following operators:
     * Op(=, !=, <>).
     * @param string|array $value value of the field to search for delete.
     * when $value is an array. value array(array(val1 => Glue)) when value is
     * the value[i] of the statement field[i] and GLue are logic operators:
     * Logic(AND, OR).
     * @param  boolean $DESC ascendent
     * @param  string  $order_field Field for the order by
     * @param  integer $lower_limit  lower limit on the query, it must be
     * an integer otherwise is going to be ignored
     * @param  integer $upper_limit higher limit on the query, it must be
     * an integer otherwise is going to be ignored
     * 
     * @return bll_category dbobject or false on failure.
     */
    public function find($field = "", $value = "", $DESC = true, $order_field = "", $lower_limit = null, $upper_limit = null) 
    {
        return parent::find($field, $value, $DESC, $order_field, $lower_limit, $upper_limit);
    }
    
    /**
     * Selects one object from the table depending on which
     * attribute you are looking for.
     *
     * @param string|array $field name of the field to search for delete.
     * when $field is an array. field array(array(fieldname => OP)) when value is
     * the statement field[i] of the value value[i] and OP are 
     * the following operators:
     * Op(=, !=, <>).
     * @param string|array $value value of the field to search for delete.
     * when $value is an array. value array(array(val1 => Glue)) when value is
     * the value[i] of the statement field[i] and GLue are logic operators:
     * Logic(AND, OR).
     * @param  boolean $DESC ascendent
     * @param  string  $order_field Field for the order by
     * @param  integer $lower_limit  lower limit on the query, it must be
     * an integer otherwise is going to be ignored
     * @param  integer $upper_limit higher limit on the query, it must be
     * an integer otherwise is going to be ignored
     * 
     * @return bll_category dbobject or false on failure.
     */
    public function findAll($field = "", $value = "", $DESC = true, $order_field = "", $lower_limit = null, $upper_limit = null) 
    {
        return parent::findAll($field, $value, $DESC, $order_field, $lower_limit, $upper_limit);
    }
    
    /**
     * Delete the object instance in the database
     *
     * @param string|array $field name of the field to search for delete.
     * when $field is an array. field array(array(fieldname => OP)) when value is
     * the statement field[i] of the value value[i] and OP are 
     * the following operators:
     * Op(=, !=, <>).
     * @param string|array $value value of the field to search for delete.
     * when $value is an array. value array(array(val1 => Glue)) when value is
     * the value[i] of the statement field[i] and GLue are logic operators:
     * Logic(AND, OR).
     *
     * @warning if the funtion is used without parameters
     * there`s only a successful delete if the object
     * Id is found in the database.
     *
     * @return boolean|bll_category Not false on success.
     */
    public function delete($field = "", $value = "") 
    {
        return parent::delete($field, $value);
    }
    
    /**
     * Insert the object to the database
     *
     * @return bll_category not false on success.
     */
    public function insert() 
    {
        $this->setAttributes($_POST);
        $obj= parent::insert();
        if($obj !== false)
        {
            $oid = $obj->id;
            //adding lang values
            $this->addLangValue($oid);
            return $this;
        }
        return false;
    }
    
    /**
     *
     * @return bll_category not false on success. 
     */
    public function update()
    {
        $this->setAttributes($_POST);
        $obj= parent::update();
        if($obj !== false)
        {
            $oid = $obj->id;
            //adding lang values
            $this->addLangValue($oid);
            return $this;
        }
        return false;
    }
    
    /**
     * Gets all the works from the category
     * 
     * @param  integer $c_id category to search works for
     * @param  boolean $DESC ascendent
     * @param  string  $order_field Field for the order by
     * @param  integer $lower_limit  lower limit on the query, it must be
     * an integer otherwise is going to be ignored
     * @param  integer $upper_limit higher limit on the query, it must be
     * an integer otherwise is going to be ignored
     * @return array array of works id
     */
    public static function get_works_id($c_id, $DESC = true, 
            $order_field = "", $lower_limit = null, $upper_limit = null)
    {
        $w = new portfolio_work_category(-1);
        $works = $w->findAll('category_id', $c_id, $DESC, $order_field, $lower_limit, $upper_limit);
        return dbobject::convertListToArray($works, 'work_id');
    }
    
    /**
     * Add the language values
     * @param int $id id of the main object 
     */
    private function addLangValue($id)
    {
        $oid = $id;
        $langs = languages::GetLanguages();
        //deleting old values
        $cfl=new portfolio_category_lang(0);
        $cfl->delete('category_id',$id);
        //adding lang values
        foreach($langs as $lang)
        {
            $lang_suffix = "_".$lang->lang_id;
            $lv = new portfolio_category_lang(0);
            $lv->category_id = $oid;
            $lv->name = $_POST['name'.$lang_suffix];
            $lv->alias = $_POST['alias'.$lang_suffix];
            $lv->meta_description = $_POST['meta_description'.$lang_suffix];
            $lv->meta_tags = $_POST['meta_tags'.$lang_suffix];
            $lv->lang_id=$lang->lang_id;
            $lv = $lv->insert();
        }
    }
   
    /**
     * Get the language value
     * @param type $LangId id of the language
     * @return portfolio_category_lang portfolio_category_lang value object.
     */
    public function getLanguageValue($LangId)
    {
        $language = new languages($LangId);
        if($language->lang_id <= 0)
        {
            return new portfolio_category_lang(-1);
        }
        $langval  = new portfolio_category_lang(-1);
        
        return $langval->find(
                              array(
                                  array('category_id','='),
                                  array('lang_id','=')
                                   ), 
                              array(
                                  array($this->id,null),
                                  array($LangId,'AND')
                                  )
                             );
        
    }
    
    /**
     * Get the language value
     * @param type $LangId id of the language
     * @return portfolio_category_lang array of portfolio_category_lang value object.
     */
    public function getLanguageValues()
    {
        $langval  = new portfolio_category_lang(-1);
        return $langval->findAll(
                              array(
                                  array('category_id','=')
                                   ), 
                              array(
                                  array($this->id,null)
                                  )
                             );
   
    }
    
    /**
     * Gets all the works from the category
     * 
     * @param  integer $c_id category to search works for
     * @param  boolean $DESC ascendent
     * @param  string  $order_field Field for the order by
     * @param  integer $lower_limit  lower limit on the query, it must be
     * an integer otherwise is going to be ignored
     * @param  integer $upper_limit higher limit on the query, it must be
     * an integer otherwise is going to be ignored
     * 
     * @return bll_work array of works
     */
    public static function get_works($c_id, $DESC = true, 
            $order_field = "", $lower_limit = null, $upper_limit = null)
    {
        $w = new portfolio_work_category(-1);
        $works = $w->findAll('category_id', $c_id, $DESC, $order_field, $lower_limit, $upper_limit);
        $result = array();
        foreach($works as $work)
        {
            $result[]=new bll_work($work->work_id);
        }
        return $result;
    }
    
}
