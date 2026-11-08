<?php

use function Safe\preg_match;

/// Collector Rules collection class
class RuleMailCollectorCollection extends RuleCollection
{
    // From RuleCollection
    public bool $stop_on_first_match = true;
    public static string $rightname           = 'rule_mailcollector';
    public string $menu_option         = 'mailcollector';


    public function getTitle()
    {
        return __('Rules for assigning a ticket created through a mails receiver');
    }

    public function prepareInputDataForProcess($input, $params)
    {
        if (isset($params['mailcollector'])) {
            $input['mailcollector'] = $params['mailcollector'];
        }
        if (isset($params['_users_id_requester'])) {
            $input['_users_id_requester'] = $params['_users_id_requester'];
        }
        if (isset($params['from'])) {
            $input['from'] = $params['from'];
        }

        $fields = $this->getFieldsToLookFor();

        // Add needed ticket datas for rules processing
        if (isset($params['ticket']) && is_array($params['ticket'])) {
            foreach ($params['ticket'] as $key => $value) {
                if (in_array($key, $fields, true) && !isset($input[$key])) {
                    $input[$key] = $value;
                }
            }
        }

        // Add needed headers for rules processing
        if (isset($params['headers']) && is_array($params['headers'])) {
            foreach ($params['headers'] as $key => $value) {
                if (in_array($key, $fields, true) && !isset($input[$key])) {
                    $input[$key] = $value;
                }
            }
            // Match "to" against every recipient individually (not the flattened,
            // comma-joined string), so "is"/"is not"/regex conditions still work
            // correctly when the email has more than one recipient.
            if (in_array('to', $fields, true) && !empty($params['headers']['tos'])) {
                $input['to'] = $params['headers']['tos'];
            }
            $input['_headers'] = implode(
                "\n",
                array_map(
                    fn($k, $v) => is_array($v) ? "$k: " . implode(', ', $v) : "$k: $v",
                    array_keys($params['headers']),
                    $params['headers']
                )
            );
        }

        // Add all user's groups
        if (in_array('_groups_id_requester', $fields, true)) {
            foreach (Group_User::getUserGroups($input['_users_id_requester']) as $group) {
                $input['_groups_id_requester'][] = $group['id'];
            }
        }

        // Add all user's profiles
        if (in_array('profiles', $fields, true)) {
            foreach (Profile_User::getForUser($input['_users_id_requester']) as $profile) {
                $input['PROFILES'][$profile['profiles_id']] = $profile['profiles_id'];
            }
        }

        // If the criteria is "user has only one time the profile xxx"
        if (in_array('unique_profile', $fields, true)) {
            //Get all profiles
            $profiles = Profile_User::getForUser($input['_users_id_requester']);
            foreach ($profiles as $profile) {
                if (
                    Profile_User::haveUniqueRight(
                        $input['_users_id_requester'],
                        $profile['profiles_id']
                    )
                ) {
                    $input['UNIQUE_PROFILE'][$profile['profiles_id']] = $profile['profiles_id'];
                }
            }
        }

        // Store the number of profiles of which the user belongs to
        if (in_array('one_profile', $fields, true)) {
            $profiles = Profile_User::getForUser($input['_users_id_requester']);
            if (count($profiles) === 1) {
                $tmp = array_pop($profiles);
                $input['ONE_PROFILE'] = $tmp['profiles_id'];
            }
        }

        if (in_array('known_domain', $fields, true)) {
            if (preg_match("/@(.*)/", $input['from'] ?? '', $results)) {
                if (Entity::getEntityIDByDomain($results[1]) !== -1) {
                    $input['KNOWN_DOMAIN'] = 1;
                } else {
                    $input['KNOWN_DOMAIN'] = 0;
                }
            }
        }

        return $input;
    }

    public function canList()
    {
        return static::canView() && MailCollector::countCollectors();
    }
}
