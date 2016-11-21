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
 * @version 1.0, Oct 26, 2016 - 1:40:49 PM
 */
class AboutItem
        extends RestaurantObject {

    private static $db = array(
        'About' => 'HTMLText',
    );
    private static $translate = array(
        'About',
    );
    private static $has_one = array(
        'Image1' => 'Image',
        'Image2' => 'Image',
        'Image3' => 'Image',
        'Restaurant' => 'RestaurantPage',
    );
    private static $has_many = array(
    );
    private static $many_many = array(
    );
    private static $belongs_many_many = array(
    );
    private static $searchable_fields = array(
    );
    private static $summary_fields = array(
        'About',
    );

    public function fieldLabels($includerelations = true) {
        $labels = parent::fieldLabels($includerelations);

        $labels['About'] = _t('Chef.ABOUT', 'About');

        return $labels;
    }

    public function getCMSFields() {
        $self = & $this;

        $this->beforeUpdateCMSFields(function ($fields) use ($self) {
            $this->reorderField($fields, 'About', 'Root.Main', 'Root.Main');

            if ($field = $fields->fieldByName('Root.Main.Image1')) {
                $field->getValidator()->setAllowedExtensions(array('jpg', 'jpeg', 'png', 'gif'));
                $field->setFolderName("chef");

                $fields->removeFieldFromTab('Root.Main', 'Image1');
                $fields->addFieldToTab('Root.Images', $field);
            }

            if ($field = $fields->fieldByName('Root.Main.Image2')) {
                $field->getValidator()->setAllowedExtensions(array('jpg', 'jpeg', 'png', 'gif'));
                $field->setFolderName("chef");

                $fields->removeFieldFromTab('Root.Main', 'Image2');
                $fields->addFieldToTab('Root.Images', $field);
            }

            if ($field = $fields->fieldByName('Root.Main.Image3')) {
                $field->getValidator()->setAllowedExtensions(array('jpg', 'jpeg', 'png', 'gif'));
                $field->setFolderName("chef");

                $fields->removeFieldFromTab('Root.Main', 'Image3');
                $fields->addFieldToTab('Root.Images', $field);
            }
        });

        $fields = parent::getCMSFields();

        return $fields;
    }

    public function getTitle() {
        return $this->trunc($this->About, 4);
    }

    public function toString() {
        return $this->About;
    }

    function trunc($phrase, $max_words) {
        $phrase_array = explode(' ', $phrase);
        if (count($phrase_array) > $max_words && $max_words > 0)
            $phrase = implode(' ', array_slice($phrase_array, 0, $max_words)) . '...';
        return $phrase;
    }

}