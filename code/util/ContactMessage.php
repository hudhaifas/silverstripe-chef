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
 * @version 1.0, Oct 26, 2016 - 11:44:10 AM
 */
class ContactMessage
        extends DataObject {

    private static $db = array(
        'Name' => 'Varchar(255)',
        'Email' => 'Varchar(255)',
        'Subject' => 'Varchar(255)',
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
        'Subject' => array(
            'field' => 'TextField',
            'filter' => 'PartialMatchFilter',
        ),
        'Message' => array(
            'field' => 'TextareaField',
            'filter' => 'PartialMatchFilter',
        ),
    );
    private static $summary_fields = array(
        'Name',
        'Email',
        'Subject',
        'Message',
        'Created.Nice',
    );

    public function fieldLabels($includerelations = true) {
        $labels = parent::fieldLabels($includerelations);

        $labels['Name'] = _t('ContactMessage.NAME', 'Name');
        $labels['Email'] = _t('ContactMessage.EMAIL', 'Email');
        $labels['Subject'] = _t('ContactMessage.SUBJECT', 'Subject');
        $labels['Message'] = _t('ContactMessage.MESSAGE', 'Message');
        $labels['Created.Nice'] = _t('ContactMessage.TIME', 'Time');

        return $labels;
    }

    public function getCMSFields() {
        $self = & $this;

        $this->beforeUpdateCMSFields(function ($fields) use ($self) {
            
        });

        $fields = parent::getCMSFields();

        return $fields;
    }

    public function getTitle() {
        return $this->Name;
    }

    public function getDefaultSearchContext() {
        $fields = $this->scaffoldSearchFields(array(
            'restrictFields' => array(
                'Name',
                'Email',
                'Subject',
                'Message',
            )
        ));

        $filters = array(
            'Name' => new PartialMatchFilter('Name'),
            'Email' => new PartialMatchFilter('Email'),
            'Subject' => new PartialMatchFilter('Subject'),
            'Message' => new PartialMatchFilter('Message'),
        );

        return new SearchContext(
                $this->class, $fields, $filters
        );
    }

}