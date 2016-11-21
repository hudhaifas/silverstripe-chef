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
 * @version 1.0, Oct 25, 2016 - 2:29:26 PM
 */
class Chef
        extends RestaurantObject {

    private static $db = array(
        'Name' => 'Varchar(255)',
        'Position' => 'Varchar(255)',
        'Portfolio' => 'HTMLText',
        'Facebook' => 'Varchar(255)',
        'Twitter' => 'Varchar(255)',
        'GooglePlus' => 'Varchar(255)',
        'LinkedIn' => 'Varchar(255)'
    );
    private static $translate = array(
        'Name',
        'Position',
        'Portfolio',
    );
    private static $has_one = array(
        'Photo' => 'Image',
        'Restaurant' => 'RestaurantPage',
    );
    private static $has_many = array(
    );
    private static $many_many = array(
    );
    private static $searchable_fields = array(
        'Name' => array(
            'field' => 'TextField',
            'filter' => 'PartialMatchFilter',
        ),
        'Position' => array(
            'field' => 'TextField',
            'filter' => 'PartialMatchFilter',
        ),
    );
    private static $summary_fields = array(
        'Photo.StripThumbnail',
        'Name',
        'Position',
    );

    public function fieldLabels($includerelations = true) {
        $labels = parent::fieldLabels($includerelations);

        $labels['Photo.StripThumbnail'] = _t('Restaurant.PHOTO', 'Photo');
        $labels['Name'] = _t('Chef.NAME', 'Name');
        $labels['Position'] = _t('Chef.POSITION', 'Position');
        $labels['Facebook'] = _t('Chef.FACEBOOK', 'Facebook');
        $labels['Twitter'] = _t('Chef.TWITTER', 'Twitter');
        $labels['GooglePlus'] = _t('Chef.GOOGLE_PLUS', 'Google Plus');
        $labels['LinkedIn'] = _t('Chef.LINKEDIN', 'LinkedIn');

        return $labels;
    }

    public function getCMSFields() {
        $self = & $this;

        $this->beforeUpdateCMSFields(function ($fields) use ($self) {
            $this->reorderField($fields, 'Name', 'Root.Main', 'Root.Main');
            $this->reorderField($fields, 'Position', 'Root.Main', 'Root.Main');
            $this->reorderField($fields, 'Portfolio', 'Root.Main', 'Root.Main');

            if ($field = $fields->fieldByName('Root.Main.Photo')) {
                $field->getValidator()->setAllowedExtensions(array('jpg', 'jpeg', 'png', 'gif'));
                $field->setFolderName("chef");

                $fields->removeFieldFromTab('Root.Main', 'Photo');
                $fields->addFieldToTab('Root.Main', $field);
            }
            
            $this->reorderField($fields, 'RestaurantID', 'Root.Main', 'Root.Main');

            // Social
            $this->reorderField($fields, 'Facebook', 'Root.Main', 'Root.Social');
            $this->reorderField($fields, 'Twitter', 'Root.Main', 'Root.Social');
            $this->reorderField($fields, 'GooglePlus', 'Root.Main', 'Root.Social');
            $this->reorderField($fields, 'LinkedIn', 'Root.Main', 'Root.Social');
        });

        $fields = parent::getCMSFields();

        return $fields;
    }

    public function getTitle() {
        return $this->Name;
    }

    function Link($action = null) {
        return parent::Link("chef/$this->ID");
    }

    public function getDefaultSearchContext() {
        $fields = $this->scaffoldSearchFields(array(
            'restrictFields' => array(
                'Name',
                'Position',
            )
        ));

        $filters = array(
            'Name' => new PartialMatchFilter('Name'),
            'Position' => new PartialMatchFilter('Email'),
        );

        return new SearchContext(
                $this->class, $fields, $filters
        );
    }

}