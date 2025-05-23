<?php

/**
 * HumHub
 * Copyright © 2014 The HumHub Project
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 */
return [

    // User 1 & 3  are Member of Space 1
    ['space_id' => '1', 'user_id' => '1', 'originator_user_id' => null, 'status' => '3', 'request_message' => null, 'last_visit' => '2014-08-08 06:49:57', 'group_id' => 'admin', 'created_at' => '2014-08-08 05:36:05', 'created_by' => '1', 'updated_at' => '2014-08-08 05:36:05', 'updated_by' => '1'],
    ['space_id' => '1', 'user_id' => '3', 'originator_user_id' => null, 'status' => '3', 'request_message' => null, 'last_visit' => null, 'group_id' => 'member', 'created_at' => '2014-08-10 16:55:41', 'created_by' => null, 'updated_at' => null, 'updated_by' => null],

    // User 1 is Member/Admin of Space 2
    ['space_id' => '2', 'user_id' => '2', 'originator_user_id' => null, 'status' => '3', 'request_message' => null, 'last_visit' => '2014-08-08 06:49:57', 'group_id' => 'admin', 'created_at' => '2014-08-08 05:36:05', 'created_by' => '1', 'updated_at' => '2014-08-08 05:36:05', 'updated_by' => '1'],

    // Admin is admin of space 3 and user 1 & 2 are members
    ['space_id' => '3', 'user_id' => '1', 'originator_user_id' => null, 'status' => '3', 'request_message' => null, 'last_visit' => '2014-08-08 06:49:57', 'group_id' => 'admin', 'created_at' => '2014-08-08 05:36:05', 'created_by' => '1', 'updated_at' => '2014-08-08 05:36:05', 'updated_by' => '1'],
    ['space_id' => '3', 'user_id' => '2', 'originator_user_id' => null, 'status' => '3', 'send_notifications' => '1', 'request_message' => null, 'last_visit' => null, 'group_id' => 'member', 'created_at' => '2014-08-10 16:55:41', 'created_by' => null, 'updated_at' => null, 'updated_by' => null],
    ['space_id' => '3', 'user_id' => '3', 'originator_user_id' => null, 'status' => '3', 'request_message' => null, 'last_visit' => '2014-08-08 06:49:57', 'group_id' => 'moderator', 'created_at' => '2014-08-08 05:36:05', 'created_by' => '1', 'updated_at' => '2014-08-08 05:36:05', 'updated_by' => '1'],

    // User 1/2 is admin of space 3 and user 3 is members
    ['space_id' => '4', 'user_id' => '1', 'originator_user_id' => null, 'status' => '3', 'request_message' => null, 'last_visit' => '2014-08-08 06:49:57', 'group_id' => 'admin', 'created_at' => '2014-08-08 05:36:05', 'created_by' => '1', 'updated_at' => '2014-08-08 05:36:05', 'updated_by' => '1'],
    ['space_id' => '4', 'user_id' => '2', 'originator_user_id' => null, 'status' => '3', 'request_message' => null, 'last_visit' => null, 'group_id' => 'admin', 'created_at' => '2014-08-10 16:55:41', 'created_by' => null, 'updated_at' => null, 'updated_by' => null],
    ['space_id' => '4', 'user_id' => '3', 'originator_user_id' => null, 'status' => '3', 'request_message' => null, 'last_visit' => '2014-08-08 06:49:57', 'group_id' => 'member', 'created_at' => '2014-08-08 05:36:05', 'created_by' => '1', 'updated_at' => '2014-08-08 05:36:05', 'updated_by' => '1'],

    ['space_id' => '5', 'user_id' => '1', 'originator_user_id' => null, 'status' => '3', 'request_message' => null, 'last_visit' => '2014-08-08 06:49:57', 'group_id' => 'admin', 'created_at' => '2014-08-08 05:36:05', 'created_by' => '1', 'updated_at' => '2014-08-08 05:36:05', 'updated_by' => '1'],
    ['space_id' => '5', 'user_id' => '2', 'originator_user_id' => null, 'status' => '3', 'request_message' => null, 'last_visit' => '2014-08-08 06:49:57', 'group_id' => 'member', 'created_at' => '2014-08-08 05:36:05', 'created_by' => '1', 'updated_at' => '2014-08-08 05:36:05', 'updated_by' => '1'],

];
