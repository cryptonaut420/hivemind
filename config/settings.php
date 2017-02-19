<?php

return array(
    'burn_requirement' => 5600, //amount of BTC to burn in order to activate account, in satoshis
    'burn_timeout' => 60*60*24*3, //amount of time in seconds to wait for them to activate account
    'max_post_tags' => 3, //max tags that can be applied per submitted link/post
    'post_rating_grades' => array( //weighting etc. for voting stuff
        0 => array(
                'name' => 'Relevence and/or Importance',
                'slug' => 'relevence',
                'weight' => 4,
                'order' => 1,
            ),
        1 => array(
                'name' => 'Post Quality',
                'slug' => 'quality',
                'weight' => 2,
                'order' => 2,
            ),
        2 => array(
                'name' => 'Biased vs. Unbiased/Informative',
                'slug' => 'biased',
                'weight' => 1,
                'order' => 3,
            ),
        3 => array(
                'name' => 'Enjoyment or Entertainment',
                'slug' => 'entertainment',
                'weight' => 0.5,
                'order' => 4,
            ),
    
    ),
    'comment_rating_grades' => array(
        0 => array(
                'name' => 'Relevence and/or Importance',
                'slug' => 'relevence',
                'weight' => 4,
                'order' => 1,
            ),
        1 => array(
                'name' => 'Comment  Quality',
                'slug' => 'quality',
                'weight' => 2,
                'order' => 2,
            ),
    ),


);
