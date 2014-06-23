<?php

/**
 * Logic layer of table work this object
 * has the basic CRUD functions build-in, for
 * normalized databases tables.
 *
 * @author Gabriel Gonzalez Disla
 */
class bll_work extends portfolio_work {
     
        
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
     * @return bll_work dbobject or false on failure.
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
     * @return bll_work dbobject or false on failure.
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
     * @return boolean|bll_work Not false on success.
     */
    public function delete($field = "", $value = "") 
    {
        return parent::delete($field, $value);
    }
    
    /**
     * Insert the object to the database
     *
     * @return bll_work not false on success.
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
     * @return bll_work not false on success. 
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
     * Add the language values
     * @param int $id id of the main object 
     */
    private function addLangValue($id)
    {
        $oid = $id;
        $langs = languages::GetLanguages();
        //deleting old values
        $cfl=new portfolio_work_lang(0);
        $cfl->delete('work_id',$id);
        //adding lang values
        foreach($langs as $lang)
        {
            $lang_suffix = "_".$lang->lang_id;
            $lv = new portfolio_work_lang(0);
            $lv->work_id = $oid;
            $lv->name = $_POST['name'.$lang_suffix];
            $lv->alias = $_POST['alias'.$lang_suffix];
            $lv->specs = $_POST['specs'.$lang_suffix];
            $lv->description = $_POST['description'.$lang_suffix];
            $lv->meta_description = $_POST['meta_description'.$lang_suffix];
            $lv->meta_tags = $_POST['meta_tags'.$lang_suffix];
            $lv->lang_id=$lang->lang_id;
            $lv = $lv->insert();
        }
    }
    
    /**
     * Get the language value
     * @param type $LangId id of the language
     * @return portfolio_work_lang portfolio_work_lang value object.
     */
    public function getLanguageValue($LangId)
    {
        $language = new languages($LangId);
        if($language->lang_id <= 0)
        {
            return new portfolio_work_lang(-1);
        }
        $langval  = new portfolio_work_lang(-1);
        
        return $langval->find(
                              array(
                                  array('work_id','='),
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
     * @return portfolio_work_lang array of portfolio_work_lang value object.
     */
    public function getLanguageValues()
    {
        $langval  = new portfolio_work_lang(-1);
        return $langval->findAll(
                              array(
                                  array('work_id','=')
                                   ), 
                              array(
                                  array($this->id,null)
                                  )
                             );
   
    }
    
    
    /**
     * Gets all the images
     * @return portolio_work_images images of the work
     */
    public function get_images()
    {
        $images = new portfolio_work_images(-1);
        return $images->findAll('work_id', $this->id);
    }
    
    public function add_images($images_arr, $index)
    {
        $tmp_image = new portfolio_work_images(-1);
        foreach($this->get_images() as $image)
        {
            $image->delete();
        }
        $i=1;
        foreach($images_arr as $_image=>$thumb)
        {
            $tmp_image->work_id = $this->id;
            $tmp_image->image = $_image;
            $tmp_image->thumb = $thumb;
            if($i == $index)
            {
                $tmp_image->main = 1;
            }
            $tmp_image->insert();
            $i++;
        }
    }
    
    /**
     * Get the main image
     * @return portfolio_work_images|boolean the image false otherwise
     */
    public function get_main_image()
    {
        $images = $this->get_images();
        foreach($images as $image)
        {
            if($image->main == '1')
            {
                return $image;
            }
        }
        return false;
    }
    
    public function set_main_image($img_id)
    {
        $images = $this->get_images();
        foreach($images as $image)
        {
            if($image->id == $img_id)
            {
                $image->main=1;
            }
            else
            {
                $image->main=0;
            }
            $image->update();
        }
    }
    
    /**
     * Gets all the categories from the work
     * 
     * @param  integer $w_id work to search categories for
     * @return array array of categories id
     */
    public static function get_categories_id($w_id)
    {
        $w = new portfolio_work_category(-1);
        $works = $w->findAll('work_id', $w_id);
        return dbobject::convertListToArray($works, 'category_id');
    }
    
    public function add_categories($carr)
    {
        $w = new portfolio_work_category(-1);
        foreach($w->findAll('work_id', $this->id) as $ob)
        {
            $ob->delete();
        }
        foreach($carr as $cid)
        {
            $tmp = new portfolio_work_category(-1);
            $tmp->category_id=$cid;
            $tmp->work_id=$this->id;
            $tmp->insert();
        }
    }
    
    /**
     * Gets all the categories from the work
     * 
     * @param  integer $w_id work to search categories for
     * @return bll_category array of categories
     */
    public static function get_categories($w_id)
    {
        $w = new portfolio_work_category(-1);
        $works = $w->findAll('work_id', $w_id);
        $result = array();
        foreach($works as $work)
        {
            $result[]=new bll_category($work->category_id);
        }
        return $result;
    }
    
}
