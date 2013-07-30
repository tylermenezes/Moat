<?php

/**
 * DB related CLI functionality
 * 
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes. Released under the BSD license.
 *
 */
class db {
    use \ThinTasks\Task;

    public function init()
    {
        if (!\TinyDb\Query::table_exists('users')) {
            echo "  * Creating table users.\n";
            \TinyDb\Query::create_table('users', [
                'userID' => [
                    'type' => 'int',
                    'auto_increment' => true,
                    'key' => 'primary'
                ],
                'username' => [
                    'type' => 'varchar(255)',
                    'key' => 'unique'
                ],
                'password' => [
                    'type' => 'text'
                ],
                'password_reset_required' => [
                    'type' => 'boolean'
                ],
                'first_name' => [
                    'type' => 'varchar(255)'
                ],
                'last_name' => [
                    'type' => 'varchar(255)'
                ],
                'bio' => [
                    'type' => 'text',
                    'null' => true
                ],
                'email' => [
                    'type' => 'varchar(255)'
                ],
                'phone' => [
                    'type' => 'varchar(255)'
                ],
                'photo' => [
                    'type' => 'varchar(255)',
                    'null' => true
                ],
                'created_at' => [
                    'type' => 'datetime'
                ],
                'modified_at' => [
                    'type' => 'datetime'
                ]
            ]);
        }

        if (!\TinyDb\Query::table_exists('users_oauth')) {
            echo "  * Creating table users_oauth.\n";
            \TinyDb\Query::create_table('users_oauth', [
                'oauthID' => [
                    'type' => 'int',
                    'auto_increment' => true,
                    'key' => 'primary'
                ],
                'userID' => [
                    'type' => 'int',
                    'key' => 'unique'
                ],
                'service' => [
                    'type' => "enum('facebook', 'linkedin', 'twitter')",
                    'key' => 'unique'
                ],
                'service_user_id' => [
                    'type' => 'varchar(255)'
                ],
                'access_token' => [
                    'type' => 'varchar(255)'
                ],
                'expires_at' => [
                    'type' => 'datetime'
                ]
            ]);
        }

        if (!\TinyDb\Query::table_exists('cohorts')) {
            echo "  * Creating table cohorts.\n";
            \TinyDb\Query::create_table('cohorts', [
                'cohortID' => [
                    'type' => 'int',
                    'auto_increment' => true,
                    'key' => 'primary'
                ],
                'name' => [
                    'type' => 'varchar(255)'
                ],
                'slug' => [
                    'type' => 'varchar(255)',
                    'key' => 'unique'
                ]
            ]);
        }

        if (!\TinyDb\Query::table_exists('companies')) {
            echo "  * Creating table companies.\n";
            \TinyDb\Query::create_table('companies', [
                'companyID' => [
                    'type' => 'int',
                    'auto_increment' => true,
                    'key' => 'primary'
                ],
                'name' => [
                    'type' => 'varchar(255)'
                ],
                'description' => [
                    'type' => 'text'
                ],
                'cohortID' => [
                    'type' => 'int'
                ],
                'is_admin' => [
                    'type' => 'boolean',
                    'default' => false
                ],
                'is_adviser' => [
                    'type' => 'boolean',
                    'default' => false
                ],
                'created_at' => [
                    'type' => 'datetime'
                ],
                'modified_at' => [
                    'type' => 'datetime'
                ]
            ]);
        }

        if (!\TinyDb\Query::table_exists('users_companies')) {
            echo "  * Creating table users_companies.\n";
            \TinyDb\Query::create_table('users_companies', [
                'companyID' => [
                    'type' => 'int',
                    'key' => 'primary'
                ],
                'userID' => [
                    'type' => 'int',
                    'key' => 'primary'
                ],
                'created_at' => [
                    'type' => 'datetime'
                ]
            ]);
        }

        if (!\TinyDb\Query::table_exists('enrollment_codes')) {
            echo "  * Creating table enrollment_codes.\n";
            \TinyDb\Query::create_table('enrollment_codes', [
                'codeID' => [
                    'type' => 'int',
                    'auto_increment' => true,
                    'key' => 'primary'
                ],
                'code' => [
                    'type' => 'varchar(255)'
                ],
                'one_time_use' => [
                    'type' => 'boolean',
                    'default' => true
                ],
                'used_at' => [
                    'type' => 'datetime',
                    'null' => true
                ],
                'companyID' => [
                    'type' => 'int',
                    'null' => true
                ]
            ]);
        }

        if (!\TinyDb\Query::table_exists('deals')) {
            echo "  * Creating table deals.\n";
            \TinyDb\Query::create_table('deals', [
                'dealID' => [
                    'type' => 'int',
                    'auto_increment' => true,
                    'key' => 'primary'
                ],
                'company' => [
                    'type' => 'varchar(255)'
                ],
                'url' => [
                    'type' => 'varchar(255)'
                ],
                'details' => [
                    'type' => 'text'
                ],
                'redemption' => [
                    'type' => 'text'
                ],
                'approved' => [
                    'type' => 'boolean',
                    'default' => false
                ],
                'cohortID' => [
                    'type' => 'int',
                    'null' => true
                ]
            ]);
        }

        if (!\TinyDb\Query::table_exists('officehours_blocks')) {
            echo "  * Creating table officehours_blocks.\n";
            \TinyDb\Query::create_table('officehours_blocks', [
                'blockID' => [
                    'type' => 'int',
                    'auto_increment' => true,
                    'key' => 'primary'
                ],
                'starts_at' => [
                    'type' => 'datetime'
                ],
                'userID' => [
                    'type' => 'int'
                ],
                'description' => [
                    'type' => 'text',
                    'null' => true
                ]
            ]);
        }

        if (!\TinyDb\Query::table_exists('officehours_slots')) {
            echo "  * Creating table officehours_slots.\n";
            \TinyDb\Query::create_table('officehours_slots', [
                'slotID' => [
                    'type' => 'int',
                    'auto_increment' => true,
                    'key' => 'primary'
                ],
                'blockID' => [
                    'type' => 'int'
                ],
                'starts_at' => [
                    'type' => 'datetime'
                ],
                'ends_at' => [
                    'type' => 'datetime'
                ],
                'userID' => [
                    'type' => 'int',
                    'null' => true
                ],
                'sent_reminder' => [
                    'type' => 'boolean',
                    'default' => false
                ],
                'noshow' => [
                    'type' => 'boolean',
                    'default' => false
                ],
                'created_at' => [
                    'type' => 'datetime'
                ],
                'modified_at' => [
                    'type' => 'datetime'
                ]
            ]);
        }
    }
}
