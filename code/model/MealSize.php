<?php

/*
 * MIT License
 *  
 * Copyright (c) 2016 Hudhaifa Shatnawi
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Oct 25, 2016 - 10:54:07 AM
 */
class MealSize
        extends RestaurantObject {

    private static $db = array(
        'Size' => 'Varchar(255)',
        'Price' => 'Varchar(255)',
        'Description' => 'Varchar(255)',
    );
    private static $translate = array(
        'Size',
        'Description',
    );
    private static $has_one = array(
        'Meal' => 'Meal',
        'Photo' => 'Image',
    );
    private static $has_many = array(
    );
    private static $many_many = array(
    );
    private static $searchable_fields = array(
        'Size' => array(
            'field' => 'TextField',
            'filter' => 'PartialMatchFilter',
        ),
        'Price' => array(
            'field' => 'TextField',
            'filter' => 'PartialMatchFilter',
        ),
    );
    private static $summary_fields = array(
        'Photo.StripThumbnail',
        'Size',
        'Price',
        'Description',
    );

    public function fieldLabels($includerelations = true) {
        $labels = parent::fieldLabels($includerelations);

        $labels['Size'] = _t('MealSize.NAME', 'Name');
        $labels['Price'] = _t('MealSize.PRICE', 'Price');
        $labels['Description'] = _t('MealSize.DESCRIPTION', 'Description');

        $labels['Photo.StripThumbnail'] = _t('MealSize.PHOTO', 'Photo');
        $labels['Photo'] = _t('MealSize.PHOTO', 'Photo');
        $labels['Meal'] = _t('MealSize.MEAL', 'Meal');

        return $labels;
    }

    public function getCMSFields() {
        $self = & $this;

        $this->beforeUpdateCMSFields(function ($fields) use ($self) {
            $this->reorderField($fields, 'Size', 'Root.Main', 'Root.Main');
            $this->reorderField($fields, 'Price', 'Root.Main', 'Root.Main');
            $this->reorderField($fields, 'Description', 'Root.Main', 'Root.Main');

            if ($field = $fields->fieldByName('Root.Main.Photo')) {
                $field->getValidator()->setAllowedExtensions(array('jpg', 'jpeg', 'png', 'gif'));
                $field->setFolderName("chef");

                $fields->removeFieldFromTab('Root.Main', 'Photo');
                $fields->addFieldToTab('Root.Main', $field);
            }
        });

        $fields = parent::getCMSFields();

        return $fields;
    }

    public function getTitle() {
        return $this->Name;
    }

    function Link($action = null) {
        return parent::Link("reservation/$this->ID");
    }

}