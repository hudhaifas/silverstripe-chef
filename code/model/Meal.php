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
 * @version 1.0, Oct 25, 2016 - 9:59:44 AM
 */
class Meal
        extends RestaurantObject {

    private static $db = array(
        'Name' => 'Varchar(255)',
        'Price' => 'Varchar(255)',
        'Origin' => 'Varchar(255)',
        'Description' => 'Text',
    );
    private static $translate = array(
        'Name',
        'Origin',
        'Description',
    );
    private static $has_one = array(
        'Photo' => 'Image',
    );
    private static $has_many = array(
        'Sizes' => 'MealSize',
    );
    private static $many_many = array(
        'Menus' => 'MealsMenu',
    );
    private static $searchable_fields = array(
        'Name' => array(
            'field' => 'TextField',
            'filter' => 'PartialMatchFilter',
        ),
        'Price' => array(
            'field' => 'TextField',
            'filter' => 'PartialMatchFilter',
        ),
        'Origin' => array(
            'field' => 'TextField',
            'filter' => 'PartialMatchFilter',
        ),
    );
    private static $summary_fields = array(
        'Photo.StripThumbnail',
        'Name',
        'Price',
        'Origin',
        'Description',
    );

    public function fieldLabels($includerelations = true) {
        $labels = parent::fieldLabels($includerelations);

        $labels['Name'] = _t('Meal.NAME', 'Name');
        $labels['Price'] = _t('Meal.PRICE', 'Price');
        $labels['Origin'] = _t('Meal.ORIGIN', 'Origin');
        $labels['Description'] = _t('Meal.DESCRIPTION', 'Description');

        $labels['Photo.StripThumbnail'] = _t('Meal.PHOTO', 'Photo');
        $labels['Photo'] = _t('Meal.PHOTO', 'Photo');
        $labels['Menus'] = _t('Meal.MENUS', 'Menus');
        $labels['Sizes'] = _t('Meal.SIZES', 'Sizes');
        
        return $labels;
    }

    public function getCMSFields() {
        $self = & $this;

        $this->beforeUpdateCMSFields(function ($fields) use ($self) {
            $this->reorderField($fields, 'Name', 'Root.Main', 'Root.Main');
            $this->reorderField($fields, 'Price', 'Root.Main', 'Root.Main');
            $this->reorderField($fields, 'Origin', 'Root.Main', 'Root.Main');
            $this->reorderField($fields, 'Description', 'Root.Main', 'Root.Main');

            $menusField = TagField::create(
                            'Menus', 'Menus', MealsMenu::get(), $self->Menus()
            );

            $fields->removeFieldFromTab('Root', 'Menus');

            $fields->addFieldToTab('Root.Main', $menusField);

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

    public function getDefaultSearchContext() {
        $fields = $this->scaffoldSearchFields(array(
            'restrictFields' => array(
                'Name',
                'Price',
                'Origin',
            )
        ));

        $filters = array(
            'Name' => new PartialMatchFilter('Name'),
            'Price' => new PartialMatchFilter('Price'),
            'Origin' => new PartialMatchFilter('Origin'),
        );

        return new SearchContext(
                $this->class, $fields, $filters
        );
    }

}