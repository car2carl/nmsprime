<?php

namespace Acme\html;

use Collective\Html\FormBuilder as CollectiveFormBuilder;
use Session;
use Log;

class FormBuilder extends CollectiveFormBuilder {

    private static $layout_form_col_md = ['label'=>4, 'form'=>7, 'help'=>1];


    /**
     * An array containing the currently opened form groups.
     *
     * @var array
     */
    protected $groupStack = array();


    public static function get_layout_form_col_md()
    {
        return static::$layout_form_col_md;
    }


	/**
     * Append <div> block with col-md-7
     * NOTE: 4: col for label, 7: col for form field, 1: col for help image - if set
     */
    public function appendDiv($s, $col = 7)
    {
        if (isset(static::$layout_form_col_md['form']))
            $col = static::$layout_form_col_md['form'];

    	return '<div class="col-md-'.$col.'">'.$s.'</div>';
    }


    /**
     * Append the given class to the given options array.
     *
     * @param $class: string to add to html class
     * @param $options: options array
     *        NOTE: use '!class' to avoid adding $class variable from FormBuilder functions,
     *              instead set only this proposed classes
     * @return: options array with (manipulated) class key
     */
    private function appendClassToOptions($class, $options = array())
    {
        // If a 'class' is already specified, append the 'form-control'
        // class to it. Otherwise, set the 'class' to 'form-control'.
        $options['class'] = isset($options['class']) ? $options['class'].' ' : '';
        $options['class'] .= $class;

        if (isset($options['!class']))
            $options['class'] = $options['!class'];

        return $options;
    }


    /**
     * Create a form input field.
     */
    public function input($type, $name, $value = null, $options = array())
    {
        if($type == 'hidden')
            return parent::input($type, $name, $value, $options);

        // these 2 lines were moved before $options assignment -> in simple form there's no form-control class added - needed for Configfile index view
        if (isset($options['style']) && $options['style'] == 'simple')
            return parent::input($type, $name, $value, $options);

        $options = $this->appendClassToOptions('form-control', $options);

        // Call the parent input method so that Laravel can handle
        // the rest of the input set up.
        return $this->appendDiv(parent::input($type, $name, $value, $options));
    }


    /**
     * Create a form input field
     */
    public function label($name, $value = null, $options = array())
    {
        $col = 4;
        if (isset(static::$layout_form_col_md['label']))
            $col = static::$layout_form_col_md['label'];

        $options = $this->appendClassToOptions('col-md-'.$col.' control-label', $options);

        // translate the value if necessary
        // $bc = new \App\Http\Controllers\BaseController;
        // $value = $bc->translate($value);
        $value = \App\Http\Controllers\BaseViewController::translate($value);

        // Call the parent input method so that Laravel can handle
        // the rest of the input set up.
        return parent::label($name, $value, $options);
    }


    /**
     * Create a form submit button.
     */
    public function submit($value = NULL, $options = array())
    {
        $options = $this->appendClassToOptions('btn btn-primary', $options);

        $value = \App\Http\Controllers\BaseViewController::translate($value);

        if (isset($options['style']) && $options['style'] == 'simple')
            $s = parent::submit($value, $options);
        else
        {
            $options['style'] = 'simple'; // style: required to auto width button to text length
            $s = '<div class="col-md-12">
                    <div class="col-md-3"></div>
                    <div class="col-md-6"><br>'.
                        parent::submit($value, $options).
                    '</div></div>';
        }

        // Call the parent input method so that Laravel can handle
        // the rest of the input set up.
        return $s;
    }


    /**
     * Create a form model field.
     */
    public function model($model, array $options = array(), $style = 'simple')
    {
        $options = $this->appendClassToOptions('form-group form-horizontal', $options);
      	if (!isset ($options['method']))
      		$options['method'] = 'put';

        $fill = '';
        if ($style == 'advanced')
            $fill = '<br>';

        return parent::model($model, $options).$fill;
    }


    /**
     * Create a select box field.
     */
    public function select($name, $list = array(), $selected = null, $options = array())
    {
        $options = $this->appendClassToOptions('form-control', $options);

        // Call the parent select method so that Laravel can handle
        // the rest of the select set up.
        return $this->appendDiv(parent::select($name, $list, $selected, $options));
    }

    /**
     * Create a checkbox input field.
     */
    public function checkbox($name, $value = 1, $label = null, $checked = null, $options = array())
    {
        $options['align'] = 'left';
        $options['class'] = '';
        $checkable = parent::checkbox($name, $value, $checked, $options);

        if (isset($options['style']) && $options['style'] == 'simple')
            return $checkable;

        return $this->appendDiv($checkable);
        // return $this->wrapCheckable($label, 'checkbox', $checkable);
    }



    /**
     * Create a textarea input field.
     */
    public function textarea($name, $value = null, $options = array())
    {
        $options = $this->appendClassToOptions('form-control', $options);

        return $this->appendDiv(parent::textarea($name, $value, $options));
    }


    /**
     * Create a plain form input field.
     */
    public function plainInput($type, $name, $value = null, $options = array())
    {
        return $this->appendDiv(parent::input($type, $name, $value, $options));
    }


    /**
     * Create a plain select box field.
     */
    public function plainSelect($name, $list = array(), $selected = null, $options = array())
    {
        return $this->appendDiv(parent::select($name, $list, $selected, $options));
    }


    public function open(array $options = array())
    {
        $options['class'] ='form_open'; // Note: this avoids form input fields with large distances
        return parent::open($options);
    }

    public function hr($value='')
    {
        return "<br><hr style=\"width: 97%; color: #D8D8D8; height: 1px; background-color:#D8D8D8;\"/>";
    }

    /**
     * Determine whether the form element with the given name
     * has any validation errors.
     */
    private function hasErrors($name)
    {
        if ( ! Session::has('errors'))
        {
            // If the session is not set, or the session doesn't contain
            // any errors, the form element does not have any errors
            // applied to it.
            return false;
        }

        // Get the errors from the session.
        $errors = Session::get('errors');

        // Check if the errors contain the form element with the given name.
        // This leverages Laravel's transformKey method to handle the
        // formatting of the form element's name.
        return $errors->has($this->transformKey($name));
    }


    /**
     * Get the formatted errors for the form element with the given name.
     */
    private function getFormattedErrors($name)
    {
        if ( ! $this->hasErrors($name))
        {
            // If the form element does not have any errors, return
            // an emptry string.
            return '';
        }
        // Get the errors from the session.
        $errors = Session::get('errors');

        // dd(\App::getLocale());


        // Return the formatted error message, if the form element has any.
        return $errors->first($this->transformKey($name), '<p align="left" class="help-block">:message</p>');
    }


    /**
     * Open a new form group.
     */
    public function openGroup($name, $label = null, $options = array(), $color = false)
    {
        $options = $this->appendClassToOptions('form-group', $options);

        // dd($name, $label);
        // Append the name of the group to the groupStack.
        $this->groupStack[] = $name;

        if ($this->hasErrors($name))
        {
            // If the form element with the given name has any errors,
            // apply the 'has-error' class to the group.
            $options = $this->appendClassToOptions('has-error', $options);
        }

        // If a label is given, we set it up here. Otherwise, we will just
        // set it to an empty string.
        // NOTE: margin-top style moves label down to same horizontal
        //       line like html fields on right side (Torsten Schmidt)
        $label = $label ? $this->label($name, $label, ['style' => 'margin-top: 10px;']) : '';

        return $this->openDivClass(12, $color).'<div'.$this->html->attributes($options).'>'.$label;
    }


    /**
     * Close out the last opened form group.
     */
    public function closeGroup()
    {
        // Get the last added name from the groupStack and
        // remove it from the array.
        $name = array_pop($this->groupStack);

        // Get the formatted errors for this form group.
        $errors = $this->getFormattedErrors($name);

        // Get Layout col-md Setting
        $col = 4;
        if (isset(static::$layout_form_col_md['label']))
            $col = static::$layout_form_col_md['label'];

        // Append the errors to the group and close it out.
        return '<div class=col-md-'.$col.'></div><div class=col-md-'.(12-$col).'>'.$errors.'</div></div>'.$this->closeDivClass();
    }


    public function openDivClass($col = 9, $color=false)
    {
        if ($color)
            return '<div class="col-md-'.$col.'" style="background-color:'.$color.'">';

        return '<div class="col-md-'.$col.'">';
    }

    public function closeDivClass()
    {
        return '</div>';
    }



}