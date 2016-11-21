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
 * @version 1.0, Oct 24, 2016 - 1:44:07 PM
 */
class Reservation
        extends RestaurantObject {

    private static $db = array(
        'Name' => 'Varchar(255)',
        'Email' => 'Varchar(255)',
        'PhoneNo' => 'Varchar(255)',
        'Date' => 'Date',
        'Time' => 'Varchar(255)',
        'People' => 'Varchar(255)',
        'Message' => 'Text',
    );
    private static $translate = array(
    );
    private static $has_one = array(
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
        'Email' => array(
            'field' => 'TextField',
            'filter' => 'PartialMatchFilter',
        ),
        'PhoneNo' => array(
            'field' => 'TextField',
            'filter' => 'PartialMatchFilter',
        ),
    );
    private static $summary_fields = array(
        'Name',
        'Email',
        'PhoneNo',
        'Date',
        'Time',
        'People',
        'Message',
    );

    public function fieldLabels($includerelations = true) {
        $labels = parent::fieldLabels($includerelations);
        $labels['Name'] = _t('Restaurant.NAME', 'Name');
        $labels['Email'] = _t('Restaurant.EMAIL', 'Email');
        $labels['PhoneNo'] = _t('Restaurant.PHONE_NUMBER', 'Phone Number');
        $labels['Date'] = _t('Restaurant.DATE', 'Date');
        $labels['Time'] = _t('Restaurant.TIME', 'Time');
        $labels['People'] = _t('Restaurant.NO_OF_PEOPLE', 'No. of People');
        $labels['Message'] = _t('Restaurant.YOUR_MESSAGE', 'Your Message');

        return $labels;
    }

    public function getCMSFields() {
        $self = & $this;

        $this->beforeUpdateCMSFields(function ($fields) use ($self) {
            $this->reorderField($fields, 'Name', 'Root.Main', 'Root.Main');
            $this->reorderField($fields, 'Email', 'Root.Main', 'Root.Main');
            $this->reorderField($fields, 'PhoneNo', 'Root.Main', 'Root.Main');

            if ($field = $self->reorderField($fields, 'Date', 'Root.Main', 'Root.Main')) {
                $field->setConfig('showcalendar', true);
            }

            $this->reorderField($fields, 'Time', 'Root.Main', 'Root.Main');
            $this->reorderField($fields, 'People', 'Root.Main', 'Root.Main');
            $this->reorderField($fields, 'Message', 'Root.Main', 'Root.Main');
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
                'Email',
                'PhoneNo',
            )
        ));

        $filters = array(
            'Name' => new PartialMatchFilter('Name'),
            'Email' => new PartialMatchFilter('Email'),
            'PhoneNo' => new PartialMatchFilter('PhoneNo'),
        );

        return new SearchContext(
                $this->class, $fields, $filters
        );
    }

}