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
 * @version 1.0, Oct 26, 2016 - 1:20:47 PM
 */
class CarouselItem
        extends RestaurantObject {

    private static $db = array(
        'Title' => 'Varchar(255)',
        'Subtitle' => 'Varchar(255)',
        'Description' => 'Text',
        'ExternalLink' => 'Varchar(255)',
    );
    private static $translate = array(
        'Title',
        'Subtitle',
        'Description',
    );
    private static $has_one = array(
        'Image' => 'Image',
        'Restaurant' => 'RestaurantPage',
    );
    private static $has_many = array(
    );
    private static $many_many = array(
    );
    private static $belongs_many_many = array(
    );
    private static $searchable_fields = array(
        'Title' => array(
            'field' => 'TextField',
            'filter' => 'PartialMatchFilter',
        ),
        'Description' => array(
            'field' => 'TextField',
            'filter' => 'PartialMatchFilter',
        ),
    );
    private static $summary_fields = array(
        'Image.StripThumbnail',
        'Title',
        'Subtitle',
        'Description',
    );

    public function fieldLabels($includerelations = true) {
        $labels = parent::fieldLabels($includerelations);

        $labels['Image.StripThumbnail'] = _t('CarouselItem.IMAGE', 'Image');
        $labels['Image'] = _t('CarouselItem.IMAGE', 'Image');
        
        $labels['Title'] = _t('CarouselItem.TITLE', 'Title');
        $labels['Subtitle'] = _t('CarouselItem.SUBTITLE', 'Subtitle');
        $labels['Description'] = _t('CarouselItem.DESCRIPTION', 'Description');
        $labels['ExternalLink'] = _t('CarouselItem.EXTERNAL_LINK', 'ExternalLink');
        $labels['Restaurant'] = _t('CarouselItem.RESTAURANT', 'Restaurant');

        return $labels;
    }

    public function getCMSFields() {
        $self = & $this;

        $this->beforeUpdateCMSFields(function ($fields) use ($self) {
            if ($field = $fields->fieldByName('Root.Main.Image')) {
                $field->getValidator()->setAllowedExtensions(array('jpg', 'jpeg', 'png', 'gif'));
                $field->setFolderName("chef");

                $fields->removeFieldFromTab('Root.Main', 'Image');
                $fields->addFieldToTab('Root.Main', $field);
            }

            $this->reorderField($fields, 'Title', 'Root.Main', 'Root.Main');
            $this->reorderField($fields, 'Subtitle', 'Root.Main', 'Root.Main');
            $this->reorderField($fields, 'Description', 'Root.Main', 'Root.Main');
            $this->reorderField($fields, 'ExternalLink', 'Root.Main', 'Root.Main');

            $this->reorderField($fields, 'RestaurantID', 'Root.Main', 'Root.Main');
        });

        $fields = parent::getCMSFields();

        return $fields;
    }

    function Link($action = null) {
        return parent::Link("carousel/$this->ID");
    }

}