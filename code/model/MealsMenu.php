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
 * @version 1.0, Oct 25, 2016 - 9:58:39 AM
 */
class MealsMenu
        extends RestaurantObject {

    private static $db = array(
        'Title' => 'Varchar(255)',
    );
    private static $translate = array(
        'Title'
    );
    private static $has_one = array(
        'Restaurant' => 'RestaurantPage',
    );
    private static $has_many = array(
    );
    private static $many_many = array(
    );
    private static $belongs_many_many = array(
        'Meals' => 'Meal',
    );
    private static $searchable_fields = array(
        'Title' => array(
            'field' => 'TextField',
            'filter' => 'PartialMatchFilter',
        ),
    );
    private static $summary_fields = array(
        'Title',
        'Meals.Count',
    );

    public function fieldLabels($includerelations = true) {
        $labels = parent::fieldLabels($includerelations);
        $labels['Title'] = _t('Chef.NAME', 'Title');
        $labels['Meals.Count'] = _t('Chef.NUMBER_OF_MEALS', 'Number Of Meals');

        return $labels;
    }

    function Link($action = null) {
        return parent::Link("reservation/$this->ID");
    }

    public function getRandomMeals() {
        $meals = array();

        foreach ($this->Meals()->sort('RAND()') as $book) {
            $meals[] = $volume;
        }

        return new ArrayList($meals);
    }

}