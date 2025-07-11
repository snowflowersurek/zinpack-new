<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * The top-level class of the "Options" subtree of the object-oriented
 * properties system (the other subtree is "Plugin").
 *
 * @package PhpMyAdmin
 */
if (! defined('PHPMYADMIN')) {
    exit;
}

/* This class extends the PropertyItem class */
require_once 'libraries/properties/PropertyItem.class.php';

/**
 * Superclass for
 *  - OptionsPropertyOneItem and
 *  - OptionsProperty Group
 *
 * @package PhpMyAdmin
 */
abstract class OptionsPropertyItem extends PropertyItem
{
    /**
     * Name
     *
     * @var string
     */
    private $_name;

    /**
     * Text
     *
     * @var string
     */
    private $_text;

    /**
     * What to force
     *
     * @var string
     */
    private $_force;



    /* ~~~~~~~~~~~~~~~~~~~~ Getters and Setters ~~~~~~~~~~~~~~~~~~~~ */


    /**
     * Gets the name
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Sets the name
     *
     * @param string $name name
     *
     * @return void
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * Gets the text
     *
     * @return string
     */
    public function getText()
    {
        return $this->_text;
    }

    /**
     * Sets the text
     *
     * @param string $text text
     *
     * @return void
     */
    public function setText($text)
    {
        $this->_text = $text;
    }

    /**
     * Gets the force parameter
     *
     * @return string
     */
    public function getForce()
    {
        return $this->_force;
    }

    /**
     * Sets the force paramter
     *
     * @param string $force force parameter
     *
     * @return void
     */
    public function setForce($force)
    {
        $this->_force = $force;
    }

    /**
     * Returns the property type ( either "options", or "plugin" ).
     *
     * @return string
     */
    public function getPropertyType()
    {
        return "options";
    }
}
?>



